<x-app-layout>
    {{-- JUDUL HANYA "RIWAYAT" (Mencakup Status & Histori) --}}
    @section('header', 'Riwayat Peminjaman Aset')

    <div class="space-y-6">
        
        {{-- BAGIAN FILTER & PENCARIAN (Satu Baris) --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" class="flex flex-col md:flex-row gap-3">
                {{-- Input Cari --}}
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari kode transaksi atau nama barang..." 
                           class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500">
                </div>

                {{-- Filter Status (Opsional, biar user bisa filter sendiri) --}}
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500 cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Sudah Kembali</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>

                <button type="submit" class="px-6 py-2 bg-navy-700 text-white rounded-lg text-sm font-bold hover:bg-navy-800 transition">
                    Filter
                </button>
            </form>
        </div>

        {{-- TABEL DATA (DIGABUNG: Status Berjalan & Histori Selesai) --}}
        <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-xs tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Kode TRX</th>
                            <th class="px-6 py-4">Detail Barang</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Tiket</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reservations as $trx)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Kode --}}
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-navy-700">{{ $trx->transaction_code }}</span>
                                </td>

                                {{-- Barang --}}
                                <td class="px-6 py-4">
                                    @foreach($trx->reservationItems as $item)
                                        <div class="flex items-center gap-2 mb-1">
                                            <i class="fas fa-box text-gray-400"></i>
                                            <span class="font-medium text-gray-700">{{ $item->asset->name ?? 'Item dihapus' }}</span>
                                            <span class="text-xs text-gray-500 bg-gray-100 px-1.5 rounded">x{{ $item->quantity }}</span>
                                        </div>
                                    @endforeach
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-6 py-4 text-xs text-gray-600">
                                    <div class="font-bold">{{ \Carbon\Carbon::parse($trx->start_time)->format('d M Y') }}</div>
                                    <div class="text-gray-400">{{ \Carbon\Carbon::parse($trx->start_time)->format('H:i') }} WIB</div>
                                </td>
                                
                                {{-- Status (Logika Warna & Label Diperbarui) --}}
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClass = match($trx->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'approved' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'borrowed' => 'bg-purple-100 text-purple-800 border-purple-200', 
                                            'returned' => 'bg-green-100 text-green-800 border-green-200', 
                                            'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        $statusLabel = match($trx->status) {
                                            'borrowed' => 'Sedang Dipinjam',
                                            'returned' => 'Selesai/Kembali',
                                            'approved' => 'Disetujui',
                                            default => ucfirst($trx->status)
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>

                                    {{-- Info Denda --}}
                                    @if($trx->penalty > 0)
                                        <div class="mt-2 text-[10px] text-red-600 font-bold p-1 bg-red-50 rounded border border-red-100">
                                            Denda: Rp {{ number_format($trx->penalty) }}
                                            <span class="block text-[9px] {{ $trx->payment_status == 'paid' ? 'text-green-600' : 'text-red-500' }}">
                                                ({{ $trx->payment_status == 'paid' ? 'Lunas' : 'Belum Bayar' }})
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                {{-- Tombol Tiket (Izinkan status returned melihat tiket kembali) --}}
                                <td class="px-6 py-4 text-center">
                                    @if(in_array($trx->status, ['approved', 'borrowed', 'returned']))
                                        <a href="{{ route('reservations.ticket', $trx->id) }}" class="text-blue-600 hover:text-blue-800 text-xl transition transform hover:scale-110 inline-block" title="Lihat Tiket QR" target="_blank">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-300 text-xl" title="Tidak Tersedia"><i class="fas fa-ban"></i></span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-4xl mb-2 text-gray-200"></i>
                                        <p>Belum ada riwayat peminjaman.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $reservations->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>