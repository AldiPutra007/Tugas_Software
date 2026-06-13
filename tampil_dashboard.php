<?php
header('Content-Type: application/json');

// Gunakan koneksi PDO Anda
include 'koneksi.php';

try {
    // 1. Menghitung Total Penjualan Hari Ini
    // Menggunakan COALESCE agar jika hari ini belum ada transaksi, hasilnya 0 (bukan null)
    $queryPenjualan = "SELECT COALESCE(SUM(total_pembelian), 0) AS total_penjualan_hari_ini 
                       FROM transaksi WHERE tanggal = CURRENT_DATE";
    $stmtPenjualan = $conn->prepare($queryPenjualan);
    $stmtPenjualan->execute();
    $penjualan = $stmtPenjualan->fetch(PDO::FETCH_ASSOC);

    // 2. Menghitung Total Transaksi Keseluruhan
    $queryTransaksi = "SELECT COUNT(id) AS total_transaksi FROM transaksi";
    $stmtTransaksi = $conn->prepare($queryTransaksi);
    $stmtTransaksi->execute();
    $transaksi = $stmtTransaksi->fetch(PDO::FETCH_ASSOC);

    // 3. Menghitung Total Produk
    // CATATAN PENTING: Ganti tulisan 'produk' di bawah ini dengan nama tabel produk/barang Anda yang sebenarnya di database
   $queryProduk = "SELECT COUNT(id) AS total_produk FROM barang";
    $stmtProduk = $conn->prepare($queryProduk);
    $stmtProduk->execute();
    $produk = $stmtProduk->fetch(PDO::FETCH_ASSOC);

    // Menggabungkan semua hasil ke dalam satu format JSON
    $response = array(
        "penjualan_hari_ini" => (double) $penjualan['total_penjualan_hari_ini'],
        "total_transaksi" => (int) $transaksi['total_transaksi'],
        "total_produk" => (int) $produk['total_produk']
    );

    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage()));
}
?>