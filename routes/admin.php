<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\WilayahController;
use App\Http\Controllers\Admin\LaporanController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // User Management
        Route::resource('users', UserManagementController::class);

        // Jenis Surat
        Route::resource('jenis-surat', JenisSuratController::class)->parameters([
            'jenis-surat' => 'jenis_surat' // Agar parameter di controller sesuai standar Laravel convention
        ]);

        Route::get('/wilayah/rw', [WilayahController::class, 'rwIndex'])
            ->name('wilayah.rw.index');

        Route::get('/wilayah/rt', [WilayahController::class, 'rtIndex'])
            ->name('wilayah.rt.index');

        // Route::get('/laporan/permohonan', [LaporanController::class, 'permohonan'])
        //    ->name('laporan.permohonan');
        
        // NEW ADVANCED REPORTS
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('reports.index');

        // MANAJEMEN KEPENDUDUKAN
        Route::prefix('kependudukan')->name('kependudukan.')->group(function () {
            Route::resource('keluarga', \App\Http\Controllers\Admin\KeluargaController::class);
            Route::resource('penduduk', \App\Http\Controllers\Admin\PendudukController::class)->except(['index', 'create', 'show']);
        });

        // Template Surat Management
        Route::resource('templates', \App\Http\Controllers\Admin\TemplateController::class);
        
        // Pengaturan Global Format Surat Pengantar RT
        Route::get('/pengaturan/surat-pengantar', [\App\Http\Controllers\Admin\PengaturanSuratController::class, 'index'])->name('settings.surat-pengantar');
        Route::put('/pengaturan/surat-pengantar', [\App\Http\Controllers\Admin\PengaturanSuratController::class, 'update'])->name('settings.surat-pengantar.update');

        // Required Documents for Jenis Surat
        Route::post('/required-documents', [\App\Http\Controllers\Admin\RequiredDocumentController::class, 'store'])->name('required-documents.store');
        Route::delete('/required-documents/{id}', [\App\Http\Controllers\Admin\RequiredDocumentController::class, 'destroy'])->name('required-documents.destroy');

        // Dynamic Template Fields for Jenis Surat
        Route::post('/template-fields', [\App\Http\Controllers\Admin\TemplateFieldController::class, 'store'])->name('template-fields.store');
        Route::delete('/template-fields/{id}', [\App\Http\Controllers\Admin\TemplateFieldController::class, 'destroy'])->name('template-fields.destroy');
    });
