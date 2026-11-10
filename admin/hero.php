<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$page_title = 'Edit Hero Section';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'hero_heading' => trim($_POST['hero_heading'] ?? ''),
        'hero_sub'     => trim($_POST['hero_sub']     ?? ''),
    ];
    if (empty($data['hero_heading'])) {
        $error = 'Heading cannot be empty.';
    } else {
        save_settings($data);
        header('Location: hero.php?saved=1'); exit;
    }
}
if (isset($_GET['saved'])) $success = 'Hero section updated successfully.';
$s = get_settings();
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($success): ?><div class="alert-success-ap"><i class="bi bi-check-circle me-2"></i><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert-error-ap"><i class="bi bi-x-circle me-2"></i><?= $error ?></div><?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header">
        <i class="bi bi-image"></i><h2>Hero Section</h2>
    </div>
    <div class="admin-card-body">
        <form method="POST">
            <div class="mb-4">
                <label class="form-label">Hero Heading (H1) <span class="text-danger">*</span></label>
                <textarea name="hero_heading" class="form-control" rows="3"
                    placeholder="e.g. Automatic Door Repair — Supply, Installation & Maintenance Ireland"
                    required><?= htmlspecialchars($s['hero_heading']) ?></textarea>
                <div class="form-hint">Use line breaks for multi-line heading. Shown as main H1 on homepage.</div>
            </div>
            <div class="mb-4">
                <label class="form-label">Hero Sub-text (description under H1)</label>
                <textarea name="hero_sub" class="form-control" rows="4"
                    placeholder="Short description shown under the main heading..."><?= htmlspecialchars($s['hero_sub']) ?></textarea>
                <div class="form-hint">Keep it under 180 characters for best display.</div>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <button type="submit" class="btn-save"><i class="bi bi-save me-2"></i>Save Changes</button>
                <a href="../" target="_blank" class="btn-view"><i class="bi bi-eye me-1"></i>Preview Site</a>
            </div>
        </form>
    </div>
</div>

<!-- Live Preview -->
<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-eye"></i><h2>Current Preview</h2></div>
    <div class="admin-card-body">
        <div style="background:linear-gradient(135deg,#1B1F23,#702963);padding:36px;border-radius:8px;color:#fff;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:2px;color:rgba(255,255,255,.6);margin-bottom:12px;">
                22 Years of Experience — Since 2003
            </div>
            <h1 style="font-size:clamp(1.4rem,3vw,2rem);font-weight:900;color:#fff;line-height:1.2;margin-bottom:12px;">
                <?= nl2br(htmlspecialchars($s['hero_heading'])) ?>
            </h1>
            <p style="font-size:14px;color:rgba(255,255,255,.75);max-width:500px;">
                <?= htmlspecialchars($s['hero_sub']) ?>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
