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

        Route::get('/permohonan', [PermohonanController::class, 'index'])
            ->name('permohonan.index');

        Route::get('/permohonan/{id}', [PermohonanController::class, 'show'])
            ->name('permohonan.detail');

        Route::get('/permohonan/{id}/verify', [PermohonanController::class, 'verify'])
            ->name('permohonan.verify');

        Route::post('/permohonan/{id}/process', [PermohonanController::class, 'processVerification'])
            ->name('permohonan.process');
    });
