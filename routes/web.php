<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/verification-pending', function () {
    return view('auth.verification-pending');
})->name('verification.pending');

// Redirect dashboard sesuai role
Route::middleware(['auth'])->get('/dashboard', function () {
    $role = Auth::user()->role->name;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'rt' => redirect()->route('rt.dashboard'),
        'kasi' => redirect()->route('kasi.dashboard'),
        'lurah' => redirect()->route('lurah.dashboard'),
        'masyarakat' => redirect()->route('masyarakat.dashboard'),
        default => abort(403, 'Role tidak dikenali'),
    };
})->name('dashboard');

// Route Global (Semua Role)
Route::middleware(['auth'])->group(function () {
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])
        ->name('notifications.mark-all-read');
});

// Load file routes per-role
require __DIR__ . '/admin.php';
require __DIR__ . '/rt.php';
require __DIR__ . '/kasi.php';
require __DIR__ . '/lurah.php';
require __DIR__ . '/masyarakat.php';

// Load auth routes
require __DIR__ . '/auth.php';
