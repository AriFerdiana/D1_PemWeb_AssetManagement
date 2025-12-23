<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">Email (Unik)</label>
                        <input type="email" name="email" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">Password</label>
                            <input type="password" name="password" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>

                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">Role Pengguna</label>
                            <select name="role" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                <option value="Mahasiswa">Mahasiswa</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Laboran">Laboran</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">Prodi (Opsional)</label>
                            <select name="prodi_id" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                                <option value="">- Tidak Ada -</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Buat User</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>