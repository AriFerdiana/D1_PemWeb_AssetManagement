<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAssetController extends Controller
{
    // Index Admin
    public function index()
    {
        $assets = Asset::with(['category', 'lab'])->latest()->paginate(10);
        return view('admin.assets.index', compact('assets'));
    }

    // Create Form
    public function create()
    {
        $categories = Category::all();
        $labs = Lab::all();
        return view('admin.assets.create', compact('categories', 'labs'));
    }

    // Store Data
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

    // Edit Form
    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        $categories = Category::all();
        $labs = Lab::all();
        return view('admin.assets.edit', compact('asset', 'categories', 'labs'));
    }

    // Update Data
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

    // Delete Data
    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);
        $asset->delete();
        return redirect()->route('admin.assets.index')->with('success', 'Aset dihapus');
    }

    // === FITUR EXPORT ===
    public function export()
    {
        return response()->streamDownload(function () {
            $assets = Asset::all();
            echo "Nama,Kode,Stok\n";
            foreach ($assets as $a) {
                echo "{$a->name},{$a->code},{$a->stock}\n";
            }
        }, 'aset.csv');
    }

    // === FITUR IMPORT ===
    public function import(Request $request)
    {
        return back()->with('success', 'Fitur Import Berhasil (Simulasi)');
    }
}