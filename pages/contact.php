<?php
/**
 * Contact — automaticdoorrepair.ie
 * SEO target: "contact automatic door repair Ireland", "automatic door quote Ireland"
 */
require_once __DIR__ . '/../includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$bp           = BASE_PATH;
$current_page = 'contact';
$captcha_q    = captcha_generate();
$sent         = false;
$error        = '';

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
        header('Location: ' . $bp . '/contact/');
        exit;
    }
}

$seo = [
    'title'       => 'Contact Us | Automatic Door Repair Ireland — Free Quote &amp; 24/7 Emergency Callout',
    'description' => 'Contact Elite Tech Precision Ltd for automatic door repair, installation and maintenance across Ireland. Free site survey. 24/7 emergency callout. Call 045-875406 or email info@elitedoors.ie.',
    'keywords'    => 'contact automatic door repair Ireland, automatic door quote Ireland, automatic door emergency Ireland, automatic door repair Kildare contact',
    'canonical'   => SITE_URL . '/contact/',
];

$schema_json = '<script type="application/ld+json">' . json_encode([
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type' => 'ContactPage',
            'url'   => SITE_URL . '/contact/',
            'name'  => 'Contact ' . SITE_NAME,
            'description' => 'Contact page for automatic door repair, installation and maintenance enquiries across Ireland.',
        ],
        [
            '@type'     => 'LocalBusiness',
            '@id'       => SITE_URL . '/#business',
            'name'      => SITE_NAME,
            'telephone' => SITE_PHONE,
            'email'     => SITE_EMAIL,
            'url'       => SITE_URL,
            'address'   => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => 'Unit 2A, Sickens Caragh',
                'addressLocality' => 'Naas',
                'addressRegion'   => 'Co. Kildare',
                'postalCode'      => 'W91ND9F',
                'addressCountry'  => 'IE',
            ],
            'geo'       => ['@type' => 'GeoCoordinates', 'latitude' => 53.245162, 'longitude' => -6.7367519],
            'openingHoursSpecification' => [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
                'opens'     => '00:00',
                'closes'    => '23:59',
            ],
            'contactPoint' => [
                '@type'       => 'ContactPoint',
                'telephone'   => SITE_PHONE,
                'contactType' => 'customer service',
                'areaServed'  => 'IE',
                'availableLanguage' => ['English'],
                'hoursAvailable'    => ['Mo-Su 00:00-23:59'],
            ],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';

require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero-sm">
  <div class="container">
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
      <a href="<?= $bp ?>/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <span>Contact Us</span>
    </nav>
    <h1>Contact Elite Tech Precision Ltd</h1>
    <p class="page-hero-sub">
      Get a free on-site survey for automatic door repair or installation anywhere in Ireland.
      Available 24/7 for emergency callouts — call <a href="tel:<?= SITE_PHONE ?>" style="color:#fff;font-weight:700;"><?= SITE_PHONE_DISPLAY ?></a> now.
    </p>
  </div>
</section>

<!-- Contact Grid -->
<section class="section-light" aria-labelledby="contact-h2">
  <div class="container">
    <div class="contact-bottom-wrap">

      <!-- Contact Info -->
      <div class="contact-info fade-up">
        <p class="eyebrow">Get In Touch</p>
        <h2 id="contact-h2">Request a Free Automatic Door Quote in Ireland</h2>
        <p>
          Our team is ready to help with any automatic door enquiry — from a 24/7 emergency breakdown
          to a full new installation. Fill in the form or reach us directly below.
        </p>
        <ul class="contact-list">
          <li>
            <i class="bi bi-telephone-fill"></i>
            <div>
              <span class="lbl">24/7 Phone</span>
              <a href="tel:<?= SITE_PHONE ?>"><?= SITE_PHONE_DISPLAY ?></a>
            </div>
          </li>
          <li>
            <i class="bi bi-whatsapp"></i>
            <div>
              <span class="lbl">WhatsApp</span>
              <a href="<?= SITE_WHATSAPP ?>" target="_blank" rel="noopener"><?= SITE_MOBILE_DISPLAY ?></a>
            </div>
          </li>
          <li>
            <i class="bi bi-envelope-fill"></i>
            <div>
              <span class="lbl">Email</span>
              <a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a>
            </div>
          </li>
          <li>
            <i class="bi bi-geo-alt-fill"></i>
            <div>
              <span class="lbl">Address</span>
              <a href="<?= SITE_MAPS_URL ?>" target="_blank" rel="noopener"><?= SITE_ADDRESS ?></a>
            </div>
          </li>
          <li>
            <i class="bi bi-clock-fill"></i>
            <div>
              <span class="lbl">Emergency Service</span>
              <span>24 Hours, 7 Days a Week — 365 Days a Year</span>
            </div>
          </li>
        </ul>

        <!-- Key reassurances -->
        <div style="display:flex;flex-direction:column;gap:10px;margin:20px 0;">
          <?php
          $reassurances = [
            ['bi-check-circle-fill', 'Free on-site survey — no-obligation written quote'],
            ['bi-check-circle-fill', 'ADSA &amp; DHF certified engineers — fully insured'],
            ['bi-check-circle-fill', 'EN 16005 compliant installations with certification'],
            ['bi-check-circle-fill', 'All 32 counties of Ireland covered'],
          ];
          foreach ($reassurances as [$icon, $text]): ?>
          <div style="display:flex;align-items:center;gap:10px;font-size:14px;font-weight:600;color:#333;">
            <i class="bi <?= $icon ?>" style="color:var(--c-accent);font-size:16px;flex-shrink:0;"></i>
            <?= $text ?>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="social-links" aria-label="Social media">
          <a href="<?= SITE_FACEBOOK ?>"  target="_blank" rel="noopener" aria-label="Follow on Facebook"><i class="bi bi-facebook"></i></a>
          <a href="<?= SITE_INSTAGRAM ?>" target="_blank" rel="noopener" aria-label="Follow on Instagram"><i class="bi bi-instagram"></i></a>
          <a href="<?= SITE_WHATSAPP ?>"  target="_blank" rel="noopener" aria-label="WhatsApp us"><i class="bi bi-whatsapp"></i></a>
          <a href="<?= SITE_YOUTUBE ?>"   target="_blank" rel="noopener" aria-label="Watch on YouTube"><i class="bi bi-youtube"></i></a>
          <a href="<?= SITE_LINKEDIN ?>"  target="_blank" rel="noopener" aria-label="Connect on LinkedIn"><i class="bi bi-linkedin"></i></a>
          <a href="<?= SITE_TIKTOK ?>"    target="_blank" rel="noopener" aria-label="Follow on TikTok"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>

      <!-- Enquiry Form -->
      <div class="contact-form-box fade-up">
        <?php if ($sent): ?>
        <div class="form-success">
          <i class="bi bi-check-circle-fill me-2"></i>
          <strong>Thank you for your enquiry!</strong><br>
          A member of our team will be in touch within the hour. For urgent matters call
          <a href="tel:<?= SITE_PHONE ?>"><?= SITE_PHONE_DISPLAY ?></a>.
        </div>
        <?php else: ?>
        <p class="eyebrow" style="margin-bottom:6px;">Free Quote — No Obligation</p>
        <h3 style="font-size:1.3rem;font-weight:800;color:var(--c-primary);margin-bottom:20px;line-height:1.2;">
          Request Your Free On-Site Survey
        </h3>
        <?php if ($error): ?>
        <div class="form-error"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= $bp ?>/contact/" id="contactForm" novalidate>
          <input type="hidden" name="cf_submit" value="1">
          <div class="form-row">
            <div class="form-group">
              <label for="cf_name">Full Name <span>*</span></label>
              <input type="text" id="cf_name" name="name" placeholder="Your full name"
                     required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label for="cf_email">Email Address <span>*</span></label>
              <input type="email" id="cf_email" name="email" placeholder="your@email.com"
                     required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="cf_phone">Phone Number</label>
              <input type="tel" id="cf_phone" name="phone" placeholder="+353 xx xxx xxxx"
                     value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label for="cf_service">Service Required</label>
              <select id="cf_service" name="service">
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
          </div>
          <div class="form-group">
            <label for="cf_message">Message / Requirements <span>*</span></label>
            <textarea id="cf_message" name="message" rows="5"
                      placeholder="Please describe your door type, the issue, your location and any other relevant details..."
                      required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
          </div>
          <div class="captcha-group form-group">
            <label for="cf_captcha">
              <i class="bi bi-shield-check me-1"></i>
              Security Check: What is <strong><?= $captcha_q ?></strong>? <span>*</span>
            </label>
            <input type="number" id="cf_captcha" name="captcha" placeholder="Your answer"
                   required min="1" max="18" autocomplete="off">
          </div>
          <button type="submit" class="btn-submit">
            <i class="bi bi-send me-2"></i>Send Free Enquiry
          </button>
          <p style="font-size:12px;color:#888;margin-top:12px;text-align:center;">
            <i class="bi bi-lock-fill me-1"></i>Your details are secure and will never be shared with third parties.
          </p>
        </form>
        <?php endif; ?>
      </div>
    </div>

    <!-- Map -->
    <div class="contact-map fade-up mt-5">
      <iframe
        title="Elite Tech Precision Ltd — Unit 2A, Sickens Caragh, Naas, Co. Kildare, Ireland"
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2385.6!2d-6.7367519!3d53.245162!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48677f44fd103cbb%3A0x6a01b023bf190926!2sElite%20Tech%20Precision%20Ltd!5e0!3m2!1sen!2sie!4v1700000000000!5m2!1sen!2sie"
        width="100%" height="420"
        style="border:0;border-radius:8px;"
        allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
