<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Form Peminjaman Aset</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('reservations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="dark:text-white">Mulai Pinjam</label>
                            <input type="datetime-local" name="start_time" class="w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div>
                            <label class="dark:text-white">Selesai Pinjam</label>
                            <input type="datetime-local" name="end_time" class="w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="font-bold mb-2 dark:text-white text-lg">Daftar Barang yang Dipinjam</h3>
                        <div id="items-container">
                            <div class="item-row flex gap-4 mb-3 items-end">
                                <div class="flex-1">
                                    <label class="text-sm dark:text-gray-300">Pilih Aset</label>
                                    <select name="items[0][asset_id]" class="w-full rounded-md border-gray-300 shadow-sm" required>
                                        <option value="{{ $asset->id }}">{{ $asset->name }} (Stok: {{ $asset->stock }})</option>
                                        </select>
                                </div>
                                <div class="w-24">
                                    <label class="text-sm dark:text-gray-300">Jumlah</label>
                                    <input type="number" name="items[0][quantity]" value="1" min="1" class="w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md remove-row opacity-50 cursor-not-allowed" disabled>Hapus</button>
                            </div>
                        </div>
                        
                        <button type="button" id="add-item" class="mt-3 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 font-bold text-sm">
                            + Tambah Barang Lain
                        </button>
                    </div>

                    <div class="mt-6">
                        <label class="dark:text-white">Keperluan / Alasan Pinjam</label>
                        <textarea name="purpose" class="w-full rounded-md border-gray-300 shadow-sm" rows="3" required></textarea>
                    </div>

                    <div class="mt-4">
                        <label class="dark:text-white">Upload Proposal (Opsional)</label>
                        <input type="file" name="proposal_file" class="w-full border p-2 rounded-md">
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">Kirim Pengajuan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let rowCount = 1;
        const container = document.getElementById('items-container');
        const addButton = document.getElementById('add-item');

        addButton.addEventListener('click', () => {
            const newRow = `
                <div class="item-row flex gap-4 mb-3 items-end">
                    <div class="flex-1">
                        <select name="items[${rowCount}][asset_id]" class="w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="">-- Pilih Barang Lain --</option>
                            @foreach(\App\Models\Asset::all() as $a)
                                <option value="{{ $a->id }}">{{ $a->name }} (Stok: {{ $a->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-24">
                        <input type="number" name="items[${rowCount}][quantity]" value="1" min="1" class="w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>
                    <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md remove-row">Hapus</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newRow);
            rowCount++;
        });

        // Event listener untuk menghapus baris
        container.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-row') && !e.target.disabled) {
                e.target.closest('.item-row').remove();
            }
        });
    </script>
</x-app-layout>