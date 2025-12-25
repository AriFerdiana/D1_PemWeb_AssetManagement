<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLabController extends Controller
{
    /**
     * Menampilkan daftar Lab
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Logika Role: Superadmin lihat semua, Laboran lihat prodi sendiri
        if ($user->hasRole('Superadmin')) {
            $query = Lab::with('prodi');
        } else {
            // Laboran hanya bisa lihat lab di prodinya
            $query = Lab::with('prodi')->where('prodi_id', $user->prodi_id);
        }

        // 2. Search Logic
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('building_name', 'like', '%' . $request->search . '%');
        }

        $labs = $query->latest()->paginate(10);
        return view('admin.labs.index', compact('labs'));
    }

    /**
     * Form Tambah Lab
     */
    public function create()
    {
        // Ambil data prodi untuk dropdown
        $prodis = Prodi::all();
        return view('admin.labs.create', compact('prodis'));
    }

    /**
     * Simpan Lab Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'prodi_id'      => 'required|exists:prodis,id',
            'building_name' => 'required|string|max:255',
            'room_number'   => 'required|string|max:20',
            'capacity'      => 'required|integer|min:1',
            'description'   => 'nullable|string',
            // TAMBAHAN: Validasi Koordinat Peta
            'latitude'      => 'nullable|string',
            'longitude'     => 'nullable|string',
        ]);

        Lab::create($request->all());

        return redirect()->route('admin.labs.index')->with('success', 'Laboratorium berhasil ditambahkan!');
    }

    /**
     * Menampilkan Detail Lab (Termasuk Peta)
     * Method ini diperlukan untuk tombol "Lihat" (icon mata)
     */
    public function show(Lab $lab)
    {
        // Validasi akses (Opsional: Laboran hanya boleh lihat lab prodinya)
        $user = Auth::user();
        if (!$user->hasRole('Superadmin') && $lab->prodi_id != $user->prodi_id) {
            abort(403, 'Anda tidak memiliki akses ke lab ini.');
        }

        return view('admin.labs.show', compact('lab'));
    }

    /**
     * Form Edit Lab
     */
    public function edit(Lab $lab)
    {
        // Validasi: Laboran tidak boleh edit lab prodi lain
        $user = Auth::user();
        if (!$user->hasRole('Superadmin') && $lab->prodi_id != $user->prodi_id) {
            abort(403, 'Anda tidak memiliki akses ke lab ini.');
        }

        $prodis = Prodi::all();
        return view('admin.labs.edit', compact('lab', 'prodis'));
    }

    /**
     * Update Lab
     */
    public function update(Request $request, Lab $lab)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'prodi_id'      => 'required|exists:prodis,id',
            'building_name' => 'required|string|max:255',
            'room_number'   => 'required|string|max:20',
            'capacity'      => 'required|integer|min:1',
            'description'   => 'nullable|string',
            // TAMBAHAN: Validasi Koordinat Peta
            'latitude'      => 'nullable|string',
            'longitude'     => 'nullable|string',
        ]);

        $lab->update($request->all());

        return redirect()->route('admin.labs.index')->with('success', 'Data laboratorium berhasil diperbarui!');
    }

    /**
     * Hapus Lab
     */
    public function destroy(Lab $lab)
    {
        // Cek apakah ada aset di dalamnya? (Opsional, untuk keamanan data)
        if ($lab->assets()->count() > 0) {
            return back()->with('error', 'Gagal hapus! Masih ada aset di dalam lab ini.');
        }

        $lab->delete();
        return redirect()->route('admin.labs.index')->with('success', 'Laboratorium berhasil dihapus.');
    }
}