<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Itenas Resource Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="antialiased bg-gray-50 text-gray-800 font-[Figtree]">

    <nav class="bg-white shadow-sm fixed w-full z-10 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/Logo_Itenas.png/1200px-Logo_Itenas.png" alt="Logo" class="h-8 w-auto mr-3">
                    <span class="font-bold text-xl tracking-tight text-blue-900">Resource Center</span>
                </div>
                <div>
                    @if (Route::has('login'))
                        <div class="space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-blue-600">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-blue-600">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 px-4 py-2 rounded-full bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 overflow-hidden bg-gradient-to-br from-blue-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-blue-900 tracking-tight mb-6">
                Sistem Peminjaman <br> <span class="text-blue-600">Aset & Fasilitas Kampus</span>
            </h1>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                Platform terintegrasi untuk mahasiswa dan dosen ITENAS. Pinjam ruang lab, alat praktikum, dan fasilitas lainnya dengan mudah, cepat, dan transparan.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('login') }}" class="px-8 py-3 rounded-lg bg-blue-600 text-white font-bold text-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-1">
                    Mulai Peminjaman 🚀
                </a>
                <a href="#fitur" class="px-8 py-3 rounded-lg bg-white text-blue-600 border border-blue-200 font-bold text-lg hover:bg-gray-50 shadow transition">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>

    <div id="fitur" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Fitur Unggulan</h2>
                <p class="mt-2 text-gray-500">Memudahkan civitas akademika dalam kegiatan perkuliahan.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-gray-50 rounded-xl hover:shadow-md transition text-center">
                    <div class="w-14 h-14 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl mb-4">🔍</div>
                    <h3 class="text-xl font-bold mb-2">Cek Ketersediaan</h3>
                    <p class="text-gray-600">Lihat stok alat dan jadwal ruang lab secara real-time tanpa perlu bolak-balik ke kampus.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-xl hover:shadow-md transition text-center">
                    <div class="w-14 h-14 mx-auto bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mb-4">⚡</div>
                    <h3 class="text-xl font-bold mb-2">Approval Cepat</h3>
                    <p class="text-gray-600">Notifikasi email otomatis ke laboran membuat proses persetujuan lebih cepat.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-xl hover:shadow-md transition text-center">
                    <div class="w-14 h-14 mx-auto bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-2xl mb-4">🎟️</div>
                    <h3 class="text-xl font-bold mb-2">E-Ticket QR</h3>
                    <p class="text-gray-600">Ambil barang cukup tunjukkan QR Code transaksi di HP kamu. Paperless & Modern.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-900 text-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <span class="font-bold text-lg">Kelompok D1 - UAS Pemrograman Web</span>
                <p class="text-gray-400 text-sm mt-1">&copy; 2026 Teknik Informatika ITENAS.</p>
            </div>
            <div class="flex space-x-6">
                <a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a>
                <a href="#" class="text-gray-400 hover:text-white">Terms of Service</a>
            </div>
        </div>
    </footer>

</body>
</html>