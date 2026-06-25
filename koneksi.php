<?php
$host = "localhost";
$user = "root";     // Username default XAMPP
$pass = "";         // Password default XAMPP biasanya kosong
$db   = "iot_data"; // GANTI dengan nama database Anda di phpMyAdmin

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>