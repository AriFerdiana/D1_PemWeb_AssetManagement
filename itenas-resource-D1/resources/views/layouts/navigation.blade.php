<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ml-6 sm:flex items-center">
                    
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <div class="h-6 border-l border-gray-300 dark:border-gray-600 mx-2"></div>

                    <div class="flex space-x-4">
                        <x-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.index')">
                            {{ __('Katalog Aset') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reservations.assets')" :active="request()->routeIs('reservations.assets')">
                            {{ __('Riwayat Aset') }}
                        </x-nav-link>
                    </div>

                    <div class="h-6 border-l border-gray-300 dark:border-gray-600 mx-2"></div>

                    <div class="flex space-x-4">
                        <x-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')">
                            {{ __('Pinjam Ruangan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('reservations.rooms')" :active="request()->routeIs('reservations.rooms')">
                            {{ __('Riwayat Ruangan') }}
                        </x-nav-link>
                    </div>

                    @role('Superadmin|Admin|Laboran')
                        <div class="h-6 border-l border-gray-300 dark:border-gray-600 mx-2"></div>
                        
                        <x-nav-link :href="route('admin.scan.index')" :active="request()->routeIs('admin.scan.index')" class="text-blue-600 dark:text-blue-400 font-bold">
                            <span class="mr-1">🔍</span> {{ __('Scanner QR') }}
                        </x-nav-link>

                        <div class="relative sm:flex sm:items-center">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>⚙️ Admin Panel</div>
                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="px-4 py-2 text-xs text-gray-400">Master Data</div>
                                    <x-dropdown-link :href="route('admin.assets.index')">Kelola Aset</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.labs.index')">Kelola Lab</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.users.index')">Kelola User</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.prodis.index')">Kelola Prodi</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.categories.index')">Kelola Kategori</x-dropdown-link>
                                    <div class="border-t border-gray-100 dark:border-gray-600"></div>
                                    <x-dropdown-link :href="route('admin.maintenances.index')">Log Perawatan</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <button onclick="toggleDarkMode()" class="mr-4 p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 focus:outline-none transition">
                    <svg class="w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg class="w-6 h-6 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- RESPONSIVE MENU (MOBILE) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
            <div class="px-4 text-xs text-gray-400 uppercase">Peminjaman Aset</div>

            <x-responsive-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.index')">
                {{ __('Katalog Aset') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reservations.assets')" :active="request()->routeIs('reservations.assets')">
                {{ __('Riwayat Aset') }}
            </x-responsive-nav-link>

            <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
            <div class="px-4 text-xs text-gray-400 uppercase">Peminjaman Ruangan</div>

            <x-responsive-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')">
                {{ __('Pinjam Ruangan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reservations.rooms')" :active="request()->routeIs('reservations.rooms')">
                {{ __('Riwayat Ruangan') }}
            </x-responsive-nav-link>

            @role('Superadmin|Laboran')
                <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                <div class="px-4 text-xs text-gray-400 uppercase font-bold text-blue-600">Admin Panel</div>

                <x-responsive-nav-link :href="route('admin.scan.index')" :active="request()->routeIs('admin.scan.index')" class="bg-blue-50 dark:bg-blue-900/20">
                    <span class="mr-1">🔍</span> {{ __('Scanner QR Code') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.assets.index')" :active="request()->routeIs('admin.assets.*')">
                    {{ __('Kelola Aset') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Kelola User') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.labs.index')" :active="request()->routeIs('admin.labs.*')">
                    {{ __('Kelola Lab') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.prodis.index')" :active="request()->routeIs('admin.prodis.*')">
                    {{ __('Kelola Prodi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                    {{ __('Kelola Kategori') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.maintenances.index')" :active="request()->routeIs('admin.maintenances.*')">
                    {{ __('Log Perawatan') }}
                </x-responsive-nav-link>
            @endrole
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>