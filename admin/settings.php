<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$page_title = 'Admin Settings';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = trim($_POST['admin_email']      ?? '');
    $new_pass  = trim($_POST['admin_password']   ?? '');
    $confirm   = trim($_POST['confirm_password'] ?? '');

    if (empty($new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!empty($new_pass) && $new_pass !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (!empty($new_pass) && strlen($new_pass) < 5) {
        $error = 'Password must be at least 5 characters.';
    } else {
        update_admin_credentials($new_email, $new_pass);
        header('Location: settings.php?saved=1'); exit;
    }
}
if (isset($_GET['saved'])) $success = 'Admin credentials updated successfully.';
$admin = get_admin_user();
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($success): ?><div class="alert-success-ap"><i class="bi bi-check-circle me-2"></i><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert-error-ap"><i class="bi bi-x-circle me-2"></i><?= $error ?></div><?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-gear"></i><h2>Admin Account Settings</h2></div>
    <div class="admin-card-body">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Admin Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="admin_email" class="form-control"
                            value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">New Password <span style="font-weight:400;color:#999;">(leave blank to keep current)</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="admin_password" class="form-control" placeholder="New password">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Repeat new password">
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn-save"><i class="bi bi-save me-2"></i>Update Credentials</button>
            </div>
        </form>
    </div>
</div>

<!-- System Info -->
<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-info-circle"></i><h2>System Info</h2></div>
    <div class="admin-card-body">
        <table class="table table-sm" style="font-size:13px;">
            <tr><td class="text-muted" style="width:180px;">PHP Version</td><td><?= phpversion() ?></td></tr>
            <tr><td class="text-muted">Database</td><td><?= DB_NAME ?></td></tr>
            <tr><td class="text-muted">Admin Email</td><td><?= htmlspecialchars($admin['email'] ?? '') ?></td></tr>
            <tr><td class="text-muted">Password Storage</td><td>✓ bcrypt hashed in MySQL</td></tr>
            <tr><td class="text-muted">Settings Storage</td><td>✓ MySQL site_settings table</td></tr>
            <tr><td class="text-muted">Login Time</td><td><?= isset($_SESSION['admin_login_time']) ? date('d M Y H:i:s', $_SESSION['admin_login_time']) : 'N/A' ?></td></tr>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
