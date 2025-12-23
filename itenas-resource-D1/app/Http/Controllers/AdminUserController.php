<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    /**
     * Menampilkan daftar user
     */
    public function index(Request $request)
    {
        $query = User::with('prodi');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Form Tambah User
     */
    public function create()
    {
        $prodis = Prodi::all();
        $roles = Role::all();
        return view('admin.users.create', compact('prodis', 'roles'));
    }

    /**
     * Simpan User Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required'],
            // Prodi optional kalau admin
            'prodi_id' => $request->role == 'Mahasiswa' ? 'required' : 'nullable', 
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'prodi_id' => $request->prodi_id,
        ]);

        // Assign Role Spatie
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Form Edit User (INI YANG TADI ERROR KARENA BELUM ADA)
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $prodis = Prodi::all();
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'prodis', 'roles'));
    }

    /**
     * Update User (INI JUGA BARU)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$id],
            'role' => ['required'],
        ]);

        // Update data dasar
        $user->name = $request->name;
        $user->email = $request->email;
        $user->prodi_id = $request->prodi_id;

        // Update password jika diisi (jika kosong biarkan lama)
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Sync Role (Ganti role lama dengan yang baru)
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui');
    }

    /**
     * Hapus User
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}