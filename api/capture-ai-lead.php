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
if (empty($whatsappLine) || strlen($whatsappLine) < 10 || strlen($whatsappLine) > 15) {
    $errors[] = 'A valid WhatsApp number is required (10 to 15 digits).';
} else {
    // Reject identical repeating digits (e.g. 0000000000)
    if (preg_match('/^(\d)\1+$/', $whatsappLine)) {
        $errors[] = 'Please provide a valid WhatsApp number (avoid repeating digits).';
    }
    // Reject sequential runs (e.g. 123456789, 9876543210)
    $seq = "01234567890123456789";
    $revSeq = "98765432109876543210";
    if (strpos($seq, $whatsappLine) !== false || strpos($revSeq, $whatsappLine) !== false || $whatsappLine === '123456789' || $whatsappLine === '12345678' || $whatsappLine === '1234567890') {
        $errors[] = 'Please provide a valid WhatsApp number (avoid sequential runs).';
    }
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

    // Send email notification via PHPMailer if SMTP is configured
    $smtpEnabled = get_setting('smtp_enabled', '0');
    $leadEmailTo = get_setting('lead_email_to', '');

    if ($smtpEnabled === '1' && !empty($leadEmailTo)) {
        try {
            require_once __DIR__ . '/../includes/mailer.php';
            $mail = get_mailer_instance();
            if ($mail) {
                // Add recipients
                $toEmails = array_filter(array_map('trim', explode(',', $leadEmailTo)));
                foreach ($toEmails as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $mail->addAddress($email);
                    }
                }

                // Detect destination from context
                $interestedPlace = "General Inquiry";
                try {
                    $destStmt = $pdo->query("SELECT name FROM destinations");
                    $destNames = $destStmt->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($destNames as $destName) {
                        if (stripos($capturedContext, $destName) !== false) {
                            $interestedPlace = ucfirst($destName);
                            break;
                        }
                    }
                } catch (Exception $e) {}

                $mail->isHTML(true);
                $mail->Subject = "New Lead from AI Conversation - " . $interestedPlace;

                $emailBody = "<h2>New Lead from Joey AI Chatbot</h2>";
                $emailBody .= "<p>A customer has completed the conversation and provided their details.</p>";
                $emailBody .= "<table style='border-collapse:collapse;width:100%;max-width:600px;font-family:sans-serif;'>";
                $emailBody .= "<tr><td style='padding:10px;border:1px solid #ddd;font-weight:bold;background:#f8fafc;width:180px;'>Name</td><td style='padding:10px;border:1px solid #ddd;'>" . htmlspecialchars($clientName) . "</td></tr>";
                $emailBody .= "<tr><td style='padding:10px;border:1px solid #ddd;font-weight:bold;background:#f8fafc;'>Email</td><td style='padding:10px;border:1px solid #ddd;'><a href='mailto:" . htmlspecialchars($workEmail) . "'>" . htmlspecialchars($workEmail) . "</a></td></tr>";
                $emailBody .= "<tr><td style='padding:10px;border:1px solid #ddd;font-weight:bold;background:#f8fafc;'>WhatsApp Number</td><td style='padding:10px;border:1px solid #ddd;'>" . htmlspecialchars($whatsappLine) . "</td></tr>";
                $emailBody .= "<tr><td style='padding:10px;border:1px solid #ddd;font-weight:bold;background:#f8fafc;'>Interested In</td><td style='padding:10px;border:1px solid #ddd;font-weight:bold;color:#1e3a8a;'>" . htmlspecialchars($interestedPlace) . "</td></tr>";
                $emailBody .= "<tr><td style='padding:10px;border:1px solid #ddd;font-weight:bold;background:#f8fafc;'>Chat Context</td><td style='padding:10px;border:1px solid #ddd;font-size:13px;color:#475569;'>" . nl2br(htmlspecialchars($capturedContext)) . "</td></tr>";
                $emailBody .= "</table>";

                $mail->Body = $emailBody;
                $mail->AltBody = strip_tags(str_replace("<br />", "\n", $emailBody));
                $mail->send();
            }
        } catch (Exception $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] Joey AI Mailer Error: " . $e->getMessage() . "\n", 3, __DIR__ . '/../uploads/save_debug.log');
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
