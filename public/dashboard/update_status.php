<?php
session_start();
require_once '../../app/config/config.php';
require_once '../../app/core/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

$conn = getConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'], $_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $user_id = $_SESSION['user_id'];

    // Ambil status sebelumnya
    $stmt = $conn->prepare("SELECT status FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        echo json_encode(['success' => false, 'error' => 'Article not found']);
        exit();
    }

    // Perbarui status artikel
    $stmt = $conn->prepare("UPDATE posts SET status = ?, published_at = (CASE WHEN ? = 'published' THEN NOW() ELSE NULL END) WHERE id = ? AND user_id = ?");
    $stmt->execute([$status, $status, $id, $user_id]);

    // Catat perubahan status ke riwayat status
    $stmt = $conn->prepare("INSERT INTO post_status_history (post_id, status) VALUES (?, ?)");
    $stmt->execute([$id, $status]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
