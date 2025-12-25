<x-app-layout>
    @section('header', 'Tambah Aset Baru')

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        
        {{-- Header Form --}}
        <div class="mb-6 border-b pb-4">
            <h2 class="text-xl font-bold text-navy-800">Form Input Data Aset</h2>
            <p class="text-gray-500 text-sm">Pastikan data yang diinput valid dan kode barang unik.</p>
        </div>

        {{-- Form Start --}}
        {{-- PENTING: enctype wajib ada untuk upload file --}}
        <form action="{{ route('admin.assets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                {{-- Nama Aset --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Aset / Barang</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500 @error('name') border-red-500 @enderror" 
                           placeholder="Contoh: Laptop ASUS ROG Zephyrus">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kode Barang --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Aset (Unik)</label>
                    <input type="text" name="code" value="{{ old('code') }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500 @error('code') border-red-500 @enderror" 
                           placeholder="Contoh: IF-LAB-001">
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Program Studi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                    {{-- Kalau user Laboran, bisa di-set otomatis readonly --}}
                    <input type="text" name="prodi" value="{{ old('prodi', Auth::user()->prodi ?? '') }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500 @error('prodi') border-red-500 @enderror" 
                           placeholder="Contoh: Informatika">
                    @error('prodi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">Kategori wajib dipilih.</p>
                    @enderror
                </div>

                {{-- Lokasi / Lab --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Penyimpanan (Lab)</label>
                    <select name="lab_id" class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500">
                        <option value="">-- Pilih Lab --</option>
                        {{-- Pastikan variable $labs dikirim dari controller --}}
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('lab_id')
                        <p class="text-red-500 text-xs mt-1">Lokasi wajib dipilih.</p>
                    @enderror
                </div>

                {{-- Stok --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', 1) }}" min="1"
                           class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500">
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Gambar --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Aset (Opsional)</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span></p>
                                <p class="text-xs text-gray-500">JPG, PNG (MAX. 2MB)</p>
                            </div>
                            <input type="file" name="image" class="hidden" />
                        </label>
                    </div>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('admin.assets.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 transition font-medium shadow-lg">
                    <i class="fas fa-save mr-2"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>