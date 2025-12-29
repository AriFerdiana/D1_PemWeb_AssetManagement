<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Exception;
use App\Imports\LabsImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminLabController extends Controller
{
    /**
     * Menampilkan daftar Lab.
     * Superadmin -> Semua Lab.
     * Laboran -> Lab di prodinya saja.
     */
    public function index(Request $request)
{
    $user = Auth::user();
    $query = Lab::with('prodi');

    // 1. FILTER BERDASARKAN ROLE (Silo Data)
    if ($user->hasRole('Laboran')) {
        $query->where('prodi_id', $user->prodi_id);
    }

    // 2. LOGIKA SEARCH
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('building_name', 'like', "%{$search}%");
        });
    }

    // 3. FITUR SHOW ENTRIES (10, 20, 50)
    // Ambil input 'per_page', defaultnya 10
    $perPage = $request->input('per_page', 10);

    // 4. AMBIL DATA & TAMPILKAN VIEW
    // Gunakan variabel $perPage di dalam paginate()
    $labs = $query->latest()->paginate($perPage)->withQueryString();
    
    $prodis = Prodi::orderBy('name', 'asc')->get();

    return view('admin.labs.index', compact('labs', 'prodis'));
}

    public function create()
    {
        $user = Auth::user();
        
        // Laboran hanya bisa membuat lab untuk prodinya sendiri
        if ($user->hasRole('Laboran')) {
            $prodis = Prodi::where('id', $user->prodi_id)->get();
        } else {
            $prodis = Prodi::all();
        }

        return view('admin.labs.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'prodi_id'      => 'required|exists:prodis,id',
            'building_name' => 'required|string',
            'capacity'      => 'required|integer',
            'description'   => 'nullable|string',
            'latitude'      => 'nullable|string', // Pastikan input ini ada di form
            'longitude'     => 'nullable|string', // Pastikan input ini ada di form
        ]);

        // PROTEKSI: Laboran tidak boleh mendaftarkan lab ke prodi lain
        if ($user->hasRole('Laboran') && $request->prodi_id != $user->prodi_id) {
            return back()->with('error', 'Anda hanya bisa menambah Lab untuk prodi Anda sendiri.');
        }

        Lab::create($request->all());

        return redirect()->route('admin.labs.index')->with('success', 'Lab berhasil ditambahkan!');
    }

    public function show(Lab $lab)
    {
        $user = Auth::user();
        // Cek akses jika Laboran
        if ($user->hasRole('Laboran') && $lab->prodi_id != $user->prodi_id) {
            abort(403, 'Anda tidak memiliki akses ke data Lab ini.');
        }

        return view('admin.labs.show', compact('lab'));
    }

    public function edit(Lab $lab)
    {
        $user = Auth::user();
        // Cek akses jika Laboran
        if ($user->hasRole('Laboran') && $lab->prodi_id != $user->prodi_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit Lab ini.');
        }

        if ($user->hasRole('Laboran')) {
            $prodis = Prodi::where('id', $user->prodi_id)->get();
        } else {
            $prodis = Prodi::all();
        }

        return view('admin.labs.edit', compact('lab', 'prodis'));
    }

    public function update(Request $request, Lab $lab)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'prodi_id'      => 'required|exists:prodis,id',
            'building_name' => 'required|string',
            'capacity'      => 'required|integer',
            'description'   => 'nullable|string',
            'latitude'      => 'nullable|string',
            'longitude'     => 'nullable|string',
        ]);

        // PROTEKSI UPDATE
        if ($user->hasRole('Laboran') && ($lab->prodi_id != $user->prodi_id || $request->prodi_id != $user->prodi_id)) {
            abort(403);
        }

        $lab->update($request->all());

        return redirect()->route('admin.labs.index')->with('success', 'Lab berhasil diperbarui!');
    }

    public function destroy(Lab $lab)
    {
        $user = Auth::user();
        // PROTEKSI HAPUS
        if ($user->hasRole('Laboran') && $lab->prodi_id != $user->prodi_id) {
            abort(403);
        }

        $lab->delete();
        return redirect()->route('admin.labs.index')->with('success', 'Lab berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new LabsImport, $request->file('file'));
            return back()->with('success', 'Data Laboratorium berhasil diimpor!');
        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan import: ' . $e->getMessage());
        }
    }
}