<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Lab Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.labs.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-bold">Nama Laboratorium</label>
                        <input type="text" name="name" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-bold">Kode Ruangan (Unik)</label>
                        <input type="text" name="code" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Contoh: 101, LAB-IF" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-bold">Gedung / Lokasi</label>
                        <input type="text" name="building_name" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Contoh: Gedung F" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-bold">Deskripsi Fasilitas (Opsional)</label>
                        <textarea name="description" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" rows="3"></textarea>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">📍 Titik Lokasi Peta (Opsional)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">Latitude</label>
                                <input type="text" name="latitude" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Contoh: -6.897123">
                            </div>
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">Longitude</label>
                                <input type="text" name="longitude" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Contoh: 107.636456">
                            </div>
                        </div>
                        
                        <div class="mb-6 text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 p-3 rounded border border-gray-200 dark:border-gray-700">
                            💡 <strong>Tips:</strong> Buka <a href="https://maps.google.com" target="_blank" class="text-blue-500 underline hover:text-blue-700">Google Maps</a>, klik kanan pada lokasi gedung, lalu klik angka koordinat (misal: -6.897..., 107.636...) untuk menyalinnya. Masukkan angka pertama ke <strong>Latitude</strong> dan angka kedua ke <strong>Longitude</strong>.
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admin.labs.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 mr-2">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-bold shadow-lg transition">Simpan Lab</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>