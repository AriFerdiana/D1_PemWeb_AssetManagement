<x-app-layout>
    @section('header', 'Edit Pengguna')

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama Lengkap --}}
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500" required>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500" required>
                </div>

                {{-- Role / Hak Akses --}}
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Role / Hak Akses</label>
                    <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Program Studi (PERBAIKAN UTAMA DISINI) --}}
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Program Studi (Wajib untuk Laboran, Mahasiswa & Dosen)
                    </label>
                    <select name="prodi_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500">
                        <option value="">- Tidak Ada (Khusus Superadmin) -</option>
                        
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ $user->prodi_id == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1 italic">
                        *Laboran hanya akan bisa mengelola aset sesuai prodi yang dipilih disini.
                    </p>
                </div>

                {{-- Ganti Password --}}
                <div class="mb-6 pt-4 border-t">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ganti Password (Opsional)</label>
                    <input type="password" name="password" placeholder="Isi hanya jika ingin mengganti password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 mb-2">
                    <input type="password" name="password_confirmation" placeholder="Ulangi Password Baru" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500">
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg font-bold hover:bg-teal-700 shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>