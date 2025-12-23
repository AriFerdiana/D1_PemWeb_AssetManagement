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
            body { background: white; }
            .ticket-container { box-shadow: none; border: 1px solid #000; }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="ticket-container max-w-md w-full bg-white rounded-xl shadow-2xl overflow-hidden relative">
        <div class="absolute inset-0 pointer-events-none flex items-center justify-center opacity-10">
            <span class="text-9xl font-black text-gray-500 transform -rotate-45 uppercase">{{ $reservation->status }}</span>
        </div>

        <div class="bg-blue-800 p-6 text-white text-center relative z-10">
            <h1 class="text-2xl font-bold tracking-widest uppercase">E-TICKET</h1>
            <p class="text-xs opacity-75">ITENAS RESOURCE CENTER</p>
            <div class="absolute top-4 right-4">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/Logo_Itenas.png/1200px-Logo_Itenas.png" alt="Logo" class="h-8 bg-white rounded-full p-1">
            </div>
        </div>

        <div class="p-6 relative z-10">
            <div class="flex flex-col items-center justify-center mb-6">
                <div class="p-2 bg-white border-2 border-dashed border-gray-300 rounded-lg">
                    {!! QrCode::size(150)->color(30, 58, 138)->generate($reservation->transaction_code) !!}
                </div>
                <p class="text-xs text-gray-500 mt-2">Scan QR ini pada Laboran</p>
            </div>

            <div class="space-y-3 border-t border-b border-gray-200 py-4">
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Kode TRX</span>
                    <span class="font-mono font-bold text-gray-800">{{ $reservation->transaction_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Peminjam</span>
                    <span class="font-bold text-gray-800 text-right">{{ $reservation->user->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-sm">Jadwal</span>
                    <div class="text-right">
                        <span class="font-bold text-blue-600 block">
                            {{ \Carbon\Carbon::parse($reservation->start_time)->format('d M Y, H:i') }}
                        </span>
                        <span class="text-xs text-gray-400">
                            s/d {{ \Carbon\Carbon::parse($reservation->end_time)->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>

                <div class="flex justify-between items-start mt-2">
                    <span class="text-gray-500 text-sm">Lokasi</span>
                    <span class="font-bold text-gray-800 text-right">
                        @if($reservation->type == 'asset')
                            {{ $reservation->reservationItems->first()->asset->lab->name ?? 'Lokasi Aset' }}
                            <br><span class="text-xs text-gray-400 font-normal">
                                {{ $reservation->reservationItems->first()->asset->lab->building_name ?? '-' }}
                            </span>
                        @elseif($reservation->type == 'room')
                            {{ $reservation->lab->name ?? 'Nama Lab' }}
                            <br><span class="text-xs text-gray-400 font-normal">
                                {{ $reservation->lab->building_name ?? '-' }}
                            </span>
                        @endif
                    </span>
                </div>
            </div>

            <div class="mt-4">
                @if($reservation->type == 'asset')
                    <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Item Dipinjam</h3>
                    <ul class="text-sm text-gray-700 space-y-1">
                        @foreach($reservation->reservationItems as $item)
                            <li class="flex items-center justify-between border-b border-gray-100 pb-1">
                                <div class="flex items-center">
                                    <span class="w-4 h-4 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs mr-2">✓</span>
                                    {{ $item->asset->name }}
                                </div>
                                <span class="font-bold text-gray-600">x{{ $item->quantity }}</span>
                            </li>
                        @endforeach
                    </ul>
                @elseif($reservation->type == 'room')
                    <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Keperluan Acara</h3>
                    <p class="text-sm text-gray-800 bg-gray-50 p-3 rounded border border-gray-100 italic">
                        "{{ $reservation->purpose }}"
                    </p>
                @endif
            </div>
        </div>

        <div class="bg-gray-50 p-4 flex justify-between items-center no-print relative z-10">
            @if($reservation->type == 'asset')
                <a href="{{ route('reservations.assets') }}" class="text-gray-500 text-sm hover:underline">← Kembali</a>
            @else
                <a href="{{ route('reservations.rooms') }}" class="text-gray-500 text-sm hover:underline">← Kembali</a>
            @endif
            
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 shadow-lg transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Tiket
            </button>
        </div>
    </div>

</body>
</html>