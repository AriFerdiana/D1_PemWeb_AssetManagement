<x-app-layout>
    @section('header', 'Riwayat Transaksi')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-6">
        
        <div class="w-full md:w-3/4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('reservations.assets') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode TRX / Nama..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-teal-500">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-teal-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu</option>
                    <option value="borrowed">Dipinjam</option>
                    <option value="returned">Selesai</option>
                    <option value="overdue">Terlambat</option>
                </select>
                <button type="submit" class="px-5 py-2 bg-navy-700 text-white rounded-lg text-sm font-bold hover:bg-navy-800 transition">
                    Filter
                </button>
            </form>
        </div>

        @role('Superadmin|Laboran')
        <a href="{{ route('admin.reports.pdf') }}" target="_blank" class="w-full md:w-auto px-5 py-4 bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-lg flex items-center justify-center gap-2 font-bold transition transform hover:-translate-y-1">
            <i class="fas fa-file-pdf text-xl"></i> Export PDF
        </a>
        @endrole
    </div>

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
                    @foreach($reservations as $trx)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-navy-700">{{ $trx->transaction_code }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $trx->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $trx->user->email }}</div>
                            </td>
                            
                            <td class="px-6 py-4">
                                @php
                                    $color = match($trx->status) {
                                        'approved' => 'blue', 'borrowed' => 'purple', 
                                        'returned' => 'green', 'rejected' => 'red', default => 'yellow'
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-700 border border-{{ $color }}-200">
                                    {{ ucfirst($trx->status) }}
                                </span>

                                @if($trx->penalty_amount > 0)
                                    <div class="mt-2 text-xs p-1 bg-red-50 rounded border border-red-100">
                                        <div class="text-red-600 font-bold">Denda: Rp {{ number_format($trx->penalty_amount) }}</div>
                                        @if($trx->penalty_status == 'paid')
                                            <span class="text-green-600 font-bold">✔ Lunas</span>
                                        @else
                                            <span class="text-red-500 font-bold">✘ Belum Lunas</span>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-xs text-gray-600">
                                <div><i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($trx->start_time)->format('d M Y') }}</div>
                                <div class="text-gray-400 text-[10px]">s/d {{ \Carbon\Carbon::parse($trx->end_time)->format('d M Y') }}</div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    
                                    @if(in_array($trx->status, ['approved', 'borrowed']))
                                        <a href="{{ route('reservations.ticket', $trx->id) }}" class="p-2 bg-gray-100 text-navy-700 rounded hover:bg-gray-200 border border-gray-300" title="Tiket QR" target="_blank">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                    @endif

                                    @role('Superadmin|Laboran')
                                        @if($trx->status == 'pending')
                                            <form action="{{ route('reservations.update', $trx->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button class="p-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 border border-blue-300" title="Setujui"><i class="fas fa-check"></i></button>
                                            </form>
                                            <form action="{{ route('reservations.update', $trx->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button class="p-2 bg-red-100 text-red-700 rounded hover:bg-red-200 border border-red-300" title="Tolak"><i class="fas fa-times"></i></button>
                                            </form>
                                        
                                        @elseif($trx->status == 'borrowed')
                                            <form action="{{ route('reservations.update', $trx->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="returned">
                                                <button class="p-2 bg-green-100 text-green-700 rounded hover:bg-green-200 border border-green-300" title="Terima Barang"><i class="fas fa-undo"></i></button>
                                            </form>
                                        @endif

                                        @if($trx->penalty_amount > 0 && $trx->penalty_status == 'unpaid')
                                            <form action="{{ route('admin.reservations.pay', $trx->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="p-2 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 border border-yellow-300 animate-bounce" title="Bayar Denda">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endrole
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $reservations->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>