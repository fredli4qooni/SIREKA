<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // Pastikan ini ada
use Carbon\Carbon; // <-- Import Carbon

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
        Schema::defaultStringLength(191);

        // === TAMBAHKAN KODE INI ===
        // Mengatur locale Carbon ke Bahasa Indonesia secara global
        Carbon::setLocale('id');
        // ========================
    }
}