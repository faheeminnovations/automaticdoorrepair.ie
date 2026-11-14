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

<!-- ═══ HERO — Split Layout with Slideshow ═══ -->
<section class="hs" id="hero" aria-label="Automatic Door Repair Specialists — Elite Tech Precision">

  <!-- ── Background Slideshow ── -->
  <div class="hs__slides" aria-hidden="true">
    <div class="hs__slide hs__slide--active">
      <img src="<?= $bp ?>/assets/images/hero-sliding-1.webp"
           alt="Automatic door repair service Ireland — commercial sliding doors"
           loading="eager" fetchpriority="high" width="1920" height="1080">
    </div>
    <div class="hs__slide">
      <img src="<?= $bp ?>/assets/images/hero-sliding-2.jpg"
           alt="Professional automatic door repair and maintenance Ireland"
           loading="lazy" width="1920" height="1080">
    </div>
  </div>

  <!-- ── Overlay ── -->
  <div class="hs__overlay" aria-hidden="true"></div>

  <!-- ── Inner Split ── -->
  <div class="container hs__inner">

    <!-- LEFT: headline + content -->
    <div class="hs__left">

      <div class="hs__badge">
        <i class="bi bi-patch-check-fill"></i>
        ADSA &amp; DHF Certified — <?= SITE_YEARS_EXP ?>+ Years in Ireland
      </div>

      <h1 class="hs__h1">
        Automatic Door<br>
        Repair Specialists
      </h1>

      <p class="hs__sub">
        Professional Automatic Sliding Door Repair &amp; Maintenance Services
      </p>

      <p class="hs__desc">
        We provide reliable automatic door repair, fault diagnosis and maintenance
        services to keep your automatic sliding doors operating safely, smoothly and efficiently.
      </p>

      <!-- Trust row -->
      <div class="hs__trust">
        <div class="hs__trust-item">
          <i class="bi bi-clock-fill"></i>
          <span>24/7 Emergency<br><strong>Repair Service</strong></span>
        </div>
        <div class="hs__trust-item">
          <i class="bi bi-tools"></i>
          <span>All Makes<br><strong>&amp; Models</strong></span>
        </div>
        <div class="hs__trust-item">
          <i class="bi bi-geo-alt-fill"></i>
          <span>All 32 Counties<br><strong>Covered</strong></span>
        </div>
        <div class="hs__trust-item">
          <i class="bi bi-star-fill"></i>
          <span>5-Star<br><strong>Google Rated</strong></span>
        </div>
      </div>

      <!-- CTAs -->
      <div class="hs__ctas">
        <a href="<?= $bp ?>/contact/" class="hs__cta-primary">
          <i class="bi bi-tools me-2"></i>Contact Us
        </a>
        <a href="tel:<?= SITE_PHONE ?>" class="hs__cta-secondary">
          <i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?>
        </a>
      </div>

    </div><!-- /hs__left -->

    <!-- RIGHT: enquiry form card -->
    <div class="hs__right" id="quote">
      <div class="hs__form-card">
        <div class="hs__form-header">
          <span class="hs__form-eyebrow">Free Repair Quote</span>
          <h2 class="hs__form-title">Request a Callback</h2>
          <p class="hs__form-sub">Describe your door fault and we'll get back to you fast.</p>
        </div>

        <?php if ($sent): ?>
        <div class="form-success">
          <i class="bi bi-check-circle-fill me-2"></i>
          <strong>Thank you!</strong> We'll call you back within the hour.
        </div>
        <?php else: ?>
        <?php if ($error): ?>
        <div class="form-error">
          <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= $bp ?>/#quote" id="heroForm" novalidate>
          <input type="hidden" name="cf_submit" value="1">

          <div class="hf-group">
            <label for="hf_name">Full Name <span>*</span></label>
            <input type="text" id="hf_name" name="name"
                   placeholder="Your full name" required
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
          </div>

          <div class="hf-row">
            <div class="hf-group">
              <label for="hf_email">Email <span>*</span></label>
              <input type="email" id="hf_email" name="email"
                     placeholder="your@email.com" required
                     value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="hf-group">
              <label for="hf_phone">Phone</label>
              <input type="tel" id="hf_phone" name="phone"
                     placeholder="+353 xx xxx xxxx"
                     value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>
          </div>

          <div class="hf-group">
            <label for="hf_service">Door Type / Service</label>
            <select id="hf_service" name="service">
              <option value="">— Select a service —</option>
              <?php foreach (db_services() as $s): ?>
              <option value="<?= htmlspecialchars($s['title']) ?>"
                <?= (($_POST['service'] ?? '') === $s['title']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($s['title']) ?>
              </option>
              <?php endforeach; ?>
              <option value="Emergency Repair">Emergency Repair</option>
              <option value="PPM Contract">PPM Contract</option>
              <option value="General Enquiry">General Enquiry</option>
            </select>
          </div>

          <div class="hf-group">
            <label for="hf_message">Describe the Fault <span>*</span></label>
            <textarea id="hf_message" name="message" rows="3"
                      placeholder="e.g. door won't open, stuck open, sensor fault, motor noise..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
          </div>

          <div class="hf-group captcha-group">
            <label for="hf_captcha">
              <i class="bi bi-shield-check me-1"></i>
              Security: What is <strong><?= $captcha_q ?></strong>? <span>*</span>
            </label>
            <input type="number" id="hf_captcha" name="captcha"
                   placeholder="Your answer" required min="1" max="18" autocomplete="off">
          </div>

          <button type="submit" class="hs__form-submit">
            <i class="bi bi-send me-2"></i>Send Repair Enquiry
          </button>
        </form>
        <?php endif; ?>

        <p class="hs__form-footer">
          <i class="bi bi-telephone-fill me-1"></i>
          Prefer to call?&nbsp;<a href="tel:<?= SITE_PHONE ?>"><?= SITE_PHONE_DISPLAY ?></a>
          &nbsp;—&nbsp;available 24/7
        </p>
      </div>
    </div><!-- /hs__right -->

  </div><!-- /container -->

  <!-- Slide dots -->
  <div class="hs__dots" aria-label="Slideshow controls">
    <button class="hs__dot hs__dot--active" data-slide="0" aria-label="Slide 1"></button>
    <button class="hs__dot" data-slide="1" aria-label="Slide 2"></button>
  </div>

</section>

<style>
/* ════════════════════════════════════════════════
   HERO — Split + Slideshow
   ════════════════════════════════════════════════ */

/* Wrapper */
.hs {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  overflow: hidden;
}

/* ── Slideshow ── */
.hs__slides {
  position: absolute;
  inset: 0;
  z-index: 0;
}
.hs__slide {
  position: absolute;
  inset: 0;
  opacity: 0;
  transition: opacity 1.4s ease;
}
.hs__slide--active { opacity: 1; }
.hs__slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  display: block;
  animation: hs-kb 12s ease-in-out infinite alternate;
}
.hs__slide:not(.hs__slide--active) img { animation-play-state: paused; }
@keyframes hs-kb {
  from { transform: scale(1.0); }
  to   { transform: scale(1.1); }
}

/* ── Overlay ── */
.hs__overlay {
  position: absolute;
  inset: 0;
  z-index: 1;
  background: linear-gradient(
    105deg,
    rgba(4,16,38,0.90) 0%,
    rgba(4,16,38,0.72) 55%,
    rgba(4,16,38,0.82) 100%
  );
}

/* ── Inner split grid ── */
.hs__inner {
  position: relative;
  z-index: 2;
  display: grid;
  grid-template-columns: 1fr 460px;
  gap: 56px;
  align-items: center;
  padding-top: 60px;
  padding-bottom: 60px;
  width: 100%;
}

/* ── LEFT column ── */
.hs__left { color: #fff; }

/* Badge */
.hs__badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(26,111,196,0.22);
  border: 1px solid rgba(26,111,196,0.5);
  color: rgba(255,255,255,0.9);
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  padding: 8px 18px;
  border-radius: 100px;
  backdrop-filter: blur(6px);
  margin-bottom: 24px;
  animation: hs-up 0.7s ease both 0.1s;
}
.hs__badge i { color: #5bb8ff; font-size: 14px; }

/* H1 */
.hs__h1 {
  font-size: clamp(2.4rem, 4.5vw, 3.8rem);
  font-weight: 900;
  color: #fff;
  line-height: 1.08;
  margin: 0 0 18px;
  letter-spacing: -1px;
  animation: hs-up 0.7s ease both 0.22s;
}

/* Subheading */
.hs__sub {
  font-size: clamp(1rem, 1.8vw, 1.2rem);
  font-weight: 600;
  color: #5bb8ff;
  margin: 0 0 16px;
  line-height: 1.4;
  animation: hs-up 0.7s ease both 0.34s;
}

/* Description */
.hs__desc {
  font-size: 16px;
  color: rgba(255,255,255,0.75);
  line-height: 1.75;
  max-width: 520px;
  margin: 0 0 32px;
  animation: hs-up 0.7s ease both 0.44s;
}

/* Trust row */
.hs__trust {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-bottom: 32px;
  animation: hs-up 0.7s ease both 0.54s;
}
.hs__trust-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  background: rgba(255,255,255,0.07);
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: 8px;
  padding: 12px 10px;
  backdrop-filter: blur(4px);
}
.hs__trust-item i {
  font-size: 20px;
  color: #5bb8ff;
  flex-shrink: 0;
  margin-top: 2px;
}
.hs__trust-item span {
  font-size: 11px;
  color: rgba(255,255,255,0.75);
  line-height: 1.4;
}
.hs__trust-item span strong {
  display: block;
  font-size: 12px;
  color: #fff;
  font-weight: 700;
}

/* CTAs */
.hs__ctas {
  display: flex;
  flex-wrap: wrap;
  gap: 14px;
  margin-bottom: 28px;
  animation: hs-up 0.7s ease both 0.64s;
}
.hs__cta-primary {
  display: inline-flex;
  align-items: center;
  background: var(--c-accent, #1a6fc4);
  color: #fff;
  font-size: 15px;
  font-weight: 700;
  padding: 14px 32px;
  border-radius: 6px;
  border: 2px solid var(--c-accent, #1a6fc4);
  text-decoration: none;
  transition: background 0.25s, border-color 0.25s, transform 0.25s, box-shadow 0.25s;
  white-space: nowrap;
}
.hs__cta-primary:hover {
  background: #0d3b6e;
  border-color: #0d3b6e;
  color: #fff;
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(0,0,0,0.4);
}
.hs__cta-secondary {
  display: inline-flex;
  align-items: center;
  background: rgba(255,255,255,0.1);
  color: #fff;
  font-size: 15px;
  font-weight: 700;
  padding: 14px 28px;
  border-radius: 6px;
  border: 2px solid rgba(255,255,255,0.4);
  text-decoration: none;
  backdrop-filter: blur(4px);
  transition: background 0.25s, transform 0.25s;
  white-space: nowrap;
}
.hs__cta-secondary:hover {
  background: rgba(255,255,255,0.2);
  color: #fff;
  transform: translateY(-2px);
}

/* ── RIGHT column — form card ── */
.hs__right { }
.hs__form-card {
  background: rgba(255,255,255,0.97);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 24px 80px rgba(0,0,0,0.45);
  backdrop-filter: blur(10px);
  animation: hs-up 0.8s ease both 0.3s;
}
.hs__form-header {
  background: var(--c-primary, #0d3b6e);
  padding: 22px 28px 18px;
}
.hs__form-eyebrow {
  display: block;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: #5bb8ff;
  margin-bottom: 4px;
}
.hs__form-title {
  font-size: 1.35rem;
  font-weight: 800;
  color: #fff;
  margin: 0 0 4px;
}
.hs__form-sub {
  font-size: 13px;
  color: rgba(255,255,255,0.65);
  margin: 0;
}

/* form body padding */
.hs__form-card > form,
.hs__form-card > .form-success,
.hs__form-card > .form-error {
  padding: 20px 28px 0;
}
/* hf-group inside card */
.hs__form-card .hf-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 13px;
}
.hs__form-card .hf-group label {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--c-primary, #0d3b6e);
  margin-bottom: 4px;
}
.hs__form-card .hf-group label span { color: #e53935; }
.hs__form-card .hf-group input,
.hs__form-card .hf-group select,
.hs__form-card .hf-group textarea {
  border: 1.5px solid rgba(0,0,0,0.12);
  border-radius: 5px;
  padding: 9px 12px;
  font-family: inherit;
  font-size: 14px;
  color: #1a1a2e;
  background: #f8fafc;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  width: 100%;
}
.hs__form-card .hf-group input:focus,
.hs__form-card .hf-group select:focus,
.hs__form-card .hf-group textarea:focus {
  border-color: var(--c-accent, #1a6fc4);
  box-shadow: 0 0 0 3px rgba(26,111,196,0.12);
  background: #fff;
}
.hs__form-card .hf-group textarea { resize: none; min-height: 72px; }
.hs__form-card .hf-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-bottom: 0;
}
.hs__form-card .hf-row .hf-group { margin-bottom: 13px; }
.hs__form-card .captcha-group label {
  background: rgba(13,59,110,0.06);
  border: 1px solid rgba(13,59,110,0.14);
  border-radius: 4px;
  padding: 7px 10px;
  font-size: 12px;
  color: var(--c-primary, #0d3b6e);
  display: flex;
  align-items: center;
  gap: 5px;
  margin-bottom: 7px;
  text-transform: none;
  letter-spacing: 0;
  font-weight: 500;
}
.hs__form-card .captcha-group label strong { color: var(--c-accent, #1a6fc4); }
.hs__form-card .captcha-group input[type="number"] {
  max-width: 110px;
  text-align: center;
  font-weight: 700;
  font-size: 15px;
}
.hs__form-submit {
  display: flex;
  align-items: center;
  justify-content: center;
  width: calc(100% - 0px);
  margin: 4px 0 0;
  padding: 13px;
  background: var(--c-accent, #1a6fc4);
  color: #fff;
  font-family: inherit;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 0.5px;
  border: none;
  border-radius: 0 0 0 0;
  cursor: pointer;
  transition: background 0.25s;
}
.hs__form-submit:hover { background: var(--c-primary, #0d3b6e); }
.hs__form-footer {
  font-size: 12px;
  color: #666;
  text-align: center;
  padding: 10px 28px 16px;
}
.hs__form-footer a {
  color: var(--c-primary, #0d3b6e);
  font-weight: 700;
  text-decoration: none;
}
.hs__form-footer a:hover { color: var(--c-accent, #1a6fc4); }

/* ── Dots ── */
.hs__dots {
  position: absolute;
  bottom: 28px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 3;
  display: flex;
  gap: 10px;
}
.hs__dot {
  width: 10px; height: 10px;
  border-radius: 50%;
  border: 2px solid rgba(255,255,255,0.55);
  background: transparent;
  cursor: pointer;
  padding: 0;
  transition: background 0.3s, border-color 0.3s, transform 0.3s;
}
.hs__dot--active {
  background: #fff;
  border-color: #fff;
  transform: scale(1.35);
}

/* ── Entrance animation ── */
@keyframes hs-up {
  from { opacity: 0; transform: translateY(26px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Responsive ── */
@media (max-width: 1100px) {
  .hs__inner { grid-template-columns: 1fr 400px; gap: 40px; }
  .hs__trust  { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 991px) {
  .hs__inner {
    grid-template-columns: 1fr;
    gap: 32px;
    padding-top: 48px;
    padding-bottom: 48px;
  }
  .hs__form-card { max-width: 560px; margin: 0 auto; }
  .hs__trust { grid-template-columns: repeat(4, 1fr); }
  .hs__h1 { font-size: 2.4rem; }
}
@media (max-width: 767px) {
  .hs { min-height: 100svh; }
  .hs__h1 { font-size: 2rem; }
  .hs__trust { grid-template-columns: repeat(2, 1fr); }
  .hs__cta-primary,
  .hs__cta-secondary { width: 100%; justify-content: center; }
  .hs__ctas { flex-direction: column; }
  .hs__form-card .hf-row { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
  .hs__inner { padding-top: 36px; padding-bottom: 36px; }
  .hs__badge { font-size: 11px; padding: 7px 14px; }
  .hs__trust { grid-template-columns: 1fr 1fr; }
}
</style>

<script>
/* ── Hero Slideshow ── */
(function () {
  var slides  = document.querySelectorAll('.hs__slide');
  var dots    = document.querySelectorAll('.hs__dot');
  var current = 0;
  var total   = slides.length;

  function goTo(n) {
    slides[current].classList.remove('hs__slide--active');
    dots[current].classList.remove('hs__dot--active');
    current = (n + total) % total;
    slides[current].classList.add('hs__slide--active');
    dots[current].classList.add('hs__dot--active');
  }

  var timer = setInterval(function () { goTo(current + 1); }, 5000);

  dots.forEach(function (dot, i) {
    dot.addEventListener('click', function () {
      clearInterval(timer);
      goTo(i);
      timer = setInterval(function () { goTo(current + 1); }, 5000);
    });
  });
})();
</script>

<!-- ═══ ACCREDITATIONS ═══ -->
<section class="accred-strip" aria-label="Accreditations and memberships">
  <div class="container">
    <div class="accred-inner">
      <div class="accred-label">Proud Members Of</div>
      <div class="accred-logos">
        <div class="accred-item">
          <img src="<?= $bp ?>/assets/images/cert-adsa.png"
               alt="ADSA Accredited Member — Automatic Door Suppliers Association"
               loading="lazy">
          <span>ADSA Accredited Member</span>
        </div>
        <div class="accred-divider" aria-hidden="true"></div>
        <div class="accred-item">
          <img src="<?= $bp ?>/assets/images/cert-dhf.png"
               alt="DHF Accredited Member — Door & Hardware Federation"
               loading="lazy">
          <span>DHF Accredited Member</span>
        </div>
      </div>
      <div class="accred-label">Certified to EN 16005 &amp; Irish Building Regulations</div>
    </div>
  </div>
</section>

<!-- ═══ TRUSTED CUSTOMERS ═══ -->
<section class="trusted-section" id="clients" aria-labelledby="clients-heading">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Trusted By Ireland's Leading Brands</p>
      <h2 id="clients-heading">Our Trusted Customers</h2>
      <p class="section-sub">
        From national retailers and healthcare providers to government buildings —
        Ireland's most recognised names trust Elite Tech Precision to repair and maintain their automatic doors.
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
             alt="<?= htmlspecialchars($name) ?> — trusted automatic door repair client"
             loading="lazy"
             onerror="this.closest('.client-logo').style.display='none'">
      </div>
      <?php endforeach; ?>
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

<!-- ═══ SEC 1: WELCOME — Image + Text ═══ -->
<section class="section-light hp-welcome" id="about" aria-labelledby="welcome-h2">
  <div class="container">
    <div class="two-col">
      <div class="two-col-img fade-up">
        <img src="<?= $bp ?>/assets/images/automatic-doors-1.jpg"
             alt="Professional automatic door repair service Ireland — Elite Tech Precision"
             loading="lazy"
             onerror="this.src='<?= $bp ?>/assets/images/automatic-doors.jpg'">
        <div class="years-badge">
          <span class="num"><?= SITE_YEARS_EXP ?>+</span>
          <span class="lbl">Years Experience</span>
        </div>
      </div>
      <div class="two-col-text fade-up">
        <p class="eyebrow">Welcome To Elite Tech Precision Ltd</p>
        <h2 id="welcome-h2">Welcome To Professional Automatic Door Repair Services</h2>
        <h3 style="font-size:1.1rem;font-weight:600;color:var(--c-accent);margin:0 0 16px;">
          We Have Years of Experience in Automatic Door Repairs
        </h3>
        <p>
          We specialise in professional automatic sliding door repair, maintenance and troubleshooting
          services. Our experienced team helps commercial and industrial businesses keep their automatic
          doors operating safely, smoothly and reliably. From mechanical faults and sensor issues to
          motor failures and electrical problems, we provide dependable repair solutions tailored to
          your requirements.
        </p>
        <!-- Feature 1 -->
        <div class="hp-feature-block">
          <div class="hp-feature-icon"><i class="bi bi-clock-fill"></i></div>
          <div>
            <h4>24/7 Emergency Repair Service</h4>
            <p>Our dedicated technicians provide responsive emergency support to restore faulty automatic doors and minimise business disruption.</p>
          </div>
        </div>
        <!-- Feature 2 -->
        <div class="hp-feature-block">
          <div class="hp-feature-icon"><i class="bi bi-tools"></i></div>
          <div>
            <h4>Professional Door Maintenance</h4>
            <p>Reliable maintenance and inspections to improve door performance, safety and long-term operational reliability.</p>
          </div>
        </div>
        <div class="about-certs" style="margin-top:20px;">
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

<!-- ═══ SEC 2: WHO WE ARE — Stats + Text ═══ -->
<section class="section-dark hp-whoweare" id="who-we-are" aria-labelledby="who-h2">
  <div class="container">
    <div class="two-col two-col--reverse">
      <div class="two-col-text fade-up">
        <p class="eyebrow">Who We Are</p>
        <h2 id="who-h2">We Are Specialists In Automatic Door Repair &amp; Maintenance</h2>
        <p style="color:rgba(215,217,220,.8);margin-bottom:16px;">
          We provide professional automatic door repair and maintenance solutions for commercial
          properties, retail outlets, healthcare facilities, offices and industrial environments.
          Our focus is on identifying faults, resolving operational issues and ensuring automatic
          doors remain safe, efficient and dependable.
        </p>
        <p style="color:rgba(215,217,220,.7);">
          Our experienced team understands the importance of properly functioning entrance systems
          and delivers reliable support to minimise downtime and maintain smooth access.
        </p>
        <!-- Stats grid -->
        <div class="hp-stats-grid">
          <div class="hp-stat">
            <span class="hp-stat-num" data-count="<?= SITE_YEARS_EXP ?>" data-suffix="+">0</span>
            <span class="hp-stat-lbl">Years of Experience</span>
          </div>
          <div class="hp-stat">
            <span class="hp-stat-num" data-count="500" data-suffix="+">0</span>
            <span class="hp-stat-lbl">Satisfied Customers</span>
          </div>
          <div class="hp-stat">
            <span class="hp-stat-num" data-count="5000" data-suffix="+">0</span>
            <span class="hp-stat-lbl">Projects Completed</span>
          </div>
          <div class="hp-stat">
            <span class="hp-stat-num" data-count="10" data-suffix="+">0</span>
            <span class="hp-stat-lbl">Professional Team</span>
          </div>
        </div>
      </div>
      <div class="two-col-img fade-up">
        <img src="<?= $bp ?>/assets/images/about-team.jpg"
             alt="Automatic door repair specialists — Elite Tech Precision Ireland"
             loading="lazy"
             onerror="this.src='<?= $bp ?>/assets/images/team.jpg'">
      </div>
    </div>
  </div>
</section>

<!-- ═══ SEC 3: REPAIR SERVICES — Features list ═══ -->
<section class="section-light hp-repair-features" aria-labelledby="repair-feat-h2">
  <div class="container">
    <div class="two-col">
      <div class="two-col-text fade-up">
        <p class="eyebrow">Our Repair Services</p>
        <h2 id="repair-feat-h2">Professional Automatic Door Repair Services</h2>
        <p>
          Automatic sliding doors require proper maintenance and professional repairs to ensure safe,
          smooth and reliable operation. Our automatic door repair services address common mechanical,
          electrical and operational faults, helping businesses maintain secure and accessible entrances.
        </p>
        <p>We provide professional troubleshooting and repair solutions for commercial and industrial automatic door systems.</p>
        <ul class="tick-list">
          <li>Automatic sliding door fault diagnosis</li>
          <li>Sensor and motion detector troubleshooting</li>
          <li>Door motor and operator repairs</li>
          <li>Electrical and control system fault repairs</li>
          <li>Door alignment and track adjustments</li>
          <li>Safety system inspection and troubleshooting</li>
        </ul>
        <a href="<?= $bp ?>/contact/" class="btn-primary-site mt-3 d-inline-block">
          Request Repair Quote <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
      <div class="two-col-img fade-up">
        <img src="<?= $bp ?>/assets/images/automatic-doors-2.jpg"
             alt="Automatic door motor and sensor repair Ireland"
             loading="lazy"
             onerror="this.src='<?= $bp ?>/assets/images/automatic-doors.jpg'">
      </div>
    </div>
  </div>
</section>

<!-- ═══ SEC 4: RELIABLE REPAIRS & SAFETY ═══ -->
<section class="section-dark hp-reliable" aria-labelledby="reliable-h2">
  <div class="container">
    <div class="two-col two-col--reverse">
      <div class="two-col-img fade-up">
        <img src="<?= $bp ?>/assets/images/automatic-doors-3.jpg"
             alt="Automatic door safety sensor inspection and repair Ireland"
             loading="lazy"
             onerror="this.src='<?= $bp ?>/assets/images/automatic-doors.jpg'">
      </div>
      <div class="two-col-text fade-up">
        <p class="eyebrow">Reliable &amp; Safe</p>
        <h2 id="reliable-h2">Reliable Repairs &amp; Safety Solutions</h2>
        <p style="color:rgba(215,217,220,.8);">
          A faulty automatic door can affect accessibility, security and daily business operations.
          Our repair services focus on identifying problems quickly and restoring dependable door functionality.
        </p>
        <ul class="tick-list tick-list--light">
          <li>Fast response to automatic door breakdowns</li>
          <li>Professional safety sensor inspection</li>
          <li>Reliable mechanical and electrical repairs</li>
          <li>Improved door operating performance</li>
          <li>Support for commercial entrance systems</li>
          <li>Repair solutions tailored to individual requirements</li>
        </ul>
        <a href="<?= $bp ?>/contact/" class="btn-outline-site mt-3 d-inline-block"
           style="color:#fff;border-color:rgba(255,255,255,.5);">
          Contact Our Repair Team <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ═══ SEC 5: MAINTENANCE & PERFORMANCE ═══ -->
<section class="section-light hp-maintenance" aria-labelledby="maint-h2">
  <div class="container">
    <div class="two-col">
      <div class="two-col-text fade-up">
        <p class="eyebrow">Preventative Maintenance</p>
        <h2 id="maint-h2">Automatic Door Maintenance &amp; Performance</h2>
        <p>
          Regular automatic door maintenance helps identify potential problems before they become major
          faults. Our maintenance solutions help improve operational efficiency, reduce unexpected
          breakdowns and extend the working life of automatic door systems.
        </p>
        <ul class="tick-list">
          <li>Routine door inspections and servicing</li>
          <li>Preventative maintenance solutions</li>
          <li>Sensor and safety mechanism checks</li>
          <li>Track and roller inspections</li>
          <li>Motor and operator performance checks</li>
          <li>Improved long-term operational reliability</li>
        </ul>
        <a href="<?= $bp ?>/services/planned-preventative-maintenance/" class="btn-primary-site mt-3 d-inline-block">
          PPM Contracts <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
      <div class="two-col-img fade-up">
        <img src="<?= $bp ?>/assets/images/automatic-doors-4.jpg"
             alt="Automatic door maintenance and servicing Ireland"
             loading="lazy"
             onerror="this.src='<?= $bp ?>/assets/images/automatic-doors.jpg'">
      </div>
    </div>
  </div>
</section>

<!-- ═══ SEC 6: ABOUT AUTO DOOR REPAIR — Consultation CTA ═══ -->
<section class="section-dark hp-about-repair" aria-labelledby="adr-h2">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">About Automatic Door Repair</p>
      <h2 id="adr-h2">Professional Repair Solutions for Every Entrance</h2>
    </div>
    <div class="two-col" style="margin-bottom:56px;">
      <div class="two-col-text fade-up">
        <p style="color:rgba(215,217,220,.8);">
          Our automatic door repair solutions are designed to support different commercial and industrial
          environments. From busy retail entrances to healthcare facilities and corporate buildings,
          we focus on maintaining safe, accessible and reliable automatic door operation.
        </p>
        <ul class="tick-list tick-list--light">
          <li>Commercial automatic sliding door repairs</li>
          <li>Industrial entrance door maintenance</li>
          <li>Healthcare and hospital entrance repairs</li>
          <li>Retail and shopping centre door servicing</li>
          <li>Office and corporate entrance maintenance</li>
          <li>Customised repair and maintenance solutions</li>
        </ul>
      </div>
      <div class="two-col-img fade-up">
        <img src="<?= $bp ?>/assets/images/automatic-doors-5.jpg"
             alt="Commercial automatic door repair solutions Ireland"
             loading="lazy"
             onerror="this.src='<?= $bp ?>/assets/images/automatic-doors.jpg'">
      </div>
    </div>
    <!-- Consultation CTA bar -->
    <div class="hp-consult-bar fade-up">
      <div class="hp-consult-features">
        <div class="hp-consult-feat"><i class="bi bi-clock-fill"></i><span>Emergency Repair Support</span></div>
        <div class="hp-consult-feat"><i class="bi bi-search"></i><span>Professional Fault Diagnosis</span></div>
        <div class="hp-consult-feat"><i class="bi bi-tools"></i><span>Reliable Maintenance</span></div>
        <div class="hp-consult-feat"><i class="bi bi-person-fill"></i><span>Experienced Technicians</span></div>
      </div>
      <div class="hp-consult-cta">
        <p class="hp-consult-heading">Have an Automatic Door Problem? Contact Our Experts Today.</p>
        <a href="<?= $bp ?>/contact/" class="hs__cta-primary" style="display:inline-flex;">
          <i class="bi bi-calendar-check me-2"></i>Make Appointment
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ═══ SEC 7: OUR SERVICES — 4 Cards ═══ -->
<section class="section-light hp-services-4" id="services" aria-labelledby="services-heading">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">What We Do</p>
      <h2 id="services-heading">Our Services</h2>
      <p class="section-sub">Professional automatic door repair and maintenance services for commercial and industrial premises across Ireland.</p>
    </div>
    <div class="hp-svc4-grid">
      <?php
      $hp_services = [
        [
          'icon'  => 'bi-clock-fill',
          'slug'  => '24-hour-reactive-call-out',
          'title' => '24-Hour Emergency Automatic Door Repair',
          'items' => [
            'Emergency response for faulty automatic sliding doors',
            'Support for unexpected mechanical and electrical failures',
            'Troubleshooting of sensor, motor and operator issues',
            'Repair support to restore safe and reliable door operation',
          ],
        ],
        [
          'icon'  => 'bi-calendar-check',
          'slug'  => 'planned-preventative-maintenance',
          'title' => 'Planned Preventative Maintenance (PPM)',
          'items' => [
            'Regular inspections to identify potential door faults',
            'Preventative servicing to reduce unexpected breakdowns',
            'Inspection of sensors, motors, tracks and safety systems',
            'Helps maintain safe and efficient automatic door operation',
          ],
        ],
        [
          'icon'  => 'bi-door-open',
          'slug'  => 'automatic-doors',
          'title' => 'Automatic Sliding Door Repairs',
          'items' => [
            'Professional diagnosis of sliding door faults',
            'Repair of door motors, operators and control systems',
            'Troubleshooting of opening and closing problems',
            'Restoration of smooth and reliable door movement',
          ],
        ],
        [
          'icon'  => 'bi-shield-fill-check',
          'slug'  => 'automatic-doors',
          'title' => 'Sensor &amp; Safety System Repairs',
          'items' => [
            'Sensor fault diagnosis and troubleshooting',
            'Inspection of safety devices and detection systems',
            'Repair support for faulty automatic door sensors',
            'Focus on safe and dependable door operation',
          ],
        ],
      ];
      foreach ($hp_services as $hs): ?>
      <div class="hp-svc4-card fade-up">
        <div class="hp-svc4-icon"><i class="bi <?= $hs['icon'] ?>"></i></div>
        <h3><?= $hs['title'] ?></h3>
        <ul>
          <?php foreach ($hs['items'] as $item): ?>
          <li><i class="bi bi-check-circle-fill"></i><?= $item ?></li>
          <?php endforeach; ?>
        </ul>
        <a href="<?= $bp ?>/services/<?= $hs['slug'] ?>/" class="hp-svc4-link">
          Read More <i class="bi bi-arrow-right"></i>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-5">
      <a href="<?= $bp ?>/services/" class="btn-primary-site">
        View All Repair Services <i class="bi bi-arrow-right ms-2"></i>
      </a>
    </div>
  </div>
</section>

<!-- ═══ WHY US ═══ -->
<section class="section-light" id="why-us" aria-labelledby="why-heading">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Ireland's Automatic Door Repair Specialists</p>
      <h2 id="why-heading">Why Ireland's Businesses Choose Us for Automatic Door Repair</h2>
      <p class="section-sub">
        With over <?= SITE_YEARS_EXP ?> years repairing automatic doors for Ireland's leading retailers,
        hospitals and facilities managers — here's what sets us apart.
      </p>
    </div>
    <div class="why-grid">
      <?php
      $reasons = [
        ['bi-clock-fill',         '24/7 Emergency Repair',         'On call day and night, 365 days a year — rapid repair response anywhere in Ireland.'],
        ['bi-geo-alt-fill',       'Nationwide Ireland Coverage',    'All 32 counties covered with fast response times from our Kildare repair base.'],
        ['bi-shield-fill-check',  'ADSA &amp; DHF Certified',       'Fully insured, industry-certified repair engineers — EN 16005 compliant every time.'],
        ['bi-tools',              'All Makes &amp; Models Repaired','We repair every major automatic door brand — CAME, GEZE, Tormax, Besam and more.'],
        ['bi-calendar-check',     'PPM Maintenance Contracts',      'Regular servicing prevents breakdowns and keeps your doors compliant year-round.'],
        ['bi-star-fill',          '5-Star Google Rated',            'Consistently rated 5 stars by our clients for repair speed, quality and professionalism.'],
        ['bi-person-fill',        'Single Point of Contact',        'The same expert repair team handles your job from first call through to sign-off.'],
        ['bi-clipboard-check',    'Free Site Survey &amp; Quote',   'We visit your premises, diagnose the issue and provide a written, no-obligation quote.'],
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
      <p class="eyebrow">Our Repair Process</p>
      <h2 id="process-heading">How Our Automatic Door Repair Service Works</h2>
      <p class="section-sub">From your first call to a fully repaired, certified door — here's exactly what to expect.</p>
    </div>
    <div class="process-grid">
      <?php
      $steps = [
        ['1', 'Call or Enquire',       'Call ' . SITE_PHONE_DISPLAY . ', WhatsApp or fill in the online form — describe your door fault, type and location.'],
        ['2', 'Fast On-Site Diagnosis','A certified repair engineer visits your premises, diagnoses the fault and provides a clear no-obligation repair quote.'],
        ['3', 'Repair &amp; Fix',      'We repair your door to EN 16005 standards — first time, on time, with minimal disruption to your business operation.'],
        ['4', 'Test &amp; Sign Off',   'Door is tested, compliance documentation issued and ongoing PPM support offered to prevent future breakdowns.'],
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
        <h2>Need an Automatic Door Repaired Urgently?</h2>
        <p>Free on-site diagnosis. Transparent repair pricing. 24/7 emergency callout across Ireland.</p>
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

<!-- ═══ FAQ (SEO structured data) ═══ -->
<section class="section-dark" aria-labelledby="faq-heading">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Common Questions</p>
      <h2 id="faq-heading">Automatic Door Repair Ireland — Frequently Asked Questions</h2>
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

<!-- ═══ GOOGLE REVIEWS ═══ -->
<section class="reviews-section" aria-labelledby="reviews-heading">
  <div class="container">

    <!-- Section header -->
    <div class="reviews-header">
      <div class="reviews-header-left">
        <p class="eyebrow">What Our Customers Say</p>
        <h2 id="reviews-heading">Google Reviews</h2>
        <div class="reviews-summary">
          <div class="reviews-score">5.0</div>
          <div class="reviews-stars-col">
            <div class="reviews-stars">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
            <span class="reviews-count">Based on Google Reviews</span>
          </div>
          <div class="reviews-google-badge">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="40" height="40" aria-label="Google"><path fill="#EA4335" d="M24 9.5c3.1 0 5.9 1.1 8.1 2.9l6-6C34.4 3.2 29.5 1 24 1 14.8 1 7 6.7 3.7 14.6l7 5.4C12.4 13.8 17.7 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4.1 7.1-10.1 7.1-17z"/><path fill="#FBBC05" d="M10.7 28.6A14.5 14.5 0 0 1 9.5 24c0-1.6.3-3.2.7-4.6l-7-5.4A23.9 23.9 0 0 0 .5 24c0 3.9.9 7.5 2.7 10.7l7.5-6.1z"/><path fill="#34A853" d="M24 46.5c5.5 0 10.1-1.8 13.5-4.9l-7.5-5.8c-1.9 1.3-4.3 2-6 2-6.3 0-11.6-4.3-13.5-10.2l-7.5 6.1C7 41.8 14.9 46.5 24 46.5z"/></svg>
          </div>
        </div>
      </div>
      <div class="reviews-header-right">
        <a href="https://www.google.com/search?q=elitetechprecision#lrd=0x48677f44fd103cbb:0x6a01b023bf190926,1,,,,"
           target="_blank" rel="noopener noreferrer" class="reviews-write-btn">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="18" height="18" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.1 0 5.9 1.1 8.1 2.9l6-6C34.4 3.2 29.5 1 24 1 14.8 1 7 6.7 3.7 14.6l7 5.4C12.4 13.8 17.7 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4.1 7.1-10.1 7.1-17z"/><path fill="#FBBC05" d="M10.7 28.6A14.5 14.5 0 0 1 9.5 24c0-1.6.3-3.2.7-4.6l-7-5.4A23.9 23.9 0 0 0 .5 24c0 3.9.9 7.5 2.7 10.7l7.5-6.1z"/><path fill="#34A853" d="M24 46.5c5.5 0 10.1-1.8 13.5-4.9l-7.5-5.8c-1.9 1.3-4.3 2-6 2-6.3 0-11.6-4.3-13.5-10.2l-7.5 6.1C7 41.8 14.9 46.5 24 46.5z"/></svg>
          Write a Review
        </a>
      </div>
    </div>

    <!-- Dynamic review cards from DB -->
    <?php
    $gsvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.1 0 5.9 1.1 8.1 2.9l6-6C34.4 3.2 29.5 1 24 1 14.8 1 7 6.7 3.7 14.6l7 5.4C12.4 13.8 17.7 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4.1 7.1-10.1 7.1-17z"/><path fill="#FBBC05" d="M10.7 28.6A14.5 14.5 0 0 1 9.5 24c0-1.6.3-3.2.7-4.6l-7-5.4A23.9 23.9 0 0 0 .5 24c0 3.9.9 7.5 2.7 10.7l7.5-6.1z"/><path fill="#34A853" d="M24 46.5c5.5 0 10.1-1.8 13.5-4.9l-7.5-5.8c-1.9 1.3-4.3 2-6 2-6.3 0-11.6-4.3-13.5-10.2l-7.5 6.1C7 41.8 14.9 46.5 24 46.5z"/></svg>';
    $reviews = db_reviews();
    ?>
    <?php if (!empty($reviews)): ?>
    <div class="reviews-grid">
      <?php foreach ($reviews as $r): ?>
      <div class="review-card fade-up">
        <div class="review-card-top">
          <div class="reviewer-avatar reviewer-avatar--<?= htmlspecialchars($r['avatar_color']) ?>">
            <?= htmlspecialchars($r['avatar_initials']) ?>
          </div>
          <div class="reviewer-info">
            <strong class="reviewer-name"><?= htmlspecialchars($r['reviewer']) ?></strong>
            <?php if ($r['meta']): ?>
            <span class="reviewer-meta"><?= htmlspecialchars($r['meta']) ?></span>
            <?php endif; ?>
          </div>
          <div class="review-google-icon"><?= $gsvg ?></div>
        </div>
        <div class="review-stars" aria-label="<?= (int)$r['rating'] ?> out of 5 stars">
          <?php for ($s = 1; $s <= 5; $s++): ?>
          <i class="bi bi-star<?= $s <= (int)$r['rating'] ? '-fill' : '' ?>"></i>
          <?php endfor; ?>
        </div>
        <p class="review-text">"<?= htmlspecialchars($r['review_text']) ?>"</p>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- View all CTA -->
    <div class="reviews-footer">
      <a href="https://www.google.com/search?q=elitetechprecision#lrd=0x48677f44fd103cbb:0x6a01b023bf190926,1,,,,"
         target="_blank" rel="noopener noreferrer" class="reviews-view-all">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.1 0 5.9 1.1 8.1 2.9l6-6C34.4 3.2 29.5 1 24 1 14.8 1 7 6.7 3.7 14.6l7 5.4C12.4 13.8 17.7 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4.1 7.1-10.1 7.1-17z"/><path fill="#FBBC05" d="M10.7 28.6A14.5 14.5 0 0 1 9.5 24c0-1.6.3-3.2.7-4.6l-7-5.4A23.9 23.9 0 0 0 .5 24c0 3.9.9 7.5 2.7 10.7l7.5-6.1z"/><path fill="#34A853" d="M24 46.5c5.5 0 10.1-1.8 13.5-4.9l-7.5-5.8c-1.9 1.3-4.3 2-6 2-6.3 0-11.6-4.3-13.5-10.2l-7.5 6.1C7 41.8 14.9 46.5 24 46.5z"/></svg>
        View All Google Reviews <i class="bi bi-arrow-right"></i>
      </a>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
