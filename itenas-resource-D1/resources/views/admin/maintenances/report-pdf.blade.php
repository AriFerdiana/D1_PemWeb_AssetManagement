<!DOCTYPE html>
<html>
<head>
    <title>Laporan Maintenance Aset</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { bg-color: #f2f2f2; }
        .total { font-weight: bold; text-align: right; margin-top: 15px; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PEMELIHARAAN ASET</h2>
        <h3>ITENAS RESOURCE CENTER</h3>
        <p>Periode: {{ $month ?? 'Semua' }} / {{ $year ?? 'Semua' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tgl</th>
                <th>Aset</th>
                <th>Jenis</th>
                <th>Sumber Dana</th>
                <th>Deskripsi</th>
                <th>Biaya (Rp)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($maintenances as $m)
            <tr>
                <td>{{ date('d/m/Y', strtotime($m->date)) }}</td>
                <td>{{ $m->asset->name }}</td>
                <td>{{ $m->type }}</td>
                <td>{{ str_replace('_', ' ', $m->funding_source) }}</td>
                <td>{{ $m->description }}</td>
                <td>{{ number_format($m->cost, 0, ',', '.') }}</td>
                <td>{{ strtoupper($m->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL PENGELUARAN: Rp {{ number_format($totalCost, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p>Bandung, {{ date('d F Y') }}</p>
        <p>Dicetak oleh: {{ $user->name }} (Laboran)</p>
        <br><br><br>
        <p>( ____________________ )</p>
        <p>Kaprodi {{ $user->prodi->name ?? '' }}</p>
    </div>
</body>
</html>