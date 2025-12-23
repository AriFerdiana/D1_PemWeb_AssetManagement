<x-app-layout>
    @section('header', 'Pengaturan Akun')

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-24">
                
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6 border-t-4 border-teal-500 text-center sticky top-24">
                        
                        <div class="relative w-32 h-32 mx-auto mb-4 group">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full rounded-full object-cover border-4 border-teal-100 shadow-lg">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-teal-400 to-navy-600 rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                                    <span class="text-4xl font-bold text-white tracking-widest">
                                        {{ substr($user->name, 0, 2) }}
                                    </span>
                                </div>
                            @endif

                            <label for="avatarInput" class="absolute bottom-0 right-0 bg-navy-700 text-white p-2 rounded-full cursor-pointer hover:bg-navy-900 transition shadow-md" title="Ganti Foto">
                                <i class="fas fa-camera text-sm"></i>
                            </label>
                        </div>

                        <h3 class="text-xl font-bold text-navy-800 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $user->email }}</p>

                        <div class="inline-block px-4 py-1 bg-navy-100 dark:bg-navy-900 text-navy-700 dark:text-navy-300 rounded-full text-xs font-bold border border-navy-200 dark:border-navy-700 mb-6">
                            {{ ucfirst($user->getRoleNames()->first() ?? 'User') }}
                        </div>

                        <div class="border-t border-gray-100 dark:border-gray-700 pt-4 text-left space-y-3">
                            <p class="text-xs text-gray-400 uppercase font-bold mb-2">Info Akun</p>
                            <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                                <div class="w-6 text-center"><i class="fas fa-calendar-alt text-teal-500"></i></div>
                                <span>Bergabung: {{ $user->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="bg-teal-100 p-2 rounded-lg text-teal-600"><i class="fas fa-user-edit"></i></div>
                            <h3 class="text-lg font-bold text-navy-800 dark:text-white">Edit Identitas</h3>
                        </div>

                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf @method('patch')

                            <input type="file" name="avatar" id="avatarInput" class="hidden" onchange="document.getElementById('fileNamePreview').innerText = 'Foto terpilih: ' + this.files[0].name">

                            <p id="fileNamePreview" class="text-xs text-teal-600 font-bold italic text-center"></p>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500">
                                <x-input-error class="mt-1 text-xs text-red-500" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500">
                                <x-input-error class="mt-1 text-xs text-red-500" :messages="$errors->get('email')" />
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="bg-navy-700 hover:bg-navy-800 text-white px-6 py-2.5 rounded-lg font-bold shadow-md transition flex items-center gap-2">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>

                            @if (session('status') === 'profile-updated')
                                <p class="text-sm text-green-600 font-bold mt-2 flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Profil berhasil diperbarui.
                                </p>
                            @endif
                        </form>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="bg-orange-100 p-2 rounded-lg text-orange-600"><i class="fas fa-lock"></i></div>
                            <h3 class="text-lg font-bold text-navy-800 dark:text-white">Ganti Password</h3>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf @method('put')

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Password Saat Ini</label>
                                <input type="password" name="current_password" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500">
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-xs text-red-500 font-bold" />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Password Baru</label>
                                <input type="password" name="password" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500">
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-xs text-red-500 font-bold" />
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500">
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-xs text-red-500 font-bold" />
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <button type="submit" 
                                        style="background-color: #ea580c !important; color: white !important;" 
                                        class="w-full md:w-auto px-6 py-3 rounded-lg font-bold shadow-lg transition flex items-center justify-center gap-2 hover:opacity-90">
                                    <i class="fas fa-key"></i> Update Password
                                </button>
                            </div>

                            @if (session('status') === 'password-updated')
                                <p class="text-sm text-green-600 font-bold mt-2 flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Password berhasil diganti.
                                </p>
                            @endif
                        </form>
                    </div>

                    <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl shadow-sm p-6 border border-red-200 dark:border-red-800">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-red-700 dark:text-red-400">Hapus Akun Permanen</h3>
                                <p class="text-sm text-red-600/70 dark:text-red-300">Data tidak dapat dikembalikan setelah dihapus.</p>
                            </div>
                            
                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg font-bold shadow transition text-sm flex items-center gap-2">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    </div>

                    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white dark:bg-gray-800">
                            @csrf @method('delete')
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Apakah Anda yakin?</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Masukkan password Anda untuk mengonfirmasi penghapusan akun ini secara permanen.</p>
                            
                            <div class="mt-6">
                                <input type="password" name="password" placeholder="Password Anda" class="w-full md:w-3/4 rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-red-500">
                                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-500" />
                            </div>
                            
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-lg font-medium">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 shadow-lg">
                                    Ya, Hapus Akun
                                </button>
                            </div>
                        </form>
                    </x-modal>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>