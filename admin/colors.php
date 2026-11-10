<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$page_title = 'Color Scheme';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    save_settings([
        'color_primary'  => trim($_POST['color_primary']  ?? '#702963'),
        'color_accent'    => trim($_POST['color_accent']   ?? '#AA336A'),
        'color_dark_bg'   => trim($_POST['color_dark_bg']  ?? '#15181A'),
        'color_light_bg'  => trim($_POST['color_light_bg'] ?? '#F6F4F0'),
        'color_white'     => trim($_POST['color_white']    ?? '#FFFFFF'),
    ]);
    header('Location: colors.php?saved=1'); exit;
}
if (isset($_GET['saved'])) $success = 'Color scheme updated successfully.';
$s = get_settings();
require_once __DIR__ . '/includes/header.php';

$colors = [
    'color_primary'  => ['Primary Colour', 'Main brand colour — headings, buttons, navbar accents', $s['color_primary'] ?? '#702963'],
    'color_accent'    => ['Accent Colour', 'CTA buttons, links, hover states, icons', $s['color_accent'] ?? '#AA336A'],
    'color_dark_bg'   => ['Dark Background', 'Dark sections, footer, process area', $s['color_dark_bg'] ?? '#15181A'],
    'color_light_bg'  => ['Light Background', 'Light sections — about, why-us, clients', $s['color_light_bg'] ?? '#F6F4F0'],
    'color_white'     => ['White', 'Cards, form backgrounds, mobile nav', $s['color_white'] ?? '#FFFFFF'],
];
?>

<?php if ($success): ?><div class="alert-success-ap"><i class="bi bi-check-circle me-2"></i><?= $success ?></div><?php endif; ?>

<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-palette"></i><h2>Website Color Scheme</h2></div>
    <div class="admin-card-body">
        <p class="text-muted mb-4" style="font-size:14px;">Change the colors below to instantly update the entire website. Colors apply to headings, buttons, backgrounds and accents.</p>
        <form method="POST">
            <div class="row g-4">
                <?php foreach ($colors as $key => [$label, $hint, $val]): ?>
                <div class="col-md-6">
                    <label class="form-label"><?= $label ?></label>
                    <div class="input-group" style="height:50px;">
                        <input type="color" name="<?= $key ?>" id="<?= $key ?>_picker"
                            class="form-control-color" style="width:50px;height:50px;border:1px solid #ddd;border-radius:6px 0 0 6px;cursor:pointer;"
                            value="<?= htmlspecialchars($val) ?>">
                        <input type="text" name="<?= $key ?>" id="<?= $key ?>"
                            class="form-control" style="border-radius:0 6px 6px 0;"
                            value="<?= htmlspecialchars($val) ?>"
                            oninput="document.getElementById('<?= $key ?>_picker').value = this.value">
                    </div>
                    <div class="form-hint"><?= $hint ?></div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Live Preview -->
            <div class="mt-4 p-4 rounded" style="border:1px solid #eee;background:<?= htmlspecialchars($s['color_light_bg']) ?>">
                <p class="mb-2" style="font-size:12px;text-transform:uppercase;letter-spacing:1.5px;font-weight:700;color:<?= htmlspecialchars($s['color_accent']) ?>">Preview</p>
                <h3 style="color:<?= htmlspecialchars($s['color_primary']) ?>;font-weight:800;margin-bottom:10px;">Heading Colour Preview</h3>
                <p style="color:#555;margin-bottom:16px;">This is how your body text and headings will look with the selected colors.</p>
                <div style="display:flex;gap:12px;flex-wrap:wrap;">
                    <span style="background:<?= htmlspecialchars($s['color_accent']) ?>;color:#fff;padding:10px 24px;border-radius:4px;font-weight:700;font-size:13px;text-transform:uppercase;cursor:pointer;">Primary Button</span>
                    <span style="background:<?= htmlspecialchars($s['color_primary']) ?>;color:#fff;padding:10px 24px;border-radius:4px;font-weight:700;font-size:13px;text-transform:uppercase;">Secondary</span>
                    <span style="background:<?= htmlspecialchars($s['color_dark_bg']) ?>;color:rgba(255,255,255,.8);padding:10px 24px;border-radius:4px;font-size:13px;">Dark Section BG</span>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn-save"><i class="bi bi-save me-2"></i>Save Color Scheme</button>
                <a href="../" target="_blank" class="btn-view ms-2"><i class="bi bi-eye me-1"></i>View Site</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
