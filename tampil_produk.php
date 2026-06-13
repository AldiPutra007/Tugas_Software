<?php
header("Content-Type: application/json; charset=UTF-8");

// 1. Memanggil file koneksi.php (menggunakan variabel koneksi $conn)
include "koneksi.php"; 

try {
    // 2. Kueri mengambil data dari tabel barang (diurutkan dari yang terbaru)
    $query = "SELECT * FROM barang ORDER BY id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $response = array();

    // 3. Mengambil data baris per baris
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        
        // Menggunakan IP Laptop Anda dan folder cloudkasir
        // Jalur dihentikan di sini karena kolom 'foto' di database sudah berawalan "uploads/..."
        $server_ip = "http://10.35.5.88/cloudkasir/";
        
        if (!empty($row['foto'])) {
            // Menghasilkan jalur pas: http://192.168.1.8/cloudkasir/uploads/nama_file.jpg
            $url_foto = $server_ip . $row['foto'];
        } else {
            // Antisipasi jika data foto di pgAdmin kosong/null
            $url_foto = $server_ip . "uploads/default.png";
        }

        // Susun struktur array data sesuai model produk di Android Studio
        $response[] = array(
            "id"    => $row['id'],
            "nama"  => $row['nama'],
            "harga" => (int)$row['harga'],
            "stok"  => (int)$row['stok'],
            "foto"  => $url_foto
        );
    }
    
    // 4. Keluarkan hasil akhir dalam format JSON Array
    echo json_encode($response);

} catch (PDOException $e) {
    // Menampilkan pesan error jika ada masalah pada kueri database
    echo json_encode(array("error" => "Gagal mengambil data: " . $e->getMessage()));
}
?>