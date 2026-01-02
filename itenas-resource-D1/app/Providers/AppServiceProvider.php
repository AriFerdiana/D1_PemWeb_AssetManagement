<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- JANGAN LUPA TAMBAH INI

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // PAKSA HTTPS JIKA PAKAI NGROK ATAU PRODUCTION
        if(config('app.env') !== 'local' || str_contains(request()->url(), 'ngrok')) {
            URL::forceScheme('https');
        }
    }
}