<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lurah\DashboardController;
use App\Http\Controllers\Lurah\PermohonanController;

Route::middleware(['auth', 'role:lurah'])
    ->prefix('lurah')
    ->name('lurah.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/permohonan', [PermohonanController::class, 'index'])
            ->name('permohonan.index');

        Route::get('/permohonan/{id}', [PermohonanController::class, 'show'])
            ->name('permohonan.detail');

        Route::get('/permohonan/{id}/sign', [PermohonanController::class, 'sign'])
            ->name('permohonan.sign');

        Route::post('/permohonan/{id}/process', [PermohonanController::class, 'processSign'])
            ->name('permohonan.process');
    });
