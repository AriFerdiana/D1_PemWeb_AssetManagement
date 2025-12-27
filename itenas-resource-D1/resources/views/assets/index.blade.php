<x-app-layout>
    
    {{-- ========================================== --}}
    {{-- BAGIAN 1: TAMPILAN KHUSUS ADMIN / LABORAN --}}
    {{-- ========================================== --}}
    @hasrole('Superadmin|Laboran')
        @section('header', 'Kelola Aset (Administrator)')

        <div class="space-y-6">
            {{-- Statistik Admin --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row justify-between gap-4 items-center">
                    <div class="flex gap-4">
                        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg font-bold text-sm">
                            <i class="fas fa-box mr-2"></i> Total: {{ $assets->total() }}
                        </div>
                        <div class="bg-red-50 text-red-700 px-4 py-2 rounded-lg font-bold text-sm">
                            <i class="fas fa-wrench mr-2"></i> Rusak: 0
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold shadow">
                            <i class="fas fa-file-import mr-1"></i> Import
                        </button>
                        <a href="{{ route('admin.assets.create') }}" class="px-4 py-2 bg-[#1F2937] hover:bg-black text-white rounded-lg text-sm font-bold shadow">
                            <i class="fas fa-plus mr-1"></i> Tambah Aset Baru
                        </a>
                    </div>
                </div>
            </div>

            {{-- Tabel Data Aset (Versi Admin) --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Nama Aset</th>
                                <th class="px-6 py-3">Kode</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3">Stok</th>
                                <th class="px-6 py-3">Lokasi</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assets as $asset)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $asset->name }}</td>
                                <td class="px-6 py-4 font-mono text-gray-500">{{ $asset->code }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $asset->category->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold">{{ $asset->stock }}</td>
                                <td class="px-6 py-4">{{ $asset->lab->name ?? 'Umum' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.assets.edit', $asset->id) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $assets->withQueryString()->links() }}
                </div>
            </div>
        </div>

    {{-- ========================================== --}}
    {{-- BAGIAN 2: TAMPILAN KHUSUS MAHASISWA --}}
    {{-- ========================================== --}}
    @else
        @section('header', 'Katalog Peminjaman Alat')

        <div class="space-y-6">
            
            {{-- Filter & Search Katalog --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <form method="GET" action="{{ route('assets.index') }}" class="flex flex-col md:flex-row gap-3">
                    <select name="category_id" class="border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 w-full md:w-auto">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari alat, sensor, atau modul..." 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-white">
                        <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>

                    <button type="submit" class="bg-[#E65100] hover:bg-orange-700 text-white px-6 py-2.5 rounded-lg font-bold shadow transition">
                        Cari
                    </button>
                </form>
            </div>

            {{-- Grid Katalog Aset --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($assets as $asset)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full group">
                        
                        {{-- Gambar --}}
                        <div class="h-48 bg-gray-100 dark:bg-gray-700 relative overflow-hidden flex items-center justify-center">
                            @if($asset->image)
                                <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <i class="fas fa-microchip text-5xl text-gray-300 dark:text-gray-500"></i>
                            @endif

                            <div class="absolute top-2 right-2">
                                @if($asset->status == 'available')
                                    <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-[10px] font-bold border border-green-200 uppercase shadow-sm">Tersedia</span>
                                @elseif($asset->status == 'maintenance')
                                    <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-[10px] font-bold border border-yellow-200 uppercase shadow-sm">Perbaikan</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-[10px] font-bold border border-red-200 uppercase shadow-sm">Dipinjam</span>
                                @endif
                            </div>
                        </div>

                        {{-- Info Aset --}}
                        <div class="p-4 flex-grow flex flex-col">
                            <div class="mb-3">
                                <h4 class="text-lg font-bold text-gray-800 dark:text-white line-clamp-1" title="{{ $asset->name }}">{{ $asset->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-mono bg-gray-100 dark:bg-gray-700 inline-block px-1 rounded">{{ $asset->code }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-300 mb-4">
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded border border-gray-100 dark:border-gray-600 flex items-center">
                                    <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i> 
                                    <span class="truncate">{{ \Illuminate\Support\Str::limit($asset->lab->name ?? 'Gudang Umum', 12) }}</span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded border border-gray-100 dark:border-gray-600 flex items-center">
                                    <i class="fas fa-cubes text-orange-500 mr-2"></i> 
                                    <span>Stok: <b>{{ $asset->stock }}</b></span>
                                </div>
                            </div>

                            {{-- Tombol Pinjam --}}
                            <div class="mt-auto pt-2">
                                @if($asset->status == 'available' && $asset->stock > 0)
                                    <a href="{{ route('reservations.create', $asset->id) }}" 
                                       class="block w-full py-2.5 bg-[#E65100] hover:bg-orange-700 text-white text-center text-sm font-bold rounded-lg transition shadow-md hover:-translate-y-0.5 transform">
                                        <i class="fas fa-shopping-cart mr-1"></i> Pinjam Alat
                                    </a>
                                @else
                                    <button disabled class="block w-full py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 text-center text-sm font-bold rounded-lg cursor-not-allowed border border-gray-200 dark:border-gray-600">
                                        <i class="fas fa-ban mr-1"></i> Tidak Tersedia
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Aset tidak ditemukan</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Coba kata kunci lain atau ubah filter kategori.</p>
                        <a href="{{ route('assets.index') }}" class="mt-4 inline-block text-orange-600 font-bold hover:underline">Reset Filter</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $assets->withQueryString()->links() }}
            </div>
        </div>
    @endhasrole

</x-app-layout>