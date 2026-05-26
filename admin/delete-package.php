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

$packageId = intval($_GET['id'] ?? 0);

if ($packageId <= 0) {
    header("Location: manage-packages.php?error=" . urlencode("Invalid package ID."));
    exit;
}

try {
    // Get package info for file cleanup
    $stmt = $pdo->prepare("SELECT hero_image FROM tour_packages WHERE id = ?");
    $stmt->execute([$packageId]);
    $pkg = $stmt->fetch();

    if (!$pkg) {
        header("Location: manage-packages.php?error=" . urlencode("Package not found."));
        exit;
    }

    // Get all gallery photos for cleanup
    $stmt = $pdo->prepare("SELECT image_path FROM package_photos WHERE package_id = ?");
    $stmt->execute([$packageId]);
    $photos = $stmt->fetchAll();

    // Delete files
    foreach ($photos as $photo) {
        $fullPath = __DIR__ . '/../' . $photo['image_path'];
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }

    // Delete hero image
    if (!empty($pkg['hero_image'])) {
        $heroFullPath = __DIR__ . '/../' . $pkg['hero_image'];
        if (file_exists($heroFullPath)) {
            @unlink($heroFullPath);
        }
    }

    // Delete upload directory
    $uploadDir = __DIR__ . '/../uploads/packages/' . $packageId . '/';
    if (is_dir($uploadDir)) {
        // Remove remaining files
        $files = glob($uploadDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
        @rmdir($uploadDir);
    }

    // Delete all related database records
    $pdo->prepare("DELETE FROM package_photos WHERE package_id = ?")->execute([$packageId]);
    $pdo->prepare("DELETE FROM package_tags WHERE package_id = ?")->execute([$packageId]);
    $pdo->prepare("DELETE FROM package_days WHERE package_id = ?")->execute([$packageId]);
    $pdo->prepare("DELETE FROM package_highlights WHERE package_id = ?")->execute([$packageId]);
    $pdo->prepare("DELETE FROM package_inclusions WHERE package_id = ?")->execute([$packageId]);
    $pdo->prepare("DELETE FROM tour_packages WHERE id = ?")->execute([$packageId]);

    header("Location: manage-packages.php?success=" . urlencode("Package deleted successfully."));
    exit;

} catch (PDOException $e) {
    header("Location: manage-packages.php?error=" . urlencode("Error deleting package: " . $e->getMessage()));
    exit;
}
?>
