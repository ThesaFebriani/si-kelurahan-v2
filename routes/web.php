<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Redirect dashboard sesuai role
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
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

// Load file routes per-role
require __DIR__ . '/admin.php';
require __DIR__ . '/rt.php';
require __DIR__ . '/kasi.php';
require __DIR__ . '/lurah.php';
require __DIR__ . '/masyarakat.php';

// Load auth routes
require __DIR__ . '/auth.php';
