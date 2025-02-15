<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../app/auth/login.php");
    exit();
}

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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
</head>

<body>
    <div class="container">
        <h1>📜 My Articles</h1>
        <a href="create.php" class="btn btn-primary mb-3">➕ Create New Article</a>
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
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td>
                            <?php if (!empty($article['image'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($article['image']); ?>" width="100" height="80" style="object-fit:cover; border-radius:5px;">
                            <?php else: ?>
                                <span class="text-muted">No Image</span>
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
                            <a href="edit.php?id=<?php echo $article['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                            <a href="delete.php?id=<?php echo $article['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i class="fas fa-trash-alt"></i> Delete</a>
                            <a href="revisions.php?id=<?php echo $article['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-history"></i> Revisions</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="../index.php" class="btn btn-secondary">⬅️ Back to Profile</a>
    </div>

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
</body>
</html>