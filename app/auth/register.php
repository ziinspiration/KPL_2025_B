<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/database.php';
require_once __DIR__ . '/../core/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'reader'; // Set default role sebagai reader

    if (!empty($fullname) && !empty($username) && !empty($email) && !empty($_POST['password'])) {
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, role) VALUES (?, ?, ?, ?, ?)");

        if ($stmt->execute([$fullname, $username, $email, $password, $role])) {
            $_SESSION['success'] = "Registration successful! You can now login.";
            header("Location: login.php");
            exit;
        } else {
            $error = "Registration failed!";
        }
    } else {
        $error = "All fields are required!";
    }
}
?>

<!-- HTML Form -->
<form action="register.php" method="POST">
    <input type="text" name="fullname" placeholder="Full Name" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
<p><?php echo isset($error) ? $error : ''; ?></p>