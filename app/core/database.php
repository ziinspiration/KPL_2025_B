<?php
function getConnection()
{
    try {
        return new PDO("mysql:host=localhost;dbname=kpl2025", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
