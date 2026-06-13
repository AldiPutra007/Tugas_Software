<?php
require 'koneksi.php';
header('Content-Type: application/json');

// Menangkap data dari Android (menggunakan metode POST)
$nama = $_POST['nama'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($nama) || empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Data tidak boleh kosong"]);
    exit();
}

// Cek apakah email sudah terdaftar sebelumnya
$stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "error", "message" => "Email sudah terdaftar, silakan Login."]);
} else {
    // Enkripsi password sebelum disimpan (SANGAT PENTING!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $insert = $conn->prepare("INSERT INTO users (nama, email, password) VALUES (:nama, :email, :password)");
    $sukses = $insert->execute([
        'nama' => $nama,
        'email' => $email,
        'password' => $hashed_password
    ]);

    if ($sukses) {
        echo json_encode(["status" => "success", "message" => "Pendaftaran berhasil"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal mendaftar"]);
    }
}
?>