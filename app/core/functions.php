<?php
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'auth/login.php');
        exit();
    }
}

function uploadImage($file) {
    $target_dir = "../public/uploads/";
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . time() . '_' . basename($file["name"]);
    
    // Check if image file is actual image
    if(getimagesize($file["tmp_name"]) === false) {
        return false;
    }
    
    // Check file size
    if ($file["size"] > 5000000) { // 5MB max
        return false;
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        return false;
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return basename($target_file);
    }
    return false;
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}