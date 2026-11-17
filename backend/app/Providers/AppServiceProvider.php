<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        // Deteksi subfolder jika aplikasi diakses melalui subdirektori Apache (misal: /Gerbangkartasusila-App/backend/public)
        $basePath = trim((string) request()->getBasePath(), '/');
        $prefix = $basePath !== '' ? "/{$basePath}" : '';

        if ($prefix !== '') {
            Livewire::setScriptRoute(function ($handle) use ($prefix) {
                return Route::get("{$prefix}/livewire/livewire.js", $handle);
            });

            Livewire::setUpdateRoute(function ($handle) use ($prefix) {
                return Route::post("{$prefix}/livewire/update", $handle)->middleware('web');
            });
        }
    }
}
