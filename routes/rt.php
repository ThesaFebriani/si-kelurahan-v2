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

        // VERIFIKASI WARGA
        Route::get('/warga/verification', [\App\Http\Controllers\RT\WargaController::class, 'indexVerification'])
            ->name('warga.verification');
        
        Route::post('/warga/verification/{id}', [\App\Http\Controllers\RT\WargaController::class, 'processVerification'])
            ->name('warga.verification.process');

        // DATA KELUARGA (READ ONLY FOR RT)
        Route::get('/keluarga', [\App\Http\Controllers\RT\KeluargaController::class, 'index'])->name('keluarga.index');
        Route::get('/keluarga/{id}', [\App\Http\Controllers\RT\KeluargaController::class, 'show'])->name('keluarga.show');

        Route::get('/permohonan', [PermohonanController::class, 'index'])
            ->name('permohonan.index');

        Route::get('/permohonan/arsip', [PermohonanController::class, 'arsip'])
            ->name('permohonan.arsip'); // <--- Arsip Route

        Route::get('/permohonan/{id}', [PermohonanController::class, 'show'])
            ->name('permohonan.detail');

        Route::get('/permohonan/{id}/approve', [PermohonanController::class, 'approve'])
            ->name('permohonan.approve'); // <--- tambahkan ini

        Route::get('/permohonan/{id}/preview-pengantar', [PermohonanController::class, 'previewSuratPengantar'])
            ->name('permohonan.preview');

        Route::post('/permohonan/{id}/process', [PermohonanController::class, 'processApproval'])
            ->name('permohonan.process');

        Route::post('/permohonan/{id}/regenerate', [PermohonanController::class, 'regenerateSuratPengantar'])
            ->name('permohonan.regenerate');
    });
