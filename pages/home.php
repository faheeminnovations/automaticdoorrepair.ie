<?php
/**
 * Homepage — automaticdoorrepair.ie
 */
require_once __DIR__ . '/../includes/functions.php';

// ── Form processing (PRG) ──────────────────────────────────────
$captcha_q = captcha_generate();
$sent = false;
if (!empty($_SESSION['form_sent'])) { $sent = true; unset($_SESSION['form_sent']); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cf_submit'])) {
    $name    = sanitise($_POST['name']    ?? '');
    $email   = sanitise($_POST['email']   ?? '');
    $phone   = sanitise($_POST['phone']   ?? '');
    $service = sanitise($_POST['service'] ?? '');
    $message = sanitise($_POST['message'] ?? '');
    $captcha = sanitise($_POST['captcha'] ?? '');

    if (!$name)                      $error = 'Please enter your full name.';
    elseif (!is_valid_email($email)) $error = 'Please enter a valid email address.';
    elseif (!is_valid_phone($phone)) $error = 'Please enter a valid Irish phone number.';
    elseif (!$message)               $error = 'Please enter your message.';
    elseif (!captcha_verify($captcha)) { $error = 'Incorrect security answer.'; $captcha_q = captcha_generate(); }
    else {
        send_enquiry_email(compact('name','email','phone','service','message'));
        $_SESSION['form_sent'] = true;
        header('Location: ' . BASE_PATH . '/#quote');
        exit;
    }
}

$bp = BASE_PATH;
$current_page = 'home';
$seo = [
    'title'       => setting('seo_title',    'Elite Tech Precision Ltd | Automatic Door Repair Ireland — Supply & Install'),
    'description' => setting('seo_desc',     'Elite Tech Precision Ltd — 22 years supplying, installing & repairing automatic doors, roller shutters, fire doors & aluminium shopfronts across Ireland. Call 045-875406.'),
    'keywords'    => setting('seo_keywords', ''),
    'canonical'   => SITE_URL . '/',
];
$schema_json = '<script type="application/ld+json">
{"@context":"https://schema.org","@type":"LocalBusiness","name":"' . SITE_NAME . '","url":"' . SITE_URL . '","logo":"' . SITE_URL . '/assets/images/logo.png","description":"' . $seo['description'] . '","foundingDate":"' . SITE_FOUNDED . '","telephone":"' . SITE_PHONE . '","email":"' . SITE_EMAIL . '","address":{"@type":"PostalAddress","streetAddress":"Unit 2A, Sickens Caragh","addressLocality":"Naas","addressRegion":"Co. Kildare","postalCode":"W91ND9F","addressCountry":"IE"},"geo":{"@type":"GeoCoordinates","latitude":53.245162,"longitude":-6.7367519},"areaServed":{"@type":"Country","name":"Ireland"},"openingHoursSpecification":{"@type":"OpeningHoursSpecification","dayOfWeek":["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],"opens":"00:00","closes":"23:59"},"sameAs":["' . SITE_FACEBOOK . '","' . SITE_INSTAGRAM . '","' . SITE_YOUTUBE . '","' . SITE_LINKEDIN . '","' . SITE_TIKTOK . '"],"priceRange":"€€"}
</script>';

require_once __DIR__ . '/../includes/header.php';

// ── Pull all sections from the old single page ──
?>
<!-- HERO -->
<section class="hero" id="hero" aria-label="Hero">
  <div class="hero-bg">
    <img src="<?= $bp ?>/assets/images/hero-1.jpeg" alt="Automatic doors installation Ireland" loading="eager" fetchpriority="high">
    <div class="hero-overlay"></div>
  </div>
  <div class="container"><div class="hero-split">
    <div class="hero-left">
      <div class="hero-tag"><i class="bi bi-patch-check-fill me-2"></i><?= SITE_YEARS_EXP ?> Years of Experience — Since <?= SITE_FOUNDED ?></div>
      <h1><?= nl2br(htmlspecialchars(setting('hero_heading', "Automatic Door Repair —\nSupply, Installation\n& Maintenance Ireland"))) ?></h1>
      <p class="hero-sub"><?= htmlspecialchars(setting('hero_sub', SITE_NAME . ' provides expert automatic door repair, supply and installation services across Ireland.')) ?></p>
      <div class="hero-ctas">
        <a href="<?= $bp ?>/services/" class="btn-primary-site">View Services <i class="bi bi-arrow-right ms-1"></i></a>
        <a href="tel:<?= SITE_PHONE ?>" class="btn-secondary-site"><i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?></a>
      </div>
      <div class="hero-badges">
        <span><i class="bi bi-clock-fill"></i> 24/7 Emergency</span>
        <span><i class="bi bi-shield-fill-check"></i> ADSA &amp; DHF Certified</span>
        <span><i class="bi bi-geo-alt-fill"></i> Nationwide Ireland</span>
        <span><i class="bi bi-award-fill"></i> Est. <?= SITE_FOUNDED ?></span>
      </div>
    </div>
    <!-- Quote form -->
    <div class="hero-form-box" id="quote">
      <div class="hero-form-inner">
        <p class="hero-form-label">Free Quote — No Obligation</p>
        <h2 class="hero-form-title">Request Your Free Quote</h2>
        <?php if ($sent): ?>
        <div class="form-success"><i class="bi bi-check-circle-fill me-2"></i><strong>Thank you!</strong> We'll be in touch very shortly.</div>
        <?php else: ?>
        <?php if (!empty($error)): ?><div class="form-error"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?></div><?php endif; ?>
        <form method="POST" action="<?= $bp ?>/#quote" id="heroForm" novalidate>
          <input type="hidden" name="cf_submit" value="1">
          <div class="hf-group"><label for="hf_name">Full Name <span>*</span></label><input type="text" id="hf_name" name="name" placeholder="Your full name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"></div>
          <div class="hf-row">
            <div class="hf-group"><label for="hf_email">Email <span>*</span></label><input type="email" id="hf_email" name="email" placeholder="your@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></div>
            <div class="hf-group"><label for="hf_phone">Phone</label><input type="tel" id="hf_phone" name="phone" placeholder="+353 xx xxx xxxx" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"></div>
          </div>
          <div class="hf-group"><label for="hf_service">Service Required</label>
            <select id="hf_service" name="service">
              <option value="">— Select a service —</option>
              <?php foreach (db_services() as $s): ?>
              <option value="<?= htmlspecialchars($s['title']) ?>" <?= (($_POST['service'] ?? '') === $s['title']) ? 'selected' : '' ?>><?= htmlspecialchars($s['title']) ?></option>
              <?php endforeach; ?>
              <option value="Emergency Callout">Emergency Callout</option>
              <option value="PPM Contract">PPM Contract</option>
              <option value="General Enquiry">General Enquiry</option>
            </select>
          </div>
          <div class="hf-group"><label for="hf_message">Message <span>*</span></label><textarea id="hf_message" name="message" rows="4" placeholder="Describe your requirements..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea></div>
          <div class="hf-group captcha-group"><label for="hf_captcha"><i class="bi bi-shield-check me-1"></i>Security Check: What is <strong><?= $captcha_q ?></strong>? <span>*</span></label><input type="number" id="hf_captcha" name="captcha" placeholder="Answer" required min="1" max="18" autocomplete="off"></div>
          <button type="submit" class="btn-submit w-100"><i class="bi bi-send me-2"></i>Send Enquiry</button>
        </form>
        <?php endif; ?>
        <div class="hero-form-footer"><i class="bi bi-telephone-fill me-1"></i>Or call us: <a href="tel:<?= SITE_PHONE ?>"><?= SITE_PHONE_DISPLAY ?></a></div>
      </div>
    </div>
  </div></div>
</section>

<!-- STATS -->
<div class="stats-strip"><div class="container"><div class="stats-grid">
  <div class="stat-item"><span class="stat-num" data-count="22" data-suffix="+">0+</span><span class="stat-lbl">Years Experience</span></div>
  <div class="stat-item"><span class="stat-num" data-count="5000" data-suffix="+">0+</span><span class="stat-lbl">Doors Serviced</span></div>
  <div class="stat-item"><span class="stat-num" data-count="500" data-suffix="+">0+</span><span class="stat-lbl">Happy Clients</span></div>
  <div class="stat-item"><span class="stat-num" data-count="32">0</span><span class="stat-lbl">Counties Covered</span></div>
</div></div></div>

<!-- ABOUT (summary) -->
<section class="section-light" id="about">
  <div class="container"><div class="two-col">
    <div class="two-col-img fade-up">
      <img src="<?= $bp ?>/assets/images/about-team.jpg" alt="Elite Tech Precision team at work" loading="lazy" onerror="this.src='<?= $bp ?>/assets/images/team.jpg'">
      <div class="years-badge"><span class="num"><?= SITE_YEARS_EXP ?>+</span><span class="lbl">Years in Business</span></div>
    </div>
    <div class="two-col-text fade-up">
      <p class="eyebrow">About Us</p>
      <h2><?= htmlspecialchars(setting('about_heading', 'We Have 22 Years of Experience in Automatic Door Repair')) ?></h2>
      <p><?= htmlspecialchars(setting('about_text', 'Founded in 2003, Elite Tech Precision Ltd specialises in Automatic Door Repair services throughout Ireland.')) ?></p>
      <ul class="tick-list">
        <li>Manufacturer, supplier &amp; installer — one point of contact</li>
        <li>ADSA &amp; DHF accredited engineers</li>
        <li>24/7 reactive breakdown &amp; emergency callout</li>
        <li>Planned Preventative Maintenance (PPM) contracts</li>
        <li>Free site survey — no obligation</li>
        <li>Nationwide coverage across all of Ireland</li>
      </ul>
      <div class="about-certs"><img src="<?= $bp ?>/assets/images/cert-adsa.png" alt="ADSA Member" onerror="this.style.display='none'"><img src="<?= $bp ?>/assets/images/cert-dhf.png" alt="DHF Member" onerror="this.style.display='none'"></div>
      <a href="<?= $bp ?>/about/" class="btn-primary-site mt-3 d-inline-block">More About Us <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
  </div></div>
</section>

<!-- SERVICES (preview cards) -->
<section class="section-dark" id="services">
  <div class="container">
    <div class="section-head center"><p class="eyebrow">What We Do</p><h2>Our Services</h2><p class="section-sub">From emergency repairs to full new installations — we cover every type of commercial door system across Ireland.</p></div>
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
    <div class="text-center mt-5"><a href="<?= $bp ?>/services/" class="btn-primary-site">View All Services <i class="bi bi-arrow-right ms-2"></i></a></div>
  </div>
</section>

<!-- WHY US -->
<section class="section-light" id="why-us">
  <div class="container"><div class="section-head center"><p class="eyebrow">Why Elite Tech</p><h2>Why Choose Us?</h2></div>
  <div class="why-grid">
    <?php foreach ([
      ['bi-clock-fill','24/7 Emergency Response','On call day and night, 365 days a year.'],
      ['bi-geo-alt-fill','Nationwide Ireland','All 32 counties covered with fast response.'],
      ['bi-shield-fill-check','ADSA & DHF Certified','Fully insured, certified and compliant.'],
      ['bi-tools','All Makes & Models','All major automatic door brands serviced.'],
      ['bi-calendar-check','PPM Contracts','Preventative maintenance avoids breakdowns.'],
      ['bi-star-fill','5-Star Google Rated','Rated 5 stars for quality and professionalism.'],
      ['bi-person-fill','One Point of Contact','Same team from first call to final handover.'],
      ['bi-clipboard-check','Free Site Survey','Free on-site assessment and written quote.'],
    ] as [$icon, $title, $text]): ?>
    <div class="why-card fade-up"><div class="why-icon"><i class="bi <?= $icon ?>"></i></div><h3><?= $title ?></h3><p><?= $text ?></p></div>
    <?php endforeach; ?>
  </div></div>
</section>

<!-- PROCESS -->
<section class="section-dark" id="process">
  <div class="container"><div class="section-head center"><p class="eyebrow">Our Process</p><h2>From Enquiry to Fitted Door</h2></div>
  <div class="process-grid">
    <?php foreach ([
      ['1','Call or Enquire','Call us or fill out the quote form with your premises and requirements.'],
      ['2','Free Site Survey','We assess the job on-site and provide a clear, no-obligation quote.'],
      ['3','Install / Repair','Your door is built and fitted by our own team, on schedule.'],
      ['4','Certify & Hand Over','Certified, fully compliant door with full documentation.'],
    ] as [$num, $title, $text]): ?>
    <div class="process-step fade-up"><div class="step-num"><?= $num ?></div><h3><?= $title ?></h3><p><?= $text ?></p></div>
    <?php endforeach; ?>
  </div></div>
</section>

<!-- CTA -->
<section class="cta-section"><div class="container"><div class="cta-inner">
  <div><h2>Need a Door Repair or Installation?</h2><p>Free quote, no obligation. Available 24/7 for emergency callouts.</p></div>
  <div class="cta-btns"><a href="<?= $bp ?>/contact/" class="btn-primary-site">Request Free Quote</a><a href="tel:<?= SITE_PHONE ?>" class="btn-outline-site"><i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?></a></div>
</div></div></section>

<!-- CLIENTS -->
<section class="section-light" id="clients"><div class="container">
  <div class="section-head center"><p class="eyebrow">Trusted By</p><h2>Some of Our Valued Clients</h2></div>
  <div class="clients-grid">
    <?php foreach ([
      ['client-tesco.png','Tesco'],['client-boots.png','Boots'],['client-centra.png','Centra'],
      ['client-vodafone.png','Vodafone'],['client-decathlon.png','Decathlon'],['client-opw.png','OPW'],
      ['client-police.png','An Garda Síochána'],['client-murphy.png','Murphy'],
      ['client-allcare.png','AllCare Pharmacy'],['client-applegreen.png','Apple Green'],['client-bank.png','Bank of Ireland'],
    ] as [$file, $name]): ?>
    <div class="client-logo"><img src="<?= $bp ?>/assets/images/<?= $file ?>" alt="<?= htmlspecialchars($name) ?>" loading="lazy" onerror="this.closest('.client-logo').style.display='none'"></div>
    <?php endforeach; ?>
  </div>
</div></section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
