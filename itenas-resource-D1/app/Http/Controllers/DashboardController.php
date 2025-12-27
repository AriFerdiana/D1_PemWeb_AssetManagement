<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Reservation;
use App\Models\User;
use App\Models\ReservationItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ==========================================
        // 1. STATISTIK KARTU (Cards) - FILTER PRODI
        // ==========================================
        if ($user->hasRole('Superadmin')) {
            // Superadmin melihat data secara global
            $totalAssets = Asset::count();
            $totalUsers = User::count();
            $totalReservations = Reservation::count();
            $activeLoans = Reservation::where('status', 'borrowed')->count();
            $totalMaintenance = Asset::where('status', 'maintenance')->count();
        } else {
            // Laboran: Hanya hitung data yang berkaitan dengan prodi-nya
            $prodiId = $user->prodi_id;

            // Total Aset milik prodi (berdasarkan lab)
            $totalAssets = Asset::whereHas('lab', fn($q) => $q->where('prodi_id', $prodiId))->count();
            
            // Total User yang terdaftar di prodi yang sama
            $totalUsers = User::where('prodi_id', $prodiId)->count();
            
            // Query Dasar Transaksi terkait prodi (baik booking ruangan lab prodi atau pinjam aset prodi)
            $resQuery = Reservation::where(function($q) use ($prodiId) {
                $q->whereHas('lab', fn($l) => $l->where('prodi_id', $prodiId))
                  ->orWhereHas('reservationItems.asset.lab', fn($a) => $a->where('prodi_id', $prodiId));
            });

            $totalReservations = (clone $resQuery)->count();
            $activeLoans = (clone $resQuery)->where('status', 'borrowed')->count();
            
            // Aset sedang diservis milik prodi
            $totalMaintenance = Asset::where('status', 'maintenance')
                ->whereHas('lab', fn($q) => $q->where('prodi_id', $prodiId))->count();
        }

        // ==========================================
        // 2. DATA GRAFIK - FILTER PRODI
        // ==========================================

        // --- GRAFIK 1: Tren Peminjaman per Bulan ---
        $monthlyQuery = Reservation::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', date('Y'));
        
        if (!$user->hasRole('Superadmin')) {
            $monthlyQuery->where(function($q) use ($user) {
                $q->whereHas('lab', fn($l) => $l->where('prodi_id', $user->prodi_id))
                  ->orWhereHas('reservationItems.asset.lab', fn($a) => $a->where('prodi_id', $user->prodi_id));
            });
        }
        $monthlyStats = $monthlyQuery->groupBy('month')->orderBy('month')->pluck('total', 'month')->toArray();
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) { $monthlyData[] = $monthlyStats[$i] ?? 0; }

        // --- GRAFIK 2: Status Transaksi ---
        $statusQuery = Reservation::select('status', DB::raw('count(*) as total'));
        if (!$user->hasRole('Superadmin')) {
            $statusQuery->where(function($q) use ($user) {
                $q->whereHas('lab', fn($l) => $l->where('prodi_id', $user->prodi_id))
                  ->orWhereHas('reservationItems.asset.lab', fn($a) => $a->where('prodi_id', $user->prodi_id));
            });
        }
        $statusRaw = $statusQuery->groupBy('status')->get();
        $statusLabels = $statusRaw->pluck('status');
        $statusData = $statusRaw->pluck('total');

        // --- GRAFIK 3: Kondisi Aset (Available, Borrowed, Maintenance) ---
        $conditionQuery = Asset::select('status', DB::raw('count(*) as total'));
        if (!$user->hasRole('Superadmin')) {
            $conditionQuery->whereHas('lab', fn($q) => $q->where('prodi_id', $user->prodi_id));
        }
        $conditionRaw = $conditionQuery->groupBy('status')->get();
        $conditionLabels = $conditionRaw->pluck('status');
        $conditionData = $conditionRaw->pluck('total');

        // --- GRAFIK 4: Komposisi Kategori ---
        $catQuery = Asset::join('categories', 'assets.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as total'));
        if (!$user->hasRole('Superadmin')) {
            $catQuery->whereHas('lab', fn($q) => $q->where('prodi_id', $user->prodi_id));
        }
        $categoryStats = $catQuery->groupBy('categories.name')->orderByDesc('total')->limit(5)->get();
        $categoryLabels = $categoryStats->pluck('name');
        $categoryData = $categoryStats->pluck('total');

        // --- GRAFIK 5: Kepatuhan Pengembalian (Tepat Waktu vs Terlambat) ---
        $compQuery = Reservation::query();
        if (!$user->hasRole('Superadmin')) {
            $compQuery->where(function($q) use ($user) {
                $q->whereHas('lab', fn($l) => $l->where('prodi_id', $user->prodi_id))
                  ->orWhereHas('reservationItems.asset.lab', fn($a) => $a->where('prodi_id', $user->prodi_id));
            });
        }
        $lateCount = (clone $compQuery)->where('penalty', '>', 0)->count();
        $onTimeCount = (clone $compQuery)->where('status', 'returned')->where('penalty', 0)->count();
        $complianceLabels = ['Tepat Waktu', 'Terlambat'];
        $complianceData = [$onTimeCount, $lateCount];

        // --- GRAFIK 6: Peminjaman per Prodi (Hanya Muncul di Superadmin) ---
        $prodiLabels = []; $prodiData = [];
        if ($user->hasRole('Superadmin')) {
            $prodiStats = Reservation::join('users', 'reservations.user_id', '=', 'users.id')
                ->join('prodis', 'users.prodi_id', '=', 'prodis.id')
                ->select('prodis.code', DB::raw('count(*) as total'))
                ->groupBy('prodis.code')->orderByDesc('total')->limit(5)->get();
            $prodiLabels = $prodiStats->pluck('code');
            $prodiData = $prodiStats->pluck('total');
        }

        // --- GRAFIK 7: Top 5 Aset Terlaris ---
        $topQuery = ReservationItem::select('asset_id', DB::raw('count(*) as total'))->with('asset');
        if (!$user->hasRole('Superadmin')) {
            $topQuery->whereHas('asset.lab', fn($q) => $q->where('prodi_id', $user->prodi_id));
        }
        $topAssets = $topQuery->groupBy('asset_id')->orderByDesc('total')->limit(5)->get();
        $topAssetLabels = $topAssets->map(fn($i) => $i->asset->name ?? 'Unknown');
        $topAssetData = $topAssets->pluck('total');

        return view('dashboard', compact(
            'totalAssets', 'totalUsers', 'totalReservations', 'activeLoans', 'totalMaintenance',
            'monthlyData', 'statusLabels', 'statusData', 'conditionLabels', 'conditionData',
            'categoryLabels', 'categoryData', 'complianceLabels', 'complianceData',
            'prodiLabels', 'prodiData', 'topAssetLabels', 'topAssetData'
        ));
    }
}