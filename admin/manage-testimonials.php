<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

try {
    $stmt = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order, created_at DESC");
    $testimonials = $stmt->fetchAll();
} catch (PDOException $e) {
    $testimonials = [];
    $error = "Error fetching testimonials: " . $e->getMessage();
}

function testimonial_admin_image_url($path) {
    if (empty($path)) {
        return '';
    }
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    return SITE_PATH . '/' . ltrim($path, '/');
}
?>

<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
    .alert { padding: 12px 16px; border-radius: var(--radius-int); margin-bottom: 24px; font-weight: 500; font-size: 13px; }
    .alert-success { background: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.2); }
    .alert-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); }
    .empty-state { text-align: center; padding: 60px 20px; color: var(--fg3); }
    .empty-state h3 { margin-bottom: 8px; color: var(--fg2); }
    .avatar-thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 50%; border: 1px solid var(--border); background: var(--bg3); }
    .status-badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .status-active { background: rgba(34, 197, 94, 0.1); color: var(--success); }
    .status-draft { background: rgba(148, 163, 184, 0.14); color: var(--fg3); }
    .rating-stars { color: var(--accent); letter-spacing: 1px; white-space: nowrap; }
</style>

<div class="page-header">
    <h1>Manage Testimonials</h1>
    <div class="page-header-actions">
        <a href="testimonial-form.php" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">+ Add New Testimonial</a>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Review</th>
                    <th>Stars</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($testimonials)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <h3>No testimonials yet</h3>
                                <p>Add testimonials to show them dynamically on the homepage.</p>
                                <a href="testimonial-form.php" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">+ Add New Testimonial</a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($testimonials as $testimonial): ?>
                        <?php $imgUrl = testimonial_admin_image_url($testimonial['image_path'] ?? ''); ?>
                        <tr>
                            <td>
                                <?php if (!empty($imgUrl)): ?>
                                    <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="" class="avatar-thumb">
                                <?php else: ?>
                                    <div class="avatar-thumb" style="display:flex;align-items:center;justify-content:center;color:var(--fg3);font-size:10px;">No</div>
                                <?php endif; ?>
                            </td>
                            <td><strong style="color: var(--fg); font-size: 13px;"><?php echo htmlspecialchars($testimonial['customer_name']); ?></strong></td>
                            <td style="max-width: 420px;"><?php echo htmlspecialchars(strlen($testimonial['content']) > 130 ? substr($testimonial['content'], 0, 130) . '...' : $testimonial['content']); ?></td>
                            <td><span class="rating-stars"><?php echo str_repeat('★', max(1, min(5, intval($testimonial['rating'])))); ?></span></td>
                            <td><?php echo intval($testimonial['sort_order']); ?></td>
                            <td><span class="status-badge status-<?php echo htmlspecialchars($testimonial['status']); ?>"><?php echo htmlspecialchars($testimonial['status']); ?></span></td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="testimonial-form.php?id=<?php echo intval($testimonial['id']); ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 11px;">Edit</a>
                                    <a href="delete-testimonial.php?id=<?php echo intval($testimonial['id']); ?>" class="btn" style="padding: 5px 10px; font-size: 11px; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); color: var(--danger);" onclick="return confirm('Are you sure you want to delete this testimonial?');">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
