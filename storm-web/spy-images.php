<?php
// spy-images.php: Admin page to view and manage all images in the images directory
// Path to images directory
$imagesDir = __DIR__ . '/images';
$imagesUrl = 'images';

// Handle delete single image
if (isset($_GET['delete'])) {
    $file = basename($_GET['delete']);
    $filePath = "$imagesDir/$file";
    if (is_file($filePath)) {
        unlink($filePath);
    }
    header('Location: spy-images.php');
    exit;
}

// Handle delete all images
if (isset($_GET['clear']) && $_GET['clear'] === 'all') {
    foreach (glob("$imagesDir/*.*") as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    header('Location: spy-images.php');
    exit;
}

// Get all images
$images = array_filter(glob("$imagesDir/*.*"), 'is_file');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spy Images Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body { padding: 2vw; background: #f8f9fa; }
        h1 { font-size: 2rem; margin-bottom: 1.5rem; text-align: center; }
        .img-actions { margin-bottom: 2rem; text-align: center; }
        .img-grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; }
        .img-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 180px;
            max-width: 98vw;
            margin-bottom: 1rem;
        }
        .img-thumb {
            width: 100%;
            max-width: 150px;
            height: auto;
            max-height: 150px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: box-shadow 0.2s;
            object-fit: contain;
        }
        .img-thumb:hover {
            box-shadow: 0 0 0 2px #0d6efd44;
        }
        @media (max-width: 600px) {
            .img-grid { gap: 0.4rem; }
            .img-container {
                width: 100vw;
                min-width: 100vw;
                max-width: 100vw;
                padding: 0.5rem;
                border-radius: 0;
            }
            .img-thumb {
                width: 98vw;
                max-width: 98vw;
                height: auto;
                max-height: 60vw;
            }
            .admin-header h1 { font-size: 1.3rem; }
            .admin-tagline { font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <h1>Spy Images Admin</h1>
    <div class="img-actions">
        <a href="spy-images.php?clear=all" class="btn btn-danger" onclick="return confirm('Delete ALL images?');">All Clear</a>
        <a href="panel.php" class="btn btn-secondary">Back to Admin Panel</a>
    </div>
    <div class="img-grid">
        <?php if (empty($images)): ?>
            <p>No images found.</p>
        <?php else: ?>
            <?php foreach ($images as $img): $imgName = basename($img); ?>
                <div class="img-container">
                    <img src="<?= $imagesUrl . '/' . $imgName ?>" class="img-thumb" alt="<?= $imgName ?>" onclick="showImageModal('<?= $imagesUrl . '/' . rawurlencode($imgName) ?>')">
                    <div>
                        <a href="spy-images.php?delete=<?= urlencode($imgName) ?>" class="btn btn-sm btn-danger mt-2" onclick="return confirm('Delete this image?');">Delete</a>
                    </div>
                    <div><small><?= $imgName ?></small></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

        <!-- Simple CSS/JS Modal for image preview -->
        <div id="imageModal" class="custom-modal">
            <span class="custom-modal-close" onclick="closeImageModal()">&times;</span>
            <img class="custom-modal-content" id="modalImage" alt="Preview">
            <div id="modalCaption" class="custom-modal-caption"></div>
        </div>

    <style>
        /* Simple Modal Styles */
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0; width: 100vw; height: 100vh;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
            align-items: center; justify-content: center;
        }
        .custom-modal-content {
            margin: auto;
            display: block;
            max-width: 90vw;
            max-height: 80vh;
            border-radius: 10px;
            box-shadow: 0 2px 8px #0002;
        }
        .custom-modal-close {
            position: absolute;
            top: 20px; right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10001;
        }
        .custom-modal-caption {
            text-align: center;
            color: #fff;
            margin-top: 10px;
        }
        @media (max-width: 600px) {
            .custom-modal-content { max-width: 98vw; max-height: 60vh; }
            .custom-modal-close { font-size: 32px; top: 10px; right: 15px; }
        }
    </style>
    <script>
        function showImageModal(src) {
            var modal = document.getElementById('imageModal');
            var modalImg = document.getElementById('modalImage');
            var caption = document.getElementById('modalCaption');
            modal.style.display = 'flex';
            modalImg.src = src;
           
        }
        function closeImageModal() {
            var modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.getElementById('modalImage').src = '';
        }
        // Close modal on outside click
        window.onclick = function(event) {
            var modal = document.getElementById('imageModal');
            if (event.target === modal) {
                closeImageModal();
            }
        }
    </script>
</body>
</html>
