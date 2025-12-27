<x-app-layout>
    @section('header', 'Manajemen Perawatan Aset')

    {{-- CSS KHUSUS UNTUK MEMAKSA WARNA --}}
    <style>
        .force-light-input {
            background-color: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #d1d5db !important;
        }
        .force-light-input::placeholder {
            color: #6b7280 !important;
            opacity: 1 !important;
        }
        html.dark .force-dark-text { color: #ffffff !important; }
        html.dark .force-dark-subtext { color: #d1d5db !important; }
        html.dark .force-dark-border { border-color: #374151 !important; }
    </style>

    <div class="space-y-6">
        
        {{-- BAGIAN TOOLBAR: SEARCH, FILTER & CETAK --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col lg:flex-row gap-4 justify-between items-center">
                
                {{-- Form Pencarian & Filter --}}
                <form method="GET" action="{{ route('admin.maintenances.index') }}" class="w-full lg:w-auto flex flex-col md:flex-row gap-3">
                    
                    {{-- Input Search --}}
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 z-10">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Cari aset/masalah..." 
                            class="force-light-input pl-10 pr-4 py-2 rounded-lg focus:ring-teal-500 focus:border-teal-500 w-full md:w-64 transition-colors">
                    </div>

                    {{-- Dropdown Filter Status --}}
                    <select name="status" onchange="this.form.submit()" 
                        class="force-light-input py-2 pl-3 pr-8 rounded-lg focus:ring-teal-500 focus:border-teal-500 cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Proses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>

                    {{-- Tombol Reset --}}
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.maintenances.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition flex items-center font-medium">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </a>
                    @endif
                </form>

                <div class="flex items-center gap-2 w-full lg:w-auto">
                    {{-- Tombol PDF (Fitur Baru) --}}
                    <a href="{{ route('admin.maintenances.export-pdf', request()->all()) }}" target="_blank" class="flex-1 lg:flex-none px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold shadow transition flex items-center justify-center">
                        <i class="fas fa-file-pdf mr-2"></i> Laporan PDF
                    </a>

                    {{-- Tombol Tambah --}}
                    <a href="{{ route('admin.maintenances.create') }}" class="flex-1 lg:flex-none px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-bold shadow-lg transition flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i> Lapor Kerusakan
                    </a>
                </div>
            </div>
        </div>

        {{-- TABEL DATA --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-card overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700 uppercase font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 border-b force-dark-border text-gray-700 force-dark-text">Detail Aset</th>
                            <th class="px-6 py-4 border-b force-dark-border text-gray-700 force-dark-text">Masalah & Biaya</th>
                            <th class="px-6 py-4 border-b force-dark-border text-gray-700 force-dark-text">Tanggal</th>
                            <th class="px-6 py-4 border-b force-dark-border text-gray-700 force-dark-text text-center">Status</th>
                            <th class="px-6 py-4 border-b force-dark-border text-gray-700 force-dark-text text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($maintenances as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 font-medium text-gray-900 force-dark-text">
                                    <div class="flex flex-col">
                                        <span class="text-base font-bold">{{ $item->asset->name ?? '-' }}</span>
                                        <span class="text-xs text-teal-600 dark:text-teal-400 font-mono">{{ $item->asset->code ?? '' }}</span>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $item->asset->lab->name ?? 'Prodi Anda' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-gray-600 force-dark-subtext">
                                    <div class="max-w-xs truncate italic">"{{ $item->description }}"</div>
                                    <div class="text-sm font-bold text-gray-900 force-dark-text mt-1">
                                        Rp {{ number_format($item->cost, 0, ',', '.') }}
                                    </div>
                                    <span class="text-[10px] px-1.5 py-0.5 bg-gray-100 rounded text-gray-500 border border-gray-200 uppercase">
                                        {{ str_replace('_', ' ', $item->funding_source ?? 'prodi_budget') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-600 force-dark-subtext whitespace-nowrap">
                                    <div class="font-bold">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</div>
                                    <span class="text-xs text-gray-400 italic">Dilaporkan: {{ $item->created_at->format('H:i') }}</span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusStyles = match($item->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'completed' => 'bg-green-100 text-green-800 border-green-200',
                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                            default => 'bg-gray-100 text-gray-800 border-gray-200'
                                        };
                                        $statusIcon = match($item->status) {
                                            'pending' => '🕒',
                                            'in_progress' => '🔧',
                                            'completed' => '✅',
                                            'cancelled' => '❌',
                                            default => '•'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase border {{ $statusStyles }}">
                                        {{ $statusIcon }} {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('admin.maintenances.edit', $item->id) }}" 
                                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 border border-yellow-200 transition" 
                                           title="Update Progress">
                                            <i class="fas fa-wrench"></i>
                                        </a>
                                        <form action="{{ route('admin.maintenances.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus log perawatan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 force-dark-subtext italic">
                                    <i class="fas fa-toolbox text-4xl mb-3 opacity-20"></i>
                                    <p>Belum ada aktivitas perawatan aset di prodi Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                {{ $maintenances->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>