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

$stmt = $conn->prepare("SELECT * FROM revisions WHERE post_id = ? ORDER BY revised_at DESC");
$stmt->execute([$id]);
$revisions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Revisions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Article Revisions</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Revised At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($revisions as $revision): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($revision['old_title']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($revision['old_content'])); ?></td>
                        
                        <td><?php echo $revision['revised_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
