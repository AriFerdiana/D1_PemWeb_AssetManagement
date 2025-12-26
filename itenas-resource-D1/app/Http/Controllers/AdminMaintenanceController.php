<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Asset;
use Illuminate\Http\Request;

class AdminMaintenanceController extends Controller
{
    public function index(Request $request)
{
    // 1. Siapkan Query
    $query = \App\Models\Maintenance::with('asset'); // Eager load relasi asset

    // 2. Logika Search (Berdasarkan Nama Aset atau Keterangan)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
              ->orWhereHas('asset', function($subQ) use ($search) {
                  $subQ->where('name', 'like', "%{$search}%");
              });
        });
    }

    // 3. Logika Filter (Berdasarkan Status)
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 4. Ambil Data (Pagination)
    $maintenances = $query->latest()->paginate(10)->withQueryString();

    return view('admin.maintenances.index', compact('maintenances'));
}

    public function create() {
        $assets = Asset::all();
        return view('admin.maintenances.create', compact('assets'));
    }

    public function store(Request $request) {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'date' => 'required|date',
            'type' => 'required|string',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
        ]);
        Maintenance::create($request->all());
        return redirect()->route('admin.maintenances.index')->with('success', 'Log Perawatan dicatat!');
    }

    public function destroy($id) {
        Maintenance::findOrFail($id)->delete();
        return back()->with('success', 'Log dihapus.');
    }

    // --- 1. MENAMPILKAN HALAMAN EDIT ---
    public function edit(\App\Models\Maintenance $maintenance)
    {
        // Muat data maintenance beserta info aset-nya
        $maintenance->load('asset');
        
        return view('admin.maintenances.edit', compact('maintenance'));
    }

    // --- 2. PROSES SIMPAN PERUBAHAN (UPDATE) ---
    public function update(Request $request, \App\Models\Maintenance $maintenance)
    {
        // Validasi input
        $request->validate([
            'description' => 'required|string|max:1000',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        // Update data
        $maintenance->update([
            'description' => $request->description,
            'status' => $request->status,
        ]);

        // Redirect kembali ke index dengan pesan sukses
        return redirect()->route('admin.maintenances.index')
            ->with('success', 'Data maintenance berhasil diperbarui!');
    }
}