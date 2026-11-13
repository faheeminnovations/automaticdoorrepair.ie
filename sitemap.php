<?php
/**
 * XML Sitemap — automaticdoorrepair.ie
 * Includes all static pages + all service URLs with correct priorities
 */
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');

$base    = SITE_URL;
$today   = date('Y-m-d');
$week    = date('Y-m-d', strtotime('-3 days'));
$month   = date('Y-m-d', strtotime('-14 days'));

// Fetch all active services
$services = db_services();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
          http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

  <!-- Homepage — highest priority -->
  <url>
    <loc><?= $base ?>/</loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>

  <!-- Core pages -->
  <url>
    <loc><?= $base ?>/about/</loc>
    <lastmod><?= $week ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= $base ?>/services/</loc>
    <lastmod><?= $week ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= $base ?>/contact/</loc>
    <lastmod><?= $week ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= $base ?>/all-projects/</loc>
    <lastmod><?= $week ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  <url>
    <loc><?= $base ?>/gallery/</loc>
    <lastmod><?= $week ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
  </url>

  <!-- Service / Product pages — high priority for ranking -->
  <?php foreach ($services as $svc): ?>
  <url>
    <loc><?= $base ?>/services/<?= htmlspecialchars($svc['slug']) ?>/</loc>
    <lastmod><?= $month ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.85</priority>
  </url>
  <?php endforeach; ?>

</urlset>
