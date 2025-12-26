<x-app-layout>
    @section('header', 'Kelola Laboratorium')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-6">
        
        {{-- === BAGIAN SEARCH DAN FILTER === --}}
        <div class="w-full md:w-3/4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.labs.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                
                {{-- 1. Input Search --}}
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari Nama Lab atau Gedung..." 
                           class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500">
                </div>

                {{-- 2. Dropdown Filter Prodi --}}
                <select name="prodi_id" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500 bg-white cursor-pointer w-full md:w-auto">
                    <option value="">-- Semua Prodi --</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->name }}
                        </option>
                    @endforeach
                </select>

                {{-- 3. Tombol Cari --}}
                <button type="submit" class="px-5 py-2 bg-navy-700 text-white rounded-lg text-sm font-bold hover:bg-navy-800 transition shadow-sm">
                    Cari
                </button>

                {{-- 4. Tombol Reset --}}
                @if(request()->hasAny(['search', 'prodi_id']))
                    <a href="{{ route('admin.labs.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-200 transition border border-gray-200 flex items-center justify-center" title="Reset Filter">
                        <i class="fas fa-undo"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Tombol Tambah Lab --}}
        <a href="{{ route('admin.labs.create') }}" class="w-full md:w-auto px-5 py-3 bg-navy-700 hover:bg-navy-800 text-white rounded-xl shadow-lg flex items-center justify-center gap-2 font-bold transition transform hover:-translate-y-1">
            <i class="fas fa-plus"></i> Tambah Lab Baru
        </a>
    </div>

    {{-- === TABEL DATA === --}}
    <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Nama Lab</th>
                        <th class="px-6 py-4">Prodi & Lokasi</th>
                        <th class="px-6 py-4">Kapasitas</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($labs as $lab)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Nama Lab --}}
                            <td class="px-6 py-4">
                                <div class="font-bold text-navy-700 text-base">{{ $lab->name }}</div>
                                <div class="text-xs text-gray-400 mt-1">Fasilitas praktikum {{ $lab->prodi->name ?? 'Umum' }}</div>
                            </td>

                            {{-- Prodi & Lokasi --}}
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-700">{{ $lab->prodi->name ?? '-' }}</div>
                                <div class="text-xs text-green-600 mt-1 flex items-center">
                                    <i class="fas fa-building mr-1"></i> {{ $lab->location }}
                                </div>
                            </td>

                            {{-- Kapasitas --}}
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold border border-blue-100">
                                    {{ $lab->capacity }} Orang
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('admin.labs.show', $lab->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 border border-blue-100 transition" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.labs.edit', $lab->id) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 border border-yellow-100 transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.labs.destroy', $lab->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Hapus Lab ini?')" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 border border-red-100 transition" title="Hapus">
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
                                    <i class="fas fa-flask text-3xl mb-2 text-gray-300"></i>
                                    <p>Tidak ada data laboratorium ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $labs->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>