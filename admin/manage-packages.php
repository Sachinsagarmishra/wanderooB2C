<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Handle filter
$filterDest = isset($_GET['destination']) ? trim($_GET['destination']) : '';

// Fetch packages
try {
    if (!empty($filterDest)) {
        $stmt = $pdo->prepare("SELECT * FROM tour_packages WHERE destination = ? ORDER BY created_at DESC");
        $stmt->execute([$filterDest]);
    } else {
        $stmt = $pdo->query("SELECT * FROM tour_packages ORDER BY created_at DESC");
    }
    $packages = $stmt->fetchAll();
} catch (PDOException $e) {
    $packages = [];
    $error = "Error fetching packages: " . $e->getMessage();
}

// Get photo counts for each package
$photoCounts = [];
try {
    $stmt = $pdo->query("SELECT package_id, COUNT(*) as cnt FROM package_photos GROUP BY package_id");
    while ($row = $stmt->fetch()) {
        $photoCounts[$row['package_id']] = $row['cnt'];
    }
} catch (PDOException $e) {}
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
    .page-header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .filter-select {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: var(--radius-int);
        padding: 8px 12px;
        color: var(--fg);
        font-family: inherit;
        font-size: 12px;
        outline: none;
    }
    .filter-select:focus {
        border-color: var(--accent);
    }
    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .status-dot.active { background: var(--success); }
    .status-dot.draft { background: var(--fg3); }
    .pkg-thumb {
        width: 60px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
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
</style>

<div class="page-header">
    <h1>Tour Packages</h1>
    <div class="page-header-actions">
        <form method="GET" style="display: flex; gap: 8px; align-items: center;">
            <select name="destination" class="filter-select" onchange="this.form.submit()">
                <option value="">All Destinations</option>
                <option value="singapore" <?php echo $filterDest === 'singapore' ? 'selected' : ''; ?>>Singapore</option>
                <option value="maldives" <?php echo $filterDest === 'maldives' ? 'selected' : ''; ?>>Maldives</option>
                <option value="bali" <?php echo $filterDest === 'bali' ? 'selected' : ''; ?>>Bali</option>
                <option value="japan" <?php echo $filterDest === 'japan' ? 'selected' : ''; ?>>Japan</option>
                <option value="kerala" <?php echo $filterDest === 'kerala' ? 'selected' : ''; ?>>Kerala</option>
            </select>
        </form>
        <a href="package-form.php" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">+ Add New Package</a>
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
                    <th>Package Title</th>
                    <th>Destination</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Photos</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($packages)): ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <h3>No packages yet</h3>
                                <p>Create your first tour package to get started.</p>
                                <a href="package-form.php" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">+ Add New Package</a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($packages as $pkg): ?>
                        <tr>
                            <td>
                                <?php if (!empty($pkg['hero_image'])): ?>
                                    <img src="<?php echo SITE_PATH; ?>/<?php echo htmlspecialchars($pkg['hero_image']); ?>" alt="" class="pkg-thumb">
                                <?php else: ?>
                                    <div style="width:60px;height:40px;background:var(--bg3);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;color:var(--fg3);">No img</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong style="color: var(--fg); font-size: 13px;"><?php echo htmlspecialchars($pkg['title']); ?></strong>
                                <div style="font-size: 11px; color: var(--fg3); margin-top: 2px;">/<?php echo htmlspecialchars($pkg['destination']); ?>/<?php echo htmlspecialchars($pkg['slug']); ?></div>
                            </td>
                            <td><span class="dest-badge"><?php echo htmlspecialchars(ucfirst($pkg['destination'])); ?></span></td>
                            <td><?php echo htmlspecialchars($pkg['duration']); ?></td>
                            <td style="white-space: nowrap;">
                                <strong style="color: var(--fg);"><?php echo htmlspecialchars($pkg['price']); ?></strong>
                                <?php if (!empty($pkg['old_price'])): ?>
                                    <div style="font-size: 10px; color: var(--fg3); text-decoration: line-through;"><?php echo htmlspecialchars($pkg['old_price']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;"><?php echo $photoCounts[$pkg['id']] ?? 0; ?></td>
                            <td>
                                <span class="status-dot <?php echo $pkg['status']; ?>"></span>
                                <?php echo ucfirst($pkg['status']); ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="package-form.php?id=<?php echo $pkg['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 11px;">Edit</a>
                                    <a href="delete-package.php?id=<?php echo $pkg['id']; ?>" class="btn" style="padding: 5px 10px; font-size: 11px; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); color: var(--danger);" onclick="return confirm('Are you sure you want to delete this package? This cannot be undone.');">Delete</a>
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
