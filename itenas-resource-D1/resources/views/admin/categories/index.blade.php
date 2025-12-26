<x-app-layout>
    @section('header', 'Kelola Kategori Aset')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-6">
        
        {{-- === BAGIAN SEARCH DAN FILTER === --}}
        <div class="w-full md:w-2/3 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                
                {{-- 1. Input Search --}}
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari Nama Kategori..." 
                           class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500">
                </div>

                {{-- 2. Dropdown Filter / Sorting --}}
                <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500 bg-white cursor-pointer w-full md:w-auto">
                    <option value="">-- Urutkan --</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                    <option value="most_assets" {{ request('sort') == 'most_assets' ? 'selected' : '' }}>Aset Terbanyak</option>
                    <option value="least_assets" {{ request('sort') == 'least_assets' ? 'selected' : '' }}>Aset Paling Sedikit</option>
                </select>

                {{-- 3. Tombol Filter --}}
                <button type="submit" class="px-5 py-2 bg-navy-700 text-white rounded-lg text-sm font-bold hover:bg-navy-800 transition shadow-sm">
                    Cari
                </button>

                {{-- 4. Tombol Reset (Muncul jika sedang filter) --}}
                @if(request()->hasAny(['search', 'sort']))
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-200 transition border border-gray-200 flex items-center justify-center" title="Reset Filter">
                        <i class="fas fa-undo"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Tombol Tambah Kategori (Sesuai Desain Lama) --}}
        <a href="{{ route('admin.categories.create') }}" class="w-full md:w-auto px-5 py-3 bg-navy-700 hover:bg-navy-800 text-white rounded-xl shadow-lg flex items-center justify-center gap-2 font-bold transition transform hover:-translate-y-1">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>

    {{-- === TABEL DATA === --}}
    <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 w-10">No</th>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4 text-center">Jumlah Aset</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $index => $category)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Nomor --}}
                            <td class="px-6 py-4 text-gray-500">
                                {{ $categories->firstItem() + $index }}
                            </td>

                            {{-- Nama Kategori --}}
                            <td class="px-6 py-4 font-bold text-navy-700">
                                {{ $category->name }}
                            </td>
                            
                            {{-- Jumlah Aset --}}
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                    {{ $category->assets_count }} Item
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 border border-yellow-100 transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Hapus kategori ini?')" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 border border-red-100 transition" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-folder-open text-3xl mb-2 text-gray-300"></i>
                                    <p>Tidak ada kategori ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $categories->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>