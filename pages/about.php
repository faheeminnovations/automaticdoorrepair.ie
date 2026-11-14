<?php
/**
 * About Us — automaticdoorrepair.ie
 * SEO target: "automatic door company Ireland", "about Elite Tech Precision"
 */
require_once __DIR__ . '/../includes/functions.php';
$bp           = BASE_PATH;
$current_page = 'about';

$seo = [
    'title'       => 'About Us | Elite Tech Precision Ltd — Automatic Door Repair Specialists Ireland Since ' . SITE_FOUNDED,
    'description' => 'Founded in 2003, Elite Tech Precision Ltd is Ireland\'s trusted automatic door repair, supply and installation company. ADSA &amp; DHF certified engineers. 22+ years experience. Naas, Co. Kildare.',
    'keywords'    => 'automatic door company Ireland, about Elite Tech Precision, automatic door specialists Ireland, ADSA certified door company, automatic door repair Kildare',
    'canonical'   => SITE_URL . '/about/',
];

$schema_json = '<script type="application/ld+json">' . json_encode([
    '@context'      => 'https://schema.org',
    '@type'         => ['LocalBusiness','Organization'],
    '@id'           => SITE_URL . '/#business',
    'name'          => SITE_NAME,
    'url'           => SITE_URL,
    'logo'          => SITE_URL . '/assets/images/logo.png',
    'foundingDate'  => SITE_FOUNDED,
    'description'   => 'Ireland\'s specialist automatic door repair, supply and installation company. ADSA & DHF certified, serving all 32 counties since 2003.',
    'telephone'     => SITE_PHONE,
    'email'         => SITE_EMAIL,
    'address'       => [
        '@type'           => 'PostalAddress',
        'streetAddress'   => 'Unit 2A, Sickens Caragh',
        'addressLocality' => 'Naas',
        'addressRegion'   => 'Co. Kildare',
        'postalCode'      => 'W91ND9F',
        'addressCountry'  => 'IE',
    ],
    'areaServed'    => ['@type' => 'Country', 'name' => 'Ireland'],
    'numberOfEmployees' => ['@type' => 'QuantitativeValue', 'value' => 15],
    'knowsAbout'    => ['Automatic Door Repair', 'Automatic Door Installation', 'Roller Shutters', 'Fire Rated Doors', 'Aluminium Shopfronts'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero-sm">
  <div class="container">
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
      <a href="<?= $bp ?>/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <span>About Us</span>
    </nav>
    <h1>About Elite Tech Precision Ltd — Ireland's Automatic Door Repair Specialists</h1>
    <p class="page-hero-sub">Ireland's trusted automatic door repair company since <?= SITE_FOUNDED ?> — repairing, servicing and maintaining automatic doors, roller shutters and fire doors across all 32 counties.</p>
  </div>
</section>

<!-- Main About Section -->
<section class="section-light" aria-labelledby="about-h2">
  <div class="container">
    <div class="two-col">
      <div class="two-col-img fade-up">
        <img src="<?= $bp ?>/assets/images/about-team.jpg"
             alt="Elite Tech Precision Ltd automatic door engineers — Naas, Co. Kildare, Ireland"
             loading="lazy"
             onerror="this.src='<?= $bp ?>/assets/images/team.jpg'">
        <div class="years-badge">
          <span class="num"><?= SITE_YEARS_EXP ?>+</span>
          <span class="lbl">Years in Business</span>
        </div>
      </div>
      <div class="two-col-text fade-up">
        <p class="eyebrow">Ireland's Automatic Door Repair Specialists</p>
        <h2 id="about-h2">
          <?= SITE_YEARS_EXP ?>+ Years Specialising in Automatic Door Repair Across Ireland
        </h2>
        <p>
          Established in <?= SITE_FOUNDED ?>, <strong>Elite Tech Precision Ltd</strong> is Ireland's leading
          automatic door repair company. Based in <strong>Naas, Co. Kildare</strong>, our ADSA and DHF
          certified engineers repair, service and maintain every type of automatic door system —
          from automatic sliding and swing doors to roller shutters, fire doors and commercial door systems.
        </p>
        <p>
          We cover <strong>all 32 counties</strong> with fast response times, accurate fault diagnosis
          and first-time fix rates that keep your business running with minimal disruption.
          No job is too complex — we carry a full stock of spare parts for all major brands
          including CAME, GEZE, Tormax, Besam, Horton and Stanley.
        </p>
        <ul class="tick-list">
          <li>Automatic door repair specialist — all makes, all models</li>
          <li>ADSA &amp; DHF accredited engineers — fully insured</li>
          <li>24/7 reactive emergency repair callout across Ireland</li>
          <li>Planned Preventative Maintenance (PPM) contracts</li>
          <li>Free on-site diagnosis &amp; no-obligation repair quotation</li>
          <li>All 32 counties covered — rapid nationwide response</li>
          <li>EN 16005 &amp; Irish Building Regulation compliant repairs</li>
          <li>Full spare parts stock — CAME, GEZE, Tormax, Besam &amp; more</li>
        </ul>
        <div class="about-certs">
          <img src="<?= $bp ?>/assets/images/cert-adsa.png" alt="ADSA Accredited Member — Elite Tech Precision" onerror="this.style.display='none'">
          <img src="<?= $bp ?>/assets/images/cert-dhf.png"  alt="DHF Accredited Member — Elite Tech Precision" onerror="this.style.display='none'">
        </div>
        <a href="<?= $bp ?>/contact/" class="btn-primary-site mt-3 d-inline-block">
          Get a Free Quote <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- What We Cover -->
<section class="section-dark" aria-labelledby="services-cover-h2">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">What We Repair</p>
      <h2 id="services-cover-h2">Every Automatic Door System Repaired — All Makes, All Ireland</h2>
      <p class="section-sub">
        From a broken sliding door sensor to a seized roller shutter motor, our certified repair
        engineers fix every type of commercial automatic door system across Ireland.
      </p>
    </div>
    <div class="why-grid">
      <?php
      $covers = [
        ['bi-door-open',          'Automatic Sliding Doors',         'EN 16005 compliant automatic sliding door supply, installation and repair for retail, healthcare and commercial premises.'],
        ['bi-columns-gap',        'Roller Shutters',                  'Industrial and commercial roller shutters — galvanised steel, aluminium, fire-rated and perforated options available.'],
        ['bi-shield-fill-check',  'Fire Rated Doors &amp; Screens',   'Fire-rated steel doors, fire exit doors and glazed screens tested to EN 1634-1 — protecting lives and meeting regulations.'],
        ['bi-shop',               'Aluminium Shopfronts',             'High-grade aluminium shopfronts with toughened glass, security locking and seamless automatic door integration.'],
        ['bi-building-fill',      'Commercial Aluminium Doors',       'Swing, sliding and bi-fold aluminium doors for offices, retail units, healthcare facilities and educational sites.'],
        ['bi-calendar-check',     'Planned Maintenance (PPM)',         'Annual and bi-annual PPM contracts ensuring all your door systems remain legally compliant and reliably operational.'],
        ['bi-clock-fill',         '24-Hour Emergency Callout',        'Round-the-clock emergency callout for automatic door breakdowns across all of Ireland — fast, reliable, guaranteed.'],
        ['bi-grid-3x3-gap',       'Curtain Walling &amp; Revolving',  'Architectural curtain walling and revolving door systems for hotels, airports, shopping centres and prestige offices.'],
      ];
      foreach ($covers as [$icon, $title, $text]): ?>
      <div class="why-card fade-up">
        <div class="why-icon"><i class="bi <?= $icon ?>"></i></div>
        <h3><?= $title ?></h3>
        <p><?= $text ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-5">
      <a href="<?= $bp ?>/services/" class="btn-primary-site">
        View All Products &amp; Services <i class="bi bi-arrow-right ms-2"></i>
      </a>
    </div>
  </div>
</section>

<!-- Areas We Serve -->
<section class="section-light" aria-labelledby="areas-h2">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Service Areas</p>
      <h2 id="areas-h2">Automatic Door Repair &amp; Installation Across All of Ireland</h2>
      <p class="section-sub">
        From our base in Naas, Co. Kildare, we provide fast-response automatic door services
        to businesses across every county in Ireland.
      </p>
    </div>
    <div class="why-grid">
      <?php
      $areas = [
        'Dublin','Kildare','Wicklow','Wexford','Carlow','Kilkenny',
        'Laois','Offaly','Westmeath','Meath','Louth','Monaghan',
        'Cork','Limerick','Galway','Waterford','Clare','Kerry',
        'Tipperary','Roscommon','Mayo','Sligo','Donegal','Cavan',
      ];
      foreach ($areas as $county): ?>
      <div class="why-card fade-up" style="text-align:center;padding:16px 12px;">
        <div class="why-icon" style="margin:0 auto 10px;"><i class="bi bi-geo-alt-fill"></i></div>
        <h3 style="font-size:14px;">Co. <?= $county ?></h3>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section" aria-label="Contact automatic door company Ireland">
  <div class="container">
    <div class="cta-inner">
      <div>
        <h2>Need Automatic Door Repair or Installation in Ireland?</h2>
        <p>Free on-site survey. No obligation. 24/7 emergency callout available nationwide.</p>
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
