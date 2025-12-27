<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
       class="w-64 bg-[#E65100] text-white flex flex-col h-screen fixed inset-y-0 left-0 z-30 transition-transform duration-300 ease-in-out md:translate-x-0 shadow-2xl">
    
    {{-- LOGO (Fixed Top) --}}
    <div class="h-20 flex items-center px-6 bg-[#BF360C] border-b border-orange-800 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:opacity-90 transition">
            <div class="bg-white p-1.5 rounded-lg shadow-sm">
                <img src="https://uat-web.itenas.ac.id/assets/images/logo-7.png" class="h-8 w-auto" alt="Logo ITENAS">
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight text-white leading-none">ITENAS</h1>
                <p class="text-[10px] text-orange-200 font-medium uppercase tracking-widest mt-0.5">Resource Center</p>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden ml-auto text-orange-200 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- MENU SCROLL --}}
    <div class="flex-1 overflow-y-auto py-6 space-y-1 custom-scroll">
        
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('dashboard') ? 'bg-orange-800 border-l-4 border-white font-bold' : 'hover:bg-orange-700 text-orange-100 hover:text-white' }}">
            <i class="fas fa-th-large w-6 text-center"></i>
            <span class="text-sm ml-2">Dashboard</span>
        </a>

        {{-- ================= MENU MAHASISWA ================= --}}
        @unlessrole('Superadmin|Laboran')
            <div class="mt-6 mb-2 px-6">
                <p class="text-[11px] font-bold text-orange-200 uppercase tracking-wider">LAYANAN MAHASISWA</p>
            </div>
            
            {{-- 1. Pinjam Asset --}}
            <a href="{{ route('assets.index') }}" class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('assets.*') ? 'bg-orange-800 border-l-4 border-white font-bold' : 'hover:bg-orange-700 text-orange-100 hover:text-white' }}">
                <i class="fas fa-search w-6 text-center"></i>
                <span class="text-sm ml-2">Pinjam Asset</span>
            </a>

            {{-- 2. Riwayat Asset --}}
            <a href="{{ route('reservations.assets') }}" class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('reservations.assets') ? 'bg-orange-800 border-l-4 border-white font-bold' : 'hover:bg-orange-700 text-orange-100 hover:text-white' }}">
                <i class="fas fa-history w-6 text-center"></i>
                <span class="text-sm ml-2">Riwayat Asset</span>
            </a>

            {{-- 3. Pinjam Ruangan --}}
            <a href="{{ route('rooms.index') }}" class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('rooms.*') ? 'bg-orange-800 border-l-4 border-white font-bold' : 'hover:bg-orange-700 text-orange-100 hover:text-white' }}">
                <i class="fas fa-door-open w-6 text-center"></i>
                <span class="text-sm ml-2">Pinjam Ruangan</span>
            </a>
            
            {{-- 4. Riwayat Ruangan --}}
            <a href="{{ route('reservations.rooms') }}" class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('reservations.rooms') ? 'bg-orange-800 border-l-4 border-white font-bold' : 'hover:bg-orange-700 text-orange-100 hover:text-white' }}">
                <i class="fas fa-clock w-6 text-center"></i>
                <span class="text-sm ml-2">Riwayat Ruangan</span>
            </a>
        @endunlessrole

        {{-- ================= MENU ADMIN & LABORAN ================= --}}
        @role('Superadmin|Laboran')
            <div class="mt-6 mb-2 px-6">
                <p class="text-[11px] font-bold text-orange-200 uppercase tracking-wider">SIRKULASI</p>
            </div>
            <a href="{{ route('admin.reservations.index') }}" class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('admin.reservations.*') ? 'bg-orange-800 border-l-4 border-white font-bold' : 'hover:bg-orange-700 text-orange-100 hover:text-white' }}">
                <i class="fas fa-exchange-alt w-6 text-center"></i><span class="text-sm ml-2">Data Transaksi</span>
            </a>
            <a href="{{ route('admin.scan.index') }}" class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('admin.scan.*') ? 'bg-orange-800 border-l-4 border-white font-bold' : 'hover:bg-orange-700 text-orange-100 hover:text-white' }}">
                <i class="fas fa-qrcode w-6 text-center"></i><span class="text-sm ml-2">Scan QR</span>
            </a>

            <div class="mt-4 mb-2 px-6">
                <p class="text-[11px] font-bold text-orange-200 uppercase tracking-wider">MASTER DATA</p>
            </div>
            <a href="{{ route('admin.assets.index') }}" class="flex items-center px-6 py-2 text-sm text-orange-100 hover:text-white hover:bg-orange-700 rounded transition {{ request()->routeIs('admin.assets.*') ? 'text-white font-bold bg-orange-700' : '' }}">
                <i class="fas fa-box w-6 text-center"></i> <span class="ml-2">Kelola Aset</span>
            </a>
            <a href="{{ route('admin.labs.index') }}" class="flex items-center px-6 py-2 text-sm text-orange-100 hover:text-white hover:bg-orange-700 rounded transition {{ request()->routeIs('admin.labs.*') ? 'text-white font-bold bg-orange-700' : '' }}">
                <i class="fas fa-flask w-6 text-center"></i> <span class="ml-2">Kelola Lab</span>
            </a>
             <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-2 text-sm text-orange-100 hover:text-white hover:bg-orange-700 rounded transition {{ request()->routeIs('admin.users.*') ? 'text-white font-bold bg-orange-700' : '' }}">
                <i class="fas fa-users w-6 text-center"></i> <span class="ml-2">Kelola User</span>
            </a>

            {{-- MENU KHUSUS SUPERADMIN (Silo Data Protection) --}}
            @role('Superadmin')
                <a href="{{ route('admin.prodis.index') }}" class="flex items-center px-6 py-2 text-sm text-orange-100 hover:text-white hover:bg-orange-700 rounded transition {{ request()->routeIs('admin.prodis.*') ? 'text-white font-bold bg-orange-700' : '' }}">
                    <i class="fas fa-university w-6 text-center"></i> <span class="ml-2">Kelola Prodi</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-2 text-sm text-orange-100 hover:text-white hover:bg-orange-700 rounded transition {{ request()->routeIs('admin.categories.*') ? 'text-white font-bold bg-orange-700' : '' }}">
                    <i class="fas fa-tags w-6 text-center"></i> <span class="ml-2">Kategori</span>
                </a>
            @endrole

            <a href="{{ route('admin.maintenances.index') }}" class="flex items-center px-6 py-2 text-sm text-orange-100 hover:text-white hover:bg-orange-700 rounded transition {{ request()->routeIs('admin.maintenances.*') ? 'text-white font-bold bg-orange-700' : '' }}">
                <i class="fas fa-tools w-6 text-center"></i> <span class="ml-2">Maintenance</span>
            </a>
        @endrole
    </div>

    {{-- LOGOUT (Fixed Bottom) --}}
    <div class="p-4 bg-[#BF360C] border-t border-orange-800 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#D32F2F] hover:bg-red-700 text-white py-2.5 rounded shadow transition font-bold text-sm group">
                <i class="fas fa-sign-out-alt group-hover:animate-pulse"></i> LOG OUT
            </button>
        </form>
    </div>
</aside>