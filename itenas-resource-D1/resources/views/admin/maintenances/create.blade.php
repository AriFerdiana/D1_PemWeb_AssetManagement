<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Catat Perawatan Baru</h2></x-slot>
    <div class="py-12"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        <form action="{{ route('admin.maintenances.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-2">Pilih Aset</label>
                <select name="asset_id" class="w-full border-gray-300 rounded">
                    @foreach($assets as $asset) <option value="{{ $asset->id }}">{{ $asset->name }} ({{ $asset->serial_number }})</option> @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-2">Tanggal</label>
                <input type="date" name="date" class="w-full border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2">Jenis Kegiatan</label>
                <select name="type" class="w-full border-gray-300 rounded">
                    <option>Perbaikan (Rusak)</option>
                    <option>Perawatan Rutin</option>
                    <option>Upgrade</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-2">Deskripsi</label>
                <textarea name="description" class="w-full border-gray-300 rounded"></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-2">Biaya (Rp)</label>
                <input type="number" name="cost" class="w-full border-gray-300 rounded" value="0">
            </div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div></div></div>
</x-app-layout>