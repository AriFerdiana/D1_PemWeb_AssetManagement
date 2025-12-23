<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Illuminate\Http\Request;

class AdminLabController extends Controller
{
    // 1. LIHAT DAFTAR LAB
    public function index()
    {
        $labs = Lab::latest()->paginate(10);
        return view('admin.labs.index', compact('labs'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('admin.labs.create');
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:labs,code|max:10',
            'building_name' => 'required|string',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',  // <--- Tambahan
            'longitude' => 'nullable|numeric', // <--- Tambahan
        ]);

        Lab::create($request->all());

        return redirect()->route('admin.labs.index')->with('success', 'Ruangan Lab berhasil ditambahkan!');
    }

    // 4. HAPUS DATA
    public function destroy($id)
    {
        // Cek apakah lab sedang dipakai aset? (Opsional, tapi bagus)
        // Lab::findOrFail($id)->delete();
        
        try {
            Lab::findOrFail($id)->delete();
            return back()->with('success', 'Lab berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus. Mungkin masih ada aset di lab ini.');
        }
    }
}