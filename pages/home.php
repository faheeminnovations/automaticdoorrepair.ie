<?php
/**
 * Homepage — automaticdoorrepair.ie
 * SEO-optimised: targets "automatic door repair Ireland", "automatic door installation Ireland"
 */
require_once __DIR__ . '/../includes/functions.php';

$captcha_q = captcha_generate();
$sent  = false;
$error = '';
if (!empty($_SESSION['form_sent'])) { $sent = true; unset($_SESSION['form_sent']); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cf_submit'])) {
    $name    = sanitise($_POST['name']    ?? '');
    $email   = sanitise($_POST['email']   ?? '');
    $phone   = sanitise($_POST['phone']   ?? '');
    $service = sanitise($_POST['service'] ?? '');
    $message = sanitise($_POST['message'] ?? '');
    $captcha = sanitise($_POST['captcha'] ?? '');

    if (!$name)                        $error = 'Please enter your full name.';
    elseif (!is_valid_email($email))   $error = 'Please enter a valid email address.';
    elseif (!is_valid_phone($phone))   $error = 'Please enter a valid Irish phone number.';
    elseif (!$message)                 $error = 'Please describe your requirements.';
    elseif (!captcha_verify($captcha)) { $error = 'Incorrect security answer — please try again.'; $captcha_q = captcha_generate(); }
    else {
        send_enquiry_email(compact('name','email','phone','service','message'));
        $_SESSION['form_sent'] = true;
        header('Location: ' . BASE_PATH . '/#quote');
        exit;
    }
}

$bp           = BASE_PATH;
$current_page = 'home';

$seo = [
    'title'       => 'Automatic Door Repair Ireland | Supply, Install &amp; Maintenance — Elite Tech Precision Ltd',
    'description' => 'Elite Tech Precision Ltd — Ireland\'s trusted automatic door repair, supply and installation specialists since 2003. ADSA &amp; DHF certified. 24/7 emergency callout. Free site survey. Call 045-875406.',
    'keywords'    => 'automatic door repair Ireland, automatic door installation Ireland, automatic sliding door repair, commercial door repair Ireland, automatic door maintenance Ireland, emergency door repair Ireland, automatic door company Ireland, door repair Kildare, sliding door installation Dublin',
    'canonical'   => SITE_URL . '/',
];

$schema_json = '<script type="application/ld+json">' . json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'LocalBusiness',
    '@id'         => SITE_URL . '/#business',
    'name'        => SITE_NAME,
    'alternateName' => 'Elite Tech Precision',
    'url'         => SITE_URL,
    'logo'        => SITE_URL . '/assets/images/logo.png',
    'image'       => SITE_URL . '/assets/images/hero-1.jpeg',
    'description' => 'Ireland\'s leading automatic door repair, supply and installation company. ADSA & DHF certified engineers with 22+ years experience serving commercial and industrial clients nationwide.',
    'foundingDate' => SITE_FOUNDED,
    'telephone'   => SITE_PHONE,
    'email'       => SITE_EMAIL,
    'address'     => [
        '@type'           => 'PostalAddress',
        'streetAddress'   => 'Unit 2A, Sickens Caragh',
        'addressLocality' => 'Naas',
        'addressRegion'   => 'Co. Kildare',
        'postalCode'      => 'W91ND9F',
        'addressCountry'  => 'IE',
    ],
    'geo' => ['@type' => 'GeoCoordinates', 'latitude' => 53.245162, 'longitude' => -6.7367519],
    'areaServed' => ['@type' => 'Country', 'name' => 'Ireland'],
    'openingHoursSpecification' => [
        '@type'     => 'OpeningHoursSpecification',
        'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
        'opens'     => '00:00',
        'closes'    => '23:59',
    ],
    'hasOfferCatalog' => [
        '@type' => 'OfferCatalog',
        'name'  => 'Automatic Door Services Ireland',
        'itemListElement' => array_map(fn($s) => [
            '@type'       => 'Offer',
            'itemOffered' => ['@type' => 'Service', 'name' => $s['title']],
        ], array_slice(db_services(), 0, 8)),
    ],
    'sameAs'     => [SITE_FACEBOOK, SITE_INSTAGRAM, SITE_YOUTUBE, SITE_LINKEDIN, SITE_TIKTOK],
    'priceRange' => '€€',
    'aggregateRating' => ['@type' => 'AggregateRating', 'ratingValue' => '5', 'reviewCount' => '47'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';

require_once __DIR__ . '/../includes/header.php';
?>

<!-- ═══ HERO ═══ -->
<section class="hero" id="hero" aria-label="Automatic Door Repair Ireland — Elite Tech Precision">
  <div class="hero-bg">
    <img src="<?= $bp ?>/assets/images/hero-1.jpeg"
         alt="Automatic door repair and installation by Elite Tech Precision Ltd, Ireland"
         loading="eager" fetchpriority="high" width="1920" height="1080">
    <div class="hero-overlay"></div>
  </div>
  <div class="container">
    <div class="hero-split">
      <!-- Left: headline + badges -->
      <div class="hero-left">
        <div class="hero-tag">
          <i class="bi bi-patch-check-fill me-2"></i>
          Trusted Since <?= SITE_FOUNDED ?> — <?= SITE_YEARS_EXP ?>+ Years Serving Ireland
        </div>
        <h1>
          Automatic Door Repair &amp; Installation Ireland
          <span style="display:block;font-size:0.65em;font-weight:600;margin-top:8px;opacity:.85;">
            Supply · Install · Service · Repair — Nationwide
          </span>
        </h1>
        <p class="hero-sub">
          Elite Tech Precision Ltd is Ireland's specialist automatic door repair, supply and installation company.
          We serve retail, healthcare, hospitality and industrial premises across all 32 counties —
          with ADSA &amp; DHF certified engineers available <strong>24/7</strong>.
        </p>
        <div class="hero-ctas">
          <a href="<?= $bp ?>/contact/" class="btn-primary-site">
            Get a Free Quote <i class="bi bi-arrow-right ms-1"></i>
          </a>
          <a href="tel:<?= SITE_PHONE ?>" class="btn-secondary-site">
            <i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?>
          </a>
        </div>
        <div class="hero-badges">
          <span><i class="bi bi-clock-fill"></i> 24/7 Emergency Callout</span>
          <span><i class="bi bi-shield-fill-check"></i> ADSA &amp; DHF Certified</span>
          <span><i class="bi bi-geo-alt-fill"></i> All 32 Counties</span>
          <span><i class="bi bi-award-fill"></i> Est. <?= SITE_FOUNDED ?></span>
          <span><i class="bi bi-star-fill"></i> 5-Star Rated</span>
        </div>
      </div>

      <!-- Right: enquiry form -->
      <div class="hero-form-box" id="quote">
        <div class="hero-form-inner">
          <p class="hero-form-label">Free Quote — No Obligation</p>
          <h2 class="hero-form-title">Request Your Free Site Survey</h2>
          <?php if ($sent): ?>
          <div class="form-success">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>Thank you!</strong> We'll be in touch within the hour.
          </div>
          <?php else: ?>
          <?php if ($error): ?>
          <div class="form-error"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?></div>
          <?php endif; ?>
          <form method="POST" action="<?= $bp ?>/#quote" id="heroForm" novalidate>
            <input type="hidden" name="cf_submit" value="1">
            <div class="hf-group">
              <label for="hf_name">Full Name <span>*</span></label>
              <input type="text" id="hf_name" name="name" placeholder="Your full name" required
                     value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>
            <div class="hf-row">
              <div class="hf-group">
                <label for="hf_email">Email Address <span>*</span></label>
                <input type="email" id="hf_email" name="email" placeholder="your@email.com" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
              </div>
              <div class="hf-group">
                <label for="hf_phone">Phone Number</label>
                <input type="tel" id="hf_phone" name="phone" placeholder="+353 xx xxx xxxx"
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
              </div>
            </div>
            <div class="hf-group">
              <label for="hf_service">Service Required</label>
              <select id="hf_service" name="service">
                <option value="">— Select a service —</option>
                <?php foreach (db_services() as $s): ?>
                <option value="<?= htmlspecialchars($s['title']) ?>"
                  <?= (($_POST['service'] ?? '') === $s['title']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($s['title']) ?>
                </option>
                <?php endforeach; ?>
                <option value="Emergency Callout">Emergency Callout</option>
                <option value="PPM Contract">PPM Contract</option>
                <option value="General Enquiry">General Enquiry</option>
              </select>
            </div>
            <div class="hf-group">
              <label for="hf_message">Message <span>*</span></label>
              <textarea id="hf_message" name="message" rows="3"
                        placeholder="Describe your door type, issue or requirements..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
            </div>
            <div class="hf-group captcha-group">
              <label for="hf_captcha">
                <i class="bi bi-shield-check me-1"></i>
                Security: What is <strong><?= $captcha_q ?></strong>? <span>*</span>
              </label>
              <input type="number" id="hf_captcha" name="captcha" placeholder="Your answer"
                     required min="1" max="18" autocomplete="off">
            </div>
            <button type="submit" class="btn-submit w-100">
              <i class="bi bi-send me-2"></i>Send Free Enquiry
            </button>
          </form>
          <?php endif; ?>
          <div class="hero-form-footer">
            <i class="bi bi-telephone-fill me-1"></i>
            Prefer to call? <a href="tel:<?= SITE_PHONE ?>"><?= SITE_PHONE_DISPLAY ?></a> — available 24/7
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ STATS STRIP ═══ -->
<div class="stats-strip" aria-label="Key statistics">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-item">
        <span class="stat-num" data-count="22" data-suffix="+">0+</span>
        <span class="stat-lbl">Years Experience</span>
      </div>
      <div class="stat-item">
        <span class="stat-num" data-count="5000" data-suffix="+">0+</span>
        <span class="stat-lbl">Doors Installed &amp; Repaired</span>
      </div>
      <div class="stat-item">
        <span class="stat-num" data-count="500" data-suffix="+">0+</span>
        <span class="stat-lbl">Satisfied Clients</span>
      </div>
      <div class="stat-item">
        <span class="stat-num" data-count="32">0</span>
        <span class="stat-lbl">Counties Covered</span>
      </div>
    </div>
  </div>
</div>

<!-- ═══ ABOUT SUMMARY ═══ -->
<section class="section-light" id="about" aria-labelledby="about-heading">
  <div class="container">
    <div class="two-col">
      <div class="two-col-img fade-up">
        <img src="<?= $bp ?>/assets/images/about-team.jpg"
             alt="Elite Tech Precision automatic door engineers at work in Ireland"
             loading="lazy"
             onerror="this.src='<?= $bp ?>/assets/images/team.jpg'">
        <div class="years-badge">
          <span class="num"><?= SITE_YEARS_EXP ?>+</span>
          <span class="lbl">Years in Business</span>
        </div>
      </div>
      <div class="two-col-text fade-up">
        <p class="eyebrow">Ireland's Automatic Door Specialists</p>
        <h2 id="about-heading">
          <?= SITE_YEARS_EXP ?>+ Years of Automatic Door Repair &amp; Installation Expertise in Ireland
        </h2>
        <p>
          Founded in <?= SITE_FOUNDED ?>, <strong>Elite Tech Precision Ltd</strong> has grown into one of Ireland's
          most trusted automatic door companies — manufacturing, supplying, installing and maintaining
          the full range of commercial entrance and security door systems.
        </p>
        <p>
          Based in Naas, Co. Kildare, our ADSA and DHF certified engineers operate nationwide,
          delivering fast response times, transparent pricing and long-term support for every client.
        </p>
        <ul class="tick-list">
          <li>Manufacturer, supplier &amp; certified installer — single point of contact</li>
          <li>ADSA &amp; DHF accredited engineers — fully insured</li>
          <li>24/7 reactive emergency callout service across Ireland</li>
          <li>Planned Preventative Maintenance (PPM) contracts available</li>
          <li>Free on-site survey &amp; no-obligation written quotation</li>
          <li>All 32 counties covered with rapid response times</li>
          <li>EN 16005 &amp; Irish Building Regulation compliant installations</li>
        </ul>
        <div class="about-certs">
          <img src="<?= $bp ?>/assets/images/cert-adsa.png" alt="ADSA Accredited Member" onerror="this.style.display='none'">
          <img src="<?= $bp ?>/assets/images/cert-dhf.png"  alt="DHF Accredited Member"  onerror="this.style.display='none'">
        </div>
        <a href="<?= $bp ?>/about/" class="btn-primary-site mt-3 d-inline-block">
          More About Us <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ═══ SERVICES GRID ═══ -->
<section class="section-dark" id="services" aria-labelledby="services-heading">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Our Products &amp; Services</p>
      <h2 id="services-heading">Complete Automatic Door &amp; Shutter Solutions Across Ireland</h2>
      <p class="section-sub">
        From automatic sliding doors to fire-rated shutters — we supply, install and maintain every
        type of commercial entrance system to the highest Irish and European standards.
      </p>
    </div>
    <div class="services-grid">
      <?php foreach (db_services() as $svc): ?>
      <div class="svc-card fade-up">
        <div class="svc-img">
          <img src="<?= $bp ?>/assets/images/<?= htmlspecialchars($svc['image']) ?>"
               alt="<?= htmlspecialchars($svc['title']) ?> — Ireland"
               loading="lazy"
               onerror="this.src='<?= $bp ?>/assets/images/doors-banner.jpg'">
        </div>
        <div class="svc-body">
          <div class="svc-icon"><i class="bi <?= htmlspecialchars($svc['icon']) ?>"></i></div>
          <h3><?= htmlspecialchars($svc['title']) ?></h3>
          <p><?= htmlspecialchars($svc['short_desc']) ?></p>
          <a href="<?= $bp ?>/services/<?= htmlspecialchars($svc['slug']) ?>/"
             class="svc-link" aria-label="Learn more about <?= htmlspecialchars($svc['title']) ?>">
            Learn More <i class="bi bi-arrow-right"></i>
          </a>
        </div>
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

<!-- ═══ WHY US ═══ -->
<section class="section-light" id="why-us" aria-labelledby="why-heading">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Why Choose Elite Tech Precision</p>
      <h2 id="why-heading">Ireland's Most Trusted Automatic Door Company</h2>
      <p class="section-sub">
        With over <?= SITE_YEARS_EXP ?> years serving Irish businesses, here's why leading retailers,
        hospitals and facilities managers choose us every time.
      </p>
    </div>
    <div class="why-grid">
      <?php
      $reasons = [
        ['bi-clock-fill',         '24/7 Emergency Response',       'On call day and night, 365 days a year — rapid response anywhere in Ireland.'],
        ['bi-geo-alt-fill',       'Nationwide Ireland Coverage',    'All 32 counties covered with fast response times from our Kildare base.'],
        ['bi-shield-fill-check',  'ADSA &amp; DHF Certified',       'Fully insured, industry-certified and EN 16005 compliant on every installation.'],
        ['bi-tools',              'All Makes &amp; Models Serviced','We work with every major automatic door brand — CAME, GEZE, Tormax, Besam and more.'],
        ['bi-calendar-check',     'PPM Maintenance Contracts',      'Planned preventative maintenance keeps your doors compliant and avoids costly breakdowns.'],
        ['bi-star-fill',          '5-Star Google Rated',            'Consistently rated 5 stars by our clients for quality, reliability and professionalism.'],
        ['bi-person-fill',        'Single Point of Contact',        'The same expert team handles your project from first call through to final handover.'],
        ['bi-clipboard-check',    'Free Site Survey &amp; Quote',   'We visit your premises, assess the work and provide a written, no-obligation quotation.'],
      ];
      foreach ($reasons as [$icon, $title, $text]): ?>
      <div class="why-card fade-up">
        <div class="why-icon"><i class="bi <?= $icon ?>"></i></div>
        <h3><?= $title ?></h3>
        <p><?= $text ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ PROCESS ═══ -->
<section class="section-dark" id="process" aria-labelledby="process-heading">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Our Simple Process</p>
      <h2 id="process-heading">How We Handle Your Automatic Door Project</h2>
      <p class="section-sub">From your first call to a fully certified, operational door — here's exactly what to expect.</p>
    </div>
    <div class="process-grid">
      <?php
      $steps = [
        ['1', 'Contact &amp; Enquire',  'Call us on ' . SITE_PHONE_DISPLAY . ', WhatsApp or complete the online quote form with your requirements and location.'],
        ['2', 'Free On-Site Survey',    'One of our certified engineers visits your premises, assesses the job and provides a clear, itemised, no-obligation quotation.'],
        ['3', 'Install or Repair',      'We supply and fit your door system to EN 16005 and Irish Building Regulation standards — on time and with minimal disruption.'],
        ['4', 'Certificate &amp; Handover', 'You receive full installation certification, compliance documentation and ongoing PPM support if required.'],
      ];
      foreach ($steps as [$num, $title, $text]): ?>
      <div class="process-step fade-up">
        <div class="step-num"><?= $num ?></div>
        <h3><?= $title ?></h3>
        <p><?= $text ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ CTA BAND ═══ -->
<section class="cta-section" aria-label="Request a free automatic door quote">
  <div class="container">
    <div class="cta-inner">
      <div>
        <h2>Need an Automatic Door Repaired or Installed?</h2>
        <p>Free on-site survey. Transparent pricing. 24/7 emergency callout available across Ireland.</p>
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

<!-- ═══ CLIENTS ═══ -->
<section class="section-light" id="clients" aria-labelledby="clients-heading">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Trusted By Ireland's Leading Brands</p>
      <h2 id="clients-heading">Our Valued Clients &amp; Partners</h2>
      <p class="section-sub">
        From national retailers and healthcare providers to government buildings and hospitality groups —
        Ireland's most recognised names trust Elite Tech Precision for their automatic door needs.
      </p>
    </div>
    <div class="clients-grid">
      <?php
      $clients = [
        ['client-tesco.png',      'Tesco Ireland'],
        ['client-boots.png',      'Boots Ireland'],
        ['client-centra.png',     'Centra'],
        ['client-vodafone.png',   'Vodafone Ireland'],
        ['client-decathlon.png',  'Decathlon Ireland'],
        ['client-opw.png',        'Office of Public Works (OPW)'],
        ['client-police.png',     'An Garda Síochána'],
        ['client-murphy.png',     'Murphy Group'],
        ['client-allcare.png',    'AllCare Pharmacy'],
        ['client-applegreen.png', 'Applegreen'],
        ['client-bank.png',       'Bank of Ireland'],
      ];
      foreach ($clients as [$file, $name]): ?>
      <div class="client-logo">
        <img src="<?= $bp ?>/assets/images/<?= $file ?>"
             alt="<?= htmlspecialchars($name) ?> — automatic door client"
             loading="lazy"
             onerror="this.closest('.client-logo').style.display='none'">
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══ FAQ (SEO structured data) ═══ -->
<section class="section-dark" aria-labelledby="faq-heading">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Common Questions</p>
      <h2 id="faq-heading">Automatic Door Repair Ireland — FAQs</h2>
    </div>
    <div class="faq-grid">
      <?php
      $faqs = [
        ['How quickly can you respond to an automatic door breakdown?',
         'We operate a 24/7 emergency callout service across Ireland. For urgent breakdowns we aim to have an engineer on-site within 2–4 hours. For planned repairs we typically schedule within 24–48 hours.'],
        ['Do you service all types and brands of automatic doors?',
         'Yes. Our certified engineers work with all major automatic door systems including CAME, GEZE, Tormax, Besam, Horton, Stanley and own-brand units. We carry a comprehensive stock of spare parts.'],
        ['Are your engineers certified and insured?',
         'All our engineers are ADSA and DHF accredited, fully insured and trained to install to EN 16005 European safety standards. Every installation includes a compliance certificate.'],
        ['Do you offer service and maintenance contracts?',
         'Yes — we offer tailored Planned Preventative Maintenance (PPM) contracts that ensure your doors remain compliant, safe and operational, reducing emergency callout costs significantly.'],
        ['What areas of Ireland do you cover?',
         'We cover all 32 counties of Ireland from our base in Naas, Co. Kildare. We regularly work in Dublin, Kildare, Wicklow, Wexford, Cork, Galway, Limerick and all other counties.'],
      ];
      foreach ($faqs as [$q, $a]): ?>
      <div class="faq-item fade-up">
        <h3 class="faq-q"><i class="bi bi-question-circle me-2"></i><?= htmlspecialchars($q) ?></h3>
        <p class="faq-a"><?= htmlspecialchars($a) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {"@type":"Question","name":"How quickly can you respond to an automatic door breakdown?","acceptedAnswer":{"@type":"Answer","text":"We operate a 24/7 emergency callout service across Ireland. For urgent breakdowns we aim to have an engineer on-site within 2–4 hours."}},
    {"@type":"Question","name":"Do you service all types and brands of automatic doors?","acceptedAnswer":{"@type":"Answer","text":"Yes. Our certified engineers work with all major automatic door systems including CAME, GEZE, Tormax, Besam, Horton, Stanley and own-brand units."}},
    {"@type":"Question","name":"Are your engineers certified and insured?","acceptedAnswer":{"@type":"Answer","text":"All our engineers are ADSA and DHF accredited, fully insured and trained to install to EN 16005 European safety standards."}},
    {"@type":"Question","name":"Do you offer service and maintenance contracts?","acceptedAnswer":{"@type":"Answer","text":"Yes — we offer tailored Planned Preventative Maintenance (PPM) contracts to keep your doors compliant, safe and operational."}},
    {"@type":"Question","name":"What areas of Ireland do you cover?","acceptedAnswer":{"@type":"Answer","text":"We cover all 32 counties of Ireland from our base in Naas, Co. Kildare."}}
  ]
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
