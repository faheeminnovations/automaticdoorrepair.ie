<?php
require_once __DIR__ . '/../includes/functions.php';
$bp = BASE_PATH;
http_response_code(404);
$seo = [
    'title'       => '404 — Page Not Found | ' . SITE_NAME,
    'description' => 'The page you were looking for could not be found.',
    'canonical'   => SITE_URL . '/',
];
require_once __DIR__ . '/../includes/header.php';
?>
<section style="padding:140px 0; text-align:center; background:var(--c-dark-bg); color:#fff;">
  <div class="container">
    <div style="font-size:8rem; font-weight:900; color:var(--c-accent); line-height:1;">404</div>
    <h1 style="color:#fff; margin:16px 0;">Page Not Found</h1>
    <p style="color:rgba(255,255,255,.6); margin-bottom:32px;">The page you're looking for doesn't exist or has been moved.</p>
    <a href="<?= $bp ?>/" class="btn-primary-site"><i class="bi bi-house me-2"></i>Back to Home</a>
    <a href="<?= $bp ?>/services/" class="btn-outline-site ms-2">Our Services</a>
  </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
