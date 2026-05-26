<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Handle search & pagination parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClauses = [];
$params = [];

if ($search !== '') {
    $whereClauses[] = "(d.name LIKE ? OR d.slug LIKE ? OR d.title LIKE ? OR d.breadcrumb LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
}

$whereSql = '';
if (!empty($whereClauses)) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

// Get total count
try {
    $countStmt = $pdo->prepare("
        SELECT COUNT(DISTINCT d.id) 
        FROM destinations d 
        LEFT JOIN tour_packages p ON d.slug = p.destination 
        $whereSql
    ");
    $countStmt->execute($params);
    $totalDestinations = intval($countStmt->fetchColumn());
} catch (PDOException $e) {
    die("Database query error (count): " . $e->getMessage());
}

$totalPages = ceil($totalDestinations / $limit);
if ($totalPages < 1) $totalPages = 1;
if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $limit;
}

// Fetch destinations with package counts
try {
    $stmt = $pdo->prepare("
        SELECT d.*, COUNT(p.id) as package_count 
        FROM destinations d 
        LEFT JOIN tour_packages p ON d.slug = p.destination 
        $whereSql
        GROUP BY d.id 
        ORDER BY d.sort_order, d.name
        LIMIT $limit OFFSET $offset
    ");
    $stmt->execute($params);
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
        <h1>Manage Destinations</h1>
        <div class="fg3" style="font-size: 12px; margin-top: -12px;">Total: <strong><?php echo $totalDestinations; ?></strong> destinations found</div>
    </div>
    <div class="page-header-actions">
        <a href="destination-form.php" class="btn btn-primary" style="padding: 10px 20px; font-size: 13px;">+ Add New Destination</a>
    </div>
</div>

<div class="search-filter-bar">
    <form method="GET" style="display: flex; gap: 12px; flex: 1; flex-wrap: wrap; width: 100%; align-items: center;">
        <div style="flex: 1; min-width: 250px;">
            <input type="text" name="search" class="form-control" placeholder="Search destinations by name, slug, title..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="padding: 10px 16px;">Search</button>
            <?php if ($search !== ''): ?>
                <a href="manage-destinations.php" class="btn" style="background: var(--bg3); border: 1px solid var(--border); color: var(--fg); padding: 10px 16px;">Clear</a>
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
                                    <?php
                                    $queryStrings = $_GET;
                                    unset($queryStrings['id']);
                                    $queryString = http_build_query($queryStrings);
                                    $editUrl = "destination-form.php?id=" . $dest['id'] . ($queryString ? '&' . $queryString : '');
                                    $deleteUrl = "delete-destination.php?id=" . $dest['id'] . ($queryString ? '&' . $queryString : '');
                                    ?>
                                    <a href="<?php echo $editUrl; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 11px;">Edit</a>
                                    <a href="<?php echo $deleteUrl; ?>" class="btn" style="padding: 5px 10px; font-size: 11px; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); color: var(--danger);" onclick="return confirm('Are you sure you want to delete this destination? This will break pages for packages linked to it. This cannot be undone.');">Delete</a>
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
            echo '<a href="manage-destinations.php?' . http_build_query($queryParams) . '" class="page-link">&laquo;</a>';
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
                    echo '<a href="manage-destinations.php?' . http_build_query($queryParams) . '" class="page-link">' . $i . '</a>';
                } elseif ($i == 2 || $i == $totalPages - 1) {
                    echo '<span class="page-link disabled">...</span>';
                }
            }
        }
        
        // Next page
        if ($page < $totalPages) {
            $queryParams['page'] = $page + 1;
            echo '<a href="manage-destinations.php?' . http_build_query($queryParams) . '" class="page-link">&raquo;</a>';
        } else {
            echo '<span class="page-link disabled">&raquo;</span>';
        }
        ?>
    </div>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
