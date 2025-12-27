<?php

use App\Http\Controllers\Auth\SocialiteController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdminReservationController;
use App\Http\Controllers\AdminLabController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAssetController;
use App\Http\Controllers\AdminMaintenanceController; // Pastikan ini di-import

Route::get('/', function () {
    return view('welcome');
});

// ROUTE DASHBOARD (Halaman Utama setelah Login)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'log.aktivitas'])
    ->name('dashboard');

// Route Social Login
Route::get('auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);

// ==========================================
// GROUP 1: ROUTE MAHASISWA / UMUM
// ==========================================
Route::middleware(['auth', 'log.aktivitas'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Katalog & Booking Aset
    Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
    Route::get('/reservations/create/{asset}', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    
    // Riwayat (Histori) Mahasiswa
    Route::get('/reservations/assets', [ReservationController::class, 'indexAssets'])->name('reservations.assets');
    Route::get('/reservations/rooms', [ReservationController::class, 'indexRooms'])->name('reservations.rooms');
    
    // Tiket QR & Detail
    Route::get('/reservations/{id}/ticket', [ReservationController::class, 'downloadTicket'])->name('reservations.ticket');

    // Katalog & Booking Ruangan
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{lab}/book', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms/book', [RoomController::class, 'store'])->name('rooms.store');
}); 

// ==========================================
// GROUP 2: ROUTE KHUSUS ADMIN (SUPERADMIN/LABORAN)
// ==========================================
Route::middleware(['auth', 'role:Superadmin|Laboran', 'log.aktivitas'])
    ->prefix('admin')
    ->name('admin.') 
    ->group(function () {
    
    // 1. Fitur Import & Export (Maintenance PDF Tambahan)
    Route::post('labs/import', [AdminLabController::class, 'import'])->name('labs.import');
    Route::post('assets/import', [AdminAssetController::class, 'import'])->name('assets.import');
    
    // --- FITUR BARU: PDF MAINTENANCE ---
    Route::get('maintenances/export-pdf', [AdminMaintenanceController::class, 'exportPDF'])->name('maintenances.export-pdf');

    // 2. CRUD Manajemen Data
    Route::resource('assets', AdminAssetController::class);
    Route::resource('labs', AdminLabController::class);
    Route::resource('users', \App\Http\Controllers\AdminUserController::class); 
    Route::resource('maintenances', AdminMaintenanceController::class);

    // 3. Manajemen Transaksi & Scan QR (INTI SISTEM)
    Route::get('/transactions', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::get('/scan', [AdminReservationController::class, 'scanIndex'])->name('scan.index');
    Route::post('/scan/process', [AdminReservationController::class, 'scanProcess'])->name('scan.process');
    Route::patch('/reservations/{id}/status', [AdminReservationController::class, 'updateStatus'])->name('reservations.update');
    Route::patch('/reservations/{id}/pay', [AdminReservationController::class, 'payPenalty'])->name('reservations.pay');
    Route::get('/reports/peminjaman-pdf', [AdminReservationController::class, 'exportPDF'])->name('reports.pdf');

    // --- PROTEKSI KHUSUS SUPERADMIN (Silo Data Master) ---
    Route::middleware(['role:Superadmin'])->group(function () {
        Route::resource('prodis', \App\Http\Controllers\AdminProdiController::class);
        Route::resource('categories', \App\Http\Controllers\AdminCategoryController::class);
    });
});

require __DIR__.'/auth.php';