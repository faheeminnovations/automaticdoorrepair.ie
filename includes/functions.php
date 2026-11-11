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
            $settings = get_settings();
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
    $p = setting('color_primary',  '#702963');
    $a = setting('color_accent',   '#AA336A');
    $d = setting('color_dark_bg',  '#15181A');
    $l = setting('color_light_bg', '#F6F4F0');
    return "<style>:root{--c-primary:{$p};--c-accent:{$a};--c-dark-bg:{$d};--c-light-bg:{$l};--c-dark-2:{$d};--c-dark-3:{$d};}</style>";
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
