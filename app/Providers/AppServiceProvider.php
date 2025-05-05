<?php

namespace App\Providers;

use App\Models\IncomingLetter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        // Route model binding untuk IncomingLetter pakai slug
        Route::bind('incomingletter', function ($value) {
            return IncomingLetter::where('slug', $value)->firstOrFail();
        });
    }
}
