<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';

$editId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$isEdit = false;
$pkg = null;
$pkgTags = [];
$pkgDays = [];
$pkgHighlights = [];
$pkgInclusions = [];
$pkgExclusions = [];
$pkgPhotos = [];

if ($editId > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM tour_packages WHERE id = ?");
        $stmt->execute([$editId]);
        $pkg = $stmt->fetch();
        if ($pkg) {
            $isEdit = true;
            
            // Fetch tags
            $stmt = $pdo->prepare("SELECT * FROM package_tags WHERE package_id = ? ORDER BY id");
            $stmt->execute([$editId]);
            $pkgTags = $stmt->fetchAll();
            
            // Fetch days
            $stmt = $pdo->prepare("SELECT * FROM package_days WHERE package_id = ? ORDER BY day_number");
            $stmt->execute([$editId]);
            $pkgDays = $stmt->fetchAll();
            
            // Fetch highlights
            $stmt = $pdo->prepare("SELECT * FROM package_highlights WHERE package_id = ? ORDER BY sort_order");
            $stmt->execute([$editId]);
            $pkgHighlights = $stmt->fetchAll();
            
            // Fetch inclusions
            $stmt = $pdo->prepare("SELECT * FROM package_inclusions WHERE package_id = ? AND type = 'inclusion' ORDER BY sort_order");
            $stmt->execute([$editId]);
            $pkgInclusions = $stmt->fetchAll();
            
            // Fetch exclusions
            $stmt = $pdo->prepare("SELECT * FROM package_inclusions WHERE package_id = ? AND type = 'exclusion' ORDER BY sort_order");
            $stmt->execute([$editId]);
            $pkgExclusions = $stmt->fetchAll();
            
            // Fetch photos
            $stmt = $pdo->prepare("SELECT * FROM package_photos WHERE package_id = ? ORDER BY sort_order");
            $stmt->execute([$editId]);
            $pkgPhotos = $stmt->fetchAll();
        }
    } catch (PDOException $e) {
        // Package not found
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
    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
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
        min-height: 100px;
        resize: vertical;
    }
    select.form-control {
        cursor: pointer;
    }
    .form-hint {
        font-size: 11px;
        color: var(--fg3);
    }

    /* Dynamic Lists */
    .dynamic-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .dynamic-list-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dynamic-list-item .form-control {
        flex: 1;
    }
    .btn-remove-item {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid rgba(239,68,68,0.3);
        background: rgba(239,68,68,0.1);
        color: var(--danger);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    .btn-remove-item:hover {
        background: rgba(239,68,68,0.2);
    }
    .btn-add-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: var(--radius-int);
        border: 1px dashed var(--border);
        background: transparent;
        color: var(--fg2);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
        margin-top: 8px;
    }
    .btn-add-item:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: rgba(245, 197, 24, 0.05);
    }

    /* Day Cards */
    .day-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-int);
        padding: 16px;
        margin-bottom: 12px;
        position: relative;
    }
    .day-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .day-card-number {
        font-weight: 700;
        font-size: 13px;
        color: var(--accent);
    }

    /* File Upload */
    .file-upload-area {
        border: 2px dashed var(--border);
        border-radius: var(--radius-int);
        padding: 24px;
        text-align: center;
        color: var(--fg3);
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
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
    .hero-preview {
        margin-top: 12px;
    }
    .hero-preview img {
        max-width: 300px;
        max-height: 150px;
        border-radius: var(--radius-int);
        border: 1px solid var(--border);
        object-fit: cover;
    }
    .gallery-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 12px;
    }
    .gallery-preview-item {
        position: relative;
        width: 100px;
        height: 70px;
    }
    .gallery-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid var(--border);
    }
    .gallery-preview-item .remove-photo {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--danger);
        color: #fff;
        border: none;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    /* Form Actions */
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
        .form-row, .form-row-3 {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-page-header">
    <h1><?php echo $isEdit ? 'Edit Package' : 'Add New Package'; ?></h1>
    <a href="manage-packages.php">← Back to Packages</a>
</div>

<form action="save-package.php" method="POST" enctype="multipart/form-data" id="packageForm">
    <?php if ($isEdit): ?>
        <input type="hidden" name="package_id" value="<?php echo $editId; ?>">
    <?php endif; ?>

    <!-- Section 1: Basic Info -->
    <div class="form-section">
        <div class="form-section-title">Basic Information</div>
        <div class="form-row">
            <div class="form-group">
                <label for="title">Package Title *</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="e.g. Luxury Honeymoon at Adaaran Prestige" required value="<?php echo $isEdit ? htmlspecialchars($pkg['title']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="destination">Destination *</label>
                <select id="destination" name="destination" class="form-control" required>
                    <option value="" disabled <?php echo !$isEdit ? 'selected' : ''; ?>>Select destination</option>
                    <option value="singapore" <?php echo ($isEdit && $pkg['destination'] === 'singapore') ? 'selected' : ''; ?>>Singapore</option>
                    <option value="maldives" <?php echo ($isEdit && $pkg['destination'] === 'maldives') ? 'selected' : ''; ?>>Maldives</option>
                    <option value="bali" <?php echo ($isEdit && $pkg['destination'] === 'bali') ? 'selected' : ''; ?>>Bali</option>
                    <option value="japan" <?php echo ($isEdit && $pkg['destination'] === 'japan') ? 'selected' : ''; ?>>Japan</option>
                    <option value="kerala" <?php echo ($isEdit && $pkg['destination'] === 'kerala') ? 'selected' : ''; ?>>Kerala</option>
                </select>
            </div>
        </div>
        <div class="form-row-3">
            <div class="form-group">
                <label for="duration">Duration</label>
                <input type="text" id="duration" name="duration" class="form-control" placeholder="e.g. 4D/3N or 5 days & 4 nights" value="<?php echo $isEdit ? htmlspecialchars($pkg['duration']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" id="rating" name="rating" class="form-control" step="0.1" min="0" max="5" placeholder="4.8" value="<?php echo $isEdit ? htmlspecialchars($pkg['rating']) : '4.5'; ?>">
            </div>
            <div class="form-group">
                <label for="rating_count">Rating Count</label>
                <input type="number" id="rating_count" name="rating_count" class="form-control" min="0" placeholder="42" value="<?php echo $isEdit ? htmlspecialchars($pkg['rating_count']) : '0'; ?>">
            </div>
        </div>
        <div class="form-row-3">
            <div class="form-group">
                <label for="price">Price *</label>
                <input type="text" id="price" name="price" class="form-control" placeholder="e.g. INR 1,30,000" required value="<?php echo $isEdit ? htmlspecialchars($pkg['price']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="old_price">Old Price (Strikethrough)</label>
                <input type="text" id="old_price" name="old_price" class="form-control" placeholder="e.g. INR 1,60,000" value="<?php echo $isEdit ? htmlspecialchars($pkg['old_price']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="save_text">Save Text</label>
                <input type="text" id="save_text" name="save_text" class="form-control" placeholder="e.g. SAVE INR 30,000" value="<?php echo $isEdit ? htmlspecialchars($pkg['save_text']) : ''; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="description">Short Description</label>
            <textarea id="description" name="description" class="form-control" placeholder="A brief 1-2 line description of the package..."><?php echo $isEdit ? htmlspecialchars($pkg['description']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="overview">Overview</label>
            <textarea id="overview" name="overview" class="form-control" style="min-height: 140px;" placeholder="Detailed overview of the package..."><?php echo $isEdit ? htmlspecialchars($pkg['overview']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control" style="max-width: 200px;">
                <option value="active" <?php echo ($isEdit && $pkg['status'] === 'active') ? 'selected' : ''; ?>>Active (Published)</option>
                <option value="draft" <?php echo ($isEdit && $pkg['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
            </select>
        </div>
    </div>

    <!-- Section 2: Hero Image -->
    <div class="form-section">
        <div class="form-section-title">Hero / Background Image</div>
        <div class="file-upload-area">
            📁 Click or drag to upload hero image (JPG, PNG, WEBP)
            <input type="file" name="hero_image" accept="image/*" id="heroImageInput">
        </div>
        <?php if ($isEdit && !empty($pkg['hero_image'])): ?>
            <div class="hero-preview" id="heroPreview">
                <img src="<?php echo SITE_PATH; ?>/<?php echo htmlspecialchars($pkg['hero_image']); ?>" alt="Hero image">
                <div style="margin-top:6px;font-size:11px;color:var(--fg3);">Current hero image — upload a new one to replace it.</div>
            </div>
        <?php else: ?>
            <div class="hero-preview" id="heroPreview"></div>
        <?php endif; ?>
    </div>

    <!-- Section 3: Gallery Photos -->
    <div class="form-section">
        <div class="form-section-title">Gallery Photos (Multiple)</div>
        <div class="file-upload-area">
            📷 Click or drag to upload gallery photos (multiple allowed)
            <input type="file" name="gallery_photos[]" accept="image/*" multiple id="galleryInput">
        </div>
        <?php if ($isEdit && !empty($pkgPhotos)): ?>
            <div class="gallery-preview" id="existingGallery">
                <?php foreach ($pkgPhotos as $photo): 
                    $isExternal = (strpos($photo['image_path'], 'http://') === 0 || strpos($photo['image_path'], 'https://') === 0);
                    $imgUrl = $isExternal ? $photo['image_path'] : SITE_PATH . '/' . $photo['image_path'];
                    $localFile = __DIR__ . '/../' . $photo['image_path'];
                    if (!$isExternal && !file_exists($localFile)) {
                        continue; // Skip non-existent local files
                    }
                ?>
                    <div class="gallery-preview-item" id="photo-<?php echo $photo['id']; ?>">
                        <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($photo['alt_text']); ?>">
                        <button type="button" class="remove-photo" onclick="markPhotoForDeletion(<?php echo $photo['id']; ?>)">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="gallery-preview" id="galleryPreview"></div>
        <input type="hidden" name="delete_photos" id="deletePhotosInput" value="">
    </div>

    <!-- Section 4: Tags -->
    <div class="form-section">
        <div class="form-section-title">Tags</div>
        <div class="dynamic-list" id="tagsList">
            <?php if ($isEdit && !empty($pkgTags)): ?>
                <?php foreach ($pkgTags as $tag): ?>
                    <div class="dynamic-list-item">
                        <input type="text" name="tags[]" class="form-control" value="<?php echo htmlspecialchars($tag['tag_name']); ?>" placeholder="e.g. Honeymoon">
                        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="dynamic-list-item">
                    <input type="text" name="tags[]" class="form-control" placeholder="e.g. Honeymoon">
                    <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="btn-add-item" onclick="addTag()">+ Add Tag</button>
    </div>

    <!-- Section 5: Highlights -->
    <div class="form-section">
        <div class="form-section-title">Highlights</div>
        <div class="dynamic-list" id="highlightsList">
            <?php if ($isEdit && !empty($pkgHighlights)): ?>
                <?php foreach ($pkgHighlights as $hl): ?>
                    <div class="dynamic-list-item">
                        <input type="text" name="highlights[]" class="form-control" value="<?php echo htmlspecialchars($hl['highlight_text']); ?>" placeholder="e.g. All-inclusive dining">
                        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="dynamic-list-item">
                    <input type="text" name="highlights[]" class="form-control" placeholder="e.g. All-inclusive dining: breakfast, lunch, and dinner">
                    <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="btn-add-item" onclick="addHighlight()">+ Add Highlight</button>
    </div>

    <!-- Section 6: Day-wise Itinerary -->
    <div class="form-section">
        <div class="form-section-title">Day-wise Itinerary</div>
        <div id="daysList">
            <?php if ($isEdit && !empty($pkgDays)): ?>
                <?php foreach ($pkgDays as $day): ?>
                    <div class="day-card" data-day="<?php echo $day['day_number']; ?>">
                        <div class="day-card-header">
                            <span class="day-card-number">Day <?php echo str_pad($day['day_number'], 2, '0', STR_PAD_LEFT); ?></span>
                            <button type="button" class="btn-remove-item" onclick="removeDay(this)" style="width:28px;height:28px;">×</button>
                        </div>
                        <input type="hidden" name="day_number[]" value="<?php echo $day['day_number']; ?>">
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom:0">
                                <label>Day Title</label>
                                <input type="text" name="day_title[]" class="form-control" placeholder="e.g. Arrival" value="<?php echo htmlspecialchars($day['day_title']); ?>">
                            </div>
                            <div class="form-group" style="margin-bottom:0">
                                <label>Accommodation</label>
                                <input type="text" name="day_accommodation[]" class="form-control" placeholder="e.g. Hotel name" value="<?php echo htmlspecialchars($day['accommodation']); ?>">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:12px;margin-bottom:0">
                            <label>Day Content</label>
                            <textarea name="day_content[]" class="form-control" placeholder="Describe the day's activities..." style="min-height:80px;"><?php echo htmlspecialchars($day['day_content']); ?></textarea>
                        </div>
                        <div class="form-group" style="margin-top:12px;margin-bottom:0">
                            <label>Meals</label>
                            <input type="text" name="day_meals[]" class="form-control" placeholder="e.g. Breakfast, Lunch, Dinner" value="<?php echo htmlspecialchars($day['meals']); ?>">
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="day-card" data-day="1">
                    <div class="day-card-header">
                        <span class="day-card-number">Day 01</span>
                        <button type="button" class="btn-remove-item" onclick="removeDay(this)" style="width:28px;height:28px;">×</button>
                    </div>
                    <input type="hidden" name="day_number[]" value="1">
                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0">
                            <label>Day Title</label>
                            <input type="text" name="day_title[]" class="form-control" placeholder="e.g. Arrival">
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label>Accommodation</label>
                            <input type="text" name="day_accommodation[]" class="form-control" placeholder="e.g. Hotel name">
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:12px;margin-bottom:0">
                        <label>Day Content</label>
                        <textarea name="day_content[]" class="form-control" placeholder="Describe the day's activities..." style="min-height:80px;"></textarea>
                    </div>
                    <div class="form-group" style="margin-top:12px;margin-bottom:0">
                        <label>Meals</label>
                        <input type="text" name="day_meals[]" class="form-control" placeholder="e.g. Breakfast, Lunch, Dinner">
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="btn-add-item" onclick="addDay()" style="margin-top:12px;">+ Add More Days</button>
    </div>

    <!-- Section 7: Inclusions -->
    <div class="form-section">
        <div class="form-section-title">Inclusions (What's included in the package)</div>
        <div class="dynamic-list" id="inclusionsList">
            <?php if ($isEdit && !empty($pkgInclusions)): ?>
                <?php foreach ($pkgInclusions as $inc): ?>
                    <div class="dynamic-list-item">
                        <input type="text" name="inclusions[]" class="form-control" value="<?php echo htmlspecialchars($inc['item_text']); ?>" placeholder="e.g. Accommodation">
                        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="dynamic-list-item">
                    <input type="text" name="inclusions[]" class="form-control" placeholder="e.g. Accommodation">
                    <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="btn-add-item" onclick="addInclusion()">+ Add Inclusion</button>
    </div>

    <!-- Section 8: Exclusions -->
    <div class="form-section">
        <div class="form-section-title">Exclusions (What's NOT included)</div>
        <div class="dynamic-list" id="exclusionsList">
            <?php if ($isEdit && !empty($pkgExclusions)): ?>
                <?php foreach ($pkgExclusions as $exc): ?>
                    <div class="dynamic-list-item">
                        <input type="text" name="exclusions[]" class="form-control" value="<?php echo htmlspecialchars($exc['item_text']); ?>" placeholder="e.g. Flights">
                        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="dynamic-list-item">
                    <input type="text" name="exclusions[]" class="form-control" placeholder="e.g. Flights">
                    <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="btn-add-item" onclick="addExclusion()">+ Add Exclusion</button>
    </div>

    <!-- Form Actions -->
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update Package' : 'Create Package'; ?></button>
        <a href="manage-packages.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<script>
// ─── Dynamic Add Functions ───

function addTag() {
    const list = document.getElementById('tagsList');
    const item = document.createElement('div');
    item.className = 'dynamic-list-item';
    item.innerHTML = `
        <input type="text" name="tags[]" class="form-control" placeholder="e.g. Beach Villa">
        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
    `;
    list.appendChild(item);
    item.querySelector('input').focus();
}

function addHighlight() {
    const list = document.getElementById('highlightsList');
    const item = document.createElement('div');
    item.className = 'dynamic-list-item';
    item.innerHTML = `
        <input type="text" name="highlights[]" class="form-control" placeholder="e.g. Complimentary transfers">
        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
    `;
    list.appendChild(item);
    item.querySelector('input').focus();
}

function addInclusion() {
    const list = document.getElementById('inclusionsList');
    const item = document.createElement('div');
    item.className = 'dynamic-list-item';
    item.innerHTML = `
        <input type="text" name="inclusions[]" class="form-control" placeholder="e.g. Airport Transfers">
        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
    `;
    list.appendChild(item);
    item.querySelector('input').focus();
}

function addExclusion() {
    const list = document.getElementById('exclusionsList');
    const item = document.createElement('div');
    item.className = 'dynamic-list-item';
    item.innerHTML = `
        <input type="text" name="exclusions[]" class="form-control" placeholder="e.g. Visa charges">
        <button type="button" class="btn-remove-item" onclick="this.parentElement.remove()">×</button>
    `;
    list.appendChild(item);
    item.querySelector('input').focus();
}

function addDay() {
    const daysList = document.getElementById('daysList');
    const dayCards = daysList.querySelectorAll('.day-card');
    const nextDay = dayCards.length + 1;
    const dayNum = String(nextDay).padStart(2, '0');
    
    const card = document.createElement('div');
    card.className = 'day-card';
    card.setAttribute('data-day', nextDay);
    card.innerHTML = `
        <div class="day-card-header">
            <span class="day-card-number">Day ${dayNum}</span>
            <button type="button" class="btn-remove-item" onclick="removeDay(this)" style="width:28px;height:28px;">×</button>
        </div>
        <input type="hidden" name="day_number[]" value="${nextDay}">
        <div class="form-row">
            <div class="form-group" style="margin-bottom:0">
                <label>Day Title</label>
                <input type="text" name="day_title[]" class="form-control" placeholder="e.g. Day at leisure">
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label>Accommodation</label>
                <input type="text" name="day_accommodation[]" class="form-control" placeholder="e.g. Hotel name">
            </div>
        </div>
        <div class="form-group" style="margin-top:12px;margin-bottom:0">
            <label>Day Content</label>
            <textarea name="day_content[]" class="form-control" placeholder="Describe the day's activities..." style="min-height:80px;"></textarea>
        </div>
        <div class="form-group" style="margin-top:12px;margin-bottom:0">
            <label>Meals</label>
            <input type="text" name="day_meals[]" class="form-control" placeholder="e.g. Breakfast, Lunch, Dinner">
        </div>
    `;
    daysList.appendChild(card);
    card.querySelector('input[name="day_title[]"]').focus();
    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function removeDay(btn) {
    const card = btn.closest('.day-card');
    card.remove();
    // Re-number days
    const dayCards = document.querySelectorAll('#daysList .day-card');
    dayCards.forEach((c, i) => {
        const num = i + 1;
        c.setAttribute('data-day', num);
        c.querySelector('.day-card-number').textContent = 'Day ' + String(num).padStart(2, '0');
        c.querySelector('input[name="day_number[]"]').value = num;
    });
}

// ─── Photo Deletion ───
var photosToDelete = [];

function markPhotoForDeletion(photoId) {
    if (confirm('Remove this photo?')) {
        photosToDelete.push(photoId);
        document.getElementById('deletePhotosInput').value = photosToDelete.join(',');
        document.getElementById('photo-' + photoId).style.display = 'none';
    }
}

// ─── File Preview ───
document.getElementById('heroImageInput').addEventListener('change', function(e) {
    const preview = document.getElementById('heroPreview');
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

document.getElementById('galleryInput').addEventListener('change', function(e) {
    const preview = document.getElementById('galleryPreview');
    preview.innerHTML = '';
    if (this.files) {
        Array.from(this.files).forEach(file => {
            const div = document.createElement('div');
            div.className = 'gallery-preview-item';
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            div.appendChild(img);
            preview.appendChild(div);
        });
    }
});

// ─── Auto-calculate Savings ───
const priceInput = document.getElementById('price');
const oldPriceInput = document.getElementById('old_price');
const saveTextInput = document.getElementById('save_text');

function autoCalculateSavings() {
    const priceStr = priceInput.value || '';
    const oldPriceStr = oldPriceInput.value || '';
    
    // Extract only digits
    const priceNum = parseInt(priceStr.replace(/\D/g, '')) || 0;
    const oldPriceNum = parseInt(oldPriceStr.replace(/\D/g, '')) || 0;
    
    if (oldPriceNum > priceNum) {
        const diff = oldPriceNum - priceNum;
        
        // Format the difference with commas
        const formattedDiff = new Intl.NumberFormat('en-IN').format(diff);
        
        // Determine currency prefix
        let prefix = 'SAVE INR ';
        if (priceStr.includes('₹') || oldPriceStr.includes('₹')) {
            prefix = 'SAVE ₹';
        }
        
        saveTextInput.value = prefix + formattedDiff;
    }
}

priceInput.addEventListener('input', autoCalculateSavings);
oldPriceInput.addEventListener('input', autoCalculateSavings);
</script>

<?php include_once 'includes/footer.php'; ?>
