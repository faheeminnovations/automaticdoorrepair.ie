<?php
require_once __DIR__ . '/../includes/functions.php';
$bp = BASE_PATH;
$current_page = 'gallery';
$seo = [
    'title'       => 'All Projects | ' . SITE_NAME . ' — Door & Shutter Installations Ireland',
    'description' => 'See all our door and shutter installation projects across Ireland — automatic doors, roller shutters, shopfronts, steel doors, glazed screens and more.',
    'canonical'   => SITE_URL . '/all-projects/',
];

$products = get_products_gallery();
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero-sm">
  <div class="container">
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
      <a href="<?= $bp ?>/">Home</a> <i class="bi bi-chevron-right"></i>
      <span>All Projects</span>
    </nav>
    <h1>All Projects</h1>
    <p class="page-hero-sub">Every product category — browse our complete portfolio of installations across Ireland.</p>
  </div>
</section>

<!-- Quick-jump product anchors -->
<section class="all-projects-nav-wrap">
  <div class="container">
    <div class="ap-nav-grid">
      <?php foreach ($products as $p): ?>
      <a href="#<?= htmlspecialchars($p['slug']) ?>" class="ap-nav-pill">
        <i class="bi <?= htmlspecialchars($p['icon']) ?>"></i>
        <?= htmlspecialchars($p['title']) ?>
        <span class="ap-nav-count"><?= count($p['images']) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Per-product gallery sections -->
<?php foreach ($products as $pidx => $p): ?>
<section class="ap-product-section <?= ($pidx % 2 === 0) ? 'section-light' : 'ap-alt' ?>" id="<?= htmlspecialchars($p['slug']) ?>">
  <div class="container">
    <div class="ap-section-head">
      <div class="ap-section-title">
        <span class="ap-icon"><i class="bi <?= htmlspecialchars($p['icon']) ?>"></i></span>
        <div>
          <h2><?= htmlspecialchars($p['title']) ?></h2>
          <p><?= count($p['images']) ?> project photo<?= count($p['images']) > 1 ? 's' : '' ?></p>
        </div>
      </div>
      <a href="<?= $bp ?>/services/<?= htmlspecialchars($p['slug']) ?>/" class="ap-svc-link">
        View Service <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="ap-gallery-grid" data-product="<?= htmlspecialchars($p['slug']) ?>">
      <?php foreach ($p['images'] as $i => $img): ?>
      <div class="ap-gallery-item" data-src="<?= $bp ?>/assets/images/gallery-projects/<?= htmlspecialchars($img['file']) ?>" data-alt="<?= htmlspecialchars($img['alt']) ?>">
        <img
          src="<?= $bp ?>/assets/images/gallery-projects/<?= htmlspecialchars($img['file']) ?>"
          alt="<?= htmlspecialchars($img['alt']) ?> Ireland"
          loading="lazy"
          onerror="this.closest('.ap-gallery-item').style.display='none'"
        >
        <div class="ap-overlay"><i class="bi bi-zoom-in"></i></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endforeach; ?>

<!-- CTA -->
<section class="cta-section">
  <div class="container">
    <div class="cta-inner">
      <div>
        <h2>Ready to Start Your Project?</h2>
        <p>Free site survey — no obligation. Nationwide coverage across Ireland.</p>
      </div>
      <div class="cta-btns">
        <a href="<?= $bp ?>/contact/" class="btn-primary-site">Get a Free Quote</a>
        <a href="tel:<?= SITE_PHONE ?>" class="btn-secondary-site"><i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?></a>
      </div>
    </div>
  </div>
</section>

<!-- Shared Lightbox -->
<div class="lightbox-overlay" id="lightboxOverlay" role="dialog" aria-modal="true" aria-label="Image lightbox" style="display:none;">
  <button class="lb-close" id="lbClose" aria-label="Close lightbox"><i class="bi bi-x-lg"></i></button>
  <button class="lb-prev"  id="lbPrev"  aria-label="Previous image"><i class="bi bi-chevron-left"></i></button>
  <button class="lb-next"  id="lbNext"  aria-label="Next image"><i class="bi bi-chevron-right"></i></button>
  <div class="lb-img-wrap">
    <img id="lbImg" src="" alt="" loading="eager">
    <p id="lbCaption" class="lb-caption"></p>
  </div>
</div>

<script>
(function(){
  const overlay = document.getElementById('lightboxOverlay');
  const lbImg   = document.getElementById('lbImg');
  const lbCap   = document.getElementById('lbCaption');
  let items = [], curIdx = 0, curGroup = [];

  /* Open lightbox within same product grid */
  document.querySelectorAll('.ap-gallery-item').forEach(item => {
    item.addEventListener('click', () => {
      const grid = item.closest('.ap-gallery-grid');
      curGroup = [...grid.querySelectorAll('.ap-gallery-item')].filter(i => i.style.display !== 'none');
      curIdx   = curGroup.indexOf(item);
      showLb();
      overlay.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });
  });

  function showLb() {
    const item = curGroup[curIdx];
    if (!item) return;
    lbImg.src = item.dataset.src;
    lbImg.alt = item.dataset.alt;
    lbCap.textContent = item.dataset.alt;
  }
  function closeLb() {
    overlay.style.display = 'none';
    document.body.style.overflow = '';
  }

  document.getElementById('lbClose').addEventListener('click', closeLb);
  document.getElementById('lbPrev').addEventListener('click', () => {
    curIdx = (curIdx - 1 + curGroup.length) % curGroup.length;
    showLb();
  });
  document.getElementById('lbNext').addEventListener('click', () => {
    curIdx = (curIdx + 1) % curGroup.length;
    showLb();
  });
  overlay.addEventListener('click', e => { if (e.target === overlay) closeLb(); });
  document.addEventListener('keydown', e => {
    if (overlay.style.display === 'none') return;
    if (e.key === 'Escape')      closeLb();
    if (e.key === 'ArrowLeft')   document.getElementById('lbPrev').click();
    if (e.key === 'ArrowRight')  document.getElementById('lbNext').click();
  });
})();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
