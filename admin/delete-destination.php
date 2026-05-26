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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: manage-destinations.php?error=" . urlencode("Invalid destination ID."));
    exit;
}

try {
    // 1. Get destination details to delete files
    $stmt = $pdo->prepare("SELECT hero_bg, dropdown_icon FROM destinations WHERE id = ?");
    $stmt->execute([$id]);
    $dest = $stmt->fetch();

    if ($dest) {
        // Delete hero bg banner file if local
        if (!empty($dest['hero_bg']) && strpos($dest['hero_bg'], 'uploads/destinations/') === 0) {
            $heroFile = __DIR__ . '/../' . $dest['hero_bg'];
            if (file_exists($heroFile)) {
                @unlink($heroFile);
            }
        }

        // Delete icon file if local
        if (!empty($dest['dropdown_icon']) && strpos($dest['dropdown_icon'], 'uploads/destinations/') === 0) {
            $iconFile = __DIR__ . '/../' . $dest['dropdown_icon'];
            if (file_exists($iconFile)) {
                @unlink($iconFile);
            }
        }

        // 2. Delete destination row from database
        $stmtDelete = $pdo->prepare("DELETE FROM destinations WHERE id = ?");
        $stmtDelete->execute([$id]);

        $msg = "Destination deleted successfully!";
        header("Location: manage-destinations.php?success=" . urlencode($msg));
        exit;
    } else {
        header("Location: manage-destinations.php?error=" . urlencode("Destination not found."));
        exit;
    }

} catch (PDOException $e) {
    header("Location: manage-destinations.php?error=" . urlencode("Database error: " . $e->getMessage()));
    exit;
}
