<?php

namespace App\Providers;

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
        // Force HTTPS in production / reverse proxies (Railway) to prevent Mixed Content asset blocking
        if ($this->app->environment('production') || request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

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
