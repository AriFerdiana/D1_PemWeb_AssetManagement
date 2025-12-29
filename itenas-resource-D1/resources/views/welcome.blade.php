<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITENAS Resource Center - Official Portal</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        itenas: { orange: '#E65100', lightOrange: '#FF9800', navy: '#1E1B4B', softNavy: '#312E81', footerBg: '#1F2937' }
                    },
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    animation: { 'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite' }
                }
            }
        }
    </script>
    <style>
        .bg-pattern {
            background-image: radial-gradient(#E65100 0.5px, transparent 0.5px), radial-gradient(#E65100 0.5px, #f8fafc 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.6;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-800 bg-gray-50 relative overflow-x-hidden w-full" 
      x-data="{ atTop: true }" 
      @scroll.window="atTop = (window.pageYOffset < 50)">

    <nav class="fixed w-full z-50 transition-all duration-300" 
         :class="atTop ? 'bg-transparent py-4' : 'bg-white/95 backdrop-blur-md border-b border-gray-200 py-2 shadow-sm'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3 cursor-pointer group" onclick="window.scrollTo(0,0)">
                    <img src="https://uat-web.itenas.ac.id/assets/images/logo-7.png" alt="Logo Itenas" 
                         class="h-12 w-auto transition-all duration-300 transform group-hover:scale-105"
                         :class="atTop ? 'brightness-0 invert opacity-90' : ''">
                    <div class="flex flex-col">
                        <h1 class="font-black text-xl tracking-tight leading-none transition-colors duration-300"
                            :class="atTop ? 'text-white' : 'text-itenas-navy'">ITENAS</h1>
                        <p class="text-[10px] font-bold text-itenas-orange tracking-[0.2em] uppercase leading-none mt-0.5">Resource Center</p>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-6"> <div class="flex gap-5 text-sm font-semibold transition-colors duration-300"
                         :class="atTop ? 'text-white/90' : 'text-gray-600'">
                        <a href="#beranda" class="hover:text-itenas-orange transition">Beranda</a>
                        <a href="#kategori" class="hover:text-itenas-orange transition">Kategori</a>
                        <a href="#fitur" class="hover:text-itenas-orange transition">Fitur</a>
                        <a href="#testimoni" class="hover:text-itenas-orange transition">Testimoni</a>
                        <a href="#faq" class="hover:text-itenas-orange transition">FAQ</a>
                        <a href="#peta" class="hover:text-itenas-orange transition">Lokasi</a>
                    </div>
                    
                    <div class="h-6 w-px bg-current opacity-20 mx-2" :class="atTop ? 'text-white' : 'text-gray-400'"></div>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold hover:text-itenas-orange transition" :class="atTop ? 'text-white' : 'text-itenas-navy'">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold hover:text-itenas-orange transition transition-colors duration-300" :class="atTop ? 'text-white' : 'text-itenas-navy'">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 rounded-xl bg-itenas-orange hover:bg-orange-700 text-white text-sm font-bold shadow-lg shadow-orange-200/50 transition transform hover:-translate-y-0.5">Daftar</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section id="beranda" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden min-h-[95vh] flex items-center">
        <div class="absolute inset-0 z-0">
            <img src="https://ayokuliah.info/wp-content/uploads/2023/12/kampus-itenas-bandung_169.jpeg" alt="Kampus Itenas" class="w-full h-full object-cover scale-105">
            <div class="absolute inset-0 bg-itenas-navy/85 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-pattern opacity-10 mix-blend-overlay"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-orange-300 text-xs font-bold uppercase tracking-wider mb-6 animate-pulse-slow">
                        <span class="w-2 h-2 rounded-full bg-itenas-orange"></span>
                        Sistem Manajemen Aset Itenas
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-6 drop-shadow-2xl">
                        Pusat Kendali Aset <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-itenas-orange to-yellow-400">Laboratorium Itenas</span>
                    </h1>
                    <p class="mt-6 text-lg text-gray-200 font-medium leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Digitalisasi peminjaman alat, reservasi laboratorium, dan pelaporan maintenance dalam satu platform terintegrasi.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                        <a href="{{ route('login') }}" class="px-8 py-4 rounded-xl bg-itenas-orange hover:bg-orange-600 text-white font-bold text-lg shadow-xl shadow-orange-900/40 transition transform hover:-translate-y-1 flex items-center justify-center gap-3">
                            <i class="fas fa-sign-in-alt"></i> Login Sekarang
                        </a>
                        <a href="#faq" class="px-8 py-4 rounded-xl bg-white/10 border-2 border-white/30 text-white hover:bg-white/20 font-bold text-lg transition flex items-center justify-center gap-3 backdrop-blur-sm">
                            <i class="fas fa-question-circle"></i> Bantuan
                        </a>
                    </div>
                </div>

                <div class="hidden lg:block relative z-10">
                    <div class="absolute -inset-2 bg-gradient-to-tr from-itenas-orange to-blue-600 rounded-[2rem] blur-xl opacity-40"></div>
                    <div class="relative bg-white rounded-[2rem] p-8 shadow-2xl border border-gray-100">
                        <h3 class="text-2xl font-black text-itenas-navy mb-2">Cek Ketersediaan</h3>
                        <p class="text-sm text-gray-500 mb-6">Cari alat atau ruangan tanpa login.</p>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Rencana Peminjaman</label>
                                <div class="relative">
                                    <input type="date" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-itenas-orange text-sm text-gray-700">
                                    <i class="fas fa-calendar-alt absolute left-4 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kategori Aset</label>
                                <div class="relative">
                                    <select class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-itenas-orange text-sm text-gray-700 appearance-none">
                                        <option>Semua Kategori</option>
                                        <option>Komputer & Laptop</option>
                                        <option>Alat Ukur Elektronik</option>
                                        <option>Mesin Perkakas</option>
                                        <option>Ruang Laboratorium</option>
                                    </select>
                                    <i class="fas fa-cubes absolute left-4 top-3.5 text-gray-400"></i>
                                    <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 text-xs"></i>
                                </div>
                            </div>
                            <a href="{{ route('login') }}" class="w-full py-4 bg-itenas-navy hover:bg-itenas-softNavy text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <i class="fas fa-search"></i> Cek Ketersediaan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 160" class="text-white fill-current">
                <path d="M0,128L48,122.7C96,117,192,107,288,101.3C384,96,480,96,576,106.7C672,117,768,139,864,138.7C960,139,1056,117,1152,106.7C1248,96,1344,96,1392,96L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <section id="statistik" class="py-10 bg-white -mt-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="p-6 bg-gray-50 rounded-2xl text-center border border-gray-100 hover:border-itenas-orange transition group">
                    <p class="text-3xl font-black text-itenas-navy">500+</p>
                    <p class="text-xs text-gray-500 uppercase font-bold mt-1">Aset Terdaftar</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-2xl text-center border border-gray-100 hover:border-itenas-orange transition group">
                    <p class="text-3xl font-black text-itenas-navy">24</p>
                    <p class="text-xs text-gray-500 uppercase font-bold mt-1">Laboratorium</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-2xl text-center border border-gray-100 hover:border-itenas-orange transition group">
                    <p class="text-3xl font-black text-itenas-navy">100%</p>
                    <p class="text-xs text-gray-500 uppercase font-bold mt-1">Paperless</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-2xl text-center border border-gray-100 hover:border-itenas-orange transition group">
                    <p class="text-3xl font-black text-itenas-navy">QR</p>
                    <p class="text-xs text-gray-500 uppercase font-bold mt-1">Sirkulasi Cepat</p>
                </div>
            </div>
        </div>
    </section>

    <section id="kategori" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-itenas-orange font-bold tracking-widest uppercase text-sm mb-2">Eksplorasi</h2>
                <h3 class="text-3xl md:text-4xl font-black text-itenas-navy">Kategori Aset Populer</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer text-center group">
                    <div class="w-16 h-16 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:bg-blue-600 group-hover:text-white transition"><i class="fas fa-laptop"></i></div>
                    <h4 class="font-bold text-gray-800">Komputer</h4>
                    <p class="text-xs text-gray-500 mt-1">Laptop, PC, Server</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer text-center group">
                    <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-600 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:bg-purple-600 group-hover:text-white transition"><i class="fas fa-microscope"></i></div>
                    <h4 class="font-bold text-gray-800">Alat Ukur</h4>
                    <p class="text-xs text-gray-500 mt-1">Mikroskop, Multimeter</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer text-center group">
                    <div class="w-16 h-16 mx-auto bg-red-50 text-red-600 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:bg-red-600 group-hover:text-white transition"><i class="fas fa-video"></i></div>
                    <h4 class="font-bold text-gray-800">Multimedia</h4>
                    <p class="text-xs text-gray-500 mt-1">Kamera, Proyektor</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-lg transition cursor-pointer text-center group">
                    <div class="w-16 h-16 mx-auto bg-orange-50 text-orange-600 rounded-full flex items-center justify-center text-2xl mb-4 group-hover:bg-itenas-orange group-hover:text-white transition"><i class="fas fa-cogs"></i></div>
                    <h4 class="font-bold text-gray-800">Mesin</h4>
                    <p class="text-xs text-gray-500 mt-1">CNC, Bubut, 3D Print</p>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-itenas-orange font-bold tracking-widest uppercase text-sm mb-2">Fitur Utama</h2>
                <h3 class="text-3xl md:text-4xl font-black text-itenas-navy">Teknologi Terintegrasi</h3>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="group p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-itenas-orange hover:shadow-2xl hover:shadow-orange-100 transition duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-white border border-gray-200 text-itenas-orange flex items-center justify-center text-2xl mb-6 shadow-sm group-hover:bg-itenas-orange group-hover:text-white transition"><i class="fas fa-layer-group"></i></div>
                    <h4 class="text-xl font-bold text-itenas-navy mb-3">Silo Data Protection</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">Laboran prodi tertentu hanya dapat mengakses aset prodinya sendiri.</p>
                </div>
                <div class="group p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-itenas-orange hover:shadow-2xl hover:shadow-orange-100 transition duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-white border border-gray-200 text-itenas-orange flex items-center justify-center text-2xl mb-6 shadow-sm group-hover:bg-itenas-orange group-hover:text-white transition"><i class="fas fa-qrcode"></i></div>
                    <h4 class="text-xl font-bold text-itenas-navy mb-3">QR Code Sirkulasi</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">Sistem check-in dan check-out instan dengan scan tiket QR.</p>
                </div>
                <div class="group p-8 rounded-3xl bg-gray-50 border border-gray-100 hover:border-itenas-orange hover:shadow-2xl hover:shadow-orange-100 transition duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-white border border-gray-200 text-itenas-orange flex items-center justify-center text-2xl mb-6 shadow-sm group-hover:bg-itenas-orange group-hover:text-white transition"><i class="fas fa-tools"></i></div>
                    <h4 class="text-xl font-bold text-itenas-navy mb-3">Laporan Maintenance</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">Pelaporan kerusakan alat terpusat dan transparan.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="testimoni" class="py-24 bg-gray-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-itenas-orange/10 rounded-full filter blur-3xl -translate-y-20 translate-x-20"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-itenas-navy/10 rounded-full filter blur-3xl translate-y-20 -translate-x-20"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-itenas-orange font-bold tracking-widest uppercase text-sm mb-2">Testimoni</h2>
                <h3 class="text-3xl md:text-4xl font-black text-itenas-navy">Apa Kata Mereka?</h3>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 rounded-3xl bg-white shadow-xl shadow-gray-100 border border-gray-100 flex flex-col hover:transform hover:-translate-y-1 transition duration-300">
                    <div class="text-itenas-orange text-3xl mb-6"><i class="fas fa-quote-left"></i></div>
                    <p class="text-gray-600 text-sm leading-relaxed flex-grow">"Sistem ini sangat membantu saya sebagai mahasiswa tingkat akhir. Peminjaman alat untuk tugas akhir jadi jauh lebih mudah dan cepat dengan fitur scan QR."</p>
                    <div class="mt-8 flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-gray-200 overflow-hidden bg-cover" style="background-image: url('https://ui-avatars.com/api/?name=Rizky+Pratama&background=E65100&color=fff');"></div>
                        <div>
                            <h4 class="font-bold text-itenas-navy text-sm">Rizky Pratama</h4>
                            <p class="text-xs text-gray-500">Mahasiswa Teknik Elektro</p>
                        </div>
                    </div>
                </div>
                <div class="p-8 rounded-3xl bg-white shadow-xl shadow-gray-100 border border-gray-100 flex flex-col hover:transform hover:-translate-y-1 transition duration-300">
                    <div class="text-itenas-orange text-3xl mb-6"><i class="fas fa-quote-left"></i></div>
                    <p class="text-gray-600 text-sm leading-relaxed flex-grow">"Sebagai laboran, fitur Silo Data sangat krusial. Saya tidak perlu khawatir data aset prodi saya tercampur dengan prodi lain."</p>
                    <div class="mt-8 flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-gray-200 overflow-hidden bg-cover" style="background-image: url('https://ui-avatars.com/api/?name=Rina+Santoso&background=1E1B4B&color=fff');"></div>
                        <div>
                            <h4 class="font-bold text-itenas-navy text-sm">Bu Rina Santoso</h4>
                            <p class="text-xs text-gray-500">Laboran Informatika</p>
                        </div>
                    </div>
                </div>
                <div class="p-8 rounded-3xl bg-white shadow-xl shadow-gray-100 border border-gray-100 flex flex-col hover:transform hover:-translate-y-1 transition duration-300">
                    <div class="text-itenas-orange text-3xl mb-6"><i class="fas fa-quote-left"></i></div>
                    <p class="text-gray-600 text-sm leading-relaxed flex-grow">"IRC memudahkan pemantauan penggunaan laboratorium. Sistem booking online membuat jadwal penggunaan ruangan menjadi jelas."</p>
                    <div class="mt-8 flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-gray-200 overflow-hidden bg-cover" style="background-image: url('https://ui-avatars.com/api/?name=Budi+Setiawan&background=E65100&color=fff');"></div>
                        <div>
                            <h4 class="font-bold text-itenas-navy text-sm">Pak Budi Setiawan</h4>
                            <p class="text-xs text-gray-500">Dosen Teknik Mesin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="py-20 bg-itenas-navy relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-itenas-orange/10 rounded-full filter blur-3xl -translate-y-20 translate-x-20"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-itenas-orange font-bold tracking-widest uppercase text-sm mb-2">Bantuan</h2>
                <h3 class="text-3xl font-black text-white">Pertanyaan Umum (FAQ)</h3>
            </div>
            <div class="space-y-4" x-data="{ active: 1 }">
                <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden transition-all duration-300" :class="active === 1 ? 'bg-white/10 shadow-lg' : ''">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-4 text-left flex justify-between items-center text-white font-bold">
                        <span>Bagaimana cara meminjam alat?</span>
                        <i class="fas" :class="active === 1 ? 'fa-minus text-itenas-orange' : 'fa-plus'"></i>
                    </button>
                    <div x-show="active === 1" x-collapse class="px-6 pb-4 text-gray-300 text-sm leading-relaxed">Login, cari alat di katalog, pilih tanggal, dan tunggu persetujuan.</div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden transition-all duration-300" :class="active === 2 ? 'bg-white/10 shadow-lg' : ''">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-4 text-left flex justify-between items-center text-white font-bold">
                        <span>Apakah ada denda keterlambatan?</span>
                        <i class="fas" :class="active === 2 ? 'fa-minus text-itenas-orange' : 'fa-plus'"></i>
                    </button>
                    <div x-show="active === 2" x-collapse class="px-6 pb-4 text-gray-300 text-sm leading-relaxed">Ya. Keterlambatan dikenakan denda sesuai kebijakan lab.</div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden transition-all duration-300" :class="active === 3 ? 'bg-white/10 shadow-lg' : ''">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-4 text-left flex justify-between items-center text-white font-bold">
                        <span>Apakah saya bisa membatalkan peminjaman?</span>
                        <i class="fas" :class="active === 3 ? 'fa-minus text-itenas-orange' : 'fa-plus'"></i>
                    </button>
                    <div x-show="active === 3" x-collapse class="px-6 pb-4 text-gray-300 text-sm leading-relaxed">Bisa. Selama status peminjaman masih "Pending", Anda dapat membatalkannya langsung melalui menu "Data Transaksi".</div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden transition-all duration-300" :class="active === 4 ? 'bg-white/10 shadow-lg' : ''">
                    <button @click="active = (active === 4 ? null : 4)" class="w-full px-6 py-4 text-left flex justify-between items-center text-white font-bold">
                        <span>Bagaimana jika alat rusak saat dipinjam?</span>
                        <i class="fas" :class="active === 4 ? 'fa-minus text-itenas-orange' : 'fa-plus'"></i>
                    </button>
                    <div x-show="active === 4" x-collapse class="px-6 pb-4 text-gray-300 text-sm leading-relaxed">Segera laporkan kepada Laboran. Kerusakan akibat kelalaian akan dikenakan sanksi penggantian.</div>
                </div>
            </div>
        </div>
    </section>

    <section id="peta" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-itenas-orange font-bold tracking-widest uppercase text-sm mb-2">Peta Lokasi</h2>
                <h3 class="text-3xl md:text-4xl font-black text-itenas-navy">Denah Kampus Itenas</h3>
            </div>
            <div class="rounded-3xl overflow-hidden shadow-2xl border-4 border-white">
                <img src="https://www.itenas.ac.id/wp-content/uploads/2021/03/Peta-Itenas-Bandung-2021.png" alt="Peta Itenas" class="w-full h-auto transform hover:scale-105 transition duration-700">
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <div class="bg-gradient-to-r from-itenas-navy to-blue-900 rounded-3xl p-10 relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-black text-white mb-4">Siap Menggunakan Laboratorium?</h2>
                    <p class="text-blue-100 mb-8 max-w-xl mx-auto">Daftarkan akun Anda sekarang untuk akses penuh.</p>
                    <a href="{{ route('register') }}" class="px-8 py-3 bg-itenas-orange hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-105">
                        Daftar Akun Mahasiswa
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-itenas-footerBg text-gray-300 pt-16 pb-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <img src="https://uat-web.itenas.ac.id/assets/images/logo-7.png" alt="Logo Itenas" class="h-10 w-auto brightness-0 invert opacity-90">
                    </div>
                    <p class="text-xs leading-relaxed opacity-70 mb-4">Sistem informasi terpadu pengelolaan sumber daya laboratorium Institut Teknologi Nasional Bandung. Mewujudkan tata kelola aset yang akuntabel dan modern.</p>
                    <div class="text-xs text-gray-500">&copy; {{ date('Y') }} ITENAS Resource Center.</div>
                </div>
                <div>
                    <h4 class="font-bold text-white text-sm mb-4 uppercase tracking-wider">Navigasi</h4>
                    <ul class="space-y-2 text-sm opacity-80">
                        <li><a href="#beranda" class="hover:text-itenas-orange transition">Home</a></li>
                        <li><a href="#fitur" class="hover:text-itenas-orange transition">Fitur</a></li>
                        <li><a href="#faq" class="hover:text-itenas-orange transition">Bantuan</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-itenas-orange transition">Login</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white text-sm mb-4 uppercase tracking-wider">Kunjungi Kami</h4>
                    <ul class="space-y-3 text-sm opacity-80">
                        <li>
                            <a href="https://www.itenas.ac.id" target="_blank" class="flex items-center gap-2 hover:text-itenas-orange transition">
                                <i class="fas fa-globe w-4 text-center"></i> Website Itenas
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/itenas.official/" target="_blank" class="flex items-center gap-2 hover:text-pink-500 transition">
                                <i class="fab fa-instagram w-4 text-center"></i> Instagram
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/channel/UCXNl5jOSO9os1nOr40oizXg" target="_blank" class="flex items-center gap-2 hover:text-red-500 transition">
                                <i class="fab fa-youtube w-4 text-center"></i> YouTube
                            </a>
                        </li>
                        <li>
                            <a href="https://x.com/@itenas_official" target="_blank" class="flex items-center gap-2 hover:text-blue-400 transition">
                                <i class="fab fa-twitter w-4 text-center"></i> Twitter / X
                            </a>
                        </li>
                        <li>
                            <a href="https://web.facebook.com/itenas.official/?_rdc=1&_rdr#" target="_blank" class="flex items-center gap-2 hover:text-blue-600 transition">
                                <i class="fab fa-facebook w-4 text-center"></i> Facebook
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white text-sm mb-4 uppercase tracking-wider">Kontak</h4>
                    <ul class="space-y-3 text-sm opacity-80">
                        <li class="flex items-start gap-3"><span class="leading-relaxed text-xs">Jl. PH. H. Mustofa No. 23 – Bandung, 40124 Indonesia</span></li>
                        <li class="flex items-center gap-3"><i class="fas fa-phone text-itenas-orange text-xs"></i><span class="text-xs">+62 22 7272215</span></li>
                        <li class="flex items-center gap-3"><i class="fas fa-envelope text-itenas-orange text-xs"></i><a href="mailto:humas@itenas.ac.id" class="text-xs hover:text-itenas-orange">humas@itenas.ac.id</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>