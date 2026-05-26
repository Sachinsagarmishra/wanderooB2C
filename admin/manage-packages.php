<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Handle filter & search parameters
$filterDest = isset($_GET['destination']) ? trim($_GET['destination']) : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClauses = [];
$params = [];

if ($filterDest !== '') {
    $whereClauses[] = "destination = ?";
    $params[] = $filterDest;
}

if ($search !== '') {
    $whereClauses[] = "(title LIKE ? OR duration LIKE ? OR slug LIKE ? OR description LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
}

if ($status !== '' && $status !== 'all') {
    $whereClauses[] = "status = ?";
    $params[] = $status;
}

$whereSql = '';
if (!empty($whereClauses)) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

// Get total count
try {
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM tour_packages $whereSql");
    $countStmt->execute($params);
    $totalPackages = intval($countStmt->fetchColumn());
} catch (PDOException $e) {
    die("Database query error (count): " . $e->getMessage());
}

$totalPages = ceil($totalPackages / $limit);
if ($totalPages < 1) $totalPages = 1;
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $limit;
}

// Fetch packages
try {
    $stmt = $pdo->prepare("SELECT * FROM tour_packages $whereSql ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
    $stmt->execute($params);
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
    
    /* Search, Filter & Pagination */
    .search-filter-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
        background: var(--bg2);
        border: 1px solid var(--border);
        padding: 16px;
        border-radius: var(--radius-main);
        align-items: center;
    }
    .form-control {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: var(--radius-int);
        padding: 10px 14px;
        color: var(--fg);
        outline: none;
        font-family: inherit;
        font-size: 13px;
        transition: border-color 0.2s;
        width: 100%;
    }
    .form-control:focus {
        border-color: var(--accent);
    }
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        margin-top: 24px;
    }
    .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        border-radius: var(--radius-int);
        background: var(--bg2);
        border: 1px solid var(--border);
        color: var(--fg2);
        font-weight: 500;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .page-link:hover {
        background: var(--bg3);
        color: var(--fg);
        border-color: var(--fg3);
    }
    .page-link.active {
        background: var(--accent);
        color: #000;
        border-color: var(--accent);
    }
    .page-link.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
</style>

<div class="page-header" style="margin-bottom: 24px;">
    <div>
        <h1>Tour Packages</h1>
        <div class="fg3" style="font-size: 12px; margin-top: -12px;">Total: <strong><?php echo $totalPackages; ?></strong> packages found</div>
    </div>
    <div class="page-header-actions">
        <a href="package-form.php" class="btn btn-primary" style="padding: 10px 20px; font-size: 13px;">+ Add New Package</a>
    </div>
</div>

<div class="search-filter-bar">
    <form method="GET" style="display: flex; gap: 12px; flex: 1; flex-wrap: wrap; width: 100%; align-items: center;">
        <div style="flex: 1; min-width: 250px;">
            <input type="text" name="search" class="form-control" placeholder="Search packages by title, duration, slug..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        
        <div style="width: 180px;">
            <select name="destination" class="form-control" onchange="this.form.submit()">
                <option value="">All Destinations</option>
                <?php
                try {
                    $stmtDests = $pdo->query("SELECT slug, name FROM destinations ORDER BY sort_order, name");
                    while ($destRow = $stmtDests->fetch()) {
                        $selected = ($filterDest === $destRow['slug']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($destRow['slug']) . '" ' . $selected . '>' . htmlspecialchars($destRow['name']) . '</option>';
                    }
                } catch (Exception $e) {
                    echo '<option value="singapore" ' . ($filterDest === 'singapore' ? 'selected' : '') . '>Singapore</option>';
                    echo '<option value="maldives" ' . ($filterDest === 'maldives' ? 'selected' : '') . '>Maldives</option>';
                    echo '<option value="bali" ' . ($filterDest === 'bali' ? 'selected' : '') . '>Bali</option>';
                    echo '<option value="japan" ' . ($filterDest === 'japan' ? 'selected' : '') . '>Japan</option>';
                    echo '<option value="kerala" ' . ($filterDest === 'kerala' ? 'selected' : '') . '>Kerala</option>';
                }
                ?>
            </select>
        </div>
        
        <div style="width: 140px;">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="all" <?php echo $status === 'all' || $status === '' ? 'selected' : ''; ?>>All Status</option>
                <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Draft</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="padding: 10px 16px;">Search</button>
            <?php if ($search !== '' || $filterDest !== '' || ($status !== '' && $status !== 'all')): ?>
                <a href="manage-packages.php" class="btn" style="background: var(--bg3); border: 1px solid var(--border); color: var(--fg); padding: 10px 16px;">Clear</a>
            <?php endif; ?>
        </div>
    </form>
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
                                    <?php
                                    $queryStrings = $_GET;
                                    unset($queryStrings['id']);
                                    $queryString = http_build_query($queryStrings);
                                    $editUrl = "package-form.php?id=" . $pkg['id'] . ($queryString ? '&' . $queryString : '');
                                    $deleteUrl = "delete-package.php?id=" . $pkg['id'] . ($queryString ? '&' . $queryString : '');
                                    ?>
                                    <a href="<?php echo $editUrl; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 11px;">Edit</a>
                                    <a href="<?php echo $deleteUrl; ?>" class="btn" style="padding: 5px 10px; font-size: 11px; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); color: var(--danger);" onclick="return confirm('Are you sure you want to delete this package? This cannot be undone.');">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php
        $queryParams = $_GET;
        
        // Previous page
        if ($page > 1) {
            $queryParams['page'] = $page - 1;
            echo '<a href="manage-packages.php?' . http_build_query($queryParams) . '" class="page-link">&laquo;</a>';
        } else {
            echo '<span class="page-link disabled">&laquo;</span>';
        }
        
        // Page numbers
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo '<span class="page-link active">' . $i . '</span>';
            } else {
                if ($i == 1 || $i == $totalPages || abs($i - $page) <= 2) {
                    $queryParams['page'] = $i;
                    echo '<a href="manage-packages.php?' . http_build_query($queryParams) . '" class="page-link">' . $i . '</a>';
                } elseif ($i == 2 || $i == $totalPages - 1) {
                    echo '<span class="page-link disabled">...</span>';
                }
            }
        }
        
        // Next page
        if ($page < $totalPages) {
            $queryParams['page'] = $page + 1;
            echo '<a href="manage-packages.php?' . http_build_query($queryParams) . '" class="page-link">&raquo;</a>';
        } else {
            echo '<span class="page-link disabled">&raquo;</span>';
        }
        ?>
    </div>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
