<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../app/auth/login.php");
    exit();
}

// Pastikan hanya author yang bisa masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'author') {
    header("Location: ../index.php");
    exit();
}

require_once '../../app/config/config.php';
require_once '../../app/core/database.php';

$conn = getConnection();
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>My Articles</h1>
        <a href="create.php" class="btn btn-primary mb-3">Create New Article</a>
        <table class="table table-bordered">
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
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td>
                            <?php if (!empty($article['image'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($article['image']); ?>" width="100" height="80" style="object-fit:cover;">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>

                        <td>
                            <select class="form-select status-dropdown" data-id="<?php echo $article['id']; ?>">
                                <option value="draft" <?php if ($article['status'] == 'draft') echo 'selected'; ?>>Draft</option>
                                <option value="published" <?php if ($article['status'] == 'published') echo 'selected'; ?>>Published</option>
                            </select>
                        </td>

                        <td><?php echo $article['created_at']; ?></td>

                        <td class="published-at">
                            <?php echo $article['published_at'] ? date('F j, Y, g:i a', strtotime($article['published_at'])) : '-'; ?>
                        </td>

                        <td>
                            <a href="edit.php?id=<?php echo $article['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="delete.php?id=<?php echo $article['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                            <a href="revisions.php?id=<?php echo $article['id']; ?>" class="btn btn-info">View Revisions</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
        <a href="../index.php" class="btn btn-secondary">Back to Profile</a>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".status-dropdown").change(function() {
            let postId = $(this).data("id");
            let newStatus = $(this).val();
            let publishedAtElement = $(this).closest("tr").find(".published-at");

            $.ajax({
                url: "update_status.php",
                type: "POST",
                data: {
                    id: postId,
                    status: newStatus
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        if (newStatus === "published") {
                            publishedAtElement.text(new Date().toLocaleString("id-ID", {
                                timeZone: "Asia/Jakarta"
                            }));
                        } else {
                            publishedAtElement.text("-");
                        }
                    } else {
                        alert("Error: " + response.error);
                    }
                },
                error: function() {
                    alert("An error occurred.");
                }
            });
        });
    });
</script>

</html>