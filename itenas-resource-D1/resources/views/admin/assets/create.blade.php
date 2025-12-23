<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Aset Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.assets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Nama Alat</label>
                        <input type="text" name="name" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Serial Number / Kode Barang</label>
                        <input type="text" name="serial_number" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Lokasi Lab</label>
                        <select name="lab_id" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->name }} - {{ $lab->building_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Stok Awal</label>
                        <input type="number" name="stock" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" min="1" value="1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Foto Alat (Opsional)</label>
                        <input type="file" name="image" class="block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Simpan Aset</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>