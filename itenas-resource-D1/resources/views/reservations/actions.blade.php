<div class="flex items-center justify-center gap-2">
    {{-- ========================================== --}}
    {{-- 1. LOGIC UNTUK ADMIN (Laboran/Superadmin)  --}}
    {{-- ========================================== --}}
    @if(auth()->user()->hasRole(['Laboran', 'Superadmin']))
        
        {{-- CASE: STATUS PENDING (Tombol Terima & Tolak) --}}
        @if($res->status == 'pending')
            <div class="flex gap-1.5">

                <form action="{{ route('admin.reservations.update', $res->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" 
                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-100 border border-green-200 transition" 
                            title="Setujui Peminjaman">
                        <i class="fas fa-check text-sm"></i>
                    </button>
                </form>

                {{-- Tombol Tolak (Memanggil SweetAlert2 Kustom di app.blade.php) --}}
                <button type="button" 
                        onclick="rejectReservation({{ $res->id }})" 
                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition" 
                        title="Tolak & Beri Alasan">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

        {{-- CASE: STATUS APPROVED (Tombol Menuju Scan QR) --}}
        @elseif($res->status == 'approved')
            <a href="{{ route('admin.scan.index') }}" 
               class="px-3 py-1.5 bg-navy-50 text-navy-700 text-[10px] font-black rounded-lg border border-navy-200 hover:bg-navy-100 transition flex items-center gap-1.5">
                <i class="fas fa-qrcode"></i> SCAN CHECK-IN
            </a>

        {{-- CASE: STATUS BORROWED (Tombol Menuju Scan Kembali) --}}
        @elseif($res->status == 'borrowed')
            <a href="{{ route('admin.scan.index') }}" 
               class="px-3 py-1.5 bg-purple-50 text-purple-700 text-[10px] font-black rounded-lg border border-purple-200 hover:bg-purple-100 transition flex items-center gap-1.5">
                <i class="fas fa-undo-alt"></i> SCAN KEMBALI
            </a>

        {{-- CASE: PEMBAYARAN DENDA (Jika terlambat dan belum bayar) --}}
        @if($res->penalty > 0 && $res->penalty_status == 'unpaid')
            <form action="{{ route('admin.reservations.pay', $res->id) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" 
                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-100 border border-orange-200 transition" 
                        title="Bayar Denda Rp {{ number_format($res->penalty) }}">
                    <i class="fas fa-money-bill-wave"></i>
                </button>
            </form>
        @endif

        @else
            <span class="text-gray-400 text-[10px] italic bg-gray-50 px-2 py-1 rounded border">Selesai</span>
        @endif

    {{-- ========================================== --}}
    {{-- 2. LOGIC UNTUK MAHASISWA                  --}}
    {{-- ========================================== --}}
    @else
        @if(in_array($res->status, ['approved', 'borrowed', 'returned']))
            <a href="{{ route('admin.reservations.ticket', $res->id) }}" target="_blank" 
               class="px-3 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-black rounded-lg border border-blue-200 hover:bg-blue-100 flex items-center gap-1.5">
                <i class="fas fa-ticket-alt"></i> TIKET QR
            </a>
        @else
            <span class="text-gray-400 text-[10px] italic">-</span>
        @endif
    @endif
</div>