<?php
// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "iot_data";

// Membuat koneksi ke database dengan charset utf8mb4
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil atau gagal
if (!$koneksi) {
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]));
}

// Set charset ke UTF-8 untuk mendukung karakter khusus
mysqli_set_charset($koneksi, "utf8mb4");
// Jika berhasil, variabel $koneksi otomatis siap digunakan di file index.php, read.php, atau insert.php
?>