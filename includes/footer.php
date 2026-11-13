<?php
/** Shared Footer — automaticdoorrepair.ie */
$bp = BASE_PATH;
?>

<!-- ═══ FOOTER ═══ -->
<footer class="site-footer">
  <div class="container">
    <div class="footer-top">

      <div class="footer-brand">
        <img src="<?= $bp ?>/assets/images/footer-logo.png" alt="<?= SITE_NAME ?>" width="180" loading="lazy" onerror="this.src='<?= $bp ?>/assets/images/logo.png'">
        <p><?= SITE_NAME ?> — supplying, installing and maintaining automatic doors, roller shutters, fire doors &amp; aluminium shopfronts across Ireland since <?= SITE_FOUNDED ?>.</p>
        <div class="footer-social">
          <a href="<?= SITE_FACEBOOK ?>"  target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a href="<?= SITE_INSTAGRAM ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="<?= SITE_WHATSAPP ?>"  target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
          <a href="<?= SITE_YOUTUBE ?>"   target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          <a href="<?= SITE_LINKEDIN ?>"  target="_blank" rel="noopener" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          <a href="<?= SITE_TIKTOK ?>"    target="_blank" rel="noopener" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>

      <div class="footer-col">
        <h4>Services</h4>
        <ul>
          <?php foreach (db_services() as $s): ?>
          <li><a href="<?= $bp ?>/services/<?= htmlspecialchars($s['slug']) ?>/"><i class="bi bi-chevron-right"></i><?= htmlspecialchars($s['title']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="<?= $bp ?>/"><i class="bi bi-chevron-right"></i>Home</a></li>
          <li><a href="<?= $bp ?>/about/"><i class="bi bi-chevron-right"></i>About Us</a></li>
          <li><a href="<?= $bp ?>/services/"><i class="bi bi-chevron-right"></i>Services</a></li>
          <li><a href="<?= $bp ?>/all-projects/"><i class="bi bi-chevron-right"></i>Gallery</a></li>
          <li><a href="<?= $bp ?>/contact/"><i class="bi bi-chevron-right"></i>Contact Us</a></li>
          <li><a href="<?= $bp ?>/sitemap.xml"><i class="bi bi-chevron-right"></i>Sitemap</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Contact</h4>
        <ul class="footer-contact">
          <li><i class="bi bi-telephone-fill"></i><a href="tel:<?= SITE_PHONE ?>"><?= SITE_PHONE_DISPLAY ?></a></li>
          <li><i class="bi bi-whatsapp"></i><a href="<?= SITE_WHATSAPP ?>" target="_blank" rel="noopener"><?= SITE_MOBILE_DISPLAY ?></a></li>
          <li><i class="bi bi-envelope-fill"></i><a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></li>
          <li><i class="bi bi-geo-alt-fill"></i><a href="<?= SITE_MAPS_URL ?>" target="_blank" rel="noopener"><?= SITE_ADDRESS ?></a></li>
          <li><i class="bi bi-clock-fill"></i><span>24/7 Emergency Service</span></li>
        </ul>
      </div>

    </div>
    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved. Registered in Ireland.</p>
      <p><a href="<?= $bp ?>/sitemap.xml">Sitemap</a> &bull; <a href="mailto:<?= SITE_EMAIL ?>">Privacy</a></p>
    </div>
  </div>
</footer>

<!-- Sticky call bar -->
<div class="sticky-call d-lg-none">
  <a href="tel:<?= SITE_PHONE ?>"><i class="bi bi-telephone-fill me-2"></i><strong>24/7 Emergency: <?= SITE_PHONE_DISPLAY ?></strong></a>
</div>

<!-- Back to top -->
<button class="back-to-top" id="backToTop" aria-label="Back to top"><i class="bi bi-arrow-up"></i></button>

<!-- Bootstrap JS + Custom JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $bp ?>/assets/js/main.js"></script>

</body>
</html>
