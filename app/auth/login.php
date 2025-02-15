<?php
session_start();
require_once '../config/config.php';
require_once '../core/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getConnection();
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // ✅ Simpan role ke session

        // Redirect setelah login
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect_url = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
        } else {
            $redirect_url = '/KPL2025_B_V2/public/index.php'; // ✅ Pastikan redirect ke halaman utama
        }

        header("Location: $redirect_url");
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="id">

<head>
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <a href="register.php">Daftar</a>
</body>

</html>