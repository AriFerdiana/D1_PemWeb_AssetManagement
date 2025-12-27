<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Maintenance::with(['asset.lab']);

        // 1. FILTER PRODI: Laboran hanya melihat maintenance aset di prodinya sendiri
        if ($user->hasRole('Laboran')) {
            $query->whereHas('asset.lab', function($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });
        }

        // 2. SEARCH: Nama aset atau kode aset
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('asset', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $maintenances = $query->latest()->paginate(10)->withQueryString();
        return view('admin.maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        $user = Auth::user();
        $assets = Asset::query();
        
        // Laboran hanya bisa memilih aset miliknya untuk didaftarkan maintenance
        if ($user->hasRole('Laboran')) {
            $assets->whereHas('lab', fn($q) => $q->where('prodi_id', $user->prodi_id));
        }
        
        $assets = $assets->where('status', '!=', 'maintenance')->get();
        return view('admin.maintenances.create', compact('assets'));
    }

    public function store(Request $request) {
    $request->validate([
        'asset_id' => 'required|exists:assets,id',
        'date' => 'required|date',
        'type' => 'required|string',
        'description' => 'required|string',
        'cost' => 'required|numeric|min:0',
        'funding_source' => 'required|in:prodi_budget,university_budget,student_compensation,penalty_fund', // Sumber Dana
    ]);

    // 1. Simpan Data Maintenance
    Maintenance::create($request->all());

    // 2. OTOMATIS: Ubah status aset menjadi 'maintenance' agar tidak bisa dipinjam
    $asset = Asset::find($request->asset_id);
    $asset->update(['status' => 'maintenance']);

    return redirect()->route('admin.maintenances.index')->with('success', 'Log Perawatan dicatat & Status Aset diperbarui!');
}

    // --- PROTEKSI TAMBAHAN PADA EDIT, UPDATE, DESTROY ---
    
    public function edit(Maintenance $maintenance)
    {
        $user = Auth::user();
        $maintenance->load('asset.lab');

        // SECURITY CHECK: Mencegah Laboran edit data maintenance prodi lain via URL
        if ($user->hasRole('Laboran') && $maintenance->asset->lab->prodi_id != $user->prodi_id) {
            abort(403, 'Anda tidak memiliki akses ke data maintenance prodi lain.');
        }
        
        return view('admin.maintenances.edit', compact('maintenance'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $user = Auth::user();
        $maintenance->load('asset.lab');

        // SECURITY CHECK
        if ($user->hasRole('Laboran') && $maintenance->asset->lab->prodi_id != $user->prodi_id) {
            abort(403, 'Tindakan ilegal: Anda tidak diizinkan mengubah data prodi lain.');
        }

        $request->validate([
            'description' => 'required|string|max:1000',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $maintenance->update([
            'description' => $request->description,
            'status' => $request->status,
        ]);

       $maintenance->update($request->all());

    // 3. OTOMATIS: Jika status maintenance 'completed', kembalikan aset ke 'available'
    if ($request->status == 'completed') {
        $maintenance->asset->update(['status' => 'available']);
    }

    return redirect()->route('admin.maintenances.index')->with('success', 'Data diperbarui!');
}

    public function destroy($id) {
        $user = Auth::user();
        $maintenance = Maintenance::with('asset.lab')->findOrFail($id);

        // SECURITY CHECK
        if ($user->hasRole('Laboran') && $maintenance->asset->lab->prodi_id != $user->prodi_id) {
            abort(403, 'Tindakan ilegal: Anda tidak diizinkan menghapus data prodi lain.');
        }

        $maintenance->delete();
        return back()->with('success', 'Log dihapus.');
    }

    public function exportPDF(Request $request)
{
    $user = Auth::user();
    $query = Maintenance::with(['asset.lab']);

    // 1. Filter Silo Data (Hanya prodi sendiri)
    if ($user->hasRole('Laboran')) {
        $query->whereHas('asset.lab', fn($q) => $q->where('prodi_id', $user->prodi_id));
    }

    // 2. Filter Periode Bulan & Tahun (Jika ada)
    if ($request->filled('month')) {
        $query->whereMonth('date', $request->month);
    }
    if ($request->filled('year')) {
        $query->whereYear('date', $request->year);
    }

    $maintenances = $query->latest()->get();
    $totalCost = $maintenances->sum('cost');

    $pdf = Pdf::loadView('admin.maintenances.report-pdf', [
        'maintenances' => $maintenances,
        'totalCost' => $totalCost,
        'user' => $user,
        'month' => $request->month,
        'year' => $request->year
    ])->setPaper('a4', 'landscape');

    return $pdf->download('Laporan-Maintenance-'.now()->format('d-m-Y').'.pdf');
}
}