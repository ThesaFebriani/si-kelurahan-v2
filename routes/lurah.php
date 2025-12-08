<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lurah\DashboardController;
use App\Http\Controllers\Lurah\PermohonanController;
use App\Http\Controllers\Lurah\TandaTanganController;

Route::middleware(['auth', 'role:lurah'])
    ->prefix('lurah')
    ->name('lurah.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/permohonan', [PermohonanController::class, 'index'])
            ->name('permohonan.index');

        Route::get('/permohonan/arsip', [PermohonanController::class, 'arsip'])
            ->name('permohonan.arsip');

        Route::get('/permohonan/{id}', [PermohonanController::class, 'show'])
            ->name('permohonan.detail');

        Route::get('/tanda-tangan', [TandaTanganController::class, 'index'])
            ->name('tanda-tangan.index');

        Route::get('/tanda-tangan/{id}/sign', [TandaTanganController::class, 'sign'])
            ->name('tanda-tangan.sign');

        Route::post('/tanda-tangan/{id}/process', [TandaTanganController::class, 'processSign'])
            ->name('tanda-tangan.process');

        Route::get('/profile', [App\Http\Controllers\Lurah\ProfileController::class, 'index'])
            ->name('profile');
        
        Route::put('/profile', [App\Http\Controllers\Lurah\ProfileController::class, 'update'])
            ->name('profile.update');
    });
