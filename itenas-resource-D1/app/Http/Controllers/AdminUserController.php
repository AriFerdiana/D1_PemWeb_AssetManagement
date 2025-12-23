<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    // 1. LIHAT DAFTAR USER
    public function index() {
        // Ambil user selain Superadmin agar aman
        $users = User::with('prodi')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // 2. FORM TAMBAH USER
    public function create() {
        $prodis = Prodi::all();
        return view('admin.users.create', compact('prodis'));
    }

    // 3. PROSES SIMPAN USER
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required', // Role: Mahasiswa, Dosen, Laboran
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'prodi_id' => $request->prodi_id, // Bisa null kalau Admin
        ]);

        // Assign Role via Spatie
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat!');
    }

    // 4. HAPUS USER
    public function destroy($id) {
        $user = User::findOrFail($id);
        
        // Cegah hapus diri sendiri
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}