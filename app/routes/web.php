<?php

use App\Http\Controllers\ArsipController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

// ── AUTH ROUTES (Breeze) ──────────────────────────────
require __DIR__.'/auth.php';

// ── AUTHENTICATED ROUTES ──────────────────────────────
Route::middleware(['auth'])->group(function () {

    // 1. Dashboard Utama
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // 2. Arsip Keuangan POK
    Route::get('/items', [ArsipController::class, 'index'])->name('items.index');
    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');

    // 5. Laporan & Rekapitulasi Digital
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── ITEM DETAIL WORKSPACE ─────────────────────────
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::patch('/items/{item}/verify', [ItemController::class, 'verify'])->name('items.verify');

    // ── DOCUMENTS ─────────────────────────────────────
    Route::post('/items/{item}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/stream', [DocumentController::class, 'stream'])->name('documents.stream');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // 3. Verifikasi Pencairan (Bendahara & Admin)
    Route::middleware('role:BENDAHARA,ADMIN')->group(function () {
        Route::get('/verification', [VerificationController::class, 'index'])->name('verification.index');
    });

    // 4. Kelola Master POK (Supervisor & Admin)
    Route::middleware('role:SUPERVISOR,ADMIN')->group(function () {
        Route::get('/master', [MasterController::class, 'index'])->name('master.index');

        Route::post('/master/fiscal-years', [MasterController::class, 'storeFiscalYear'])->name('master.fiscal-years.store');
        Route::post('/master/programs', [MasterController::class, 'storeProgram'])->name('master.programs.store');
        Route::patch('/master/programs/{program}', [MasterController::class, 'updateProgram'])->name('master.programs.update');
        Route::delete('/master/programs/{program}', [MasterController::class, 'destroyProgram'])->name('master.programs.destroy');

        Route::post('/master/outputs', [MasterController::class, 'storeOutput'])->name('master.outputs.store');
        Route::post('/master/sub-outputs', [MasterController::class, 'storeSubOutput'])->name('master.sub-outputs.store');
        Route::post('/master/components', [MasterController::class, 'storeComponent'])->name('master.components.store');
        Route::post('/master/sub-components', [MasterController::class, 'storeSubComponent'])->name('master.sub-components.store');
        Route::post('/master/accounts', [MasterController::class, 'storeAccount'])->name('master.accounts.store');

        Route::post('/master/items', [MasterController::class, 'storeItem'])->name('master.items.store');
        Route::patch('/master/items/{item}', [MasterController::class, 'updateItem'])->name('master.items.update');
        Route::delete('/master/items/{item}', [MasterController::class, 'destroyItem'])->name('master.items.destroy');
    });

    // 6. Manajemen Pengguna (Admin only)
    Route::middleware('role:ADMIN')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
