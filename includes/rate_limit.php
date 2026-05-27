<?php
/**
 * Login Rate Limiter
 * Tracks failed admin login attempts per IP.
 * After RATE_LIMIT_MAX_ATTEMPTS failures within the window → block for RATE_LIMIT_BLOCK_MINUTES.
 */

define('RATE_LIMIT_MAX_ATTEMPTS',   5);   // Max failed attempts before block
define('RATE_LIMIT_BLOCK_MINUTES',  15);  // How long to block (minutes)
define('RATE_LIMIT_WINDOW_MINUTES', 15);  // Sliding window to count attempts (minutes)

// ─── Table Bootstrap ──────────────────────────────────────────────────────────

function rate_limit_ensure_table($pdo) {
    static $ensured = false;
    if ($ensured) return;
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `login_attempts` (
            `id`            int(11) NOT NULL AUTO_INCREMENT,
            `ip`            varchar(45) NOT NULL,
            `attempts`      int(11) NOT NULL DEFAULT 1,
            `last_attempt`  timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `blocked_until` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `ip` (`ip`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    } catch (PDOException $e) { /* silently fail */ }
    $ensured = true;
}

// ─── Public API ───────────────────────────────────────────────────────────────

/**
 * Check if IP is currently blocked or has prior failures.
 * Returns array: ['blocked' => bool, 'attempts' => int, 'remaining_seconds' => int]
 */
function rate_limit_check($pdo, $ip) {
    rate_limit_ensure_table($pdo);
    try {
        $stmt = $pdo->prepare("SELECT * FROM login_attempts WHERE ip = ?");
        $stmt->execute([$ip]);
        $row = $stmt->fetch();

        if (!$row) {
            return ['blocked' => false, 'attempts' => 0, 'remaining_seconds' => 0];
        }

        // Active block?
        if (!empty($row['blocked_until'])) {
            $blockedUntil = strtotime($row['blocked_until']);
            $now          = time();
            if ($now < $blockedUntil) {
                return [
                    'blocked'           => true,
                    'attempts'          => (int) $row['attempts'],
                    'remaining_seconds' => $blockedUntil - $now,
                ];
            }
            // Block expired — wipe it
            _rate_limit_delete($pdo, $ip);
            return ['blocked' => false, 'attempts' => 0, 'remaining_seconds' => 0];
        }

        // Outside the counting window?
        $windowSec = RATE_LIMIT_WINDOW_MINUTES * 60;
        if ((time() - strtotime($row['last_attempt'])) > $windowSec) {
            _rate_limit_delete($pdo, $ip);
            return ['blocked' => false, 'attempts' => 0, 'remaining_seconds' => 0];
        }

        return ['blocked' => false, 'attempts' => (int) $row['attempts'], 'remaining_seconds' => 0];

    } catch (PDOException $e) {
        return ['blocked' => false, 'attempts' => 0, 'remaining_seconds' => 0];
    }
}

/**
 * Record one failed login attempt for the given IP.
 * Automatically triggers a block when the threshold is hit.
 */
function rate_limit_record_failure($pdo, $ip) {
    rate_limit_ensure_table($pdo);
    try {
        $pdo->prepare("
            INSERT INTO login_attempts (ip, attempts, last_attempt)
            VALUES (?, 1, NOW())
            ON DUPLICATE KEY UPDATE
                attempts     = attempts + 1,
                last_attempt = NOW()
        ")->execute([$ip]);

        // Fetch updated count
        $stmt = $pdo->prepare("SELECT attempts FROM login_attempts WHERE ip = ?");
        $stmt->execute([$ip]);
        $row = $stmt->fetch();

        if ($row && (int) $row['attempts'] >= RATE_LIMIT_MAX_ATTEMPTS) {
            $blockedUntil = date('Y-m-d H:i:s', time() + (RATE_LIMIT_BLOCK_MINUTES * 60));
            $pdo->prepare("UPDATE login_attempts SET blocked_until = ? WHERE ip = ?")
                ->execute([$blockedUntil, $ip]);
        }
    } catch (PDOException $e) { /* silently fail */ }
}

/**
 * Clear all attempt records for an IP (call after a successful login).
 */
function rate_limit_clear($pdo, $ip) {
    try {
        _rate_limit_delete($pdo, $ip);
    } catch (PDOException $e) { /* silently fail */ }
}

/**
 * Convert remaining seconds into a human-readable string.
 */
function rate_limit_format_time($seconds) {
    $seconds = max(0, (int) $seconds);
    if ($seconds >= 60) {
        $mins = (int) ceil($seconds / 60);
        return $mins . ' minute' . ($mins > 1 ? 's' : '');
    }
    return $seconds . ' second' . ($seconds !== 1 ? 's' : '');
}

// ─── Internal helpers ─────────────────────────────────────────────────────────

function _rate_limit_delete($pdo, $ip) {
    $pdo->prepare("DELETE FROM login_attempts WHERE ip = ?")->execute([$ip]);
}
?>
