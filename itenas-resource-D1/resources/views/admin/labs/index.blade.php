<x-app-layout>
    @section('header', 'Kelola Laboratorium')

    <div x-data="{ showImportModal: false, fileName: '' }">
        
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm">
                <span class="block sm:inline font-bold"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm">
                <span class="block sm:inline font-bold"><i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-6">
            <div class="w-full md:w-3/4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <form action="{{ route('admin.labs.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari Nama Lab atau Gedung..." 
                               class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500">
                    </div>

                    <select name="prodi_id" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500 bg-white cursor-pointer w-full md:w-auto">
                        <option value="">-- Semua Prodi --</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->name }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="px-5 py-2 bg-navy-700 text-white rounded-lg text-sm font-bold hover:bg-navy-800 transition">Cari</button>
                    @if(request()->hasAny(['search', 'prodi_id']))
                        <a href="{{ route('admin.labs.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-bold border border-gray-200 flex items-center justify-center"><i class="fas fa-undo"></i></a>
                    @endif
                </form>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <button @click="showImportModal = true; fileName = ''" class="flex-1 md:flex-none px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg flex items-center justify-center gap-2 font-bold transition transform hover:-translate-y-1">
                    <i class="fas fa-file-import"></i> Import Excel
                </button>
                <a href="{{ route('admin.labs.create') }}" class="flex-1 md:flex-none px-5 py-3 bg-navy-700 hover:bg-navy-800 text-white rounded-xl shadow-lg flex items-center justify-center gap-2 font-bold transition transform hover:-translate-y-1">
                    <i class="fas fa-plus"></i> Tambah Lab Baru
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-xs tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Nama Lab</th>
                            <th class="px-6 py-4">Prodi & Lokasi</th>
                            <th class="px-6 py-4 text-center">Kapasitas</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($labs as $lab)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-navy-700 text-base">{{ $lab->name }}</div>
                                    <div class="text-xs text-gray-400 mt-1">Fasilitas praktikum {{ $lab->prodi->name ?? 'Umum' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-700">{{ $lab->prodi->name ?? '-' }}</div>
                                    <div class="text-xs text-green-600 mt-1 flex items-center">
                                        <i class="fas fa-building mr-1"></i> {{ $lab->building_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold border border-blue-100">{{ $lab->capacity }} Orang</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('admin.labs.show', $lab->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.labs.edit', $lab->id) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.labs.destroy', $lab->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Hapus Lab?')" class="p-2 bg-red-50 text-red-600 rounded-lg"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $labs->withQueryString()->links() }}
            </div>
        </div>

        <div x-show="showImportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-transition>
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" @click.away="showImportModal = false">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-navy-800">Import Data Lab</h3>
                    <button @click="showImportModal = false" class="text-gray-400"><i class="fas fa-times"></i></button>
                </div>
                <form action="{{ route('admin.labs.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-4">
                            <p class="text-xs font-bold text-blue-700 uppercase mb-2">Format Header Wajib:</p>
                            <code class="text-[10px] text-blue-600 bg-white p-1 rounded border block overflow-x-auto">nama_lab, prodi, lokasi, kapasitas, deskripsi</code>
                        </div>
                        <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-blue-400 bg-gray-50 transition cursor-pointer">
                            <input type="file" name="file" required @change="fileName = $event.target.files[0].name" class="absolute inset-0 opacity-0 cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-blue-500 mb-3"></i>
                            <p class="text-sm font-bold" :class="fileName ? 'text-blue-600' : 'text-gray-500'" x-text="fileName ? fileName : 'Klik atau tarik file ke sini'"></p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="showImportModal = false" class="flex-1 px-4 py-2.5 border rounded-lg font-bold">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg font-bold">Unggah Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>