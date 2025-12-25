<x-app-layout>
    @section('header', 'Kelola Program Studi')

    <div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-navy-700">Daftar Program Studi</h3>
            <a href="{{ route('admin.prodis.create') }}" class="px-4 py-2 bg-navy-700 hover:bg-navy-800 text-white rounded-lg text-sm font-bold shadow-md transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Prodi
            </a>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-100">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Fakultas</th>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Nama Prodi</th>
                        <th class="px-6 py-4">Lokasi Kantor</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($prodis as $prodi)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-500">{{ $prodi->faculty }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-teal-100 text-teal-800 px-2 py-1 rounded text-xs font-mono font-bold border border-teal-200">
                                {{ $prodi->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-navy-800">{{ $prodi->name }}</td>
                        <td class="px-6 py-4 text-gray-600 text-xs">
                            <i class="fas fa-map-marker-alt text-red-400 mr-1"></i> {{ $prodi->location_office ?? '-' }}
                        </td>

                        {{-- BAGIAN AKSI (IKON) --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.prodis.edit', $prodi->id) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition border border-yellow-100" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.prodis.destroy', $prodi->id) }}" method="POST" onsubmit="return confirm('Hapus prodi ini? Data lab dan user terkait mungkin akan error.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition border border-red-100" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data program studi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $prodis->links() }}
        </div>
    </div>
</x-app-layout>