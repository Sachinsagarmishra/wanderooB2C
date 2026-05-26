<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

// Fetch Statistics
try {
    // Total Leads
    $stmt = $pdo->query("SELECT COUNT(*) FROM leads");
    $totalLeads = $stmt->fetchColumn();

    // Contact Leads
    $stmt = $pdo->query("SELECT COUNT(*) FROM leads WHERE type = 'contact'");
    $contactLeads = $stmt->fetchColumn();

    // Enquiry Leads
    $stmt = $pdo->query("SELECT COUNT(*) FROM leads WHERE type = 'enquiry'");
    $enquiryLeads = $stmt->fetchColumn();

    // Recent Leads (Limit 5)
    $stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC LIMIT 5");
    $recentLeads = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database query error: " . $e->getMessage());
}
?>

<div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
    <h1>Overview</h1>
    <div class="fg3"><?php echo date('l, F j, Y'); ?></div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Leads</div>
        <div class="stat-value"><?php echo $totalLeads; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Contact Form Leads</div>
        <div class="stat-value"><?php echo $contactLeads; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Popup Enquiries</div>
        <div class="stat-value"><?php echo $enquiryLeads; ?></div>
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Recent Leads</h2>
        <a href="leads.php" class="btn btn-primary" style="padding: 6px 12px; font-size: 11px;">View All</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Submitter</th>
                    <th>Type</th>
                    <th>Date Received</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentLeads)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--fg3); padding: 40px 20px;">No leads received yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentLeads as $lead): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--fg);"><?php echo htmlspecialchars($lead['fullname']); ?></strong><br>
                                <span style="font-size: 11px; color: var(--fg3);"><?php echo htmlspecialchars($lead['email']); ?></span>
                            </td>
                            <td>
                                <?php if ($lead['type'] === 'enquiry'): ?>
                                    <span class="badge badge-info">Popup Enquiry</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Contact Form</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo date('M j, Y, g:i a', strtotime($lead['created_at'])); ?>
                            </td>
                            <td>
                                <a href="leads.php?view=<?php echo $lead['id']; ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 11px; background: var(--bg3); border-color: var(--border); color: var(--fg);">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
