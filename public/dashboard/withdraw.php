<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../app/config/config.php';
require_once '../../app/core/database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$conn = getConnection();
$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE posts SET status = 'withdrawn' WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

header("Location: index.php");
exit();
?>
