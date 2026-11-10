<?php
require_once __DIR__ . '/../includes/functions.php';
$bp = BASE_PATH;
$current_page = 'gallery';
$seo = [
    'title'       => 'Gallery | ' . SITE_NAME . ' — Automatic Door Projects Ireland',
    'description' => 'Browse our gallery of automatic door installations, repairs and maintenance projects completed across Ireland by Elite Tech Precision Ltd.',
    'canonical'   => SITE_URL . '/gallery/',
];
$gallery = [
    ['automatic-doors.jpg',          'Automatic Doors Installation',     'automatic-doors'],
    ['automatic-doors-1.jpg',        'Automatic Sliding Door Repair',    'automatic-doors'],
    ['automatic-swing-door.jpg',     'Automatic Swing Door Install',     'automatic-doors'],
    ['sliding-doors.jpg',            'Commercial Sliding Doors',         'sliding-doors'],
    ['sliding-doors-1.jpg',          'Sliding Door Servicing',            'sliding-doors'],
    ['shopfronts.jpeg',              'Aluminium Shopfront Install',      'shopfronts'],
    ['shopfronts-1.jpg',             'Shopfront with Auto Door',         'shopfronts'],
    ['aluminium-commercial-1.jpg',   'Aluminium Commercial Door',         'commercial-doors'],
    ['gallery-auto-1.jpg',           'Automatic Door Repair Dublin',      'automatic-doors'],
    ['gallery-auto-2.jpg',           'Automatic Door Maintenance',       'automatic-doors'],
    ['gallery-shopfront-2.jpg',      'Shopfront Repair',                 'shopfronts'],
    ['gallery-sliding-1.jpg',        'Sliding Door Installation',         'sliding-doors'],
];
require_once __DIR__ . '/../includes/header.php';
?>
<section class="section-light" style="padding-top:120px;">
  <div class="container">
    <div class="section-head center"><p class="eyebrow">Our Work</p><h1>Project Gallery</h1><p class="section-sub">A selection of automatic door projects completed across Ireland.</p></div>
    <div class="gallery-grid">
      <?php foreach ($gallery as [$img, $title, $cat]): ?>
      <div class="gallery-item" data-category="<?= $cat ?>">
        <img src="<?= $bp ?>/assets/images/<?= $img ?>" alt="<?= htmlspecialchars($title) ?>" loading="lazy" onerror="this.closest('.gallery-item').style.display='none'">
        <div class="overlay"><i class="bi bi-zoom-in"></i></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<div class="modal fade lightbox-modal" id="lightboxModal" tabindex="-1" aria-label="Image lightbox">
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header border-0 p-2"><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button></div>
    <div class="modal-body p-0 text-center"><img id="lightboxImg" src="" alt="Gallery image" class="img-fluid mx-auto d-block"></div>
  </div></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
