<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RT\DashboardController;
use App\Http\Controllers\RT\PermohonanController;
use App\Http\Middleware\RoleMiddleware;

Route::middleware(['auth', RoleMiddleware::class . ':rt'])
    ->prefix('rt')
    ->name('rt.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/permohonan', [PermohonanController::class, 'index'])
            ->name('permohonan.index');

        Route::get('/permohonan/{id}', [PermohonanController::class, 'show'])
            ->name('permohonan.detail');

        Route::get('/permohonan/{id}/approve', [PermohonanController::class, 'approve'])
            ->name('permohonan.approve'); // <--- tambahkan ini

        Route::get('/permohonan/{id}/preview-pengantar', [PermohonanController::class, 'previewSuratPengantar'])
            ->name('permohonan.preview');

        Route::post('/permohonan/{id}/process', [PermohonanController::class, 'processApproval'])
            ->name('permohonan.process');
    });
