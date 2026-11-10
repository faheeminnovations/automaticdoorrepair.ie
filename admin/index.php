<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$page_title = 'Dashboard';
$settings   = get_settings();
require_once __DIR__ . '/includes/header.php';

// Count enquiries from DB
$enquiry_count = 0;
try {
    $r = db()->query("SELECT COUNT(*) as c FROM " . DB_PREFIX . "e_submissions");
    if ($r) $enquiry_count = $r->fetch_assoc()['c'] ?? 0;
} catch (Exception $e) {}
?>

<!-- Stat Cards -->
<div class="stat-cards">
    <div class="stat-card">
        <div class="num">12</div>
        <div class="lbl">Total Services</div>
    </div>
    <div class="stat-card">
        <div class="num"><?= (int)$enquiry_count ?></div>
        <div class="lbl">DB Enquiries</div>
    </div>
    <div class="stat-card">
        <div class="num">1</div>
        <div class="lbl">Admin User</div>
    </div>
    <div class="stat-card">
        <div class="num">✓</div>
        <div class="lbl">Site Live</div>
    </div>
</div>

<!-- Quick Edit Cards -->
<div class="row g-4">

    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <i class="bi bi-image"></i>
                <h2>Hero Section</h2>
            </div>
            <div class="admin-card-body">
                <p class="text-muted mb-1" style="font-size:13px;">Current headline:</p>
                <p class="fw-bold mb-3" style="font-size:14px;"><?= nl2br(htmlspecialchars($settings['hero_heading'])) ?></p>
                <a href="hero.php" class="btn-save" style="font-size:12px;padding:8px 18px;text-decoration:none;">
                    <i class="bi bi-pencil me-1"></i>Edit Hero
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <i class="bi bi-telephone"></i>
                <h2>Contact Info</h2>
            </div>
            <div class="admin-card-body">
                <p class="text-muted mb-1" style="font-size:13px;">Phone: <strong><?= htmlspecialchars($settings['site_phone']) ?></strong></p>
                <p class="text-muted mb-3" style="font-size:13px;">Email: <strong><?= htmlspecialchars($settings['site_email']) ?></strong></p>
                <a href="contact-info.php" class="btn-save" style="font-size:12px;padding:8px 18px;text-decoration:none;">
                    <i class="bi bi-pencil me-1"></i>Edit Contact
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <i class="bi bi-search"></i>
                <h2>SEO Settings</h2>
            </div>
            <div class="admin-card-body">
                <p class="text-muted mb-1" style="font-size:13px;">Title:</p>
                <p class="fw-bold mb-3" style="font-size:13px;"><?= htmlspecialchars(substr($settings['seo_title'], 0, 60)) ?>...</p>
                <a href="seo.php" class="btn-save" style="font-size:12px;padding:8px 18px;text-decoration:none;">
                    <i class="bi bi-pencil me-1"></i>Edit SEO
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <i class="bi bi-share"></i>
                <h2>Social Links</h2>
            </div>
            <div class="admin-card-body">
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <?php foreach(['facebook','instagram','whatsapp','youtube','linkedin','tiktok'] as $s): ?>
                    <?php if(!empty($settings["site_$s"])): ?>
                    <span class="badge" style="background:var(--ap);font-size:11px;"><?= ucfirst($s) ?></span>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <a href="social.php" class="btn-save" style="font-size:12px;padding:8px 18px;text-decoration:none;">
                    <i class="bi bi-pencil me-1"></i>Edit Social
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Quick Links -->
<div class="admin-card mt-2">
    <div class="admin-card-header">
        <i class="bi bi-lightning"></i>
        <h2>Quick Actions</h2>
    </div>
    <div class="admin-card-body d-flex gap-3 flex-wrap">
        <a href="hero.php"         class="btn-view"><i class="bi bi-image me-1"></i>Hero</a>
        <a href="about.php"        class="btn-view"><i class="bi bi-info-circle me-1"></i>About</a>
        <a href="services.php"     class="btn-view"><i class="bi bi-tools me-1"></i>Services</a>
        <a href="contact-info.php" class="btn-view"><i class="bi bi-telephone me-1"></i>Contact</a>
        <a href="social.php"       class="btn-view"><i class="bi bi-share me-1"></i>Social</a>
        <a href="seo.php"          class="btn-view"><i class="bi bi-search me-1"></i>SEO</a>
        <a href="settings.php"     class="btn-view"><i class="bi bi-gear me-1"></i>Settings</a>
        <a href="enquiries.php"    class="btn-view"><i class="bi bi-envelope me-1"></i>Enquiries</a>
        <a href="../" target="_blank" class="btn-view"><i class="bi bi-eye me-1"></i>View Site</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
