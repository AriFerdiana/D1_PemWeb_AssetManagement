<!DOCTYPE html>
<html>
<head>
    <title>Peminjaman Baru</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #2563EB;">🔔 Pengajuan Peminjaman Baru</h2>
        <p>Halo Admin/Laboran,</p>
        <p>Ada mahasiswa yang baru saja mengajukan peminjaman alat. Berikut detailnya:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Peminjam:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $reservation->user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Prodi:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $reservation->user->prodi->name ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Keperluan:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $reservation->purpose }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Tanggal Ambil:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $reservation->start_time }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px;">Silakan login ke dashboard untuk melakukan persetujuan.</p>
        <a href="{{ route('login') }}" style="background-color: #2563EB; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Buka Dashboard</a>
    </div>
</body>
</html>