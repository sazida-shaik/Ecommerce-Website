<?php
$host = "sql213.infinityfree.com";
$dbname = "if0_42272527_ecommerce";
$username = "if0_42272527";
$password = "Sazida2006"; // Use your InfinityFree/vPanel password

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>