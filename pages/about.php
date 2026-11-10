<?php
require_once __DIR__ . '/../includes/functions.php';
$bp = BASE_PATH;
$current_page = 'about';
$seo = [
    'title'       => 'About Us | ' . SITE_NAME . ' — 22 Years Automatic Door Repair Ireland',
    'description' => 'Founded in 2003, Elite Tech Precision Ltd specialises in automatic door repair, supply and installation across Ireland. ADSA & DHF certified. 22 years experience.',
    'canonical'   => SITE_URL . '/about/',
];
$schema_json = '<script type="application/ld+json">{"@context":"https://schema.org","@type":"AboutPage","url":"' . SITE_URL . '/about/","name":"About ' . SITE_NAME . '","description":"' . $seo['description'] . '"}</script>';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="section-light" style="padding-top:120px;">
  <div class="container">
    <div class="two-col">
      <div class="two-col-img fade-up">
        <img src="<?= $bp ?>/assets/images/about-team.jpg" alt="Elite Tech Precision team" loading="lazy" onerror="this.src='<?= $bp ?>/assets/images/team.jpg'">
        <div class="years-badge"><span class="num"><?= SITE_YEARS_EXP ?>+</span><span class="lbl">Years in Business</span></div>
      </div>
      <div class="two-col-text fade-up">
        <p class="eyebrow">About Us</p>
        <h1><?= htmlspecialchars(setting('about_heading', 'We Have 22 Years of Experience in Automatic Door Repair')) ?></h1>
        <p><?= htmlspecialchars(setting('about_text', 'Founded in 2003, Elite Tech Precision Ltd specialises in automatic door repair services throughout Ireland.')) ?></p>
        <p>Our expert technicians work with a wide range of commercial and industrial automatic doors, ensuring minimal disruption and dependable long-term results. As a smaller business, we value strong client relationships, adaptability and reliable solutions tailored to every need.</p>
        <ul class="tick-list">
          <li>Manufacturer, supplier &amp; installer — one point of contact</li>
          <li>ADSA &amp; DHF accredited engineers</li>
          <li>24/7 reactive breakdown &amp; emergency callout</li>
          <li>Planned Preventative Maintenance (PPM) contracts</li>
          <li>Free site survey — no obligation</li>
          <li>Nationwide coverage across all of Ireland</li>
        </ul>
        <div class="about-certs"><img src="<?= $bp ?>/assets/images/cert-adsa.png" alt="ADSA Member" onerror="this.style.display='none'"><img src="<?= $bp ?>/assets/images/cert-dhf.png" alt="DHF Member" onerror="this.style.display='none'"></div>
        <a href="<?= $bp ?>/contact/" class="btn-primary-site mt-3 d-inline-block">Get a Free Quote</a>
      </div>
    </div>
  </div>
</section>
<section class="cta-section"><div class="container"><div class="cta-inner">
  <div><h2>Need Automatic Door Repair?</h2><p>Free quote, no obligation. 24/7 emergency callouts across Ireland.</p></div>
  <div class="cta-btns"><a href="<?= $bp ?>/contact/" class="btn-primary-site">Request Free Quote</a><a href="tel:<?= SITE_PHONE ?>" class="btn-outline-site"><i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?></a></div>
</div></div></section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
