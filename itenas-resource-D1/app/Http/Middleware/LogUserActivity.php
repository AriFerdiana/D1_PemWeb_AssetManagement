<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class LogUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Lanjutkan request dulu sampai selesai (biar kita tahu sukses/gagal)
        $response = $next($request);

        // 2. Cek jika User Login & Request bukan 'GET' (hanya catat aksi Create/Update/Delete)
        // Kita tidak mencatat 'GET' biar database gak penuh sampah view
        if (Auth::check() && $request->method() !== 'GET') {
            
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $request->method(), // POST, PUT, DELETE
                'description' => 'User mengakses URL: ' . $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}