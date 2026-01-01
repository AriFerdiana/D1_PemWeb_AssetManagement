<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-navy-800 dark:text-white leading-tight flex items-center gap-2">
            <i class="fas fa-user-edit text-yellow-500"></i>
            {{ __('Edit Data Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-card sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-8">
                
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Ubah Data: {{ $targetUser->name }}</h3>
                    <p class="text-sm text-gray-500">Pastikan data NIM dan Kontak sudah sesuai untuk keperluan sirkulasi.</p>
                </div>

                <form action="{{ route('admin.users.update', $targetUser->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT') {{-- PENTING: Gunakan PUT untuk update --}}
                    
                    <div class="space-y-6">
                        
                        {{-- Bagian 1: Identitas --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $targetUser->name) }}"
                                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500" 
                                    required>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- NIM / NIDN --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">NIM / NIDN</label>
                                <input type="text" name="nim" value="{{ old('nim', $targetUser->nim) }}"
                                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">
                                @error('nim') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Bagian 2: Kontak (INI YANG SEBELUMNYA HILANG) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Email (Readonly) --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', $targetUser->email) }}"
                                    class="w-full rounded-xl border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed" 
                                    readonly>
                                <p class="text-[10px] text-gray-400 mt-1">*Email tidak dapat diubah demi keamanan.</p>
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- No HP (Baru) --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nomor WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone', $targetUser->phone) }}"
                                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500"
                                    placeholder="0812...">
                                @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Alamat (Baru) --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Alamat Domisili</label>
                            <textarea name="address" rows="2" 
                                class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">{{ old('address', $targetUser->address) }}</textarea>
                        </div>

                        {{-- Bagian 3: Hak Akses (Beda Tampilan untuk Superadmin vs Laboran) --}}
                        <div class="border-t pt-4 mt-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-xl">
                            <h4 class="text-sm font-bold text-gray-800 dark:text-white mb-4">Pengaturan Akses</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- ROLE: Hanya Superadmin yang bisa ganti Role --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Role Pengguna</label>
                                    
                                    @hasrole('Superadmin')
                                        {{-- Jika Superadmin: Tampilkan Select Box --}}
                                        <select name="role" class="w-full rounded-xl border-gray-300 focus:ring-teal-500">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ $targetUser->hasRole($role->name) ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        {{-- Jika Laboran: Hanya Teks (Hidden Input) --}}
                                        <input type="text" value="{{ ucfirst($targetUser->getRoleNames()->first()) }}" 
                                               class="w-full rounded-xl border-gray-200 bg-gray-200 text-gray-500 cursor-not-allowed" readonly>
                                        {{-- Kirim value asli agar controller tidak error validasi --}}
                                        <input type="hidden" name="role" value="{{ $targetUser->getRoleNames()->first() }}">
                                    @endhasrole
                                </div>

                                {{-- PRODI: Hanya Superadmin yang bisa ganti Prodi --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Program Studi</label>
                                    
                                    @hasrole('Superadmin')
                                        {{-- Jika Superadmin: Boleh ganti prodi --}}
                                        <select name="prodi_id" class="w-full rounded-xl border-gray-300 focus:ring-teal-500">
                                            <option value="">-- Tidak Ada --</option>
                                            @foreach($prodis as $prodi)
                                                <option value="{{ $prodi->id }}" {{ $targetUser->prodi_id == $prodi->id ? 'selected' : '' }}>
                                                    {{ $prodi->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        {{-- Jika Laboran: Readonly (Sesuai Prodi User Tersebut) --}}
                                        <input type="text" value="{{ $targetUser->prodi->name ?? '-' }}" 
                                               class="w-full rounded-xl border-gray-200 bg-gray-200 text-gray-500 cursor-not-allowed" readonly>
                                        <input type="hidden" name="prodi_id" value="{{ $targetUser->prodi_id }}">
                                    @endhasrole
                                </div>
                            </div>
                        </div>

                        {{-- Password Reset (Opsional) --}}
                        <div class="mt-4">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Ubah Password (Opsional)</label>
                            <input type="password" name="password" autocomplete="new-password"
                                class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500" 
                                placeholder="Biarkan kosong jika tidak ingin mengganti password">
                            
                            <input type="password" name="password_confirmation" 
                                class="w-full rounded-xl border-gray-300 mt-2 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500" 
                                placeholder="Konfirmasi Password Baru">
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t">
                            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" class="bg-navy-700 hover:bg-navy-800 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg transition transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>