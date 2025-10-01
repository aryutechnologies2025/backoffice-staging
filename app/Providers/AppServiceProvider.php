<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use App\Models\Settings;
use Illuminate\Support\Facades\View;

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
    // public function boot(): void
    // {
    //     View::share('settings', Settings::first());
    // }
    public function boot(): void
    {
        try {
            $settings = Cache::remember('settings', 86400, function () {
                return Settings::first();
            });

            View::share('settings', $settings);
        } catch (\Exception $e) {
            // Log the error but don't break the application
            \Log::error('Settings loading failed: ' . $e->getMessage());
            View::share('settings', null);
        }
    }
}
