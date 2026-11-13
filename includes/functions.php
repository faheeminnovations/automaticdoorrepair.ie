<?php
/**
 * Core functions — automaticdoorrepair.ie
 * Elite Tech Precision Ltd
 */

// ── Site Identity ──────────────────────────────────────────────
define('SITE_NAME',         'Elite Tech Precision Ltd');
define('SITE_TAGLINE',      'Automatic Doors — Manufactured, Supplied & Installed Nationwide');
define('SITE_URL',          'https://automaticdoorrepair.ie');

// ── Real Contact Details (from DB) ─────────────────────────────
define('SITE_PHONE',         '+35345875406');
define('SITE_PHONE_DISPLAY', '045-875406');
define('SITE_MOBILE',        '+353851000459');
define('SITE_MOBILE_DISPLAY','085 100 0459');
define('SITE_EMAIL',         'info@elitedoors.ie');
define('SITE_ADDRESS',       'Unit 2A, Sickens Caragh, Naas, Co. Kildare, W91ND9F');
define('SITE_ADDRESS_SHORT', 'Naas, Co. Kildare, Ireland');
define('SITE_MAPS_URL',      'https://maps.app.goo.gl/7yg3G2DqV14MBRsM7');
define('SITE_FACEBOOK',      'https://www.facebook.com/profile.php?id=61562841684995');
define('SITE_INSTAGRAM',     'https://www.instagram.com/elitetechprecision/');
define('SITE_WHATSAPP',      'https://wa.me/353851000459');
define('SITE_YOUTUBE',       'https://www.youtube.com/@EliteTechPrecisionLtd');
define('SITE_LINKEDIN',      'https://www.linkedin.com/in/elite-tech-precision-ltd-766a496a/');
define('SITE_TIKTOK',        'https://www.tiktok.com/@elitetechprecision');
define('SITE_FOUNDED',       '2003');
define('SITE_YEARS_EXP',     '22');

// ── Base path (auto: works on localhost AND live root) ──────────
define('BASE_PATH', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));

// ── Helpers ────────────────────────────────────────────────────
function bp(): string { return BASE_PATH; }
function img(string $f): string { return BASE_PATH . '/assets/images/' . ltrim($f, '/'); }
function asset(string $f): string { return BASE_PATH . '/assets/' . ltrim($f, '/'); }

function sanitise(string $s): string {
    return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
}

function is_valid_email(string $e): bool {
    return (bool) filter_var($e, FILTER_VALIDATE_EMAIL);
}

/**
 * Load a setting from MySQL DB
 */
function setting(string $key, string $default = ''): string {
    static $settings = null;

    if ($settings === null) {
        require_once __DIR__ . '/../admin/includes/config.php';

        try {
            $raw = get_settings();
            // get_settings() returns ['setting_key' => 'setting_value', ...]
            $settings = $raw;
        } catch (Throwable $e) {
            error_log('Settings error: ' . $e->getMessage());
            $settings = [];
        }
    }

    $value = $settings[$key] ?? $default;
    return is_scalar($value) ? (string) $value : $default;
}
/**
 * Load services from DB
 */
function db_services(): array {
    static $cache = null;
    if ($cache !== null) return $cache;
    $cache = [];
    try {
        $sock  = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
        $conn  = new mysqli();
        $conn->real_connect(null, 'root', '', 'automaticdoorrepair', 3306, $sock);
        if (!$conn->connect_error) {
            $conn->set_charset('utf8mb4');
            $res = $conn->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order, id");
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $cache[] = $row;
                }
            }
            $conn->close();
        }
    } catch (Exception $e) {}
    return $cache;
}

/**
 * Inject CSS color variables from DB
 */
function color_vars(): string {
    $p = setting('color_primary',  '#0d3b6e');
    $a = setting('color_accent',   '#1a6fc4');
    $d = setting('color_dark_bg',  '#071e38');
    $l = setting('color_light_bg', '#f0f5fb');
    $d2 = setting('color_dark_bg', '#071e38');
    return "<style>:root{--c-primary:{$p};--c-accent:{$a};--c-accent-h:" . adjustHex($a, -20) . ";--c-dark-bg:{$d};--c-light-bg:{$l};--c-dark-2:{$d2};--c-dark-3:{$d2};}</style>";
}

/**
 * Darken a hex colour by a fixed amount (for hover states)
 */
function adjustHex(string $hex, int $amount): string {
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    $r = max(0, min(255, hexdec(substr($hex,0,2)) + $amount));
    $g = max(0, min(255, hexdec(substr($hex,2,2)) + $amount));
    $b = max(0, min(255, hexdec(substr($hex,4,2)) + $amount));
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

/**
 * Validate Irish / international phone — optional field
 */
function is_valid_phone(string $phone): bool {
    if (empty(trim($phone))) return true; // optional
    $clean = preg_replace('/[\s\-\.\(\)]+/', '', $phone);
    return (bool) preg_match('/^(\+353|00353|0)\d{7,10}$/', $clean);
}

/**
 * Generate a simple math captcha question and store answer in session.
 * Returns e.g. "3 + 7"
 */
function captcha_generate(): string {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $a = random_int(1, 9);
    $b = random_int(1, 9);
    $_SESSION['captcha_answer'] = $a + $b;
    return "$a + $b";
}

/**
 * Verify user's captcha input against session answer.
 */
function captcha_verify(string $input): bool {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $expected = $_SESSION['captcha_answer'] ?? null;
    // Regenerate after check so it can't be reused
    unset($_SESSION['captcha_answer']);
    return $expected !== null && (int) trim($input) === (int) $expected;
}

/**
 * Send enquiry email via PHP mail()
 */
function send_enquiry_email(array $d): bool {
    $to      = SITE_EMAIL;
    $subject = "New Enquiry — {$d['service']} | automaticdoorrepair.ie";
    $body    = "New enquiry from automaticdoorrepair.ie\r\n\r\n"
             . "Name:    {$d['name']}\r\n"
             . "Email:   {$d['email']}\r\n"
             . "Phone:   {$d['phone']}\r\n"
             . "Service: {$d['service']}\r\n\r\n"
             . "Message:\r\n{$d['message']}\r\n";
    $headers = "From: noreply@automaticdoorrepair.ie\r\n"
             . "Reply-To: {$d['email']}\r\n"
             . "Content-Type: text/plain; charset=UTF-8\r\n"
             . "X-Mailer: PHP/" . phpversion();
    return @mail($to, $subject, $body, $headers);
}

// ── All Products with gallery images (for gallery & all-projects pages) ─
function get_products_gallery(): array {
    return [
        [
            'slug'   => 'automatic-doors',
            'title'  => 'Automatic Doors',
            'icon'   => 'bi-door-open',
            'cover'  => 'Automatic-Doors-1.jpg',
            'images' => [
                ['file'=>'Automatic-Doors-1.jpg',          'alt'=>'Automatic Doors Installation'],
                ['file'=>'Automatic-Doors-2.jpg',          'alt'=>'Automatic Sliding Door'],
                ['file'=>'Automatic-Doors-3.jpg',          'alt'=>'Automatic Door Repair'],
                ['file'=>'Automatic-Doors-4.jpg',          'alt'=>'Commercial Automatic Door'],
                ['file'=>'Automatic-Doors-5.jpg',          'alt'=>'Automatic Door Ireland'],
                ['file'=>'Automatic-Doors-6.jpg',          'alt'=>'Automatic Sliding Door Install'],
                ['file'=>'Automatic-Doors-7.jpg',          'alt'=>'Automatic Door Service'],
                ['file'=>'Automatic-Doors-8.jpg',          'alt'=>'Automatic Door Maintenance'],
                ['file'=>'Automatic-Doors-9.jpg',          'alt'=>'Automatic Door Supply'],
                ['file'=>'Automatic-Doors-10.jpg',         'alt'=>'Automatic Door Systems'],
                ['file'=>'Automatic-Doors-11.jpg',         'alt'=>'Automatic Door Project'],
                ['file'=>'Automatic-Doors-12.jpg',         'alt'=>'Automatic Door Kildare'],
                ['file'=>'Automatic-Doors.jpg',            'alt'=>'Automatic Door'],
                ['file'=>'Automatic-Doors-1.jpeg',         'alt'=>'Automatic Doors Installed'],
                ['file'=>'automatic-doors-and-Aluminium-Shopfronts.jpeg',  'alt'=>'Automatic Doors & Shopfront'],
                ['file'=>'automatic-doors-and-Aluminium-Shopfronts-2.jpeg','alt'=>'Doors & Shopfront 2'],
                ['file'=>'automatic-doors-and-Aluminium-Shopfronts-3-.jpeg','alt'=>'Doors & Shopfront 3'],
            ],
        ],
        [
            'slug'   => 'aluminium-shopfronts',
            'title'  => 'Aluminium Shopfronts',
            'icon'   => 'bi-shop',
            'cover'  => 'Aluminium-Shopfronts-1.jpg',
            'images' => [
                ['file'=>'Aluminium-Shopfronts-1.jpg',                     'alt'=>'Aluminium Shopfront Install'],
                ['file'=>'Aluminium-Shopfronts-2.jpg',                     'alt'=>'Aluminium Shopfront Dublin'],
                ['file'=>'Aluminium-Shopfronts-3.jpg',                     'alt'=>'Shopfront Design'],
                ['file'=>'Aluminium-Shopfronts-4.jpg',                     'alt'=>'Aluminium Shop Front'],
                ['file'=>'Aluminium-Shopfronts-5.jpg',                     'alt'=>'Commercial Shopfront'],
                ['file'=>'Aluminium-Shopfronts-6.jpg',                     'alt'=>'Shopfront Ireland'],
                ['file'=>'Aluminium-Shopfronts.jpg',                       'alt'=>'Aluminium Shopfront'],
                ['file'=>'Aluminium-Shopfronts.jpeg',                      'alt'=>'Shopfront Supply'],
                ['file'=>'Aluminium-Shopfronts-and-automatic-doors.jpeg',  'alt'=>'Shopfront with Auto Door'],
                ['file'=>'Shop-Fronts.jpeg',                               'alt'=>'Shop Front Installed'],
                ['file'=>'Glass-Shop-Front-scaled.jpg',                    'alt'=>'Glass Shop Front'],
                ['file'=>'Shop-Front-1-scaled.webp',                       'alt'=>'Shop Front Project'],
            ],
        ],
        [
            'slug'   => 'commercial-roller-shutters',
            'title'  => 'Commercial Roller Shutters',
            'icon'   => 'bi-columns-gap',
            'cover'  => 'Commercial-Shutter.jpeg',
            'images' => [
                ['file'=>'Commercial-Shutter.jpeg',              'alt'=>'Commercial Roller Shutter'],
                ['file'=>'Commercial-Shutter1.jpeg',             'alt'=>'Commercial Shutter Install'],
                ['file'=>'Commercial-Roller-Shutters-scaled.jpg','alt'=>'Commercial Roller Shutters'],
                ['file'=>'Roller-Shutter-1-scaled.jpg',          'alt'=>'Roller Shutter 1'],
                ['file'=>'Roller-Shutter-2-1-scaled.jpg',        'alt'=>'Roller Shutter 2'],
                ['file'=>'Roller-Shutter-4-scaled.jpg',          'alt'=>'Roller Shutter 4'],
                ['file'=>'Roller-Shutter-5-scaled.jpg',          'alt'=>'Roller Shutter 5'],
                ['file'=>'Roller-Shutter-B-scaled.jpg',          'alt'=>'Commercial Shutter B'],
                ['file'=>'Roller-Shutter-B-1-scaled.jpg',        'alt'=>'Commercial Shutter B1'],
                ['file'=>'Green-Roller-Shutter.jpg',             'alt'=>'Green Roller Shutter'],
                ['file'=>'shutter-image.jpg',                    'alt'=>'Roller Shutter Project'],
            ],
        ],
        [
            'slug'   => 'fire-rated-roller-shutters',
            'title'  => 'Fire Rated Roller Shutters',
            'icon'   => 'bi-fire',
            'cover'  => 'Fire-Rated-Shutters-1.jpeg',
            'images' => [
                ['file'=>'Fire-Rated-Shutters-1.jpeg',  'alt'=>'Fire Rated Roller Shutter'],
                ['file'=>'Fire-Rated-Shutters-2.jpeg',  'alt'=>'Fire Shutter Install'],
                ['file'=>'Fire-Shutter-1.jpg',          'alt'=>'Fire Rated Shutter Project'],
            ],
        ],
        [
            'slug'   => 'commercial-steel-doors',
            'title'  => 'Commercial Steel Doors',
            'icon'   => 'bi-shield-fill',
            'cover'  => 'Single-Steel-Door-1.jpg',
            'images' => [
                ['file'=>'Single-Door.jpg',         'alt'=>'Single Steel Door'],
                ['file'=>'Single-Steel-Door-1.jpg', 'alt'=>'Steel Door Installed'],
                ['file'=>'Single-Steel-Door.jpg',   'alt'=>'Commercial Steel Door'],
                ['file'=>'Steel-Doors-1-scaled.jpg','alt'=>'Steel Doors Project'],
                ['file'=>'Steel-Doors-scaled.jpg',  'alt'=>'Steel Doors Supply'],
                ['file'=>'Steel-Doors.jpeg',        'alt'=>'Commercial Steel Doors Ireland'],
                ['file'=>'Steel-Door.jpg',          'alt'=>'Steel Door Project'],
                ['file'=>'Door-1-scaled.jpg',       'alt'=>'Commercial Door Install'],
                ['file'=>'D-Doors.jpg',             'alt'=>'D Doors Project'],
                ['file'=>'D-Doors-1.webp',          'alt'=>'Commercial D Doors'],
                ['file'=>'Door-Half.jpg',           'alt'=>'Half Steel Door'],
            ],
        ],
        [
            'slug'   => 'fire-rated-steel-doors',
            'title'  => 'Fire Rated Steel Doors',
            'icon'   => 'bi-shield-fill-check',
            'cover'  => 'Fire-Rated-Steel-Doors.jpeg',
            'images' => [
                ['file'=>'Fire-Rated-Steel-Doors.jpeg',             'alt'=>'Fire Rated Steel Door'],
                ['file'=>'Fire-Rated-Steel-Doors-Landing-Page.jpeg','alt'=>'Fire Rated Doors Ireland'],
                ['file'=>'Fire-Exit-Doors.jpeg',                    'alt'=>'Fire Exit Doors'],
                ['file'=>'Fire-Exit-Doors-1.jpg',                   'alt'=>'Fire Exit Door Install'],
                ['file'=>'FireExit-Doors-scaled.jpg',               'alt'=>'Fire Exit Doors Project'],
                ['file'=>'After-New-Exit-Doors-2-scaled.jpg',       'alt'=>'New Exit Doors After'],
                ['file'=>'After-New-Exit-Doors-3-scaled.jpg',       'alt'=>'Exit Doors Installed'],
                ['file'=>'After-New-Exit-Doors-4-scaled.jpg',       'alt'=>'Exit Doors Complete'],
            ],
        ],
        [
            'slug'   => 'aluminium-commercial-doors',
            'title'  => 'Aluminium Commercial Doors',
            'icon'   => 'bi-building-fill',
            'cover'  => 'Aluminium-Commercial-Doors-1.jpg',
            'images' => [
                ['file'=>'Aluminium-Commercial-Doors-1.jpg', 'alt'=>'Aluminium Commercial Door 1'],
                ['file'=>'Aluminium-Commercial-Doors-2.jpg', 'alt'=>'Aluminium Commercial Door 2'],
                ['file'=>'Aluminium-Commercial-Doors-3.jpg', 'alt'=>'Aluminium Commercial Door 3'],
                ['file'=>'Aluminium-Commercial-Doors-4.jpg', 'alt'=>'Aluminium Commercial Door 4'],
                ['file'=>'Aluminium-Commercial-Doors-5.jpg', 'alt'=>'Aluminium Commercial Door 5'],
                ['file'=>'Aluminium-Commercial-Doors-6.jpg', 'alt'=>'Aluminium Commercial Door 6'],
                ['file'=>'Aluminium-Commercial-Doors-7.jpg', 'alt'=>'Aluminium Commercial Door 7'],
                ['file'=>'Aluminium-Commercial-Doors-8.jpg', 'alt'=>'Aluminium Commercial Door 8'],
                ['file'=>'Aluminium-Commercial-Doors-9.jpg', 'alt'=>'Aluminium Commercial Door 9'],
                ['file'=>'Aluminium-Commercial-Doors-10.jpg','alt'=>'Aluminium Commercial Door 10'],
                ['file'=>'Aluminium-Commercial-Doors-11.jpg','alt'=>'Aluminium Commercial Door 11'],
                ['file'=>'Aluminium-Commercial-Doors-13.jpg','alt'=>'Aluminium Commercial Door 13'],
                ['file'=>'Aluminium-Commercial-Doors.jpg',   'alt'=>'Aluminium Commercial Doors'],
            ],
        ],
        [
            'slug'   => 'automatic-swing-doors',
            'title'  => 'Automatic Swing Doors',
            'icon'   => 'bi-box-arrow-in-right',
            'cover'  => 'Automatic-Swing-Door-1-1.jpg',
            'images' => [
                ['file'=>'Automatic-Swing-Door-1-1.jpg',   'alt'=>'Automatic Swing Door'],
                ['file'=>'Automatic-Swing-Door.jpg',       'alt'=>'Swing Door Install'],
                ['file'=>'Automatic-Doors-scaled.jpg',     'alt'=>'Automatic Swing Door Project'],
            ],
        ],
        [
            'slug'   => 'fire-rated-glazed-screens',
            'title'  => 'Fire Rated Glazed Screens',
            'icon'   => 'bi-layout-three-columns',
            'cover'  => 'Fire-Rated-Glazed-Screens.jpg',
            'images' => [
                ['file'=>'Fire-Rated-Glazed-Screens.jpg',   'alt'=>'Fire Rated Glazed Screen'],
                ['file'=>'Fire-Rated-Glazed-Screens.jpeg',  'alt'=>'Glazed Fire Screen'],
                ['file'=>'Fire-Rated-Glazed-Screens.webp',  'alt'=>'Fire Screen Install'],
                ['file'=>'Fire-Rated-Glazed-Screens-1.webp','alt'=>'Fire Screen Project'],
                ['file'=>'Fire-Rated-Glazed-Screen.jpeg',   'alt'=>'Fire Rated Glass Screen'],
                ['file'=>'013-1-1120x1200-1.jpg',           'alt'=>'Fire Glazed Screen Ireland'],
            ],
        ],
        [
            'slug'   => 'stack-doors',
            'title'  => 'Stack Doors',
            'icon'   => 'bi-layers',
            'cover'  => 'Stack-Door-1.jpg',
            'images' => [
                ['file'=>'Stack-Door-1.jpg',   'alt'=>'Stack Door Install'],
                ['file'=>'Stack-Door-2.jpg',   'alt'=>'Stack Door Project'],
                ['file'=>'Stack-Door-3.jpg',   'alt'=>'Stack Door Ireland'],
                ['file'=>'slider2-2.webp',     'alt'=>'Stack Door System'],
            ],
        ],
        [
            'slug'   => 'curtain-walling',
            'title'  => 'Curtain Walling',
            'icon'   => 'bi-grid-3x3-gap',
            'cover'  => 'Curtain-Walling-Revolving-Doors-1.jpg',
            'images' => [
                ['file'=>'Curtain-Walling-Revolving-Doors-1.jpg', 'alt'=>'Curtain Walling Install'],
                ['file'=>'Curtain-Walling-Revolving-Doors-1.webp','alt'=>'Curtain Wall System'],
                ['file'=>'Curtain-Walling-Revolving-Doors.webp',  'alt'=>'Curtain Walling Ireland'],
                ['file'=>'slider-3-1.jpeg',                        'alt'=>'Curtain Wall Project'],
            ],
        ],
        [
            'slug'   => 'revolving-doors',
            'title'  => 'Revolving Doors',
            'icon'   => 'bi-arrow-repeat',
            'cover'  => 'Curtain-Walling-Revolving-Doors-1.jpg',
            'images' => [
                ['file'=>'Curtain-Walling-Revolving-Doors-1.jpg', 'alt'=>'Revolving Door Installed'],
                ['file'=>'Curtain-Walling-Revolving-Doors-1.webp','alt'=>'Revolving Door System'],
                ['file'=>'Curtain-Walling-Revolving-Doors.webp',  'alt'=>'Revolving Door Project'],
            ],
        ],
        [
            'slug'   => 'industrial-roller-shutters',
            'title'  => 'Industrial Roller Shutters',
            'icon'   => 'bi-layout-three-columns',
            'cover'  => 'Industrial-Roller-Shutters-3.jpg',
            'images' => [
                ['file'=>'Industrial-Roller-Shutters-1.jpg', 'alt'=>'Industrial Roller Shutter 1'],
                ['file'=>'Industrial-Roller-Shutters-2.jpg', 'alt'=>'Industrial Roller Shutter 2'],
                ['file'=>'Industrial-Roller-Shutters-3.jpg', 'alt'=>'Industrial Roller Shutter 3'],
                ['file'=>'Industrial-Roller-Shutters-4.jpg', 'alt'=>'Industrial Roller Shutter 4'],
                ['file'=>'Industrial-Roller-Shutters.jpg',   'alt'=>'Industrial Shutters Ireland'],
                ['file'=>'Industrial-Roller-Shutters.jpeg',  'alt'=>'Industrial Shutter Install'],
            ],
        ],
    ];
}

// ── Services — automaticdoorrepair.ie only (from DB pages) ─────
function get_services(): array {
    return [
        [
            'slug'  => 'automatic-doors',
            'title' => 'Automatic Doors',
            'short' => 'Supply, installation, repair & maintenance of all types of automatic sliding, swing and revolving doors across Ireland.',
            'image' => 'automatic-doors.jpg',
            'icon'  => 'bi-door-open',
        ],
        [
            'slug'  => 'automatic-swing-doors',
            'title' => 'Automatic Swing Doors',
            'short' => 'Automated swing door operators for accessible, hands-free entry — ideal for healthcare, retail and offices.',
            'image' => 'automatic-swing-door.jpg',
            'icon'  => 'bi-box-arrow-in-right',
        ],
        [
            'slug'  => 'commercial-sliding-doors',
            'title' => 'Commercial Sliding Doors',
            'short' => 'Energy-efficient commercial sliding door systems for retail, healthcare and office environments.',
            'image' => 'sliding-doors.jpg',
            'icon'  => 'bi-arrows-expand',
        ],
        [
            'slug'  => 'aluminium-shopfronts',
            'title' => 'Aluminium Shopfronts',
            'short' => 'Custom aluminium shopfronts designed to enhance your business entrance and maximise security.',
            'image' => 'shopfronts.jpeg',
            'icon'  => 'bi-building',
        ],
        [
            'slug'  => 'aluminium-commercial-doors',
            'title' => 'Aluminium Commercial Doors',
            'short' => 'Durable aluminium commercial door systems for offices, retail units and industrial premises.',
            'image' => 'aluminium-commercial-1.jpg',
            'icon'  => 'bi-building-fill',
        ],
        [
            'slug'  => 'industrial-roller-shutters',
            'title' => 'Industrial Roller Shutters',
            'short' => 'Heavy-duty industrial roller shutters for warehouses, factories and loading bays.',
            'image' => 'roller-shutters.jpg',
            'icon'  => 'bi-layout-three-columns',
        ],
        [
            'slug'  => 'commercial-roller-shutters',
            'title' => 'Commercial Roller Shutters',
            'short' => 'Robust commercial roller shutter solutions providing security and weather protection for businesses.',
            'image' => 'commercial-roller-shutters.jpg',
            'icon'  => 'bi-columns-gap',
        ],
        [
            'slug'  => 'fire-rated-steel-doors',
            'title' => 'Fire Rated Steel Doors',
            'short' => 'Certified fire-rated steel doors — 30 to 240 min ratings — installed to EN 1634-1 standards.',
            'image' => 'fire-rated-doors.jpeg',
            'icon'  => 'bi-shield-fill-check',
        ],
        [
            'slug'  => 'fire-exit-doors',
            'title' => 'Fire Exit Doors',
            'short' => 'Compliant fire exit door systems ensuring safe evacuation routes per Irish building regulations.',
            'image' => 'fire-exit-doors.jpg',
            'icon'  => 'bi-sign-turn-right',
        ],
        [
            'slug'  => 'curtain-walling',
            'title' => 'Curtain Walling & Revolving Doors',
            'short' => 'Architectural curtain walling and revolving entrance door systems for prestige commercial buildings.',
            'image' => 'curtain-walling.jpg',
            'icon'  => 'bi-grid-3x3-gap',
        ],
        [
            'slug'  => 'stack-doors',
            'title' => 'Stack Doors',
            'short' => 'Space-saving stack door systems for wide openings in warehouses, sports halls and commercial units.',
            'image' => 'gallery-stack-1.jpg',
            'icon'  => 'bi-layers',
        ],
        [
            'slug'  => 'planned-preventative-maintenance',
            'title' => 'Planned Preventative Maintenance',
            'short' => 'PPM contracts to keep all your door systems compliant, reliable and free from unexpected breakdowns.',
            'image' => 'ppm.jpeg',
            'icon'  => 'bi-calendar-check',
        ],
    ];
}
