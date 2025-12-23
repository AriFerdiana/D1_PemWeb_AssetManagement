<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Kategori Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Nama Kategori</label>
                        <input type="text" name="name" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white placeholder-gray-400" placeholder="Contoh: Elektronik, Furniture, Kendaraan" required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="3" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white placeholder-gray-400" placeholder="Penjelasan singkat tentang kategori ini..."></textarea>
                    </div>

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 mr-2">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Simpan Kategori</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>