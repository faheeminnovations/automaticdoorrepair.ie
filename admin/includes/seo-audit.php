<?php
/**
 * SEO Audit Engine
 * Crawls sitemaps, fetches pages, extracts meta/H1, detects duplicates
 */

class SEOAudit {
    private mysqli $db;
    private int $siteId;
    private int $auditId;
    private array $pageTitles = [];
    private array $pageDescs  = [];
    private array $pageH1s    = [];
    private int $issueCount   = 0;
    private int $criticalCount = 0;
    private int $warningCount  = 0;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    /**
     * Run full audit for a site
     */
    public function auditSite(int $siteId): array {
        $this->siteId = $siteId;
        $site = $this->getSite($siteId);
        if (!$site) return ['error' => 'Site not found'];

        // Create audit record
        $stmt = $this->db->prepare("INSERT INTO seo_audits (site_id, status) VALUES (?, 'running')");
        $stmt->bind_param('i', $siteId);
        $stmt->execute();
        $this->auditId = $this->db->insert_id;

        // Clear old data for this site
        $stmt = $this->db->prepare("DELETE FROM seo_pages WHERE site_id = ?");
        $stmt->bind_param('i', $siteId);
        $stmt->execute();
        $stmt = $this->db->prepare("DELETE FROM seo_issues WHERE site_id = ?");
        $stmt->bind_param('i', $siteId);
        $stmt->execute();

        // Get URLs from sitemap or crawl
        $urls = [];
        if (!empty($site['sitemap_url'])) {
            $urls = $this->fetchSitemapUrls($site['sitemap_url']);
        }
        if (count($urls) < 2) {
            $urls = $this->crawlSite($site['url']);
        }

        // Fetch each page
        $pageCount = 0;
        foreach ($urls as $url) {
            if ($pageCount >= 100) break; // safety limit
            $this->fetchAndStorePage($url);
            $pageCount++;
        }

        // Run duplicate detection across ALL sites
        $this->detectDuplicates();

        // Complete audit
        $this->db->query("UPDATE seo_audits SET status='completed', total_pages={$pageCount}, total_issues={$this->issueCount}, critical_count={$this->criticalCount}, warning_count={$this->warningCount}, completed_at=NOW() WHERE id={$this->auditId}");
        $this->db->query("UPDATE seo_sites SET last_audited=NOW() WHERE id={$siteId}");

        return [
            'site'        => $site['name'],
            'pages'       => $pageCount,
            'issues'      => $this->issueCount,
            'critical'    => $this->criticalCount,
            'warnings'    => $this->warningCount,
        ];
    }

    private function getSite(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM seo_sites WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /**
     * Fetch URLs from XML sitemap
     */
    private function fetchSitemapUrls(string $sitemapUrl): array {
        $xml = @file_get_contents($sitemapUrl);
        if (!$xml) return [];

        $urls = [];
        $dom = new DOMDocument();
        @$dom->loadXML($xml);

        // Sitemap index (contains sub-sitemaps)
        $sitemaps = $dom->getElementsByTagName('sitemap');
        if ($sitemaps->length > 0) {
            foreach ($sitemaps as $sm) {
                $loc = $sm->getElementsByTagName('loc')->item(0);
                if ($loc) {
                    $subUrls = $this->fetchSitemapUrls(trim($loc->nodeValue));
                    $urls = array_merge($urls, $subUrls);
                }
            }
        }

        // URLs
        $urlNodes = $dom->getElementsByTagName('url');
        foreach ($urlNodes as $u) {
            $loc = $u->getElementsByTagName('loc')->item(0);
            if ($loc) $urls[] = trim($loc->nodeValue);
        }

        return array_unique($urls);
    }

    /**
     * Crawl site homepage and extract internal links
     */
    private function crawlSite(string $baseUrl): array {
        $html = @file_get_contents($baseUrl);
        if (!$html) return [$baseUrl];

        $urls = [$baseUrl];
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');

        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (empty($href) || $href[0] === '#') continue;
            $full = $this->resolveUrl($href, $baseUrl);
            if ($full && strpos($full, $baseUrl) === 0 && !in_array($full, $urls)) {
                $urls[] = $full;
            }
        }

        return array_slice(array_unique($urls), 0, 50);
    }

    private function resolveUrl(string $href, string $base): string {
        if (filter_var($href, FILTER_VALIDATE_URL)) return $href;
        if ($href[0] === '/') {
            $parsed = parse_url($base);
            return $parsed['scheme'] . '://' . $parsed['host'] . $href;
        }
        return rtrim($base, '/') . '/' . ltrim($href, '/');
    }

    /**
     * Fetch page, extract meta, store
     */
    private function fetchAndStorePage(string $url): void {
        $ctx = stream_context_create(['http' => ['timeout' => 10, 'header' => 'User-Agent: SEO-Audit-Bot']]);
        $html = @file_get_contents($url, false, $ctx);
        if (!$html) {
            $this->addIssue($url, 'broken_page', 'critical', 'Page could not be fetched');
            return;
        }

        // Extract meta
        $metaTitle = $this->extractMeta($html, 'title');
        $metaDesc  = $this->extractMetaName($html, 'description');
        $metaKw    = $this->extractMetaName($html, 'keywords');
        $h1        = $this->extractHeadings($html, 'h1');
        $h2        = $this->extractHeadings($html, 'h2');
        $words     = str_word_count(strip_tags($html));

        // Store page
        $stmt = $this->db->prepare(
            "INSERT INTO seo_pages (site_id, url, meta_title, meta_desc, meta_keywords, h1, h2, word_count, status_code, crawled_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 200, NOW())"
        );
        $stmt->bind_param('isssssis', $this->siteId, $url, $metaTitle, $metaDesc, $metaKw, $h1, $h2, $words);
        $stmt->execute();
        $pageId = $this->db->insert_id;

        // Per-page checks
        $this->checkPageSeo($url, $pageId, $metaTitle, $metaDesc, $h1, $words);

        // Track for cross-site duplicate detection
        if ($metaTitle) $this->pageTitles[$url] = $metaTitle;
        if ($metaDesc)  $this->pageDescs[$url]  = $metaDesc;
        if ($h1)        $this->pageH1s[$url]    = $h1;
    }

    private function extractMeta(string $html, string $tag): string {
        if ($tag === 'title') {
            if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
                return trim(html_entity_decode($m[1]));
            }
        }
        return '';
    }

    private function extractMetaName(string $html, string $name): string {
        if (preg_match('/<meta\s+name=["\']' . $name . '["\']\s+content=["\'](.*?)["\']/is', $html, $m)) {
            return trim(html_entity_decode($m[1]));
        }
        if (preg_match('/<meta\s+content=["\'](.*?)["\']\s+name=["\']' . $name . '["\']/is', $html, $m)) {
            return trim(html_entity_decode($m[1]));
        }
        return '';
    }

    private function extractHeadings(string $html, string $tag): string {
        $result = [];
        if (preg_match_all('/<' . $tag . '[^>]*>(.*?)<\/' . $tag . '>/is', $html, $m)) {
            foreach ($m[1] as $h) {
                $clean = trim(strip_tags(html_entity_decode($h)));
                if ($clean) $result[] = $clean;
            }
        }
        return implode(' | ', $result);
    }

    private function checkPageSeo(string $url, int $pageId, string $title, string $desc, string $h1, int $words): void {
        // Missing title
        if (empty($title)) {
            $this->addIssue($url, 'missing_title', 'critical', 'Missing meta title', null, $pageId);
        } elseif (strlen($title) > 60) {
            $this->addIssue($url, 'title_too_long', 'warning', 'Title is ' . strlen($title) . ' chars (max 60 recommended)', null, $pageId);
        } elseif (strlen($title) < 30) {
            $this->addIssue($url, 'title_too_short', 'info', 'Title is only ' . strlen($title) . ' chars (min 30 recommended)', null, $pageId);
        }

        // Missing description
        if (empty($desc)) {
            $this->addIssue($url, 'missing_desc', 'critical', 'Missing meta description', null, $pageId);
        } elseif (strlen($desc) > 160) {
            $this->addIssue($url, 'desc_too_long', 'warning', 'Description is ' . strlen($desc) . ' chars (max 160 recommended)', null, $pageId);
        } elseif (strlen($desc) < 70) {
            $this->addIssue($url, 'desc_too_short', 'info', 'Description is only ' . strlen($desc) . ' chars (min 70 recommended)', null, $pageId);
        }

        // Missing H1
        if (empty($h1)) {
            $this->addIssue($url, 'missing_h1', 'critical', 'Missing H1 heading', null, $pageId);
        }

        // Low word count
        if ($words > 0 && $words < 300) {
            $this->addIssue($url, 'low_word_count', 'warning', "Low content: {$words} words (min 300 recommended)", null, $pageId);
        }
    }

    /**
     * Detect duplicates across ALL sites
     */
    public function detectDuplicates(): void {
        // Get ALL pages from ALL sites
        $result = $this->db->query("SELECT p.id, p.url, p.meta_title, p.meta_desc, p.h1, s.name as site_name, s.id as site_id FROM seo_pages p JOIN seo_sites s ON p.site_id = s.id");
        if (!$result) return;

        $allPages = [];
        while ($row = $result->fetch_assoc()) {
            $allPages[] = $row;
        }

        // Group by title
        $byTitle = [];
        $byDesc  = [];
        $byH1    = [];
        foreach ($allPages as $p) {
            if (!empty($p['meta_title'])) $byTitle[strtolower(trim($p['meta_title']))][] = $p;
            if (!empty($p['meta_desc']))  $byDesc[strtolower(trim($p['meta_desc']))][]  = $p;
            if (!empty($p['h1']))         $byH1[strtolower(trim($p['h1']))][]         = $p;
        }

        // Duplicate titles
        foreach ($byTitle as $title => $pages) {
            if (count($pages) > 1) {
                foreach ($pages as $p) {
                    $others = array_filter($pages, fn($x) => $x['url'] !== $p['url']);
                    $dupUrls = implode(', ', array_map(fn($x) => $x['url'], $others));
                    $this->addIssue($p['url'], 'duplicate_title', 'critical',
                        "Duplicate meta title found on: $dupUrls", $dupUrls, (int)$p['id'], (int)$p['site_id']);
                }
            }
        }

        // Duplicate descriptions
        foreach ($byDesc as $desc => $pages) {
            if (count($pages) > 1) {
                foreach ($pages as $p) {
                    $others = array_filter($pages, fn($x) => $x['url'] !== $p['url']);
                    $dupUrls = implode(', ', array_map(fn($x) => $x['url'], $others));
                    $this->addIssue($p['url'], 'duplicate_desc', 'critical',
                        "Duplicate meta description found on: $dupUrls", $dupUrls, (int)$p['id'], (int)$p['site_id']);
                }
            }
        }

        // Duplicate H1
        foreach ($byH1 as $h1 => $pages) {
            if (count($pages) > 1) {
                foreach ($pages as $p) {
                    $others = array_filter($pages, fn($x) => $x['url'] !== $p['url']);
                    $dupUrls = implode(', ', array_map(fn($x) => $x['url'], $others));
                    $this->addIssue($p['url'], 'duplicate_h1', 'warning',
                        "Duplicate H1 found on: $dupUrls", $dupUrls, (int)$p['id'], (int)$p['site_id']);
                }
            }
        }

        // Keyword cannibalization — same H1 across different pages on the SAME site
        $bySiteH1 = [];
        foreach ($allPages as $p) {
            $key = $p['site_id'] . '|' . strtolower(trim($p['h1']));
            $bySiteH1[$key][] = $p;
        }
        foreach ($bySiteH1 as $key => $pages) {
            if (count($pages) > 1) {
                foreach ($pages as $p) {
                    $others = array_filter($pages, fn($x) => $x['url'] !== $p['url']);
                    $dupUrls = implode(', ', array_map(fn($x) => $x['url'], $others));
                    $this->addIssue($p['url'], 'keyword_cannibalization', 'warning',
                        "Keyword cannibalization: same H1 on multiple pages: $dupUrls", $dupUrls, (int)$p['id'], (int)$p['site_id']);
                }
            }
        }
    }

    private function addIssue(string $url, string $type, string $severity, string $message, ?string $dupUrl = null, ?int $pageId = null, ?int $siteId = null): void {
        $sid = $siteId ?? $this->siteId;
        $stmt = $this->db->prepare(
            "INSERT INTO seo_issues (site_id, page_id, issue_type, severity, message, duplicate_url)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('iissss', $sid, $pageId, $type, $severity, $message, $dupUrl);
        $stmt->execute();
        $this->issueCount++;
        if ($severity === 'critical') $this->criticalCount++;
        elseif ($severity === 'warning') $this->warningCount++;
    }
}
