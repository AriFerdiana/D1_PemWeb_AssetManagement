<x-app-layout>
    @section('header', 'Katalog Peminjaman Aset')

    <div class="space-y-6">
        
        {{-- Filter & Search --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 justify-between items-center">
            
            {{-- Form dengan method GET --}}
            <form method="GET" action="{{ route('assets.index') }}" class="flex-1 flex flex-col md:flex-row gap-2 w-full">
                
                {{-- Dropdown Jumlah Baris (10, 20, dst) --}}
                <select name="per_page" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 w-full md:w-28 text-center font-bold bg-gray-50 cursor-pointer" title="Jumlah Data">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Data</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 Data</option>
                    <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30 Data</option>
                    <option value="40" {{ request('per_page') == 40 ? 'selected' : '' }}>40 Data</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Data</option>
                </select>

                {{-- Input Pencarian --}}
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari alat, sensor, atau modul..." class="w-full flex-1 rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500">
                
                {{-- Dropdown Kategori --}}
                <select name="category_id" class="rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 w-full md:w-auto">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                
                {{-- Tombol Cari --}}
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg font-bold hover:bg-gray-900 transition shadow-md">
                    Cari
                </button>

                {{-- Tombol Reset (Hanya muncul jika sedang filter) --}}
                @if(request('search') || request('category_id') || request('per_page'))
                    <a href="{{ route('assets.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-300 transition flex items-center justify-center shadow-sm" title="Reset Filter">
                        <i class="fas fa-undo"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Grid Katalog Aset --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($assets as $asset)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group flex flex-col">
                    
                    {{-- Gambar Aset --}}
                    <div class="h-48 bg-gray-100 relative overflow-hidden flex items-center justify-center">
                        @if(filter_var($asset->image, FILTER_VALIDATE_URL))
                            {{-- Jika Link URL --}}
                            <img src="{{ $asset->image }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @elseif($asset->image)
                            {{-- Jika File Upload --}}
                            <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <i class="fas fa-image text-4xl"></i>
                            </div>
                        @endif
                        
                        {{-- Badge Stok --}}
                        <div class="absolute top-2 right-2 bg-white/90 backdrop-blur px-2 py-1 rounded-lg text-xs font-bold shadow text-gray-800">
                            Stok: {{ $asset->quantity }}
                        </div>
                    </div>

                    {{-- Info Aset --}}
                    <div class="p-4 flex flex-col flex-grow">
                        <p class="text-xs text-orange-600 font-bold uppercase mb-1">{{ $asset->category->name ?? 'Umum' }}</p>
                        <h3 class="font-bold text-gray-800 text-lg leading-tight mb-1" title="{{ $asset->name }}">{{ $asset->name }}</h3>
                        <p class="text-sm text-gray-500 mb-4"><i class="fas fa-map-marker-alt mr-1"></i> {{ $asset->lab->name ?? '-' }}</p>
                        
                        {{-- Tombol Pinjam (POSISI DI BAWAH) --}}
                        <div class="mt-auto pt-4 border-t border-gray-100">
                            @if($asset->quantity > 0)
                                {{-- [PERBAIKAN WARNA DI SINI: Ganti bg-navy-700 jadi bg-blue-600] --}}
                                <a href="{{ route('reservations.create', ['asset' => $asset->id]) }}" class="block w-full bg-blue-600 text-white text-center py-2.5 rounded-lg font-bold hover:bg-blue-700 transition shadow-sm">
                                    Ajukan Peminjaman
                                </a>
                            @else
                                <button disabled class="block w-full bg-gray-200 text-gray-400 text-center py-2.5 rounded-lg font-bold cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-xl border-2 border-dashed border-gray-300">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Aset tidak ditemukan</h3>
                    <p class="text-gray-500 text-sm mt-1">Coba kata kunci lain atau ubah filter kategori.</p>
                    <a href="{{ route('assets.index') }}" class="mt-4 inline-block text-orange-600 font-bold hover:underline">Reset Filter</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $assets->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>