<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Log Perawatan Aset</h2>
            <a href="{{ route('admin.maintenances.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Catat Perawatan</a>
        </div>
    </x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8"><div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
        <table class="w-full text-sm text-gray-500">
            <thead class="text-xs uppercase bg-gray-50"><tr>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Aset</th>
                <th class="px-6 py-3">Jenis</th>
                <th class="px-6 py-3">Biaya</th>
                <th class="px-6 py-3">Aksi</th>
            </tr></thead>
            <tbody>
                @foreach($maintenances as $m)
                <tr class="border-b">
                    <td class="px-6 py-4">{{ $m->date }}</td>
                    <td class="px-6 py-4 font-bold">{{ $m->asset->name }}</td>
                    <td class="px-6 py-4">{{ $m->type }}</td>
                    <td class="px-6 py-4">Rp {{ number_format($m->cost) }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.maintenances.destroy', $m->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-red-500">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div></div></div>
</x-app-layout>