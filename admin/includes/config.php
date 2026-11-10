<?php
/**
 * Admin Panel Config — automaticdoorrepair.ie
 * All settings stored in MySQL DB
 */

// ── Database ───────────────────────────────────────────────────
define('DB_SOCKET', '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock');
define('DB_NAME',   'automaticdoorrepair');
define('DB_USER',   'root');
define('DB_PASS',   '');
define('DB_PREFIX', 'I6xCT2c5E_');

// ── Paths ──────────────────────────────────────────────────────
define('ROOT_PATH', __DIR__ . '/../..');

// ── Session ────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) session_start();

// ── DB Connection (singleton) ──────────────────────────────────
function db(): mysqli {
    static $conn = null;
    if ($conn instanceof mysqli && $conn->ping()) return $conn;
    $conn = new mysqli();
    $conn->real_connect(null, DB_USER, DB_PASS, DB_NAME, 3306, DB_SOCKET);
    if ($conn->connect_error) {
        // Fallback TCP
        $conn = new mysqli('127.0.0.1', DB_USER, DB_PASS, DB_NAME, 3306);
        if ($conn->connect_error) die('DB connection failed: ' . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

// ── Auth helpers ───────────────────────────────────────────────
function admin_logged_in(): bool {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_login(): void {
    if (!admin_logged_in()) {
        header('Location: ' . base_admin_url() . 'login.php');
        exit;
    }
}

function base_admin_url(): string {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $base   = rtrim(str_replace(basename($script), '', $script), '/');
    if (!str_ends_with($base, '/admin')) $base .= '/admin';
    return rtrim($base, '/') . '/';
}

// ── Admin credentials from DB ──────────────────────────────────
function get_admin_user(): array {
    $r = db()->query("SELECT * FROM admin_users LIMIT 1");
    return $r ? ($r->fetch_assoc() ?? []) : [];
}

function admin_login(string $email, string $pass): bool {
    $stmt = db()->prepare("SELECT id, email, password FROM admin_users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row && password_verify($pass, $row['password'])) {
        $_SESSION['admin_logged_in']  = true;
        $_SESSION['admin_email']      = $row['email'];
        $_SESSION['admin_id']         = $row['id'];
        $_SESSION['admin_login_time'] = time();
        return true;
    }
    return false;
}

function update_admin_credentials(string $email, string $pass = ''): bool {
    if ($pass) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = db()->prepare("UPDATE admin_users SET email=?, password=? WHERE id=?");
        $stmt->bind_param('ssi', $email, $hash, $_SESSION['admin_id']);
    } else {
        $stmt = db()->prepare("UPDATE admin_users SET email=? WHERE id=?");
        $stmt->bind_param('si', $email, $_SESSION['admin_id']);
    }
    $ok = $stmt->execute();
    if ($ok) $_SESSION['admin_email'] = $email;
    return $ok;
}

// ── Settings helpers ───────────────────────────────────────────
function get_settings(): array {
    $defaults = [
        'seo_title'      => 'Elite Tech Precision Ltd | Automatic Door Repair Ireland — Supply & Install',
        'seo_desc'       => 'Elite Tech Precision Ltd — 22 years supplying, installing & repairing automatic doors, roller shutters, fire doors & aluminium shopfronts across Ireland. Call 045-875406.',
        'seo_keywords'   => 'automatic door repair Ireland, automatic door installation, roller shutters, fire doors, aluminium shopfronts',
        'hero_heading'   => "Automatic Door Repair —\nSupply, Installation\n& Maintenance Ireland",
        'hero_sub'       => 'Elite Tech Precision Ltd provides expert automatic door repair, supply and installation services across Ireland. Available 24/7.',
        'about_heading'  => 'We Have 22 Years of Experience in Automatic Door Repair',
        'about_text'     => 'Founded in 2003, Elite Tech Precision Ltd specialises in Automatic Door Repair services and professional entrance automation solutions throughout Ireland.',
        'site_name'      => 'Elite Tech Precision Ltd',
        'site_phone'     => '045-875406',
        'site_mobile'    => '085 100 0459',
        'site_email'     => 'info@elitedoors.ie',
        'site_address'   => 'Unit 2A, Sickens Caragh, Naas, Co. Kildare, W91ND9F',
        'site_maps_url'  => 'https://maps.app.goo.gl/7yg3G2DqV14MBRsM7',
        'site_facebook'  => 'https://www.facebook.com/profile.php?id=61562841684995',
        'site_instagram' => 'https://www.instagram.com/elitetechprecision/',
        'site_whatsapp'  => 'https://wa.me/353851000459',
        'site_youtube'   => 'https://www.youtube.com/@EliteTechPrecisionLtd',
        'site_linkedin'  => 'https://www.linkedin.com/in/elite-tech-precision-ltd-766a496a/',
        'site_tiktok'    => 'https://www.tiktok.com/@elitetechprecision',
    ];

    $result = db()->query("SELECT setting_key, setting_value FROM site_settings");
    if (!$result) return $defaults;

    $settings = $defaults;
    while ($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

function save_settings(array $data): bool {
    $stmt = db()->prepare(
        "INSERT INTO site_settings (setting_key, setting_value)
         VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    );
    foreach ($data as $key => $val) {
        $stmt->bind_param('ss', $key, $val);
        $stmt->execute();
    }
    return true;
}

// ── Services CRUD helpers ──────────────────────────────────────
function db_get_services(bool $active_only = false): array {
    $sql = "SELECT * FROM services";
    if ($active_only) $sql .= " WHERE is_active = 1";
    $sql .= " ORDER BY sort_order ASC, id ASC";
    $result = db()->query($sql);
    $services = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
    }
    return $services;
}

function db_get_service(int $id): ?array {
    $stmt = db()->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ?: null;
}

function db_add_service(array $data): bool {
    $stmt = db()->prepare(
        "INSERT INTO services (title, slug, short_desc, image, icon, sort_order, is_active)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $active = isset($data['is_active']) ? 1 : 0;
    $order  = (int)($data['sort_order'] ?? 0);
    $stmt->bind_param('sssssii', $data['title'], $data['slug'], $data['short_desc'], $data['image'], $data['icon'], $order, $active);
    return $stmt->execute();
}

function db_update_service(int $id, array $data): bool {
    $stmt = db()->prepare(
        "UPDATE services SET title=?, slug=?, short_desc=?, image=?, icon=?, sort_order=?, is_active=? WHERE id=?"
    );
    $active = isset($data['is_active']) ? 1 : 0;
    $order  = (int)($data['sort_order'] ?? 0);
    $stmt->bind_param('sssssiii', $data['title'], $data['slug'], $data['short_desc'], $data['image'], $data['icon'], $order, $active, $id);
    return $stmt->execute();
}

function db_delete_service(int $id): bool {
    $stmt = db()->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

