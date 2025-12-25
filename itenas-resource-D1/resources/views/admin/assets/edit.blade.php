<x-app-layout>
    @section('header', 'Edit Data Aset')

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        
        <div class="mb-6 border-b pb-4 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-navy-800">Edit Aset: {{ $asset->name }}</h2>
                <p class="text-gray-500 text-sm">Update informasi aset terkini.</p>
            </div>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded text-sm font-mono">
                {{ $asset->code }}
            </span>
        </div>

        {{-- Form Start --}}
        {{-- Action ke route Update dengan ID --}}
        <form action="{{ route('admin.assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- WAJIB: Untuk method Update --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                {{-- Nama Aset --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Aset</label>
                    <input type="text" name="name" value="{{ old('name', $asset->name) }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500 @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kode Barang (Biasanya boleh diedit tapi harus unik) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Aset</label>
                    <input type="text" name="code" value="{{ old('code', $asset->code) }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500 @error('code') border-red-500 @enderror">
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Prodi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                    <input type="text" name="prodi" value="{{ old('prodi', $asset->prodi) }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500 @error('prodi') border-red-500 @enderror">
                    @error('prodi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $asset->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Lokasi / Lab --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Penyimpanan</label>
                    <select name="lab_id" class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500">
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" {{ old('lab_id', $asset->lab_id) == $lab->id ? 'selected' : '' }}>
                                {{ $lab->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Stok --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $asset->stock) }}" min="0"
                           class="w-full rounded-lg border-gray-300 focus:border-navy-500 focus:ring-navy-500">
                </div>

                {{-- Status (Opsional, jika ingin bisa ubah status manual) --}}
                {{-- 
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Kondisi</label>
                    <select name="status" class="w-full rounded-lg border-gray-300">
                        <option value="available" {{ $asset->status == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="maintenance" {{ $asset->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="broken" {{ $asset->status == 'broken' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
                --}}

                {{-- Upload Gambar (Dengan Preview) --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Foto</label>
                    
                    <div class="flex items-start gap-4">
                        {{-- Preview Gambar Lama --}}
                        @if($asset->image)
                            <div class="w-24 h-24 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0">
                                <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover" title="Foto Saat Ini">
                            </div>
                        @endif

                        <div class="flex-1">
                            <input type="file" name="image" class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-navy-50 file:text-navy-700
                                hover:file:bg-navy-100 cursor-pointer
                            "/>
                            <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah foto.</p>
                            @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('admin.assets.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium shadow-lg">
                    <i class="fas fa-sync-alt mr-2"></i> Update Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>