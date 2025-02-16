<style>
    body {
        background-color: #f4f6f9;
        font-family: Arial, sans-serif;
    }

    .container {
        margin-top: 50px;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    .table {
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead {
        background: #343a40;
        color: white;
    }

    .table tbody tr:hover {
        background: #f1f1f1;
    }

    .btn-sm {
        font-size: 0.9rem;
        padding: 5px 10px;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container">
    <h1>📜 My Articles</h1>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['success_message']);
            unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>
    <a href="<?= BASEURL; ?>/posts/create" class="btn btn-primary mb-3">➕ Create New Article</a>
    <table class="table table-striped table-bordered text-center">
        <thead>
            <tr>
                <th>Title</th>
                <th>Image</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Published At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['articles'] as $article): ?>
                <tr>
                    <td><?php echo htmlspecialchars($article['title']); ?></td>
                    <td>
                        <?php if (!empty($article['image'])): ?>
                            <img src="<?= BASEURL . '/uploads/' . htmlspecialchars($article['image']); ?>" alt="Article image"
                                width="100" height="80" style="object-fit: cover; border-radius: 5px;"
                                onerror="this.onerror=null; this.src='<?= BASEURL ?>/public/img/no-image.jpg';">
                        <?php else: ?>
                            <span class="text-muted">No Image</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <form action="<?= htmlspecialchars(BASEURL) ?>/posts/updateStatus" method="POST"
                            enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($article['id']) ?>">
                            <label for="status">Status:</label>
                            <select name="status" id="status">
                                <option value="draft" <?= ($article['status'] == 'draft') ? 'selected' : '' ?>>Draft
                                </option>
                                <option value="published" <?= ($article['status'] == 'published') ? 'selected' : '' ?>>
                                    Published</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update Status</button>
                        </form>
                    </td>

                    <td><?php echo htmlspecialchars($article['created_at']); ?></td>

                    <td class="published-at">
                        <?php echo $article['published_at'] ? date('F j, Y, g:i a', strtotime($article['published_at'])) : '-'; ?>
                    </td>
                    <td>
                        <a href="<?= BASEURL ?>/posts/edit/<?php echo htmlspecialchars($article['slug']); ?>"
                            class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        <a href="<?= BASEURL ?>/posts/revisions/<?php echo htmlspecialchars($article['slug']); ?>"
                            class="btn btn-info btn-sm"><i class="fas fa-history"></i> Revisions</a>
                        <a href="<?= BASEURL ?>/posts/delete/<?php echo htmlspecialchars($article['slug']); ?>"
                            class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i
                                class="fas fa-trash-alt"></i> Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="<?= BASEURL ?>/home" class="btn btn-secondary">⬅️ Back to Home</a>
</div>