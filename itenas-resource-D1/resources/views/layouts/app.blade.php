<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ITENAS Resource Center</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { itenas: { primary: '#F97316', dark: '#C2410C' } }
                }
            }
        }
    </script>

    <style>
        /* PAKSA SEMUA WARNA HIJAU LAMA MENJADI ORANYE ITENAS */
        .bg-teal-500, .bg-teal-600, .bg-emerald-500, .bg-emerald-600 { background-color: #F97316 !important; }
        .text-teal-600, .text-emerald-600 { color: #F97316 !important; }
        .border-teal-500, .border-emerald-500 { border-color: #F97316 !important; }
        .hover\:bg-teal-600:hover { background-color: #C2410C !important; }
        
        /* Style tambahan untuk Sidebar Active */
        .sidebar-active { background-color: white !important; color: #F97316 !important; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); font-weight: 700; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-72 fixed inset-y-0 left-0 bg-gradient-to-b from-orange-600 to-orange-800 text-white shadow-2xl overflow-y-auto z-50 flex flex-col">
            <div class="h-24 flex items-center justify-start px-6 border-b border-white/20 shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="bg-white p-1.5 rounded-lg shadow-sm">
                        <img src="https://uat-web.itenas.ac.id/assets/images/logo-7.png" alt="Logo" class="h-8 w-auto">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-tight">ITENAS</h1>
                        <p class="text-[10px] text-orange-100 opacity-90 uppercase tracking-widest font-semibold">Resource Center</p>
                    </div>
                </div>
            </div>

            <nav class="mt-6 px-4 space-y-1 flex-grow">
                @hasrole('Superadmin|Laboran')
                    <p class="px-4 text-[10px] font-bold text-orange-200 uppercase mb-2">Utama</p>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-th-large w-8 text-center"></i> Dashboard
                    </a>

                    <p class="px-4 text-[10px] font-bold text-orange-200 uppercase mt-4 mb-2">Sirkulasi</p>
                    <a href="{{ route('admin.reservations.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.reservations.*') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-exchange-alt w-8 text-center"></i> Data Transaksi
                    </a>
                    <a href="{{ route('admin.scan.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.scan.*') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-qrcode w-8 text-center"></i> Scan QR Code
                    </a>

                    <p class="px-4 text-[10px] font-bold text-orange-200 uppercase mt-4 mb-2">Master Data</p>
                    <a href="{{ route('admin.assets.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.assets.*') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-box w-8 text-center"></i> Data Aset
                    </a>
                    <a href="{{ route('admin.labs.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.labs.*') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-flask w-8 text-center"></i> Data Lab
                    </a>
                    <a href="{{ route('admin.prodis.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.prodis.*') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-university w-8 text-center"></i> Data Prodi
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-tags w-8 text-center"></i> Kategori Aset
                    </a>
                    <a href="{{ route('admin.maintenances.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.maintenances.*') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-tools w-8 text-center"></i> Log Perawatan
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-users w-8 text-center"></i> Data Pengguna
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-home w-8 text-center"></i> Beranda
                    </a>
                    <a href="{{ route('assets.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('assets.*') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-search w-8 text-center"></i> Pinjam Alat
                    </a>
                    <a href="{{ route('reservations.assets') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('reservations.*') ? 'sidebar-active' : 'hover:bg-white/10 text-white' }}">
                        <i class="fas fa-list w-8 text-center"></i> Status Saya
                    </a>
                @endhasrole
            </nav>

            <div class="p-4 border-t border-white/10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-white hover:bg-red-500 rounded-xl transition font-medium">
                        <i class="fas fa-sign-out-alt w-8 text-center"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 ml-72 flex flex-col h-screen overflow-y-auto bg-gray-50">
            <header class="bg-white shadow-sm h-20 flex items-center justify-between px-8 sticky top-0 z-40">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">
                        @yield('header', 'Panel Sistem')
                    </h2>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-800 leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-orange-600 font-bold uppercase mt-1">
                            {{ Auth::user()->roles->first()->name ?? 'User' }}
                        </p>
                    </div>
                    <img class="h-10 w-10 rounded-full border-2 border-orange-500" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=F97316&color=fff">
                </div>
            </header>

            <main class="p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>