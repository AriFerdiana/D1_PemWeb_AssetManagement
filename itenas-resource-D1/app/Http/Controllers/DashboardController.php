<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Reservation;
use App\Models\User;
use App\Models\ReservationItem;
use App\Models\Category; // Pastikan Model Category di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // ==========================
        // 1. STATISTIK KARTU (Cards)
        // ==========================
        $totalAssets = Asset::count();
        $totalUsers = User::count();
        $totalReservations = Reservation::count();
        $activeLoans = Reservation::where('status', 'borrowed')->count();
        $totalMaintenance = Asset::where('status', 'maintenance')->count();

        // ==========================
        // 2. DATA UNTUK 7 GRAFIK
        // ==========================

        // --- GRAFIK 1: Tren Peminjaman per Bulan (Line Chart) ---
        $monthlyStats = Reservation::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $monthlyStats[$i] ?? 0;
        }

        // --- GRAFIK 2: Status Transaksi (Doughnut Chart) ---
        $statusRaw = Reservation::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        $statusLabels = $statusRaw->pluck('status');
        $statusData = $statusRaw->pluck('total');

        // --- GRAFIK 3: Kondisi Aset (Pie Chart) ---
        $conditionRaw = Asset::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        $conditionLabels = $conditionRaw->pluck('status');
        $conditionData = $conditionRaw->pluck('total');

        // --- GRAFIK 4: Komposisi Kategori (Pie Chart) - NEW! ---
        $categoryStats = Asset::join('categories', 'assets.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->limit(5) // Top 5 Kategori
            ->get();
        $categoryLabels = $categoryStats->pluck('name');
        $categoryData = $categoryStats->pluck('total');

        // --- GRAFIK 5: Kepatuhan Pengembalian (Doughnut Chart) - NEW! ---
        // Hitung yang kena denda vs yang lunas/tepat waktu
        $lateCount = Reservation::where('penalty', '>', 0)->count();
        $onTimeCount = Reservation::where('status', 'returned')->where('penalty', 0)->count();
        
        $complianceLabels = ['Tepat Waktu', 'Terlambat'];
        $complianceData = [$onTimeCount, $lateCount];

        // --- GRAFIK 6: Peminjaman per Prodi (Bar Chart) ---
        $prodiStats = Reservation::join('users', 'reservations.user_id', '=', 'users.id')
            ->join('prodis', 'users.prodi_id', '=', 'prodis.id')
            ->select('prodis.code', DB::raw('count(*) as total'))
            ->groupBy('prodis.code')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $prodiLabels = $prodiStats->pluck('code');
        $prodiData = $prodiStats->pluck('total');

        // --- GRAFIK 7: Top 5 Aset Terlaris (Horizontal Bar Chart) ---
        $topAssets = ReservationItem::select('asset_id', DB::raw('count(*) as total'))
            ->with('asset')
            ->groupBy('asset_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $topAssetLabels = $topAssets->map(fn($i) => $i->asset->name ?? 'Unknown');
        $topAssetData = $topAssets->pluck('total');

        return view('dashboard', compact(
            'totalAssets', 'totalUsers', 'totalReservations', 'activeLoans', 'totalMaintenance',
            'monthlyData', 
            'statusLabels', 'statusData',
            'conditionLabels', 'conditionData',
            'categoryLabels', 'categoryData', // Data Kategori
            'complianceLabels', 'complianceData', // Data Kepatuhan
            'prodiLabels', 'prodiData',
            'topAssetLabels', 'topAssetData'
        ));
    }
}