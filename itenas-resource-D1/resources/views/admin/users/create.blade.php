<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-navy-800 dark:text-white leading-tight flex items-center gap-2">
            <i class="fas fa-user-plus text-teal-500"></i>
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-card sm:rounded-2xl border border-gray-100 dark:border-gray-700 p-8">
                
                {{-- Form Start --}}
                {{-- autocomplete="off" di form tag mematikan saran browser --}}
                <form action="{{ route('admin.users.store') }}" method="POST" autocomplete="off">
                    @csrf
                    
                    <div class="space-y-6">
                        
                        {{-- Bagian 1: Identitas --}}
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Identitas Dasar</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Nama Lengkap --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500" 
                                        placeholder="Contoh: Budi Santoso" required>
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                {{-- NIM / NIDN (PENTING) --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">NIM / NIDN</label>
                                    <input type="text" name="nim" value="{{ old('nim') }}"
                                        class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500" 
                                        placeholder="Contoh: 152024001">
                                    @error('nim') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Bagian 2: Kontak & Alamat --}}
                        <div class="border-b pb-4 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Email (Unik) --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Alamat Email</label>
                                    {{-- autocomplete="off" agar tidak diisi otomatis --}}
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500" 
                                        autocomplete="off" placeholder="email@itenas.ac.id" required>
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                {{-- No HP --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nomor WhatsApp</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                        class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500" 
                                        placeholder="08123456789">
                                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            {{-- Alamat --}}
                            <div class="mt-4">
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Alamat Domisili</label>
                                <textarea name="address" rows="2" 
                                    class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500"
                                    placeholder="Jalan PHH Mustofa No. 23...">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        {{-- Bagian 3: Keamanan & Akses --}}
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Keamanan Akun</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Password</label>
                                    {{-- autocomplete="new-password" PENTING --}}
                                    <input type="password" name="password" 
                                        class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500" 
                                        autocomplete="new-password" placeholder="Minimal 8 karakter" required>
                                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" 
                                        class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500" 
                                        required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Role --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Role Pengguna</label>
                                    <select name="role" class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Prodi --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Program Studi</label>
                                    <select name="prodi_id" class="w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">
                                        <option value="">-- Pilih Prodi (Jika Ada) --</option>
                                        @foreach($prodis as $prodi)
                                            <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[10px] text-gray-500 mt-1">*Wajib dipilih untuk Mahasiswa & Laboran</p>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t">
                            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" class="bg-navy-700 hover:bg-navy-800 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg transition transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i> Simpan User
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>