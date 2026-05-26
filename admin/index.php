<?php
require_once __DIR__ . '/../includes/db.php';

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Both fields are required!";
    } else {
        try {
            // Retrieve user
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Log in user
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_email'] = $user['email'];

                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid username or password!";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?php echo SITE_NAME; ?></title>
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
            max-width: 400px;
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
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 13px;
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
            <div class="auth-subtitle">Sign in to Admin Dashboard</div>

            <?php if (!empty($error)): ?>
                <div class="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Log In</button>
            </form>
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
