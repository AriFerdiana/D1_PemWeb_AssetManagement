<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Itenas</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; }
        .summary { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>INSTITUT TEKNOLOGI NASIONAL</h2>
        <h3>Laporan Peminjaman Inventaris Laboratorium</h3>
        <p>Periode: {{ $month }} / {{ $year }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Pinjam</th>
                <th>Nama Mahasiswa</th>
                <th>Aset</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $key => $res)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($res->start_time)->format('d/m/Y') }}</td>
                <td>{{ $res->user->name }}</td>
                <td>
                    @foreach($res->reservationItems as $item)
                        {{ $item->asset->name }} ({{ $item->quantity }}),
                    @endforeach
                </td>
                <td>{{ strtoupper($res->status) }}</td>
                <td>Rp {{ number_format($res->penalty, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        Total Pendapatan Denda (Lunas): Rp {{ number_format($totalDenda, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p>Bandung, {{ $date }}</p>
        <br><br><br>
        <p>( ____________________ )</p>
        <p>Kepala Laboran</p>
    </div>
</body>
</html>