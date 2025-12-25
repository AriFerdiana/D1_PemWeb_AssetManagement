<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Transaksi Lab') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Sirkulasi</h3>
                    <a href="{{ route('admin.reports.pdf') }}" class="bg-red-600 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-red-700 transition">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border">
                        <thead class="bg-gray-50 uppercase text-xs font-bold text-gray-600">
                            <tr>
                                <th class="px-6 py-3 border">Kode TRX</th>
                                <th class="px-6 py-3 border">Peminjam</th>
                                <th class="px-6 py-3 border">Laboratorium</th>
                                <th class="px-6 py-3 border text-center">Status</th>
                                <th class="px-6 py-3 border text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 border font-mono font-bold text-indigo-600">
                                    {{ $item->transaction_code }}
                                </td>
                                <td class="px-6 py-4 border">
                                    <div class="font-bold text-gray-900">{{ $item->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">{{ $item->user->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 border text-gray-700">
                                    {{ $item->lab->name ?? 'Lab Terhapus' }}
                                </td>
                                <td class="px-6 py-4 border text-center">
                                    <span class="px-2 py-1 rounded text-[10px] font-black uppercase shadow-sm
                                        {{ $item->status == 'approved' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $item->status == 'returned' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $item->status == 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $item->status == 'borrowed' ? 'bg-purple-100 text-purple-700' : '' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 border text-center">
                                    {{-- LOGIKA AKSI BERDASARKAN STATUS --}}
                                    @if($item->status == 'pending')
                                        <div class="flex flex-col gap-2">
                                            {{-- Tombol Setuju --}}
                                            <form action="{{ route('reservations.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="w-full bg-blue-600 text-white px-3 py-1 rounded text-[10px] font-bold hover:bg-blue-700 transition shadow-sm">
                                                    SETUJUI
                                                </button>
                                            </form>

                                            {{-- Tombol Tolak --}}
                                            <form action="{{ route('reservations.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="w-full bg-red-600 text-white px-3 py-1 rounded text-[10px] font-bold hover:bg-red-700 transition shadow-sm">
                                                    TOLAK
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($item->penalty > 0 && $item->payment_status == 'unpaid')
                                        {{-- Tombol Bayar Denda --}}
                                        <form action="{{ route('admin.reservations.pay', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-[10px] font-bold hover:bg-green-700 transition shadow-sm">
                                                LUNASI DENDA
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 italic text-[10px]">Tidak ada aksi</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-400 italic bg-gray-50">
                                    Belum ada data transaksi yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $reservations->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>