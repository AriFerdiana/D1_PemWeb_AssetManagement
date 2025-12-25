<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="bg-navy-800 text-white w-64 flex flex-col flex-shrink-0 fixed md:relative inset-y-0 left-0 z-30 transition-transform duration-300 ease-in-out md:translate-x-0 shadow-xl border-r border-navy-700">
    
    {{-- Header Logo --}}
    <div class="h-16 flex items-center px-6 bg-navy-900 border-b border-navy-700">
        <div class="flex items-center space-x-3">
            <div class="bg-teal-500 w-8 h-8 rounded-lg flex items-center justify-center shadow-lg shadow-teal-500/50">
                <i class="fas fa-server text-white text-sm"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold tracking-wide text-white">ITENAS</h1>
                <p class="text-[10px] text-teal-400 font-medium uppercase tracking-widest">Resource Center</p>
            </div>
        </div>
        <button @click="sidebarOpen = false" class="md:hidden ml-auto text-gray-400 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    {{-- Menu Scrollable --}}
    <div class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
        
        <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Navigasi Utama</p>
        
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-teal-600 text-white shadow-md' : 'text-gray-400 hover:bg-navy-700 hover:text-white' }}">
            <i class="fas fa-chart-pie w-5 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-teal-500' }}"></i>
            <span class="font-medium text-sm">Dashboard</span>
        </a>

        {{-- MENU MAHASISWA --}}
        @unlessrole('Superadmin|Laboran')
            <div class="mt-6 mb-2">
                <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Layanan Peminjaman</p>
            </div>

            <a href="{{ route('assets.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('assets.*') ? 'bg-teal-600 text-white shadow-md' : 'text-gray-400 hover:bg-navy-700 hover:text-white' }}">
                <i class="fas fa-box-open w-5 text-center {{ request()->routeIs('assets.*') ? 'text-white' : 'text-blue-400' }}"></i>
                <span class="font-medium text-sm">Katalog Alat</span>
            </a>

            <a href="{{ route('rooms.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('rooms.*') ? 'bg-teal-600 text-white shadow-md' : 'text-gray-400 hover:bg-navy-700 hover:text-white' }}">
                <i class="fas fa-door-open w-5 text-center {{ request()->routeIs('rooms.*') ? 'text-white' : 'text-purple-400' }}"></i>
                <span class="font-medium text-sm">Pinjam Ruangan</span>
            </a>

            <div class="mt-6 mb-2">
                <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Aktivitas Saya</p>
            </div>

            <a href="{{ route('reservations.assets') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('reservations.*') ? 'bg-teal-600 text-white shadow-md' : 'text-gray-400 hover:bg-navy-700 hover:text-white' }}">
                <i class="fas fa-history w-5 text-center {{ request()->routeIs('reservations.*') ? 'text-white' : 'text-orange-400' }}"></i>
                <span class="font-medium text-sm">Riwayat & Tiket</span>
            </a>
        @endunlessrole


        {{-- MENU ADMIN --}}
        @role('Superadmin|Laboran')
            <div class="mt-6 mb-2">
                <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Sirkulasi & Admin</p>
            </div>

            {{-- 🔥 PERBAIKAN: LINK INI SEKARANG MENGARAH KE ADMIN 🔥 --}}
            <a href="{{ route('admin.reservations.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reservations.index') ? 'bg-teal-600 text-white shadow-md' : 'text-gray-400 hover:bg-navy-700 hover:text-white' }}">
                <i class="fas fa-exchange-alt w-5 text-center"></i>
                <span class="font-medium text-sm">Data Transaksi</span>
            </a>

            <a href="{{ route('admin.scan.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.scan.*') ? 'bg-teal-600 text-white shadow-md' : 'text-gray-400 hover:bg-navy-700 hover:text-white' }}">
                <i class="fas fa-qrcode w-5 text-center"></i>
                <span class="font-medium text-sm">Scan QR Code</span>
            </a>

            <div class="mt-6 mb-2">
                <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Master Data</p>
            </div>

            <a href="{{ route('admin.assets.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.assets.*') ? 'bg-navy-700 text-white' : 'text-gray-400 hover:bg-navy-700 hover:text-white' }}">
                <i class="fas fa-box w-5 text-center"></i> <span class="text-sm">Data Aset</span>
            </a>
            
            <a href="{{ route('admin.labs.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.labs.*') ? 'bg-navy-700 text-white' : 'text-gray-400 hover:bg-navy-700 hover:text-white' }}">
                <i class="fas fa-building w-5 text-center"></i> <span class="text-sm">Data Lab</span>
            </a>

            <a href="{{ route('admin.prodis.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.prodis.*') ? 'bg-navy-700 text-white' : 'text-gray-400 hover:bg-navy-700 hover:text-white' }}">
                <i class="fas fa-university w-5 text-center"></i> <span class="text-sm">Data Prodi</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-navy-700 text-white' : 'text-gray-400 hover:bg-navy-700 hover:text-white' }}">
                <i class="fas fa-tags w-5 text-center"></i> <span class="text-sm">Kategori Aset</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-navy-700 text-white' : 'text-gray-400 hover:bg-navy-700 hover:text-white' }}">
                <i class="fas fa-users-cog w-5 text-center"></i> <span class="text-sm">Data Pengguna</span>
            </a>
        @endrole
    </div>
    
    {{-- Footer User Profile --}}
    <div class="p-4 bg-navy-900 border-t border-navy-700">
        <div class="flex items-center gap-3">
            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-10 h-10 rounded-full object-cover border border-teal-500 shadow-sm">
            @else
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-teal-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-teal-400 truncate uppercase tracking-wider">{{ Auth::user()->getRoleNames()->first() ?? 'Mahasiswa' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-red-400 transition" title="Keluar"><i class="fas fa-power-off"></i></button>
            </form>
        </div>
    </div>
</aside>