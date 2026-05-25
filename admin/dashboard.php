<?php include_once 'includes/header.php'; ?>

<div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
    <h1>Overview</h1>
    <div class="fg3"><?php echo date('l, F j, Y'); ?></div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Pages</div>
        <div class="stat-value">12</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Site Visits</div>
        <div class="stat-value">850</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">New Inquiries</div>
        <div class="stat-value">5</div>
    </div>
</div>

<div class="card">
    <h2>Recent Activity</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Homepage Content Updated</td>
                    <td>2026-04-26</td>
                    <td><span class="badge badge-success">Success</span></td>
                </tr>
                <tr>
                    <td>New User Registered</td>
                    <td>2026-04-25</td>
                    <td><span class="badge badge-success">Success</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
