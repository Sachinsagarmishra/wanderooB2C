<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enforce admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("HTTP/1.1 403 Forbidden");
    die("Access denied. Please log in.");
}

require_once __DIR__ . '/../includes/db.php';

// Handle filter & search parameters (same logic as leads.php, but without pagination LIMIT)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$dateFilter = isset($_GET['date_filter']) ? trim($_GET['date_filter']) : 'all';
$startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$endDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

$whereClauses = [];
$params = [];

// Search criteria
if ($search !== '') {
    $whereClauses[] = "(fullname LIKE ? OR email LIKE ? OR phone LIKE ? OR destination LIKE ? OR subject LIKE ? OR message LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam]);
}

// Type filter
if ($type !== '' && $type !== 'all') {
    $whereClauses[] = "type = ?";
    $params[] = $type;
}

// Date filters
if ($dateFilter === 'today') {
    $whereClauses[] = "created_at >= CURDATE()";
} elseif ($dateFilter === 'yesterday') {
    $whereClauses[] = "created_at >= DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND created_at < CURDATE()";
} elseif ($dateFilter === 'this_week') {
    $whereClauses[] = "created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} elseif ($dateFilter === 'this_month') {
    $whereClauses[] = "created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
} elseif ($dateFilter === 'custom') {
    if ($startDate !== '') {
        $whereClauses[] = "created_at >= ?";
        $params[] = $startDate . ' 00:00:00';
    }
    if ($endDate !== '') {
        $whereClauses[] = "created_at <= ?";
        $params[] = $endDate . ' 23:59:59';
    }
}

$whereSql = '';
if (!empty($whereClauses)) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

try {
    $stmt = $pdo->prepare("SELECT * FROM leads $whereSql ORDER BY created_at DESC");
    $stmt->execute($params);
    $leads = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database query error: " . $e->getMessage());
}

// Set headers to trigger file download
$filename = "leads_export_" . date("Y-m-d_H-i-s") . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Add UTF-8 Byte Order Mark (BOM) so Excel reads international characters correctly
fwrite($output, "\xEF\xBB\xBF");

// Output column headers
fputcsv($output, [
    'ID',
    'Date Submitted',
    'Type',
    'Full Name',
    'Email Address',
    'Phone Number',
    'Source Page URL',
    'Subject (Contact Form)',
    'Message (Contact Form)',
    'Destination (Enquiry)',
    'Departure Date (Enquiry)',
    'Nights (Enquiry)',
    'Companion (Enquiry)',
    'Rooms Configurations (Enquiry)',
    'Special Notes (Enquiry)'
]);

// Loop through the data and write to output stream
foreach ($leads as $lead) {
    // Format rooms config into a human-readable string if present
    $roomsText = '';
    if (!empty($lead['rooms_config']) && $lead['type'] === 'enquiry') {
        try {
            $config = json_decode($lead['rooms_config'], true);
            if (is_array($config)) {
                $roomsParts = [];
                foreach ($config as $r) {
                    $childText = 'No children';
                    if (!empty($r['children'])) {
                        $childText = count($r['children']) . " child(ren) (Ages: " . implode(', ', $r['children']) . ")";
                    }
                    $roomsParts[] = "Room " . $r['room'] . ": " . $r['adults'] . " Adult(s), " . $childText;
                }
                $roomsText = implode(" | ", $roomsParts);
            } else {
                $roomsText = $lead['rooms_config'];
            }
        } catch (Exception $e) {
            $roomsText = $lead['rooms_config'];
        }
    }

    fputcsv($output, [
        $lead['id'],
        $lead['created_at'],
        $lead['type'] === 'enquiry' ? 'Popup Enquiry' : 'Contact Form',
        $lead['fullname'],
        $lead['email'],
        $lead['phone'],
        $lead['source_page'],
        $lead['subject'] ?? '',
        $lead['message'] ?? '',
        $lead['destination'] ?? '',
        $lead['departure_date'] ?? '',
        $lead['nights'] ?? '',
        $lead['companion'] ?? '',
        $roomsText,
        $lead['notes'] ?? ''
    ]);
}

fclose($output);
exit;
?>
