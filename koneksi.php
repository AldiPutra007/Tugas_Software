<?php
$host = "localhost";
$port = "5432"; // Port default PostgreSQL
$dbname = "cloudkasir"; // Ganti dengan nama database kamu
$user = "postgres"; // Ganti dengan username PostgreSQL kamu
$password = "aldi"; // Ganti dengan password PostgreSQL kamu

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $conn = new PDO($dsn, $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Koneksi Gagal: " . $e->getMessage()]);
    exit();
}
?>