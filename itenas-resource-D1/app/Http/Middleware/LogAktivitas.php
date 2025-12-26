<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Wajib ada
use Illuminate\Support\Facades\Log;  // Wajib ada
use Symfony\Component\HttpFoundation\Response;

class LogAktivitas
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (Auth::check()) {
            $user = Auth::user();
            $nama = $user->name;
            // Mengambil role pertama (jika pakai Spatie)
            $role = $user->getRoleNames()->first() ?? 'User Biasa';
            
            $url = $request->fullUrl();
            $method = $request->method(); // GET, POST, PUT, DELETE
            $ip = $request->ip();

            // Tulis ke Log (storage/logs/laravel.log)
            Log::info("📝 AKTIVITAS: [{$role}] {$nama} mengakses {$method} di URL: {$url} (IP: {$ip})");
        }

        return $next($request);
    }
}