<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Fetch destinations with package counts
try {
    $stmt = $pdo->query("
        SELECT d.*, COUNT(p.id) as package_count 
        FROM destinations d 
        LEFT JOIN tour_packages p ON d.slug = p.destination 
        GROUP BY d.id 
        ORDER BY d.sort_order, d.name
    ");
    $destinationsList = $stmt->fetchAll();
} catch (PDOException $e) {
    $destinationsList = [];
    $error = "Error fetching destinations: " . $e->getMessage();
}
?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .dest-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        background: rgba(245, 197, 24, 0.1);
        color: var(--accent);
    }
    .pkg-thumb {
        width: 60px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid var(--border);
    }
    .icon-thumb {
        width: 32px;
        height: 32px;
        object-fit: contain;
        background: var(--bg3);
        border-radius: 4px;
        padding: 4px;
        border: 1px solid var(--border);
    }
    .alert {
        padding: 12px 16px;
        border-radius: var(--radius-int);
        margin-bottom: 24px;
        font-weight: 500;
        font-size: 13px;
    }
    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        color: var(--success);
        border: 1px solid rgba(34, 197, 94, 0.2);
    }
    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--fg3);
    }
    .empty-state h3 {
        margin-bottom: 8px;
        color: var(--fg2);
    }
    .empty-state p {
        margin-bottom: 20px;
    }
</style>

<div class="page-header">
    <h1>Manage Destinations</h1>
    <div class="page-header-actions">
        <a href="destination-form.php" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">+ Add New Destination</a>
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
                    <th>Bg Image</th>
                    <th>Icon</th>
                    <th>Destination Name</th>
                    <th>Slug</th>
                    <th>Breadcrumb</th>
                    <th>Sort Order</th>
                    <th style="text-align: center;">Active Packages</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($destinationsList)): ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <h3>No destinations yet</h3>
                                <p>Create your first destination to get started.</p>
                                <a href="destination-form.php" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">+ Add New Destination</a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($destinationsList as $dest): 
                        $isBgExternal = (strpos($dest['hero_bg'], 'http://') === 0 || strpos($dest['hero_bg'], 'https://') === 0);
                        $bgUrl = $isBgExternal ? $dest['hero_bg'] : SITE_PATH . '/' . $dest['hero_bg'];
                        
                        $isIconExternal = (strpos($dest['dropdown_icon'], 'http://') === 0 || strpos($dest['dropdown_icon'], 'https://') === 0);
                        $iconUrl = $isIconExternal ? $dest['dropdown_icon'] : SITE_PATH . '/' . $dest['dropdown_icon'];
                    ?>
                        <tr>
                            <td>
                                <?php if (!empty($dest['hero_bg'])): ?>
                                    <img src="<?php echo htmlspecialchars($bgUrl); ?>" alt="" class="pkg-thumb">
                                <?php else: ?>
                                    <div style="width:60px;height:40px;background:var(--bg3);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;color:var(--fg3);">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($dest['dropdown_icon'])): ?>
                                    <img src="<?php echo htmlspecialchars($iconUrl); ?>" alt="" class="icon-thumb">
                                <?php else: ?>
                                    <div style="width:32px;height:32px;background:var(--bg3);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;color:var(--fg3);">-</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong style="color: var(--fg); font-size: 13px;"><?php echo htmlspecialchars($dest['name']); ?></strong>
                            </td>
                            <td><code><?php echo htmlspecialchars($dest['slug']); ?></code></td>
                            <td><?php echo htmlspecialchars($dest['breadcrumb']); ?></td>
                            <td><?php echo intval($dest['sort_order']); ?></td>
                            <td style="text-align: center;">
                                <span class="dest-badge"><?php echo intval($dest['package_count']); ?> packages</span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="destination-form.php?id=<?php echo $dest['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 11px;">Edit</a>
                                    <a href="delete-destination.php?id=<?php echo $dest['id']; ?>" class="btn" style="padding: 5px 10px; font-size: 11px; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); color: var(--danger);" onclick="return confirm('Are you sure you want to delete this destination? This will break pages for packages linked to it. This cannot be undone.');">Delete</a>
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
