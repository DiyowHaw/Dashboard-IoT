<?php
// get_data.php
require "db.php"; // Panggil koneksi database Anda

// Pastikan output berupa JSON
header('Content-Type: application/json');

// Ambil 1 data paling baru (diurutkan dari ID paling besar/descending)
$query = "SELECT esp_suhu, esp_kelembapan, esp_tanah FROM datasensor ORDER BY id DESC LIMIT 1";
$result = $koneksi->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Kirim data dalam format JSON
    echo json_encode($row);
} else {
    // Jika tabel kosong atau error
    echo json_encode(["error" => "Belum ada data"]);
}
?>