<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ 
          darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
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
      x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); if(darkMode) document.documentElement.classList.add('dark');"
      :class="{ 'dark': darkMode }">
      
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ITENAS Asset') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
    
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        
        @include('layouts.sidebar')

        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 z-20 bg-black bg-opacity-50 md:hidden transition-opacity"></div>

        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-6 z-10 transition-colors duration-300">
                
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-navy-800 dark:text-white leading-tight">
                        @yield('header', 'Dashboard') 
                    </h2>
                </div>

                <div class="flex items-center space-x-3 md:space-x-4">
                    
                    <div class="hidden md:block relative">
                        <form action="{{ route('assets.index') }}" method="GET">
                            <input type="text" name="search" placeholder="Cari aset..." class="pl-10 pr-4 py-2 bg-gray-100 dark:bg-gray-700 border-none rounded-full text-sm focus:ring-2 focus:ring-teal-500 w-64 text-gray-700 dark:text-gray-200 placeholder-gray-400 transition-colors">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                        </form>
                    </div>
                    
                    <button @click="toggleTheme()" class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition transform active:scale-95">
                        <i class="fas fa-moon" x-show="!darkMode"></i>
                        <i class="fas fa-sun text-yellow-400" x-show="darkMode" x-cloak></i>
                    </button>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition focus:outline-none">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-1 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-gray-800"></span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-sm font-bold text-gray-700 dark:text-white">Notifikasi</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <p class="text-sm text-gray-800 dark:text-gray-200 font-medium">Sistem Siap</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Selamat datang di sistem manajemen aset.</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="relative ml-3" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</p>
                            </div>
                            <div class="h-9 w-9 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center text-teal-700 dark:text-teal-300 font-bold border border-teal-200 dark:border-teal-700">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-user-circle mr-2 w-4"></i> Edit Profile
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30">
                                    <i class="fas fa-sign-out-alt mr-2 w-4"></i> Log Out
                                </a>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6 transition-colors duration-300">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/50 border-l-4 border-green-500 text-green-700 dark:text-green-300 rounded shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center"><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</div>
                        <button @click="show = false"><i class="fas fa-times"></i></button>
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>