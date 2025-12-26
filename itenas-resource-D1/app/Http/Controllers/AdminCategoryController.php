<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminCategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori dengan Search & Filter
     */
    public function index(Request $request)
    {
        // 1. Ambil Data Kategori + Hitung Jumlah Aset (assets_count)
        // Pastikan di Model Category ada relasi public function assets() { ... }
        $query = Category::withCount('assets');

        // 2. LOGIKA SEARCH (Cari Nama Kategori)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 3. LOGIKA FILTER / SORTING
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'most_assets':
                    $query->orderBy('assets_count', 'desc');
                    break;
                case 'least_assets':
                    $query->orderBy('assets_count', 'asc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            // Default: Urutkan dari yang terbaru dibuat
            $query->latest();
        }

        // 4. Pagination
        $categories = $query->paginate(10)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    // --- FUNCTION LAIN (Store, Update, Destroy) BIARKAN TETAP SEPERTI SEMULA ---
    
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        // Opsional: Cek jika kategori masih punya aset sebelum hapus
        if ($category->assets()->count() > 0) {
            return back()->with('error', 'Gagal hapus! Kategori ini masih memiliki aset.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}