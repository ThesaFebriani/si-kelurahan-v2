<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Masyarakat\DashboardController;
use App\Http\Controllers\Masyarakat\PermohonanController;
use App\Http\Middleware\RoleMiddleware;

// Route group masyarakat
Route::prefix('masyarakat')
    ->name('masyarakat.')
    ->middleware(['auth', RoleMiddleware::class . ':masyarakat'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::post('/feedback', [\App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');
        // Profile
        Route::get('/profile', [App\Http\Controllers\Masyarakat\ProfileController::class, 'index'])
            ->name('profile.index');
        Route::put('/profile', [App\Http\Controllers\Masyarakat\ProfileController::class, 'update'])
            ->name('profile.update');

        // Route group permohonan
        Route::prefix('permohonan')
            ->name('permohonan.')
            ->group(function () {

                // Index
                Route::get('/', [PermohonanController::class, 'index'])
                    ->name('index');

                // Create
                Route::get('/create', [PermohonanController::class, 'create'])
                    ->name('create');

                // Create form berdasarkan jenis_surat_id
                Route::get('/create/form/{jenis_surat_id}', [PermohonanController::class, 'createForm'])
                    ->where('jenis_surat_id', '[0-9]+')
                    ->name('create.form');

                // Store dinamis berdasarkan jenis_surat_id
                Route::post('/store/dinamis/{jenis_surat_id}', [PermohonanController::class, 'storeDinamis'])
                    ->where('jenis_surat_id', '[0-9]+')
                    ->middleware('throttle:6,1')
                    ->name('store.dinamis');

                // Store umum
                Route::post('/', [PermohonanController::class, 'store'])
                    ->middleware('throttle:6,1')
                    ->name('store');

                // Detail permohonan
                Route::get('/detail/{id}', [PermohonanController::class, 'show'])
                    ->where('id', '[0-9]+')
                    ->name('detail');
            });
    });
