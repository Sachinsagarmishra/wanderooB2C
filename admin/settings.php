<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

// Auth is checked in includes/header.php

$successMsg = '';
$errorMsg = '';

// Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updates = [
        'contact_email' => trim($_POST['contact_email'] ?? ''),
        'contact_phone' => trim($_POST['contact_phone'] ?? ''),
        'contact_whatsapp' => preg_replace('/\D/', '', trim($_POST['contact_whatsapp'] ?? '')),
        'contact_address' => trim($_POST['contact_address'] ?? ''),
        'smtp_enabled' => isset($_POST['smtp_enabled']) ? '1' : '0',
        'smtp_host' => trim($_POST['smtp_host'] ?? ''),
        'smtp_port' => trim($_POST['smtp_port'] ?? '587'),
        'smtp_auth' => isset($_POST['smtp_auth']) ? '1' : '0',
        'smtp_username' => trim($_POST['smtp_username'] ?? ''),
        'smtp_password' => $_POST['smtp_password'] ?? '',
        'smtp_secure' => trim($_POST['smtp_secure'] ?? 'tls'),
        'smtp_from_email' => trim($_POST['smtp_from_email'] ?? ''),
        'smtp_from_name' => trim($_POST['smtp_from_name'] ?? ''),
        'lead_email_to' => trim($_POST['lead_email_to'] ?? ''),
        'lead_email_bcc' => trim($_POST['lead_email_bcc'] ?? '')
    ];

    if (empty($updates['contact_email']) || empty($updates['contact_phone']) || empty($updates['contact_whatsapp']) || empty($updates['contact_address'])) {
        $errorMsg = "All global contact fields are required!";
    } elseif ($updates['smtp_enabled'] === '1' && (empty($updates['smtp_host']) || empty($updates['smtp_port']) || empty($updates['smtp_from_email']))) {
        $errorMsg = "SMTP Host, Port, and From Email are required when SMTP is enabled!";
    } elseif ($updates['smtp_enabled'] === '1' && $updates['smtp_auth'] === '1' && (empty($updates['smtp_username']) || empty($updates['smtp_password']))) {
        $errorMsg = "SMTP Username and Password are required when SMTP Authentication is enabled!";
    } else {
        try {
            $pdo->beginTransaction();

            foreach ($updates as $key => $val) {
                $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$key, $val, $val]);
            }

            $pdo->commit();
            $successMsg = "Settings updated successfully!";
            
            // Reload global settings cache for this request
            global $site_settings;
            foreach ($updates as $key => $val) {
                $site_settings[$key] = $val;
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch current values
$currentEmail = get_setting('contact_email', 'support@wanderoo.world');
$currentPhone = get_setting('contact_phone', '+91 91 135 154 62');
$currentWhatsapp = get_setting('contact_whatsapp', '919113515462');
$currentAddress = get_setting('contact_address', '');

$smtpEnabled = get_setting('smtp_enabled', '0');
$smtpHost = get_setting('smtp_host', '');
$smtpPort = get_setting('smtp_port', '587');
$smtpAuth = get_setting('smtp_auth', '0');
$smtpUsername = get_setting('smtp_username', '');
$smtpPassword = get_setting('smtp_password', '');
$smtpSecure = get_setting('smtp_secure', 'tls');
$smtpFromEmail = get_setting('smtp_from_email', '');
$smtpFromName = get_setting('smtp_from_name', SITE_NAME . ' Alerts');
$leadEmailTo = get_setting('lead_email_to', '');
$leadEmailBcc = get_setting('lead_email_bcc', '');
?>

<style>
    .settings-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 24px;
        align-items: start;
        margin-top: 20px;
    }
    @media (max-width: 992px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }
    .settings-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius-main);
        padding: 24px;
        box-shadow: var(--shadow-card);
    }
    .settings-card h2 {
        font-size: 16px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
        color: var(--fg);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 18px;
    }
    .form-group-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    @media (max-width: 576px) {
        .form-group-row {
            grid-template-columns: 1fr;
        }
    }
    .form-group label {
        font-weight: 600;
        color: var(--fg2);
        font-size: 12px;
    }
    .form-control {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: var(--radius-int);
        padding: 10px 14px;
        color: var(--fg);
        outline: none;
        font-family: inherit;
        font-size: 13px;
        transition: border-color 0.2s;
        width: 100%;
    }
    .form-control:focus {
        border-color: var(--accent);
    }
    .form-control:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    textarea.form-control {
        min-height: 90px;
        resize: vertical;
    }
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        padding: 10px;
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: var(--radius-int);
    }
    .checkbox-group input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--accent);
        cursor: pointer;
    }
    .checkbox-group label {
        font-weight: 600;
        font-size: 12px;
        color: var(--fg);
        cursor: pointer;
        user-select: none;
    }
    .alert {
        padding: 12px 16px;
        border-radius: var(--radius-int);
        margin-bottom: 24px;
        font-weight: 500;
        font-size: 13px;
    }
    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        color: var(--success);
        border: 1px solid rgba(34, 197, 94, 0.2);
    }
    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    
    /* SMTP Test Card Style */
    .test-smtp-box {
        margin-top: 24px;
        background: rgba(245, 197, 24, 0.03);
        border: 1px dashed var(--accent);
    }
    .test-result {
        margin-top: 12px;
        padding: 12px;
        border-radius: var(--radius-int);
        font-family: monospace;
        font-size: 12px;
        white-space: pre-wrap;
        display: none;
    }
    .test-result.success {
        background: rgba(34, 197, 94, 0.1);
        color: var(--success);
        border: 1px solid rgba(34, 197, 94, 0.2);
    }
    .test-result.error {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
</style>

<div class="settings-container">
    <h1>Global Site Settings</h1>
    
    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
    <?php endif; ?>
    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php endif; ?>

    <form action="" method="POST" id="settingsForm">
        <div class="settings-grid">
            
            <!-- Left Column: Contact settings -->
            <div class="settings-card">
                <h2>📞 Contact & Profile Info</h2>
                
                <div class="form-group">
                    <label for="contact_email">Display Email Address *</label>
                    <input type="email" id="contact_email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($currentEmail); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="contact_phone">Phone Number (Display & Calls) *</label>
                    <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="<?php echo htmlspecialchars($currentPhone); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="contact_whatsapp">WhatsApp Number (Digits only, with country code) *</label>
                    <input type="text" id="contact_whatsapp" name="contact_whatsapp" class="form-control" value="<?php echo htmlspecialchars($currentWhatsapp); ?>" required placeholder="e.g. 919113515462">
                    <div style="font-size: 11px; color: var(--fg3); margin-top: 4px;">Do not include '+', spaces, or hyphens (e.g. 919113515462).</div>
                </div>
                
                <div class="form-group">
                    <label for="contact_address">Office Address *</label>
                    <textarea id="contact_address" name="contact_address" class="form-control" required><?php echo htmlspecialchars($currentAddress); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 13px; margin-top: 10px; width: 100%;">Save All Settings</button>
            </div>
            
            <!-- Right Column: SMTP settings -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div class="settings-card">
                    <h2>✉️ SMTP Mail Server Settings</h2>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="smtp_enabled" name="smtp_enabled" value="1" <?php echo $smtpEnabled === '1' ? 'checked' : ''; ?> onchange="toggleSmtpFields()">
                        <label for="smtp_enabled">Enable SMTP Email Notifications</label>
                    </div>
                    
                    <div class="smtp-fields">
                        <div class="form-group-row">
                            <div class="form-group">
                                <label for="smtp_host">SMTP Host Address</label>
                                <input type="text" id="smtp_host" name="smtp_host" class="form-control" value="<?php echo htmlspecialchars($smtpHost); ?>" placeholder="e.g. smtp.gmail.com">
                            </div>
                            
                            <div class="form-group">
                                <label for="smtp_port">SMTP Port</label>
                                <input type="text" id="smtp_port" name="smtp_port" class="form-control" value="<?php echo htmlspecialchars($smtpPort); ?>" placeholder="e.g. 587">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="smtp_secure">Secure Encryption Protocol</label>
                            <select id="smtp_secure" name="smtp_secure" class="form-control">
                                <option value="none" <?php echo $smtpSecure === 'none' ? 'selected' : ''; ?>>None (Plain / Unencrypted)</option>
                                <option value="tls" <?php echo $smtpSecure === 'tls' ? 'selected' : ''; ?>>TLS / STARTTLS (Recommended for Port 587)</option>
                                <option value="ssl" <?php echo $smtpSecure === 'ssl' ? 'selected' : ''; ?>>SSL / SMTPS (Recommended for Port 465)</option>
                            </select>
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="smtp_auth" name="smtp_auth" value="1" <?php echo $smtpAuth === '1' ? 'checked' : ''; ?> onchange="toggleSmtpAuthFields()">
                            <label for="smtp_auth">SMTP Server Requires Authentication (Login)</label>
                        </div>
                        
                        <div class="smtp-auth-fields">
                            <div class="form-group-row">
                                <div class="form-group">
                                    <label for="smtp_username">SMTP Username / Email</label>
                                    <input type="text" id="smtp_username" name="smtp_username" class="form-control" value="<?php echo htmlspecialchars($smtpUsername); ?>" placeholder="username@example.com">
                                </div>
                                
                                <div class="form-group">
                                    <label for="smtp_password">SMTP Password</label>
                                    <input type="password" id="smtp_password" name="smtp_password" class="form-control" value="<?php echo htmlspecialchars($smtpPassword); ?>" placeholder="Enter password">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group-row">
                            <div class="form-group">
                                <label for="smtp_from_email">Sender From Email</label>
                                <input type="email" id="smtp_from_email" name="smtp_from_email" class="form-control" value="<?php echo htmlspecialchars($smtpFromEmail); ?>" placeholder="alerts@wanderoo.world">
                            </div>
                            
                            <div class="form-group">
                                <label for="smtp_from_name">Sender Display Name</label>
                                <input type="text" id="smtp_from_name" name="smtp_from_name" class="form-control" value="<?php echo htmlspecialchars($smtpFromName); ?>" placeholder="Wanderoo Portal">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="lead_email_to">Lead Alert TO Recipients (Comma-separated)</label>
                            <input type="text" id="lead_email_to" name="lead_email_to" class="form-control" value="<?php echo htmlspecialchars($leadEmailTo); ?>" placeholder="admin@example.com, bookings@example.com">
                            <div style="font-size: 11px; color: var(--fg3); margin-top: 4px;">Send notifications to multiple recipients. Comma-separated.</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="lead_email_bcc">Lead Alert BCC Recipients (Comma-separated)</label>
                            <input type="text" id="lead_email_bcc" name="lead_email_bcc" class="form-control" value="<?php echo htmlspecialchars($leadEmailBcc); ?>" placeholder="bcc-archives@example.com">
                            <div style="font-size: 11px; color: var(--fg3); margin-top: 4px;">Blind Carbon Copy recipients. Comma-separated.</div>
                        </div>
                    </div>
                </div>
                
                <!-- SMTP Testing Panel -->
                <div class="settings-card test-smtp-box" id="testSmtpSection">
                    <h2>⚡ Test SMTP Email Delivery</h2>
                    <p style="font-size:12px; color:var(--fg2); margin-bottom: 12px;">Verify SMTP settings by sending a live test email. You can test settings entered above <strong>without saving them first</strong>.</p>
                    
                    <div class="form-group">
                        <label for="test_email_recipient">Recipient Test Email Address</label>
                        <div style="display:flex; gap:10px;">
                            <input type="email" id="test_email_recipient" class="form-control" placeholder="test-recipient@example.com" style="flex:1;">
                            <button type="button" class="btn" style="background:var(--accent); color:#000;" onclick="executeSmtpTest()">Send Test Email</button>
                        </div>
                    </div>
                    
                    <div class="test-result" id="testResultBox"></div>
                </div>
            </div>
            
        </div>
    </form>
</div>

<script>
function toggleSmtpFields() {
    const isEnabled = document.getElementById('smtp_enabled').checked;
    const fieldsContainer = document.querySelector('.smtp-fields');
    const testSmtpSection = document.getElementById('testSmtpSection');
    
    const inputs = fieldsContainer.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        // Only keep auth fields disabled if auth itself is unchecked
        if (input.closest('.smtp-auth-fields') && !document.getElementById('smtp_auth').checked) {
            input.disabled = true;
        } else {
            input.disabled = !isEnabled;
        }
    });
    
    if (isEnabled) {
        testSmtpSection.style.opacity = '1';
        testSmtpSection.style.pointerEvents = 'auto';
    } else {
        testSmtpSection.style.opacity = '0.5';
        testSmtpSection.style.pointerEvents = 'none';
    }
}

function toggleSmtpAuthFields() {
    const isEnabled = document.getElementById('smtp_enabled').checked;
    const isAuthEnabled = document.getElementById('smtp_auth').checked;
    const authFields = document.querySelector('.smtp-auth-fields').querySelectorAll('input');
    
    authFields.forEach(input => {
        input.disabled = !(isEnabled && isAuthEnabled);
    });
}

function executeSmtpTest() {
    const recipient = document.getElementById('test_email_recipient').value.trim();
    const resultBox = document.getElementById('testResultBox');
    
    if (!recipient) {
        alert('Please specify a recipient email address.');
        return;
    }
    
    resultBox.style.display = 'block';
    resultBox.className = 'test-result';
    resultBox.innerHTML = 'Connecting to SMTP server and sending test email... Please wait.';
    
    // Gather current values from the form inputs
    const formData = new FormData();
    formData.append('test_email', recipient);
    formData.append('smtp_enabled', document.getElementById('smtp_enabled').checked ? '1' : '0');
    formData.append('smtp_host', document.getElementById('smtp_host').value);
    formData.append('smtp_port', document.getElementById('smtp_port').value);
    formData.append('smtp_secure', document.getElementById('smtp_secure').value);
    formData.append('smtp_auth', document.getElementById('smtp_auth').checked ? '1' : '0');
    formData.append('smtp_username', document.getElementById('smtp_username').value);
    formData.append('smtp_password', document.getElementById('smtp_password').value);
    formData.append('smtp_from_email', document.getElementById('smtp_from_email').value);
    formData.append('smtp_from_name', document.getElementById('smtp_from_name').value);
    
    fetch('test-smtp.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultBox.className = 'test-result success';
            resultBox.innerHTML = '<strong>Success!</strong> Test email sent successfully to ' + recipient + '.';
        } else {
            resultBox.className = 'test-result error';
            resultBox.innerHTML = '<strong>Error!</strong> Test email failed.<br><br>' + data.message;
        }
    })
    .catch(error => {
        resultBox.className = 'test-result error';
        resultBox.innerHTML = '<strong>Request Error!</strong> Communication failed.<br><br>' + error;
    });
}

// Run on page load to initialize state
document.addEventListener('DOMContentLoaded', () => {
    toggleSmtpFields();
});
</script>

<?php include_once 'includes/footer.php'; ?>
