<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["error" => "Unauthorized"]));
}

require_once '../../app/config/config.php';
require_once '../../app/core/database.php';

if (!isset($_POST['id']) || !isset($_POST['status'])) {
    die(json_encode(["error" => "Invalid request"]));
}

$conn = getConnection();
$id = $_POST['id'];
$status = $_POST['status'];
$user_id = $_SESSION['user_id'];

// Validasi status hanya bisa 'draft' atau 'published'
if (!in_array($status, ['draft', 'published'])) {
    die(json_encode(["error" => "Invalid status"]));
}

// Jika status diubah ke 'published', atur `published_at`, jika 'draft' kosongkan
$published_at = ($status === 'published') ? date('Y-m-d H:i:s') : null;

try {
    $stmt = $conn->prepare("UPDATE posts SET status = ?, published_at = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$status, $published_at, $id, $user_id]);

    echo json_encode(["success" => true, "status" => $status, "published_at" => $published_at]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
