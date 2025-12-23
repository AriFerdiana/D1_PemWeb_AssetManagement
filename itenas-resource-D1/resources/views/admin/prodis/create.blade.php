<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Prodi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.prodis.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Nama Program Studi</label>
                        <input type="text" name="name" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Kode Prodi (Unik)</label>
                        <input type="text" name="code" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Contoh: IF, SI, DKV" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Nama Kaprodi (Opsional)</label>
                        <input type="text" name="kaprodi_name" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Simpan Prodi</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>