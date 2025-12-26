<?php

use App\Http\Controllers\Auth\SocialiteController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdminReservationController; 
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// ROUTE DASHBOARD (Kita pasang log.aktivitas disini)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'log.aktivitas']) // <--- TAMBAHAN LOG
    ->name('dashboard');

// Route Social Login
Route::get('auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);

// ==========================================
// GROUP 1: ROUTE MAHASISWA / UMUM
// ==========================================
// Kita tambahkan 'log.aktivitas' agar kegiatan mahasiswa tercatat
Route::middleware(['auth', 'log.aktivitas'])->group(function () {
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Aset (Katalog Mahasiswa)
    Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');

    // Transaksi & Reservasi
    Route::get('/reservations/export', [ReservationController::class, 'export'])->name('reservations.export'); 
    Route::get('/reservations/create/{asset}', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    
    Route::get('/reservations/assets', [ReservationController::class, 'indexAssets'])->name('reservations.assets');
    Route::get('/reservations/rooms', [ReservationController::class, 'indexRooms'])->name('reservations.rooms');
    
    Route::get('/reservations/{id}/ticket', [ReservationController::class, 'downloadTicket'])->name('reservations.ticket');
    Route::patch('/reservations/{id}', [ReservationController::class, 'updateStatus'])->name('reservations.update');

    // PINJAM RUANGAN
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{lab}/book', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms/book', [RoomController::class, 'store'])->name('rooms.store');
}); 

// ==========================================
// GROUP 2: ROUTE KHUSUS ADMIN (SUPERADMIN/LABORAN)
// ==========================================

// A. Route Export/Import & Custom Admin
Route::middleware(['auth', 'role:Superadmin|Laboran', 'log.aktivitas'])->prefix('admin')->group(function () {
    
    // --- FITUR IMPORT YANG DIMINTA ---
    Route::post('assets/import', [AssetController::class, 'import'])->name('assets.import');
});

// B. Route Resource Admin
// Kita tambahkan 'log.aktivitas' disini juga untuk memantau Admin
Route::middleware(['auth', 'role:Superadmin|Laboran', 'log.aktivitas'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. CRUD Assets
    Route::resource('assets', AssetController::class);
    
    // ========================================================
    // 🔥 PERBAIKAN: MENAMBAHKAN RUTE DATA TRANSAKSI ADMIN 🔥
    // ========================================================
    Route::get('/transactions', [AdminReservationController::class, 'index'])->name('reservations.index');
    // ========================================================

    // --- FITUR SCANNER QR CODE, DENDA & LAPORAN ---
    Route::get('/scan', [AdminReservationController::class, 'scanIndex'])->name('scan.index');
    Route::post('/scan/process', [AdminReservationController::class, 'scanProcess'])->name('scan.process');
    Route::patch('/reservations/{id}/pay', [AdminReservationController::class, 'payPenalty'])->name('reservations.pay');
    
    // CETAK LAPORAN PDF
    Route::get('/reports/peminjaman-pdf', [AdminReservationController::class, 'exportPDF'])->name('reports.pdf');

    // CRUD Master Data Lainnya
    
    // 2. CRUD Users (Pengguna)
    Route::resource('users', \App\Http\Controllers\AdminUserController::class); 

    // 3. CRUD Labs (Laboratorium)
    Route::resource('labs', \App\Http\Controllers\AdminLabController::class);

    // 4. CRUD Prodis (Program Studi)
    Route::resource('prodis', \App\Http\Controllers\AdminProdiController::class);

    // 5. CRUD Categories (Kategori)
    Route::resource('categories', \App\Http\Controllers\AdminCategoryController::class);

    // Log Maintenance
    Route::resource('maintenances', \App\Http\Controllers\AdminMaintenanceController::class);
});

require __DIR__.'/auth.php';