<?php
require_once __DIR__ . '/../includes/functions.php';
$bp = BASE_PATH;
$current_page = 'gallery';
$seo = [
    'title'       => 'Projects Gallery | ' . SITE_NAME . ' — Automatic Door Projects Ireland',
    'description' => 'Browse our complete gallery of automatic door installations, roller shutters, shopfronts, steel doors and glazed screen projects completed across Ireland.',
    'canonical'   => SITE_URL . '/gallery/',
];

$products   = get_products_gallery();
$all_images = [];
foreach ($products as $p) {
    foreach ($p['images'] as $img) {
        $all_images[] = [
            'file'  => $img['file'],
            'alt'   => $img['alt'],
            'slug'  => $p['slug'],
            'title' => $p['title'],
        ];
    }
}
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero-sm">
  <div class="container">
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
      <a href="<?= $bp ?>/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <span>Projects Gallery</span>
    </nav>
    <h1>Projects Gallery</h1>
    <p class="page-hero-sub">Over <?= count($all_images) ?>+ real project photos from across Ireland since <?= SITE_FOUNDED ?>.</p>
  </div>
</section>

<section class="gallery-page-wrap">
  <div class="container">

    <!-- ── Filter Tabs ── -->
    <div class="gf-tabs" id="galleryFilters">
      <button class="gf-tab active" data-filter="all">
        All <span><?= count($all_images) ?></span>
      </button>
      <?php foreach ($products as $p): ?>
      <button class="gf-tab" data-filter="<?= htmlspecialchars($p['slug']) ?>">
        <?= htmlspecialchars($p['title']) ?> <span><?= count($p['images']) ?></span>
      </button>
      <?php endforeach; ?>
    </div>

    <!-- ── Grid ── -->
    <div class="gf-grid" id="galleryGrid">
      <?php foreach ($all_images as $img): ?>
      <div class="gf-item" data-cat="<?= htmlspecialchars($img['slug']) ?>">
        <img
          src="<?= $bp ?>/assets/images/gallery-projects/<?= htmlspecialchars($img['file']) ?>"
          alt="<?= htmlspecialchars($img['alt']) ?>"
          loading="lazy"
          onerror="this.closest('.gf-item').remove()"
        >
        <div class="gf-item-overlay">
          <span class="gf-item-cat"><?= htmlspecialchars($img['title']) ?></span>
          <i class="bi bi-zoom-in"></i>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <p class="gf-empty" id="gfEmpty" style="display:none;">No images found for this filter.</p>

  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="container">
    <div class="cta-inner">
      <div>
        <h2>Ready to Start Your Project?</h2>
        <p>Free site survey. No obligation. Nationwide coverage across Ireland.</p>
      </div>
      <div class="cta-btns">
        <a href="<?= $bp ?>/contact/" class="btn-primary-site">Get a Free Quote</a>
        <a href="tel:<?= SITE_PHONE ?>" class="btn-secondary-site">
          <i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Lightbox -->
<div class="gf-lightbox" id="gfLightbox" style="display:none;" role="dialog" aria-modal="true" aria-label="Image viewer">
  <button class="gf-lb-close" id="gfLbClose" aria-label="Close"><i class="bi bi-x-lg"></i></button>
  <button class="gf-lb-nav gf-lb-prev" id="gfLbPrev" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
  <button class="gf-lb-nav gf-lb-next" id="gfLbNext" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
  <div class="gf-lb-stage">
    <img id="gfLbImg" src="" alt="">
    <p id="gfLbCap" class="gf-lb-cap"></p>
  </div>
  <div class="gf-lb-counter"><span id="gfLbCur">1</span> / <span id="gfLbTotal">1</span></div>
</div>

<script>
(function () {
  'use strict';
  const tabs    = document.querySelectorAll('.gf-tab');
  const items   = Array.from(document.querySelectorAll('.gf-item'));
  const empty   = document.getElementById('gfEmpty');
  const lb      = document.getElementById('gfLightbox');
  const lbImg   = document.getElementById('gfLbImg');
  const lbCap   = document.getElementById('gfLbCap');
  const lbCur   = document.getElementById('gfLbCur');
  const lbTotal = document.getElementById('gfLbTotal');
  let visible   = items.slice(), curIdx = 0;

  /* ── Filter ── */
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      const f = tab.dataset.filter;
      visible = [];
      items.forEach(item => {
        const show = f === 'all' || item.dataset.cat === f;
        item.classList.toggle('gf-hidden', !show);
        if (show) visible.push(item);
      });
      empty.style.display = visible.length === 0 ? 'block' : 'none';
    });
  });

  /* ── Lightbox open ── */
  items.forEach(item => {
    item.addEventListener('click', () => {
      curIdx = visible.indexOf(item);
      lbShow();
      lb.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });
  });

  function lbShow() {
    const item = visible[curIdx];
    if (!item) return;
    const img = item.querySelector('img');
    lbImg.src = img.src;
    lbImg.alt = img.alt;
    lbCap.textContent = img.alt;
    lbCur.textContent = curIdx + 1;
    lbTotal.textContent = visible.length;
  }
  function lbClose() {
    lb.style.display = 'none';
    document.body.style.overflow = '';
  }

  document.getElementById('gfLbClose').addEventListener('click', lbClose);
  document.getElementById('gfLbPrev').addEventListener('click', () => {
    curIdx = (curIdx - 1 + visible.length) % visible.length;
    lbShow();
  });
  document.getElementById('gfLbNext').addEventListener('click', () => {
    curIdx = (curIdx + 1) % visible.length;
    lbShow();
  });
  lb.addEventListener('click', e => { if (e.target === lb) lbClose(); });
  document.addEventListener('keydown', e => {
    if (lb.style.display === 'none') return;
    if (e.key === 'Escape')     lbClose();
    if (e.key === 'ArrowLeft')  document.getElementById('gfLbPrev').click();
    if (e.key === 'ArrowRight') document.getElementById('gfLbNext').click();
  });
})();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
