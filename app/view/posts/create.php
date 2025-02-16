<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center text-primary">Create New Article</h2>

        <?php if (isset($data['error_message'])) : ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($data['error_message']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars(BASEURL) ?>/posts/processCreate" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter article title" required
                    maxlength="255">
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="5" placeholder="Write your content here"
                    required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Keywords</label>
                <input type="text" name="keywords" class="form-control"
                    placeholder="Enter keywords (separated by spaces)">
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
            </div>

            <div class="mb-3 text-center">
                <img id="imagePreview" src="#" class="img-fluid rounded shadow"
                    style="display:none; max-width: 300px; height: auto; margin-top: 10px;">
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?= htmlspecialchars(BASEURL) ?>/home" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>