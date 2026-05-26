<?php
require_once __DIR__ . '/../includes/db.php';

// DEBUG LOGGING to writable uploads directory
$logFile = __DIR__ . '/../uploads/save_debug.log';
$logData = date('[Y-m-d H:i:s] ') . "Request received. Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
$logData .= "POST data: " . print_r($_POST, true) . "\n";
$logData .= "FILES data: " . print_r($_FILES, true) . "\n";
$logData .= "Session: " . print_r($_SESSION, true) . "\n";
file_put_contents($logFile, $logData, FILE_APPEND);

// Enforce admin authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Auth check failed. Redirecting to index.php.\n", FILE_APPEND);
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Method is not POST. Redirecting to manage-packages.php.\n", FILE_APPEND);
    header("Location: manage-packages.php");
    exit;
}

try {
    $packageId = intval($_POST['package_id'] ?? 0);
    $isEdit = $packageId > 0;

    // Basic fields
    $title = trim($_POST['title'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $duration = trim($_POST['duration'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $overview = trim($_POST['overview'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $old_price = trim($_POST['old_price'] ?? '');
    $save_text = trim($_POST['save_text'] ?? '');
    $rating = floatval($_POST['rating'] ?? 4.5);
    $rating_count = intval($_POST['rating_count'] ?? 0);
    $status = trim($_POST['status'] ?? 'active');

    if (empty($title) || empty($destination) || empty($price)) {
        header("Location: manage-packages.php?error=" . urlencode("Title, Destination, and Price are required."));
        exit;
    }

    // Auto-calculate save_text if not set
    if (empty($save_text) && !empty($old_price) && !empty($price)) {
        $oldNum = intval(preg_replace('/[^\d]/', '', $old_price));
        $priceNum = intval(preg_replace('/[^\d]/', '', $price));
        if ($oldNum > $priceNum) {
            $diff = $oldNum - $priceNum;
            $formattedDiff = number_format($diff);
            $prefix = 'SAVE INR ';
            if (strpos($price, '₹') !== false || strpos($old_price, '₹') !== false) {
                $prefix = 'SAVE ₹';
            }
            $save_text = $prefix . $formattedDiff;
        }
    }

    // Generate slug from title
    $slug = slugify($title);

    // Check slug uniqueness (exclude current package if editing)
    $stmtCheck = $pdo->prepare("SELECT id FROM tour_packages WHERE slug = ? AND id != ?");
    $stmtCheck->execute([$slug, $packageId]);
    if ($stmtCheck->fetch()) {
        // Append destination to make unique
        $slug = $destination . '-' . $slug;
        $stmtCheck->execute([$slug, $packageId]);
        if ($stmtCheck->fetch()) {
            // Append timestamp
            $slug .= '-' . time();
        }
    }

    // ─── Handle Hero Image Upload ───
    $heroImagePath = '';
    if ($isEdit) {
        // Keep existing hero image if no new one uploaded
        $stmt = $pdo->prepare("SELECT hero_image FROM tour_packages WHERE id = ?");
        $stmt->execute([$packageId]);
        $heroImagePath = $stmt->fetchColumn() ?: '';
    }

    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/packages/';
        
        // Create temp dir using package ID (or temp ID)
        $tempId = $isEdit ? $packageId : 'temp_' . time();
        $pkgUploadDir = $uploadDir . $tempId . '/';
        if (!is_dir($pkgUploadDir)) {
            mkdir($pkgUploadDir, 0755, true);
        }
        
        $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($ext, $allowed)) {
            $heroFilename = 'hero.' . $ext;
            $heroFullPath = $pkgUploadDir . $heroFilename;
            
            // Delete old hero image if exists
            if (!empty($heroImagePath)) {
                $oldHeroFull = __DIR__ . '/../' . $heroImagePath;
                if (file_exists($oldHeroFull)) {
                    @unlink($oldHeroFull);
                }
            }
            
            move_uploaded_file($_FILES['hero_image']['tmp_name'], $heroFullPath);
            $heroImagePath = 'uploads/packages/' . $tempId . '/' . $heroFilename;
        }
    }

    // ─── Insert or Update Package ───
    if ($isEdit) {
        $stmt = $pdo->prepare("UPDATE tour_packages SET 
            destination = ?, title = ?, slug = ?, description = ?, overview = ?,
            duration = ?, old_price = ?, price = ?, save_text = ?,
            rating = ?, rating_count = ?, hero_image = ?, status = ?
            WHERE id = ?");
        $stmt->execute([
            $destination, $title, $slug, $description, $overview,
            $duration, $old_price, $price, $save_text,
            $rating, $rating_count, $heroImagePath, $status,
            $packageId
        ]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO tour_packages 
            (destination, title, slug, description, overview, duration, old_price, price, save_text, rating, rating_count, hero_image, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $destination, $title, $slug, $description, $overview,
            $duration, $old_price, $price, $save_text,
            $rating, $rating_count, $heroImagePath, $status
        ]);
        $packageId = $pdo->lastInsertId();

        // Rename temp upload directory if we used a temp ID
        if (isset($tempId) && strpos($tempId, 'temp_') === 0) {
            $oldDir = __DIR__ . '/../uploads/packages/' . $tempId . '/';
            $newDir = __DIR__ . '/../uploads/packages/' . $packageId . '/';
            if (is_dir($oldDir)) {
                rename($oldDir, $newDir);
                // Update hero_image path
                if (!empty($heroImagePath)) {
                    $heroImagePath = str_replace('uploads/packages/' . $tempId . '/', 'uploads/packages/' . $packageId . '/', $heroImagePath);
                    $pdo->prepare("UPDATE tour_packages SET hero_image = ? WHERE id = ?")->execute([$heroImagePath, $packageId]);
                }
            }
        }
    }

    // ─── Handle Gallery Photo Deletions ───
    $deletePhotos = trim($_POST['delete_photos'] ?? '');
    if (!empty($deletePhotos)) {
        $deleteIds = array_map('intval', explode(',', $deletePhotos));
        foreach ($deleteIds as $delId) {
            // Get file path before deleting
            $stmt = $pdo->prepare("SELECT image_path FROM package_photos WHERE id = ? AND package_id = ?");
            $stmt->execute([$delId, $packageId]);
            $photoPath = $stmt->fetchColumn();
            if ($photoPath) {
                $fullPath = __DIR__ . '/../' . $photoPath;
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }
            $pdo->prepare("DELETE FROM package_photos WHERE id = ? AND package_id = ?")->execute([$delId, $packageId]);
        }
    }

    // ─── Handle Gallery Photo Uploads ───
    if (isset($_FILES['gallery_photos']) && !empty($_FILES['gallery_photos']['name'][0])) {
        $pkgUploadDir = __DIR__ . '/../uploads/packages/' . $packageId . '/';
        if (!is_dir($pkgUploadDir)) {
            mkdir($pkgUploadDir, 0755, true);
        }

        // Get current max sort order
        $stmt = $pdo->prepare("SELECT MAX(sort_order) FROM package_photos WHERE package_id = ?");
        $stmt->execute([$packageId]);
        $maxSort = intval($stmt->fetchColumn());

        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $fileCount = count($_FILES['gallery_photos']['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['gallery_photos']['error'][$i] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['gallery_photos']['name'][$i], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    $maxSort++;
                    $galleryFilename = 'gallery_' . $maxSort . '_' . time() . '.' . $ext;
                    $galleryFullPath = $pkgUploadDir . $galleryFilename;
                    
                    move_uploaded_file($_FILES['gallery_photos']['tmp_name'][$i], $galleryFullPath);
                    
                    $altText = pathinfo($_FILES['gallery_photos']['name'][$i], PATHINFO_FILENAME);
                    $imagePath = 'uploads/packages/' . $packageId . '/' . $galleryFilename;
                    
                    $stmt = $pdo->prepare("INSERT INTO package_photos (package_id, image_path, alt_text, sort_order) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$packageId, $imagePath, $altText, $maxSort]);
                }
            }
        }
    }

    // ─── Save Tags ───
    $pdo->prepare("DELETE FROM package_tags WHERE package_id = ?")->execute([$packageId]);
    $tags = $_POST['tags'] ?? [];
    foreach ($tags as $tag) {
        $tag = trim($tag);
        if (!empty($tag)) {
            $pdo->prepare("INSERT INTO package_tags (package_id, tag_name) VALUES (?, ?)")->execute([$packageId, $tag]);
        }
    }

    // ─── Save Highlights ───
    $pdo->prepare("DELETE FROM package_highlights WHERE package_id = ?")->execute([$packageId]);
    $highlights = $_POST['highlights'] ?? [];
    $sortOrder = 0;
    foreach ($highlights as $hl) {
        $hl = trim($hl);
        if (!empty($hl)) {
            $sortOrder++;
            $pdo->prepare("INSERT INTO package_highlights (package_id, highlight_text, sort_order) VALUES (?, ?, ?)")->execute([$packageId, $hl, $sortOrder]);
        }
    }

    // ─── Save Days ───
    $pdo->prepare("DELETE FROM package_days WHERE package_id = ?")->execute([$packageId]);
    $dayNumbers = $_POST['day_number'] ?? [];
    $dayTitles = $_POST['day_title'] ?? [];
    $dayContents = $_POST['day_content'] ?? [];
    $dayAccommodations = $_POST['day_accommodation'] ?? [];
    $dayMeals = $_POST['day_meals'] ?? [];

    for ($i = 0; $i < count($dayNumbers); $i++) {
        $dayTitle = trim($dayTitles[$i] ?? '');
        $dayContent = trim($dayContents[$i] ?? '');
        if (!empty($dayTitle) || !empty($dayContent)) {
            $pdo->prepare("INSERT INTO package_days (package_id, day_number, day_title, day_content, accommodation, meals) VALUES (?, ?, ?, ?, ?, ?)")
                ->execute([
                    $packageId,
                    intval($dayNumbers[$i]),
                    $dayTitle,
                    $dayContent,
                    trim($dayAccommodations[$i] ?? ''),
                    trim($dayMeals[$i] ?? '')
                ]);
        }
    }

    // ─── Save Inclusions ───
    $pdo->prepare("DELETE FROM package_inclusions WHERE package_id = ?")->execute([$packageId]);
    $inclusions = $_POST['inclusions'] ?? [];
    $sortOrder = 0;
    foreach ($inclusions as $inc) {
        $inc = trim($inc);
        if (!empty($inc)) {
            $sortOrder++;
            $pdo->prepare("INSERT INTO package_inclusions (package_id, type, item_text, sort_order) VALUES (?, 'inclusion', ?, ?)")->execute([$packageId, $inc, $sortOrder]);
        }
    }

    // ─── Save Exclusions ───
    $exclusions = $_POST['exclusions'] ?? [];
    $sortOrder = 0;
    foreach ($exclusions as $exc) {
        $exc = trim($exc);
        if (!empty($exc)) {
            $sortOrder++;
            $pdo->prepare("INSERT INTO package_inclusions (package_id, type, item_text, sort_order) VALUES (?, 'exclusion', ?, ?)")->execute([$packageId, $exc, $sortOrder]);
        }
    }

    $successMsg = $isEdit ? "Package updated successfully!" : "Package created successfully!";
    header("Location: manage-packages.php?success=" . urlencode($successMsg));
    exit;

} catch (PDOException $e) {
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "PDOException: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString() . "\n", FILE_APPEND);
    header("Location: manage-packages.php?error=" . urlencode("Database error: " . $e->getMessage()));
    exit;
} catch (Exception $e) {
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Exception: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString() . "\n", FILE_APPEND);
    header("Location: manage-packages.php?error=" . urlencode("Error: " . $e->getMessage()));
    exit;
}
?>
