<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Itenas</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header h3 { margin: 5px 0; font-size: 14px; }
        .header p { margin: 2px 0; font-size: 12px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        
        .footer { margin-top: 40px; text-align: right; width: 100%; }
        .footer-content { float: right; text-align: center; width: 200px; }
        .summary { margin-top: 20px; font-weight: bold; text-align: right; }
        
        /* Utility */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge { padding: 2px 5px; font-size: 10px; border-radius: 3px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <div class="header">
        <h2>INSTITUT TEKNOLOGI NASIONAL</h2>
        <h3>Laporan Peminjaman Inventaris Laboratorium</h3>
        {{-- PERBAIKAN: Menggunakan $monthName agar tidak error Undefined variable --}}
        <p>Periode: {{ $monthName ?? 'Semua Bulan' }} {{ $year ?? date('Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tgl Pinjam</th>
                <th style="width: 20%;">Peminjam</th>
                <th style="width: 30%;">Aset (Qty)</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 15%;">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $key => $res)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center">
                    {{ $res->start_time ? \Carbon\Carbon::parse($res->start_time)->format('d/m/Y') : '-' }}
                </td>
                <td>
                    {{-- Gunakan Null Coalescing (??) agar tidak error jika user dihapus --}}
                    {{ $res->user->name ?? 'User Tidak Dikenal' }}
                    <br>
                    <span style="font-size: 10px; color: #555;">{{ $res->user->email ?? '-' }}</span>
                </td>
                <td>
                    <ul style="margin: 0; padding-left: 15px;">
                        @foreach($res->reservationItems as $item)
                            <li>
                                {{ $item->asset->name ?? 'Item Dihapus' }} 
                                <b>({{ $item->quantity }})</b>
                            </li>
                        @endforeach
                    </ul>
                </td>
                <td class="text-center">
                    {{ strtoupper($res->status) }}
                </td>
                <td class="text-right">
                    Rp {{ number_format($res->penalty, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">
                    Tidak ada data peminjaman pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        {{-- PERBAIKAN: Variable $totalDenda dikirim dari controller --}}
        Total Pendapatan Denda: Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}
    </div>

    <div class="footer">
        <div class="footer-content">
            {{-- PERBAIKAN: Variable $date dikirim dari controller --}}
            <p>Bandung, {{ $date ?? date('d F Y') }}</p>
            <p>Mengetahui,</p>
            <br><br><br>
            <p style="text-decoration: underline; font-weight: bold;">( {{ Auth::user()->name ?? 'Administrator' }} )</p>
            <p>Kepala Laboratorium</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>