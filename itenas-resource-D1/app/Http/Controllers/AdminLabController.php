<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Prodi; // Pastikan Model Prodi di-import
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminLabController extends Controller
{
    /**
     * Menampilkan daftar Lab dengan Search & Filter
     */
    public function index(Request $request)
    {
        // 1. Mulai Query dengan Eager Loading (Relasi Prodi)
        // Asumsi nama relasi di Model Lab adalah 'prodi'
        $query = Lab::with('prodi');

        // 2. LOGIKA SEARCH (Nama Lab atau Lokasi/Gedung)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // 3. LOGIKA FILTER (Berdasarkan Prodi)
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        // 4. Ambil data terbaru + pagination
        $labs = $query->latest()->paginate(10)->withQueryString();

        // 5. Ambil data Prodi untuk isi Dropdown Filter
        $prodis = Prodi::orderBy('name', 'asc')->get();

        return view('admin.labs.index', compact('labs', 'prodis'));
    }

    // --- FUNCTION LAIN (Create, Store, Edit, Update, Destroy) BIARKAN TETAP ---
    
    public function create()
    {
        // Kita butuh data prodi juga di form create
        $prodis = Prodi::all();
        return view('admin.labs.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodis,id',
            'location' => 'required|string',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        Lab::create($request->all());

        return redirect()->route('admin.labs.index')->with('success', 'Lab berhasil ditambahkan!');
    }

    public function show(Lab $lab)
    {
        return view('admin.labs.show', compact('lab'));
    }

    public function edit(Lab $lab)
    {
        $prodis = Prodi::all();
        return view('admin.labs.edit', compact('lab', 'prodis'));
    }

    public function update(Request $request, Lab $lab)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodis,id',
            'location' => 'required|string',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $lab->update($request->all());

        return redirect()->route('admin.labs.index')->with('success', 'Lab berhasil diperbarui!');
    }

    public function destroy(Lab $lab)
    {
        $lab->delete();
        return redirect()->route('admin.labs.index')->with('success', 'Lab berhasil dihapus!');
    }
}