<x-app-layout>
    @section('header', 'Edit Pengguna')

    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-navy-800">Ubah Data Pengguna</h2>
                <p class="text-xs text-gray-500">Pastikan data NIM dan Prodi sudah sesuai untuk akses sirkulasi.</p>
            </div>

            {{-- Pastikan menggunakan $targetUser->id sesuai kiriman Controller --}}
            <form action="{{ route('admin.users.update', $targetUser->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="space-y-5">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $targetUser->name) }}" 
                               class="w-full rounded-lg border-gray-300 focus:ring-navy-500 text-sm">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $targetUser->email) }}" 
                               class="w-full rounded-lg border-gray-300 bg-gray-50 text-sm" readonly>
                        <p class="text-[10px] text-gray-400 mt-1">*Email tidak dapat diubah untuk keamanan sistem.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- NIM --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">NIM / Identitas</label>
                            <input type="text" name="nim" value="{{ old('nim', $targetUser->nim) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:ring-navy-500 text-sm">
                        </div>

                        {{-- Prodi --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Program Studi</label>
                            <select name="prodi_id" class="w-full rounded-lg border-gray-300 focus:ring-navy-500 text-sm">
                                <option value="">-- Pilih Prodi --</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}" {{ $targetUser->prodi_id == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Role (Hanya muncul jika Superadmin yang mengedit) --}}
                    @hasrole('Superadmin')
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Role / Hak Akses</label>
                        <select name="role" class="w-full rounded-lg border-gray-300 focus:ring-navy-500 text-sm">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $targetUser->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endhasrole

                    <div class="pt-4 flex gap-3">
                        <button type="submit" class="flex-1 bg-navy-700 text-white py-2.5 rounded-lg font-bold text-sm hover:bg-navy-800 transition shadow-md">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-600 rounded-lg font-bold text-sm hover:bg-gray-50 transition">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>