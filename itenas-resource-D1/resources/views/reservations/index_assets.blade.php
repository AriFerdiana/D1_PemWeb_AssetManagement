<x-app-layout>
    @section('header', 'Riwayat Transaksi')

    <div class="flex flex-col gap-4 mb-6">
        
        {{-- === BAGIAN SEARCH, FILTER STATUS & TANGGAL === --}}
        <div class="w-full bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            {{-- Form Filter --}}
            <form action="{{ route('admin.reservations.index') }}" method="GET" class="flex flex-col gap-4">
                
                <div class="flex flex-col md:flex-row gap-3">
                    {{-- 1. Input Search --}}
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari Kode TRX / Nama Peminjam..." 
                               class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500">
                    </div>

                    {{-- 2. Dropdown Filter Status --}}
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500 bg-white cursor-pointer md:w-48">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                {{-- Baris Kedua: Filter Tanggal & Tombol Aksi --}}
                <div class="flex flex-col md:flex-row gap-3 items-center justify-between border-t border-gray-100 pt-3 mt-1">
                    
                    {{-- 3. Filter Tanggal (Datepicker) --}}
                    <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <span class="text-xs font-bold text-gray-500 uppercase">Dari:</span>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                   class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500 w-full md:w-auto">
                        </div>
                        <span class="hidden md:inline text-gray-400">-</span>
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <span class="text-xs font-bold text-gray-500 uppercase">Sampai:</span>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                   class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500 w-full md:w-auto">
                        </div>
                    </div>

                    {{-- Tombol-tombol --}}
                    <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                        {{-- Tombol Cari --}}
                        <button type="submit" class="px-5 py-2 bg-navy-700 text-white rounded-lg text-sm font-bold hover:bg-navy-800 transition shadow-sm flex items-center gap-2">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>

                        {{-- Tombol Reset --}}
                        @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                            <a href="{{ route('admin.reservations.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-200 transition border border-gray-200" title="Reset Filter">
                                Reset
                            </a>
                        @endif
                        
                        {{-- Tombol Export PDF (Dipindah kesini biar rapi) --}}
                        @role('Superadmin|Laboran')
                        <div class="border-l border-gray-300 h-6 mx-1 hidden md:block"></div>
                        <a href="{{ route('admin.reports.pdf') }}" target="_blank" class="px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-lg text-sm font-bold hover:bg-red-100 transition flex items-center gap-1">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        @endrole
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- === TABEL DATA === --}}
    <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Kode TRX</th>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Status & Denda</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reservations as $trx)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Kode TRX --}}
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-navy-700 bg-gray-100 px-2 py-1 rounded border">{{ $trx->transaction_code }}</span>
                            </td>

                            {{-- Peminjam --}}
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $trx->user->name ?? 'User Terhapus' }}</div>
                                <div class="text-xs text-gray-500">{{ $trx->user->email ?? '-' }}</div>
                            </td>
                            
                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @php
                                    $bg = match($trx->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'approved' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'borrowed' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'returned' => 'bg-green-100 text-green-800 border-green-200',
                                        'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $bg }}">
                                    {{ ucfirst($trx->status) }}
                                </span>

                                {{-- Info Denda --}}
                                @if($trx->penalty > 0)
                                    <div class="mt-2 text-xs p-2 bg-red-50 rounded border border-red-100">
                                        <div class="text-red-600 font-bold mb-1">Denda: Rp {{ number_format($trx->penalty) }}</div>
                                        @if($trx->penalty_status == 'paid')
                                            <span class="text-green-700 font-bold">✔ Lunas</span>
                                        @else
                                            <span class="text-red-700 font-bold animate-pulse">⚠ Belum Lunas</span>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <div class="mb-1"><i class="fas fa-calendar-alt text-teal-500 mr-1"></i> {{ \Carbon\Carbon::parse($trx->start_time)->format('d M Y') }}</div>
                                <div><i class="fas fa-clock text-red-400 mr-1"></i> s/d {{ \Carbon\Carbon::parse($trx->end_time)->format('d M Y') }}</div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    {{-- Lihat Tiket --}}
                                    @if(in_array($trx->status, ['approved', 'borrowed']))
                                        <a href="{{ route('reservations.ticket', $trx->id) }}" class="p-2 bg-white text-navy-700 rounded border border-gray-200 hover:bg-gray-50" title="Lihat Tiket" target="_blank">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                    @endif

                                    @role('Superadmin|Laboran')
                                        {{-- Pending Actions --}}
                                        @if($trx->status == 'pending')
                                            <form action="{{ route('reservations.update', $trx->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button onclick="return confirm('Setujui?')" class="p-2 bg-blue-50 text-blue-600 rounded hover:bg-blue-100"><i class="fas fa-check"></i></button>
                                            </form>
                                            <form action="{{ route('reservations.update', $trx->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button onclick="return confirm('Tolak?')" class="p-2 bg-red-50 text-red-600 rounded hover:bg-red-100"><i class="fas fa-times"></i></button>
                                            </form>
                                        
                                        {{-- Return Action --}}
                                        @elseif($trx->status == 'borrowed')
                                            <form action="{{ route('reservations.update', $trx->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="returned">
                                                <button onclick="return confirm('Barang kembali?')" class="p-2 bg-green-50 text-green-600 rounded hover:bg-green-100" title="Kembalikan Barang"><i class="fas fa-box-open"></i></button>
                                            </form>
                                        @endif

                                        {{-- Pay Penalty --}}
                                        @if($trx->penalty > 0 && $trx->penalty_status == 'unpaid')
                                            <form action="{{ route('admin.reservations.pay', $trx->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button onclick="return confirm('Bayar denda?')" class="p-2 bg-yellow-50 text-yellow-600 rounded hover:bg-yellow-100 animate-bounce"><i class="fas fa-money-bill-wave"></i></button>
                                            </form>
                                        @endif
                                    @endrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-search text-3xl mb-2 text-gray-300"></i>
                                    <p>Tidak ada data ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $reservations->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>