<?php
include "db.php";
header("Content-Type: application/json");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

$query = "SELECT * FROM kontrol_pompa WHERE id=1";
$result = $koneksi->query($query);
$row = $result->fetch_assoc();

echo json_encode([
    "mode" => $row['mode'],
    "status" => (int)$row['status'],
    "durasi" => (int)$row['durasi']
]);
?>