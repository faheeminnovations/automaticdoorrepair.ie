<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$page_title = 'SEO Settings';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['seo_title'] ?? '');
    $desc  = trim($_POST['seo_desc']  ?? '');
    if (empty($title) || empty($desc)) {
        $error = 'Title and description are required.';
    } else {
        save_settings([
            'seo_title'    => $title,
            'seo_desc'     => $desc,
            'seo_keywords' => trim($_POST['seo_keywords'] ?? ''),
        ]);
        header('Location: seo.php?saved=1'); exit;
    }
}
if (isset($_GET['saved'])) $success = 'SEO settings updated successfully.';
$s = get_settings();
require_once __DIR__ . '/includes/header.php';

$title_len = strlen($s['seo_title']);
$desc_len  = strlen($s['seo_desc']);
?>

<?php if ($success): ?><div class="alert-success-ap"><i class="bi bi-check-circle me-2"></i><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert-error-ap"><i class="bi bi-x-circle me-2"></i><?= $error ?></div><?php endif; ?>

<!-- Google Preview -->
<div class="admin-card mb-4">
    <div class="admin-card-header"><i class="bi bi-google"></i><h2>Google Search Preview</h2></div>
    <div class="admin-card-body">
        <div style="max-width:600px;padding:16px;background:#f8f9fa;border-radius:8px;font-family:arial,sans-serif;">
            <div style="font-size:12px;color:#202124;">automaticdoorrepair.ie</div>
            <div style="font-size:18px;color:#1a0dab;margin:4px 0;cursor:pointer;" id="previewTitle">
                <?= htmlspecialchars($s['seo_title']) ?>
            </div>
            <div style="font-size:14px;color:#4d5156;line-height:1.5;" id="previewDesc">
                <?= htmlspecialchars($s['seo_desc']) ?>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header"><i class="bi bi-search"></i><h2>Edit SEO Meta</h2></div>
    <div class="admin-card-body">
        <form method="POST">
            <div class="mb-4">
                <label class="form-label">
                    Page Title <span class="text-danger">*</span>
                    <span class="ms-2 <?= $title_len > 60 ? 'text-danger' : 'text-success' ?>" style="font-size:12px;">
                        <?= $title_len ?>/60 chars <?= $title_len > 60 ? '⚠ Too long' : '✓ Good' ?>
                    </span>
                </label>
                <input type="text" name="seo_title" id="seoTitle" class="form-control"
                    value="<?= htmlspecialchars($s['seo_title']) ?>" required maxlength="70">
                <div class="form-hint">Recommended: 50–60 characters. Shown in browser tab and Google results.</div>
            </div>
            <div class="mb-4">
                <label class="form-label">
                    Meta Description <span class="text-danger">*</span>
                    <span class="ms-2 <?= $desc_len > 160 ? 'text-danger' : ($desc_len < 120 ? 'text-warning' : 'text-success') ?>" style="font-size:12px;">
                        <?= $desc_len ?>/160 chars
                    </span>
                </label>
                <textarea name="seo_desc" id="seoDesc" class="form-control" rows="3"
                    required maxlength="170"><?= htmlspecialchars($s['seo_desc']) ?></textarea>
                <div class="form-hint">Recommended: 120–160 characters. Shown under title in Google results.</div>
            </div>
            <div class="mb-4">
                <label class="form-label">Keywords <span style="font-weight:400;color:#999;">(comma separated)</span></label>
                <input type="text" name="seo_keywords" class="form-control"
                    value="<?= htmlspecialchars($s['seo_keywords']) ?>"
                    placeholder="automatic door repair Ireland, roller shutters, fire doors...">
                <div class="form-hint">Not a major ranking factor but good for context.</div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn-save"><i class="bi bi-save me-2"></i>Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
// Live preview update
const titleInput = document.getElementById('seoTitle');
const descInput  = document.getElementById('seoDesc');
const prevTitle  = document.getElementById('previewTitle');
const prevDesc   = document.getElementById('previewDesc');
if (titleInput) titleInput.addEventListener('input', () => prevTitle.textContent = titleInput.value);
if (descInput)  descInput.addEventListener('input',  () => prevDesc.textContent  = descInput.value);
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
