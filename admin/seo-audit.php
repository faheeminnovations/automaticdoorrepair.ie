<?php
require_once __DIR__ . '/includes/config.php';
require_login();
require_once __DIR__ . '/includes/seo-audit.php';

$page_title = 'SEO Audit System';
$success = $error = '';

// ── Add site ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_site') {
    $name = trim($_POST['name'] ?? '');
    $url  = trim($_POST['url'] ?? '');
    $sitemap = trim($_POST['sitemap_url'] ?? '');
    $isMain = isset($_POST['is_main_site']) ? 1 : 0;
    if ($name && $url) {
        $stmt = db()->prepare("INSERT INTO seo_sites (name, url, sitemap_url, is_main_site) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $name, $url, $sitemap, $isMain);
        $stmt->execute();
        header('Location: seo-audit.php?added=1'); exit;
    } else {
        $error = 'Name and URL are required.';
    }
}

// ── Delete site ──────────────────────────────────────────────
if (isset($_GET['delete_site'])) {
    $stmt = db()->prepare("DELETE FROM seo_sites WHERE id = ?");
    $id = (int)$_GET['delete_site'];
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: seo-audit.php?deleted=1'); exit;
}

// ── Run audit ────────────────────────────────────────────────
if (isset($_GET['run_audit'])) {
    $siteId = (int)$_GET['run_audit'];
    $audit = new SEOAudit(db());
    $result = $audit->auditSite($siteId);
    header('Location: seo-audit.php?audit_done=' . urlencode($result['site'] ?? 'unknown')); exit;
}

if (isset($_GET['added']))      $success = 'Website added successfully.';
if (isset($_GET['deleted']))    $success = 'Website removed.';
if (isset($_GET['audit_done'])) $success = 'Audit completed for: ' . htmlspecialchars($_GET['audit_done']);

// ── Load data ────────────────────────────────────────────────
$sites  = [];
$r = db()->query("SELECT * FROM seo_sites ORDER BY is_main_site DESC, name ASC");
if ($r) while ($row = $r->fetch_assoc()) $sites[] = $row;

$issues = [];
$r = db()->query("SELECT i.*, s.name as site_name, p.url as page_url
                  FROM seo_issues i
                  LEFT JOIN seo_sites s ON i.site_id = s.id
                  LEFT JOIN seo_pages p ON i.page_id = p.id
                  ORDER BY i.severity ASC, i.created_at DESC
                  LIMIT 200");
if ($r) while ($row = $r->fetch_assoc()) $issues[] = $row;

$audits = [];
$r = db()->query("SELECT a.*, s.name as site_name FROM seo_audits a LEFT JOIN seo_sites s ON a.site_id = s.id ORDER BY a.started_at DESC LIMIT 20");
if ($r) while ($row = $r->fetch_assoc()) $audits[] = $row;

// Issue type labels
$issueLabels = [
    'duplicate_title'       => ['Duplicate Title', 'critical'],
    'duplicate_desc'        => ['Duplicate Description', 'critical'],
    'duplicate_h1'          => ['Duplicate H1', 'warning'],
    'duplicate_content'     => ['Duplicate Content', 'critical'],
    'missing_title'         => ['Missing Title', 'critical'],
    'missing_desc'          => ['Missing Description', 'critical'],
    'missing_h1'            => ['Missing H1', 'critical'],
    'title_too_long'        => ['Title Too Long', 'warning'],
    'title_too_short'       => ['Title Too Short', 'info'],
    'desc_too_long'         => ['Description Too Long', 'warning'],
    'desc_too_short'        => ['Description Too Short', 'info'],
    'keyword_cannibalization'=> ['Keyword Cannibalization', 'warning'],
    'missing_canonical'     => ['Missing Canonical', 'warning'],
    'low_word_count'        => ['Low Word Count', 'warning'],
    'broken_page'           => ['Broken Page', 'critical'],
];

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($success): ?><div class="alert-success-ap"><i class="bi bi-check-circle me-2"></i><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert-error-ap"><i class="bi bi-x-circle me-2"></i><?= $error ?></div><?php endif; ?>

<!-- ═══ WEBSITES ═══ -->
<div class="admin-card">
    <div class="admin-card-header">
        <i class="bi bi-globe"></i><h2>Tracked Websites</h2>
    </div>
    <div class="admin-card-body p-0">
        <table class="table table-hover mb-0" style="font-size:14px;">
            <thead style="background:#fafafa;">
                <tr>
                    <th style="padding:14px 16px;">Website</th>
                    <th>URL</th>
                    <th>Type</th>
                    <th>Pages</th>
                    <th>Last Audited</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sites as $site):
                    $pageCount = db()->query("SELECT COUNT(*) as c FROM seo_pages WHERE site_id={$site['id']}")->fetch_assoc()['c'] ?? 0;
                ?>
                <tr>
                    <td style="padding:12px 16px;">
                        <strong><?= htmlspecialchars($site['name']) ?></strong>
                        <?php if ($site['is_main_site']): ?>
                        <span class="badge" style="background:var(--ap);font-size:10px;">MAIN</span>
                        <?php endif; ?>
                    </td>
                    <td><a href="<?= htmlspecialchars($site['url']) ?>" target="_blank" style="font-size:13px;"><?= htmlspecialchars($site['url']) ?></a></td>
                    <td>
                        <?php $issueC = db()->query("SELECT COUNT(*) as c FROM seo_issues WHERE site_id={$site['id']} AND severity='critical'")->fetch_assoc()['c'] ?? 0; ?>
                        <?php if ($issueC > 0): ?>
                        <span class="badge" style="background:#dc3545;font-size:11px;"><?= $issueC ?> critical</span>
                        <?php else: ?>
                        <span class="badge" style="background:#28a745;font-size:11px;">Clean</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $pageCount ?></td>
                    <td style="font-size:12px;color:#888;"><?= $site['last_audited'] ? date('d M Y H:i', strtotime($site['last_audited'])) : 'Never' ?></td>
                    <td>
                        <a href="seo-audit.php?run_audit=<?= $site['id'] ?>" class="btn btn-sm" style="background:var(--ap2);color:#fff;font-size:12px;padding:4px 12px;" onclick="return confirm('Run SEO audit for <?= htmlspecialchars($site['name']) ?>? This may take 30-60 seconds.')">
                            <i class="bi bi-play-fill"></i> Run Audit
                        </a>
                        <a href="seo-report.php?site_id=<?= $site['id'] ?>" class="btn btn-sm btn-outline-primary" style="font-size:12px;padding:4px 10px;">
                            <i class="bi bi-file-text"></i> Report
                        </a>
                        <?php if (!$site['is_main_site']): ?>
                        <a href="seo-audit.php?delete_site=<?= $site['id'] ?>" onclick="return confirm('Remove this website?')" class="btn btn-sm btn-outline-danger" style="font-size:12px;padding:4px 10px;">
                            <i class="bi bi-trash"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ═══ ADD WEBSITE ═══ -->
<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-plus-circle"></i><h2>Add Website to Track</h2></div>
    <div class="admin-card-body">
        <form method="POST" action="">
            <input type="hidden" name="action" value="add_site">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Website Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Elite Shopfronts" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">URL</label>
                    <input type="url" name="url" class="form-control" placeholder="https://example.ie" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sitemap URL</label>
                    <input type="url" name="sitemap_url" class="form-control" placeholder="https://example.ie/sitemap.xml">
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="checkbox" name="is_main_site" class="form-check-input" id="isMain">
                        <label class="form-check-label" for="isMain">Main site</label>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn-save" style="padding:10px 16px;"><i class="bi bi-plus"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ═══ COMBINED COMPARISON REPORT ═══ -->
<div class="admin-card">
    <div class="admin-card-header">
        <i class="bi bi-files"></i><h2>SEO Comparison Report — All Websites</h2>
        <a href="seo-report.php" class="btn-view ms-auto" style="font-size:12px;"><i class="bi bi-file-text me-1"></i>Full Report</a>
    </div>
    <div class="admin-card-body p-0">
        <?php if (empty($issues)): ?>
        <div class="text-center py-5">
            <i class="bi bi-shield-check" style="font-size:3rem;color:#28a745;"></i>
            <p class="text-muted mt-3 mb-0">No issues detected yet.</p>
            <p class="text-muted" style="font-size:13px;">Run an audit on each website to detect SEO problems.</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px;">
                <thead style="background:#fafafa;">
                    <tr>
                        <th style="padding:12px 16px;">Severity</th>
                        <th>Issue Type</th>
                        <th>Website</th>
                        <th>Page URL</th>
                        <th>Details</th>
                        <th>Duplicate Found On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($issues as $issue):
                        $label = $issueLabels[$issue['issue_type']] ?? [$issue['issue_type'], 'info'];
                        $sevColor = $label[1] === 'critical' ? '#dc3545' : ($label[1] === 'warning' ? '#ffc107' : '#17a2b8');
                    ?>
                    <tr>
                        <td style="padding:10px 16px;">
                            <span class="badge" style="background:<?= $sevColor ?>;font-size:10px;text-transform:uppercase;">
                                <?= $label[1] ?>
                            </span>
                        </td>
                        <td><strong><?= $label[0] ?></strong></td>
                        <td>
                            <span style="font-size:12px;color:var(--ap);font-weight:600;"><?= htmlspecialchars($issue['site_name']) ?></span>
                        </td>
                        <td style="max-width:250px;word-break:break-all;">
                            <a href="<?= htmlspecialchars($issue['page_url'] ?? '#') ?>" target="_blank" style="font-size:12px;">
                                <?= htmlspecialchars(substr($issue['page_url'] ?? '', 0, 60)) ?>...
                            </a>
                        </td>
                        <td style="max-width:300px;"><?= htmlspecialchars($issue['message']) ?></td>
                        <td style="max-width:250px;word-break:break-all;font-size:12px;color:#dc3545;">
                            <?= $issue['duplicate_url'] ? htmlspecialchars(substr($issue['duplicate_url'], 0, 60)) . '...' : '—' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ═══ RECENT AUDITS ═══ -->
<?php if (!empty($audits)): ?>
<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-clock-history"></i><h2>Recent Audit Runs</h2></div>
    <div class="admin-card-body p-0">
        <table class="table table-sm mb-0" style="font-size:13px;">
            <thead style="background:#fafafa;">
                <tr>
                    <th style="padding:10px 16px;">Website</th>
                    <th>Pages</th>
                    <th>Issues</th>
                    <th>Critical</th>
                    <th>Warnings</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($audits as $a): ?>
                <tr>
                    <td style="padding:8px 16px;"><?= htmlspecialchars($a['site_name'] ?? '—') ?></td>
                    <td><?= $a['total_pages'] ?></td>
                    <td><?= $a['total_issues'] ?></td>
                    <td><span style="color:#dc3545;font-weight:700;"><?= $a['critical_count'] ?></span></td>
                    <td><span style="color:#ffc107;font-weight:700;"><?= $a['warning_count'] ?></span></td>
                    <td>
                        <span class="badge" style="background:<?= $a['status'] === 'completed' ? '#28a745' : '#ffc107' ?>;font-size:10px;">
                            <?= $a['status'] ?>
                        </span>
                    </td>
                    <td style="font-size:12px;color:#888;"><?= date('d M H:i', strtotime($a['started_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
