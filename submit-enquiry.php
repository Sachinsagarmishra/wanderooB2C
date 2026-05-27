<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/turnstile.php';

try {
    // Verify CSRF Token
    if (!csrf_verify()) {
        echo json_encode(['success' => false, 'error' => 'Security validation failed (CSRF token mismatch).']);
        exit;
    }

    // Verify Turnstile CAPTCHA first
    $turnstileToken = $_POST['cf-turnstile-response'] ?? '';
    if (!verify_turnstile($turnstileToken, $_SERVER['REMOTE_ADDR'] ?? '')) {
        echo json_encode(['success' => false, 'error' => 'CAPTCHA verification failed. Please try again.']);
        exit;
    }

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

    // Send email notification (fails silently if SMTP disabled or fails)
    try {
        $leadData = [
            'type' => 'enquiry',
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'destination' => $destination,
            'departure_date' => $departure_date,
            'nights' => $nights,
            'companion' => $companion,
            'rooms_config' => $rooms_config,
            'notes' => $notes,
            'source_page' => $source_page
        ];
        require_once __DIR__ . '/includes/mailer.php';
        send_lead_notification($leadData);
    } catch (\Exception $ex) {
        // Fallback catch, error_log handled within send_lead_notification
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection or query error: ' . $e->getMessage()]);
}
exit;
?>
