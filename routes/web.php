<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\TwoFactorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
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

    // 2FA Routes
    Route::get('/2fa/setup', [TwoFactorController::class, 'showSetup'])->name('2fa.setup');
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable');

    // Bin API Routes
    Route::get('/api/bins', [\App\Http\Controllers\BinController::class, 'index'])->name('bins.index');
    Route::post('/api/bins/{slug}/scan', [\App\Http\Controllers\BinController::class, 'simulateScan'])->name('bins.scan');
    Route::post('/api/bins/{slug}/empty', [\App\Http\Controllers\BinController::class, 'emptyBin'])->name('bins.empty');
    Route::post('/api/system/seed-mock-data', [\App\Http\Controllers\BinController::class, 'seedMockData'])->name('system.seed');
    Route::get('/dashboard/export', [\App\Http\Controllers\BinController::class, 'exportPdf'])->name('dashboard.export');
    Route::get('/dashboard/reports', [\App\Http\Controllers\BinController::class, 'reports'])->name('dashboard.reports');
    Route::get('/dashboard/history', [\App\Http\Controllers\BinController::class, 'history'])->name('dashboard.history');
    Route::delete('/dashboard/history/clear', [\App\Http\Controllers\BinController::class, 'clearHistory'])->name('dashboard.history.clear');
});

require __DIR__.'/auth.php';
