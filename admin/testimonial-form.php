<?php
require_once __DIR__ . '/../includes/db.php';
include_once 'includes/header.php';
include_once 'includes/media-picker.php';

$editId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$isEdit = false;
$testimonial = null;

if ($editId > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
        $stmt->execute([$editId]);
        $testimonial = $stmt->fetch();
        if ($testimonial) {
            $isEdit = true;
        }
    } catch (PDOException $e) {
        // Testimonial not found.
    }
}

function testimonial_form_image_url($path) {
    if (empty($path)) {
        return '';
    }
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    return SITE_PATH . '/' . ltrim($path, '/');
}
?>

<style>
    .form-page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
    .form-page-header a { color: var(--fg3); font-size: 12px; }
    .form-section { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius-main); padding: 24px; margin-bottom: 20px; }
    .form-section-title { font-size: 14px; font-weight: 700; color: var(--fg); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
    .form-group label { font-weight: 600; color: var(--fg2); font-size: 12px; }
    .form-control { background: var(--bg3); border: 1px solid var(--border); border-radius: var(--radius-int); padding: 10px 14px; color: var(--fg); outline: none; font-family: inherit; font-size: 13px; transition: border-color 0.2s; }
    .form-control:focus { border-color: var(--accent); }
    textarea.form-control { min-height: 140px; resize: vertical; }
    .file-upload-area { border: 2px dashed var(--border); border-radius: var(--radius-int); padding: 20px; text-align: center; color: var(--fg3); font-size: 12px; cursor: pointer; transition: all 0.2s; position: relative; margin-bottom: 12px; }
    .file-upload-area:hover { border-color: var(--accent); color: var(--accent); }
    .file-upload-area input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
    .image-preview img { width: 96px; height: 96px; border-radius: 50%; border: 1px solid var(--border); object-fit: cover; }
    .form-actions { display: flex; gap: 12px; margin-top: 24px; }
    .form-actions .btn { padding: 12px 24px; font-size: 13px; }
    .btn-secondary { background: var(--bg3); border: 1px solid var(--border); color: var(--fg2); }
    @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } }
</style>

<div class="form-page-header">
    <h1><?php echo $isEdit ? 'Edit Testimonial' : 'Add New Testimonial'; ?></h1>
    <a href="manage-testimonials.php">← Back to Testimonials</a>
</div>

<form action="save-testimonial.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="testimonial_id" value="<?php echo $isEdit ? intval($testimonial['id']) : 0; ?>">

    <div class="form-section">
        <div class="form-section-title">Testimonial Details</div>

        <div class="form-row">
            <div class="form-group">
                <label for="customer_name">Customer Name *</label>
                <input type="text" id="customer_name" name="customer_name" class="form-control" required value="<?php echo $isEdit ? htmlspecialchars($testimonial['customer_name']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="rating">Star Rating *</label>
                <select id="rating" name="rating" class="form-control" required>
                    <?php $currentRating = $isEdit ? intval($testimonial['rating']) : 5; ?>
                    <?php for ($rating = 5; $rating >= 1; $rating--): ?>
                        <option value="<?php echo $rating; ?>" <?php echo $currentRating === $rating ? 'selected' : ''; ?>><?php echo $rating; ?> Star<?php echo $rating > 1 ? 's' : ''; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="content">Review Content *</label>
            <textarea id="content" name="content" class="form-control" required><?php echo $isEdit ? htmlspecialchars($testimonial['content']) : ''; ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="<?php echo $isEdit ? intval($testimonial['sort_order']) : 0; ?>">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <?php $currentStatus = $isEdit ? $testimonial['status'] : 'active'; ?>
                    <option value="active" <?php echo $currentStatus === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="draft" <?php echo $currentStatus === 'draft' ? 'selected' : ''; ?>>Draft</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="form-section-title">Customer Image</div>
        <?php $currentImage = $isEdit ? ($testimonial['image_path'] ?? '') : ''; ?>
        <div class="file-upload-area">
            <input type="file" name="image" accept="image/*">
            <strong>Click to upload image</strong>
            <p>JPG, PNG, WEBP or GIF</p>
        </div>
        <div class="media-picker-actions">
            <input type="hidden" id="selected_image" name="selected_image" value="">
            <button type="button" class="btn btn-secondary" onclick="openMediaPicker('selected_image', 'testimonialImagePreview')">Select Existing Media</button>
        </div>
        <div class="form-group">
            <label for="image_alt">Image Alt Text</label>
            <input type="text" id="image_alt" name="image_alt" class="form-control" value="<?php echo $isEdit ? htmlspecialchars($testimonial['image_alt'] ?? '') : ''; ?>" placeholder="e.g. Happy Wanderoo customer">
        </div>
        <div id="testimonialImagePreview" class="image-preview">
            <?php if (!empty($currentImage)): ?>
                <img src="<?php echo htmlspecialchars(testimonial_form_image_url($currentImage)); ?>" alt="">
            <?php endif; ?>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update Testimonial' : 'Create Testimonial'; ?></button>
        <a href="manage-testimonials.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<?php include_once 'includes/footer.php'; ?>
