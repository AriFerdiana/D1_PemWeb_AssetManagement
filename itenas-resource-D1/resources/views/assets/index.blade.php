<x-app-layout>
    @section('header', 'Katalog Aset')

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        
        <div>
            <h3 class="text-2xl font-bold text-navy-800 dark:text-white">Daftar Alat & Fasilitas</h3>
            <p class="text-gray-600 dark:text-gray-300 text-sm">Cari dan pinjam aset untuk keperluan praktikum/kegiatan.</p>
        </div>

        <form method="GET" action="{{ route('assets.index') }}" class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
            
            <div class="relative group">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama alat / kode..." 
                    class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent w-full md:w-64 bg-white shadow-sm transition-all group-hover:border-teal-400"
                >
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400 group-hover:text-teal-500 transition-colors"></i>
            </div>

            <select name="category_id" class="border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white shadow-sm text-gray-700 cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-navy-700 hover:bg-navy-800 text-white px-5 py-2.5 rounded-lg flex items-center justify-center shadow-md transition-all transform active:scale-95">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>

            @if(request()->has('search') || request()->has('category_id'))
                <a href="{{ route('assets.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2.5 rounded-lg flex items-center justify-center transition" title="Reset Filter">
                    <i class="fas fa-undo"></i>
                </a>
            @endif

            @role('Superadmin|Laboran')
            <a href="{{ route('admin.assets.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center shadow-md ml-0 md:ml-2 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i> Tambah
            </a>
            @endrole
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
        @forelse($assets as $asset)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-card overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-300 flex flex-col h-full group">
                
                <div class="h-1.5 w-full bg-gradient-to-r 
                    {{ $asset->category_id % 2 == 0 ? 'from-teal-500 to-blue-500' : 'from-purple-500 to-pink-500' }}">
                </div>

                <div class="p-5 flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-xl group-hover:scale-110 transition-transform duration-300">
                            @if($asset->image)
                                <img src="{{ asset('storage/' . $asset->image) }}" class="w-10 h-10 object-cover rounded-md">
                            @else
                                <i class="fas fa-cube text-navy-600 dark:text-teal-400 text-2xl"></i>
                            @endif
                        </div>

                        @if($asset->status == 'available')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                Tersedia
                            </span>
                        @elseif($asset->status == 'maintenance')
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                Perbaikan
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                Dipinjam
                            </span>
                        @endif
                    </div>

                    <h4 class="text-lg font-bold text-navy-900 dark:text-white mb-1 line-clamp-1" title="{{ $asset->name }}">
                        {{ $asset->name }}
                    </h4>
                    <p class="text-gray-500 text-xs mb-4 line-clamp-2 min-h-[2.5em]">
                        {{ $asset->description ?? 'Tidak ada deskripsi aset.' }}
                    </p>

                    <div class="grid grid-cols-2 gap-2 text-xs bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div>
                            <p class="text-gray-400">Kode Aset</p>
                            <p class="font-semibold text-navy-700 dark:text-gray-200 truncate">{{ $asset->code }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Lokasi</p>
                            <p class="font-semibold text-navy-700 dark:text-gray-200 truncate" title="{{ $asset->lab->name ?? 'Umum' }}">
                                {{ \Illuminate\Support\Str::limit($asset->lab->name ?? 'Umum', 12) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-5 pb-5 pt-0 mt-auto">
                    @if($asset->status == 'available')
                        <a href="{{ route('reservations.create', $asset->id) }}" 
                           class="w-full py-2.5 bg-white border border-teal-500 text-teal-600 text-sm font-bold rounded-lg hover:bg-teal-600 hover:text-white transition-colors duration-300 flex items-center justify-center gap-2 group-hover:shadow-md">
                            <i class="fas fa-shopping-cart"></i> Pinjam Sekarang
                        </a>
                    @else
                        <button disabled class="w-full py-2.5 bg-gray-100 text-gray-400 text-sm font-bold rounded-lg cursor-not-allowed flex items-center justify-center gap-2 border border-gray-200">
                            <i class="fas fa-lock"></i> Tidak Tersedia
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-xl border border-dashed border-gray-300">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Aset tidak ditemukan</h3>
                <p class="text-gray-500 text-sm mt-1">Coba ubah kata kunci pencarian atau filter kategori.</p>
                <a href="{{ route('assets.index') }}" class="mt-4 inline-block text-teal-600 font-semibold hover:underline">
                    Reset Pencarian
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $assets->withQueryString()->links() }}
    </div>
</x-app-layout>