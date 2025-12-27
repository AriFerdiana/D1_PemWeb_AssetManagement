<x-app-layout>
    {{-- JUDUL HALAMAN UNTUK ADMIN --}}
    @section('header', 'Manajemen Transaksi Sirkulasi')

    <div class="space-y-6">
        
        {{-- 1. BAGIAN STATISTIK RINGKAS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Menunggu Persetujuan</p>
                <p class="text-2xl font-black text-orange-600">{{ $reservations->where('status', 'pending')->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Sedang Dipinjam</p>
                <p class="text-2xl font-black text-blue-600">{{ $reservations->where('status', 'borrowed')->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Terlambat Kembali</p>
                <p class="text-2xl font-black text-red-600">{{ $reservations->where('status', 'borrowed')->filter->isOverdue()->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Total Transaksi</p>
                <p class="text-2xl font-black text-gray-800">{{ $reservations->total() }}</p>
            </div>
        </div>

        {{-- 2. BAGIAN FILTER & PENCARIAN --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.reservations.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari Kode TRX atau Nama Mahasiswa..." 
                           class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500">
                </div>

                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-navy-500 cursor-pointer">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved (Siap Scan)</option>
                    <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed (Dipinjam)</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned (Selesai)</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-navy-700 text-white rounded-lg text-sm font-bold hover:bg-navy-800 transition shadow-sm">
                        Cari
                    </button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('admin.reservations.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-200 transition border border-gray-200">
                            Reset
                        </a>
                    @endif
                    {{-- TOMBOL PDF HANYA UNTUK ADMIN --}}
                    <a href="{{ route('admin.reports.pdf') }}" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 transition shadow-sm flex items-center gap-2">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </form>
        </div>

        {{-- 3. TABEL DATA MANAJEMEN --}}
        <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-xs tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Kode TRX</th>
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Barang / Ruangan</th>
                            <th class="px-6 py-4">Jadwal</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reservations as $res)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Kode --}}
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-navy-700 bg-gray-100 px-2 py-1 rounded border">{{ $res->transaction_code }}</span>
                                </td>

                                {{-- Peminjam --}}
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $res->user->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase">{{ $res->user->prodi->name ?? 'Umum' }}</div>
                                </td>

                                {{-- Detail --}}
                                <td class="px-6 py-4">
                                    @if($res->type == 'asset')
                                        @foreach($res->reservationItems as $item)
                                            <div class="text-xs text-gray-700 flex items-center gap-1 mb-1">
                                                <i class="fas fa-box text-gray-400"></i>
                                                {{ $item->asset->name }} (x{{ $item->quantity }})
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-xs text-teal-700 font-bold flex items-center gap-1">
                                            <i class="fas fa-door-open text-teal-400"></i>
                                            {{ $res->lab->name ?? 'Ruangan' }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Jadwal --}}
                                <td class="px-6 py-4 text-[11px] text-gray-600">
                                    <div><i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($res->start_time)->format('d/m/Y H:i') }}</div>
                                    <div><i class="fas fa-clock mr-1"></i> s/d {{ \Carbon\Carbon::parse($res->end_time)->format('d/m/Y H:i') }}</div>
                                </td>
                                
                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase border {{ $res->status_label }}">
                                        {{ $res->status }}
                                    </span>
                                    {{-- Badge Denda --}}
                                    @if($res->penalty > 0)
                                        <div class="mt-1 text-[9px] font-bold text-red-600">
                                            Rp {{ number_format($res->penalty) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-center">
                                    {{-- Menggunakan file actions.blade.php yang sudah Anda miliki --}}
                                    <div class="flex justify-center">
                                        @include('reservations.actions', ['res' => $res])
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-database text-4xl mb-2 text-gray-200"></i>
                                        <p>Tidak ada data transaksi ditemukan.</p>
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