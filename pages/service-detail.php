<?php
/**
 * Service Detail — automaticdoorrepair.ie
 * Dynamic SEO: title, meta, H1, schema, FAQ, internal links per service
 */
require_once __DIR__ . '/../includes/functions.php';
$bp = BASE_PATH;

// Fetch service from DB
$slug = $service_slug ?? '';
$conn = new mysqli();
$conn->real_connect(null, 'root', '', 'automaticdoorrepair', 3306, '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');
$stmt = $conn->prepare("SELECT * FROM services WHERE slug = ? AND is_active = 1 LIMIT 1");
$stmt->bind_param('s', $slug);
$stmt->execute();
$svc = $stmt->get_result()->fetch_assoc();
$conn->close();

if (!$svc) { http_response_code(404); require __DIR__ . '/404.php'; exit; }

$current_page = 'services';
$title_raw = $svc['title'];  // e.g. "Automatic Doors"
$title_seo = htmlspecialchars($title_raw);

// Repair-first title — no double encoding, no "Supply/Install"
$seo = [
    'title'       => $title_raw . ' Repair Ireland | 24/7 Emergency Service — ' . SITE_NAME,
    'description' => $svc['short_desc'] . ' ADSA & DHF certified engineers. Free diagnosis. 24/7 emergency callout across Ireland. Call ' . SITE_PHONE_DISPLAY . '.',
    'keywords'    => strtolower($title_raw) . ' repair Ireland, ' . strtolower($title_raw) . ' repair Dublin, ' . strtolower($title_raw) . ' service Ireland, emergency ' . strtolower($title_raw) . ' repair',
    'canonical'   => SITE_URL . '/services/' . $svc['slug'] . '/',
];

$schema_json = '<script type="application/ld+json">' . json_encode([
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'Service',
            '@id'         => SITE_URL . '/services/' . $svc['slug'] . '/#service',
            'name'        => $svc['title'] . ' Ireland',
            'description' => $svc['short_desc'],
            'url'         => SITE_URL . '/services/' . $svc['slug'] . '/',
            'provider'    => [
                '@type'     => 'LocalBusiness',
                '@id'       => SITE_URL . '/#business',
                'name'      => SITE_NAME,
                'telephone' => SITE_PHONE,
                'url'       => SITE_URL,
            ],
            'areaServed'  => ['@type' => 'Country', 'name' => 'Ireland'],
            'serviceType' => $svc['title'],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',              'item' => SITE_URL . '/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products & Services','item' => SITE_URL . '/services/'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $svc['title'],        'item' => SITE_URL . '/services/' . $svc['slug'] . '/'],
            ],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';

require_once __DIR__ . '/../includes/header.php';

// Resolve image path
$imgFile = htmlspecialchars($svc['image']);
$gpPath  = __DIR__ . '/../assets/images/gallery-projects/' . $svc['image'];
$imgSrc  = file_exists($gpPath)
    ? $bp . '/assets/images/gallery-projects/' . $imgFile
    : $bp . '/assets/images/' . $imgFile;
?>

<!-- Page Hero -->
<section class="page-hero-sm">
  <div class="container">
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
      <a href="<?= $bp ?>/">Home</a>
      <i class="bi bi-chevron-right"></i>
      <a href="<?= $bp ?>/services/">Products &amp; Services</a>
      <i class="bi bi-chevron-right"></i>
      <span><?= $title_seo ?></span>
    </nav>
    <h1><?= $title_seo ?> Repair Ireland</h1>
    <p class="page-hero-sub">
      Expert <?= strtolower($title_seo) ?> repair, servicing and maintenance across all of Ireland.
      ADSA &amp; DHF certified engineers. 24/7 emergency callout available.
    </p>
  </div>
</section>

<!-- Main Content -->
<section class="section-light" aria-labelledby="svc-detail-h2">
  <div class="container">
    <div class="two-col">
      <div class="two-col-img fade-up">
        <img src="<?= $imgSrc ?>"
             alt="<?= $title_seo ?> installation by Elite Tech Precision Ltd, Ireland"
             loading="lazy"
             onerror="this.src='<?= $bp ?>/assets/images/doors-banner.jpg'"
             width="600" height="450">
      </div>
      <div class="two-col-text fade-up">
        <p class="eyebrow"><?= $title_seo ?> Repair Ireland</p>
        <h2 id="svc-detail-h2">Professional <?= $title_seo ?> Repair &amp; Maintenance Services Across Ireland</h2>
        <p style="font-size:18px;color:#444;margin-bottom:20px;line-height:1.7;">
          <?= htmlspecialchars($svc['short_desc']) ?>
        </p>
        <p>
          At <strong><?= SITE_NAME ?></strong>, we specialise in professional
          <strong><?= strtolower($title_seo) ?> repair</strong> for commercial and industrial
          premises throughout Ireland. Our ADSA and DHF certified engineers diagnose faults accurately,
          repair first time and ensure every system meets Irish Building Regulations and EN European
          safety standards — with minimal disruption to your operation.
        </p>
        <ul class="tick-list">
          <li>Fully ADSA &amp; DHF accredited and insured engineers</li>
          <li>EN 16005 and Irish Building Regulation compliant</li>
          <li>24/7 emergency breakdown callout available</li>
          <li>Nationwide coverage across all 32 counties of Ireland</li>
          <li>Free on-site survey — no-obligation written quotation</li>
          <li>Planned Preventative Maintenance (PPM) contracts available</li>
          <li>All major brands serviced: CAME, GEZE, Tormax, Besam &amp; more</li>
        </ul>
        <div class="d-flex gap-3 mt-4 flex-wrap">
          <a href="<?= $bp ?>/contact/" class="btn-primary-site">
            Get a Free Quote
          </a>
          <a href="tel:<?= SITE_PHONE ?>" class="btn-outline-site"
             style="color:var(--c-primary);border-color:var(--c-primary);">
            <i class="bi bi-telephone-fill me-2"></i><?= SITE_PHONE_DISPLAY ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Why Choose Us for this service -->
<section class="section-dark" aria-labelledby="svc-why-h2">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Why Choose Elite Tech Precision</p>
      <h2 id="svc-why-h2">Why Choose Us for <?= $title_seo ?> Repair in Ireland</h2>
    </div>
    <div class="why-grid">
      <?php
      $benefits = [
        ['bi-clock-fill',         '24/7 Emergency Response',      'On call day and night — rapid response to door breakdowns across all of Ireland.'],
        ['bi-shield-fill-check',  'Certified &amp; Compliant',    'All work certified to EN 16005 and Irish Building Regulations. Fully insured and accredited.'],
        ['bi-tools',              'All Brands Covered',           'We service all major door manufacturers — no job too complex, no brand unsupported.'],
        ['bi-clipboard-check',    'Free Written Quote',           'On-site survey followed by a detailed, transparent quote — no hidden charges.'],
      ];
      foreach ($benefits as [$icon, $title, $text]): ?>
      <div class="why-card fade-up">
        <div class="why-icon"><i class="bi <?= $icon ?>"></i></div>
        <h3><?= $title ?></h3>
        <p><?= $text ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Gallery from this product category -->
<?php
$products = get_products_gallery();
$prod     = null;
foreach ($products as $p) {
    if ($p['slug'] === $svc['slug']) { $prod = $p; break; }
}
if ($prod && count($prod['images']) > 0):
?>
<section class="section-light" aria-labelledby="svc-gallery-h2">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Our Work</p>
      <h2 id="svc-gallery-h2"><?= $title_seo ?> Project Gallery — Ireland</h2>
      <p class="section-sub">Real installations completed by our engineers across Ireland.</p>
    </div>
    <div class="ap-gallery-grid" style="grid-template-columns:repeat(4,1fr);">
      <?php foreach (array_slice($prod['images'], 0, 8) as $img): ?>
      <div style="aspect-ratio:1;border-radius:6px;overflow:hidden;background:#cdd5da;">
        <img src="<?= $bp ?>/assets/images/gallery-projects/<?= htmlspecialchars($img['file']) ?>"
             alt="<?= htmlspecialchars($img['alt']) ?> — Ireland"
             loading="lazy"
             style="width:100%;height:100%;object-fit:cover;"
             onerror="this.closest('div').style.display='none'">
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
      <a href="<?= $bp ?>/all-projects/#<?= htmlspecialchars($svc['slug']) ?>" class="btn-primary-site">
        View Full <?= $title_seo ?> Gallery <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- FAQ for this service -->
<section class="section-dark" aria-labelledby="svc-faq-h2">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Common Questions</p>
      <h2 id="svc-faq-h2"><?= $title_seo ?> Ireland — Frequently Asked Questions</h2>
    </div>
    <div class="faq-grid">
      <div class="faq-item fade-up">
        <h3 class="faq-q"><i class="bi bi-question-circle me-2"></i>How much does <?= strtolower($title_seo) ?> cost in Ireland?</h3>
        <p class="faq-a">
          Costs vary depending on the door type, size and specification required.
          We provide free, no-obligation on-site surveys with a detailed written quotation so you know
          exactly what you'll pay — no surprises. Contact us on <?= SITE_PHONE_DISPLAY ?> to arrange yours.
        </p>
      </div>
      <div class="faq-item fade-up">
        <h3 class="faq-q"><i class="bi bi-question-circle me-2"></i>How long does <?= strtolower($title_seo) ?> installation take?</h3>
        <p class="faq-a">
          Most installations are completed within one day. Complex or large-scale projects may take
          2–3 days. We work efficiently to minimise disruption and always agree a schedule with you in advance.
        </p>
      </div>
      <div class="faq-item fade-up">
        <h3 class="faq-q"><i class="bi bi-question-circle me-2"></i>Do you provide certification after installation?</h3>
        <p class="faq-a">
          Yes. Every installation includes full compliance certification to EN 16005 and Irish Building
          Regulations. All documentation is handed over at project completion.
        </p>
      </div>
      <div class="faq-item fade-up">
        <h3 class="faq-q"><i class="bi bi-question-circle me-2"></i>Can you repair an existing <?= strtolower($title_seo) ?>?</h3>
        <p class="faq-a">
          Absolutely. Our engineers are trained to repair and service all makes and models.
          We carry a comprehensive stock of spare parts for rapid turnaround, and our 24/7 emergency
          callout means we can respond to breakdowns any time of day or night.
        </p>
      </div>
    </div>
  </div>
</section>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {"@type":"Question","name":"How much does <?= addslashes($svc['title']) ?> cost in Ireland?","acceptedAnswer":{"@type":"Answer","text":"Costs vary depending on door type and size. We provide free on-site surveys with detailed written quotations — call <?= SITE_PHONE_DISPLAY ?>."}},
    {"@type":"Question","name":"How long does <?= addslashes($svc['title']) ?> installation take?","acceptedAnswer":{"@type":"Answer","text":"Most installations are completed within one day. We always agree a schedule in advance to minimise disruption."}},
    {"@type":"Question","name":"Do you provide certification after <?= addslashes($svc['title']) ?> installation?","acceptedAnswer":{"@type":"Answer","text":"Yes — every installation includes full compliance certification to EN 16005 and Irish Building Regulations."}},
    {"@type":"Question","name":"Can you repair an existing <?= addslashes($svc['title']) ?>?","acceptedAnswer":{"@type":"Answer","text":"Yes. We repair all makes and models with 24/7 emergency callout available across Ireland."}}
  ]
}
</script>

<!-- Related Services -->
<section class="section-light" aria-labelledby="related-h2">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow">Related Services</p>
      <h2 id="related-h2">Other Automatic Door Services We Provide</h2>
    </div>
    <div class="services-grid">
      <?php
      $all     = db_services();
      $related = array_filter($all, fn($s) => $s['slug'] !== $svc['slug']);
      $related = array_slice(array_values($related), 0, 4);
      foreach ($related as $r):
        $rGp  = __DIR__ . '/../assets/images/gallery-projects/' . $r['image'];
        $rSrc = file_exists($rGp)
            ? $bp . '/assets/images/gallery-projects/' . htmlspecialchars($r['image'])
            : $bp . '/assets/images/' . htmlspecialchars($r['image']);
      ?>
      <div class="svc-card fade-up">
        <div class="svc-img">
          <img src="<?= $rSrc ?>"
               alt="<?= htmlspecialchars($r['title']) ?> Ireland"
               loading="lazy"
               onerror="this.src='<?= $bp ?>/assets/images/doors-banner.jpg'">
        </div>
        <div class="svc-body">
          <div class="svc-icon"><i class="bi <?= htmlspecialchars($r['icon']) ?>"></i></div>
          <h3><?= htmlspecialchars($r['title']) ?></h3>
          <p><?= htmlspecialchars($r['short_desc']) ?></p>
          <a href="<?= $bp ?>/services/<?= htmlspecialchars($r['slug']) ?>/"
             class="svc-link"
             aria-label="Learn more about <?= htmlspecialchars($r['title']) ?>">
            Learn More <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Final CTA -->
<section class="cta-section" aria-label="Get a free <?= strtolower($title_seo) ?> quote">
  <div class="container">
    <div class="cta-inner">
      <div>
        <h2>Get a Free <?= $title_seo ?> Repair Quote Today</h2>
        <p>Free on-site diagnosis. No-obligation repair quote. 24/7 emergency callout available.</p>
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
