<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        // Log out a user's other sessions when their password changes.
        $middleware->web(append: [
            \Illuminate\Session\Middleware\AuthenticateSession::class,
        ]);
        // Only the device endpoints (ESP32, Raspberry Pi) skip CSRF; they have no
        // browser session. Dashboard actions under /api/* must send a CSRF token.
        $middleware->validateCsrfTokens(except: [
            'api/sensor/levels',
            'api/update_bins',
            'api/bins/camera-scan',
            'api/bins/telemetry',
            'api/hardware/telemetry',
            'api/hardware/rpi-telemetry',
            'api/hardware/esp32-telemetry',
        ]);
        $middleware->alias([
            '2fa' => \Laragear\TwoFactor\Http\Middleware\ConfirmTwoFactorCode::class,
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'hardware.crypto' => \App\Http\Middleware\VerifyHardwareSignature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 419) {
                return redirect()->route('login')->with('status', 'Your session expired. Please sign in again.');
            }
        });
    })->create();
