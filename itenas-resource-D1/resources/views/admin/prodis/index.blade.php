<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kelola Program Studi') }}
            </h2>
            <a href="{{ route('admin.prodis.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-bold">
                + Tambah Prodi
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
                            <th class="px-6 py-3">Nama Prodi</th>
                            <th class="px-6 py-3">Kode</th>
                            <th class="px-6 py-3">Kaprodi</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prodis as $prodi)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-bold">{{ $prodi->name }}</td>
                            <td class="px-6 py-4 font-mono">{{ $prodi->code }}</td>
                            <td class="px-6 py-4">{{ $prodi->kaprodi_name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.prodis.destroy', $prodi->id) }}" method="POST" onsubmit="return confirm('Hapus prodi ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $prodis->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>