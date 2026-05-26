<?php
// Enforce basic admin session authentication for security
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Content-Type: text/plain');
    die("Access denied. Please log in to your admin panel first, then run this script.");
}

header('Content-Type: text/plain');

$docRoot = $_SERVER['DOCUMENT_ROOT'];
// Attempt to replace wanderoo.world with the hostingersite.com domain
$target = str_replace('wanderoo.world', 'mediumvioletred-salmon-526657.hostingersite.com', $docRoot) . '/uploads';
$link = $docRoot . '/uploads';

echo "Document Root: " . $docRoot . "\n";
echo "Attempting to create Symbolic Link:\n";
echo "Target (Original uploads directory): " . $target . "\n";
echo "Link (New uploads shortcut): " . $link . "\n\n";

// Fallback auto-detection if domain-replace is not matching
if (!file_exists($target)) {
    $domainsDir = dirname(dirname($docRoot)); // e.g. /home/uXXXX/domains
    echo "Scanning domain directories at: " . $domainsDir . "\n";
    if (is_dir($domainsDir)) {
        $dirs = scandir($domainsDir);
        foreach ($dirs as $dir) {
            if ($dir !== '.' && $dir !== '..' && strpos($dir, 'hostingersite.com') !== false) {
                $target = $domainsDir . '/' . $dir . '/public_html/uploads';
                echo "Detected target: " . $target . "\n";
                break;
            }
        }
    }
}

if (!is_dir($target)) {
    echo "ERROR: Target directory does not exist or could not be found.\n";
    echo "Please edit admin/create-symlink.php and define the \$target path manually.\n";
    exit;
}

if (is_link($link)) {
    echo "SUCCESS: A symbolic link already exists at " . $link . "!\n";
    exit;
}

if (file_exists($link)) {
    if (is_dir($link)) {
        // Delete directory only if it's empty to prevent accidental loss
        $files = array_diff(scandir($link), array('.', '..'));
        if (empty($files)) {
            if (rmdir($link)) {
                echo "Removed empty local uploads folder to clear space for the link.\n";
            } else {
                echo "ERROR: Could not remove empty local uploads folder. Please delete it manually.\n";
                exit;
            }
        } else {
            echo "ERROR: The local uploads folder is NOT empty. Please backup/move its contents and delete the folder manually.\n";
            exit;
        }
    } else {
        unlink($link);
    }
}

// Create the symlink
if (symlink($target, $link)) {
    echo "SUCCESS: Symbolic link created successfully!\n";
    echo "Both websites will now share the same uploads folder. Images will load on both domains without taking double disk space.";
} else {
    echo "ERROR: Failed to create symbolic link. Check permissions or PHP server configuration.";
}
?>
