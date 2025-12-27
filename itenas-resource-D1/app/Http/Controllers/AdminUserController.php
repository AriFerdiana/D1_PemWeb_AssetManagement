<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // <--- TAMBAHKAN BARIS INI
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    /**
     * Menampilkan daftar user (Difilter per Prodi untuk Laboran)
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Sekarang ini tidak akan error lagi
        $query = User::with(['prodi', 'roles']);

        // LOGIKA SILO DATA: Laboran hanya melihat user di prodinya sendiri
        if ($user->hasRole('Laboran')) {
            $query->where('prodi_id', $user->prodi_id)
                  ->whereDoesntHave('roles', fn($q) => $q->where('name', 'Superadmin'));
        }

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        
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
  public function edit($id)
{
    $currentUser = Auth::user();
    $targetUser = User::findOrFail($id);

    // LOGIKA PROTEKSI UNTUK LABORAN
    if ($currentUser->hasRole('Laboran')) {
        
        // 1. Izinkan jika dia mengedit dirinya sendiri
        if ($currentUser->id == $targetUser->id) {
            // Lanjut ke view
        } else {
            // 2. Jika mengedit orang lain, pastikan prodi_id sama
            // Gunakan (int) untuk memastikan tipe data integer agar perbandingan !== tidak gagal
            if ((int)$targetUser->prodi_id !== (int)$currentUser->prodi_id) {
                abort(403, 'ANDA HANYA BISA MENGELOLA PENGGUNA DARI PRODI ANDA SENDIRI.');
            }

            // 3. Jangan izinkan Laboran mengedit Superadmin
            if ($targetUser->hasRole('Superadmin')) {
                abort(403, 'LABORAN TIDAK MEMILIKI WEWENANG MENGUBAH DATA SUPERADMIN.');
            }
        }
    }

    $prodis = Prodi::all();
    $roles = Role::all();
    
    return view('admin.users.edit', compact('targetUser', 'prodis', 'roles'));
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