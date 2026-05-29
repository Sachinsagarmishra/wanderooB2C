<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

$successMsg = '';
$errorMsg = '';
$activeTab = isset($_GET['tab']) ? trim($_GET['tab']) : 'persona';

// ──── Handle POST Saves ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errorMsg = "Security validation failed (CSRF token mismatch).";
    } else {
        $action = $_POST['form_action'] ?? '';

        if ($action === 'save_persona') {
            $updates = [
                'ai_agent_enabled'        => isset($_POST['ai_agent_enabled']) ? '1' : '0',
                'ai_agent_openrouter_key' => trim($_POST['ai_agent_openrouter_key'] ?? ''),
                'ai_agent_model'          => trim($_POST['ai_agent_model'] ?? 'google/gemini-2.0-flash'),
                'ai_agent_temperature'    => floatval($_POST['ai_agent_temperature'] ?? 0.7),
            ];

            try {
                foreach ($updates as $key => $val) {
                    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                    $stmt->execute([$key, $val, $val]);
                }
                $successMsg = "Persona & Configuration saved!";
                global $site_settings;
                foreach ($updates as $k => $v) { $site_settings[$k] = $v; }
            } catch (PDOException $e) {
                $errorMsg = "Database error: " . $e->getMessage();
            }
            $activeTab = 'persona';

        } elseif ($action === 'save_prompt') {
            $systemPrompt = trim($_POST['ai_agent_system_prompt'] ?? '');
            try {
                $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute(['ai_agent_system_prompt', $systemPrompt, $systemPrompt]);
                $successMsg = "System prompt updated!";
                global $site_settings;
                $site_settings['ai_agent_system_prompt'] = $systemPrompt;
            } catch (PDOException $e) {
                $errorMsg = "Database error: " . $e->getMessage();
            }
            $activeTab = 'prompt';

        } elseif ($action === 'save_quickcards') {
            try {
                for ($i = 1; $i <= 6; $i++) {
                    $icon  = trim($_POST["ai_quick_card_{$i}_icon"] ?? '');
                    $title = trim($_POST["ai_quick_card_{$i}_title"] ?? '');
                    $desc  = trim($_POST["ai_quick_card_{$i}_desc"] ?? '');
                    foreach (['icon' => $icon, 'title' => $title, 'desc' => $desc] as $suffix => $val) {
                        $key = "ai_quick_card_{$i}_{$suffix}";
                        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                        $stmt->execute([$key, $val, $val]);
                    }
                }
                $successMsg = "Quick-start cards updated!";
                // Reload settings cache
                global $site_settings;
                $stmtReload = $pdo->query("SELECT setting_key, setting_value FROM settings");
                while ($row = $stmtReload->fetch()) {
                    $site_settings[$row['setting_key']] = $row['setting_value'];
                }
            } catch (PDOException $e) {
                $errorMsg = "Database error: " . $e->getMessage();
            }
            $activeTab = 'quickcards';

        } elseif ($action === 'delete_lead') {
            $leadId = intval($_POST['lead_id'] ?? 0);
            if ($leadId > 0) {
                try {
                    $stmt = $pdo->prepare("DELETE FROM ai_leads WHERE id = ?");
                    $stmt->execute([$leadId]);
                    $successMsg = "Lead deleted.";
                } catch (PDOException $e) {
                    $errorMsg = "Error: " . $e->getMessage();
                }
            }
            $activeTab = 'leads';
        }
    }
}

// ──── Fetch Current Settings ───────────────────────────────────
$agentEnabled   = get_setting('ai_agent_enabled', '0');
$openrouterKey  = get_setting('ai_agent_openrouter_key', '');
$modelId        = get_setting('ai_agent_model', 'google/gemini-2.0-flash');
$temperature    = get_setting('ai_agent_temperature', '0.7');
$systemPrompt   = get_setting('ai_agent_system_prompt', '');

// ──── Knowledge Stats ──────────────────────────────────────────
$destCount = 0;
$pkgCount  = 0;
$dayCount  = 0;
try {
    $destCount = intval($pdo->query("SELECT COUNT(*) FROM destinations")->fetchColumn());
    $pkgCount  = intval($pdo->query("SELECT COUNT(*) FROM tour_packages WHERE status = 'active'")->fetchColumn());
    $dayCount  = intval($pdo->query("SELECT COUNT(*) FROM package_days")->fetchColumn());
} catch (PDOException $e) { /* skip */ }

// ──── Leads Data ───────────────────────────────────────────────
$leadSearch = isset($_GET['lead_search']) ? trim($_GET['lead_search']) : '';
$leadsPage  = isset($_GET['lpage']) ? max(1, intval($_GET['lpage'])) : 1;
$leadsLimit = 10;
$leadsOffset = ($leadsPage - 1) * $leadsLimit;

$leadsWhere = '';
$leadsParams = [];
if ($leadSearch !== '') {
    $leadsWhere = "WHERE (client_name LIKE ? OR work_email LIKE ? OR whatsapp_line LIKE ? OR captured_context LIKE ?)";
    $sp = "%$leadSearch%";
    $leadsParams = [$sp, $sp, $sp, $sp];
}

$totalLeads = 0;
$aiLeads = [];
try {
    $cntStmt = $pdo->prepare("SELECT COUNT(*) FROM ai_leads $leadsWhere");
    $cntStmt->execute($leadsParams);
    $totalLeads = intval($cntStmt->fetchColumn());

    $leadStmt = $pdo->prepare("SELECT * FROM ai_leads $leadsWhere ORDER BY created_at DESC LIMIT $leadsLimit OFFSET $leadsOffset");
    $leadStmt->execute($leadsParams);
    $aiLeads = $leadStmt->fetchAll();
} catch (PDOException $e) { /* skip */ }

$totalLeadsPages = max(1, ceil($totalLeads / $leadsLimit));
?>

<style>
    .ai-agent-container { max-width: 1200px; margin: 0 auto; }
    .ai-tabs {
        display: flex; gap: 0; border-bottom: 2px solid var(--border);
        margin-bottom: 24px; flex-wrap: wrap;
    }
    .ai-tab {
        padding: 12px 20px; font-size: 13px; font-weight: 600; cursor: pointer;
        color: var(--fg3); border-bottom: 2px solid transparent; margin-bottom: -2px;
        transition: all 0.2s; background: none; border-top: none; border-left: none; border-right: none;
        font-family: inherit;
    }
    .ai-tab:hover { color: var(--fg); }
    .ai-tab.active {
        color: var(--accent); border-bottom-color: var(--accent);
    }
    .ai-tab-panel { display: none; }
    .ai-tab-panel.active { display: block; }
    .ai-card {
        background: var(--bg2); border: 1px solid var(--border);
        border-radius: var(--radius-main); padding: 24px;
        box-shadow: var(--shadow-card); margin-bottom: 20px;
    }
    .ai-card h2 {
        font-size: 16px; margin-bottom: 20px; padding-bottom: 10px;
        border-bottom: 1px solid var(--border); color: var(--fg);
        display: flex; align-items: center; gap: 8px;
    }
    .ai-form-group {
        display: flex; flex-direction: column; gap: 6px; margin-bottom: 18px;
    }
    .ai-form-group label {
        font-weight: 600; color: var(--fg2); font-size: 12px;
    }
    .ai-form-control {
        background: var(--bg3); border: 1px solid var(--border);
        border-radius: var(--radius-int); padding: 10px 14px;
        color: var(--fg); outline: none; font-family: inherit;
        font-size: 13px; transition: border-color 0.2s; width: 100%;
    }
    .ai-form-control:focus { border-color: var(--accent); }
    textarea.ai-form-control { min-height: 200px; resize: vertical; }
    .ai-checkbox-group {
        display: flex; align-items: center; gap: 10px; margin-bottom: 18px;
        padding: 10px; background: var(--bg3); border: 1px solid var(--border);
        border-radius: var(--radius-int);
    }
    .ai-checkbox-group input[type="checkbox"] {
        width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer;
    }
    .ai-checkbox-group label {
        font-weight: 600; font-size: 12px; color: var(--fg); cursor: pointer; user-select: none;
    }
    .ai-stat-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px; margin-bottom: 20px;
    }
    .ai-stat-card {
        background: var(--bg3); border: 1px solid var(--border);
        padding: 16px; border-radius: var(--radius-int); text-align: center;
    }
    .ai-stat-card .ai-stat-value { font-size: 28px; font-weight: 700; color: var(--accent); }
    .ai-stat-card .ai-stat-label { font-size: 11px; color: var(--fg3); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; }
    .ai-quickcard-row {
        display: grid; grid-template-columns: 80px 1fr 1.5fr;
        gap: 10px; align-items: center; margin-bottom: 12px;
        padding: 10px; background: var(--bg3); border-radius: var(--radius-int);
        border: 1px solid var(--border);
    }
    @media (max-width: 600px) {
        .ai-quickcard-row { grid-template-columns: 1fr; }
    }
    .ai-quickcard-label {
        font-size: 11px; font-weight: 700; color: var(--fg3);
        text-transform: uppercase;
    }
    .alert {
        padding: 12px 16px; border-radius: var(--radius-int);
        margin-bottom: 24px; font-weight: 500; font-size: 13px;
    }
    .alert-success {
        background: rgba(34, 197, 94, 0.1); color: var(--success);
        border: 1px solid rgba(34, 197, 94, 0.2);
    }
    .alert-danger {
        background: rgba(239, 68, 68, 0.1); color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    .ai-lead-search-bar {
        display: flex; gap: 10px; margin-bottom: 16px; flex-wrap: wrap;
    }
    .badge-ai-count {
        font-size: 11px; background: rgba(249,115,22,0.15); color: #F97316;
        padding: 2px 8px; border-radius: 10px; font-weight: 700;
    }
</style>

<div class="ai-agent-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 12px;">
        <h1 style="margin-bottom: 0;">🦘 Joey AI Agent Manager</h1>
    </div>
    <p style="color: var(--fg3); font-size: 12px; margin-bottom: 24px;">Configure your AI travel advisor, manage leads, and customize the chat experience.</p>

    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
    <?php endif; ?>
    <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php endif; ?>

    <!-- Tabs Navigation -->
    <div class="ai-tabs">
        <button class="ai-tab <?php echo $activeTab === 'persona' ? 'active' : ''; ?>" data-tab="persona">Persona & Configuration</button>
        <button class="ai-tab <?php echo $activeTab === 'prompt' ? 'active' : ''; ?>" data-tab="prompt">Agent Prompt & Knowledge Base</button>
        <button class="ai-tab <?php echo $activeTab === 'quickcards' ? 'active' : ''; ?>" data-tab="quickcards">Pre-Questions Grid</button>
        <button class="ai-tab <?php echo $activeTab === 'leads' ? 'active' : ''; ?>" data-tab="leads">Captured Leads <span class="badge-ai-count"><?php echo $totalLeads; ?></span></button>
    </div>

    <!-- ═══ TAB A: Persona & Configuration ═══ -->
    <div class="ai-tab-panel <?php echo $activeTab === 'persona' ? 'active' : ''; ?>" id="tab-persona">
        <form method="POST" action="ai-agent.php?tab=persona">
            <?php csrf_input(); ?>
            <input type="hidden" name="form_action" value="save_persona">
            <div class="ai-card">
                <h2>⚙️ Persona & Configuration</h2>

                <div class="ai-checkbox-group">
                    <input type="checkbox" id="ai_agent_enabled" name="ai_agent_enabled" value="1" <?php echo $agentEnabled === '1' ? 'checked' : ''; ?>>
                    <label for="ai_agent_enabled">Enable Joey AI Chatbot on Public Website</label>
                </div>

                <div class="ai-form-group">
                    <label for="ai_agent_openrouter_key">OpenRouter API Key *</label>
                    <input type="password" id="ai_agent_openrouter_key" name="ai_agent_openrouter_key" class="ai-form-control" value="<?php echo htmlspecialchars($openrouterKey); ?>" placeholder="sk-or-v1-xxxxxxxxxx">
                    <div style="font-size: 11px; color: var(--fg3); margin-top: 4px;">Get your key from <a href="https://openrouter.ai/keys" target="_blank" style="color: var(--accent); text-decoration: underline;">openrouter.ai/keys</a></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="ai-form-group">
                        <label for="ai_agent_model">OpenRouter Model ID</label>
                        <input type="text" id="ai_agent_model" name="ai_agent_model" class="ai-form-control" value="<?php echo htmlspecialchars($modelId); ?>" placeholder="google/gemini-2.0-flash">
                        <div style="font-size: 11px; color: var(--fg3); margin-top: 4px;">e.g. google/gemini-2.0-flash, google/gemini-2.5-flash</div>
                    </div>
                    <div class="ai-form-group">
                        <label for="ai_agent_temperature">Temperature: <strong id="tempDisplay"><?php echo htmlspecialchars($temperature); ?></strong></label>
                        <input type="range" id="ai_agent_temperature" name="ai_agent_temperature" class="ai-form-control" min="0" max="1" step="0.1" value="<?php echo htmlspecialchars($temperature); ?>" style="padding: 4px;" oninput="document.getElementById('tempDisplay').textContent = this.value">
                        <div style="font-size: 11px; color: var(--fg3); margin-top: 4px;">0 = factual/consistent, 1 = creative/varied</div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 13px; margin-top: 10px;">💾 Save Configuration</button>
            </div>
        </form>
    </div>

    <!-- ═══ TAB B: Agent Prompt & Knowledge Base ═══ -->
    <div class="ai-tab-panel <?php echo $activeTab === 'prompt' ? 'active' : ''; ?>" id="tab-prompt">
        <div class="ai-card" style="margin-bottom: 20px;">
            <h2>📊 Live Knowledge Base Status</h2>
            <div class="ai-stat-grid">
                <div class="ai-stat-card">
                    <div class="ai-stat-value"><?php echo $destCount; ?></div>
                    <div class="ai-stat-label">Active Destinations</div>
                </div>
                <div class="ai-stat-card">
                    <div class="ai-stat-value"><?php echo $pkgCount; ?></div>
                    <div class="ai-stat-label">Active Packages</div>
                </div>
                <div class="ai-stat-card">
                    <div class="ai-stat-value"><?php echo $dayCount; ?></div>
                    <div class="ai-stat-label">Itinerary Days</div>
                </div>
            </div>
            <p style="font-size: 12px; color: var(--fg3);">Joey AI automatically loads all destination, package, itinerary, highlight, and inclusion/exclusion data from your database. When you add or update content, the AI brain updates instantly.</p>
        </div>

        <form method="POST" action="ai-agent.php?tab=prompt">
            <?php csrf_input(); ?>
            <input type="hidden" name="form_action" value="save_prompt">
            <div class="ai-card">
                <h2>🧠 System Prompt (Persona Instructions)</h2>
                <div class="ai-form-group">
                    <label for="ai_agent_system_prompt">Custom System Prompt</label>
                    <textarea id="ai_agent_system_prompt" name="ai_agent_system_prompt" class="ai-form-control" placeholder="Leave blank to use the default Joey AI persona with safety guardrails..."><?php echo htmlspecialchars($systemPrompt); ?></textarea>
                    <div style="font-size: 11px; color: var(--fg3); margin-top: 4px;">If left blank, a comprehensive default prompt with safety guardrails will be used. Only override if you need custom behavior.</div>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 13px;">💾 Save System Prompt</button>
            </div>
        </form>
    </div>

    <!-- ═══ TAB C: Quick-Start Grid ═══ -->
    <div class="ai-tab-panel <?php echo $activeTab === 'quickcards' ? 'active' : ''; ?>" id="tab-quickcards">
        <form method="POST" action="ai-agent.php?tab=quickcards">
            <?php csrf_input(); ?>
            <input type="hidden" name="form_action" value="save_quickcards">
            <div class="ai-card">
                <h2>🗂️ Pre-Questions Grid (Quick-Start Cards)</h2>
                <p style="font-size: 12px; color: var(--fg3); margin-bottom: 16px;">Configure the 6 quick-click cards shown when the chat opens. Leave blank to use defaults.</p>

                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <div class="ai-quickcard-row">
                        <div>
                            <span class="ai-quickcard-label">Card <?php echo $i; ?></span>
                            <input type="text" name="ai_quick_card_<?php echo $i; ?>_icon" class="ai-form-control" value="<?php echo htmlspecialchars(get_setting("ai_quick_card_{$i}_icon", '')); ?>" placeholder="🌴" style="margin-top: 6px; text-align: center;">
                        </div>
                        <div>
                            <input type="text" name="ai_quick_card_<?php echo $i; ?>_title" class="ai-form-control" value="<?php echo htmlspecialchars(get_setting("ai_quick_card_{$i}_title", '')); ?>" placeholder="Card Title (e.g. Bali packages)">
                        </div>
                        <div>
                            <input type="text" name="ai_quick_card_<?php echo $i; ?>_desc" class="ai-form-control" value="<?php echo htmlspecialchars(get_setting("ai_quick_card_{$i}_desc", '')); ?>" placeholder="Short description (e.g. Ubud jungle, beachfront)">
                        </div>
                    </div>
                <?php endfor; ?>

                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 13px; margin-top: 10px;">💾 Save Quick Cards</button>
            </div>
        </form>
    </div>

    <!-- ═══ TAB D: Captured Proposal Leads ═══ -->
    <div class="ai-tab-panel <?php echo $activeTab === 'leads' ? 'active' : ''; ?>" id="tab-leads">
        <div class="ai-card">
            <h2>📋 Captured AI Leads</h2>
            <p style="font-size: 12px; color: var(--fg3); margin-bottom: 16px;">Whenever a visitor uses Joey AI and requests a customized proposal, their contact details are logged here.</p>

            <div class="ai-lead-search-bar">
                <form method="GET" style="display: flex; gap: 8px; flex: 1; min-width: 250px;">
                    <input type="hidden" name="tab" value="leads">
                    <input type="text" name="lead_search" class="ai-form-control" placeholder="Search by name, email, WhatsApp, or retreat details..." value="<?php echo htmlspecialchars($leadSearch); ?>" style="flex: 1;">
                    <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">Search</button>
                    <?php if ($leadSearch): ?>
                        <a href="ai-agent.php?tab=leads" class="btn" style="background: var(--bg3); border: 1px solid var(--border); color: var(--fg); padding: 8px 16px; font-size: 12px;">Clear</a>
                    <?php endif; ?>
                </form>
                <div style="font-size: 12px; color: var(--fg3); display: flex; align-items: center; gap: 6px;">
                    Showing <?php echo count($aiLeads); ?> of <?php echo $totalLeads; ?> leads
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"></th>
                            <th>Submitted Date</th>
                            <th>Client Name</th>
                            <th>Work Email</th>
                            <th>WhatsApp Line</th>
                            <th>Captured Context</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($aiLeads)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--fg3); padding: 50px 20px;">No AI leads captured yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($aiLeads as $lead): ?>
                                <tr>
                                    <td><input type="checkbox" class="lead-checkbox" value="<?php echo $lead['id']; ?>"></td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($lead['created_at'])); ?></td>
                                    <td><strong style="color: var(--fg);"><?php echo htmlspecialchars($lead['client_name']); ?></strong></td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($lead['work_email']); ?>" style="color: var(--accent);"><?php echo htmlspecialchars($lead['work_email']); ?></a></td>
                                    <td><?php echo htmlspecialchars($lead['whatsapp_line']); ?></td>
                                    <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($lead['captured_context']); ?>">
                                        <?php echo htmlspecialchars($lead['captured_context']); ?>
                                    </td>
                                    <td>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this lead?');">
                                            <?php csrf_input(); ?>
                                            <input type="hidden" name="form_action" value="delete_lead">
                                            <input type="hidden" name="lead_id" value="<?php echo $lead['id']; ?>">
                                            <button type="submit" class="btn" style="padding: 5px 10px; font-size: 11px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: var(--danger); cursor: pointer;">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalLeadsPages > 1): ?>
                <div style="display: flex; justify-content: center; gap: 6px; margin-top: 20px;">
                    <?php
                    $paginationParams = $_GET;
                    $paginationParams['tab'] = 'leads';
                    for ($p = 1; $p <= $totalLeadsPages; $p++) {
                        $paginationParams['lpage'] = $p;
                        $isActivePage = ($p === $leadsPage) ? 'background: var(--accent); color: #000; border-color: var(--accent);' : '';
                        echo '<a href="ai-agent.php?' . http_build_query($paginationParams) . '" class="btn" style="padding: 6px 12px; font-size: 12px; ' . $isActivePage . '">' . $p . '</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Tab switching
document.querySelectorAll('.ai-tab').forEach(function(tab) {
    tab.addEventListener('click', function() {
        var target = this.getAttribute('data-tab');

        document.querySelectorAll('.ai-tab').forEach(function(t) { t.classList.remove('active'); });
        document.querySelectorAll('.ai-tab-panel').forEach(function(p) { p.classList.remove('active'); });

        this.classList.add('active');
        document.getElementById('tab-' + target).classList.add('active');

        // Update URL for bookmark support
        var url = new URL(window.location);
        url.searchParams.set('tab', target);
        window.history.replaceState({}, '', url);
    });
});

function toggleSelectAll(checkbox) {
    document.querySelectorAll('.lead-checkbox').forEach(function(cb) {
        cb.checked = checkbox.checked;
    });
}
</script>

<?php include_once 'includes/footer.php'; ?>
