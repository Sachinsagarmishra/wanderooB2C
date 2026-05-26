<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

// Auth is checked in includes/header.php

$successMsg = '';
$errorMsg = '';

// Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['contact_email'] ?? '');
    $phone = trim($_POST['contact_phone'] ?? '');
    $whatsapp = trim($_POST['contact_whatsapp'] ?? '');
    $address = trim($_POST['contact_address'] ?? '');

    if (empty($email) || empty($phone) || empty($whatsapp) || empty($address)) {
        $errorMsg = "All settings fields are required!";
    } else {
        try {
            $pdo->beginTransaction();

            $updates = [
                'contact_email' => $email,
                'contact_phone' => $phone,
                'contact_whatsapp' => preg_replace('/\D/', '', $whatsapp), // digits only
                'contact_address' => $address
            ];

            foreach ($updates as $key => $val) {
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$val, $key]);
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
?>

<style>
    .settings-container {
        max-width: 700px;
    }
    .settings-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius-main);
        padding: 32px;
        box-shadow: var(--shadow-card);
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 20px;
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
        padding: 12px 16px;
        color: var(--fg);
        outline: none;
        font-family: inherit;
        font-size: 13px;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: var(--accent);
    }
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
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
</style>

<div class="settings-container">
    <h1>Global Contact Settings</h1>
    
    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
    <?php endif; ?>
    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php endif; ?>

    <div class="settings-card">
        <form action="" method="POST">
            <div class="form-group">
                <label for="contact_email">Email Address *</label>
                <input type="email" id="contact_email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($currentEmail); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="contact_phone">Phone Number (Display & Calls) *</label>
                <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="<?php echo htmlspecialchars($currentPhone); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="contact_whatsapp">WhatsApp Number (Digits only, with country code) *</label>
                <input type="text" id="contact_whatsapp" name="contact_whatsapp" class="form-control" value="<?php echo htmlspecialchars($currentWhatsapp); ?>" required placeholder="e.g. 919113515462">
                <div style="font-size: 11px; color: var(--fg3); margin-top: 4px;">Do not include '+', spaces, or hyphens (e.g., use 919113515462 instead of +91 91 135 154 62).</div>
            </div>
            
            <div class="form-group">
                <label for="contact_address">Office Address *</label>
                <textarea id="contact_address" name="contact_address" class="form-control" required><?php echo htmlspecialchars($currentAddress); ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 13px; margin-top: 10px;">Save Settings</button>
        </form>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
