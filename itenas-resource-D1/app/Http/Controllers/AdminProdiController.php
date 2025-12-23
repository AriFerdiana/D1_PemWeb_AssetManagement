<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class AdminProdiController extends Controller
{
    // 1. LIHAT DAFTAR PRODI
    public function index()
    {
        $prodis = Prodi::latest()->paginate(10);
        return view('admin.prodis.index', compact('prodis'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('admin.prodis.create');
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:prodis,code|max:10',
            'kaprodi_name' => 'nullable|string|max:255', // Kolom tambahan tadi
        ]);

        Prodi::create($request->all());

        return redirect()->route('admin.prodis.index')->with('success', 'Program Studi berhasil ditambahkan!');
    }

    // 4. HAPUS DATA
    public function destroy($id)
    {
        try {
            Prodi::findOrFail($id)->delete();
            return back()->with('success', 'Prodi berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus. Masih ada user di Prodi ini.');
        }
    }
}