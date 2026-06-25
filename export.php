<?php
include 'koneksi.php'; // Sekarang file ini akan berfungsi

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data_sensor_irigrow.csv');

$output = fopen('php://output', 'w');

// Header Kolom (Sesuaikan dengan nama kolom di tabel Anda)
fputcsv($output, ['ID', 'Waktu', 'Suhu', 'Kelembapan', 'Kelembapan_Tanah']);

// Query ambil semua data
$query = "SELECT * FROM datasensor ORDER BY id DESC"; // Pastikan nama tabelnya 'datasensor'
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>