// Memanggil modul yang sudah diinstal
const express = require('express');
const mqtt = require('mqtt');

const app = express();
const port = 8080;

// 1. Pengaturan Express (Web Server)
app.get('/', (req, res) => {
  res.send('Halo, ini halaman utama IoT Dashboard!');
});

// 2. Pengaturan MQTT (Koneksi ke Broker publik sebagai contoh)
const client = mqtt.connect('mqtt://broker.hivemq.com');

client.on('connect', () => {
  console.log('Berhasil terhubung ke MQTT Broker!');
});

// 3. Menjalankan Server
app.listen(port, () => {
  console.log(`Server berjalan di http://localhost:${port}`);
});