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
        // Hapus gambar lama jika ada
        if (!empty($article['image']) && file_exists($targetDir . $article['image'])) {
            unlink($targetDir . $article['image']);
        }
        $image = $fileName;
    } else {
        $image = $article['image']; // Jika gagal upload, pakai gambar lama
    }
} else {
    $image = $article['image']; // Jika tidak upload gambar baru, pakai gambar lama
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $keywords = trim($_POST['keywords']);

    // Ambil data lama sebelum di-update
    $stmt = $conn->prepare("SELECT title, content, image FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    $oldArticle = $stmt->fetch(PDO::FETCH_ASSOC);

    // Simpan riwayat revisi
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
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Article</h2>
        <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($article['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Content</label>
                <textarea name="content" class="form-control" required><?php echo htmlspecialchars($article['content']); ?></textarea>
            </div>
            <div class="mb-3">
                <label>Keywords</label>
                <input type="text" name="keywords" class="form-control" value="<?php echo htmlspecialchars($article['keywords']); ?>">
            </div>

            <!-- ✅ Preview Gambar -->
            <div class="mb-3">
                <label>Current Image:</label><br>
                <?php if (!empty($article['image'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($article['image']); ?>" width="150" height="100" style="object-fit:cover;"><br>
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </div>

            <!-- ✅ Upload Gambar Baru -->
            <div class="mb-3">
                <label>Change Image (Optional)</label>
                <input type="file" name="image" class="form-control">
            </div>

            <!-- ✅ Tambahkan Button Submit -->
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>