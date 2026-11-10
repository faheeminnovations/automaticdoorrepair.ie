<?php
/**
 * Front-Controller Router — automaticdoorrepair.ie
 * Routes all clean URLs to the correct page template
 */
require_once __DIR__ . '/includes/functions.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Resolve page slug from REQUEST_URI
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); // e.g. /automaticdoorrepair.ie
$uri  = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
if ($base && strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
}
$uri = trim($uri, '/'); // "about" | "services" | "services/automatic-doors" | ""

// Route
switch (true) {
    case ($uri === '' || $uri === 'index.php'):
        require __DIR__ . '/pages/home.php';
        break;
    case ($uri === 'about'):
        require __DIR__ . '/pages/about.php';
        break;
    case ($uri === 'services'):
        require __DIR__ . '/pages/services.php';
        break;
    case (preg_match('#^services/([a-z0-9\-]+)$#', $uri, $m)):
        $service_slug = $m[1];
        require __DIR__ . '/pages/service-detail.php';
        break;
    case ($uri === 'gallery'):
        require __DIR__ . '/pages/gallery.php';
        break;
    case ($uri === 'contact'):
        require __DIR__ . '/pages/contact.php';
        break;
    case ($uri === 'sitemap.xml'):
        require __DIR__ . '/sitemap.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/pages/404.php';
        break;
}
