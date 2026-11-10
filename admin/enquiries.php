<?php
require_once __DIR__ . '/includes/config.php';
require_login();
$page_title = 'Enquiries';

// Try to get submissions from DB
$enquiries = [];
try {
    $result = db()->query(
        "SELECT s.id, s.created_at, 
                MAX(CASE WHEN sv.form_field_name='name'    THEN sv.value END) as name,
                MAX(CASE WHEN sv.form_field_name='email'   THEN sv.value END) as email,
                MAX(CASE WHEN sv.form_field_name='phone'   THEN sv.value END) as phone,
                MAX(CASE WHEN sv.form_field_name='service' THEN sv.value END) as service,
                MAX(CASE WHEN sv.form_field_name='message' THEN sv.value END) as message
         FROM " . DB_PREFIX . "e_submissions s
         LEFT JOIN " . DB_PREFIX . "e_submissions_values sv ON s.id = sv.submission_id
         GROUP BY s.id
         ORDER BY s.created_at DESC
         LIMIT 100"
    );
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $enquiries[] = $row;
        }
    }
} catch (Exception $e) {
    // DB query failed — no enquiries
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="admin-card">
    <div class="admin-card-header">
        <i class="bi bi-envelope"></i>
        <h2>Form Enquiries</h2>
        <span class="ms-auto badge" style="background:#AA336A;"><?= count($enquiries) ?> total</span>
    </div>

    <?php if (empty($enquiries)): ?>
    <div class="admin-card-body text-center py-5">
        <i class="bi bi-inbox" style="font-size:3rem;color:#ddd;"></i>
        <p class="text-muted mt-3 mb-0">No enquiries found in the database yet.</p>
        <p class="text-muted" style="font-size:13px;">Enquiries submitted via the contact form will appear here.</p>
    </div>
    <?php else: ?>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px;">
                <thead style="background:#fafafa;">
                    <tr>
                        <th style="padding:12px 16px;">#</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Service</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enquiries as $i => $eq): ?>
                    <tr>
                        <td style="padding:12px 16px;"><?= $i + 1 ?></td>
                        <td style="white-space:nowrap;">
                            <?= htmlspecialchars(date('d M Y H:i', strtotime($eq['created_at']))) ?>
                        </td>
                        <td><strong><?= htmlspecialchars($eq['name'] ?? '—') ?></strong></td>
                        <td>
                            <a href="mailto:<?= htmlspecialchars($eq['email'] ?? '') ?>">
                                <?= htmlspecialchars($eq['email'] ?? '—') ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($eq['phone'] ?? '—') ?></td>
                        <td>
                            <span class="badge" style="background:#702963;font-size:11px;">
                                <?= htmlspecialchars($eq['service'] ?? '—') ?>
                            </span>
                        </td>
                        <td style="max-width:200px;">
                            <span title="<?= htmlspecialchars($eq['message'] ?? '') ?>">
                                <?= htmlspecialchars(substr($eq['message'] ?? '', 0, 60)) ?>
                                <?= strlen($eq['message'] ?? '') > 60 ? '...' : '' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
