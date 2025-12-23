<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Reservation;
use App\Models\User;
use App\Models\ReservationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK KARTU (Cards)
        $totalAssets = Asset::count();
        $totalUsers = User::role('Mahasiswa')->count();
        $totalReservations = Reservation::count();
        $activeLoans = Reservation::where('status', 'borrowed')->count();

        // 2. STATISTIK KEUANGAN (Data Baru)
        // Total Pendapatan Denda Bulan Ini (Hanya yang sudah PAID)
        $monthlyRevenue = Reservation::where('payment_status', 'paid')
            ->whereMonth('updated_at', date('m'))
            ->whereYear('updated_at', date('Y'))
            ->sum('penalty');

        // Status Pembayaran Denda (Unpaid vs Paid) untuk Pie Chart
        $penaltyStats = [
            'paid' => Reservation::where('payment_status', 'paid')->count(),
            'unpaid' => Reservation::where('payment_status', 'unpaid')->count(),
        ];

        // Top 5 Aset Paling Banyak Menghasilkan Denda (Sering Telat)
        $lateAssets = ReservationItem::join('assets', 'reservation_items.asset_id', '=', 'assets.id')
            ->join('reservations', 'reservation_items.reservation_id', '=', 'reservations.id')
            ->where('reservations.penalty', '>', 0)
            ->select('assets.name', DB::raw('SUM(reservations.penalty) as total_penalty'))
            ->groupBy('assets.name')
            ->orderByDesc('total_penalty')
            ->limit(5)
            ->get();

        // 3. DATA UNTUK GRAFIK (Chart.js)
        
        // Grafik 1: Status Transaksi (Pie Chart)
        $reservationStats = Reservation::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Grafik 2: Peminjaman per Prodi (Bar Chart)
        $prodiStats = Reservation::join('users', 'reservations.user_id', '=', 'users.id')
            ->join('prodis', 'users.prodi_id', '=', 'prodis.id')
            ->select('prodis.code', DB::raw('count(*) as total'))
            ->groupBy('prodis.code')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'prodis.code')
            ->toArray();

        // Grafik 3: Top 5 Aset Terlaris
        $topAssetsRaw = ReservationItem::select('asset_id', DB::raw('SUM(quantity) as total'))
            ->with('asset')
            ->groupBy('asset_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        $assetLabels = $topAssetsRaw->map(fn($item) => $item->asset->name ?? 'N/A');
        $assetData = $topAssetsRaw->map(fn($item) => $item->total);

        // Grafik 4: Tren Peminjaman per Bulan (Tahun Berjalan)
        $monthlyStats = Reservation::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyData = array_fill(0, 12, 0); 
        foreach ($monthlyStats as $stat) {
            $monthlyData[$stat->month - 1] = $stat->total;
        }

        return view('dashboard', compact(
            'totalAssets', 
            'totalUsers', 
            'totalReservations', 
            'activeLoans',
            'reservationStats',
            'prodiStats',
            'assetLabels',
            'assetData',
            'monthlyData',
            'monthlyRevenue',
            'penaltyStats',
            'lateAssets'
        ));
    }
}