<x-app-layout>
    @section('header', 'Scanner Transaksi')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-150px)]">
        
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

                <div class="relative flex-1 bg-black rounded-xl overflow-hidden flex items-center justify-center group max-h-[500px]">
                    <div id="reader" class="w-full h-full"></div>
                    
                    <div id="camera-placeholder" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 text-white z-10">
                        <i class="fas fa-qrcode text-6xl text-gray-700 mb-4"></i>
                        <p class="text-gray-400 text-sm">Pastikan pencahayaan cukup</p>
                    </div>
                </div>

                <div class="mt-4 flex gap-3">
                    <button onclick="startScanner()" id="btn-start" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-xl font-bold transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-power-off"></i> Aktifkan Kamera
                    </button>
                    <button onclick="stopScanner()" id="btn-stop" class="hidden flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-bold transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-stop"></i> Stop Kamera
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-6 flex flex-col">
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="font-bold text-navy-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-keyboard text-orange-500"></i> Input Manual
                </h3>
                <form action="{{ route('admin.scan.process') }}" method="POST">
                    @csrf
                    <div class="relative">
                        <input type="text" name="transaction_code" placeholder="Ketik Kode TRX..." 
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 dark:text-white transition">
                        <i class="fas fa-barcode absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                    <button type="submit" class="w-full mt-3 bg-navy-700 hover:bg-navy-800 text-white py-2.5 rounded-xl font-bold transition">
                        Proses Manual
                    </button>
                </form>
            </div>

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
                            <p class="text-xs text-gray-400 uppercase">Kode Transaksi</p>
                            <p id="res-code" class="font-mono font-bold text-navy-800 dark:text-white text-lg">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Peminjam</p>
                            <p id="res-user" class="font-medium text-gray-700 dark:text-gray-300">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Waktu</p>
                            <p id="res-time" class="text-sm text-gray-500">-</p>
                        </div>
                    </div>
                    <button onclick="resetResult()" class="w-full py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-bold text-sm hover:bg-gray-300 transition">
                        Scan Lagi
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    <style>
        #reader {
            width: 100%;
            height: 100%;
        }
        #reader video {
            object-fit: cover; /* Video full screen tanpa gepeng */
            border-radius: 0.75rem;
        }
    </style>

    <script>
        let html5QrCode;
        const beepSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');

        function startScanner() {
            document.getElementById('camera-placeholder').classList.add('hidden');
            document.getElementById('btn-start').classList.add('hidden');
            document.getElementById('btn-stop').classList.remove('hidden');

            html5QrCode = new Html5Qrcode("reader");
            
            // === CONFIG BARU: KOTAK LEBIH BESAR ===
            const config = { 
                fps: 10, 
                // Ukuran kotak scan: Lebar 450px, Tinggi 350px (Biar pas di Laptop)
                qrbox: { width: 450, height: 350 }, 
                aspectRatio: 1.333333 // 4:3 Ratio standard webcam
            };

            html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
            .catch(err => {
                console.log("Error kamera belakang, coba kamera depan.");
                html5QrCode.start({ facingMode: "user" }, config, onScanSuccess);
            });
        }

        function stopScanner() {
            if(html5QrCode) {
                html5QrCode.stop().then(() => {
                    document.getElementById('camera-placeholder').classList.remove('hidden');
                    document.getElementById('btn-start').classList.remove('hidden');
                    document.getElementById('btn-stop').classList.add('hidden');
                });
            }
        }

        function onScanSuccess(decodedText) {
            beepSound.play();
            html5QrCode.pause();
            showResultLoading();

            fetch("{{ route('admin.scan.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ qr_code: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                showResultData(data, decodedText);
                setTimeout(() => html5QrCode.resume(), 3000);
            })
            .catch(err => {
                alert("Gagal koneksi server");
                html5QrCode.resume();
            });
        }

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
            document.getElementById('res-user').innerText = isSuccess ? (data.user || "User") : "Tidak Diketahui";
            document.getElementById('res-time').innerText = new Date().toLocaleTimeString();
            document.getElementById('res-status-text').innerText = data.message;

            const badge = document.getElementById('res-status-bg');
            if(isSuccess) {
                badge.className = "p-3 rounded-lg text-center border bg-green-100 text-green-800 border-green-200";
            } else {
                badge.className = "p-3 rounded-lg text-center border bg-red-100 text-red-800 border-red-200";
            }
        }

        function resetResult() {
            document.getElementById('result-content').classList.add('hidden');
            document.getElementById('result-empty').classList.remove('hidden');
        }
    </script>
</x-app-layout>