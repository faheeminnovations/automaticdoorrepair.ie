<?php
require_once __DIR__ . '/../includes/functions.php';
$bp = BASE_PATH;
$current_page = 'services';
$seo = [
    'title'       => 'Our Services | ' . SITE_NAME . ' — Automatic Door Repair Ireland',
    'description' => 'Complete range of door services: automatic doors, aluminium shopfronts, roller shutters, fire doors, commercial sliding doors & more across Ireland.',
    'canonical'   => SITE_URL . '/services/',
];
$schema_json = '<script type="application/ld+json">{"@context":"https://schema.org","@type":"ItemList","name":"Door Services","url":"' . SITE_URL . '/services/"}</script>';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="section-dark" style="padding-top:120px;">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">What We Do</p>
      <h1 style="color: white !important" >All Door Services We Provide</h1>
      <p style="color: white !important" class="section-sub">From emergency repairs to full new installations — we cover every type of commercial door system across Ireland.</p>
    </div>
    <div class="services-grid">
      <?php foreach (db_services() as $svc): ?>
      <div class="svc-card fade-up">
        <div class="svc-img"><img src="<?= $bp ?>/assets/images/<?= htmlspecialchars($svc['image']) ?>" alt="<?= htmlspecialchars($svc['title']) ?> Ireland" loading="lazy" onerror="this.src='<?= $bp ?>/assets/images/doors-banner.jpg'"></div>
        <div class="svc-body">
          <div class="svc-icon"><i class="bi <?= htmlspecialchars($svc['icon']) ?>"></i></div>
          <h3><?= htmlspecialchars($svc['title']) ?></h3>
          <p><?= htmlspecialchars($svc['short_desc']) ?></p>
          <a href="<?= $bp ?>/services/<?= htmlspecialchars($svc['slug']) ?>/" class="svc-link">Learn More <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section class="cta-section"><div class="container"><div class="cta-inner">
  <div><h2>Need a Door Service?</h2><p>Free quote, no obligation. 24/7 emergency callouts.</p></div>
  <div class="cta-btns"><a href="<?= $bp ?>/contact/" class="btn-primary-site">Request Free Quote</a><a href="tel:<?= SITE_PHONE ?>" class="btn-outline-site"><i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?></a></div>
</div></div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
