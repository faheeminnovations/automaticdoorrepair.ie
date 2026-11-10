<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_title ?? 'Admin Panel' ?> — automaticdoorrepair.ie</title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
  :root {
    --ap: #702963;
    --ap2: #AA336A;
    --dark: #1a1a2e;
    --sidebar-w: 260px;
  }
  body { font-family: 'Segoe UI', sans-serif; background: #f4f6fb; margin: 0; }

  /* Sidebar */
  .sidebar {
    position: fixed; top: 0; left: 0; bottom: 0;
    width: var(--sidebar-w);
    background: var(--dark);
    z-index: 100;
    overflow-y: auto;
    display: flex; flex-direction: column;
  }
  .sidebar-brand {
    padding: 20px 20px 16px;
    border-bottom: 1px solid rgba(255,255,255,.08);
  }
  .sidebar-brand .logo {
    font-size: 13px; font-weight: 700; color: #fff;
    text-transform: uppercase; letter-spacing: 1.5px;
  }
  .sidebar-brand .domain {
    font-size: 11px; color: var(--ap2); margin-top: 2px;
  }
  .sidebar nav { flex: 1; padding: 12px 0; }
  .sidebar nav a {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 20px;
    color: rgba(255,255,255,.65);
    font-size: 14px; font-weight: 500;
    text-decoration: none;
    transition: all .2s;
    border-left: 3px solid transparent;
  }
  .sidebar nav a:hover { color: #fff; background: rgba(255,255,255,.05); }
  .sidebar nav a.active {
    color: #fff;
    background: rgba(170,51,106,.2);
    border-left-color: var(--ap2);
  }
  .sidebar nav .nav-section {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 2px; color: rgba(255,255,255,.25);
    padding: 20px 20px 6px;
  }
  .sidebar nav a i { font-size: 16px; min-width: 20px; }
  .sidebar-footer {
    padding: 16px 20px;
    border-top: 1px solid rgba(255,255,255,.08);
    font-size: 12px; color: rgba(255,255,255,.35);
  }
  .sidebar-footer a { color: rgba(255,255,255,.45); text-decoration: none; }
  .sidebar-footer a:hover { color: #fff; }

  /* Main */
  .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; }
  .topbar {
    background: #fff;
    border-bottom: 1px solid #e8e8e8;
    padding: 14px 28px;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 50;
    box-shadow: 0 1px 8px rgba(0,0,0,.06);
  }
  .topbar h1 { font-size: 18px; font-weight: 700; color: var(--ap); margin: 0; }
  .topbar-right { display: flex; align-items: center; gap: 12px; }
  .topbar-right .admin-badge {
    font-size: 12px; color: #666;
    background: #f4f6fb; padding: 6px 14px; border-radius: 20px;
  }
  .content { padding: 28px; }

  /* Cards */
  .admin-card {
    background: #fff; border-radius: 10px;
    border: 1px solid #eee;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    margin-bottom: 24px;
    overflow: hidden;
  }
  .admin-card-header {
    padding: 16px 24px;
    border-bottom: 1px solid #f0f0f0;
    background: #fafafa;
    display: flex; align-items: center; gap: 10px;
  }
  .admin-card-header h2 {
    font-size: 15px; font-weight: 700; color: var(--ap); margin: 0;
  }
  .admin-card-header i { font-size: 18px; color: var(--ap2); }
  .admin-card-body { padding: 24px; }

  /* Form */
  .form-label { font-size: 13px; font-weight: 600; color: #444; margin-bottom: 5px; }
  .form-control, .form-select {
    border: 1px solid #ddd; border-radius: 6px; font-size: 14px;
    transition: border-color .2s, box-shadow .2s;
  }
  .form-control:focus, .form-select:focus {
    border-color: var(--ap); box-shadow: 0 0 0 3px rgba(112,41,99,.1);
  }
  .form-hint { font-size: 11px; color: #999; margin-top: 4px; }

  /* Buttons */
  .btn-save {
    background: var(--ap2); color: #fff; border: none;
    font-size: 13px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1px; padding: 10px 24px; border-radius: 6px;
    cursor: pointer; transition: all .2s;
  }
  .btn-save:hover { background: var(--ap); transform: translateY(-1px); }
  .btn-view {
    background: #f4f6fb; color: var(--ap); border: 1px solid #ddd;
    font-size: 13px; font-weight: 600; padding: 8px 18px; border-radius: 6px;
    text-decoration: none; transition: all .2s;
  }
  .btn-view:hover { background: var(--ap); color: #fff; border-color: var(--ap); }

  /* Alerts */
  .alert-success-ap {
    background: #d4edda; border: 1px solid #c3e6cb; color: #155724;
    padding: 12px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;
  }
  .alert-error-ap {
    background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;
    padding: 12px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;
  }

  /* Stats */
  .stat-cards { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 28px; }
  .stat-card {
    background: #fff; border-radius: 10px; padding: 20px;
    border: 1px solid #eee; text-align: center;
  }
  .stat-card .num { font-size: 2rem; font-weight: 800; color: var(--ap); }
  .stat-card .lbl { font-size: 12px; color: #888; margin-top: 4px; }

  /* Mobile */
  @media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); transition: transform .3s; }
    .sidebar.open { transform: translateX(0); }
    .main-wrap { margin-left: 0; }
    .stat-cards { grid-template-columns: 1fr 1fr; }
  }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="logo"><i class="bi bi-shield-lock me-2"></i>Admin Panel</div>
    <div class="domain">automaticdoorrepair.ie</div>
  </div>
  <nav>
    <div class="nav-section">Main</div>
    <a href="index.php"       class="<?= (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <div class="nav-section">Content</div>
    <a href="hero.php"        class="<?= (basename($_SERVER['PHP_SELF']) === 'hero.php') ? 'active' : '' ?>">
      <i class="bi bi-image"></i> Hero Section
    </a>
    <a href="about.php"       class="<?= (basename($_SERVER['PHP_SELF']) === 'about.php') ? 'active' : '' ?>">
      <i class="bi bi-info-circle"></i> About Section
    </a>
    <a href="services.php"    class="<?= (basename($_SERVER['PHP_SELF']) === 'services.php') ? 'active' : '' ?>">
      <i class="bi bi-tools"></i> Services
    </a>
    <a href="colors.php"     class="<?= (basename($_SERVER['PHP_SELF']) === 'colors.php') ? 'active' : '' ?>">
      <i class="bi bi-palette"></i> Color Scheme
    </a>
    <a href="contact-info.php" class="<?= (basename($_SERVER['PHP_SELF']) === 'contact-info.php') ? 'active' : '' ?>">
      <i class="bi bi-telephone"></i> Contact Info
    </a>
    <a href="social.php"      class="<?= (basename($_SERVER['PHP_SELF']) === 'social.php') ? 'active' : '' ?>">
      <i class="bi bi-share"></i> Social Links
    </a>
    <div class="nav-section">SEO</div>
    <a href="seo.php"         class="<?= (basename($_SERVER['PHP_SELF']) === 'seo.php') ? 'active' : '' ?>">
      <i class="bi bi-search"></i> SEO Settings
    </a>
    <div class="nav-section">System</div>
    <a href="settings.php"    class="<?= (basename($_SERVER['PHP_SELF']) === 'settings.php') ? 'active' : '' ?>">
      <i class="bi bi-gear"></i> Admin Settings
    </a>
    <a href="enquiries.php"   class="<?= (basename($_SERVER['PHP_SELF']) === 'enquiries.php') ? 'active' : '' ?>">
      <i class="bi bi-envelope"></i> Enquiries
    </a>
    <a href="../" target="_blank">
      <i class="bi bi-box-arrow-up-right"></i> View Website
    </a>
    <a href="logout.php">
      <i class="bi bi-box-arrow-left"></i> Logout
    </a>
  </nav>
  <div class="sidebar-footer">
    Logged in as<br><a><?= htmlspecialchars($_SESSION['admin_email'] ?? ADMIN_EMAIL) ?></a>
  </div>
</div>

<!-- Main -->
<div class="main-wrap">
  <div class="topbar">
    <h1><?= $page_title ?? 'Dashboard' ?></h1>
    <div class="topbar-right">
      <a href="../" target="_blank" class="btn-view">
        <i class="bi bi-eye me-1"></i>View Site
      </a>
      <span class="admin-badge">
        <i class="bi bi-person-circle me-1"></i>Admin
      </span>
    </div>
  </div>
  <div class="content">
