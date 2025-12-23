<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Scanner QR Code</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center">
                
                <div id="setup-area">
                    <p class="mb-4 dark:text-gray-300">Klik tombol di bawah untuk mengaktifkan kamera</p>
                    <button onclick="initScanner()" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                        Buka Kamera Scanner
                    </button>
                </div>

                <div id="reader-container" class="hidden">
                    <h3 class="mb-4 dark:text-white font-bold text-lg">Arahkan Kamera ke QR Code</h3>
                    <div id="reader" class="rounded-lg overflow-hidden border-4 border-blue-500 bg-black"></div>
                    <button onclick="location.reload()" class="mt-4 text-sm text-red-500 underline">Matikan/Reset Kamera</button>
                </div>

                <div id="result" class="mt-4 p-4 rounded-lg hidden text-lg"></div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <script>
        let html5QrCode;

        function initScanner() {
            document.getElementById('setup-area').classList.add('hidden');
            document.getElementById('reader-container').classList.remove('hidden');

            // Inisialisasi scanner
            html5QrCode = new Html5Qrcode("reader");
            
            const config = { 
                fps: 15, 
                qrbox: { width: 250, height: 250 } 
            };

            // Memulai kamera belakang (Environment)
            html5QrCode.start(
                { facingMode: "environment" }, 
                config, 
                onScanSuccess
            ).catch(err => {
                console.warn("Gagal kamera belakang, mencoba kamera depan/webcam...");
                // Jika gagal (biasanya di Laptop), gunakan kamera default mana saja
                html5QrCode.start({ facingMode: "user" }, config, onScanSuccess)
                .catch(err2 => {
                    alert("Kamera tidak dapat diakses. Pastikan izin kamera diizinkan dan tidak sedang dipakai aplikasi lain (Zoom/GMeet).");
                });
            });
        }

        function onScanSuccess(decodedText) {
            // Berhenti scan sementara
            html5QrCode.stop().then(() => {
                sendDataToServer(decodedText);
            });
        }

        function sendDataToServer(code) {
            const resultDiv = document.getElementById('result');
            resultDiv.classList.remove('hidden');
            resultDiv.className = "mt-4 p-4 rounded-lg bg-blue-100 text-blue-800 animate-pulse";
            resultDiv.innerText = "Memproses data: " + code;

            fetch("{{ route('admin.scan.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ qr_code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.className = "mt-4 p-4 rounded-lg bg-green-100 text-green-800 font-bold";
                    resultDiv.innerText = data.message;
                    new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3').play();
                } else {
                    resultDiv.className = "mt-4 p-4 rounded-lg bg-red-100 text-red-800 font-bold";
                    resultDiv.innerText = data.message;
                }
                
                // Reload halaman setelah 4 detik untuk scan berikutnya
                setTimeout(() => { location.reload(); }, 4000);
            })
            .catch(err => {
                alert("Error saat menghubungi server.");
                location.reload();
            });
        }
    </script>

    <style>
        /* CSS untuk memastikan reader punya tinggi awal agar terlihat saat loading */
        #reader {
            min-height: 300px;
            width: 100% !important;
        }
        #reader video {
            object-fit: cover !important;
        }
    </style>
</x-app-layout>