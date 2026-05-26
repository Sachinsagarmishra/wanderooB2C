<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';
include_once 'includes/media-picker.php';

$editId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$isEdit = false;
$pkg = null;
$pkgTags = [];
$pkgDays = [];
$pkgHighlights = [];
$pkgInclusions = [];
$pkgExclusions = [];
$pkgPhotos = [];
$pkgDayImages = [];

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

            $stmt = $pdo->prepare("SELECT * FROM package_day_images WHERE package_id = ? ORDER BY day_number, sort_order");
            $stmt->execute([$editId]);
            foreach ($stmt->fetchAll() as $dayImage) {
                $pkgDayImages[intval($dayImage['day_number'])][] = $dayImage;
            }
            
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
    .day-media-tools {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .day-media-list,
    .day-upload-preview {
        margin-top: 10px;
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
                    <?php
                    try {
                        $stmtDests = $pdo->query("SELECT slug, name FROM destinations ORDER BY sort_order, name");
                        while ($destRow = $stmtDests->fetch()) {
                            $selected = ($isEdit && $pkg['destination'] === $destRow['slug']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($destRow['slug']) . '" ' . $selected . '>' . htmlspecialchars($destRow['name']) . '</option>';
                        }
                    } catch (Exception $e) {
                        echo '<option value="singapore" ' . ($isEdit && $pkg['destination'] === 'singapore' ? 'selected' : '') . '>Singapore</option>';
                        echo '<option value="maldives" ' . ($isEdit && $pkg['destination'] === 'maldives' ? 'selected' : '') . '>Maldives</option>';
                        echo '<option value="bali" ' . ($isEdit && $pkg['destination'] === 'bali' ? 'selected' : '') . '>Bali</option>';
                        echo '<option value="japan" ' . ($isEdit && $pkg['destination'] === 'japan' ? 'selected' : '') . '>Japan</option>';
                        echo '<option value="kerala" ' . ($isEdit && $pkg['destination'] === 'kerala' ? 'selected' : '') . '>Kerala</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="slug">SEO URL Slug</label>
            <input type="text" id="slug" name="slug" class="form-control" placeholder="e.g. luxury-maldives-honeymoon-package" value="<?php echo $isEdit ? htmlspecialchars($pkg['slug']) : ''; ?>">
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

    <div class="form-section">
        <div class="form-section-title">SEO Settings</div>
        <div class="form-group">
            <label for="meta_title">Meta Title</label>
            <input type="text" id="meta_title" name="meta_title" class="form-control" maxlength="255" placeholder="e.g. Maldives Honeymoon Package with Water Villa" value="<?php echo $isEdit ? htmlspecialchars($pkg['meta_title'] ?? '') : ''; ?>">
        </div>
        <div class="form-group">
            <label for="meta_description">Meta Description</label>
            <textarea id="meta_description" name="meta_description" class="form-control" maxlength="320" placeholder="Write a Google-friendly 150-160 character description..."><?php echo $isEdit ? htmlspecialchars($pkg['meta_description'] ?? '') : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="focus_keywords">Focus Keywords</label>
            <input type="text" id="focus_keywords" name="focus_keywords" class="form-control" placeholder="e.g. Maldives honeymoon package, water villa Maldives, luxury Maldives trip" value="<?php echo $isEdit ? htmlspecialchars($pkg['focus_keywords'] ?? '') : ''; ?>">
        </div>
    </div>

    <!-- Section 2: Hero Image -->
    <div class="form-section">
        <div class="form-section-title">Hero / Background Image</div>
        <input type="hidden" name="selected_hero_image" id="selectedHeroImage" value="">
        <div class="media-picker-actions">
            <button type="button" class="btn btn-secondary" onclick="openMediaPicker('selectedHeroImage', 'heroPreview')">Select Existing Media</button>
        </div>
        <div class="file-upload-area">
            📁 Click or drag to upload hero image (JPG, PNG, WEBP)
            <input type="file" name="hero_image" accept="image/*" id="heroImageInput">
        </div>
        <div class="form-group">
            <label for="hero_image_alt">Hero Image Alt Tag</label>
            <input type="text" id="hero_image_alt" name="hero_image_alt" class="form-control" placeholder="e.g. Maldives water villa honeymoon package" value="<?php echo $isEdit ? htmlspecialchars($pkg['hero_image_alt'] ?? '') : ''; ?>">
        </div>
        <?php if ($isEdit && !empty($pkg['hero_image'])): ?>
            <div class="hero-preview" id="heroPreview">
                <img src="<?php echo SITE_PATH; ?>/<?php echo htmlspecialchars($pkg['hero_image']); ?>" alt="<?php echo htmlspecialchars($pkg['hero_image_alt'] ?? 'Hero image'); ?>">
                <div style="margin-top:6px;font-size:11px;color:var(--fg3);">Current hero image — upload a new one to replace it.</div>
            </div>
        <?php else: ?>
            <div class="hero-preview" id="heroPreview"></div>
        <?php endif; ?>
    </div>

    <!-- Section 3: Gallery Photos -->
    <div class="form-section">
        <div class="form-section-title">Gallery Photos (Multiple)</div>
        <div class="media-picker-actions">
            <button type="button" class="btn btn-secondary" onclick="openMediaPicker('selectedGalleryInput', 'selectedGalleryMedia', 'gallery')">Select Existing Media</button>
        </div>
        <input type="hidden" id="selectedGalleryInput" value="">
        <div class="selected-media-list" id="selectedGalleryMedia"></div>
        <div class="file-upload-area">
            📷 Click or drag to upload gallery photos (multiple allowed)
            <input type="file" name="gallery_photos[]" accept="image/*" multiple id="galleryInput">
        </div>
        <div class="form-group">
            <label for="gallery_upload_alt">Alt Tag For Newly Uploaded Photos</label>
            <input type="text" id="gallery_upload_alt" name="gallery_upload_alt" class="form-control" placeholder="Used for newly uploaded gallery photos. Existing selected media has per-image alt below.">
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
                        <input type="text" name="existing_photo_alt[<?php echo $photo['id']; ?>]" class="form-control" value="<?php echo htmlspecialchars($photo['alt_text']); ?>" placeholder="Alt tag" style="margin-top:8px;font-size:11px;padding:7px;">
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
                            <label>Day Images</label>
                            <div class="day-media-tools">
                                <button type="button" class="btn btn-secondary" onclick="openMediaPicker('dayMediaTarget_<?php echo $day['day_number']; ?>', 'daySelectedMedia_<?php echo $day['day_number']; ?>', 'gallery')">Select Existing Media</button>
                                <div class="file-upload-area" style="margin:0;padding:10px 14px;min-width:220px;">
                                    📷 Upload day images
                                    <input type="file" name="day_images[<?php echo $day['day_number']; ?>][]" accept="image/*" multiple onchange="previewDayUploads(this)">
                                </div>
                            </div>
                            <input type="text" name="day_upload_alt[<?php echo $day['day_number']; ?>]" class="form-control" style="margin-top:10px;" placeholder="Alt tag for newly uploaded day images">
                            <input type="hidden" id="dayMediaTarget_<?php echo $day['day_number']; ?>" value="">
                            <div id="daySelectedMedia_<?php echo $day['day_number']; ?>" class="selected-media-list day-media-list" data-path-name="day_existing_image_paths[<?php echo $day['day_number']; ?>][]" data-alt-name="day_existing_image_alt[<?php echo $day['day_number']; ?>][]">
                                <?php foreach (($pkgDayImages[intval($day['day_number'])] ?? []) as $dayImage): ?>
                                    <?php $dayImageUrl = (strpos($dayImage['image_path'], 'http://') === 0 || strpos($dayImage['image_path'], 'https://') === 0) ? $dayImage['image_path'] : SITE_PATH . '/' . $dayImage['image_path']; ?>
                                    <div class="selected-media-row">
                                        <img src="<?php echo htmlspecialchars($dayImageUrl); ?>" alt="Selected day media">
                                        <div>
                                            <input type="hidden" name="day_existing_image_paths[<?php echo $day['day_number']; ?>][]" value="<?php echo htmlspecialchars($dayImage['image_path']); ?>" data-field="day-path">
                                            <input type="text" name="day_existing_image_alt[<?php echo $day['day_number']; ?>][]" class="form-control" value="<?php echo htmlspecialchars($dayImage['alt_text'] ?? ''); ?>" placeholder="Alt tag for this image" data-field="day-alt">
                                            <div style="font-size:11px;color:var(--fg3);margin-top:5px;overflow-wrap:anywhere;"><?php echo htmlspecialchars($dayImage['image_path']); ?></div>
                                        </div>
                                        <button type="button" class="btn-remove-item" onclick="this.closest('.selected-media-row').remove()">×</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="gallery-preview day-upload-preview"></div>
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
                        <label>Day Images</label>
                        <div class="day-media-tools">
                            <button type="button" class="btn btn-secondary" onclick="openMediaPicker('dayMediaTarget_1', 'daySelectedMedia_1', 'gallery')">Select Existing Media</button>
                            <div class="file-upload-area" style="margin:0;padding:10px 14px;min-width:220px;">
                                📷 Upload day images
                                <input type="file" name="day_images[1][]" accept="image/*" multiple onchange="previewDayUploads(this)">
                            </div>
                        </div>
                        <input type="text" name="day_upload_alt[1]" class="form-control" style="margin-top:10px;" placeholder="Alt tag for newly uploaded day images">
                        <input type="hidden" id="dayMediaTarget_1" value="">
                        <div id="daySelectedMedia_1" class="selected-media-list day-media-list" data-path-name="day_existing_image_paths[1][]" data-alt-name="day_existing_image_alt[1][]"></div>
                        <div class="gallery-preview day-upload-preview"></div>
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
const packageTitleInput = document.getElementById('title');
const packageSlugInput = document.getElementById('slug');

function makeSeoSlug(value) {
    return value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
}

if (packageTitleInput && packageSlugInput) {
    packageTitleInput.addEventListener('input', function() {
        <?php if (!$isEdit): ?>
        packageSlugInput.value = makeSeoSlug(packageTitleInput.value);
        <?php endif; ?>
    });
}

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
            <label>Day Images</label>
            <div class="day-media-tools">
                <button type="button" class="btn btn-secondary" onclick="openMediaPicker('dayMediaTarget_${nextDay}', 'daySelectedMedia_${nextDay}', 'gallery')">Select Existing Media</button>
                <div class="file-upload-area" style="margin:0;padding:10px 14px;min-width:220px;">
                    📷 Upload day images
                    <input type="file" name="day_images[${nextDay}][]" accept="image/*" multiple onchange="previewDayUploads(this)">
                </div>
            </div>
            <input type="text" name="day_upload_alt[${nextDay}]" class="form-control" style="margin-top:10px;" placeholder="Alt tag for newly uploaded day images">
            <input type="hidden" id="dayMediaTarget_${nextDay}" value="">
            <div id="daySelectedMedia_${nextDay}" class="selected-media-list day-media-list" data-path-name="day_existing_image_paths[${nextDay}][]" data-alt-name="day_existing_image_alt[${nextDay}][]"></div>
            <div class="gallery-preview day-upload-preview"></div>
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
        const oldNum = c.getAttribute('data-day');
        c.setAttribute('data-day', num);
        c.querySelector('.day-card-number').textContent = 'Day ' + String(num).padStart(2, '0');
        c.querySelector('input[name="day_number[]"]').value = num;
        syncDayMediaFields(c, num, oldNum);
    });
}

function syncDayMediaFields(card, num, oldNum) {
    const target = card.querySelector(`#dayMediaTarget_${oldNum}`) || card.querySelector('input[id^="dayMediaTarget_"]');
    const list = card.querySelector(`#daySelectedMedia_${oldNum}`) || card.querySelector('.day-media-list');
    if (target) target.id = `dayMediaTarget_${num}`;
    if (list) {
        list.id = `daySelectedMedia_${num}`;
        list.dataset.pathName = `day_existing_image_paths[${num}][]`;
        list.dataset.altName = `day_existing_image_alt[${num}][]`;
    }
    const pickerBtn = card.querySelector('.day-media-tools .btn-secondary');
    if (pickerBtn) {
        pickerBtn.setAttribute('onclick', `openMediaPicker('dayMediaTarget_${num}', 'daySelectedMedia_${num}', 'gallery')`);
    }
    card.querySelectorAll('input[type="file"][name^="day_images"]').forEach(input => {
        input.name = `day_images[${num}][]`;
    });
    card.querySelectorAll('input[name^="day_upload_alt"]').forEach(input => {
        input.name = `day_upload_alt[${num}]`;
    });
    card.querySelectorAll('[data-field="day-path"]').forEach(input => {
        input.name = `day_existing_image_paths[${num}][]`;
    });
    card.querySelectorAll('[data-field="day-alt"]').forEach(input => {
        input.name = `day_existing_image_alt[${num}][]`;
    });
}

function previewDayUploads(input) {
    const preview = input.closest('.form-group').querySelector('.day-upload-preview');
    if (!preview) return;
    preview.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'gallery-preview-item';
            div.innerHTML = `<img src="${e.target.result}" alt="Day image preview">`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
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
