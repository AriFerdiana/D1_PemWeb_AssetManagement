<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Data Aset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-4 rounded mb-4 shadow-sm border-l-4 border-green-500">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 text-red-800 p-4 rounded mb-4 shadow-sm border-l-4 border-red-500">{{ session('error') }}</div>
                @endif

                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <a href="{{ route('admin.assets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-bold shadow transition flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Aset Manual
                    </a>

                    <div class="flex items-center bg-gray-50 dark:bg-gray-700 p-2 rounded-lg border border-gray-200 dark:border-gray-600">
                        <form action="{{ route('admin.assets.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                            @csrf
                            <input type="file" name="file" class="text-sm text-gray-500 file:mr-2 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:text-gray-300" required>
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm font-bold shadow transition flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Import Excel
                            </button>
                        </form>
                    </div>
                </div>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Nama Alat</th>
                                <th class="px-6 py-3">Kode / Serial Number</th>
                                <th class="px-6 py-3">Lokasi (Lab)</th>
                                <th class="px-6 py-3">Status Kondisi</th>
                                <th class="px-6 py-3 text-center">Stok</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assets as $asset)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $asset->name }}</td>
                                <td class="px-6 py-4 font-mono text-xs">{{ $asset->serial_number ?? $asset->code }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                        {{ $asset->lab->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- LABEL STATUS KONDISI --}}
                                    @if($asset->status == 'available')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            ✅ Tersedia
                                        </span>
                                    @elseif($asset->status == 'maintenance')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 uppercase">
                                            🛠️ Maintenance
                                        </span>
                                    @elseif($asset->status == 'broken')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 uppercase">
                                            ❌ Rusak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 uppercase">
                                            Borrowed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-center {{ $asset->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $asset->stock }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.assets.edit', $asset->id) }}" class="text-yellow-500 hover:text-yellow-700 font-bold flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.assets.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Yakin hapus aset ini? Semua histori terkait mungkin terdampak.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-bold flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $assets->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>