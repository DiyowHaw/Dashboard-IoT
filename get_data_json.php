<?php
// Memanggil koneksi database Anda
require "db.php"; 

// Memberi tahu Android bahwa output dari file ini adalah format JSON
header('Content-Type: application/json');

// Mengambil 1 baris data sensor yang paling terakhir/baru dimasukkan
// Sesuaikan "datasensor" dengan nama tabel sensor Anda jika berbeda
$query = "SELECT esp_suhu, esp_kelembapan, esp_tanah, esp_air FROM datasensor ORDER BY id DESC LIMIT 1";
$result = $koneksi->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Membungkus data ke dalam format JSON
    echo json_encode([
        "suhu" => $row['esp_suhu'],
        "kelembapan" => $row['esp_kelembapan'],
        "tanah" => $row['esp_tanah'],
        "air" => $row['esp_air']
    ]);
} else {
    // Jika tabel masih kosong, kirim data default "--"
    echo json_encode([
        "suhu" => "--",
        "kelembapan" => "--",
        "tanah" => "--",
        "air" => "--"
    ]);
}
?>