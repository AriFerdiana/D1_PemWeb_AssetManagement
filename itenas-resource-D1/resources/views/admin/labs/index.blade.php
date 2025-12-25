<x-app-layout>
    @section('header', 'Kelola Laboratorium')

    <div class="space-y-6">
        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        {{-- Action Bar --}}
        <div class="flex flex-col md:flex-row justify-between gap-4 bg-white p-4 rounded-xl shadow-sm">
            <form method="GET" action="{{ route('admin.labs.index') }}" class="w-full md:w-1/2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lab atau gedung..." class="w-full rounded-lg border-gray-300 text-sm">
            </form>
            <a href="{{ route('admin.labs.create') }}" class="px-4 py-2 bg-navy-700 hover:bg-navy-800 text-white rounded-lg text-sm font-bold shadow flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Tambah Lab Baru
            </a>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-xs">
                        <tr>
                            <th class="px-6 py-4">Nama Lab</th>
                            <th class="px-6 py-4">Prodi & Lokasi</th>
                            <th class="px-6 py-4">Kapasitas</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($labs as $lab)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-navy-800">
                                {{ $lab->name }}
                                <div class="text-xs text-gray-400 font-normal mt-1">{{ Str::limit($lab->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-700">{{ $lab->prodi->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-building text-teal-500"></i> {{ $lab->building_name }} - {{ $lab->room_number }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $lab->capacity }} Orang</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    {{-- TOMBOL LIHAT (PETA) --}}
                                    <a href="{{ route('admin.labs.show', $lab->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 border border-blue-200" title="Lihat Peta & Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- TOMBOL EDIT --}}
                                    <a href="{{ route('admin.labs.edit', $lab->id) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded hover:bg-yellow-100 border border-yellow-200" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- TOMBOL HAPUS --}}
                                    <form action="{{ route('admin.labs.destroy', $lab->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded hover:bg-red-100 border border-red-200" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $labs->links() }}</div>
        </div>
    </div>
</x-app-layout>