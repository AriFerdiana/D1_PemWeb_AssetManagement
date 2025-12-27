<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProdiRequest;
use App\Http\Requests\UpdateProdiRequest;

class AdminProdiController extends Controller
{
    /**
     * Menampilkan daftar Prodi dengan Search & Filter
     */
    public function index(Request $request)
    {
        $query = Prodi::query();

        // 1. LOGIKA SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // 2. LOGIKA FILTER (Fakultas)
        if ($request->filled('faculty')) {
            $query->where('faculty', $request->faculty);
        }

        // 3. Sorting & Pagination
        $prodis = $query->orderBy('faculty', 'asc')
                        ->orderBy('name', 'asc')
                        ->paginate(10)
                        ->withQueryString();

        return view('admin.prodis.index', compact('prodis'));
    }

    public function create()
    {
        return view('admin.prodis.create');
    }

    public function store(StoreProdiRequest $request)
    {
        Prodi::create($request->validated());
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
        if ($prodi->users()->count() > 0 || $prodi->labs()->count() > 0) {
            return back()->with('error', 'Gagal: Prodi ini masih memiliki data User atau Laboratorium.');
        }

        $prodi->delete();
        return redirect()->route('admin.prodis.index')->with('success', 'Prodi berhasil dihapus!');
    }
}