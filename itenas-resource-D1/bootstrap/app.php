<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// Pastikan nama ini sesuai dengan file yang Anda buat di langkah sebelumnya
use App\Http\Middleware\LogAktivitas; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // DAFTAR ALIAS MIDDLEWARE
        // Kita daftarkan di sini agar bisa dipanggil di routes/web.php
        $middleware->alias([
            // 1. Spatie (Permission & Role)
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

            // 2. Middleware Kustom Kita (Log Aktivitas)
            // PENTING: Ini menghubungkan nama 'log.aktivitas' dengan class LogAktivitas
            'log.aktivitas' => LogAktivitas::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();