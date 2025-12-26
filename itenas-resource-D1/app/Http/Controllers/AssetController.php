<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    // Wajib untuk cek user login
use Illuminate\Support\Facades\Storage; // Wajib untuk hapus gambar
use App\Http\Requests\StoreAssetRequest; // Request Validasi Tambah
use App\Http\Requests\UpdateAssetRequest; // Request Validasi Edit
use Exception; // Untuk Error Handling (Try-Catch)

// --- TAMBAHAN BARU UNTUK EXCEL ---
use App\Imports\AssetsImport;
use Maatwebsite\Excel\Facades\Excel;
// ---------------------------------

class AssetController extends Controller
{
    /**
     * Menampilkan daftar aset (Halaman Admin)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 1. Ambil Data Kategori (PENTING: Agar dropdown filter view tidak error)
        $categories = Category::all(); 

        // 2. Query Dasar berdasarkan Role
        if ($user->hasRole('Superadmin')) {
            // Superadmin melihat semua data
            $query = Asset::query();
        } else {
            // Laboran hanya melihat data sesuai Probinya
            $query = Asset::where('prodi', $user->prodi);
        }

        // 3. Eager Loading (Optimasi Query)
        $query->with(['category', 'lab']);

        // 4. Logika Search
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($subQ) use ($search) {
                $subQ->where('name', 'like', '%' . $search . '%')
                     ->orWhere('code', 'like', '%' . $search . '%');
            });
        });

        // 5. Logika Filter Kategori
        $query->when($request->filled('category_id'), function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });

        // 6. Pagination
        $assets = $query->latest()->paginate(10)->withQueryString();

        // 7. Return View
        return view('admin.assets.index', compact('assets', 'categories'));
    }

    /**
     * Menampilkan form tambah barang
     */
    public function create()
    {
        $categories = Category::all();
        $labs = Lab::all();
        
        return view('admin.assets.create', compact('categories', 'labs'));
    }

    /**
     * Menyimpan data baru ke Database
     */
    public function store(Request $request) 
{
    $request->validate([
        'name' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file
        // ... validasi lain ...
    ]);

    $data = $request->all();

    // LOGIKA UPLOAD FILE YANG BENAR
    if ($request->hasFile('image')) {
        // Upload ke folder 'storage/app/public/assets'
        // Hasilnya path seperti: "assets/namafileunik.jpg"
        $path = $request->file('image')->store('assets', 'public'); 
        $data['image'] = $path;
    }

    Asset::create($data);

    return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambah');
}

    /**
     * Menampilkan detail aset
     */
    public function show(Asset $asset)
    {
        $asset->load(['category', 'lab']);
        return view('admin.assets.show', compact('asset'));
    }

    /**
     * Menampilkan form edit barang
     */
    public function edit(Asset $asset)
    {
        $categories = Category::all();
        $labs = Lab::all();

        return view('admin.assets.edit', compact('asset', 'categories', 'labs'));
    }

    /**
     * Mengupdate data barang
     */
    public function update(UpdateAssetRequest $request, Asset $asset)
{
    $data = $request->validated();

    // --- BAGIAN YANG HARUS DIPERBAIKI ---
    if ($request->hasFile('image')) {
        // 1. Cek apakah ada gambar lama, jika ada HAPUS
        if ($asset->image && Storage::disk('public')->exists($asset->image)) {
            Storage::disk('public')->delete($asset->image);
        }

        // 2. Upload gambar baru
        $path = $request->file('image')->store('assets', 'public');
        $data['image'] = $path;
    }
    // ------------------------------------

    $asset->update($data);

    return redirect()->route('assets.index')->with('success', 'Aset berhasil diperbarui');
}

    /**
     * Menghapus barang
     */
    public function destroy(Asset $asset)
    {
        try {
            // Hapus File Gambar Fisik
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

    /**
     * --- FITUR BARU ---
     * Import Data dari Excel
     */

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
        return back()->with('error', 'Gagal import! Cek format data baris ke-' . $failures[0]->row());
    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
}