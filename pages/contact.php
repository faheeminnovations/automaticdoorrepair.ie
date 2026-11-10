<?php
require_once __DIR__ . '/../includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$bp = BASE_PATH;
$current_page = 'contact';
$captcha_q = captcha_generate();
$sent = false;
$error = '';

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
        header('Location: ' . $bp . '/contact/');
        exit;
    }
}

$seo = [
    'title'       => 'Contact Us | ' . SITE_NAME . ' — Automatic Door Repair Ireland',
    'description' => 'Contact Elite Tech Precision for automatic door repair, installation and maintenance across Ireland. 24/7 emergency callout. Call 045-875406.',
    'canonical'   => SITE_URL . '/contact/',
];
$schema_json = '<script type="application/ld+json">{"@context":"https://schema.org","@type":"ContactPage","url":"' . SITE_URL . '/contact/","name":"Contact ' . SITE_NAME . '"}</script>';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="section-light" style="padding-top:120px;">
  <div class="container">
    <div class="contact-bottom-wrap">
      <div class="contact-info fade-up">
        <p class="eyebrow">Get In Touch</p>
        <h1>Contact Us</h1>
        <p>Our team is available 24/7 for emergency callouts. For quotes and general enquiries, fill in the form or reach us directly.</p>
        <ul class="contact-list">
          <li><i class="bi bi-telephone-fill"></i><div><span class="lbl">Phone</span><a href="tel:<?= SITE_PHONE ?>"><?= SITE_PHONE_DISPLAY ?></a></div></li>
          <li><i class="bi bi-whatsapp"></i><div><span class="lbl">WhatsApp</span><a href="<?= SITE_WHATSAPP ?>" target="_blank" rel="noopener"><?= SITE_MOBILE_DISPLAY ?></a></div></li>
          <li><i class="bi bi-envelope-fill"></i><div><span class="lbl">Email</span><a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></div></li>
          <li><i class="bi bi-geo-alt-fill"></i><div><span class="lbl">Address</span><a href="<?= SITE_MAPS_URL ?>" target="_blank" rel="noopener"><?= SITE_ADDRESS ?></a></div></li>
          <li><i class="bi bi-clock-fill"></i><div><span class="lbl">Emergency</span><span>24 Hours, 7 Days a Week</span></div></li>
        </ul>
        <div class="social-links">
          <a href="<?= SITE_FACEBOOK ?>"  target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="<?= SITE_INSTAGRAM ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="<?= SITE_WHATSAPP ?>"  target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
          <a href="<?= SITE_YOUTUBE ?>"   target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          <a href="<?= SITE_LINKEDIN ?>"  target="_blank" rel="noopener" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          <a href="<?= SITE_TIKTOK ?>"    target="_blank" rel="noopener" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>
      <div class="contact-form-box fade-up">
        <?php if ($sent): ?>
        <div class="form-success"><i class="bi bi-check-circle-fill me-2"></i><strong>Thank you!</strong> We'll be in touch very shortly.</div>
        <?php else: ?>
        <?php if ($error): ?><div class="form-error"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?></div><?php endif; ?>
        <form method="POST" action="<?= $bp ?>/contact/" id="heroForm" novalidate>
          <input type="hidden" name="cf_submit" value="1">
          <div class="form-row">
            <div class="form-group"><label for="cf_name">Full Name <span>*</span></label><input type="text" id="cf_name" name="name" class="form-control" placeholder="Your full name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"></div>
            <div class="form-group"><label for="cf_email">Email <span>*</span></label><input type="email" id="cf_email" name="email" class="form-control" placeholder="your@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></div>
          </div>
          <div class="form-row">
            <div class="form-group"><label for="cf_phone">Phone</label><input type="tel" id="cf_phone" name="phone" class="form-control" placeholder="+353 xx xxx xxxx" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"></div>
            <div class="form-group"><label for="cf_service">Service</label><select id="cf_service" name="service" class="form-select">
              <option value="">— Select —</option>
              <?php foreach (db_services() as $s): ?>
              <option value="<?= htmlspecialchars($s['title']) ?>" <?= (($_POST['service'] ?? '') === $s['title']) ? 'selected' : '' ?>><?= htmlspecialchars($s['title']) ?></option>
              <?php endforeach; ?>
              <option value="Emergency Callout">Emergency Callout</option>
              <option value="General Enquiry">General Enquiry</option>
            </select></div>
          </div>
          <div class="form-group"><label for="cf_message">Message <span>*</span></label><textarea id="cf_message" name="message" class="form-control" rows="5" placeholder="Describe your requirements..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea></div>
          <div class="captcha-group form-group"><label for="cf_captcha"><i class="bi bi-shield-check me-1"></i>Security: What is <strong><?= $captcha_q ?></strong>? <span>*</span></label><input type="number" id="cf_captcha" name="captcha" class="form-control" placeholder="Answer" required min="1" max="18" autocomplete="off" style="max-width:120px;text-align:center;font-weight:700;"></div>
          <button type="submit" class="btn-submit"><i class="bi bi-send me-2"></i>Send Enquiry</button>
        </form>
        <?php endif; ?>
      </div>
    </div>
    <div class="contact-map fade-up mt-5">
      <iframe title="Elite Tech Precision Ltd — Naas, Co. Kildare" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2385.6!2d-6.7367519!3d53.245162!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48677f44fd103cbb%3A0x6a01b023bf190926!2sElite%20Tech%20Precision%20Ltd!5e0!3m2!1sen!2sie!4v1700000000000!5m2!1sen!2sie" width="100%" height="420" style="border:0;border-radius:8px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
