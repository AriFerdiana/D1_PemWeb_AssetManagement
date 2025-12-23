<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Histori Booking Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg">{{ session('success') }}</div>
                @endif

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Kode TRX</th>
                                <th class="px-6 py-3">Peminjam</th>
                                <th class="px-6 py-3">Ruangan</th>
                                <th class="px-6 py-3">Keperluan</th>
                                <th class="px-6 py-3">Jadwal Pemakaian</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $res)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold">{{ $res->transaction_code }}</td>
                                <td class="px-6 py-4">{{ $res->user->name }}<br><span class="text-xs text-gray-400">{{ $res->user->prodi->code ?? '-' }}</span></td>
                                <td class="px-6 py-4">
                                    <div class="text-indigo-600 font-bold">🏢 {{ $res->lab->name ?? 'Error' }}</div>
                                    <div class="text-xs">{{ $res->lab->building_name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ Str::limit($res->purpose, 30) }}
                                    @if($res->proposal_file)
                                        <a href="{{ asset('storage/' . $res->proposal_file) }}" target="_blank" class="block text-xs text-blue-500 underline mt-1">Lihat Proposal</a>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($res->start_time)->format('d M, H:i') }} - <br>
                                    {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($res->status == 'pending') <span class="text-yellow-600 bg-yellow-100 px-2 py-1 rounded text-xs">Menunggu</span>
                                    @elseif($res->status == 'approved') <span class="text-green-600 bg-green-100 px-2 py-1 rounded text-xs">Disetujui</span>
                                    @elseif($res->status == 'returned') <span class="text-blue-600 bg-blue-100 px-2 py-1 rounded text-xs">Selesai</span>
                                    @else <span class="text-red-600 bg-red-100 px-2 py-1 rounded text-xs">Ditolak</span> @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Memanggil file partials/actions.blade.php yang sudah kamu buat sebelumnya --}}
                                    @include('reservations.partials.actions', ['res' => $res])
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-4">Belum ada booking ruangan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $reservations->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>