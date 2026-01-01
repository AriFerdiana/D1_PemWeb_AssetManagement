<x-app-layout>
    @section('header', 'Scanner Transaksi')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-150px)]">
        
        {{-- BAGIAN KIRI: KAMERA --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-4 border border-gray-100 dark:border-gray-700 h-full flex flex-col">
                <div class="flex justify-between items-center mb-4 px-2">
                    <h3 class="font-bold text-navy-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-camera text-teal-500"></i> Kamera Aktif
                    </h3>
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full animate-pulse font-bold">
                        ● LIVE
                    </span>
                </div>

                {{-- AREA KAMERA (UKURAN DIPERBAIKI) --}}
                <div class="relative w-full rounded-xl overflow-hidden flex items-center justify-center bg-black group" style="height: 650px;">
                    <div id="reader" style="width: 100%; height: 100%;"></div>
                    
                    {{-- Placeholder --}}
                    <div id="camera-placeholder" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 text-white z-10">
                        <i class="fas fa-qrcode text-6xl text-gray-700 mb-4"></i>
                        <p class="text-gray-400 text-sm mt-2">Klik tombol "Aktifkan Kamera"</p>
                    </div>
                </div>

                {{-- Tombol Kontrol --}}
                <div class="mt-4 flex gap-3">
                    <button type="button" id="btn-start" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-xl font-bold transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-power-off"></i> Aktifkan Kamera
                    </button>
                    <button type="button" id="btn-stop" class="hidden flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-bold transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-stop"></i> Stop Kamera
                    </button>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: HASIL SCAN --}}
        <div class="space-y-6 flex flex-col">
            {{-- Input Manual --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-navy-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-keyboard text-orange-500"></i> Input Manual
                </h3>
                <form id="manual-form">
                    <div class="relative">
                        <input type="text" id="manual-input" placeholder="Ketik Kode TRX..." 
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 dark:text-white transition uppercase font-mono">
                        <i class="fas fa-barcode absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                    <button type="submit" class="w-full mt-3 bg-navy-700 hover:bg-navy-800 text-white py-2.5 rounded-xl font-bold transition shadow-md">
                        Proses Manual
                    </button>
                </form>
            </div>

            {{-- Hasil Scan --}}
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6 border border-gray-100 dark:border-gray-700 flex flex-col">
                <h3 class="font-bold text-navy-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-clipboard-check text-blue-500"></i> Hasil Scan
                </h3>
                
                <div id="result-empty" class="flex-1 flex flex-col items-center justify-center text-center opacity-50">
                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-full mb-3">
                        <i class="fas fa-box-open text-4xl text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu scan...</p>
                </div>

                <div id="result-content" class="hidden space-y-4">
                    <div id="res-status-bg" class="p-3 rounded-lg text-center border">
                        <span id="res-status-text" class="font-bold text-lg uppercase tracking-wide">BERHASIL</span>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Kode Transaksi</p>
                            <p id="res-code" class="font-mono font-bold text-navy-800 dark:text-white text-lg">-</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Peminjam</p>
                            <p id="res-user" class="font-medium text-gray-700 dark:text-gray-300">-</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Pesan</p>
                            <p id="res-message" class="text-sm text-gray-600 dark:text-gray-400 font-medium bg-gray-50 dark:bg-gray-700 p-2 rounded mt-1">-</p>
                        </div>
                    </div>
                    <button type="button" onclick="resetResult()" class="w-full py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-bold text-sm hover:bg-gray-300 transition">
                        Scan Lagi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    {{-- CUSTOM CSS UNTUK READER AGAR TIDAK MELUBER --}}
    <style>
        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important; /* Video mengisi kotak tanpa gepeng */
            border-radius: 0.75rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            let html5QrCode = null;
            const beepSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
            const processUrl = "/admin/scan/process";

            // 1. INPUT MANUAL
            const formManual = document.getElementById('manual-form');
            if(formManual) {
                formManual.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const code = document.getElementById('manual-input').value.trim();
                    if(code) processCode(code, false);
                });
            }

            // 2. TOMBOL CONTROL
            const btnStart = document.getElementById('btn-start');
            const btnStop = document.getElementById('btn-stop');
            if(btnStart) btnStart.addEventListener('click', startScanner);
            if(btnStop) btnStop.addEventListener('click', stopScanner);

            // 3. START SCANNER
            async function startScanner() {
                document.getElementById('camera-placeholder').classList.add('hidden');
                btnStart.classList.add('hidden');
                btnStop.classList.remove('hidden');

                if(!html5QrCode) {
                    html5QrCode = new Html5Qrcode("reader");
                }

                // KONFIGURASI YANG DIKEMBALIKAN KE STANDAR (Biar kotak rapi)
                const config = { 
                    fps: 15, 
                    qrbox: 350,      // Ukuran kotak scan fix 250px (standar)
                    aspectRatio: 1.0 // Kotak persegi 1:1
                };

                try {
                    await html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
                } catch (err) {
                    console.warn("Kamera belakang gagal:", err);
                    try {
                        await html5QrCode.start({ facingMode: "user" }, config, onScanSuccess);
                    } catch (err2) {
                        alert("Gagal akses kamera. Cek izin browser.");
                        stopScanner();
                    }
                }
            }

            // 4. STOP SCANNER
            async function stopScanner() {
                if(html5QrCode) {
                    try {
                        if(html5QrCode.isScanning) {
                            await html5QrCode.stop();
                        }
                        html5QrCode.clear();
                    } catch(e) {}
                }
                document.getElementById('camera-placeholder').classList.remove('hidden');
                btnStart.classList.remove('hidden');
                btnStop.classList.add('hidden');
                html5QrCode = null;
            }

            function onScanSuccess(decodedText) {
                processCode(decodedText, true);
            }

            // 5. PROSES SERVER
            function processCode(code, isCamera) {
                beepSound.play();
                if(isCamera && html5QrCode) html5QrCode.pause();

                showResultLoading();
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

                fetch(processUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ qr_code: code })
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || "Server Error");
                    showResultData(data, code);
                    if(isCamera && html5QrCode) setTimeout(() => html5QrCode.resume(), 2500);
                })
                .catch(err => {
                    console.error("Fetch Error:", err);
                    displayError(err.message);
                    if(isCamera && html5QrCode) setTimeout(() => html5QrCode.resume(), 2000);
                });
            }

            // 6. UI FUNCTIONS
            function showResultLoading() {
                const container = document.getElementById('result-content');
                const empty = document.getElementById('result-empty');
                empty.classList.add('hidden');
                container.classList.remove('hidden');
                document.getElementById('res-status-text').innerText = "MEMPROSES...";
                document.getElementById('res-status-bg').className = "p-3 rounded-lg text-center border bg-blue-100 text-blue-800 border-blue-200 animate-pulse";
            }

            function showResultData(data, code) {
                const isSuccess = data.success;
                document.getElementById('res-code').innerText = code;
                document.getElementById('res-user').innerText = isSuccess ? (data.user || "User") : "-";
                document.getElementById('res-message').innerText = data.message;

                const badge = document.getElementById('res-status-bg');
                if(isSuccess) {
                    badge.className = "p-3 rounded-lg text-center border bg-green-100 text-green-800 border-green-200";
                    document.getElementById('res-status-text').innerText = "BERHASIL";
                } else {
                    badge.className = "p-3 rounded-lg text-center border bg-red-100 text-red-800 border-red-200";
                    document.getElementById('res-status-text').innerText = "GAGAL";
                }
            }

            function displayError(message) {
                const container = document.getElementById('result-content');
                const empty = document.getElementById('result-empty');
                empty.classList.add('hidden');
                container.classList.remove('hidden');
                document.getElementById('res-status-text').innerText = "ERROR";
                document.getElementById('res-status-bg').className = "p-3 rounded-lg text-center border bg-red-100 text-red-800 border-red-200";
                document.getElementById('res-message').innerText = message;
            }

            window.resetResult = function() {
                document.getElementById('result-content').classList.add('hidden');
                document.getElementById('result-empty').classList.remove('hidden');
                document.getElementById('manual-input').value = "";
            }
        });
    </script>
</x-app-layout>