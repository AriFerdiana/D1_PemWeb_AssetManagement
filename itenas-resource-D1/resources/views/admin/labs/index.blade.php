<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kelola Ruangan Lab') }}
            </h2>
            <a href="{{ route('admin.labs.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-bold">
                + Tambah Lab
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('error') }}</div>
                @endif

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">Nama Lab</th>
                            <th class="px-6 py-3">Kode</th>
                            <th class="px-6 py-3">Gedung</th>
                            <th class="px-6 py-3">Deskripsi</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($labs as $lab)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-bold">{{ $lab->name }}</td>
                            <td class="px-6 py-4 font-mono">{{ $lab->code }}</td>
                            <td class="px-6 py-4">{{ $lab->building_name }}</td>
                            <td class="px-6 py-4">{{ Str::limit($lab->description, 30) }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.labs.destroy', $lab->id) }}" method="POST" onsubmit="return confirm('Hapus lab ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $labs->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>