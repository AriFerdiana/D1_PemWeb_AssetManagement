<x-app-layout>
    @section('header', 'Kelola Kategori Aset')

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-navy-700">Daftar Kategori</h3>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-navy-700 hover:bg-navy-800 text-white rounded-lg text-sm font-bold shadow-md transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Kategori
            </a>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-100">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Jumlah Aset</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-navy-800">{{ $cat->name }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-blue-200">
                                {{ $cat->assets->count() }} Item
                            </span>
                        </td>
                        
                        {{-- BAGIAN AKSI (IKON) --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.categories.edit', $cat->id) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition border border-yellow-100" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Aset di dalamnya akan kehilangan kategori.');">
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
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data kategori.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>