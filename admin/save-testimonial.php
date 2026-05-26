<?php
require_once __DIR__ . '/../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: manage-testimonials.php");
    exit;
}

try {
    $testimonialId = intval($_POST['testimonial_id'] ?? 0);
    $isEdit = $testimonialId > 0;

    $customerName = trim($_POST['customer_name'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $rating = max(1, min(5, intval($_POST['rating'] ?? 5)));
    $sortOrder = intval($_POST['sort_order'] ?? 0);
    $status = ($_POST['status'] ?? 'active') === 'draft' ? 'draft' : 'active';
    $imageAlt = trim($_POST['image_alt'] ?? '');
    $selectedImage = trim($_POST['selected_image'] ?? '');

    if ($customerName === '' || $content === '') {
        header("Location: manage-testimonials.php?error=" . urlencode("Customer name and review content are required."));
        exit;
    }

    $imagePath = '';
    if ($isEdit) {
        $stmt = $pdo->prepare("SELECT image_path FROM testimonials WHERE id = ?");
        $stmt->execute([$testimonialId]);
        $imagePath = $stmt->fetchColumn() ?: '';
    }

    if ($selectedImage !== '') {
        $imagePath = $selectedImage;
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($ext, $allowed, true)) {
            $uploadDir = __DIR__ . '/../uploads/testimonials/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = 'testimonial_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename)) {
                $imagePath = 'uploads/testimonials/' . $filename;
            }
        }
    }

    if ($isEdit) {
        $stmt = $pdo->prepare("UPDATE testimonials SET customer_name = ?, image_path = ?, image_alt = ?, content = ?, rating = ?, sort_order = ?, status = ? WHERE id = ?");
        $stmt->execute([$customerName, $imagePath, $imageAlt, $content, $rating, $sortOrder, $status, $testimonialId]);
        $message = "Testimonial successfully updated!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO testimonials (customer_name, image_path, image_alt, content, rating, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$customerName, $imagePath, $imageAlt, $content, $rating, $sortOrder, $status]);
        $message = "Testimonial successfully created!";
    }

    header("Location: manage-testimonials.php?success=" . urlencode($message));
    exit;
} catch (PDOException $e) {
    header("Location: manage-testimonials.php?error=" . urlencode("Database error: " . $e->getMessage()));
    exit;
}
