<?php
/**
 * Joey AI — Lead Capture API Endpoint
 * Receives lead details from the chat widget and stores them in the ai_leads table.
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/../includes/db.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload.']);
    exit;
}

$clientName     = trim($input['client_name'] ?? '');
$workEmail      = trim($input['work_email'] ?? '');
$whatsappLine   = preg_replace('/\D/', '', trim($input['whatsapp_line'] ?? ''));
$capturedContext = trim($input['captured_context'] ?? '');

// Validation
$errors = [];
if (empty($clientName)) {
    $errors[] = 'Client name is required.';
}
if (empty($workEmail) || !filter_var($workEmail, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email address is required.';
}
if (empty($whatsappLine) || strlen($whatsappLine) < 10) {
    $errors[] = 'A valid WhatsApp/phone number is required.';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['error' => implode(' ', $errors)]);
    exit;
}

// Insert lead into database
try {
    $stmt = $pdo->prepare("INSERT INTO ai_leads (client_name, work_email, whatsapp_line, captured_context) VALUES (?, ?, ?, ?)");
    $stmt->execute([$clientName, $workEmail, $whatsappLine, $capturedContext]);

    // Optional: Trigger email notification if SMTP is configured
    $smtpEnabled = get_setting('smtp_enabled', '0');
    $leadEmailTo = get_setting('lead_email_to', '');

    if ($smtpEnabled === '1' && !empty($leadEmailTo)) {
        try {
            require_once __DIR__ . '/../includes/mailer.php';

            $emailSubject = "🦘 New Joey AI Lead: " . $clientName;
            $emailBody = "<h2>New Lead from Joey AI Chatbot</h2>";
            $emailBody .= "<table style='border-collapse:collapse;width:100%;'>";
            $emailBody .= "<tr><td style='padding:8px;border:1px solid #ddd;font-weight:bold;'>Name</td><td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($clientName) . "</td></tr>";
            $emailBody .= "<tr><td style='padding:8px;border:1px solid #ddd;font-weight:bold;'>Email</td><td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($workEmail) . "</td></tr>";
            $emailBody .= "<tr><td style='padding:8px;border:1px solid #ddd;font-weight:bold;'>WhatsApp</td><td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($whatsappLine) . "</td></tr>";
            $emailBody .= "<tr><td style='padding:8px;border:1px solid #ddd;font-weight:bold;'>Context</td><td style='padding:8px;border:1px solid #ddd;'>" . nl2br(htmlspecialchars($capturedContext)) . "</td></tr>";
            $emailBody .= "</table>";

            if (function_exists('sendLeadNotification')) {
                sendLeadNotification($emailSubject, $emailBody);
            }
        } catch (Exception $e) {
            // Non-blocking: email failure should not fail lead capture
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your details have been captured. A Wanderoo travel expert will reach out to you shortly.',
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save your details. Please try again.']);
}
