<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminAssetController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();
    
    // 1. AMBIL DATA KATEGORI
    $categories = Category::all(); 

    // 2. QUERY DASAR ASET
    $query = Asset::with(['category', 'lab']);

    // 3. FILTER PRODI: Laboran hanya melihat aset milik prodinya
    if ($user->hasRole('Laboran')) {
        $query->whereHas('lab', function($q) use ($user) {
            $q->where('prodi_id', $user->prodi_id);
        });
    }

    // 4. FILTER PENCARIAN (Search)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");
        });
    }

    // 5. FILTER KATEGORI (Dropdown)
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // --- [BAGIAN INI YANG DIUBAH/DITAMBAHKAN] ---
    
    // 6. AMBIL DATA DENGAN PAGINATION DINAMIS
    // Tangkap input 'per_page' dari view, jika kosong set default 10
    $perPage = $request->input('per_page', 10);

    // Gunakan variabel $perPage di dalam paginate()
    $assets = $query->latest()
                    ->paginate($perPage) 
                    ->withQueryString(); // Agar filter tidak hilang saat ganti halaman

    // ---------------------------------------------

    // 7. KIRIM VARIABEL KE VIEW
    return view('admin.assets.index', compact('assets', 'categories'));
}

    public function create()
    {
        $user = Auth::user();
        $categories = Category::all();
        
        // Filter Lab pilihan saat tambah aset (Laboran hanya bisa tambah ke Lab prodinya)
        $labs = Lab::query();
        if ($user->hasRole('Laboran')) {
            $labs->where('prodi_id', $user->prodi_id);
        }
        $labs = $labs->get();

        return view('admin.assets.create', compact('categories', 'labs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:assets',
            'category_id' => 'required',
            'lab_id' => 'required',
            'stock' => 'required|integer',
            'image' => 'nullable|image'
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('assets', 'public');
        }
        $data['status'] = 'available';

        Asset::create($data);
        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $asset = Asset::findOrFail($id);
        
        // Keamanan: Laboran tidak boleh edit aset prodi lain via URL
        if ($user->hasRole('Laboran') && $asset->lab->prodi_id != $user->prodi_id) {
            abort(403, 'Bukan wewenang prodi Anda.');
        }

        $categories = Category::all();
        $labs = Lab::query();
        if ($user->hasRole('Laboran')) {
            $labs->where('prodi_id', $user->prodi_id);
        }
        $labs = $labs->get();

        return view('admin.assets.edit', compact('asset', 'categories', 'labs'));
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            if($asset->image) Storage::disk('public')->delete($asset->image);
            $data['image'] = $request->file('image')->store('assets', 'public');
        }

        $asset->update($data);
        return redirect()->route('admin.assets.index')->with('success', 'Aset berhasil diupdate');
    }

    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);
        $asset->delete();
        return redirect()->route('admin.assets.index')->with('success', 'Aset dihapus');
    }
}