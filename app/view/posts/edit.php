<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        max-width: 700px;
        margin: auto;
        margin-top: 50px;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    img {
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="card">
    <h2 class="text-center">Edit Article</h2>
    <form action="<?= htmlspecialchars(BASEURL) ?>/posts/processEdit" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data['csrf_token']) ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars($data['article']['id']) ?>">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control"
                value="<?php echo htmlspecialchars($data['article']['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="5"
                required><?php echo htmlspecialchars($data['article']['content']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Keywords</label>
            <input type="text" name="keywords" class="form-control"
                value="<?php echo htmlspecialchars($data['article']['keywords']); ?>">
        </div>
        <div class="mb-3 text-center">
            <label class="form-label">Current Image:</label><br>
            <?php if (!empty($data['article']['image'])): ?>
                <img src="<?= BASEURL . '/uploads/' . htmlspecialchars($data['article']['image']); ?>"
                    alt="Current article image" width="200" height="150" class="img-fluid"
                    onerror="this.onerror=null; this.src='<?= BASEURL ?>/public/img/no-image.jpg';">
            <?php else: ?>
                <p class="text-muted">No Image</p>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Change Image (Optional)</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary w-50">Save Changes</button>
            <a href="<?= BASEURL ?>/posts/index" class="btn btn-secondary w-50 ms-2">Cancel</a>
        </div>
    </form>
</div>