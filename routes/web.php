<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\TwoFactorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('login');

Route::get('/login', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', '2fa'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/verify-email-change', [ProfileController::class, 'showVerifyEmailChange'])->name('profile.verify-email-change');
    Route::post('/profile/confirm-email-change', [ProfileController::class, 'confirmEmailChange'])->name('profile.confirm-email-change');

    // 2FA Routes
    Route::get('/2fa/setup', [TwoFactorController::class, 'showSetup'])->name('2fa.setup');
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable');
    Route::post('/2fa/reset', [TwoFactorController::class, 'resetSetup'])->name('2fa.reset');
    Route::get('/2fa/confirm', [TwoFactorController::class, 'showConfirm'])->name('2fa.confirm');
    Route::post('/2fa/confirm', [TwoFactorController::class, 'confirm']);

    // Common Operational Routes (Admin & SuperAdmin) with Rate Limiting (max 60 req/min)
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/api/bins', [\App\Http\Controllers\BinController::class, 'index'])->name('bins.index');
        Route::post('/api/bins/{slug}/empty', [\App\Http\Controllers\BinController::class, 'emptyBin'])->name('bins.empty');
        Route::get('/dashboard/reports', [\App\Http\Controllers\BinController::class, 'reports'])->name('dashboard.reports');
        Route::get('/dashboard/export', [\App\Http\Controllers\BinController::class, 'exportPdf'])->name('dashboard.export');
        Route::get('/dashboard/export/csv', [\App\Http\Controllers\BinController::class, 'exportCsv'])->name('dashboard.export.csv');
        Route::get('/dashboard/history', [\App\Http\Controllers\BinController::class, 'history'])->name('dashboard.history');
    });

    // Hardware Scanning Endpoints with Cryptographic Payload & Signature Verification (ECDSA / AES-256-GCM)
    Route::middleware(['throttle:60,1', 'hardware.crypto'])->group(function () {
        Route::post('/api/bins/{slug}/scan', [\App\Http\Controllers\BinController::class, 'simulateScan'])->name('bins.scan');
        Route::post('/api/bins/camera-scan', [\App\Http\Controllers\BinController::class, 'cameraScan'])->name('bins.camera-scan');
    });

    // SuperAdmin Only Routes (Hardware Monitoring & System Maintenance)
    Route::middleware(['role:superadmin', 'throttle:60,1'])->group(function () {
        Route::get('/dashboard/hardware', [\App\Http\Controllers\BinController::class, 'hardware'])->name('dashboard.hardware');
        Route::post('/api/system/seed-mock-data', [\App\Http\Controllers\BinController::class, 'seedMockData'])->name('system.seed');
        Route::post('/api/hardware/crypto-demo', [\App\Http\Controllers\BinController::class, 'cryptoDemo'])->name('hardware.crypto-demo');
        Route::delete('/dashboard/history/clear', [\App\Http\Controllers\BinController::class, 'clearHistory'])->name('dashboard.history.clear');
    });
});

require __DIR__.'/auth.php';
