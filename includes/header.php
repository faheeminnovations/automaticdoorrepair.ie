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

  <!-- TOP BAR: logo + contact details -->
  <div class="top-bar">
    <div class="container top-bar-inner">
      <a href="<?= $bp ?>/" class="site-logo" aria-label="Elite Tech Precision — Home">
        <img src="<?= $bp ?>/assets/images/logo.png" alt="Elite Tech Precision Ltd Logo" width="150" height="auto" loading="eager">
      </a>
      <div class="top-contacts">
        <a href="<?= SITE_WHATSAPP ?>" class="top-contact-item" target="_blank" rel="noopener">
          <span class="top-contact-icon top-contact-icon--wa"><i class="bi bi-whatsapp"></i></span>
          <span class="top-contact-text">
            <span class="top-contact-label">WhatsApp Number</span>
            <span class="top-contact-value">+353851000459</span>
          </span>
        </a>
        <a href="tel:<?= SITE_PHONE ?>" class="top-contact-item">
          <span class="top-contact-icon top-contact-icon--ph"><i class="bi bi-telephone-fill"></i></span>
          <span class="top-contact-text">
            <span class="top-contact-label">24/7 Phone Number</span>
            <span class="top-contact-value"><?= SITE_PHONE_DISPLAY ?></span>
          </span>
        </a>
        <a href="mailto:<?= SITE_EMAIL ?>" class="top-contact-item">
          <span class="top-contact-icon top-contact-icon--em"><i class="bi bi-envelope-fill"></i></span>
          <span class="top-contact-text">
            <span class="top-contact-label">Send Your Mail</span>
            <span class="top-contact-value"><?= SITE_EMAIL ?></span>
          </span>
        </a>
      </div>
    </div>
  </div>

  <!-- MAIN NAV: dark bar with links + CTA -->
  <nav class="main-nav" id="mainNav" aria-label="Main navigation">
    <div class="container main-nav-inner">
      <ul class="main-nav-links" id="navLinks">
        <li><a href="<?= $bp ?>/" <?= ($current_page ?? '') === 'home' ? 'class="active"' : '' ?>>Home</a></li>
        <li><a href="<?= $bp ?>/about/" <?= ($current_page ?? '') === 'about' ? 'class="active"' : '' ?>>About Us</a></li>

        <!-- Products dropdown -->
        <li class="has-dropdown">
          <a href="<?= $bp ?>/services/" <?= ($current_page ?? '') === 'services' ? 'class="active"' : '' ?>>
            Products <i class="bi bi-chevron-down dropdown-arrow"></i>
          </a>
          <ul class="dropdown-menu-nav">
            <li><a href="<?= $bp ?>/services/automatic-doors/">Automatic Doors</a></li>
            <li><a href="<?= $bp ?>/services/automatic-swing-doors/">Automatic Swing Doors</a></li>
            <li><a href="<?= $bp ?>/services/commercial-sliding-doors/">Commercial Sliding Doors</a></li>
            <li><a href="<?= $bp ?>/services/aluminium-shopfronts/">Aluminium Shopfronts</a></li>
            <li><a href="<?= $bp ?>/services/aluminium-commercial-doors/">Aluminium Commercial Doors</a></li>
            <li><a href="<?= $bp ?>/services/industrial-roller-shutters/">Industrial Roller Shutters</a></li>
            <li><a href="<?= $bp ?>/services/commercial-roller-shutters/">Commercial Roller Shutters</a></li>
            <li><a href="<?= $bp ?>/services/fire-rated-roller-shutters/">Fire Rated Roller Shutters</a></li>
            <li><a href="<?= $bp ?>/services/fire-rated-steel-doors/">Fire Rated Steel Doors</a></li>
            <li><a href="<?= $bp ?>/services/commercial-steel-doors/">Commercial Steel Doors</a></li>
            <li><a href="<?= $bp ?>/services/fire-exit-doors/">Fire Exit Doors</a></li>
            <li><a href="<?= $bp ?>/services/fire-rated-glazed-screens/">Fire Rated Glazed Screens</a></li>
            <li><a href="<?= $bp ?>/services/curtain-walling/">Curtain Walling</a></li>
            <li><a href="<?= $bp ?>/services/revolving-doors/">Revolving Doors</a></li>
            <li><a href="<?= $bp ?>/services/stack-doors/">Stack Doors</a></li>
          </ul>
        </li>

        <!-- Services dropdown -->
        <li class="has-dropdown">
          <a href="<?= $bp ?>/services/">
            Services <i class="bi bi-chevron-down dropdown-arrow"></i>
          </a>
          <ul class="dropdown-menu-nav">
            <li><a href="<?= $bp ?>/services/planned-preventative-maintenance/">Planned Preventative Maintenance</a></li>
            <li><a href="<?= $bp ?>/services/24-hour-reactive-call-out/">24-Hour Reactive Call-Out</a></li>
          </ul>
        </li>

        <li><a href="<?= $bp ?>/contact/" <?= ($current_page ?? '') === 'contact' ? 'class="active"' : '' ?>>Contact Us</a></li>
        <li><a href="<?= $bp ?>/all-projects/" <?= ($current_page ?? '') === 'gallery' ? 'class="active"' : '' ?>>Projects Gallery</a></li>
      </ul>

      <div class="main-nav-right">
        <a href="<?= $bp ?>/contact/" class="btn-quote">Get a Free Quote</a>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </nav>

</header>

<!-- Mobile nav drawer -->
<div class="mobile-nav" id="mobileNav" aria-hidden="true">
  <ul>
    <li><a href="<?= $bp ?>/" class="mob-link">Home</a></li>
    <li><a href="<?= $bp ?>/about/" class="mob-link">About Us</a></li>
    <li class="mob-has-dropdown">
      <button class="mob-dropdown-toggle" aria-expanded="false">
        Products <i class="bi bi-chevron-down"></i>
      </button>
      <ul class="mob-dropdown">
        <li><a href="<?= $bp ?>/services/automatic-doors/">Automatic Doors</a></li>
        <li><a href="<?= $bp ?>/services/automatic-swing-doors/">Automatic Swing Doors</a></li>
        <li><a href="<?= $bp ?>/services/commercial-sliding-doors/">Commercial Sliding Doors</a></li>
        <li><a href="<?= $bp ?>/services/aluminium-shopfronts/">Aluminium Shopfronts</a></li>
        <li><a href="<?= $bp ?>/services/aluminium-commercial-doors/">Aluminium Commercial Doors</a></li>
        <li><a href="<?= $bp ?>/services/industrial-roller-shutters/">Industrial Roller Shutters</a></li>
        <li><a href="<?= $bp ?>/services/commercial-roller-shutters/">Commercial Roller Shutters</a></li>
        <li><a href="<?= $bp ?>/services/fire-rated-roller-shutters/">Fire Rated Roller Shutters</a></li>
        <li><a href="<?= $bp ?>/services/fire-rated-steel-doors/">Fire Rated Steel Doors</a></li>
        <li><a href="<?= $bp ?>/services/commercial-steel-doors/">Commercial Steel Doors</a></li>
        <li><a href="<?= $bp ?>/services/fire-exit-doors/">Fire Exit Doors</a></li>
        <li><a href="<?= $bp ?>/services/fire-rated-glazed-screens/">Fire Rated Glazed Screens</a></li>
        <li><a href="<?= $bp ?>/services/curtain-walling/">Curtain Walling</a></li>
        <li><a href="<?= $bp ?>/services/revolving-doors/">Revolving Doors</a></li>
        <li><a href="<?= $bp ?>/services/stack-doors/">Stack Doors</a></li>
      </ul>
    </li>
    <li class="mob-has-dropdown">
      <button class="mob-dropdown-toggle" aria-expanded="false">
        Services <i class="bi bi-chevron-down"></i>
      </button>
      <ul class="mob-dropdown">
        <li><a href="<?= $bp ?>/services/planned-preventative-maintenance/">Planned Preventative Maintenance</a></li>
        <li><a href="<?= $bp ?>/services/24-hour-reactive-call-out/">24-Hour Reactive Call-Out</a></li>
      </ul>
    </li>
    <li><a href="<?= $bp ?>/contact/" class="mob-link">Contact Us</a></li>
    <li><a href="<?= $bp ?>/all-projects/" class="mob-link">Projects Gallery</a></li>
    <li>
      <a href="<?= $bp ?>/contact/" class="mob-cta">Get a Free Quote</a>
    </li>
    <li class="mob-contacts">
      <a href="tel:<?= SITE_PHONE ?>"><i class="bi bi-telephone-fill"></i> <?= SITE_PHONE_DISPLAY ?></a>
      <a href="mailto:<?= SITE_EMAIL ?>"><i class="bi bi-envelope-fill"></i> <?= SITE_EMAIL ?></a>
    </li>
  </ul>
</div>
<div class="mobile-overlay" id="mobileOverlay"></div>
