<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\WilayahController;


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

        // Master Data Bidang (Kasi)
        Route::resource('bidang', \App\Http\Controllers\Admin\BidangController::class);

        // Wilayah Management
        Route::prefix('wilayah')->name('wilayah.')->group(function () {
            // RW
            Route::get('/rw', [WilayahController::class, 'rwIndex'])->name('rw.index');
            Route::get('/rw/create', [WilayahController::class, 'rwCreate'])->name('rw.create');
            Route::post('/rw', [WilayahController::class, 'rwStore'])->name('rw.store');
            Route::get('/rw/{rw}/edit', [WilayahController::class, 'rwEdit'])->name('rw.edit');
            Route::put('/rw/{rw}', [WilayahController::class, 'rwUpdate'])->name('rw.update');
            Route::delete('/rw/{rw}', [WilayahController::class, 'rwDestroy'])->name('rw.destroy');

            // RT
            Route::get('/rt', [WilayahController::class, 'rtIndex'])->name('rt.index');
            Route::get('/rt/create', [WilayahController::class, 'rtCreate'])->name('rt.create');
            Route::post('/rt', [WilayahController::class, 'rtStore'])->name('rt.store');
            Route::get('/rt/{rt}/edit', [WilayahController::class, 'rtEdit'])->name('rt.edit');
            Route::put('/rt/{rt}', [WilayahController::class, 'rtUpdate'])->name('rt.update');
            Route::delete('/rt/{rt}', [WilayahController::class, 'rtDestroy'])->name('rt.destroy');
        });


        
        // NEW ADVANCED REPORTS


        Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])
            ->name('reports.export');
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('reports.index');

        // AUDIT LOGS
        Route::get('audit-logs/export', [\App\Http\Controllers\Admin\AuditLogController::class, 'export'])->name('audit-logs.export');
        Route::resource('audit-logs', \App\Http\Controllers\Admin\AuditLogController::class)->only(['index', 'show']);


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

        // Pengaturan Instansi (Global System Settings)
        Route::get('/settings', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'update'])->name('settings.update');

        // Required Documents for Jenis Surat
        Route::post('/required-documents', [\App\Http\Controllers\Admin\RequiredDocumentController::class, 'store'])->name('required-documents.store');
        Route::delete('/required-documents/{id}', [\App\Http\Controllers\Admin\RequiredDocumentController::class, 'destroy'])->name('required-documents.destroy');

        // Dynamic Template Fields for Jenis Surat
        Route::post('/template-fields', [\App\Http\Controllers\Admin\TemplateFieldController::class, 'store'])->name('template-fields.store');
        Route::delete('/template-fields/{id}', [\App\Http\Controllers\Admin\TemplateFieldController::class, 'destroy'])->name('template-fields.destroy');
    });
