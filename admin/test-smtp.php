<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enforce admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please log in.']);
    exit;
}

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $toEmail = trim($_POST['test_email'] ?? '');
    
    $customSettings = [
        'smtp_enabled' => trim($_POST['smtp_enabled'] ?? '0'),
        'smtp_host' => trim($_POST['smtp_host'] ?? ''),
        'smtp_port' => trim($_POST['smtp_port'] ?? '587'),
        'smtp_auth' => trim($_POST['smtp_auth'] ?? '0'),
        'smtp_username' => trim($_POST['smtp_username'] ?? ''),
        'smtp_password' => $_POST['smtp_password'] ?? '',
        'smtp_secure' => trim($_POST['smtp_secure'] ?? 'tls'),
        'smtp_from_email' => trim($_POST['smtp_from_email'] ?? ''),
        'smtp_from_name' => trim($_POST['smtp_from_name'] ?? '')
    ];
    
    if (empty($toEmail) || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid recipient email address.']);
        exit;
    }
    
    $res = send_test_email($toEmail, $customSettings);
    if ($res === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $res]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request.']);
exit;
?>
