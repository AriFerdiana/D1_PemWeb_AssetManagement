<?php

use App\Http\Controllers\Auth\SocialiteController; 
use App\Http\Controllers\AdminAssetController;
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

// ROUTE DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route Social Login
Route::get('auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);

// === GROUP 1: ROUTE MAHASISWA / UMUM ===
Route::middleware('auth')->group(function () {
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

// === GROUP 2: ROUTE KHUSUS ADMIN (SUPERADMIN/LABORAN) ===

// A. Route Export/Import (Ditaruh diluar grup 'admin.' agar nama route 'assets.export' terbaca benar)
Route::middleware(['auth', 'role:Superadmin|Laboran'])->prefix('admin')->group(function () {
    Route::get('assets/export', [AdminAssetController::class, 'export'])->name('assets.export');
    Route::post('assets/import', [AdminAssetController::class, 'import'])->name('assets.import');
});

// B. Route Resource Admin (Otomatis pakai nama 'admin.')
Route::middleware(['auth', 'role:Superadmin|Laboran'])->prefix('admin')->name('admin.')->group(function () {
    
    // CRUD Assets
    Route::resource('assets', AdminAssetController::class);
    
    // --- FITUR SCANNER QR CODE, DENDA & LAPORAN ---
    Route::get('/scan', [AdminReservationController::class, 'scanIndex'])->name('scan.index');
    Route::post('/scan/process', [AdminReservationController::class, 'scanProcess'])->name('scan.process');
    Route::patch('/reservations/{id}/pay', [AdminReservationController::class, 'payPenalty'])->name('reservations.pay');
    
    // CETAK LAPORAN PDF
    Route::get('/reports/peminjaman-pdf', [AdminReservationController::class, 'exportPDF'])->name('reports.pdf');

    // CRUD Master Data Lainnya
    
    // PERBAIKAN DI SINI: Hapus "->except" agar fitur EDIT USER jalan
    Route::resource('users', \App\Http\Controllers\AdminUserController::class); 
    
    Route::resource('labs', \App\Http\Controllers\AdminLabController::class)->except(['edit', 'update']);
    Route::resource('prodis', \App\Http\Controllers\AdminProdiController::class)->except(['edit', 'update']);
    Route::resource('categories', \App\Http\Controllers\AdminCategoryController::class)->except(['edit', 'update']);
    Route::resource('maintenances', \App\Http\Controllers\AdminMaintenanceController::class)->except(['edit', 'update']);
});

require __DIR__.'/auth.php';