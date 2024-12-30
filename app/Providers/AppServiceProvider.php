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
        $settings = Cache::remember('settings', 3600, function () {
            return Settings::first();
        });

        View::share('settings', $settings);
    }

}



