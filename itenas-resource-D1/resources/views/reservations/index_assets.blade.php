<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Histori Peminjaman Aset') }}
            </h2>
            
            {{-- Tombol Cetak PDF Bulanan (Hanya Admin/Laboran) --}}
            @role('Superadmin|Laboran')
            <a href="{{ route('admin.reports.pdf') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Cetak PDF Laporan
            </a>
            @endrole
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg shadow-sm border-l-4 border-green-500">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">Kode TRX</th>
                                <th class="px-6 py-3">Peminjam</th>
                                <th class="px-6 py-3">Daftar Barang</th>
                                <th class="px-6 py-3">Waktu Pinjam</th>
                                <th class="px-6 py-3">Status & Denda</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $res)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    {{ $res->transaction_code }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $res->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $res->user->prodi->code ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <ul class="list-disc list-inside text-xs">
                                        @foreach($res->reservationItems as $item)
                                            <li>{{ $item->asset->name }} ({{ $item->quantity }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    <span class="font-semibold">Mulai:</span> {{ \Carbon\Carbon::parse($res->start_time)->format('d M Y H:i') }} <br>
                                    <span class="font-semibold text-red-500">Batas:</span> {{ \Carbon\Carbon::parse($res->end_time)->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Status Utama --}}
                                    <div class="mb-2">
                                        @if($res->status == 'pending') <span class="text-yellow-600 bg-yellow-100 px-2 py-1 rounded text-[10px] font-bold">PENDING</span>
                                        @elseif($res->status == 'approved') <span class="text-green-600 bg-green-100 px-2 py-1 rounded text-[10px] font-bold">APPROVED</span>
                                        @elseif($res->status == 'borrowed') <span class="text-orange-600 bg-orange-100 px-2 py-1 rounded text-[10px] font-bold">BORROWED</span>
                                        @elseif($res->status == 'returned') <span class="text-blue-600 bg-blue-100 px-2 py-1 rounded text-[10px] font-bold">RETURNED</span>
                                        @else <span class="text-red-600 bg-red-100 px-2 py-1 rounded text-[10px] font-bold">REJECTED</span> @endif
                                    </div>

                                    {{-- Info & Form Denda (Hanya Admin) --}}
                                    @role('Superadmin|Laboran')
                                        @if($res->payment_status == 'unpaid' && $res->penalty > 0)
                                            <div class="mt-2 p-2 bg-red-50 dark:bg-red-900/20 border border-red-200 rounded">
                                                <div class="text-red-600 dark:text-red-400 font-bold text-[11px] mb-1">
                                                    ⚠️ Denda: Rp {{ number_format($res->penalty, 0, ',', '.') }}
                                                </div>
                                                
                                                <form action="{{ route('admin.reservations.pay', $res->id) }}" method="POST" class="flex gap-1">
                                                    @csrf 
                                                    @method('PATCH')
                                                    <select name="payment_method" class="text-[10px] py-0 px-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-blue-500 h-6">
                                                        <option value="Cash">Cash</option>
                                                        <option value="Transfer">Transfer</option>
                                                        <option value="QRIS">QRIS</option>
                                                    </select>
                                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-2 py-0.5 rounded text-[10px] font-bold transition shadow-sm h-6">
                                                        LUNAS
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($res->payment_status == 'paid')
                                            <div class="mt-1">
                                                <span class="text-green-600 dark:text-green-400 text-[10px] font-bold">✅ Denda Lunas ({{ $res->payment_method }})</span>
                                            </div>
                                        @endif
                                    @endrole

                                    {{-- Pesan Denda untuk Mahasiswa --}}
                                    @if(Auth::user()->hasRole('Mahasiswa') && $res->payment_status == 'unpaid' && $res->penalty > 0)
                                        <div class="mt-1">
                                            <span class="text-red-500 font-bold text-[10px]">Silakan bayar denda ke Laboran: Rp {{ number_format($res->penalty, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @include('reservations.partials.actions', ['res' => $res])
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 dark:text-gray-400 italic">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-10 h-10 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Belum ada histori peminjaman aset.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $reservations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>