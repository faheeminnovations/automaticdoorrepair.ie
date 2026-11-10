<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$page_title = 'SEO Report';

$siteId = (int)($_GET['site_id'] ?? 0);
$showCombined = !$siteId;

// Get site info
$site = null;
if ($siteId) {
    $stmt = db()->prepare("SELECT * FROM seo_sites WHERE id = ?");
    $stmt->bind_param('i', $siteId);
    $stmt->execute();
    $site = $stmt->get_result()->fetch_assoc();
}

// Get pages
$pages = [];
if ($siteId) {
    $stmt = db()->prepare("SELECT * FROM seo_pages WHERE site_id = ? ORDER BY word_count DESC");
    $stmt->bind_param('i', $siteId);
    $stmt->execute();
    $r = $stmt->get_result();
} else {
    $r = db()->query("SELECT p.*, s.name as site_name FROM seo_pages p JOIN seo_sites s ON p.site_id = s.id ORDER BY s.name, p.word_count DESC");
}
if ($r) while ($row = $r->fetch_assoc()) $pages[] = $row;

// Get issues
$issues = [];
if ($siteId) {
    $stmt = db()->prepare("SELECT i.*, p.url as page_url FROM seo_issues i LEFT JOIN seo_pages p ON i.page_id = p.id WHERE i.site_id = ? ORDER BY i.severity ASC");
    $stmt->bind_param('i', $siteId);
    $stmt->execute();
    $r = $stmt->get_result();
} else {
    $r = db()->query("SELECT i.*, p.url as page_url, s.name as site_name FROM seo_issues i LEFT JOIN seo_pages p ON i.page_id = p.id LEFT JOIN seo_sites s ON i.site_id = s.id ORDER BY i.severity ASC");
}
if ($r) while ($row = $r->fetch_assoc()) $issues[] = $row;

// Stats
$stats = [
    'total_pages'   => count($pages),
    'total_issues'  => count($issues),
    'critical'      => count(array_filter($issues, fn($i) => $i['severity'] === 'critical')),
    'warnings'      => count(array_filter($issues, fn($i) => $i['severity'] === 'warning')),
    'avg_words'     => $pages ? round(array_sum(array_column($pages, 'word_count')) / count($pages)) : 0,
    'missing_titles'=> count(array_filter($pages, fn($p) => empty($p['meta_title']))),
    'missing_descs' => count(array_filter($pages, fn($p) => empty($p['meta_desc']))),
    'missing_h1'    => count(array_filter($pages, fn($p) => empty($p['h1']))),
];

$issueLabels = [
    'duplicate_title'       => 'Duplicate Title',
    'duplicate_desc'        => 'Duplicate Description',
    'duplicate_h1'          => 'Duplicate H1',
    'duplicate_content'     => 'Duplicate Content',
    'missing_title'         => 'Missing Title',
    'missing_desc'          => 'Missing Description',
    'missing_h1'            => 'Missing H1',
    'title_too_long'        => 'Title Too Long',
    'title_too_short'       => 'Title Too Short',
    'desc_too_long'         => 'Description Too Long',
    'desc_too_short'        => 'Description Too Short',
    'keyword_cannibalization'=> 'Keyword Cannibalization',
    'missing_canonical'     => 'Missing Canonical',
    'low_word_count'        => 'Low Word Count',
    'broken_page'           => 'Broken Page',
];

require_once __DIR__ . '/includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0" style="font-size:18px;font-weight:700;color:var(--ap);">
        <?= $showCombined ? 'Combined SEO Comparison Report' : 'SEO Report — ' . htmlspecialchars($site['name'] ?? '') ?>
    </h2>
    <div>
        <?php if ($siteId): ?>
        <a href="seo-report.php" class="btn-view" style="font-size:13px;"><i class="bi bi-files me-1"></i>View Combined</a>
        <?php endif; ?>
        <a href="seo-audit.php" class="btn-view" style="font-size:13px;"><i class="bi bi-arrow-left me-1"></i>Back to Audit</a>
    </div>
</div>

<!-- Stats -->
<div class="stat-cards">
    <div class="stat-card">
        <div class="num"><?= $stats['total_pages'] ?></div>
        <div class="lbl">Pages Audited</div>
    </div>
    <div class="stat-card">
        <div class="num" style="color:#dc3545;"><?= $stats['critical'] ?></div>
        <div class="lbl">Critical Issues</div>
    </div>
    <div class="stat-card">
        <div class="num" style="color:#ffc107;"><?= $stats['warnings'] ?></div>
        <div class="lbl">Warnings</div>
    </div>
    <div class="stat-card">
        <div class="num"><?= $stats['avg_words'] ?></div>
        <div class="lbl">Avg Words/Page</div>
    </div>
</div>

<!-- Issues by type -->
<?php if (!empty($issues)): ?>
<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-exclamation-triangle"></i><h2>Issues Found (<?= count($issues) ?>)</h2></div>
    <div class="admin-card-body p-0">
        <div class="table-responsive" style="max-height:500px;overflow-y:auto;">
            <table class="table table-sm table-hover mb-0" style="font-size:13px;">
                <thead style="background:#fafafa;position:sticky;top:0;">
                    <tr>
                        <th style="padding:10px 16px;">Sev</th>
                        <th>Type</th>
                        <th>Website</th>
                        <th>Page</th>
                        <th>Message</th>
                        <th>Duplicate On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($issues as $issue):
                        $label = $issueLabels[$issue['issue_type']] ?? $issue['issue_type'];
                        $color = $issue['severity'] === 'critical' ? '#dc3545' : ($issue['severity'] === 'warning' ? '#ffc107' : '#17a2b8');
                    ?>
                    <tr>
                        <td style="padding:8px 16px;">
                            <span class="badge" style="background:<?= $color ?>;font-size:9px;text-transform:uppercase;"><?= $issue['severity'] ?></span>
                        </td>
                        <td><strong><?= $label ?></strong></td>
                        <td style="font-size:12px;color:var(--ap);"><?= htmlspecialchars($issue['site_name'] ?? '—') ?></td>
                        <td style="max-width:200px;word-break:break-all;font-size:11px;">
                            <a href="<?= htmlspecialchars($issue['page_url'] ?? '#') ?>" target="_blank"><?= htmlspecialchars(substr($issue['page_url'] ?? '', 0, 50)) ?>...</a>
                        </td>
                        <td style="max-width:300px;"><?= htmlspecialchars($issue['message']) ?></td>
                        <td style="max-width:200px;word-break:break-all;font-size:11px;color:#dc3545;">
                            <?= $issue['duplicate_url'] ? htmlspecialchars(substr($issue['duplicate_url'], 0, 50)) . '...' : '—' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Pages detail -->
<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-list-check"></i><h2>All Audited Pages (<?= count($pages) ?>)</h2></div>
    <div class="admin-card-body p-0">
        <div class="table-responsive" style="max-height:600px;overflow-y:auto;">
            <table class="table table-sm table-hover mb-0" style="font-size:12px;">
                <thead style="background:#fafafa;position:sticky;top:0;">
                    <tr>
                        <th style="padding:10px 16px;">URL</th>
                        <th>Site</th>
                        <th>Meta Title</th>
                        <th>Meta Description</th>
                        <th>H1</th>
                        <th>Words</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pages as $p): ?>
                    <tr>
                        <td style="padding:8px 16px;max-width:200px;word-break:break-all;">
                            <a href="<?= htmlspecialchars($p['url']) ?>" target="_blank" style="font-size:11px;"><?= htmlspecialchars(substr($p['url'], 0, 50)) ?>...</a>
                        </td>
                        <td style="font-size:11px;color:var(--ap);"><?= htmlspecialchars($p['site_name'] ?? '—') ?></td>
                        <td style="max-width:200px;">
                            <?= $p['meta_title'] ? htmlspecialchars(substr($p['meta_title'], 0, 60)) : '<span style="color:#dc3545;">MISSING</span>' ?>
                            <?php if ($p['meta_title'] && strlen($p['meta_title']) > 60): ?>
                            <span style="color:#ffc107;font-size:10px;">(<?= strlen($p['meta_title']) ?> chars)</span>
                            <?php endif; ?>
                        </td>
                        <td style="max-width:200px;">
                            <?= $p['meta_desc'] ? htmlspecialchars(substr($p['meta_desc'], 0, 80)) : '<span style="color:#dc3545;">MISSING</span>' ?>
                        </td>
                        <td style="max-width:150px;">
                            <?= $p['h1'] ? htmlspecialchars(substr($p['h1'], 0, 50)) : '<span style="color:#dc3545;">MISSING</span>' ?>
                        </td>
                        <td>
                            <span style="<?= $p['word_count'] < 300 ? 'color:#dc3545;' : 'color:#28a745;' ?>">
                                <?= $p['word_count'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($pages)): ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No pages audited yet. Run an audit first.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
