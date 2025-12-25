<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class AdminProdiController extends Controller
{
    public function index() {
        $prodis = Prodi::latest()->paginate(10);
        return view('admin.prodis.index', compact('prodis'));
    }

    public function create() {
        return view('admin.prodis.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:prodis,code',
            'faculty' => 'required'
        ]);
        Prodi::create($request->all());
        return redirect()->route('admin.prodis.index')->with('success', 'Prodi berhasil dibuat');
    }

    public function edit(Prodi $prodi) {
        return view('admin.prodis.edit', compact('prodi'));
    }

    public function update(Request $request, Prodi $prodi) {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:prodis,code,'.$prodi->id,
            'faculty' => 'required'
        ]);
        $prodi->update($request->all());
        return redirect()->route('admin.prodis.index')->with('success', 'Prodi berhasil diupdate');
    }

    public function destroy(Prodi $prodi) {
        $prodi->delete();
        return redirect()->route('admin.prodis.index')->with('success', 'Prodi dihapus');
    }
}