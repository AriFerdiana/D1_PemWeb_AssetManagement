<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest; 
use App\Models\Asset;
use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel; // <--- Import Library Excel
use App\Imports\AssetsImport;        // <--- Import Logic Import Kita

class AdminAssetController extends Controller
{
    // 1. READ (Lihat Daftar Aset utk Admin)
    public function index()
    {
        $assets = Asset::with('lab')->latest()->paginate(10);
        return view('admin.assets.index', compact('assets'));
    }

    // 2. CREATE (Form Tambah Aset)
    public function create()
    {
        $labs = Lab::all(); // Admin butuh daftar lab untuk lokasi aset
        return view('admin.assets.create', compact('labs'));
    }

    // 3. STORE (Proses Simpan ke DB)
    public function store(StoreAssetRequest $request)
    {
        // Validasi sudah otomatis jalan di StoreAssetRequest.
        $data = $request->all();

        // Handle Upload Gambar
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('assets', 'public');
        }

        Asset::create($data);

        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil ditambahkan!');
    }

    // 4. EDIT (Form Edit)
    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        $labs = Lab::all();
        return view('admin.assets.edit', compact('asset', 'labs'));
    }

  // 5. UPDATE (Proses Simpan Perubahan)
public function update(Request $request, $id)
{
    $asset = Asset::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'serial_number' => 'required|unique:assets,serial_number,' . $asset->id,
        'lab_id' => 'required|exists:labs,id',
        'stock' => 'required|integer|min:0',
        'status' => 'required|in:available,borrowed,maintenance,broken', // Tambahkan validasi status
        'image' => 'nullable|image|max:2048',
    ]);

    $data = $request->only(['name', 'serial_number', 'lab_id', 'stock', 'status', 'description']);

    // LOGIKA OTOMATIS: Jika stok 0, status otomatis jadi tidak tersedia (optional)
    if ($data['stock'] == 0 && $data['status'] == 'available') {
        $data['status'] = 'broken'; // atau biarkan tetap available tapi stok habis
    }

    // Handle Ganti Gambar
    if ($request->hasFile('image')) {
        if ($asset->image_path) {
            Storage::disk('public')->delete($asset->image_path);
        }
        $data['image_path'] = $request->file('image')->store('assets', 'public');
    }

    $asset->update($data);

    return redirect()->route('admin.assets.index')->with('success', 'Data aset berhasil diperbarui dengan status: ' . strtoupper($asset->status));
}

    // 6. DELETE (Hapus Aset)
    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);

        // Hapus gambar dari penyimpanan
        if ($asset->image_path) {
            Storage::disk('public')->delete($asset->image_path);
        }

        $asset->delete();

        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil dihapus!');
    }

    // 7. IMPORT EXCEL (Fitur Baru) 📥
    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new AssetsImport, $request->file('file'));
            return back()->with('success', 'Data Aset berhasil diimport dari Excel!');
        } catch (\Exception $e) {
            // Tangkap error jika format excel salah
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}