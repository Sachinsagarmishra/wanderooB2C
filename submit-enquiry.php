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
    $country_code = trim($_POST['country_code'] ?? '+91');
    $phone_raw = trim($_POST['phone'] ?? '');
    $phone_digits = preg_replace('/\D/', '', $phone_raw);
    $phone = $country_code . ' ' . $phone_raw;
    $email = trim($_POST['email'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $source_page = trim($_POST['source_page'] ?? '');
    if (empty($source_page) && !empty($_SERVER['HTTP_REFERER'])) {
        $source_page = $_SERVER['HTTP_REFERER'];
    }

    if (empty($fullname) || empty($email) || empty($phone_raw) || empty($destination)) {
        echo json_encode(['success' => false, 'error' => 'Required fields are missing.']);
        exit;
    }

    if ($country_code === '+91' && strlen($phone_digits) !== 10) {
        echo json_encode(['success' => false, 'error' => 'Indian phone number must be exactly 10 digits.']);
        exit;
    }

    if (strlen($phone_digits) < 7 || strlen($phone_digits) > 15) {
        echo json_encode(['success' => false, 'error' => 'Please enter a valid phone number (between 7 and 15 digits).']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO leads (
        type, fullname, email, phone, destination, departure_date, nights, companion, rooms_config, notes, source_page
    ) VALUES (
        'enquiry', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
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
        $notes,
        $source_page
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection or query error: ' . $e->getMessage()]);
}
exit;
?>
