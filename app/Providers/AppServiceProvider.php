<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        // Force HTTPS in production / reverse proxies (Railway) to prevent Mixed Content & 419 token redirects
        if (request()->header('X-Forwarded-Proto') === 'https' || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) || isset($_SERVER['HTTPS']) || app()->isProduction()) {
            URL::forceScheme('https');
        }

        // Own counter for "Back up now"; a plain throttle:5,1 would share the
        // per-user counter with every other throttled request (dashboard polling).
        RateLimiter::for('backups', function (Request $request) {
            return Limit::perMinute(5)->by('backups:'.$request->user()?->id);
        });

        // Configure Strong Password default requirements across the application
        Password::defaults(function () {
            return Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();
        });
    }
}
