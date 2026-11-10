<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$page_title = 'Services';
$success = $error = '';

// ── Handle actions ─────────────────────────────────────────────
$action = $_GET['action'] ?? ($_POST['action'] ?? 'list');

// Delete
if ($action === 'delete' && isset($_GET['id'])) {
    db_delete_service((int)$_GET['id']);
    header('Location: services.php?deleted=1'); exit;
}

// Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $data = [
        'title'      => trim($_POST['title'] ?? ''),
        'slug'       => !empty($_POST['slug']) ? trim($_POST['slug']) : slugify($_POST['title']),
        'short_desc'=> trim($_POST['short_desc'] ?? ''),
        'image'      => trim($_POST['image'] ?? 'doors-banner.jpg'),
        'icon'       => trim($_POST['icon'] ?? 'bi-tools'),
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
        'is_active'  => isset($_POST['is_active']),
    ];
    if (empty($data['title'])) {
        $error = 'Service title is required.';
    } else {
        db_add_service($data);
        header('Location: services.php?added=1'); exit;
    }
}

// Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {
    $id = (int)($_POST['id'] ?? 0);
    $data = [
        'title'      => trim($_POST['title'] ?? ''),
        'slug'       => !empty($_POST['slug']) ? trim($_POST['slug']) : slugify($_POST['title']),
        'short_desc'=> trim($_POST['short_desc'] ?? ''),
        'image'      => trim($_POST['image'] ?? 'doors-banner.jpg'),
        'icon'       => trim($_POST['icon'] ?? 'bi-tools'),
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
        'is_active'  => isset($_POST['is_active']),
    ];
    if (empty($data['title'])) {
        $error = 'Service title is required.';
    } else {
        db_update_service($id, $data);
        header('Location: services.php?updated=1'); exit;
    }
}

if (isset($_GET['added']))   $success = 'Service added successfully.';
if (isset($_GET['updated'])) $success = 'Service updated successfully.';
if (isset($_GET['deleted'])) $success = 'Service deleted successfully.';

require_once __DIR__ . '/includes/header.php';

// Available icons
$icons = ['bi-door-open','bi-box-arrow-in-right','bi-arrows-expand','bi-building','bi-building-fill',
          'bi-layout-three-columns','bi-columns-gap','bi-shield-fill-check','bi-sign-turn-right',
          'bi-grid-3x3-gap','bi-layers','bi-calendar-check','bi-tools','bi-truck','bi-fire',
          'bi-window','bi-door-closed','bi-shield-lock','bi-gear','bi-hammer'];
?>

<?php if ($success): ?><div class="alert-success-ap"><i class="bi bi-check-circle me-2"></i><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="alert-error-ap"><i class="bi bi-x-circle me-2"></i><?= $error ?></div><?php endif; ?>

<?php if ($action === 'add' || ($action === 'edit' && isset($_GET['id']))): ?>
<!-- ═══ ADD / EDIT FORM ═══ -->
<?php
    $edit_service = null;
    if ($action === 'edit') {
        $edit_service = db_get_service((int)$_GET['id']);
        if (!$edit_service) { echo '<div class="alert-error-ap">Service not found.</div>'; require_once __DIR__ . '/includes/footer.php'; exit; }
    }
?>
<div class="admin-card">
    <div class="admin-card-header">
        <i class="bi bi-<?= $action === 'edit' ? 'pencil' : 'plus-circle' ?>"></i>
        <h2><?= $action === 'edit' ? 'Edit Service' : 'Add New Service' ?></h2>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="services.php">
            <input type="hidden" name="action" value="<?= $action ?>">
            <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?= $edit_service['id'] ?>"><?php endif; ?>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Service Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required
                        value="<?= htmlspecialchars($edit_service['title'] ?? '') ?>"
                        placeholder="e.g. Automatic Doors">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Slug <span style="font-weight:400;color:#999;">(URL)</span></label>
                    <input type="text" name="slug" class="form-control"
                        value="<?= htmlspecialchars($edit_service['slug'] ?? '') ?>"
                        placeholder="auto-generated">
                    <div class="form-hint">Leave blank to auto-generate from title.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Short Description</label>
                    <textarea name="short_desc" class="form-control" rows="3"
                        placeholder="Brief description shown on the website..."><?= htmlspecialchars($edit_service['short_desc'] ?? '') ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Image Filename</label>
                    <input type="text" name="image" class="form-control"
                        value="<?= htmlspecialchars($edit_service['image'] ?? 'doors-banner.jpg') ?>"
                        placeholder="e.g. automatic-doors.jpg">
                    <div class="form-hint">File must exist in /assets/images/ folder.</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Icon</label>
                    <select name="icon" class="form-select">
                        <?php foreach ($icons as $ic): ?>
                        <option value="<?= $ic ?>" <?= ($edit_service['icon'] ?? '') === $ic ? 'selected' : '' ?>>
                            <?= $ic ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control"
                        value="<?= htmlspecialchars($edit_service['sort_order'] ?? 0) ?>" min="0">
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive"
                            <?= (!isset($edit_service) || $edit_service['is_active']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isActive">Active (show on website)</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn-save"><i class="bi bi-save me-2"></i><?= $action === 'edit' ? 'Update' : 'Add' ?> Service</button>
                <a href="services.php" class="btn-view"><i class="bi bi-arrow-left me-1"></i>Back to List</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<!-- ═══ SERVICES LIST ═══ -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0" style="font-size:18px;font-weight:700;color:var(--ap);">All Services (<?= count(db_get_services()) ?>)</h2>
    <a href="services.php?action=add" class="btn-save" style="text-decoration:none;">
        <i class="bi bi-plus-circle me-2"></i>Add New Service
    </a>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        <table class="table table-hover mb-0" style="font-size:14px;">
            <thead style="background:#fafafa;">
                <tr>
                    <th style="padding:14px 16px;">#</th>
                    <th>Service Name</th>
                    <th>Slug</th>
                    <th>Image</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (db_get_services() as $i => $svc): ?>
                <tr>
                    <td style="padding:12px 16px;"><?= $i + 1 ?></td>
                    <td>
                        <i class="bi <?= htmlspecialchars($svc['icon']) ?> me-2" style="color:#AA336A;"></i>
                        <strong><?= htmlspecialchars($svc['title']) ?></strong>
                    </td>
                    <td><code style="font-size:12px;"><?= htmlspecialchars($svc['slug']) ?></code></td>
                    <td>
                        <?php $img_path = ROOT_PATH . '/assets/images/' . $svc['image']; ?>
                        <?php if (file_exists($img_path)): ?>
                        <img src="<?= '../assets/images/' . htmlspecialchars($svc['image']) ?>"
                             style="width:48px;height:36px;object-fit:cover;border-radius:4px;border:1px solid #eee;">
                        <?php else: ?>
                        <span style="font-size:11px;color:#ccc;">No image</span>
                        <?php endif; ?>
                    </td>
                    <td><?= (int)$svc['sort_order'] ?></td>
                    <td>
                        <span class="badge" style="background:<?= $svc['is_active'] ? '#28a745' : '#6c757d' ?>;font-size:11px;">
                            <?= $svc['is_active'] ? 'Active' : 'Hidden' ?>
                        </span>
                    </td>
                    <td>
                        <a href="services.php?action=edit&id=<?= $svc['id'] ?>" class="btn btn-sm btn-outline-primary" style="font-size:12px;padding:3px 10px;">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="services.php?action=delete&id=<?= $svc['id'] ?>"
                           onclick="return confirm('Delete this service? This cannot be undone.')"
                           class="btn btn-sm btn-outline-danger" style="font-size:12px;padding:3px 10px;">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
