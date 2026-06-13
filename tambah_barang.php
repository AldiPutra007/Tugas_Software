<?php
require 'koneksi.php';
header('Content-Type: application/json');

$nama = $_POST['nama'] ?? '';
$harga = $_POST['harga'] ?? '0';
$stok = $_POST['stok'] ?? '0';
$foto_base64 = $_POST['foto'] ?? '';

if (empty($nama) || empty($harga) || empty($foto_base64)) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    exit();
}

// 1. Proses Gambar
// Buat nama file unik (gabungan waktu saat ini dan nama barang)
$nama_file_bersih = preg_replace("/[^a-zA-Z0-9]/", "", $nama); // Hapus spasi dan simbol
$nama_file = time() . "_" . $nama_file_bersih . ".jpg";
$lokasi_simpan = "uploads/" . $nama_file;

// Decode teks base64 kembali menjadi file fisik dan simpan ke folder uploads/
if (file_put_contents($lokasi_simpan, base64_decode($foto_base64))) {
    
    // 2. Simpan ke PostgreSQL
    $stmt = $conn->prepare("INSERT INTO barang (nama, harga, stok, foto) VALUES (:nama, :harga, :stok, :foto)");
    $sukses = $stmt->execute([
        'nama' => $nama,
        'harga' => $harga,
        'stok' => $stok,
        'foto' => $lokasi_simpan // Menyimpan lokasi file gambar ke database
    ]);

    if ($sukses) {
        echo json_encode(["status" => "success", "message" => "Barang berhasil ditambahkan!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan ke database"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Gagal memproses gambar"]);
}
?>