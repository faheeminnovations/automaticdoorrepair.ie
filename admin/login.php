<?php
require_once __DIR__ . '/includes/config.php';

// Already logged in
if (admin_logged_in()) {
    header('Location: index.php'); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']    ?? '');
    $pass  = trim($_POST['password'] ?? '');

    if (admin_login($email, $pass)) {
        header('Location: index.php'); exit;
    } else {
        $error = 'Invalid email or password. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — automaticdoorrepair.ie</title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
  body {
    min-height: 100vh;
    background: linear-gradient(135deg, #1a1a2e 0%, #702963 60%, #AA336A 100%);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Segoe UI', sans-serif;
  }
  .login-box {
    background: #fff;
    border-radius: 16px;
    padding: 48px 40px;
    width: 100%; max-width: 420px;
    box-shadow: 0 24px 80px rgba(0,0,0,.35);
  }
  .login-logo {
    text-align: center; margin-bottom: 32px;
  }
  .login-logo .icon {
    width: 64px; height: 64px;
    background: linear-gradient(135deg, #702963, #AA336A);
    border-radius: 16px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 28px; color: #fff; margin-bottom: 14px;
  }
  .login-logo h1 { font-size: 20px; font-weight: 800; color: #702963; margin: 0; }
  .login-logo p  { font-size: 13px; color: #888; margin: 4px 0 0; }
  .form-label { font-size: 13px; font-weight: 600; color: #444; }
  .form-control {
    border: 1px solid #ddd; border-radius: 8px; padding: 12px 14px;
    font-size: 15px; transition: all .2s;
  }
  .form-control:focus {
    border-color: #702963; box-shadow: 0 0 0 3px rgba(112,41,99,.12);
    outline: none;
  }
  .btn-login {
    width: 100%; background: #AA336A; color: #fff; border: none;
    padding: 13px; border-radius: 8px; font-size: 15px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1px; cursor: pointer;
    transition: all .2s; margin-top: 8px;
  }
  .btn-login:hover { background: #702963; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,0,0,.2); }
  .error-box {
    background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;
    padding: 12px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 8px;
  }
  .input-icon { position: relative; }
  .input-icon i {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #aaa; font-size: 16px;
  }
  .input-icon input { padding-left: 40px; }
  .footer-note { text-align: center; margin-top: 20px; font-size: 12px; color: #bbb; }
</style>
</head>
<body>
<div class="login-box">
  <div class="login-logo">
    <div class="icon"><i class="bi bi-shield-lock"></i></div>
    <h1>Admin Panel</h1>
    <p>automaticdoorrepair.ie</p>
  </div>

  <?php if ($error): ?>
  <div class="error-box">
    <i class="bi bi-exclamation-triangle-fill"></i><?= htmlspecialchars($error) ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label class="form-label">Email Address</label>
      <div class="input-icon">
        <i class="bi bi-envelope"></i>
        <input type="email" name="email" class="form-control"
               placeholder="admin@email.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
      </div>
    </div>
    <div class="mb-4">
      <label class="form-label">Password</label>
      <div class="input-icon">
        <i class="bi bi-lock"></i>
        <input type="password" name="password" class="form-control"
               placeholder="••••••" required>
      </div>
    </div>
    <button type="submit" class="btn-login">
      <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
    </button>
  </form>
  <div class="footer-note">Protected area — authorised access only</div>
</div>
</body>
</html>
