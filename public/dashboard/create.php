<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'author') {
    header("Location: ../index.php");
    exit();
}


require_once '../../app/config/config.php';
require_once '../../app/core/database.php';

$image = null;
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getConnection();
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $keywords = trim($_POST['keywords']);
    $status = 'draft';

    // 🖼️ Cek apakah ada gambar yang diupload
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../uploads/'; // ✅ Perbaiki path ke uploads
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $imageName;
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        // 🔍 Validasi file gambar
        if (!in_array($imageFileType, $allowedTypes)) {
            $error_message = "Error: Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) { // Maksimum 2MB
            $error_message = "Error: Ukuran gambar terlalu besar (maksimal 2MB).";
        } elseif (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $error_message = "Error: Gagal mengupload gambar.";
        } else {
            $image = $imageName;
        }
    }

    // Jika tidak ada error, simpan data ke database
    if (empty($error_message)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, keywords, status, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $title, $content, $keywords, $status, $image]);

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Article</title>
</head>

<body>
    <h2>Create New Article</h2>

    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="create.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Title" required><br>
        <textarea name="content" placeholder="Content" required></textarea><br>
        <input type="text" name="keywords" placeholder="Keywords"><br>
        <input type="file" name="image" accept="image/*" onchange="previewImage(event)"><br>

        <!-- ✅ Tampilkan preview gambar -->
        <img id="imagePreview" src="" style="display:none; width:200px; height:auto; margin-top:10px;"><br>

        <button type="submit">Save</button>
    </form>

    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById("imagePreview");
            imagePreview.src = URL.createObjectURL(event.target.files[0]);
            imagePreview.style.display = "block";
        }
    </script>
</body>

</html>