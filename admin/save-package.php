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

// Verify CSRF Token
if (!csrf_verify()) {
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "CSRF validation failed. Redirecting to manage-packages.php.\n", FILE_APPEND);
    header("Location: manage-packages.php?error=" . urlencode("Security validation failed (CSRF token mismatch)."));
    exit;
}

try {
    foreach ([
        "ALTER TABLE `tour_packages` ADD COLUMN `meta_title` varchar(255) DEFAULT NULL AFTER `title`",
        "ALTER TABLE `tour_packages` ADD COLUMN `meta_description` text DEFAULT NULL AFTER `meta_title`",
        "ALTER TABLE `tour_packages` ADD COLUMN `focus_keywords` text DEFAULT NULL AFTER `meta_description`",
        "ALTER TABLE `tour_packages` ADD COLUMN `hero_image_alt` varchar(255) DEFAULT NULL AFTER `hero_image`"
    ] as $alterSql) {
        try {
            $pdo->exec($alterSql);
        } catch (PDOException $e) {
            // Column already exists on updated installations.
        }
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS `package_day_images` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `package_id` int(11) NOT NULL,
      `day_number` int(11) NOT NULL,
      `image_path` varchar(500) NOT NULL,
      `alt_text` varchar(255) DEFAULT NULL,
      `sort_order` int(11) DEFAULT 0,
      PRIMARY KEY (`id`),
      KEY `package_id` (`package_id`),
      KEY `day_number` (`day_number`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $packageId = intval($_POST['package_id'] ?? 0);
    $isEdit = $packageId > 0;

    // Basic fields
    $title = trim($_POST['title'] ?? '');
    $slugInput = trim($_POST['slug'] ?? '');
    $metaTitle = trim($_POST['meta_title'] ?? '');
    $metaDescription = trim($_POST['meta_description'] ?? '');
    $focusKeywords = trim($_POST['focus_keywords'] ?? '');
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
    $heroImageAlt = trim($_POST['hero_image_alt'] ?? '');
    $selectedHeroImage = trim($_POST['selected_hero_image'] ?? '');

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

    // Generate slug from SEO URL Slug field, fallback to title.
    $slug = slugify(!empty($slugInput) ? $slugInput : $title);

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

    if (!empty($selectedHeroImage)) {
        $heroImagePath = $selectedHeroImage;
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
            
            move_uploaded_file($_FILES['hero_image']['tmp_name'], $heroFullPath);
            $heroImagePath = 'uploads/packages/' . $tempId . '/' . $heroFilename;
        }
    }

    // ─── Insert or Update Package ───
    if ($isEdit) {
        $stmt = $pdo->prepare("UPDATE tour_packages SET 
            destination = ?, title = ?, slug = ?, meta_title = ?, meta_description = ?, focus_keywords = ?, description = ?, overview = ?,
            duration = ?, old_price = ?, price = ?, save_text = ?,
            rating = ?, rating_count = ?, hero_image = ?, status = ?
            WHERE id = ?");
        $stmt->execute([
            $destination, $title, $slug, $metaTitle, $metaDescription, $focusKeywords, $description, $overview,
            $duration, $old_price, $price, $save_text,
            $rating, $rating_count, $heroImagePath, $status,
            $packageId
        ]);
        $pdo->prepare("UPDATE tour_packages SET hero_image_alt = ? WHERE id = ?")->execute([$heroImageAlt, $packageId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO tour_packages 
            (destination, title, slug, meta_title, meta_description, focus_keywords, description, overview, duration, old_price, price, save_text, rating, rating_count, hero_image, hero_image_alt, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $destination, $title, $slug, $metaTitle, $metaDescription, $focusKeywords, $description, $overview,
            $duration, $old_price, $price, $save_text,
            $rating, $rating_count, $heroImagePath, $heroImageAlt, $status
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
                $ownedPrefix = 'uploads/packages/' . $packageId . '/';
                if (strpos($photoPath, $ownedPrefix) === 0 && file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }
            $pdo->prepare("DELETE FROM package_photos WHERE id = ? AND package_id = ?")->execute([$delId, $packageId]);
        }
    }

    $existingPhotoAlt = $_POST['existing_photo_alt'] ?? [];
    foreach ($existingPhotoAlt as $photoId => $altText) {
        $pdo->prepare("UPDATE package_photos SET alt_text = ? WHERE id = ? AND package_id = ?")
            ->execute([trim($altText), intval($photoId), $packageId]);
    }

    $existingGalleryPaths = $_POST['existing_gallery_paths'] ?? [];
    $existingGalleryAlt = $_POST['existing_gallery_alt'] ?? [];
    if (!empty($existingGalleryPaths)) {
        $stmt = $pdo->prepare("SELECT MAX(sort_order) FROM package_photos WHERE package_id = ?");
        $stmt->execute([$packageId]);
        $maxSort = intval($stmt->fetchColumn());

        foreach ($existingGalleryPaths as $idx => $mediaPath) {
            $mediaPath = trim($mediaPath);
            if (empty($mediaPath)) {
                continue;
            }
            $maxSort++;
            $altText = trim($existingGalleryAlt[$idx] ?? '');
            if (empty($altText)) {
                $altText = pathinfo($mediaPath, PATHINFO_FILENAME);
            }
            $stmt = $pdo->prepare("INSERT INTO package_photos (package_id, image_path, alt_text, sort_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$packageId, $mediaPath, $altText, $maxSort]);
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
                    
                    $altText = trim($_POST['gallery_upload_alt'] ?? '');
                    if (empty($altText)) {
                        $altText = pathinfo($_FILES['gallery_photos']['name'][$i], PATHINFO_FILENAME);
                    }
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

    // ─── Save Day-wise Images ───
    $pdo->prepare("DELETE FROM package_day_images WHERE package_id = ?")->execute([$packageId]);
    $dayExistingPaths = $_POST['day_existing_image_paths'] ?? [];
    $dayExistingAlt = $_POST['day_existing_image_alt'] ?? [];
    $daySortOrders = [];

    foreach ($dayExistingPaths as $dayNumber => $paths) {
        $dayNumber = intval($dayNumber);
        if (!is_array($paths)) {
            continue;
        }
        foreach ($paths as $idx => $path) {
            $path = trim($path);
            if ($path === '') {
                continue;
            }
            $daySortOrders[$dayNumber] = ($daySortOrders[$dayNumber] ?? 0) + 1;
            $altText = trim($dayExistingAlt[$dayNumber][$idx] ?? '');
            if ($altText === '') {
                $altText = pathinfo($path, PATHINFO_FILENAME);
            }
            $stmt = $pdo->prepare("INSERT INTO package_day_images (package_id, day_number, image_path, alt_text, sort_order) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$packageId, $dayNumber, $path, $altText, $daySortOrders[$dayNumber]]);
        }
    }

    if (isset($_FILES['day_images']) && !empty($_FILES['day_images']['name'])) {
        $dayUploadDir = __DIR__ . '/../uploads/packages/' . $packageId . '/days/';
        if (!is_dir($dayUploadDir)) {
            mkdir($dayUploadDir, 0755, true);
        }
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $dayUploadAlt = $_POST['day_upload_alt'] ?? [];

        foreach ($_FILES['day_images']['name'] as $dayNumber => $fileNames) {
            $dayNumber = intval($dayNumber);
            if (!is_array($fileNames)) {
                continue;
            }
            foreach ($fileNames as $idx => $fileName) {
                if (($_FILES['day_images']['error'][$dayNumber][$idx] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                    continue;
                }
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed, true)) {
                    continue;
                }
                $daySortOrders[$dayNumber] = ($daySortOrders[$dayNumber] ?? 0) + 1;
                $dayFileName = 'day_' . $dayNumber . '_' . $daySortOrders[$dayNumber] . '_' . time() . '.' . $ext;
                $fullPath = $dayUploadDir . $dayFileName;
                if (move_uploaded_file($_FILES['day_images']['tmp_name'][$dayNumber][$idx], $fullPath)) {
                    $imagePath = 'uploads/packages/' . $packageId . '/days/' . $dayFileName;
                    $altText = trim($dayUploadAlt[$dayNumber] ?? '');
                    if ($altText === '') {
                        $altText = pathinfo($fileName, PATHINFO_FILENAME);
                    }
                    $stmt = $pdo->prepare("INSERT INTO package_day_images (package_id, day_number, image_path, alt_text, sort_order) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$packageId, $dayNumber, $imagePath, $altText, $daySortOrders[$dayNumber]]);
                }
            }
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
