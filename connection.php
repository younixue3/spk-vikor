<?php

$host = "localhost";
$dbname = "spk_vikor";
$username = "root";
$password = "";

try {
    $conn = new PDO('mysql:host=localhost;dbname=spk_vikor', $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "Connected to database successfully";
} catch(PDOException $e) {
//    echo "Connection failed: " . $e->getMessage();
}
?>