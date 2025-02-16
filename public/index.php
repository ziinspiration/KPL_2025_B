<?php
require_once '../app/init.php';

try {
    $app = new App();
} catch (Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
    error_log("Error: " . $e->getMessage());
}
