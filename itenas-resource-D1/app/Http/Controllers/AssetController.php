<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Menampilkan daftar aset (Katalog Mahasiswa)
     */
    public function index(Request $request)
    {
        // 1. Ambil Kategori untuk Filter
        $categories = Category::all();

        // 2. Query Aset
        $query = Asset::with(['category', 'lab']);

        // 3. Logika Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        // 4. Logika Filter Kategori
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // 5. Pagination
        $assets = $query->latest()->paginate(12);

        return view('assets.index', compact('assets', 'categories'));
    }

    /**
     * Menampilkan detail aset
     */
    public function show($id)
    {
        $asset = Asset::with(['category', 'lab'])->findOrFail($id);
        return view('assets.show', compact('asset'));
    }
}