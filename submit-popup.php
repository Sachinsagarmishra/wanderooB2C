<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/db.php';

try {
    // Verify CSRF Token
    if (!csrf_verify()) {
        echo json_encode(['success' => false, 'error' => 'Security validation failed (CSRF token mismatch).']);
        exit;
    }

    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $adults = intval($_POST['adults'] ?? 1);
    $kids = intval($_POST['kids'] ?? 0);
    $departure_date = trim($_POST['departure_date'] ?? '');
    if (empty($departure_date)) {
        $departure_date = null;
    }
    $nights = trim($_POST['nights'] ?? '');
    $departure_city = trim($_POST['departure_city'] ?? '');
    $flights_booked = trim($_POST['flights_booked'] ?? 'No');

    if (empty($fullname) || empty($email) || empty($phone) || empty($destination)) {
        echo json_encode(['success' => false, 'error' => 'Required fields are missing.']);
        exit;
    }

    // Format rooms config: 1 room with X adults and Y kids
    $children_array = [];
    if ($kids > 0) {
        for ($i = 0; $i < $kids; $i++) {
            $children_array[] = 0;
        }
    }

    $rooms_config_array = [
        [
            'room' => 1,
            'adults' => $adults,
            'children' => $children_array
        ]
    ];
    $rooms_config = json_encode($rooms_config_array);

    // Format notes column to store Departure City & Flight status
    $notes = "Departure City: " . $departure_city . "\nFlights Booked: " . $flights_booked;

    $source_page = $_POST['source_page'] ?? '';
    if (empty($source_page) && !empty($_SERVER['HTTP_REFERER'])) {
        $source_page = $_SERVER['HTTP_REFERER'];
    }

    // Insert into database leads table
    $stmt = $pdo->prepare("INSERT INTO leads (
        type, fullname, email, phone, destination, departure_date, nights, companion, rooms_config, notes, source_page
    ) VALUES (
        'enquiry', ?, ?, ?, ?, ?, ?, 'Family', ?, ?, ?
    )");
    
    $stmt->execute([
        $fullname,
        $email,
        $phone,
        $destination,
        $departure_date,
        $nights,
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
            'companion' => 'Family',
            'rooms_config' => $rooms_config,
            'notes' => $notes,
            'source_page' => $source_page
        ];
        require_once __DIR__ . '/includes/mailer.php';
        send_lead_notification($leadData);
    } catch (\Exception $ex) {
        // Log mail errors to save_debug.log (handled within send_lead_notification or fall through)
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
exit;
?>
