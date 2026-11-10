<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$page_title = 'Edit About Section';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'about_heading' => trim($_POST['about_heading'] ?? ''),
        'about_text'    => trim($_POST['about_text']    ?? ''),
    ];
    if (empty($data['about_heading'])) {
        $error = 'Heading cannot be empty.';
    } else {
        save_settings($data);
        header('Location: about.php?saved=1'); exit;
    }
}
if (isset($_GET['saved'])) $success = 'About section updated successfully.';
$s = get_settings();
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($success): ?><div class="alert-success-ap"><i class="bi bi-check-circle me-2"></i><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert-error-ap"><i class="bi bi-x-circle me-2"></i><?= $error ?></div><?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-info-circle"></i><h2>About Section</h2></div>
    <div class="admin-card-body">
        <form method="POST">
            <div class="mb-4">
                <label class="form-label">About Heading (H2) <span class="text-danger">*</span></label>
                <input type="text" name="about_heading" class="form-control"
                    value="<?= htmlspecialchars($s['about_heading']) ?>"
                    placeholder="e.g. We Have 22 Years of Experience in Automatic Door Repair" required>
            </div>
            <div class="mb-4">
                <label class="form-label">About Description (paragraph 1)</label>
                <textarea name="about_text" class="form-control" rows="6"><?= htmlspecialchars($s['about_text']) ?></textarea>
                <div class="form-hint">Main about paragraph shown on homepage.</div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn-save"><i class="bi bi-save me-2"></i>Save Changes</button>
                <a href="../" target="_blank" class="btn-view"><i class="bi bi-eye me-1"></i>Preview Site</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
