<?php
/**
 * Shared Header — automaticdoorrepair.ie
 * Used by all pages. Expects $seo array + $bp variable
 */
if (!defined('SITE_NAME')) require_once __DIR__ . '/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$bp = BASE_PATH;
?>
<!DOCTYPE html>
<html lang="en-IE">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Primary SEO -->
<title><?= htmlspecialchars($seo['title'] ?? SITE_NAME) ?></title>
<meta name="description" content="<?= htmlspecialchars($seo['description'] ?? '') ?>">
<?php if (!empty($seo['keywords'])): ?>
<meta name="keywords"    content="<?= htmlspecialchars($seo['keywords']) ?>">
<?php endif; ?>
<link rel="canonical"    href="<?= htmlspecialchars($seo['canonical'] ?? SITE_URL . '/') ?>">
<meta name="robots"      content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="author"      content="<?= SITE_NAME ?>">
<meta name="geo.region"  content="IE-KE">
<meta name="geo.placename" content="Naas, Co. Kildare">

<!-- Open Graph -->
<meta property="og:type"        content="website">
<meta property="og:url"         content="<?= htmlspecialchars($seo['canonical'] ?? SITE_URL . '/') ?>">
<meta property="og:title"       content="<?= htmlspecialchars($seo['title'] ?? SITE_NAME) ?>">
<meta property="og:description" content="<?= htmlspecialchars($seo['description'] ?? '') ?>">
<meta property="og:image"       content="<?= SITE_URL ?>/assets/images/logo.png">
<meta property="og:locale"      content="en_IE">
<meta property="og:site_name"   content="<?= SITE_NAME ?>">

<!-- Twitter Card -->
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="<?= htmlspecialchars($seo['title'] ?? SITE_NAME) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($seo['description'] ?? '') ?>">
<meta name="twitter:image"       content="<?= SITE_URL ?>/assets/images/logo.png">

<!-- Favicon -->
<link rel="icon"             href="<?= $bp ?>/assets/images/favicon.png" type="image/png">
<link rel="apple-touch-icon" href="<?= $bp ?>/assets/images/favicon.png">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- Bootstrap 5 + Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="<?= $bp ?>/assets/css/style.css">

<!-- Dynamic colors from DB -->
<?= color_vars() ?>

<?php if (!empty($schema_json)) echo $schema_json; ?>
</head>
<body id="top">

<!-- ═══ HEADER / NAV ═══ -->
<header class="site-header" id="siteHeader">
  <div class="container">
    <nav class="nav-inner">
      <a href="<?= $bp ?>/" class="site-logo" aria-label="Elite Tech Precision — Home">
        <img src="<?= $bp ?>/assets/images/logo.png" alt="Elite Tech Precision Ltd Logo" width="160" height="auto" loading="eager">
      </a>
      <ul class="nav-links" id="navLinks">
        <li><a href="<?= $bp ?>/about/"     <?= ($current_page ?? '') === 'about'     ? 'class="active"' : '' ?>>About</a></li>
        <li><a href="<?= $bp ?>/services/"  <?= ($current_page ?? '') === 'services'  ? 'class="active"' : '' ?>>Services</a></li>
        <li><a href="<?= $bp ?>/#why-us"   >Why Us</a></li>
        <li><a href="<?= $bp ?>/#process"  >Process</a></li>
        <li><a href="<?= $bp ?>/#clients"  >Clients</a></li>
        <li><a href="<?= $bp ?>/contact/"  <?= ($current_page ?? '') === 'contact'    ? 'class="active"' : '' ?>>Contact</a></li>
      </ul>
      <div class="nav-actions">
        <a href="tel:<?= SITE_PHONE ?>" class="btn-call">
          <i class="bi bi-telephone-fill"></i><span><?= SITE_PHONE_DISPLAY ?></span>
        </a>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </nav>
  </div>
</header>

<!-- Mobile nav drawer -->
<div class="mobile-nav" id="mobileNav" aria-hidden="true">
  <ul>
    <li><a href="<?= $bp ?>/about/"    class="mob-link">About</a></li>
    <li><a href="<?= $bp ?>/services/"  class="mob-link">Services</a></li>
    <li><a href="<?= $bp ?>/#why-us"    class="mob-link">Why Us</a></li>
    <li><a href="<?= $bp ?>/#process"   class="mob-link">Process</a></li>
    <li><a href="<?= $bp ?>/#clients"   class="mob-link">Clients</a></li>
    <li><a href="<?= $bp ?>/contact/"   class="mob-link">Contact</a></li>
    <li>
      <a href="tel:<?= SITE_PHONE ?>" class="mob-cta">
        <i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?>
      </a>
    </li>
  </ul>
</div>
<div class="mobile-overlay" id="mobileOverlay"></div>
