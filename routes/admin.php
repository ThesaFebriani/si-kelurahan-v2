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

        Route::get('/users', [UserManagementController::class, 'index'])
            ->name('users.index');

        Route::get('/jenis-surat', [JenisSuratController::class, 'index'])
            ->name('jenis-surat.index');

        Route::get('/wilayah/rw', [WilayahController::class, 'rwIndex'])
            ->name('wilayah.rw.index');

        Route::get('/wilayah/rt', [WilayahController::class, 'rtIndex'])
            ->name('wilayah.rt.index');

        Route::get('/laporan/permohonan', [LaporanController::class, 'permohonan'])
            ->name('laporan.permohonan');
    });
