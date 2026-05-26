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

    // 3. Tour Packages Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `tour_packages` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `destination` varchar(50) NOT NULL,
      `title` varchar(255) NOT NULL,
      `slug` varchar(255) NOT NULL,
      `description` text DEFAULT NULL,
      `overview` text DEFAULT NULL,
      `duration` varchar(50) DEFAULT NULL,
      `old_price` varchar(50) DEFAULT NULL,
      `price` varchar(50) DEFAULT NULL,
      `save_text` varchar(50) DEFAULT NULL,
      `rating` decimal(2,1) DEFAULT 4.5,
      `rating_count` int(11) DEFAULT 0,
      `hero_image` varchar(500) DEFAULT NULL,
      `status` enum('active','draft') NOT NULL DEFAULT 'active',
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`),
      UNIQUE KEY `slug` (`slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 4. Package Photos Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `package_photos` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `package_id` int(11) NOT NULL,
      `image_path` varchar(500) NOT NULL,
      `alt_text` varchar(255) DEFAULT NULL,
      `sort_order` int(11) DEFAULT 0,
      PRIMARY KEY (`id`),
      KEY `package_id` (`package_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 5. Package Tags Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `package_tags` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `package_id` int(11) NOT NULL,
      `tag_name` varchar(100) NOT NULL,
      PRIMARY KEY (`id`),
      KEY `package_id` (`package_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 6. Package Days (Itinerary) Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `package_days` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `package_id` int(11) NOT NULL,
      `day_number` int(11) NOT NULL,
      `day_title` varchar(255) NOT NULL,
      `day_content` text DEFAULT NULL,
      `accommodation` varchar(255) DEFAULT NULL,
      `meals` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `package_id` (`package_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 7. Package Highlights Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `package_highlights` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `package_id` int(11) NOT NULL,
      `highlight_text` varchar(500) NOT NULL,
      `sort_order` int(11) DEFAULT 0,
      PRIMARY KEY (`id`),
      KEY `package_id` (`package_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 8. Package Inclusions/Exclusions Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `package_inclusions` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `package_id` int(11) NOT NULL,
      `type` enum('inclusion','exclusion') NOT NULL DEFAULT 'inclusion',
      `item_text` varchar(500) NOT NULL,
      `sort_order` int(11) DEFAULT 0,
      PRIMARY KEY (`id`),
      KEY `package_id` (`package_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 9. Settings Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `settings` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `setting_key` varchar(100) NOT NULL,
      `setting_value` text DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `setting_key` (`setting_key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed default settings if they do not exist
    $defaults = [
        'contact_email' => 'support@wanderoo.world',
        'contact_phone' => '+91 91 135 154 62',
        'contact_whatsapp' => '919113515462',
        'contact_address' => "Wanderoo\nThe landmark\n2nd Floor, Santacruz West\nMumbai - 400049"
    ];

    foreach ($defaults as $key => $val) {
        $stmtCheckSetting = $pdo->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
        $stmtCheckSetting->execute([$key]);
        if ($stmtCheckSetting->fetchColumn() == 0) {
            $stmtInsertSetting = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
            $stmtInsertSetting->execute([$key, $val]);
        }
    }

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
    <!-- Prevent flash of wrong theme -->
    <script>
        (function() {
            var theme = localStorage.getItem('admin_theme');
            if (theme === 'light') {
                document.documentElement.classList.add('light-mode');
            }
        })();
    </script>
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
            box-shadow: var(--auth-shadow);
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
    <!-- Theme Toggle for Auth Pages -->
    <button class="auth-theme-toggle" id="authThemeToggle" title="Toggle Light/Dark Mode" onclick="toggleAdminTheme()">
        <span class="icon-sun">☀️</span>
        <span class="icon-moon">🌙</span>
    </button>

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

    <script>
    function toggleAdminTheme() {
        var html = document.documentElement;
        if (html.classList.contains('light-mode')) {
            html.classList.remove('light-mode');
            localStorage.setItem('admin_theme', 'dark');
        } else {
            html.classList.add('light-mode');
            localStorage.setItem('admin_theme', 'light');
        }
    }
    </script>
</body>
</html>
