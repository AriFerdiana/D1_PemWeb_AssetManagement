<x-app-layout>
    @section('header', 'Data Maintenance')

    {{-- CSS KHUSUS UNTUK MEMAKSA WARNA (TANPA PERLU COMPILE ULANG) --}}
    <style>
        /* 1. Paksa Input & Select agar SELALU Putih dengan Tulisan Hitam */
        .force-light-input {
            background-color: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #d1d5db !important;
        }
        .force-light-input::placeholder {
            color: #6b7280 !important; /* Abu-abu gelap */
            opacity: 1 !important;
        }

        /* 2. Paksa Teks Tabel menjadi Putih saat Dark Mode aktif */
        /* Kita deteksi class 'dark' di tag HTML */
        html.dark .force-dark-text {
            color: #ffffff !important;
        }
        html.dark .force-dark-subtext {
            color: #d1d5db !important; /* Abu-abu terang */
        }
        html.dark .force-dark-border {
            border-color: #374151 !important; /* Gray 700 */
        }
    </style>

    <div class="space-y-6">
        
        {{-- BAGIAN TOOLBAR: SEARCH & FILTER --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row gap-4 justify-between items-center">
            
            {{-- Form Pencarian & Filter --}}
            <form method="GET" action="{{ route('admin.maintenances.index') }}" class="w-full md:w-auto flex flex-col md:flex-row gap-3">
                
                {{-- Input Search --}}
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 z-10">
                        <i class="fas fa-search"></i>
                    </span>
                    {{-- Menggunakan class 'force-light-input' --}}
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari aset/masalah..." 
                        class="force-light-input pl-10 pr-4 py-2 rounded-lg focus:ring-teal-500 focus:border-teal-500 w-full md:w-64 transition-colors">
                </div>

                {{-- Dropdown Filter Status --}}
                {{-- Menggunakan class 'force-light-input' --}}
                <select name="status" onchange="this.form.submit()" 
                    class="force-light-input py-2 pl-3 pr-8 rounded-lg focus:ring-teal-500 focus:border-teal-500 cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>

                {{-- Tombol Reset --}}
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.maintenances.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition flex items-center font-medium">
                        <i class="fas fa-undo mr-2"></i> Reset
                    </a>
                @endif
            </form>

            {{-- Tombol Tambah --}}
            <a href="{{ route('admin.maintenances.create') }}" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-bold shadow-lg transition flex items-center">
                <i class="fas fa-plus mr-2"></i> Lapor Kerusakan
            </a>
        </div>

        {{-- TABEL DATA --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-card overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    {{-- Header Tabel --}}
                    <thead class="bg-gray-50 dark:bg-gray-700 uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 border-b force-dark-border text-gray-700 force-dark-text">Aset</th>
                            <th class="px-6 py-4 border-b force-dark-border text-gray-700 force-dark-text">Masalah</th>
                            <th class="px-6 py-4 border-b force-dark-border text-gray-700 force-dark-text">Tanggal Lapor</th>
                            <th class="px-6 py-4 border-b force-dark-border text-gray-700 force-dark-text">Status</th>
                            <th class="px-6 py-4 border-b force-dark-border text-gray-700 force-dark-text text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($maintenances as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                
                                {{-- Kolom Nama Aset --}}
                                <td class="px-6 py-4 font-medium text-gray-900 force-dark-text">
                                    <div class="flex flex-col">
                                        <span class="text-base">{{ $item->asset->name ?? '-' }}</span>
                                        {{-- Kode Aset --}}
                                        <span class="text-xs text-gray-500 force-dark-subtext font-mono mt-0.5">{{ $item->asset->code ?? '' }}</span>
                                    </div>
                                </td>

                                {{-- Kolom Masalah --}}
                                <td class="px-6 py-4 text-gray-600 force-dark-subtext">
                                    {{ Str::limit($item->description, 50) }}
                                </td>

                                {{-- Kolom Tanggal --}}
                                <td class="px-6 py-4 text-gray-600 force-dark-subtext whitespace-nowrap">
                                    {{ $item->created_at->format('d M Y') }}
                                    <span class="block text-xs text-gray-400 force-dark-subtext">{{ $item->created_at->format('H:i') }} WIB</span>
                                </td>

                                {{-- Kolom Status --}}
                                <td class="px-6 py-4">
                                    @if($item->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                            🕒 Pending
                                        </span>
                                    @elseif($item->status == 'in_progress')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                            🔧 Proses
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                            ✅ Selesai
                                        </span>
                                    @endif
                                </td>
                                
                                {{-- Kolom Aksi --}}
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        {{-- Detail --}}
                                        <a href="{{ route('admin.maintenances.show', $item->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.maintenances.edit', $item->id) }}" 
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition" 
                                           title="Edit Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        {{-- Hapus --}}
                                        <form action="{{ route('admin.maintenances.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 force-dark-subtext">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-full mb-3">
                                            <i class="fas fa-folder-open text-3xl text-gray-400"></i>
                                        </div>
                                        <p class="font-medium">Data tidak ditemukan.</p>
                                        <p class="text-sm mt-1">Belum ada laporan kerusakan yang masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-100 dark:border-gray-700 force-dark-text">
                {{ $maintenances->links() }}
            </div>
        </div>
    </div>
</x-app-layout>