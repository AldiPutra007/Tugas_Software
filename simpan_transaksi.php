<?php
// Mencegah error reporting agar tidak merusak format JSON jika ada notice
error_reporting(0);

// 1. Hubungkan ke database (Menggunakan PDO dari koneksi.php)
include 'koneksi.php'; 

// 2. Cek apakah data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil data dari Retrofit
    $total_pembelian = isset($_POST['total_pembelian']) ? $_POST['total_pembelian'] : 0;
    $metode_pembayaran = isset($_POST['metode_pembayaran']) ? $_POST['metode_pembayaran'] : '';
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d'); 

    // Validasi data
    if ($total_pembelian > 0 && !empty($metode_pembayaran)) {
        
        try {
            // 3. Query INSERT menggunakan Prepared Statement PDO
            $query = "INSERT INTO transaksi (total_pembelian, metode_pembayaran, tanggal) 
                      VALUES (:total, :metode, :tanggal)";
            
            // Mempersiapkan query
            $stmt = $conn->prepare($query);
            
            // Mengeksekusi query dengan memasukkan datanya langsung ke placeholder (:total, :metode, :tanggal)
            $result = $stmt->execute([
                ':total'   => $total_pembelian,
                ':metode'  => $metode_pembayaran,
                ':tanggal' => $tanggal
            ]);

            if ($result) {
                // Berhasil
                echo json_encode(array("status" => "sukses", "pesan" => "Transaksi berhasil disimpan"));
            } else {
                // Gagal eksekusi tanpa throw exception
                echo json_encode(array("status" => "gagal", "pesan" => "Gagal menyimpan data ke database"));
            }
            
        } catch (PDOException $e) {
            // Menangkap error jika terjadi kegagalan sistem database
            echo json_encode(array("status" => "gagal", "pesan" => "Error Database: " . $e->getMessage()));
        }
        
    } else {
        // Data tidak lengkap
        echo json_encode(array("status" => "gagal", "pesan" => "Data tidak lengkap atau total pembelian tidak valid"));
    }

} else {
    // Jika diakses via browser secara langsung (bukan POST)
    echo json_encode(array("status" => "gagal", "pesan" => "Akses ditolak"));
}

// Catatan: Di PDO, koneksi tidak perlu ditutup manual dengan pg_close(). 
// Koneksi akan otomatis tertutup ketika eksekusi file PHP selesai.
?>