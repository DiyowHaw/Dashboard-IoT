<?php 
include "koneksi.php";
?>
<?php 
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>✨ IoT Dashboard by KELUARGA CEMARA</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
  #temperature, #humidity, #soil-moisture {
    transition: all 0.3s ease-in-out; }
  </style>
  <script src="https://unpkg.com/brain.js"></script>
  <!-- TAMBAHAN: Library Chart.js untuk Grafik -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="text-gray-100">
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-6xl rounded-2xl overflow-hidden flex flex-col md:flex-row shadow-xl bg-gradient-to-br from-gray-900 to-gray-800 border border-gray-700/50">
      
      <aside class="p-6 w-full md:w-64 flex-shrink-0 border-r border-gray-700/50 flex flex-col">
        <div class="flex items-center space-x-3 mb-10">
          <div class="p-3 bg-green-500/10 rounded-xl">
            <i class="fas fa-heart text-green-400 text-xl"></i>
          </div>
          <h2 class="text-xl font-bold text-white">
            IRI<span class="text-green-400">Grow</span>
          </h2>
        </div>

        <nav class="space-y-2">
          <a href="#" onclick="showSection('live')" id="nav-live" class="doy-pill active flex items-center space-x-3 px-4 py-3 rounded-lg text-white">
            <i class="fas fa-fire text-green-400 w-5"></i>
            <span>Pemantauan</span>
            <span class="ml-auto text-xs bg-green-900/30 text-green-400 px-2 py-1 rounded-full doy-badge">Hot</span>
          </a>
          <a href="#" onclick="showSection('control')" id="nav-control" class="doy-pill flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white transition">
            <i class="fas fa-hand-holding-water w-5"></i>
            <span>Kontrol & Penjadwalan</span>
          </a>
          
          <a href="#" onclick="showSection('settings')" id="nav-settings" class="doy-pill flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white" style="display: none;">
            <i class="fas fa-cogs w-5"></i>
            <span>Setting</span>
          </a>
          <a href="#" onclick="showSection('history')" id="nav-history" class="doy-pill flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white" style="display: none;">
            <i class="fas fa-history w-5"></i>
            <span>Riwayat</span>
          </a>
          
          <!-- INFORMASI USER & TOMBOL LOGOUT FIREBASE -->
          <div class="pt-4 mt-4 border-t border-gray-700/50">
            <div class="px-4 pb-2 mb-2">
                <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Login sebagai:</p>
                <p id="userEmailDisplay" class="text-xs font-bold text-[#FFD700] truncate">Memuat...</p>
                <span id="userRoleBadge" class="inline-block mt-1 text-[10px] bg-gray-700 text-white px-2 py-0.5 rounded">User</span>
            </div>
            <a href="#" id="btnLogout" class="doy-pill flex items-center space-x-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-300 transition">
              <i class="fas fa-sign-out-alt w-5"></i>
              <span>Logout</span>
            </a>
          </div>
        </nav>

        <div class="mt-auto pt-8">
          <div class="text-xs text-gray-400 text-center">
            Dibuat <i class="fas fa-heart text-red-400 mx-1"></i> Oleh
            <span class="signature">@KeluargaCemara</span>
          </div>
        </div>
      </aside>

      <main class="flex-1 p-6 md:p-8">
        
        <header class="mb-8">
          <h1 class="text-2xl md:text-3xl font-bold text-white" id="page-title">
            Selamat Datang di <span class="text-green-400">IRIGrow Dashboard</span>
          </h1>
          <p class="text-gray-400 mt-2" id="page-subtitle">
            Pemantauan sederhana dengan wawasan yang mendalam • Dibuat oleh @KeluargaCemara
          </p>
        </header>

       <section id="section-live" class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="doy-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-sm font-medium text-gray-400">
                <i class="fas fa-thermometer-half text-red-400 mr-2"></i>
                SUHU
              </h2>
              <i class="fas fa-sync-alt text-gray-500 animate-spin" id="temp-loading"></i>
            </div>
            <p id="temperature" class="text-4xl font-bold text-white mb-1">--°C</p>
            <p class="text-xs text-gray-400">
              <i class="far fa-clock mr-1"></i>
              Diperbarui: <span id="timestamp">Waiting...</span>
            </p>
          </div>

            <div class="doy-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-sm font-medium text-gray-400">
                <i class="fas fa-tint text-blue-400 mr-2"></i>
                KELEMBAPAN
              </h2>
              <span class="text-xs bg-blue-900/30 text-blue-400 px-2 py-1 rounded-full">Stabil</span>
            </div>
            <p id="humidity" class="text-4xl font-bold text-blue-300 mb-1">--%</p>
            <p class="text-xs text-gray-400">
              <i class="fas fa-info-circle mr-1"></i>
              Rentang Ideal: 40-70%
            </p>
          </div>

          <div class="doy-card rounded-xl p-6 border-l-4 border-l-[#FFD700]">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-sm font-medium text-gray-400">
                <i class="fas fa-seedling text-[#FFD700] mr-2"></i>
                KELEMBAPAN TANAH
              </h2>
              <span class="text-xs bg-[#800000] text-[#FFD700] px-3 py-1 rounded-full font-medium shadow-sm">Optimal</span>
            </div>
            <p id="soil-moisture" class="text-4xl font-bold text-[#FFD700] mb-1">--%</p>
            <p class="text-xs text-gray-400">
              <i class="fas fa-leaf mr-1"></i>
              Kondisi kelembapan tanah
            </p>
          </div>

          <div class="doy-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-sm font-medium text-gray-400">
                <i class="fas fa-plug text-green-400 mr-2"></i>
                STATUS PERANGKAT
              </h2>
              <div class="flex items-center">
                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                <span class="text-xs text-green-400">Online</span>
              </div>
            </div>
            <p class="text-xl font-bold text-white">ESP32</p>
            <p class="text-xs text-gray-400 mt-1">
              <i class="fas fa-code-branch mr-1"></i>
              Firmware: v2.1.8
            </p>
          </div>

          <div class="doy-card rounded-xl p-6 bg-[#800000] border-l-4 border-l-[#FFD700] md:col-span-2 flex flex-col md:flex-row items-start md:items-center justify-between shadow-lg">
            <div>
              <div class="flex items-center mb-2">
                <h2 class="text-sm font-medium text-[#FFD700] tracking-wider">
                  <i class="fas fa-brain mr-2"></i>
                  PREDIKSI
                </h2>
                <span class="ml-3 text-xs bg-black/30 text-[#FFD700] px-3 py-1 rounded-full font-medium">Brain.js</span>
              </div>
              <p id="ai-status" class="text-3xl font-bold text-white mb-1">Menganalisis...</p>
            </div>
            <div class="mt-4 md:mt-0 text-left md:text-right bg-black/20 p-3 rounded-lg border border-[#FFD700]/20">
              <p class="text-xs text-gray-300 uppercase tracking-wider mb-1">Tingkat Kepercayaan</p>
              <p id="ai-accuracy" class="text-2xl font-bold text-[#FFD700]">--%</p>
            </div>
          </div>
        </section>

        <section id="section-control" class="hidden animate-fade-in">
          
          <div class="mb-6 bg-gradient-to-r from-[#800000] to-gray-900 rounded-xl p-6 border border-[#FFD700]/30 flex flex-col md:flex-row items-center justify-between shadow-lg">
            <div class="flex items-center mb-4 md:mb-0">
              <div class="p-3 bg-[#FFD700]/20 rounded-lg mr-4">
                <i class="fas fa-brain text-[#FFD700] text-xl"></i>
              </div>
              <div>
                <h3 class="text-white font-bold text-lg">Rekomendasi (Data 30 Hari)</h3>
                <p class="text-gray-300 text-sm">Pola tanah kering tercatat setiap pukul 14:00. menyarankan jadwal penyiraman 15 menit.</p>
              </div>
            </div>
            <button class="bg-[#FFD700] hover:bg-yellow-500 text-gray-900 font-bold py-2 px-6 rounded-lg transition shadow-[0_0_15px_rgba(255,215,0,0.4)]">
              Terapkan Jadwal
            </button>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 doy-card rounded-xl p-6 border border-gray-700/50">
              <div class="flex justify-between items-center mb-8">
                <div>
                  <h2 class="text-lg font-bold text-white">Kontrol Pompa Manual</h2>
                  <p class="text-xs text-gray-400">Timpa sistem otomatis sesaat</p>
                </div>
                <div class="flex items-center space-x-2">
                  <span class="text-sm text-gray-300">Mode Otomatis</span>
                  <label class="relative inline-flex items-center cursor-pointer">
                   <input type="checkbox" id="switch-pompa" class="sr-only peer" checked onchange="updatePumpTarget()">
                    <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#800000]"></div>
                  </label>
                </div>
              </div>

              <div class="flex justify-center mb-10">
                <button id="btn-pump" onclick="togglePump()" class="w-40 h-40 rounded-full bg-gray-800 border-4 border-gray-700 flex flex-col items-center justify-center text-gray-400 hover:border-[#FFD700] hover:text-[#FFD700] transition-all duration-300 shadow-[inset_0_0_20px_rgba(0,0,0,0.5)] group">
                  <i class="fas fa-power-off text-5xl mb-2 group-hover:drop-shadow-[0_0_10px_rgba(255,215,0,0.8)]"></i>
                  <span id="pump-status-text" class="font-bold tracking-widest uppercase">Off</span>
                </button>
              </div>

              <div class="mb-2">
                <div class="flex justify-between text-sm mb-2">
                  <span class="text-gray-400">Durasi Penyiraman</span>
                  <span id="slider-val" class="font-bold text-[#FFD700]">15 Menit</span>
                </div>
                <input type="range" min="1" max="60" value="15" class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-[#800000]" oninput="document.getElementById('slider-val').innerText = this.value + ' Menit'">
              </div>
            </div>

            <div class="doy-card rounded-xl p-6 border border-gray-700/50 flex flex-col">
              <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-white">Jadwal Aktif</h2>
                <button class="text-[#FFD700] hover:text-white text-sm"><i class="fas fa-plus"></i></button>
              </div>

              <div class="space-y-4 overflow-y-auto pr-2 flex-1">
                <div class="bg-gray-800/50 p-4 rounded-lg border border-gray-700 hover:border-[#800000] transition group">
                  <div class="flex justify-between items-center">
                    <div class="flex-1">
                      <div class="flex items-center space-x-2">
                        <i class="far fa-clock text-[#FFD700]"></i>
                        <span class="font-bold text-lg text-white">06:00</span>
                        <span class="text-xs text-gray-400">Pagi</span>
                      </div>
                      <p class="text-xs text-gray-400 mt-1">Durasi: 10 Min</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                      <input type="checkbox" class="sr-only peer" checked>
                      <div class="w-9 h-5 bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                  </div>
                </div>

                <div class="bg-gray-800/50 p-4 rounded-lg border border-[#800000] transition group">
                  <div class="flex justify-between items-center">
                    <div class="flex-1 pr-2">
                      <div class="flex items-center space-x-2">
                        <i class="fas fa-magic text-[#FFD700]"></i>
                        <span class="font-bold text-lg text-white">14:00</span>
                      </div>
                      <div class="flex items-center mt-1.5 flex-wrap gap-2">
                        <p class="text-xs text-gray-400">Durasi: 15 Min</p>
                        <span class="text-[10px] bg-[#800000] text-[#FFD700] px-2 py-0.5 rounded font-bold tracking-wider uppercase border border-[#FFD700]/30">AI Rekomendasi</span>
                      </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-2">
                      <input type="checkbox" class="sr-only peer" checked>
                      <div class="w-9 h-5 bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
        </section>
        
        <section id="section-settings" class="hidden">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="doy-card rounded-xl p-6">
              <h3 class="text-lg font-bold text-[#FFD700] mb-4">⚙️ Konfigurasi API</h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm text-gray-400 mb-1">URL Endpoint</label>
                  <input type="text" value="http://localhost/iot-dashboard/read.php" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#FFD700]">
                </div>
                <div>
                  <label class="block text-sm text-gray-400 mb-1">Interval Refresh (ms)</label>
                  <input type="number" value="1000" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#FFD700]">
                </div>
                <button class="w-full bg-[#800000] hover:bg-[#600000] text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                  Simpan Konfigurasi
                </button>
              </div>
            </div>

            <div class="doy-card rounded-xl p-6">
              <h3 class="text-lg font-bold text-[#FFD700] mb-4">🔔 Pengaturan Peringatan</h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm text-gray-400 mb-1">Batas Suhu Maksimal (°C)</label>
                  <input type="number" value="40" class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#FFD700]">
                </div>
                <div class="flex items-center mt-4">
                  <input type="checkbox" checked class="w-5 h-5 accent-[#800000] rounded">
                  <span class="ml-2 text-sm text-gray-300">Aktifkan Notifikasi Suhu Ekstrem</span>
                </div>
                <button class="w-full bg-[#800000] hover:bg-[#600000] text-white font-bold py-2 px-4 rounded-lg transition duration-300 mt-2">
                  Perbarui Parameter
                </button>
              </div>
            </div>
          </div>
        </section>

        <!-- BAGIAN RIWAYAT YANG SUDAH DITAMBAHKAN GRAFIK DAN TOMBOL EXPORT -->
        <section id="section-history" class="hidden">
          <div class="doy-card rounded-xl p-6">
            
            <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
              <h3 class="text-lg font-bold text-[#FFD700]">Riwayat Data Sensor</h3>
              <div class="flex space-x-3">
                <!-- TOMBOL DOWNLOAD CSV -->
                <a href="export.php" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-500 transition">
                <i class="fas fa-file-csv mr-2"></i> Unduh Data Lengkap (CSV)
                </a>
                <button class="bg-[#800000] hover:bg-[#600000] text-white px-4 py-2 rounded-md text-sm transition shadow-lg flex items-center" onclick="window.location.reload();">
                  <i class="fas fa-sync-alt mr-2"></i> Refresh
                </button>
              </div>
            </div>
            
            <!-- KANVAS UNTUK GRAFIK CHART.JS -->
            <div class="bg-gray-800/50 p-4 rounded-xl border border-gray-700/50 mb-6 w-full overflow-hidden">
                <canvas id="grafikSensor" height="80"></canvas>
            </div>
            
            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse" id="tabelRiwayat">
                <thead>
                  <tr class="border-b border-gray-600 text-[#FFD700]">
                    <th class="py-3 px-4 font-semibold text-sm">No</th>
                    <th class="py-3 px-4 font-semibold text-sm">Waktu Nyata</th>
                    <th class="py-3 px-4 font-semibold text-sm">Suhu (°C)</th>
                    <th class="py-3 px-4 font-semibold text-sm">Udara (%)</th>
                    <th class="py-3 px-4 font-semibold text-sm">Tanah (%)</th>
                  </tr>
                </thead>
                <tbody class="text-gray-300 text-sm">
                  <?php
                  // Array PHP untuk menyiapkan data Grafik JS
                  $waktu_arr = [];
                  $suhu_arr = [];
                  $hum_arr = [];
                  $soil_arr = [];

                  if(isset($koneksi)){
                    // Mengambil 15 data terakhir untuk grafik & tabel
                    $sql_history = mysqli_query($koneksi, "SELECT * FROM datasensor ORDER BY id DESC LIMIT 15");
                    $no = 1;
                    
                    while($row = mysqli_fetch_assoc($sql_history)){
                      // Memasukkan data ke Array (Format jam saja agar grafik tidak kepanjangan)
                      array_push($waktu_arr, date('H:i:s', strtotime($row['waktu'])));
                      array_push($suhu_arr, $row['esp_suhu']);
                      array_push($hum_arr, $row['esp_kelembapan']);
                      array_push($soil_arr, ($row['esp_tanah'] ?? 0));

                      echo '<tr class="border-b border-gray-700/50 hover:bg-white/5 transition">';
                      echo '<td class="py-3 px-4">'.$no++.'</td>';
                      echo '<td class="py-3 px-4">'.$row['waktu'].'</td>';
                      echo '<td class="py-3 px-4">'.$row['esp_suhu'].'</td>';
                      echo '<td class="py-3 px-4">'.$row['esp_kelembapan'].'</td>';
                      echo '<td class="py-3 px-4">'.($row['esp_tanah'] ?? '0').'</td>';
                      echo '</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="5" class="py-4 text-center text-red-400">Koneksi Database Gagal. Periksa db.php!</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>

          </div>
        </section>

      </main>
    </div>
  </div>

  <!-- SCRIPT TRANSFER DATA PHP KE JAVASCRIPT UNTUK GRAFIK -->
  <script>
      // Kita membalikkan array (reverse) agar grafik dibaca dari kiri (terlama) ke kanan (terbaru)
      const labelWaktu = <?php echo json_encode(array_reverse($waktu_arr)); ?>;
      const dataSuhu = <?php echo json_encode(array_reverse($suhu_arr)); ?>;
      const dataHum = <?php echo json_encode(array_reverse($hum_arr)); ?>;
      const dataSoil = <?php echo json_encode(array_reverse($soil_arr)); ?>;
  </script>

  <script>
    // DEKLARASI GLOBAL VARIABLE ROLE
    let currentUserRole = "user"; // Default role
    let lastTimestamp = ""; 
    let isPumpOn = false; 

    // ==========================================
    // 📊 RENDER GRAFIK DENGAN CHART.JS
    // ==========================================
    // 1. TAMBAHKAN VARIABEL INI DI ATAS SEMUA SCRIPT
    let myChart; 

    // 2. MODIFIKASI INISIALISASI GRAFIK
    window.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('grafikSensor').getContext('2d');
        myChart = new Chart(ctx, { // Pastikan myChart didefinisikan di sini
            type: 'line',
            data: {
                labels: labelWaktu,
                datasets: [
                    { label: 'Suhu (°C)', data: dataSuhu, borderColor: '#ef4444', borderWidth: 2, tension: 0.4 },
                    { label: 'Kelembapan Udara (%)', data: dataHum, borderColor: '#3b82f6', borderWidth: 2, tension: 0.4 },
                    { label: 'Kelembapan Tanah (%)', data: dataSoil, borderColor: '#FFD700', borderWidth: 2, tension: 0.4 }
                ]
            },
            options: { responsive: true, /* ... opsi lainnya ... */ }
        });
    });

    // ==========================================
    // 📥 FUNGSI UNDUH DATA KE CSV / EXCEL
    // ==========================================
    function downloadCSV(filename) {
        let csv = [];
        // Ambil elemen tabel
        let rows = document.querySelectorAll("#tabelRiwayat tr");
        
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll("td, th");
            
            for (let j = 0; j < cols.length; j++) {
                // Bungkus teks dengan kutip untuk mencegah error pemisahan koma di dalam tabel
                row.push('"' + cols[j].innerText.replace(/"/g, '""') + '"');
            }
            csv.push(row.join(",")); // Pisahkan kolom dengan koma (format CSV standard)
        }

        // Jalankan trigger download file
        let csvFile = new Blob([csv.join("\n")], {type: "text/csv;charset=utf-8;"});
        let downloadLink = document.createElement("a");
        
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

    // ==========================================
    // 🧠 SETUP MACHINE LEARNING (BRAIN.JS)
    // ==========================================
    const net = new brain.NeuralNetwork();
    net.train([
      { input: { temp: 0.29, hum: 0.78, soil: 0.90 }, output: { optimal: 1 } }, 
      { input: { temp: 0.35, hum: 0.40, soil: 0.20 }, output: { bahaya_kering: 1 } }, 
      { input: { temp: 0.25, hum: 0.90, soil: 0.95 }, output: { waspada_jamur: 1 } }, 
      { input: { temp: 0.32, hum: 0.60, soil: 0.40 }, output: { butuh_air: 1 } }      
    ], { iterations: 2000, errorThresh: 0.011 });

    // ==========================================
    // 📡 FETCH DATA LIVE
    // ==========================================
    async function fetchData() {
        try {
            const response = await fetch('read.php?t=' + new Date().getTime()); 
            const data = await response.json();

            if (data.error) return;

            if (data.timestamp !== lastTimestamp) {
                
                document.getElementById('temperature').textContent = data.temperature;
                document.getElementById('humidity').textContent = data.humidity;
                document.getElementById('soil-moisture').textContent = data.soil_moisture;
                document.getElementById('timestamp').textContent = data.timestamp;
                
                let currentTemp = parseFloat(data.temperature) / 100;
                let currentHum = parseFloat(data.humidity) / 100;
                let currentSoil = parseFloat(data.soil_moisture) / 100;

                const result = net.run({ temp: currentTemp, hum: currentHum, soil: currentSoil });
                
                let highestProb = 0;
                let statusName = "";
                
                for (let key in result) {
                  if (result[key] > highestProb) {
                    highestProb = result[key];
                    statusName = key;
                  }
                }

                const aiStatusEl = document.getElementById('ai-status');
                const aiAccEl = document.getElementById('ai-accuracy');
                
                if(statusName === 'optimal') aiStatusEl.textContent = "Kondisi Sangat Baik";
                else if(statusName === 'bahaya_kering') aiStatusEl.textContent = "Krisis Air & Panas";
                else if(statusName === 'waspada_jamur') aiStatusEl.textContent = "Rawan Jamur (Lembap)";
                else if(statusName === 'butuh_air') aiStatusEl.textContent = "Segera Siram";
                else aiStatusEl.textContent = "Menganalisis...";

                let jamSekarang = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'});
                updateGrafik(jamSekarang, data.temperature, data.humidity, data.soil_moisture);

                aiAccEl.textContent = (highestProb * 100).toFixed(1) + "%";
                lastTimestamp = data.timestamp;
            }
        } catch (error) {
            console.error("Fetch error:", error);
        }
    }
    
    // FUNGSI INI DITAMBAHKAN AGAR GRAFIK OTOMATIS BERUBAH
    function updateGrafik(newLabel, newSuhu, newHum, newSoil) {
        if (myChart) {
            myChart.data.labels.push(newLabel);
            myChart.data.datasets[0].data.push(newSuhu);
            myChart.data.datasets[1].data.push(newHum);
            myChart.data.datasets[2].data.push(newSoil);

            if (myChart.data.labels.length > 15) { // Batasi 15 data saja
                myChart.data.labels.shift();
                myChart.data.datasets.forEach(dataset => dataset.data.shift());
            }
            myChart.update();
        }
    }

    setInterval(fetchData, 1000);
    fetchData();

    // ==========================================
    // 🗂️ FUNGSI NAVIGASI MENU
    // ==========================================
    function showSection(sectionName) {
      if (currentUserRole !== "admin" && (sectionName === 'settings' || sectionName === 'history')) {
        alert("Akses Ditolak! Fitur ini hanya untuk Admin.");
        return; 
      }

      const sections = ['live', 'control', 'settings', 'history']; 
      
      sections.forEach(sec => {
        const sectionEl = document.getElementById(`section-${sec}`);
        if (sectionEl) sectionEl.classList.add('hidden');
        
        const navItem = document.getElementById(`nav-${sec}`);
        if (navItem) {
          navItem.classList.remove('active', 'text-white');
          navItem.classList.add('text-gray-300');
        }
      });

      const activeSection = document.getElementById(`section-${sectionName}`);
      if (activeSection) {
        activeSection.classList.remove('hidden');
        activeSection.classList.add('animate-fade-in');
      }
      
      const activeNav = document.getElementById(`nav-${sectionName}`);
      if (activeNav) {
        activeNav.classList.add('active', 'text-white');
        activeNav.classList.remove('text-gray-300');
      }

      const pageTitle = document.getElementById('page-title');
      const pageSubtitle = document.getElementById('page-subtitle');
      
      if(sectionName === 'live') {
        if (pageTitle) pageTitle.innerHTML = `Selamat Datang di <span class="text-green-400">IRIGrow Dashboard</span>`;
        if (pageSubtitle) pageSubtitle.textContent = "Pemantauan sederhana dengan wawasan yang mendalam • Dibuat oleh @KeluargaCemara";
      } else if (sectionName === 'control') {
        if (pageTitle) pageTitle.innerHTML = `Kontrol <span class="text-[#FFD700]">Irigasi</span>`;
        if (pageSubtitle) pageSubtitle.textContent = "Kelola penyiraman manual dan jadwal rekomendasi AI.";
      } else if (sectionName === 'settings') {
        if (pageTitle) pageTitle.innerHTML = `Sistem <span class="text-[#FFD700]">Setting</span>`;
        if (pageSubtitle) pageSubtitle.textContent = "Atur parameter dan konfigurasi server Anda.";
      } else if (sectionName === 'history') {
        if (pageTitle) pageTitle.innerHTML = `Data <span class="text-[#FFD700]">Riwayat Tanaman Cabai</span>`;
        if (pageSubtitle) pageSubtitle.textContent = "Pantau rekam jejak sensor secara lengkap.";
      }
    }

    // ==========================================
    // 🔌 FUNGSI KONTROL POMPA TUNGGAL
    // ==========================================
    async function togglePump() {
      const btn = document.getElementById('btn-pump');
      const text = document.getElementById('pump-status-text');
      const icon = btn.querySelector('i');
      
      isPumpOn = !isPumpOn;
      
      if(isPumpOn) {
        btn.classList.remove('bg-gray-800', 'border-gray-700', 'text-gray-400');
        btn.classList.add('bg-gray-900', 'border-[#FFD700]', 'text-[#FFD700]', 'shadow-[0_0_30px_rgba(255,215,0,0.3)]');
        icon.classList.add('drop-shadow-[0_0_10px_rgba(255,215,0,0.8)]');
        text.innerText = "ON";
      } else {
        btn.classList.add('bg-gray-800', 'border-gray-700', 'text-gray-400');
        btn.classList.remove('bg-gray-900', 'border-[#FFD700]', 'text-[#FFD700]', 'shadow-[0_0_30px_rgba(255,215,0,0.3)]');
        icon.classList.remove('drop-shadow-[0_0_10px_rgba(255,215,0,0.8)]');
        text.innerText = "OFF";
      }

      await updatePumpTarget();
    }

    async function updatePumpTarget() {
      const isAuto = document.getElementById('mode-switch')?.checked || false;
      const duration = document.querySelector('input[type="range"]')?.value || 15;
      
      const payload = {
        mode: isAuto ? 'auto' : 'manual',
        status: isPumpOn ? 1 : 0,
        durasi: parseInt(duration)
      };

      try {
        await fetch('set_status.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
      } catch (error) {
        console.error("Gagal memperbarui status pompa ke server:", error);
      }
    }
  </script>
  <script>
    function fetchRealtimeData() {
        fetch('get_data.php')
            .then(response => response.json())
            .then(data => {
                if(!data.error) {
                    if (document.getElementById('val-suhu')) document.getElementById('val-suhu').innerText = data.esp_suhu + "°C";
                    if (document.getElementById('val-hum')) document.getElementById('val-hum').innerText = data.esp_kelembapan + "%";
                    if (document.getElementById('val-soil')) document.getElementById('val-soil').innerText = data.esp_tanah + "%";
                }
            })
            .catch(error => console.error('Gagal mengambil data:', error));
    }
    fetchRealtimeData();
    setInterval(fetchRealtimeData, 2000);
</script>
<script>
const switchPompa = document.getElementById('switch-pompa');
if(switchPompa) {
  switchPompa.addEventListener('change', function() {
      let statusNilai = this.checked ? 1 : 0;
      fetch('kontrol.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ status: statusNilai })
      })
      .then(response => response.json())
      .then(data => {
          if(!data.success) alert("Gagal mengubah kontrol pompa di database");
      })
      .catch(error => console.error('Error:', error));
  });
}

function kirimKontrol(data) {
    fetch('kontrol.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    });
}
</script>

<!-- ==========================================
     🔥 FIREBASE AUTH & ROLE SYSTEM 🔥
     ========================================== -->
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
    import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-auth.js";

    // PENTING: Paste kode config Firebase milik Anda di bawah ini
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
    apiKey: "AIzaSyDq0xZ_lykDxp-EvILlI2AOMCpYE7Wgvrc",
    authDomain: "iot-dashboard-39482.firebaseapp.com",
    projectId: "iot-dashboard-39482",
    storageBucket: "iot-dashboard-39482.firebasestorage.app",
    messagingSenderId: "668572009153",
    appId: "1:668572009153:web:fea639d6421fefdbf74a40",
    measurementId: "G-CC872FXDT3"
  };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    // ==========================================
    // 👑 TENTUKAN EMAIL ADMIN DI SINI
    // ==========================================
    const adminEmails = ["admin@keluargacemara.com", "diogentano@gmail.com"]; 

    onAuthStateChanged(auth, (user) => {
        if (!user) {
            window.location.replace("login.html");
        } else {
            document.getElementById('userEmailDisplay').textContent = user.email;

            if (adminEmails.includes(user.email)) {
                currentUserRole = "admin";
                document.getElementById('userRoleBadge').textContent = "Administrator";
                document.getElementById('userRoleBadge').classList.replace('bg-gray-700', 'bg-[#800000]');
                
                document.getElementById('nav-settings').style.display = 'flex';
                document.getElementById('nav-history').style.display = 'flex';
            } else {
                currentUserRole = "user";
                document.getElementById('userRoleBadge').textContent = "Pengguna Biasa";
                
                document.getElementById('nav-settings').style.display = 'none';
                document.getElementById('nav-history').style.display = 'none';
            }
        }
    });

    const btnLogout = document.getElementById('btnLogout');
    if(btnLogout) {
        btnLogout.addEventListener('click', (e) => {
            e.preventDefault(); 
            signOut(auth).then(() => {
                window.location.replace("login.html");
            }).catch((error) => {
                alert("Gagal Logout: " + error.message);
            });
        });
    }
</script>

</body>
</html>