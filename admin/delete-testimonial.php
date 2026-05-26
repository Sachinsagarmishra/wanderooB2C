<?php
require_once __DIR__ . '/../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$testimonialId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($testimonialId <= 0) {
    header("Location: manage-testimonials.php?error=" . urlencode("Invalid testimonial ID."));
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->execute([$testimonialId]);
    header("Location: manage-testimonials.php?success=" . urlencode("Testimonial deleted successfully."));
    exit;
} catch (PDOException $e) {
    header("Location: manage-testimonials.php?error=" . urlencode("Database error: " . $e->getMessage()));
    exit;
}
