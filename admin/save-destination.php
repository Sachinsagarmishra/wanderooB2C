<?php
require_once __DIR__ . '/../includes/db.php';

// Enforce admin authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: manage-destinations.php");
    exit;
}

try {
    foreach ([
        "ALTER TABLE `destinations` ADD COLUMN `meta_title` varchar(255) DEFAULT NULL AFTER `title`",
        "ALTER TABLE `destinations` ADD COLUMN `meta_description` text DEFAULT NULL AFTER `meta_title`",
        "ALTER TABLE `destinations` ADD COLUMN `focus_keywords` text DEFAULT NULL AFTER `meta_description`"
    ] as $alterSql) {
        try {
            $pdo->exec($alterSql);
        } catch (PDOException $e) {
            // Column already exists on updated installations.
        }
    }

    $destinationId = intval($_POST['destination_id'] ?? 0);
    $isEdit = $destinationId > 0;

    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $metaTitle = trim($_POST['meta_title'] ?? '');
    $metaDescription = trim($_POST['meta_description'] ?? '');
    $focusKeywords = trim($_POST['focus_keywords'] ?? '');
    $breadcrumb = trim($_POST['breadcrumb'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if (empty($name) || empty($slug) || empty($title) || empty($breadcrumb)) {
        header("Location: manage-destinations.php?error=" . urlencode("Name, Slug, Title, and Breadcrumb are required."));
        exit;
    }

    // Clean slug
    $slug = slugify($slug);

    // Check slug uniqueness
    $stmtCheck = $pdo->prepare("SELECT id FROM destinations WHERE slug = ? AND id != ?");
    $stmtCheck->execute([$slug, $destinationId]);
    if ($stmtCheck->fetch()) {
        header("Location: manage-destinations.php?error=" . urlencode("The slug '{$slug}' is already in use by another destination. Slugs must be unique."));
        exit;
    }

    // Ensure uploads directory exists
    $uploadDir = __DIR__ . '/../uploads/destinations/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // ─── Process Hero BG Banner Upload ───
    $heroBgPath = '';
    if ($isEdit) {
        $stmt = $pdo->prepare("SELECT hero_bg FROM destinations WHERE id = ?");
        $stmt->execute([$destinationId]);
        $heroBgPath = $stmt->fetchColumn() ?: '';
    }

    if (isset($_FILES['hero_bg']) && $_FILES['hero_bg']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['hero_bg']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($ext, $allowed)) {
            $filename = 'bg_' . $slug . '_' . time() . '.' . $ext;
            $fullPath = $uploadDir . $filename;
            
            // Delete old local file if any
            if (!empty($heroBgPath) && strpos($heroBgPath, 'http://') !== 0 && strpos($heroBgPath, 'https://') !== 0) {
                $oldFile = __DIR__ . '/../' . $heroBgPath;
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            if (move_uploaded_file($_FILES['hero_bg']['tmp_name'], $fullPath)) {
                $heroBgPath = 'uploads/destinations/' . $filename;
            }
        }
    }

    // ─── Process Dropdown Icon Upload ───
    $iconPath = '';
    if ($isEdit) {
        $stmt = $pdo->prepare("SELECT dropdown_icon FROM destinations WHERE id = ?");
        $stmt->execute([$destinationId]);
        $iconPath = $stmt->fetchColumn() ?: '';
    }

    if (isset($_FILES['dropdown_icon']) && $_FILES['dropdown_icon']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['dropdown_icon']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
        if (in_array($ext, $allowed)) {
            $filename = 'icon_' . $slug . '_' . time() . '.' . $ext;
            $fullPath = $uploadDir . $filename;
            
            // Delete old local icon if any
            if (!empty($iconPath) && strpos($iconPath, 'http://') !== 0 && strpos($iconPath, 'https://') !== 0) {
                $oldFile = __DIR__ . '/../' . $iconPath;
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            if (move_uploaded_file($_FILES['dropdown_icon']['tmp_name'], $fullPath)) {
                $iconPath = 'uploads/destinations/' . $filename;
            }
        }
    }

    // ─── Insert or Update in Database ───
    if ($isEdit) {
        $stmt = $pdo->prepare("UPDATE destinations SET 
            slug = ?, name = ?, title = ?, meta_title = ?, meta_description = ?, focus_keywords = ?, breadcrumb = ?, hero_bg = ?, dropdown_icon = ?, description = ?, sort_order = ? 
            WHERE id = ?");
        $stmt->execute([
            $slug, $name, $title, $metaTitle, $metaDescription, $focusKeywords, $breadcrumb, $heroBgPath, $iconPath, $description, $sort_order, $destinationId
        ]);
        $msg = "Destination successfully updated!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO destinations 
            (slug, name, title, meta_title, meta_description, focus_keywords, breadcrumb, hero_bg, dropdown_icon, description, sort_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $slug, $name, $title, $metaTitle, $metaDescription, $focusKeywords, $breadcrumb, $heroBgPath, $iconPath, $description, $sort_order
        ]);
        $msg = "Destination successfully created!";
    }

    header("Location: manage-destinations.php?success=" . urlencode($msg));
    exit;

} catch (Exception $e) {
    header("Location: manage-destinations.php?error=" . urlencode("An error occurred: " . $e->getMessage()));
    exit;
}
