<?php
require_once __DIR__ . '/../includes/functions.php';
$bp = BASE_PATH;

// Fetch service from DB
$slug = $service_slug ?? '';
$conn = new mysqli();
$conn->real_connect(null, 'root', '', 'automaticdoorrepair', 3306, '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');
$stmt = $conn->prepare("SELECT * FROM services WHERE slug = ? AND is_active = 1 LIMIT 1");
$stmt->bind_param('s', $slug);
$stmt->execute();
$svc = $stmt->get_result()->fetch_assoc();
$conn->close();

if (!$svc) { http_response_code(404); require __DIR__ . '/404.php'; exit; }

$current_page = 'services';
$seo = [
    'title'       => htmlspecialchars($svc['title']) . ' | ' . SITE_NAME . ' — Ireland',
    'description' => htmlspecialchars($svc['short_desc']),
    'canonical'   => SITE_URL . '/services/' . $svc['slug'] . '/',
];
$schema_json = '<script type="application/ld+json">{"@context":"https://schema.org","@type":"Service","name":"' . htmlspecialchars($svc['title']) . '","description":"' . htmlspecialchars($svc['short_desc']) . '","provider":{"@type":"LocalBusiness","name":"' . SITE_NAME . '","url":"' . SITE_URL . '","telephone":"' . SITE_PHONE . '"},"areaServed":{"@type":"Country","name":"Ireland"},"url":"' . $seo['canonical'] . '"}</script>';

require_once __DIR__ . '/../includes/header.php';
?>
<!-- Hero image -->
<section style="padding-top:100px;background:var(--c-dark-bg);">
  <div class="container">
    <nav style="font-size:13px;color:rgba(255,255,255,.5);padding:20px 0;" aria-label="breadcrumb">
      <a href="<?= $bp ?>/" style="color:rgba(255,255,255,.6);">Home</a> /
      <a href="<?= $bp ?>/services/" style="color:rgba(255,255,255,.6);">Services</a> /
      <span style="color:rgba(255,255,255,.8);"><?= htmlspecialchars($svc['title']) ?></span>
    </nav>
  </div>
</section>

<section class="section-light">
  <div class="container">
    <div class="two-col">
      <div class="two-col-img fade-up">
        <img src="<?= $bp ?>/assets/images/<?= htmlspecialchars($svc['image']) ?>" alt="<?= htmlspecialchars($svc['title']) ?> Ireland" loading="lazy" onerror="this.src='<?= $bp ?>/assets/images/doors-banner.jpg'">
      </div>
      <div class="two-col-text fade-up">
        <p class="eyebrow">Service Detail</p>
        <h1><?= htmlspecialchars($svc['title']) ?></h1>
        <p style="font-size:18px;color:#555;margin-bottom:24px;"><?= htmlspecialchars($svc['short_desc']) ?></p>
        <p>At <?= SITE_NAME ?>, we provide professional <?= strtolower(htmlspecialchars($svc['title'])) ?> services for commercial and industrial premises across Ireland. Our certified engineers ensure every job meets Irish Building Regulations and EN standards.</p>
        <ul class="tick-list">
          <li>Fully certified and insured engineers</li>
          <li>ADSA &amp; DHF accredited</li>
          <li>24/7 emergency callout available</li>
          <li>Nationwide coverage across Ireland</li>
          <li>Free site survey — no obligation</li>
          <li>Planned Preventative Maintenance contracts</li>
        </ul>
        <div class="d-flex gap-3 mt-3 flex-wrap">
          <a href="<?= $bp ?>/contact/" class="btn-primary-site">Get a Free Quote</a>
          <a href="tel:<?= SITE_PHONE ?>" class="btn-outline-site" style="color:var(--c-primary);border-color:var(--c-primary);"><i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Related services -->
<section class="section-dark">
  <div class="container">
    <div class="section-head center"><p class="eyebrow">More Services</p><h2>Other Services We Provide</h2></div>
    <div class="services-grid">
      <?php
      $all = db_services();
      $related = array_filter($all, fn($s) => $s['slug'] !== $svc['slug']);
      $related = array_slice(array_values($related), 0, 4);
      foreach ($related as $r): ?>
      <div class="svc-card fade-up">
        <div class="svc-img"><img src="<?= $bp ?>/assets/images/<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['title']) ?>" loading="lazy" onerror="this.src='<?= $bp ?>/assets/images/doors-banner.jpg'"></div>
        <div class="svc-body">
          <div class="svc-icon"><i class="bi <?= htmlspecialchars($r['icon']) ?>"></i></div>
          <h3><?= htmlspecialchars($r['title']) ?></h3>
          <p><?= htmlspecialchars($r['short_desc']) ?></p>
          <a href="<?= $bp ?>/services/<?= htmlspecialchars($r['slug']) ?>/" class="svc-link">Learn More <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
