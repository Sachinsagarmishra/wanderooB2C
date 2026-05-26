<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

// Auth is already checked in includes/header.php

$logFile = __DIR__ . '/../uploads/save_debug.log';

// Handle Clear Log action
if (isset($_POST['clear_log'])) {
    if (file_exists($logFile)) {
        unlink($logFile);
    }
    header("Location: view-log.php?success=Log+cleared+successfully");
    exit;
}

$logContent = "No log file found. Try saving a package first.";
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
}
?>

<div style="max-width: 900px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Save Package Debug Log</h1>
        <div>
            <form method="POST" style="display: inline;">
                <button type="submit" name="clear_log" class="btn" style="background: var(--danger); color: #fff; padding: 8px 16px; font-size: 12px; cursor: pointer; border: none; border-radius: 4px;">Clear Log</button>
            </form>
            <a href="manage-packages.php" class="btn btn-secondary" style="margin-left: 10px; text-decoration: none; padding: 8px 16px; font-size: 12px; border-radius: 4px;">Back to Packages</a>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div style="background: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.2); padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <div style="background: var(--bg3); border: 1px solid var(--border); border-radius: 8px; padding: 20px; overflow: auto; max-height: 600px;">
        <pre style="font-family: 'Courier New', Courier, monospace; font-size: 12px; color: var(--fg); white-space: pre-wrap; word-break: break-all; margin: 0;"><?php echo htmlspecialchars($logContent); ?></pre>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
