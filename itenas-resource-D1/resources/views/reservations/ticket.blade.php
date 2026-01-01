<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket: {{ $reservation->transaction_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .ticket-container { box-shadow: none; border: 1px solid #e5e7eb; max-width: 100%; }
        }
        /* Efek Dot Matrix untuk kesan tiket asli */
        .dot-matrix { font-family: 'Courier New', Courier, monospace; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="ticket-container max-w-md w-full bg-white rounded-xl shadow-2xl overflow-hidden relative border border-gray-200">
        
        {{-- WATERMARK STATUS --}}
        <div class="absolute inset-0 pointer-events-none flex items-center justify-center opacity-[0.03]">
            <span class="text-8xl font-black text-gray-900 transform -rotate-45 uppercase">{{ $reservation->status }}</span>
        </div>

        {{-- HEADER TIKET --}}
        <div class="bg-blue-900 p-6 text-white text-center relative z-10 border-b-4 border-yellow-400">
            <h1 class="text-2xl font-black tracking-widest uppercase">E-TICKET</h1>
            <p class="text-[10px] opacity-80 tracking-widest uppercase mt-1">ITENAS RESOURCE CENTER</p>
            
            {{-- LOGO ITENAS DI POJOK KANAN --}}
            <div class="absolute top-4 right-4">
                <img src="https://uat-web.itenas.ac.id/assets/images/logo-7.png" alt="Logo Itenas" class="h-10 bg-white rounded-lg p-1 shadow-sm object-contain">
            </div>
        </div>

        <div class="p-6 relative z-10">
            {{-- QR CODE AREA --}}
            <div class="flex flex-col items-center justify-center mb-6">
                <div class="p-3 bg-white border-4 border-double border-blue-900 rounded-2xl shadow-inner">
                    {!! QrCode::size(160)->margin(1)->color(30, 58, 138)->generate($reservation->transaction_code) !!}
                </div>
                <div class="mt-3 text-center">
                    <p class="text-xs font-black text-blue-900 uppercase tracking-tighter">Tunjukkan ke Laboran</p>
                    <p class="text-[9px] text-gray-400">Scan untuk Check-in / Check-out</p>
                </div>
            </div>

            <div class="space-y-3 border-t border-b border-dashed border-gray-300 py-5">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-[10px] uppercase font-bold">No. Transaksi</span>
                    <span class="font-mono font-black text-gray-800 bg-gray-100 px-2 py-0.5 rounded">{{ $reservation->transaction_code }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-[10px] uppercase font-bold">Peminjam</span>
                    <span class="font-bold text-gray-800 text-sm">{{ $reservation->user->name }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-gray-400 text-[10px] uppercase font-bold">Waktu Penggunaan</span>
                    <div class="text-right">
                        <span class="font-black text-blue-700 block text-xs">
                            {{ \Carbon\Carbon::parse($reservation->start_time)->translatedFormat('d M Y, H:i') }}
                        </span>
                        <span class="text-[10px] text-gray-400 font-bold">
                            s/d {{ \Carbon\Carbon::parse($reservation->end_time)->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>
                </div>

                <div class="flex justify-between items-start pt-2">
                    <span class="text-gray-400 text-[10px] uppercase font-bold">Lokasi Utama</span>
                    <div class="text-right text-xs">
                        <span class="font-bold text-gray-800 block">
                            @if($reservation->type == 'asset')
                                {{ $reservation->reservationItems->first()->asset->lab->name ?? 'Laboratorium' }}
                            @else
                                {{ $reservation->lab->name ?? 'Nama Lab' }}
                            @endif
                        </span>
                        <span class="text-[10px] text-gray-400">
                            @if($reservation->type == 'asset')
                                {{ $reservation->reservationItems->first()->asset->lab->building_name ?? '-' }}
                            @else
                                {{ $reservation->lab->building_name ?? '-' }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- DETAIL ITEM / KEPERLUAN --}}
            <div class="mt-5">
                @if($reservation->type == 'asset')
                    <h3 class="text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">Daftar Barang Dipinjam</h3>
                    <div class="space-y-2">
                        @foreach($reservation->reservationItems as $item)
                            <div class="flex items-center justify-between text-xs bg-gray-50 p-2 rounded border border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                    <span class="font-medium text-gray-700">{{ $item->asset->name }}</span>
                                </div>
                                <span class="font-black text-blue-900">x{{ $item->quantity }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <h3 class="text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Tujuan Penggunaan</h3>
                    <div class="text-xs text-gray-700 bg-blue-50 p-3 rounded-lg border border-blue-100 italic leading-relaxed shadow-inner">
                        "{{ $reservation->purpose }}"
                    </div>
                @endif
            </div>

            {{-- INFO DENDA (Jika ada) --}}
            @if($reservation->penalty > 0)
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-center">
                    <p class="text-[9px] text-red-500 uppercase font-black">Informasi Denda Keterlambatan</p>
                    <p class="text-lg font-black text-red-700">Rp {{ number_format($reservation->penalty) }}</p>
                    <p class="text-[10px] font-bold {{ $reservation->payment_status == 'paid' ? 'text-green-600' : 'text-red-500' }}">
                        STATUS: {{ $reservation->payment_status == 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                    </p>
                </div>
            @endif
        </div>

        {{-- FOOTER TIKET --}}
        <div class="bg-gray-100 px-6 py-3 border-t border-dashed border-gray-300">
            <p class="text-[8px] text-center text-gray-400 italic">Tiket ini diterbitkan secara elektronik oleh Sistem Manajemen IRC. Pastikan membawa KTM asli saat verifikasi.</p>
        </div>

        <div class="bg-white p-4 flex justify-between items-center no-print border-t border-gray-100">
            <a href="{{ $reservation->type == 'asset' ? route('reservations.assets') : route('reservations.rooms') }}" 
               class="text-gray-400 text-xs font-bold hover:text-blue-800 transition flex items-center">
               <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
               KEMBALI
            </a>
            
            <button onclick="window.print()" class="bg-blue-900 text-white px-6 py-2 rounded-full text-xs font-black hover:bg-blue-800 shadow-md transition flex items-center tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                CETAK
            </button>
        </div>
    </div>

</body>
</html>