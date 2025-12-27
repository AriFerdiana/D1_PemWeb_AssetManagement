<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ITENAS Resource Center') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased min-h-screen flex" 
      x-data="{ 
          sidebarOpen: false,
          darkMode: localStorage.getItem('theme') === 'dark',
          toggleTheme() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          }
      }"
      x-init="if(darkMode) document.documentElement.classList.add('dark');">

    {{-- 1. SIDEBAR (Fixed Position) --}}
    @include('layouts.sidebar')

    {{-- 2. KONTEN UTAMA --}}
    {{-- PERBAIKAN: 'md:ml-64' wajib ada agar konten tidak ketutupan sidebar --}}
    <div class="flex-1 flex flex-col min-w-0 md:ml-64 transition-all duration-300 min-h-screen">
        
        {{-- HEADER NAVIGATION --}}
        <header class="bg-white dark:bg-gray-800 h-20 shadow-sm border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-4 md:px-8 sticky top-0 z-20">
            
            <div class="flex items-center">
                {{-- Hamburger Mobile --}}
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none mr-4 md:hidden">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <h2 class="text-lg md:text-2xl font-black text-gray-800 dark:text-white uppercase tracking-tight truncate">
                    @yield('header', 'Dashboard')
                </h2>
            </div>

            {{-- MENU KANAN --}}
            <div class="flex items-center gap-3 md:gap-4">
                
                {{-- Notifikasi --}}
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 transition relative">
                        <i class="fas fa-bell text-xl"></i>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1.5 right-2 h-2.5 w-2.5 rounded-full bg-red-500 border-2 border-white dark:border-gray-800 animate-pulse"></span>
                        @endif
                    </button>

                    <div x-show="notifOpen" @click.away="notifOpen = false" x-cloak
                         class="absolute right-0 mt-2 w-72 md:w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                            <span class="text-sm font-bold">Notifikasi</span>
                            <span class="text-xs text-gray-500">{{ Auth::user()->unreadNotifications->count() }} Baru</span>
                        </div>
                        <div class="max-h-60 overflow-y-auto custom-scroll">
                            @forelse(Auth::user()->unreadNotifications as $notification)
                                <div class="px-4 py-3 border-b border-gray-50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 cursor-pointer">
                                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $notification->data['message'] ?? 'Info Baru' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <div class="p-4 text-center text-sm text-gray-500">Tidak ada notifikasi.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Dark Mode --}}
                <button @click="toggleTheme()" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 transition">
                    <i class="fas" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon'"></i>
                </button>

                <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 mx-1"></div>

                {{-- Profile --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-3 focus:outline-none text-left">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-bold text-gray-800 dark:text-white leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-[#E65100] font-bold uppercase mt-1">
                                {{ Auth::user()->roles->first()->name ?? 'Mahasiswa' }}
                            </p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-[#E65100] text-white flex items-center justify-center font-bold text-sm shadow-md overflow-hidden ring-2 ring-white dark:ring-gray-700">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/'.Auth::user()->avatar) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()->name, 0, 2) }}
                            @endif
                        </div>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 py-1 z-50 border border-gray-100 dark:border-gray-700">
                        
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 md:hidden">
                            <p class="text-xs font-bold text-gray-800 dark:text-white">{{ Auth::user()->name }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-user-cog w-4 mr-2"></i> Edit Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">
                                <i class="fas fa-sign-out-alt w-4 mr-2"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        {{-- MAIN CONTENT SCROLL AREA --}}
        <main class="flex-1 p-4 md:p-8 overflow-y-auto custom-scroll">
            {{ $slot }}
        </main>

    </div>

    {{-- Overlay Mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 bg-black/50 z-20 md:hidden"
         x-transition.opacity>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        /**
         * 1. NOTIFIKASI OTOMATIS (TOAST)
         * Muncul otomatis jika ada Session Success/Error dari Controller
         */
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif

        @if(session('error'))
            Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
        @endif

        /**
         * 2. KONFIRMASI HAPUS GLOBAL
         * Mencari semua tombol dengan class 'delete-confirm'
         */
        document.addEventListener('click', function(e) {
            const deleteButton = e.target.closest('.delete-confirm');
            if (deleteButton) {
                e.preventDefault();
                const form = deleteButton.closest('form');
                
                Swal.fire({
                    title: 'Hapus Data Permanen?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#E65100', // Oranye ITENAS
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });

        /**
         * 3. FUNGSI KHUSUS PENOLAKAN RESERVASI (MODAL DENGAN INPUT)
         * Digunakan di AdminReservationController
         */
        function rejectReservation(id) {
            Swal.fire({
                title: 'Tolak Peminjaman?',
                text: "Berikan alasan penolakan untuk mahasiswa:",
                input: 'textarea',
                inputPlaceholder: 'Contoh: Barang sedang digunakan untuk praktikum lain...',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Alasan penolakan wajib diisi!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    // Buat form dinamis untuk kirim data ke controller
                    let form = document.createElement('form');
                    form.action = `/admin/reservations/${id}/status`;
                    form.method = 'POST';
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="status" value="rejected">
                        <input type="hidden" name="rejection_note" value="${result.value}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            })
        }
    </script>
</body>
</html>