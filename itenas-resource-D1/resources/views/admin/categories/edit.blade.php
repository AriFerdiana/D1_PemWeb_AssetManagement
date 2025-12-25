<x-app-layout>
    @section('header', 'Edit Kategori Aset')

    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            {{-- Nama Kategori --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500" required>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg font-bold hover:bg-teal-700 shadow-md transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</x-app-layout>