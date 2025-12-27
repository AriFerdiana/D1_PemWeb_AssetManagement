<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Imports\AssetsImport;
use Maatwebsite\Excel\Facades\Excel;

class AssetController extends Controller
{
    /**
     * Menampilkan daftar aset.
     * Admin/Laboran -> Lihat Tabel (Manajemen)
     * Mahasiswa -> Lihat Katalog (Peminjaman)
     */
    public function index(Request $request)
{
    $user = Auth::user();
    $categories = Category::all(); 

    // 1. Query Dasar
    $query = Asset::with(['category', 'lab']);

    // 2. LOGIKA FILTER PRODI UNTUK ADMIN/LABORAN (TAMBAHKAN INI)
    if ($user->hasRole(['Superadmin', 'Laboran'])) {
        if ($user->hasRole('Laboran')) {
            $query->whereHas('lab', function($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });
        }
    } else {
        // Mahasiswa hanya melihat aset yang statusnya 'available'
        $query->where('status', 'available'); 
    }

    // 3. Filter Search
    $query->when($request->filled('search'), function ($q) use ($request) {
        $search = $request->search;
        $q->where(function ($subQ) use ($search) {
            $subQ->where('name', 'like', '%' . $search . '%')
                 ->orWhere('code', 'like', '%' . $search . '%');
        });
    });

    // 4. Filter Kategori
    $query->when($request->filled('category_id'), function ($q) use ($request) {
        $q->where('category_id', $request->category_id);
    });

    $assets = $query->latest()->paginate(10)->withQueryString();

    // 5. LOGIKA PEMISAH TAMPILAN
    if ($user->hasRole(['Superadmin', 'Laboran'])) {
        return view('admin.assets.index', compact('assets', 'categories'));
    } else {
        return view('assets.catalog', compact('assets', 'categories'));
    }
}

    public function create()
    {
        $categories = Category::all();
        $labs = Lab::all();
        return view('admin.assets.create', compact('categories', 'labs'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|unique:assets,code',
            'category_id' => 'required|exists:categories,id',
            'lab_id'      => 'required|exists:labs,id',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'prodi'       => 'required|string'
        ]);

        $data = $request->all();
        
        // Default Status
        $data['status'] = $data['status'] ?? 'available';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('assets', 'public'); 
            $data['image'] = $path;
        }

        Asset::create($data);

        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil ditambah');
    }

    public function show(Asset $asset)
    {
        $asset->load(['category', 'lab']);
        return view('admin.assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $categories = Category::all();
        $labs = Lab::all();
        return view('admin.assets.edit', compact('asset', 'categories', 'labs'));
    }

    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|unique:assets,code,' . $asset->id,
            'category_id' => 'required|exists:categories,id',
            'lab_id'      => 'required|exists:labs,id',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'prodi'       => 'required|string'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($asset->image && Storage::disk('public')->exists($asset->image)) {
                Storage::disk('public')->delete($asset->image);
            }
            $path = $request->file('image')->store('assets', 'public');
            $data['image'] = $path;
        }

        $asset->update($data);

        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil diperbarui');
    }

    public function destroy(Asset $asset)
    {
        try {
            if ($asset->image && Storage::disk('public')->exists($asset->image)) {
                Storage::disk('public')->delete($asset->image);
            }

            $asset->delete();

            return redirect()->route('admin.assets.index')
                             ->with('success', 'Aset berhasil dihapus permanen.');

        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new AssetsImport, $request->file('file'));
            return back()->with('success', 'Data aset berhasil diimpor!');
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Gagal import pada baris ke-' . $failures[0]->row() . ': ' . implode(', ', $failures[0]->errors());
            return back()->with('error', $errorMsg);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan import: ' . $e->getMessage());
        }
    }
}