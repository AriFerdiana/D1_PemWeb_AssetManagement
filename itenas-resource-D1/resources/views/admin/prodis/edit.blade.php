<x-app-layout>
    @section('header', 'Edit Program Studi')

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        
        <form action="{{ route('admin.prodis.update', $prodi->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            {{-- Nama Fakultas --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Fakultas</label>
                <select name="faculty" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">
                    <option value="FTI" {{ $prodi->faculty == 'FTI' ? 'selected' : '' }}>Fakultas Teknologi Industri (FTI)</option>
                    <option value="FTSP" {{ $prodi->faculty == 'FTSP' ? 'selected' : '' }}>Fakultas Teknik Sipil & Perencanaan (FTSP)</option>
                    <option value="FAD" {{ $prodi->faculty == 'FAD' ? 'selected' : '' }}>Fakultas Arsitektur & Desain (FAD)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                {{-- Kode Prodi --}}
                <div class="col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kode (Singkatan)</label>
                    <input type="text" name="code" value="{{ old('code', $prodi->code) }}" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" required>
                </div>
                
                {{-- Nama Prodi --}}
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Program Studi</label>
                    <input type="text" name="name" value="{{ old('name', $prodi->name) }}" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" required>
                </div>
            </div>

            {{-- Lokasi Kantor --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi Kantor / Tata Usaha</label>
                <input type="text" name="location_office" value="{{ old('location_office', $prodi->location_office) }}" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.prodis.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg font-bold hover:bg-teal-700 shadow-md transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</x-app-layout>