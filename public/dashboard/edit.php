<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../app/config/config.php';
require_once '../../app/core/database.php';

$conn = getConnection();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header("Location: index.php");
    exit();
}

if (!empty($_FILES['image']['name'])) {
    $targetDir = "../../public/uploads/";
    $fileName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        if (!empty($article['image']) && file_exists($targetDir . $article['image'])) {
            unlink($targetDir . $article['image']);
        }
        $image = $fileName;
    } else {
        $image = $article['image'];
    }
} else {
    $image = $article['image'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $keywords = trim($_POST['keywords']);

    $stmt = $conn->prepare("SELECT title, content, image FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    $oldArticle = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("INSERT INTO revisions (post_id, user_id, old_title, old_content, old_image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id, $user_id, $oldArticle['title'], $oldArticle['content'], $oldArticle['image']]);

    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, keywords = ?, image = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $content, $keywords, $image, $id, $user_id]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
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
</head>
<body>
    <div class="card">
        <h2 class="text-center">Edit Article</h2>
        <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($article['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="5" required><?php echo htmlspecialchars($article['content']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Keywords</label>
                <input type="text" name="keywords" class="form-control" value="<?php echo htmlspecialchars($article['keywords']); ?>">
            </div>
            <div class="mb-3 text-center">
                <label class="form-label">Current Image:</label><br>
                <?php if (!empty($article['image'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($article['image']); ?>" width="200" height="150" class="img-fluid">
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
                <a href="index.php" class="btn btn-secondary w-50 ms-2">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
