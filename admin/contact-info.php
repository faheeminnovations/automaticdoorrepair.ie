<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$page_title = 'Edit Contact Info';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'site_phone'    => trim($_POST['site_phone']    ?? ''),
        'site_mobile'   => trim($_POST['site_mobile']   ?? ''),
        'site_email'    => trim($_POST['site_email']    ?? ''),
        'site_address'  => trim($_POST['site_address']  ?? ''),
        'site_maps_url' => trim($_POST['site_maps_url'] ?? ''),
    ];
    if (empty($data['site_phone']) || empty($data['site_email'])) {
        $error = 'Phone and Email are required.';
    } elseif (!filter_var($data['site_email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        save_settings($data);
        header('Location: contact-info.php?saved=1'); exit;
    }
}
if (isset($_GET['saved'])) $success = 'Contact info updated successfully.';
$s = get_settings();
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($success): ?><div class="alert-success-ap"><i class="bi bi-check-circle me-2"></i><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert-error-ap"><i class="bi bi-x-circle me-2"></i><?= $error ?></div><?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-telephone"></i><h2>Contact Information</h2></div>
    <div class="admin-card-body">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="site_phone" class="form-control"
                            value="<?= htmlspecialchars($s['site_phone']) ?>"
                            placeholder="045-875406" required>
                    </div>
                    <div class="form-hint">Display format e.g. 045-875406</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mobile / WhatsApp</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                        <input type="text" name="site_mobile" class="form-control"
                            value="<?= htmlspecialchars($s['site_mobile']) ?>"
                            placeholder="085 100 0459">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="site_email" class="form-control"
                            value="<?= htmlspecialchars($s['site_email']) ?>"
                            placeholder="info@elitedoors.ie" required>
                    </div>
                    <div class="form-hint">Enquiry emails will be sent to this address.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Google Maps URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input type="url" name="site_maps_url" class="form-control"
                            value="<?= htmlspecialchars($s['site_maps_url']) ?>"
                            placeholder="https://maps.app.goo.gl/...">
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Full Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
                        <input type="text" name="site_address" class="form-control"
                            value="<?= htmlspecialchars($s['site_address']) ?>"
                            placeholder="Unit 2A, Sickens Caragh, Naas, Co. Kildare, W91ND9F">
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn-save"><i class="bi bi-save me-2"></i>Save Changes</button>
                <a href="../" target="_blank" class="btn-view"><i class="bi bi-eye me-1"></i>Preview Site</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
