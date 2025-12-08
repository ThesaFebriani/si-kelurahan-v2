<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kasi\DashboardController;
use App\Http\Controllers\Kasi\PermohonanController;

Route::middleware(['auth', 'role:kasi'])
    ->prefix('kasi')
    ->name('kasi.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/permohonan/arsip', [PermohonanController::class, 'arsip'])
            ->name('permohonan.arsip');

        Route::get('/permohonan/{id}/preview', [PermohonanController::class, 'previewSurat'])
            ->name('permohonan.preview');

        Route::get('/permohonan', [PermohonanController::class, 'index'])
            ->name('permohonan.index');

        Route::get('/permohonan/{id}', [PermohonanController::class, 'show'])
            ->name('permohonan.detail');

        Route::get('/permohonan/{id}/verify', [PermohonanController::class, 'verify'])
            ->name('permohonan.verify');

        Route::post('/permohonan/{id}/process', [PermohonanController::class, 'processVerification'])
            ->name('permohonan.process');

        Route::get('/permohonan/{id}/draft', [PermohonanController::class, 'draft'])
            ->name('permohonan.draft');
            
        Route::post('/permohonan/{id}/draft', [PermohonanController::class, 'storeDraft'])
            ->name('permohonan.store-draft');
    });
