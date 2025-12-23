<x-app-layout>
    @section('header', 'Kelola Pengguna')

    <div class="space-y-6">
        
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            
            <form action="{{ route('admin.users.index') }}" method="GET" class="w-full md:w-auto">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 w-full md:w-64 text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
            </form>

            <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-navy-700 hover:bg-navy-800 text-white rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-lg">
                <i class="fas fa-user-plus"></i> Tambah User Baru
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-card overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase font-bold text-xs tracking-wider border-b border-gray-200 dark:border-gray-600">
                        <tr>
                            <th class="px-6 py-4">Nama User</th>
                            <th class="px-6 py-4">Email & Prodi</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div class="font-bold text-navy-800 dark:text-white">{{ $user->name }}</div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-gray-700 dark:text-gray-300">{{ $user->email }}</div>
                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-graduation-cap text-gray-400"></i>
                                    {{ $user->prodi->name ?? 'Non-Prodi' }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $role = $user->getRoleNames()->first();
                                    $color = match($role) {
                                        'Superadmin' => 'purple',
                                        'Laboran' => 'blue',
                                        'Dosen' => 'orange',
                                        default => 'gray'
                                    };
                                @endphp
                                <span class="px-2 py-1 bg-{{ $color }}-100 text-{{ $color }}-700 rounded-full text-xs font-bold border border-{{ $color }}-200">
                                    {{ ucfirst($role) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition border border-yellow-200" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition border border-red-200" title="Hapus User">
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
            
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 dark:text-white">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>