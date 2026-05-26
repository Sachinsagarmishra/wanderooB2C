<?php
if (!function_exists('get_admin_media_items')) {
    function get_admin_media_items($includeSvg = true) {
        $rootDir = realpath(__DIR__ . '/../..');
        $scanDirs = ['assets/img', 'uploads'];
        $extensions = $includeSvg ? ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'] : ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $items = [];

        foreach ($scanDirs as $dir) {
            $absDir = $rootDir . '/' . $dir;
            if (!is_dir($absDir)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($absDir, FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if (!$file->isFile()) {
                    continue;
                }

                $ext = strtolower($file->getExtension());
                if (!in_array($ext, $extensions, true)) {
                    continue;
                }

                $relative = str_replace($rootDir . '/', '', $file->getPathname());
                $relative = str_replace('\\', '/', $relative);
                $items[] = [
                    'path' => $relative,
                    'name' => $file->getBasename()
                ];
            }
        }

        usort($items, function ($a, $b) {
            return strcmp($a['path'], $b['path']);
        });

        return $items;
    }
}

$adminMediaItems = get_admin_media_items(true);
?>

<div class="media-picker-modal" id="mediaPickerModal" style="display:none;">
    <div class="media-picker-panel">
        <div class="media-picker-header">
            <h3>Select Media</h3>
            <button type="button" class="media-picker-close" onclick="closeMediaPicker()">×</button>
        </div>
        <div class="media-picker-grid">
            <?php if (empty($adminMediaItems)): ?>
                <div class="media-picker-empty">No media files found in assets/img or uploads.</div>
            <?php else: ?>
                <?php foreach ($adminMediaItems as $item): ?>
                    <button type="button" class="media-picker-item" data-path="<?php echo htmlspecialchars($item['path']); ?>" onclick="selectMediaItem(this)">
                        <span class="media-picker-thumb">
                            <img src="<?php echo SITE_PATH . '/' . htmlspecialchars($item['path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        </span>
                        <span class="media-picker-name"><?php echo htmlspecialchars($item['name']); ?></span>
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .media-picker-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 10px 0 12px;
    }
    .media-picker-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.72);
        align-items: center;
        justify-content: center;
        padding: 24px;
    }
    .media-picker-panel {
        width: min(960px, 100%);
        max-height: 86vh;
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius-main);
        box-shadow: var(--shadow-card);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .media-picker-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px;
        border-bottom: 1px solid var(--border);
    }
    .media-picker-header h3 {
        margin: 0;
        font-size: 16px;
        color: var(--fg);
    }
    .media-picker-close {
        width: 34px;
        height: 34px;
        border: 1px solid var(--border);
        border-radius: var(--radius-int);
        background: var(--bg3);
        color: var(--fg);
        cursor: pointer;
        font-size: 22px;
        line-height: 1;
    }
    .media-picker-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
        padding: 18px;
        overflow: auto;
    }
    .media-picker-item {
        border: 1px solid var(--border);
        border-radius: var(--radius-int);
        background: var(--bg3);
        padding: 8px;
        cursor: pointer;
        text-align: left;
        color: var(--fg2);
    }
    .media-picker-item:hover {
        border-color: var(--accent);
    }
    .media-picker-thumb {
        display: block;
        aspect-ratio: 4 / 3;
        background: var(--bg);
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 8px;
    }
    .media-picker-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .media-picker-name {
        display: block;
        font-size: 11px;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }
    .media-picker-empty {
        grid-column: 1 / -1;
        color: var(--fg3);
        padding: 40px;
        text-align: center;
    }
    .selected-media-list {
        display: grid;
        gap: 12px;
        margin-top: 12px;
    }
    .selected-media-row {
        display: grid;
        grid-template-columns: 82px 1fr auto;
        gap: 12px;
        align-items: center;
        border: 1px solid var(--border);
        border-radius: var(--radius-int);
        padding: 10px;
        background: var(--bg3);
    }
    .selected-media-row img {
        width: 82px;
        height: 62px;
        object-fit: cover;
        border-radius: 6px;
        background: var(--bg);
    }
</style>

<script>
let mediaPickerTarget = null;
let mediaPickerPreview = null;
let mediaPickerMode = 'single';

function openMediaPicker(targetInputId, previewId, mode = 'single') {
    mediaPickerTarget = document.getElementById(targetInputId);
    mediaPickerPreview = previewId ? document.getElementById(previewId) : null;
    mediaPickerMode = mode;
    const modal = document.getElementById('mediaPickerModal');
    modal.style.display = 'flex';
}

function closeMediaPicker() {
    const modal = document.getElementById('mediaPickerModal');
    modal.style.display = 'none';
}

function mediaUrl(path) {
    return '<?php echo SITE_PATH; ?>/' + path;
}

function selectMediaItem(button) {
    const path = button.getAttribute('data-path');
    if (!mediaPickerTarget) return;

    if (mediaPickerMode === 'gallery') {
        addSelectedGalleryMedia(path);
    } else {
        mediaPickerTarget.value = path;
        if (mediaPickerPreview) {
            mediaPickerPreview.innerHTML = '<img src="' + mediaUrl(path) + '" alt="Selected media"><div style="margin-top:6px;font-size:11px;color:var(--fg3);">Selected from media library.</div>';
        }
        closeMediaPicker();
    }
}

function addSelectedGalleryMedia(path) {
    const list = document.getElementById('selectedGalleryMedia');
    if (!list) return;

    const row = document.createElement('div');
    row.className = 'selected-media-row';
    row.innerHTML = `
        <img src="${mediaUrl(path)}" alt="Selected gallery media">
        <div>
            <input type="hidden" name="existing_gallery_paths[]" value="${path}">
            <input type="text" name="existing_gallery_alt[]" class="form-control" placeholder="Alt tag for this image">
            <div style="font-size:11px;color:var(--fg3);margin-top:5px;overflow-wrap:anywhere;">${path}</div>
        </div>
        <button type="button" class="btn-remove-item" onclick="this.closest('.selected-media-row').remove()">×</button>
    `;
    list.appendChild(row);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMediaPicker();
});
</script>
