<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$page_title = 'Edit Social Links';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'site_facebook'  => trim($_POST['site_facebook']  ?? ''),
        'site_instagram' => trim($_POST['site_instagram'] ?? ''),
        'site_whatsapp'  => trim($_POST['site_whatsapp']  ?? ''),
        'site_youtube'   => trim($_POST['site_youtube']   ?? ''),
        'site_linkedin'  => trim($_POST['site_linkedin']  ?? ''),
        'site_tiktok'    => trim($_POST['site_tiktok']    ?? ''),
    ];
    save_settings($data);
    header('Location: social.php?saved=1'); exit;
}
if (isset($_GET['saved'])) $success = 'Social links updated successfully.';
$s = get_settings();
require_once __DIR__ . '/includes/header.php';

$platforms = [
    'site_facebook'  => ['Facebook',  'bi-facebook',  '#3b5998', 'https://www.facebook.com/...'],
    'site_instagram' => ['Instagram', 'bi-instagram', '#e1306c', 'https://www.instagram.com/...'],
    'site_whatsapp'  => ['WhatsApp',  'bi-whatsapp',  '#25d366', 'https://wa.me/353...'],
    'site_youtube'   => ['YouTube',   'bi-youtube',   '#ff0000', 'https://www.youtube.com/@...'],
    'site_linkedin'  => ['LinkedIn',  'bi-linkedin',  '#0077b5', 'https://www.linkedin.com/...'],
    'site_tiktok'    => ['TikTok',    'bi-tiktok',    '#010101', 'https://www.tiktok.com/@...'],
];
?>

<?php if ($success): ?><div class="alert-success-ap"><i class="bi bi-check-circle me-2"></i><?= $success ?></div><?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-share"></i><h2>Social Media Links</h2></div>
    <div class="admin-card-body">
        <form method="POST">
            <div class="row g-3">
                <?php foreach ($platforms as $key => [$name, $icon, $color, $placeholder]): ?>
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi <?= $icon ?> me-1" style="color:<?= $color ?>"></i><?= $name ?>
                    </label>
                    <input type="url" name="<?= $key ?>" class="form-control"
                        value="<?= htmlspecialchars($s[$key] ?? '') ?>"
                        placeholder="<?= $placeholder ?>">
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn-save"><i class="bi bi-save me-2"></i>Save Changes</button>
                <a href="../" target="_blank" class="btn-view"><i class="bi bi-eye me-1"></i>Preview Site</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
