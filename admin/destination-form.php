<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';
include_once 'includes/media-picker.php';

$editId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$isEdit = false;
$dest = null;

if ($editId > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM destinations WHERE id = ?");
        $stmt->execute([$editId]);
        $dest = $stmt->fetch();
        if ($dest) {
            $isEdit = true;
        }
    } catch (PDOException $e) {
        // Destination not found
    }
}
?>

<style>
    .form-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }
    .form-page-header a {
        color: var(--fg3);
        font-size: 12px;
    }
    .form-page-header a:hover {
        color: var(--fg);
    }
    .form-section {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius-main);
        padding: 24px;
        margin-bottom: 20px;
    }
    .form-section-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--fg);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
    }
    .form-group label {
        font-weight: 600;
        color: var(--fg2);
        font-size: 12px;
    }
    .form-control {
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: var(--radius-int);
        padding: 10px 14px;
        color: var(--fg);
        outline: none;
        font-family: inherit;
        font-size: 13px;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: var(--accent);
    }
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
    .file-upload-area {
        border: 2px dashed var(--border);
        border-radius: var(--radius-int);
        padding: 20px;
        text-align: center;
        color: var(--fg3);
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        margin-bottom: 12px;
    }
    .file-upload-area:hover {
        border-color: var(--accent);
        color: var(--accent);
    }
    .file-upload-area input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .image-preview img {
        max-width: 300px;
        max-height: 150px;
        border-radius: var(--radius-int);
        border: 1px solid var(--border);
        object-fit: cover;
    }
    .icon-preview img {
        max-width: 48px;
        max-height: 48px;
        border-radius: var(--radius-int);
        border: 1px solid var(--border);
        object-fit: contain;
        background: var(--bg3);
        padding: 4px;
    }
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }
    .form-actions .btn {
        padding: 12px 24px;
        font-size: 13px;
    }
    .btn-secondary {
        background: var(--bg3);
        border: 1px solid var(--border);
        color: var(--fg);
    }
    .btn-secondary:hover {
        background: var(--border);
    }
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-page-header">
    <h1><?php echo $isEdit ? 'Edit Destination' : 'Add New Destination'; ?></h1>
    <a href="manage-destinations.php">← Back to Destinations</a>
</div>

<form action="save-destination.php" method="POST" enctype="multipart/form-data" id="destinationForm">
    <?php csrf_input(); ?>
    <?php if ($isEdit): ?>
        <input type="hidden" name="destination_id" value="<?php echo $editId; ?>">
    <?php endif; ?>

    <div class="form-section">
        <div class="form-section-title">Destination Details</div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="name">Destination Name *</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Switzerland" required value="<?php echo $isEdit ? htmlspecialchars($dest['name']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="slug">SEO URL Slug * (auto-generated)</label>
                <input type="text" id="slug" name="slug" class="form-control" placeholder="e.g. switzerland" required value="<?php echo $isEdit ? htmlspecialchars($dest['slug']) : ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Page Meta Title *</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="e.g. Switzerland Tour Packages" required value="<?php echo $isEdit ? htmlspecialchars($dest['title']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="breadcrumb">Breadcrumb Name *</label>
                <input type="text" id="breadcrumb" name="breadcrumb" class="form-control" placeholder="e.g. Switzerland" required value="<?php echo $isEdit ? htmlspecialchars($dest['breadcrumb']) : ''; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="sort_order">Sort Order (lower numbers show first in menus)</label>
            <input type="number" id="sort_order" name="sort_order" class="form-control" placeholder="0" style="max-width: 150px;" value="<?php echo $isEdit ? intval($dest['sort_order']) : '0'; ?>">
        </div>

        <div class="form-group">
            <label for="description">About Description (appears on Destination page)</label>
            <textarea id="description" name="description" class="form-control" placeholder="Write details about this destination..."><?php echo $isEdit ? htmlspecialchars($dest['description']) : ''; ?></textarea>
        </div>
    </div>

    <div class="form-section">
        <div class="form-section-title">SEO Settings</div>
        <div class="form-group">
            <label for="meta_title">Meta Title</label>
            <input type="text" id="meta_title" name="meta_title" class="form-control" maxlength="255" placeholder="e.g. Best Switzerland Tour Packages from India" value="<?php echo $isEdit ? htmlspecialchars($dest['meta_title'] ?? '') : ''; ?>">
        </div>
        <div class="form-group">
            <label for="meta_description">Meta Description</label>
            <textarea id="meta_description" name="meta_description" class="form-control" maxlength="320" placeholder="Write a Google-friendly 150-160 character description..."><?php echo $isEdit ? htmlspecialchars($dest['meta_description'] ?? '') : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="focus_keywords">Focus Keywords</label>
            <input type="text" id="focus_keywords" name="focus_keywords" class="form-control" placeholder="e.g. Switzerland packages, Switzerland honeymoon, Europe tour" value="<?php echo $isEdit ? htmlspecialchars($dest['focus_keywords'] ?? '') : ''; ?>">
        </div>
    </div>

    <!-- Section 2: Banner Image -->
    <div class="form-section">
        <div class="form-section-title">Hero Banner Image *</div>
        <input type="hidden" name="selected_hero_bg" id="selectedHeroBg" value="">
        <div class="media-picker-actions">
            <button type="button" class="btn btn-secondary" onclick="openMediaPicker('selectedHeroBg', 'heroBgPreview')">Select Existing Media</button>
        </div>
        <div class="file-upload-area">
            📁 Click or drag to upload hero background banner (Recommended 1600x600 px)
            <input type="file" name="hero_bg" accept="image/*" id="heroBgInput">
        </div>
        <div class="form-group">
            <label for="hero_bg_alt">Hero Banner Alt Tag</label>
            <input type="text" id="hero_bg_alt" name="hero_bg_alt" class="form-control" placeholder="e.g. Maldives luxury beach holiday banner" value="<?php echo $isEdit ? htmlspecialchars($dest['hero_bg_alt'] ?? '') : ''; ?>">
        </div>
        
        <div class="image-preview" id="heroBgPreview">
            <?php if ($isEdit && !empty($dest['hero_bg'])): 
                $isBgExternal = (strpos($dest['hero_bg'], 'http://') === 0 || strpos($dest['hero_bg'], 'https://') === 0);
                $bgUrl = $isBgExternal ? $dest['hero_bg'] : SITE_PATH . '/' . $dest['hero_bg'];
            ?>
                <img src="<?php echo htmlspecialchars($bgUrl); ?>" alt="Banner Preview">
                <div style="margin-top:6px;font-size:11px;color:var(--fg3);">Current banner image — upload a new one to replace it.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Section 3: Dropdown Icon -->
    <div class="form-section">
        <div class="form-section-title">Dropdown Navigation Icon</div>
        <input type="hidden" name="selected_dropdown_icon" id="selectedDropdownIcon" value="">
        <div class="media-picker-actions">
            <button type="button" class="btn btn-secondary" onclick="openMediaPicker('selectedDropdownIcon', 'dropdownIconPreview')">Select Existing Media</button>
        </div>
        <div class="file-upload-area">
            📁 Click or drag to upload dropdown icon (Preferred format: SVG or PNG, max 48x48 px)
            <input type="file" name="dropdown_icon" accept="image/*,image/svg+xml" id="dropdownIconInput">
        </div>
        <div class="form-group">
            <label for="dropdown_icon_alt">Dropdown Icon Alt Tag</label>
            <input type="text" id="dropdown_icon_alt" name="dropdown_icon_alt" class="form-control" placeholder="e.g. Maldives destination icon" value="<?php echo $isEdit ? htmlspecialchars($dest['dropdown_icon_alt'] ?? '') : ''; ?>">
        </div>
        
        <div class="icon-preview" id="dropdownIconPreview">
            <?php if ($isEdit && !empty($dest['dropdown_icon'])): 
                $isIconExternal = (strpos($dest['dropdown_icon'], 'http://') === 0 || strpos($dest['dropdown_icon'], 'https://') === 0);
                $iconUrl = $isIconExternal ? $dest['dropdown_icon'] : SITE_PATH . '/' . $dest['dropdown_icon'];
            ?>
                <img src="<?php echo htmlspecialchars($iconUrl); ?>" alt="Icon Preview">
                <div style="margin-top:6px;font-size:11px;color:var(--fg3);">Current dropdown icon — upload a new one to replace it.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update Destination' : 'Create Destination'; ?></button>
        <a href="manage-destinations.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<script>
// Auto slug generation
const nameInput = document.getElementById('name');
const slugInput = document.getElementById('slug');
const titleInput = document.getElementById('title');
const breadcrumbInput = document.getElementById('breadcrumb');

nameInput.addEventListener('input', function() {
    <?php if (!$isEdit): ?>
    // Auto populate other fields on creation
    const val = nameInput.value;
    slugInput.value = val.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
    titleInput.value = val + " Packages";
    breadcrumbInput.value = val;
    <?php endif; ?>
});

// Image Previews
document.getElementById('heroBgInput').addEventListener('change', function(e) {
    const preview = document.getElementById('heroBgPreview');
    preview.innerHTML = '';
    if (this.files && this.files[0]) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(this.files[0]);
        img.style.maxWidth = '300px';
        img.style.maxHeight = '150px';
        img.style.borderRadius = '8px';
        img.style.border = '1px solid var(--border)';
        img.style.objectFit = 'cover';
        img.style.marginTop = '12px';
        preview.appendChild(img);
    }
});

document.getElementById('dropdownIconInput').addEventListener('change', function(e) {
    const preview = document.getElementById('dropdownIconPreview');
    preview.innerHTML = '';
    if (this.files && this.files[0]) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(this.files[0]);
        img.style.maxWidth = '48px';
        img.style.maxHeight = '48px';
        img.style.borderRadius = '4px';
        img.style.border = '1px solid var(--border)';
        img.style.objectFit = 'contain';
        img.style.background = 'var(--bg3)';
        img.style.padding = '4px';
        img.style.marginTop = '12px';
        preview.appendChild(img);
    }
});
</script>

<?php include_once 'includes/footer.php'; ?>
