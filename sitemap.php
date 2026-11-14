<?php
/**
 * XML Sitemap — automaticdoorrepair.ie
 * Complete sitemap: 5 core pages + all 17 active service pages
 */
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');
header('Cache-Control: public, max-age=3600');

$base  = SITE_URL;
$today = date('Y-m-d');
$week  = date('Y-m-d', strtotime('-3 days'));
$month = date('Y-m-d', strtotime('-14 days'));

// Direct DB query — bypasses static cache in db_services()
$slugs = [];
try {
    $sock = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
    $db   = new mysqli();
    $db->real_connect(null, 'root', '', 'automaticdoorrepair', 3306, $sock);
    if (!$db->connect_error) {
        $db->set_charset('utf8mb4');
        $res = $db->query("SELECT slug FROM services WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
        if ($res) while ($r = $res->fetch_assoc()) $slugs[] = $r['slug'];
        $db->close();
    }
} catch (Exception $e) {}

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

  <!-- All 17 service/repair pages — high priority -->
  <?php foreach ($slugs as $slug): ?>
  <url>
    <loc><?= $base ?>/services/<?= htmlspecialchars($slug) ?>/</loc>
    <lastmod><?= $month ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.85</priority>
  </url>
  <?php endforeach; ?>

</urlset>
