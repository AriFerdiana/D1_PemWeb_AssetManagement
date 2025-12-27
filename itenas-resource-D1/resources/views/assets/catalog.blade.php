<x-app-layout>
    @section('header', 'Katalog Peminjaman Aset')

    <div class="space-y-6">
        {{-- Filter & Search --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 justify-between">
            <form method="GET" class="flex-1 flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari alat..." class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500">
                <select name="category_id" class="rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg font-bold">Cari</button>
            </form>
        </div>

        {{-- Grid Katalog Aset --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($assets as $asset)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                    {{-- Gambar Aset --}}
                    <div class="h-48 bg-gray-100 relative">
                        @if($asset->image)
                            <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <i class="fas fa-image text-4xl"></i>
                            </div>
                        @endif
                        
                        {{-- Badge Stok --}}
                        <div class="absolute top-2 right-2 bg-white/90 backdrop-blur px-2 py-1 rounded-lg text-xs font-bold shadow">
                            Stok: {{ $asset->stock }}
                        </div>
                    </div>

                    {{-- Info Aset --}}
                    <div class="p-4">
                        <p class="text-xs text-orange-600 font-bold uppercase mb-1">{{ $asset->category->name ?? 'Umum' }}</p>
                        <h3 class="font-bold text-gray-800 text-lg truncate" title="{{ $asset->name }}">{{ $asset->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1"><i class="fas fa-map-marker-alt mr-1"></i> {{ $asset->lab->name ?? '-' }}</p>
                        
                        {{-- Tombol Pinjam --}}
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            @if($asset->stock > 0)
                                <a href="{{ route('reservations.create', ['asset' => $asset->id]) }}" class="block w-full bg-navy-700 text-white text-center py-2 rounded-lg font-bold hover:bg-navy-800 transition">
                                    Ajukan Peminjaman
                                </a>
                            @else
                                <button disabled class="block w-full bg-gray-200 text-gray-400 text-center py-2 rounded-lg font-bold cursor-not-allowed">
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                    <p>Aset tidak ditemukan.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $assets->links() }}
        </div>
    </div>
</x-app-layout>