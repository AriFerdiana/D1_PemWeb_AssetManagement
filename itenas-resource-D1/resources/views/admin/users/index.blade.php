<x-app-layout>
    @section('header', 'Kelola Pengguna')

    <div class="space-y-6">
        {{-- Search & Filter --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari Nama / NIM / Email..." 
                       class="flex-1 border-gray-300 rounded-lg text-sm focus:ring-navy-500">
                
                <button type="submit" class="bg-navy-700 text-white px-6 py-2 rounded-lg font-bold text-sm hover:bg-navy-800 transition">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                
                {{-- Tombol Tambah hanya untuk yang berwenang --}}
                <a href="{{ route('admin.users.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-orange-600 transition flex items-center shadow-sm">
                    <i class="fas fa-user-plus mr-2"></i> Tambah User
                </a>
            </form>
        </div>

        {{-- Tabel Data Pengguna --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Nama & Email</th>
                        <th class="px-6 py-4">Identitas (NIM)</th>
                        <th class="px-6 py-4">Prodi</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-navy-100 text-navy-700 flex items-center justify-center font-bold mr-3 uppercase">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $u->name }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-gray-600">{{ $u->nim ?? '-' }}</td>
                            <td class="px-6 py-4 uppercase font-bold text-xs text-teal-600">
                                {{ $u->prodi->code ?? ($u->prodi ?? 'Umum') }}
                            </td>
                            <td class="px-6 py-4">
                                @foreach($u->roles as $role)
                                    <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[10px] font-black uppercase">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    {{-- PROTEKSI KEAMANAN: Laboran hanya bisa edit user di prodinya & bukan Superadmin --}}
                                    @php
                                        $canManage = Auth::user()->hasRole('Superadmin') || 
                                                    (Auth::user()->hasRole('Laboran') && 
                                                     $u->prodi_id == Auth::user()->prodi_id && 
                                                     !$u->hasRole('Superadmin'));
                                    @endphp

                                    @if($canManage)
                                        <a href="{{ route('admin.users.edit', $u->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 shadow-sm" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 shadow-sm" title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-gray-400 italic">Terproteksi</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-10 text-gray-400 italic font-medium">Data pengguna tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t bg-gray-50">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>