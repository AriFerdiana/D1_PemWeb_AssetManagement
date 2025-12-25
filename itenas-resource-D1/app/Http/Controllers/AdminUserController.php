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
        $query = User::with(['prodi', 'roles']); // Eager load roles juga

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
            'prodi_id' => ['nullable', 'exists:prodis,id'], 
        ]);

        // Logika Prodi: Jika Superadmin, set null. Selain itu ambil dari input.
        $prodiId = ($request->role === 'Superadmin') ? null : $request->prodi_id;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'prodi_id' => $prodiId,
        ]);

        // Assign Role Spatie
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Form Edit User
     * Menggunakan Route Model Binding (User $user)
     */
    public function edit(User $user)
    {
        // Tidak perlu findOrFail lagi karena Laravel otomatis mencarikan berdasarkan ID di URL
        $prodis = Prodi::all();
        $roles = Role::all();
        
        return view('admin.users.edit', compact('user', 'prodis', 'roles'));
    }

    /**
     * Update User
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Ignore email milik user ini sendiri saat cek unique
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'role' => ['required'],
            'prodi_id' => ['nullable', 'exists:prodis,id'],
        ]);

        // 1. Update Data Dasar
        $user->name = $request->name;
        $user->email = $request->email;

        // 2. Logika Prodi (PENTING)
        // Jika role yang dipilih adalah Superadmin, hapus prodinya (karena superadmin lintas prodi)
        // Jika Laboran/Mahasiswa, simpan prodi yang dipilih
        if ($request->role === 'Superadmin') {
            $user->prodi_id = null;
        } else {
            $user->prodi_id = $request->prodi_id;
        }

        // 3. Update Password (Hanya jika diisi)
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // 4. Sync Role (Ganti role lama dengan yang baru)
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui');
    }

    /**
     * Hapus User
     */
    public function destroy(User $user)
    {
        // Jangan biarkan user menghapus dirinya sendiri
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}