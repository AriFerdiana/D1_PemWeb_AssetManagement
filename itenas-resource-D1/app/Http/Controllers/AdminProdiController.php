<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreProdiRequest;
use App\Http\Requests\UpdateProdiRequest;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProdiController extends Controller
{
    /**
     * Menampilkan daftar Prodi dengan Search & Filter
     */
    public function index(Request $request)
    {
        $query = Prodi::query();

        // 1. LOGIKA SEARCH (Nama Prodi atau Kode)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // 2. LOGIKA FILTER (Fakultas)
        // Pastikan nama kolom di DB Anda 'faculty' atau 'fakultas'
        if ($request->filled('faculty')) {
            $query->where('faculty', $request->faculty);
        }

        // 3. Sorting & Pagination
        // Kita urutkan berdasarkan Fakultas, lalu Nama
        $prodis = $query->orderBy('faculty', 'asc')
                        ->orderBy('name', 'asc')
                        ->paginate(10)
                        ->withQueryString();

        return view('admin.prodis.index', compact('prodis'));
    }

    // --- FUNCTION LAIN (Create, Store, Edit, Update, Destroy) BIARKAN TETAP ---
    
    public function create()
    {
        return view('admin.prodis.create');
    }

    public function store(StoreProdiRequest $request)
    {
        // Validasi sudah otomatis jalan di StoreProdiRequest
        // Jika gagal, otomatis kembali ke form dengan error
        
        Prodi::create($request->validated()); // Pakai validated() biar aman

        return redirect()->route('admin.prodis.index')->with('success', 'Prodi berhasil ditambahkan!');
    }

    public function edit(Prodi $prodi)
    {
        return view('admin.prodis.edit', compact('prodi'));
    }

    public function update(UpdateProdiRequest $request, Prodi $prodi)
    {
        $prodi->update($request->validated());

        return redirect()->route('admin.prodis.index')->with('success', 'Prodi berhasil diperbarui!');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();
        return redirect()->route('admin.prodis.index')->with('success', 'Prodi berhasil dihapus!');
    }
}