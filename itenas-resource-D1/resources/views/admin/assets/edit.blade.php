<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Data Aset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf 
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Nama Alat</label>
                        <input type="text" name="name" value="{{ $asset->name }}" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Serial Number</label>
                        <input type="text" name="serial_number" value="{{ $asset->serial_number }}" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Lokasi Lab</label>
                        <select name="lab_id" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" {{ $asset->lab_id == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->name }} - {{ $lab->building_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-bold">Kondisi/Status Aset</label>
                        <select name="status" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-blue-500">
                            <option value="available" {{ $asset->status == 'available' ? 'selected' : '' }}>
                                ✅ Tersedia (Available)
                            </option>
                            <option value="maintenance" {{ $asset->status == 'maintenance' ? 'selected' : '' }}>
                                🛠️ Sedang Diperbaiki (Maintenance)
                            </option>
                            <option value="broken" {{ $asset->status == 'broken' ? 'selected' : '' }}>
                                ❌ Rusak (Broken)
                            </option>
                            {{-- Status Borrowed dikunci agar tidak bisa diubah manual secara sembarangan --}}
                            <option value="borrowed" {{ $asset->status == 'borrowed' ? 'selected' : '' }} @if($asset->status != 'borrowed') disabled @endif>
                                📦 Sedang Dipinjam (Borrowed)
                            </option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1 italic">*Jika status 'Maintenance' atau 'Broken', aset akan otomatis hilang dari katalog mahasiswa.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Stok Saat Ini</label>
                        <input type="number" name="stock" value="{{ $asset->stock }}" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" min="0" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Foto Saat Ini</label>
                        @if($asset->image_path)
                            <img src="{{ asset('storage/' . $asset->image_path) }}" alt="Asset Image" class="w-32 h-32 object-cover rounded-md mb-2 shadow">
                        @else
                            <p class="text-gray-500 text-sm">Tidak ada foto.</p>
                        @endif
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 mt-4 text-sm font-semibold text-blue-600">Ganti Foto Baru</label>
                        <input type="file" name="image" class="block w-full text-sm text-gray-500">
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('admin.assets.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 font-bold shadow-lg transition">Update Data & Status</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>