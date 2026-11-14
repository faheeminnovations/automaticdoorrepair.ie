<?php
/**
 * Services / Products listing — automaticdoorrepair.ie
 * SEO target: "automatic door services Ireland", "commercial door installation Ireland"
 */
require_once __DIR__ . '/../includes/functions.php';
$bp           = BASE_PATH;
$current_page = 'services';

$seo = [
    'title'       => 'Automatic Door Repair Services Ireland | All Door Types — Elite Tech Precision',
    'description' => 'Professional automatic door repair services across Ireland — sliding doors, roller shutters, fire doors, steel doors & more. ADSA certified. 24/7 emergency repair. Free diagnosis.',
    'keywords'    => 'automatic door repair services Ireland, sliding door repair Ireland, roller shutter repair Ireland, fire door repair Ireland, emergency door repair Ireland, automatic door maintenance Ireland',
    'canonical'   => SITE_URL . '/services/',
];

$services    = db_services();
$schema_list = array_map(fn($s, $i) => [
    '@type'    => 'ListItem',
    'position' => $i + 1,
    'item'     => [
        '@type'       => 'Service',
        '@id'         => SITE_URL . '/services/' . $s['slug'] . '/',
        'name'        => $s['title'],
        'description' => $s['short_desc'],
        'url'         => SITE_URL . '/services/' . $s['slug'] . '/',
        'provider'    => ['@type' => 'LocalBusiness', 'name' => SITE_NAME],
        'areaServed'  => ['@type' => 'Country', 'name' => 'Ireland'],
    ],
], $services, array_keys($services));

$schema_json = '<script type="application/ld+json">' . json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => 'Automatic Door Services Ireland — Elite Tech Precision Ltd',
    'description'     => 'Complete commercial door products and services across Ireland.',
    'url'             => SITE_URL . '/services/',
    'numberOfItems'   => count($services),
    'itemListElement' => $schema_list,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero-sm">
  <div class="container">
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
      <a href="<?= $bp ?>/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <span>Products &amp; Services</span>
    </nav>
    <h1>Automatic Door Repair Services Across Ireland</h1>
    <p class="page-hero-sub">
      Professional repair, servicing and maintenance for every type of automatic door system — fast response, all brands, all 32 counties.
    </p>
  </div>
</section>

<!-- Intro strip -->
<section style="background:#fff;padding:40px 0;border-bottom:1px solid rgba(0,0,0,.07);">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px;text-align:center;">
      <?php
      $highlights = [
        ['bi-award-fill',         '22+ Years',       'Industry experience'],
        ['bi-shield-fill-check',  'ADSA &amp; DHF',  'Certified engineers'],
        ['bi-clock-fill',         '24/7',            'Emergency callout'],
        ['bi-geo-alt-fill',       'All 32 Counties', 'Ireland-wide coverage'],
      ];
      foreach ($highlights as [$icon, $val, $lbl]): ?>
      <div>
        <i class="bi <?= $icon ?>" style="font-size:28px;color:var(--c-accent);display:block;margin-bottom:8px;"></i>
        <strong style="display:block;font-size:1.3rem;color:var(--c-primary);"><?= $val ?></strong>
        <span style="font-size:13px;color:#666;"><?= $lbl ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Services Grid -->
<section class="section-dark" aria-labelledby="services-list-h2">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Our Repair Services</p>
      <h2 id="services-list-h2">Every Door System Repaired — All Makes, All Ireland</h2>
      <p class="section-sub">
        From automatic sliding doors to fire-rated roller shutters — we repair, service and maintain
        every type of commercial door system across Ireland. Fast response. All brands. All 32 counties.
      </p>
    </div>
    <div class="services-grid">
      <?php foreach ($services as $svc):
        $imgSrc = $bp . '/assets/images/' . htmlspecialchars($svc['image']);
      ?>
      <div class="svc-card fade-up" itemscope itemtype="https://schema.org/Service">
        <div class="svc-img">
          <img src="<?= $imgSrc ?>"
               alt="<?= htmlspecialchars($svc['title']) ?> — Ireland"
               loading="lazy"
               onerror="this.src='<?= $bp ?>/assets/images/doors-banner.jpg'"
               itemprop="image">
        </div>
        <div class="svc-body">
          <div class="svc-icon"><i class="bi <?= htmlspecialchars($svc['icon']) ?>"></i></div>
          <h3 itemprop="name"><?= htmlspecialchars($svc['title']) ?></h3>
          <p itemprop="description"><?= htmlspecialchars($svc['short_desc']) ?></p>
          <a href="<?= $bp ?>/services/<?= htmlspecialchars($svc['slug']) ?>/"
             class="svc-link"
             aria-label="Learn more about <?= htmlspecialchars($svc['title']) ?> in Ireland"
             itemprop="url">
            Learn More <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Why Us strip -->
<section class="section-light" aria-labelledby="why-services-h2">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Why Elite Tech Precision</p>
      <h2 id="why-services-h2">The Right Choice for Automatic Doors in Ireland</h2>
    </div>
    <div class="why-grid">
      <?php
      $points = [
        ['bi-tools',         'All Makes Serviced',         'We repair and maintain all major automatic door brands including CAME, GEZE, Tormax, Besam and Horton.'],
        ['bi-patch-check',   'Certified Installations',    'Every installation is completed to EN 16005 and Irish Building Regulations with full certification provided.'],
        ['bi-lightning-fill','Fast Emergency Response',    'Our 24/7 service means a certified engineer can be on-site within hours for breakdown emergencies anywhere in Ireland.'],
        ['bi-chat-quote',    'Free Written Quotation',     'We carry out a full on-site assessment and provide a detailed, transparent quotation with no hidden costs.'],
      ];
      foreach ($points as [$icon, $title, $text]): ?>
      <div class="why-card fade-up">
        <div class="why-icon"><i class="bi <?= $icon ?>"></i></div>
        <h3><?= $title ?></h3>
        <p><?= $text ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section" aria-label="Request a free automatic door quote Ireland">
  <div class="container">
    <div class="cta-inner">
      <div>
        <h2>Need an Automatic Door Service in Ireland?</h2>
        <p>Free on-site survey. No obligation. 24/7 emergency callout available across all 32 counties.</p>
      </div>
      <div class="cta-btns">
        <a href="<?= $bp ?>/contact/" class="btn-primary-site">Request Free Quote</a>
        <a href="tel:<?= SITE_PHONE ?>" class="btn-secondary-site">
          <i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?>
        </a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
