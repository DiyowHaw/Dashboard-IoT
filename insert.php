<?php
require "db.php"; // Memanggil koneksi database ($koneksi)

// Menerima data JSON dari ESP32
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    // Menangkap nilai dari JSON
    $suhu = $data['esp_suhu'];
    $kelembapan = $data['esp_kelembapan'];
    $tanah = $data['esp_tanah'];
    $air = $data['jarak_air']; // Tambahan untuk jarak air (float)

    // Menyimpan ke database tabel 'datasensor'
    $query = "INSERT INTO datasensor (esp_suhu, esp_kelembapan, esp_tanah, jarak_air) 
              VALUES ('$suhu', '$kelembapan', '$tanah', '$air')";

    if ($koneksi->query($query)) {
        echo "OK"; // Cukup kirim "OK" saja agar ESP32 tidak bingung
    } else {
        echo "Error: " . $koneksi->error; // Untuk mempermudah debugging jika gagal
    }
} else {
    echo "Gagal: Tidak ada data JSON yang diterima";
}
?>