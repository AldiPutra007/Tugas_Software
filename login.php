<?php
require 'koneksi.php';
header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Email dan password harus diisi"]);
    exit();
}

// Cari user berdasarkan email
$stmt = $conn->prepare("SELECT id, nama, password FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika user ditemukan
if ($user) {
    // Cocokkan password yang diketik dengan password acak di database
    if (password_verify($password, $user['password'])) {
        echo json_encode([
            "status" => "success", 
            "message" => "Login berhasil",
            "data" => [
                "id" => $user['id'],
                "nama" => $user['nama']
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Password salah!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Email tidak ditemukan!"]);
}
?>