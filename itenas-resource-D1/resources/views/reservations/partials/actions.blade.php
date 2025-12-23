<div class="flex items-center space-x-2">
    {{-- ============================== --}}
    {{-- LOGIC UNTUK ADMIN (Laboran/Superadmin) --}}
    {{-- ============================== --}}
    @role('Laboran|Superadmin|Dosen')
        
        {{-- Jika Status PENDING: Muncul Tombol Terima & Tolak --}}
        @if($res->status == 'pending')
            <div class="flex flex-col space-y-1 w-full">
                {{-- Tombol Terima --}}
                <form action="{{ route('reservations.update', $res->id) }}" method="POST" onsubmit="return confirm('Yakin setujui peminjaman ini?');">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="w-full px-3 py-1 text-xs font-bold text-white bg-green-600 rounded hover:bg-green-700 transition shadow-sm">
                        ✔ Terima
                    </button>
                </form>

                {{-- Tombol Tolak --}}
                <form action="{{ route('reservations.update', $res->id) }}" method="POST" onsubmit="return confirm('Yakin tolak peminjaman ini?');">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="w-full px-3 py-1 text-xs font-bold text-white bg-red-600 rounded hover:bg-red-700 transition shadow-sm">
                        ✖ Tolak
                    </button>
                </form>
            </div>

        {{-- Jika Status APPROVED: Muncul Tombol Selesai/Kembali --}}
        @elseif($res->status == 'approved')
            <form action="{{ route('reservations.update', $res->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian/selesai?');">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="returned">
                <button type="submit" class="px-3 py-1 text-xs font-bold text-white bg-blue-600 rounded hover:bg-blue-700 transition shadow-sm">
                    🔄 Selesai / Kembali
                </button>
            </form>

        {{-- Jika Status LAINNYA --}}
        @else
            <span class="text-gray-400 text-xs italic">Selesai</span>
        @endif

    {{-- ============================== --}}
    {{-- LOGIC UNTUK MAHASISWA (User Biasa) --}}
    {{-- ============================== --}}
    @else
        
        @if($res->status == 'pending')
            <span class="text-xs text-gray-500 italic">Menunggu persetujuan...</span>
        
        @elseif($res->status == 'approved')
            {{-- Tombol Download Tiket --}}
            <a href="{{ route('reservations.ticket', $res->id) }}" target="_blank" class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded hover:bg-blue-200 border border-blue-300 transition shadow-sm">
                🎟️ E-Ticket
            </a>
        
        @elseif($res->status == 'rejected')
            <div class="flex flex-col">
                <span class="text-xs text-red-500 font-bold">Ditolak</span>
                @if($res->rejection_note)
                    <span class="text-[10px] text-red-400" title="{{ $res->rejection_note }}">
                        "{{ Str::limit($res->rejection_note, 15) }}"
                    </span>
                @endif
            </div>
        
        @else
            <span class="text-xs text-gray-400">Riwayat Selesai</span>
        @endif

    @endrole
</div>