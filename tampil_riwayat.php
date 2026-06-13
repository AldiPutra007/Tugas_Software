<?php
// Set header agar output dibaca sebagai JSON oleh Android
header('Content-Type: application/json');

// Gunakan koneksi PDO yang sudah dibuat sebelumnya
include 'koneksi.php';

try {
    // Mengambil semua data transaksi, diurutkan dari yang terbaru (id terbesar / DESC)
    $query = "SELECT id, total_pembelian, metode_pembayaran, tanggal FROM transaksi ORDER BY id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    // Ambil semua hasil query dalam bentuk array asosiatif
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Kirim data dalam bentuk JSON
    echo json_encode($result);
    
} catch (PDOException $e) {
    // Jika error, kirim pesan error (opsional)
    echo json_encode([array("status" => "error", "message" => $e->getMessage())]);
}
?>