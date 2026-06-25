<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // Allow CORS for development
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include "db.php";

try {
    // 1. Cek koneksi dari db.php (jaga-jaga jika variabel $koneksi tidak ada)
    if (!$koneksi) {
        http_response_code(500);
        throw new Exception('Koneksi database terputus');
    }

    // 2. Ambil data sensor terbaru dengan error handling
    $query = "SELECT esp_suhu, esp_kelembapan, esp_tanah, waktu FROM datasensor ORDER BY id DESC LIMIT 1";
    $sql = mysqli_query($koneksi, $query);

    // 3. Validasi: Pastikan query berhasil (tidak false) DAN datanya lebih dari 0 baris
    if (!$sql) {
        http_response_code(500);
        throw new Exception('Database query failed: ' . mysqli_error($koneksi));
    }

    if (mysqli_num_rows($sql) > 0) {
        $data = mysqli_fetch_assoc($sql);
        
        echo json_encode([
            'temperature' => (isset($data['esp_suhu']) ? $data['esp_suhu'] : '0') . '°C',
            'humidity' => (isset($data['esp_kelembapan']) ? $data['esp_kelembapan'] : '0') . '%',
            'soil_moisture' => (isset($data['esp_tanah']) ? $data['esp_tanah'] : '0') . '%',
            'timestamp' => (isset($data['waktu']) ? $data['waktu'] : 'N/A')
        ]);
    } else {
        // Menangani jika tabel kosong
        http_response_code(404);
        echo json_encode([
            'error' => 'No sensor data available yet',
            'temperature' => '0°C',
            'humidity' => '0%',
            'soil_moisture' => '0%',
            'timestamp' => 'N/A'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    error_log('read.php error: ' . $e->getMessage());
}
?>