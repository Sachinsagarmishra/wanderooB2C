<?php
require_once __DIR__ . '/../includes/db.php';

$message = '';
$messageType = '';

// Check and create tables if not exist
try {
    // 1. Users Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `username` varchar(50) NOT NULL,
      `password` varchar(255) NOT NULL,
      `email` varchar(100) NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      UNIQUE KEY `username` (`username`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Force alter column width for existing tables to prevent password truncating issues
    $pdo->exec("ALTER TABLE `users` MODIFY COLUMN `password` varchar(255) NOT NULL;");
    $pdo->exec("ALTER TABLE `users` MODIFY COLUMN `email` varchar(100) NOT NULL;");

    // 2. Leads Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `leads` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `type` enum('contact', 'enquiry') NOT NULL DEFAULT 'contact',
      `fullname` varchar(100) NOT NULL,
      `email` varchar(100) NOT NULL,
      `phone` varchar(50) NOT NULL,
      `subject` varchar(255) DEFAULT NULL,
      `message` text DEFAULT NULL,
      `destination` varchar(100) DEFAULT NULL,
      `departure_date` date DEFAULT NULL,
      `nights` varchar(50) DEFAULT NULL,
      `companion` varchar(50) DEFAULT NULL,
      `rooms_config` text DEFAULT NULL,
      `notes` text DEFAULT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
} catch (PDOException $e) {
    $message = "Database initialization failed: " . $e->getMessage();
    $messageType = "danger";
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $message = "All fields are required!";
        $messageType = "danger";
    } else {
        try {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            if ($stmt->fetchColumn() > 0) {
                // Force overwrite existing admin account details
                $stmt = $pdo->prepare("UPDATE users SET password = ?, email = ? WHERE username = ?");
                $stmt->execute([$hashed_password, $email, $username]);
                $message = "Admin user password has been force overwritten successfully! You can now log in.";
                $messageType = "success";
            } else {
                // Insert new admin
                $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
                $stmt->execute([$username, $hashed_password, $email]);
                $message = "Admin user successfully created! You can now log in.";
                $messageType = "success";
            }
        } catch (PDOException $e) {
            $message = "Error handling admin registration: " . $e->getMessage();
            $messageType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin Account | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_PATH; ?>/admin/assets/css/admin-style.css">
    <style>
        .auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: var(--bg);
            padding: 20px;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius-main);
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .auth-logo {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 8px;
        }
        .auth-subtitle {
            text-align: center;
            color: var(--fg2);
            font-size: 13px;
            margin-bottom: 24px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 600;
            color: var(--fg2);
        }
        .form-control {
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: var(--radius-int);
            padding: 12px 16px;
            color: var(--fg);
            outline: none;
            font-family: inherit;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: var(--accent);
        }
        .btn-block {
            width: 100%;
            margin-top: 10px;
        }
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-int);
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 13px;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        .auth-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: var(--fg3);
        }
        .auth-footer a {
            color: var(--accent);
            font-weight: 500;
        }
        .auth-footer a:hover {
            color: var(--accent2);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">Wanderoo</div>
            <div class="auth-subtitle">Create Administrator Account</div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Choose a username" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="admin@example.com" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register Admin</button>
            </form>

            <div class="auth-footer">
                Already have an admin account? <a href="index.php">Log In</a>
            </div>
        </div>
    </div>
</body>
</html>
