<?php
// Konfigurasi Database - Tidak mengubah apapun di database Anda
require "db.php"; 
header('Content-Type: application/json');

// 1. Menerima update dari Website (Tombol di UI)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Update Mode (Auto/Manual)
    if (isset($data['mode_auto'])) {
        $mode = intval($data['mode_auto']);
        $koneksi->query("UPDATE kontrol_pompa SET mode_auto = $mode WHERE id = 1");
    }
    
    // Update Status Pompa (ON/OFF Manual)
    if (isset($data['status_pompa'])) {
        $status = intval($data['status_pompa']);
        $koneksi->query("UPDATE kontrol_pompa SET status_pompa = $status WHERE id = 1");
    }
    
    echo json_encode(["status" => "success"]);
    exit();
}

// 2. Mengirim data status ke ESP32
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Pastikan kolom mode_auto sudah ada (telah dijalankan ALTER TABLE)
    $query = "SELECT mode_auto, status_pompa FROM kontrol_pompa WHERE id = 1";
    $result = $koneksi->query($query);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Mengirim data ke ESP32 dalam format: "ModeAuto,StatusPompa"
        // Contoh: "1,0" berarti Auto aktif, Pompa mati
        echo $row['mode_auto'] . "," . $row['status_pompa'];
    } else {
        // Default jika data kosong
        echo "1,0"; 
    }
    exit();
}
?>