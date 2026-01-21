<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/kebijakan-privasi', function () {
    return view('pages.public.privacy-policy');
})->name('privacy.policy');

// Public Verification Routes
// IMPORTANT: Specific routes must verify BEFORE greedy wildcard routes

Route::get('/verify/surat/{nomor_surat}/view', [\App\Http\Controllers\PublicController::class, 'viewSurat'])
    ->where('nomor_surat', '.*')
    ->name('public.verify.surat.view');

Route::get('/verify/surat/{nomor_surat}', [\App\Http\Controllers\PublicController::class, 'verifySurat'])
    ->where('nomor_surat', '.*') // Allow slashes in nomor_surat
    ->name('public.verify.surat');

Route::get('/verify/surat-pengantar/{id}', [\App\Http\Controllers\PublicController::class, 'verifyPengantar'])
    ->name('public.verify.pengantar');

Route::get('/verify/surat-pengantar/{id}/view', [\App\Http\Controllers\PublicController::class, 'viewPengantar'])
    ->name('public.verify.pengantar.view');

Route::get('/peta-digital', [\App\Http\Controllers\GisController::class, 'index'])->name('gis.index');

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

    // Secure Document Routes
    Route::get('/documents/{filename}', [\App\Http\Controllers\DocumentController::class, 'show'])
        ->where('filename', '.*')
        ->name('documents.show');
});

// Load file routes per-role
require __DIR__ . '/admin.php';
require __DIR__ . '/rt.php';
require __DIR__ . '/kasi.php';
require __DIR__ . '/lurah.php';
require __DIR__ . '/masyarakat.php';

// Load auth routes
require __DIR__ . '/auth.php';
