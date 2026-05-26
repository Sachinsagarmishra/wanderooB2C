<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/db.php';

try {
    $destination = trim($_POST['destination'] ?? '');
    $departure_date = trim($_POST['departure_date'] ?? '');
    if (empty($departure_date)) {
        $departure_date = null;
    }
    $nights = trim($_POST['nights'] ?? '');
    $companion = trim($_POST['companion'] ?? '');
    $rooms_config = trim($_POST['rooms_config'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = '+91 ' . trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if (empty($fullname) || empty($email) || empty($_POST['phone']) || empty($destination)) {
        echo json_encode(['success' => false, 'error' => 'Required fields are missing.']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO leads (
        type, fullname, email, phone, destination, departure_date, nights, companion, rooms_config, notes
    ) VALUES (
        'enquiry', ?, ?, ?, ?, ?, ?, ?, ?, ?
    )");
    
    $stmt->execute([
        $fullname,
        $email,
        $phone,
        $destination,
        $departure_date,
        $nights,
        $companion,
        $rooms_config,
        $notes
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection or query error: ' . $e->getMessage()]);
}
exit;
?>
