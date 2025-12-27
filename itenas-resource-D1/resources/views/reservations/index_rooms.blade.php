<x-app-layout>
    {{-- JUDUL SIMPEL --}}
    @section('header', 'Riwayat Peminjaman Ruangan')

    <div class="space-y-6">
        
        {{-- FILTER & SEARCH --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari kode booking..." 
                           class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500">
                </div>
                
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500 cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Sedang Digunakan</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>

                <button type="submit" class="px-6 py-2 bg-navy-700 text-white rounded-lg text-sm font-bold hover:bg-navy-800 transition">
                    Filter
                </button>
            </form>
        </div>

        {{-- TABEL DATA --}}
        <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-xs tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Kode Booking</th>
                            <th class="px-6 py-4">Ruangan</th>
                            <th class="px-6 py-4">Jadwal & Keperluan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Tiket</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reservations as $item)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Kode --}}
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-indigo-700">{{ $item->transaction_code }}</span>
                                </td>

                                {{-- Ruangan --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-door-open text-gray-400"></i>
                                        <span class="font-bold text-gray-800">{{ $item->lab->name ?? 'Ruangan Terhapus' }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 pl-6">{{ $item->lab->building_name ?? '-' }}</div>
                                </td>

                                {{-- Jadwal --}}
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-gray-700 mb-1">
                                        {{ \Carbon\Carbon::parse($item->start_time)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mb-2">
                                        {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }} WIB
                                    </div>
                                    <div class="inline-block bg-gray-100 text-gray-600 text-[10px] px-2 py-0.5 rounded italic truncate max-w-[200px]">
                                        "{{ $item->purpose }}"
                                    </div>
                                </td>

                                {{-- Status (Warna Disesuaikan dengan Alur Scan) --}}
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $bg = match($item->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'approved' => 'bg-green-100 text-green-800 border-green-200',
                                            'borrowed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'returned' => 'bg-gray-100 text-gray-600 border-gray-200',
                                            'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        $label = match($item->status) {
                                            'approved' => 'Siap Digunakan',
                                            'borrowed' => 'Sedang Dipakai',
                                            'returned' => 'Selesai',
                                            default => ucfirst($item->status)
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border {{ $bg }}">
                                        {{ $label }}
                                    </span>
                                </td>

                                {{-- Tiket --}}
                                <td class="px-6 py-4 text-center">
                                    @if(in_array($item->status, ['approved', 'borrowed', 'returned']))
                                        <a href="{{ route('reservations.ticket', $item->id) }}" class="text-blue-600 hover:text-blue-800 text-xl transition transform hover:scale-110 inline-block" title="Download Tiket" target="_blank">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-300 text-xl"><i class="fas fa-ban"></i></span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-door-closed text-4xl mb-2 text-gray-200"></i>
                                        <p>Belum ada riwayat booking ruangan.</p>
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