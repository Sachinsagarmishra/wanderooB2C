<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

$success = '';
$error = '';

// Handle Delete Lead Action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $leadId = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("DELETE FROM leads WHERE id = ?");
        $stmt->execute([$leadId]);
        $success = "Lead deleted successfully.";
    } catch (PDOException $e) {
        $error = "Error deleting lead: " . $e->getMessage();
    }
}

// Fetch all leads
try {
    $stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC");
    $leads = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database query error: " . $e->getMessage());
}
?>

<style>
    .admin-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .admin-modal-content {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius-main);
        width: 100%;
        max-width: 650px;
        padding: 24px;
        position: relative;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        max-height: 90vh;
        overflow-y: auto;
    }
    .admin-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 12px;
    }
    .close-modal {
        font-size: 28px;
        cursor: pointer;
        color: var(--fg3);
        line-height: 1;
    }
    .close-modal:hover {
        color: var(--fg);
    }
    .lead-detail-row {
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .lead-detail-label {
        font-size: 11px;
        text-transform: uppercase;
        color: var(--fg3);
        font-weight: 600;
    }
    .lead-detail-value {
        font-size: 13px;
        color: var(--fg);
        background: var(--bg3);
        padding: 8px 12px;
        border-radius: var(--radius-int);
        border: 1px solid var(--border);
        white-space: pre-wrap;
        word-break: break-word;
    }
    .lead-detail-value div {
        margin-bottom: 6px;
    }
    .lead-detail-value div:last-child {
        margin-bottom: 0;
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

<div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
    <h1>Leads Manager</h1>
    <div class="fg3">Total: <strong><?php echo count($leads); ?></strong> entries</div>
</div>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Submitter</th>
                    <th>Contact info</th>
                    <th>Type</th>
                    <th>Date Received</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($leads)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--fg3); padding: 50px 20px;">No lead records found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--fg); font-size: 14px;"><?php echo htmlspecialchars($lead['fullname']); ?></strong>
                                <?php if (!empty($lead['source_page'])): ?>
                                    <div style="font-size: 11px; color: var(--fg3); margin-top: 4px; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        Src: <a href="<?php echo htmlspecialchars($lead['source_page']); ?>" target="_blank" title="<?php echo htmlspecialchars($lead['source_page']); ?>" style="color: var(--accent); text-decoration: underline;"><?php echo htmlspecialchars($lead['source_page']); ?></a>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>" style="color: var(--accent);"><?php echo htmlspecialchars($lead['email']); ?></a></div>
                                <div style="font-size: 11px; color: var(--fg3); margin-top: 2px;"><?php echo htmlspecialchars($lead['phone']); ?></div>
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
                                <div style="display: flex; gap: 8px;">
                                    <button class="btn btn-primary" style="padding: 6px 12px; font-size: 11px;" onclick='showLead(<?php echo json_encode($lead); ?>)'>View</button>
                                    <a href="leads.php?action=delete&id=<?php echo $lead['id']; ?>" class="btn" style="padding: 6px 12px; font-size: 11px; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); color: var(--danger);" onclick="return confirm('Are you sure you want to delete this lead?');">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Viewer -->
<div id="leadModal" class="admin-modal" style="display: none;">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3>Inquiry Details</h3>
            <span class="close-modal" onclick="closeLeadModal()">&times;</span>
        </div>
        <div class="admin-modal-body" id="leadModalBody">
            <!-- Dynamically Populated -->
        </div>
    </div>
</div>

<script>
function showLead(lead) {
    const body = document.getElementById('leadModalBody');
    let html = '';
    
    // Common Info
    html += `
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="lead-detail-row">
                <span class="lead-detail-label">Name</span>
                <span class="lead-detail-value">${escapeHtml(lead.fullname)}</span>
            </div>
            <div class="lead-detail-row">
                <span class="lead-detail-label">Source</span>
                <span class="lead-detail-value">${lead.type === 'enquiry' ? 'Popup Multi-step Enquiry' : 'Contact Us Page Form'}</span>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div class="lead-detail-row">
                <span class="lead-detail-label">Email</span>
                <span class="lead-detail-value"><a href="mailto:${escapeHtml(lead.email)}" style="color: var(--accent);">${escapeHtml(lead.email)}</a></span>
            </div>
            <div class="lead-detail-row">
                <span class="lead-detail-label">Phone</span>
                <span class="lead-detail-value">${escapeHtml(lead.phone)}</span>
            </div>
        </div>
        <div class="lead-detail-row">
            <span class="lead-detail-label">Date Submitted</span>
            <span class="lead-detail-value">${lead.created_at}</span>
        </div>
        <div class="lead-detail-row">
            <span class="lead-detail-label">Source Page URL</span>
            <span class="lead-detail-value">
                ${lead.source_page ? `<a href="${escapeHtml(lead.source_page)}" target="_blank" style="color: var(--accent); text-decoration: underline;">${escapeHtml(lead.source_page)}</a>` : 'N/A'}
            </span>
        </div>
    `;
    
    if (lead.type === 'contact') {
        html += `
            <div class="lead-detail-row">
                <span class="lead-detail-label">Subject</span>
                <span class="lead-detail-value">${escapeHtml(lead.subject)}</span>
            </div>
            <div class="lead-detail-row">
                <span class="lead-detail-label">Message</span>
                <div class="lead-detail-value" style="min-height: 100px;">${escapeHtml(lead.message)}</div>
            </div>
        `;
    } else {
        // Parse rooms config
        let roomsHtml = 'N/A';
        if (lead.rooms_config) {
            try {
                const config = JSON.parse(lead.rooms_config);
                roomsHtml = '';
                config.forEach(r => {
                    let childText = 'No children';
                    if (r.children && r.children.length > 0) {
                        childText = `${r.children.length} child(ren) (Ages: ${r.children.join(', ')})`;
                    }
                    roomsHtml += `<div><strong>Room ${r.room}</strong>: ${r.adults} Adult(s), ${childText}</div>`;
                });
            } catch(e) {
                roomsHtml = escapeHtml(lead.rooms_config);
            }
        }
        
        html += `
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="lead-detail-row">
                    <span class="lead-detail-label">Destination</span>
                    <span class="lead-detail-value" style="text-transform: capitalize;">${escapeHtml(lead.destination)}</span>
                </div>
                <div class="lead-detail-row">
                    <span class="lead-detail-label">Departure Date</span>
                    <span class="lead-detail-value">${lead.departure_date ? lead.departure_date : 'Flexible / Undefined'}</span>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div class="lead-detail-row">
                    <span class="lead-detail-label">Duration</span>
                    <span class="lead-detail-value">${escapeHtml(lead.nights)}</span>
                </div>
                <div class="lead-detail-row">
                    <span class="lead-detail-label">Companion Type</span>
                    <span class="lead-detail-value">${escapeHtml(lead.companion)}</span>
                </div>
            </div>
            <div class="lead-detail-row">
                <span class="lead-detail-label">Rooms Configuration</span>
                <div class="lead-detail-value">${roomsHtml}</div>
            </div>
            <div class="lead-detail-row">
                <span class="lead-detail-label">Special Notes</span>
                <div class="lead-detail-value" style="min-height: 80px;">${lead.notes ? escapeHtml(lead.notes) : 'None'}</div>
            </div>
        `;
    }
    
    body.innerHTML = html;
    document.getElementById('leadModal').style.display = 'flex';
}

function closeLeadModal() {
    document.getElementById('leadModal').style.display = 'none';
}

function escapeHtml(str) {
    if (!str) return '';
    return str
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

// Close modal when clicking outside content area
document.getElementById('leadModal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('leadModal')) {
        closeLeadModal();
    }
});
</script>

<?php if (isset($_GET['view'])): ?>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = ?");
    $stmt->execute([intval($_GET['view'])]);
    $viewLead = $stmt->fetch();
    ?>
    <?php if ($viewLead): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showLead(<?php echo json_encode($viewLead); ?>);
            });
        </script>
    <?php endif; ?>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>
