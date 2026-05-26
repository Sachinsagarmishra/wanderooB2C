<?php
/**
 * Wanderoo PHPMailer SMTP Integration Utility
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/phpmailer/Exception.php';
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';

/**
 * Configure and get a PHPMailer instance using current DB settings or passed credentials
 */
function get_mailer_instance($customSettings = null) {
    $mail = new PHPMailer(true);

    // Read SMTP settings
    $enabled = $customSettings ? ($customSettings['smtp_enabled'] ?? '0') : get_setting('smtp_enabled', '0');
    if ($enabled !== '1') {
        return null;
    }

    $host = $customSettings ? ($customSettings['smtp_host'] ?? '') : get_setting('smtp_host', '');
    $auth = $customSettings ? ($customSettings['smtp_auth'] ?? '0') : get_setting('smtp_auth', '0');
    $user = $customSettings ? ($customSettings['smtp_username'] ?? '') : get_setting('smtp_username', '');
    $pass = $customSettings ? ($customSettings['smtp_password'] ?? '') : get_setting('smtp_password', '');
    $secure = $customSettings ? ($customSettings['smtp_secure'] ?? '') : get_setting('smtp_secure', '');
    $port = intval($customSettings ? ($customSettings['smtp_port'] ?? '587') : get_setting('smtp_port', '587'));
    $fromEmail = $customSettings ? ($customSettings['smtp_from_email'] ?? '') : get_setting('smtp_from_email', '');
    $fromName = $customSettings ? ($customSettings['smtp_from_name'] ?? '') : get_setting('smtp_from_name', SITE_NAME . ' Alerts');

    // Setup server
    $mail->isSMTP();
    $mail->Host       = $host;
    $mail->SMTPAuth   = ($auth === '1');
    $mail->Username   = $user;
    $mail->Password   = $pass;
    $mail->Port       = $port;

    if ($secure === 'ssl') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } elseif ($secure === 'tls') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    } else {
        $mail->SMTPSecure = '';
        $mail->SMTPAutoTLS = false;
    }

    // Default Sender
    $mail->setFrom($fromEmail, $fromName);
    
    // Set charset
    $mail->CharSet = 'UTF-8';

    return $mail;
}

/**
 * Send a notification email for a new lead entry
 */
function send_lead_notification($leadData) {
    try {
        $mail = get_mailer_instance();
        if (!$mail) {
            return false; // SMTP is disabled
        }

        // Fetch recipients from Settings
        $toEmailsStr = get_setting('lead_email_to', '');
        $bccEmailsStr = get_setting('lead_email_bcc', '');

        $toEmails = array_filter(array_map('trim', explode(',', $toEmailsStr)));
        $bccEmails = array_filter(array_map('trim', explode(',', $bccEmailsStr)));

        if (empty($toEmails)) {
            // Fallback to sender email if TO is undefined
            $sender = get_setting('smtp_from_email', '');
            if (!empty($sender)) {
                $toEmails[] = $sender;
            } else {
                return false; // No recipients
            }
        }

        // Add Recipient Addresses
        foreach ($toEmails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress($email);
            }
        }

        // Add BCC Addresses
        foreach ($bccEmails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mail->addBCC($email);
            }
        }

        // Content layout
        $mail->isHTML(true);
        $typeLabel = $leadData['type'] === 'enquiry' ? 'Popup Multi-step Enquiry' : 'Contact Form Submission';
        $mail->Subject = 'New Lead Alert: [' . $typeLabel . '] from ' . $leadData['fullname'];

        // Build HTML Body Content
        $html = '<div style="font-family: \'Urbanist\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #f8fafc;">';
        $html .= '<h2 style="color: #1e3a8a; margin-top: 0; padding-bottom: 12px; border-bottom: 2px solid #eff6ff;">New Travel Enquiry Received</h2>';
        $html .= '<p style="color: #475569; font-size: 14px;">A new lead has been submitted on the <strong>' . SITE_NAME . '</strong> portal.</p>';

        $html .= '<table style="width: 100%; border-collapse: collapse; margin-top: 18px;">';
        
        // General Submitter Info
        $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; width: 160px; color: #334155; border-bottom: 1px solid #e2e8f0;">Full Name</td>';
        $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0;">' . htmlspecialchars($leadData['fullname']) . '</td></tr>';
        
        $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Email ID</td>';
        $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0;"><a href="mailto:' . htmlspecialchars($leadData['email']) . '" style="color: #3b82f6; text-decoration: none;">' . htmlspecialchars($leadData['email']) . '</a></td></tr>';

        $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Phone Number</td>';
        $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0;">' . htmlspecialchars($leadData['phone']) . '</td></tr>';

        $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Source Flow</td>';
        $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0;"><span style="background-color: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 700;">' . $typeLabel . '</span></td></tr>';

        if (!empty($leadData['source_page'])) {
            $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Source Page URL</td>';
            $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0;"><a href="' . htmlspecialchars($leadData['source_page']) . '" style="color: #3b82f6; text-decoration: underline;">' . htmlspecialchars($leadData['source_page']) . '</a></td></tr>';
        }

        // Enquiry-specific Info
        if ($leadData['type'] === 'enquiry') {
            $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Destination</td>';
            $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0; text-transform: capitalize;">' . htmlspecialchars($leadData['destination']) . '</td></tr>';

            $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Departure Date</td>';
            $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0;">' . ($leadData['departure_date'] ? htmlspecialchars($leadData['departure_date']) : 'Flexible') . '</td></tr>';

            $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Nights</td>';
            $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0;">' . htmlspecialchars($leadData['nights']) . '</td></tr>';

            $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Companion Type</td>';
            $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0;">' . htmlspecialchars($leadData['companion']) . '</td></tr>';

            if (!empty($leadData['rooms_config'])) {
                $roomsText = '';
                try {
                    $config = json_decode($leadData['rooms_config'], true);
                    if (is_array($config)) {
                        foreach ($config as $r) {
                            $childText = 'No children';
                            if (!empty($r['children'])) {
                                $childText = count($r['children']) . ' child(ren) (Ages: ' . implode(', ', $r['children']) . ')';
                            }
                            $roomsText .= '<div style="margin-bottom: 4px;"><strong>Room ' . htmlspecialchars($r['room']) . '</strong>: ' . htmlspecialchars($r['adults']) . ' Adult(s), ' . $childText . '</div>';
                        }
                    }
                } catch (\Exception $e) {
                    $roomsText = htmlspecialchars($leadData['rooms_config']);
                }

                $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Room Configurations</td>';
                $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0; font-size: 13px;">' . $roomsText . '</td></tr>';
            }

            if (!empty($leadData['notes'])) {
                $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Special Notes</td>';
                $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0; white-space: pre-wrap; font-size: 13px;">' . htmlspecialchars($leadData['notes']) . '</td></tr>';
            }
        } else {
            // Contact-specific Info
            if (!empty($leadData['subject'])) {
                $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Subject</td>';
                $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0;">' . htmlspecialchars($leadData['subject']) . '</td></tr>';
            }

            if (!empty($leadData['message'])) {
                $html .= '<tr><td style="padding: 8px 10px; font-weight: 700; color: #334155; border-bottom: 1px solid #e2e8f0;">Message</td>';
                $html .= '<td style="padding: 8px 10px; color: #0f172a; border-bottom: 1px solid #e2e8f0; white-space: pre-wrap; font-size: 13px;">' . htmlspecialchars($leadData['message']) . '</td></tr>';
            }
        }

        $html .= '</table>';
        
        $html .= '<div style="margin-top: 24px; padding-top: 15px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; text-align: center;">';
        $html .= 'This notification is automatically generated by ' . SITE_NAME . ' systems.';
        $html .= '</div>';
        $html .= '</div>';

        $mail->Body = $html;
        $mail->AltBody = strip_tags(str_replace(['<br />', '</div>', '</tr>'], "\n", $html));

        $mail->send();
        return true;
    } catch (\Exception $e) {
        // Log mail errors to save_debug.log
        error_log("[" . date('Y-m-d H:i:s') . "] Mailer Exception: " . $e->getMessage() . "\n", 3, __DIR__ . '/../uploads/save_debug.log');
        return false;
    }
}

/**
 * Send a manual test email with test credentials
 */
function send_test_email($toEmail, $customSettings) {
    try {
        $mail = get_mailer_instance($customSettings);
        if (!$mail) {
            return "SMTP is disabled in the settings provided.";
        }

        if (empty($toEmail) || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return "Please provide a valid test recipient email address.";
        }

        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Wanderoo Portal: SMTP Integration Test Email';
        
        $html = '<div style="font-family: Arial, sans-serif; max-width: 500px; padding: 25px; border: 1.5px solid #10b981; border-radius: 10px; background-color: #ecfdf5; color: #064e3b;">';
        $html .= '<h2 style="margin-top: 0; color: #065f46;">🎉 Test Successful!</h2>';
        $html .= '<p style="font-size: 14px;">Congratulations, your SMTP server settings are correctly configured on Wanderoo.</p>';
        $html .= '<p style="font-size: 12px; color: #047857; margin-bottom: 0;">Sent on: ' . date('M j, Y, g:i a') . '</p>';
        $html .= '</div>';

        $mail->Body = $html;
        $mail->AltBody = "SMTP Integration Test Successful!\nSent on: " . date('M j, Y, g:i a');

        $mail->send();
        return true;
    } catch (\Exception $e) {
        return "PHPMailer Error: " . $e->getMessage();
    }
}
