<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\RT\DashboardController as RTDashboardController;
use App\Http\Controllers\Kasi\DashboardController as KasiDashboardController;
use App\Http\Controllers\Lurah\DashboardController as LurahDashboardController;
use App\Http\Controllers\Masyarakat\DashboardController as MasyarakatDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ==================== ROUTE PUBLIC ====================
Route::get('/', function () {
    return view('welcome'); // Biarkan Breeze handle welcome page
});

// ==================== ROUTE AUTH DASHBOARD ====================
Route::get('/dashboard', function () {
    if (Auth::check()) {
        $user = Auth::user();
        $roleName = $user->role->name;

        echo "<h1>🚀 SISTEM KELURAHAN - DASHBOARD</h1>";
        echo "<p>Selamat datang, <strong>{$user->name}</strong>!</p>";
        echo "<p>Role: <strong>{$roleName}</strong></p>";
        echo "<p>Email: <strong>{$user->email}</strong></p>";

        echo "<h2>🎯 Menu Berdasarkan Role:</h2>";
        echo "<ul>";

        if ($roleName === 'admin') {
            echo "<li><a href='/admin/dashboard' style='color: green;'>📊 Admin Dashboard</a></li>";
        }
        if ($roleName === 'rt') {
            echo "<li><a href='/rt/dashboard' style='color: blue;'>🏠 RT Dashboard</a></li>";
        }
        if ($roleName === 'kasi') {
            echo "<li><a href='/kasi/dashboard' style='color: orange;'>📋 Kasi Dashboard</a></li>";
        }
        if ($roleName === 'lurah') {
            echo "<li><a href='/lurah/dashboard' style='color: purple;'>📝 Lurah Dashboard</a></li>";
        }
        if ($roleName === 'masyarakat') {
            echo "<li><a href='/masyarakat/dashboard' style='color: brown;'>👥 Masyarakat Dashboard</a></li>";
        }

        echo "</ul>";

        echo "<h2>🧪 Testing Access:</h2>";
        echo "<ul>";
        echo "<li><a href='/test/simple-role-check'>Cek Role & Permission</a></li>";
        echo "<li><a href='/test/all-dashboards'>Test Semua Dashboard</a></li>";
        echo "</ul>";

        echo "<hr>";
        echo "<p><a href='/logout' onclick='event.preventDefault(); document.getElementById(\"logout-form\").submit();'>🚪 Logout</a></p>";
        echo "<form id='logout-form' action='/logout' method='POST' style='display: none;'>" . csrf_field() . "</form>";

        return;
    }
    return redirect('/login'); // Redirect ke login jika belum login
})->middleware(['auth', 'verified'])->name('dashboard');

// ==================== ROUTE PROFILE ====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== ROUTE DASHBOARD BERDASARKAN ROLE ====================

// Admin Dashboard - TEMPORARY tanpa middleware 'admin'
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role->name !== 'admin') abort(403);
        return app(\App\Http\Controllers\Admin\DashboardController::class)->index();
    })->name('dashboard');

    // User Management
    Route::get('/users', function () {
        $user = Auth::user();
        if ($user->role->name !== 'admin') abort(403);
        return "<h1>Admin - Management User</h1><p>Halaman management user</p>";
    })->name('users.index');

    // Jenis Surat
    Route::get('/jenis-surat', function () {
        $user = Auth::user();
        if ($user->role->name !== 'admin') abort(403);
        return "<h1>Admin - Jenis Surat</h1><p>Halaman management jenis surat</p>";
    })->name('jenis-surat.index');

    // Data Wilayah
    Route::get('/wilayah/rt', function () {
        $user = Auth::user();
        if ($user->role->name !== 'admin') abort(403);
        return "<h1>Admin - Data RT</h1><p>Halaman data RT</p>";
    })->name('wilayah.rt.index');

    Route::get('/wilayah/rw', function () {
        $user = Auth::user();
        if ($user->role->name !== 'admin') abort(403);
        return "<h1>Admin - Data RW</h1><p>Halaman data RW</p>";
    })->name('wilayah.rw.index');

    // Laporan
    Route::get('/laporan/permohonan', function () {
        $user = Auth::user();
        if ($user->role->name !== 'admin') abort(403);
        return "<h1>Admin - Laporan Permohonan</h1><p>Halaman laporan permohonan</p>";
    })->name('laporan.permohonan');

    Route::get('/laporan/kinerja', function () {
        $user = Auth::user();
        if ($user->role->name !== 'admin') abort(403);
        return "<h1>Admin - Laporan Kinerja</h1><p>Halaman laporan kinerja</p>";
    })->name('laporan.kinerja');
});

// RT Dashboard - TEMPORARY tanpa middleware 'rt'
Route::middleware(['auth'])->prefix('rt')->name('rt.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role->name !== 'rt') abort(403);
        return app(\App\Http\Controllers\RT\DashboardController::class)->index();
    })->name('dashboard');

    // Permohonan Surat
    Route::get('/permohonan', function () {
        $user = Auth::user();
        if ($user->role->name !== 'rt') abort(403);
        return "<h1>RT - Daftar Permohonan</h1><p>Halaman daftar permohonan surat</p>";
    })->name('permohonan.index');

    Route::get('/permohonan/{id}', function ($id) {
        $user = Auth::user();
        if ($user->role->name !== 'rt') abort(403);
        return "<h1>RT - Detail Permohonan</h1><p>Detail permohonan ID: {$id}</p>";
    })->name('permohonan.detail');

    Route::get('/permohonan/{id}/approve', function ($id) {
        $user = Auth::user();
        if ($user->role->name !== 'rt') abort(403);
        return "<h1>RT - Approve Permohonan</h1><p>Approve permohonan ID: {$id}</p>";
    })->name('permohonan.approve');

    // Data Keluarga
    Route::get('/keluarga', function () {
        $user = Auth::user();
        if ($user->role->name !== 'rt') abort(403);
        return "<h1>RT - Data Keluarga</h1><p>Halaman data keluarga</p>";
    })->name('keluarga.index');

    Route::get('/keluarga/{id}', function ($id) {
        $user = Auth::user();
        if ($user->role->name !== 'rt') abort(403);
        return "<h1>RT - Detail Keluarga</h1><p>Detail keluarga ID: {$id}</p>";
    })->name('keluarga.detail');
});

// Kasi Dashboard - TEMPORARY tanpa middleware 'kasi'
Route::middleware(['auth'])->prefix('kasi')->name('kasi.')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role->name !== 'kasi') abort(403, 'Akses Kasi ditolak. Role Anda: ' . $user->role->name);
        return app(\App\Http\Controllers\Kasi\DashboardController::class)->index();
    })->name('dashboard');

    // Tambahkan routes Kasi lainnya
    Route::get('/permohonan', function () {
        $user = Auth::user();
        if ($user->role->name !== 'kasi') abort(403);
        return "<h1>Kasi - Verifikasi Permohonan</h1><p>Halaman verifikasi permohonan</p>";
    })->name('permohonan.index');

    Route::get('/permohonan/{id}/verify', function ($id) {
        $user = Auth::user();
        if ($user->role->name !== 'kasi') abort(403);
        return "<h1>Kasi - Verifikasi Permohonan ID: {$id}</h1><p>Halaman verifikasi detail</p>";
    })->name('permohonan.verify');

    Route::get('/template', function () {
        $user = Auth::user();
        if ($user->role->name !== 'kasi') abort(403);
        return "<h1>Kasi - Template Surat</h1><p>Halaman management template surat</p>";
    })->name('template.index');
});

// Lurah Routes  
// Lurah Dashboard - TEMPORARY tanpa middleware 'lurah'
Route::middleware(['auth'])->prefix('lurah')->name('lurah.')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role->name !== 'lurah') abort(403);
        return app(\App\Http\Controllers\Lurah\DashboardController::class)->index();
    })->name('dashboard');

    // Tambahkan routes Lurah lainnya
    Route::get('/tanda-tangan', function () {
        $user = Auth::user();
        if ($user->role->name !== 'lurah') abort(403);
        return "<h1>Lurah - Tanda Tangan Digital</h1><p>Halaman tanda tangan digital</p>";
    })->name('tanda-tangan.index');

    Route::get('/laporan', function () {
        $user = Auth::user();
        if ($user->role->name !== 'lurah') abort(403);
        return "<h1>Lurah - Laporan</h1><p>Halaman laporan</p>";
    })->name('laporan.index');
});

// Masyarakat Dashboard - TEMPORARY tanpa middleware 'masyarakat'
Route::middleware(['auth'])->prefix('masyarakat')->name('masyarakat.')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role->name !== 'masyarakat') abort(403);
        return app(\App\Http\Controllers\Masyarakat\DashboardController::class)->index();
    })->name('dashboard');

    // Tambahkan routes Masyarakat lainnya
    Route::get('/permohonan', function () {
        $user = Auth::user();
        if ($user->role->name !== 'masyarakat') abort(403);
        return "<h1>Masyarakat - Permohonan</h1><p>Halaman permohonan</p>";
    })->name('permohonan.index');

    Route::get('/permohonan/create', function () {
        $user = Auth::user();
        if ($user->role->name !== 'masyarakat') abort(403);
        return "<h1>Masyarakat - Ajukan Permohonan</h1><p>Halaman ajukan permohonan</p>";
    })->name('permohonan.create');
});

// ==================== TEST ROUTES ====================
Route::get('/test/simple-role-check', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    $roleName = $user->role->name;

    echo "<h1>🧪 SIMPLE ROLE CHECK</h1>";
    echo "<pre>";
    print_r([
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role_id' => $user->role_id,
        'role_name' => $roleName,
        'rt_id' => $user->rt_id,
        'bidang' => $user->bidang,
    ]);
    echo "</pre>";

    echo "<h3>🔗 Test Dashboard Access:</h3>";
    echo "<ul>";
    echo "<li><a href='/admin/dashboard'>Admin Dashboard</a></li>";
    echo "<li><a href='/rt/dashboard'>RT Dashboard</a></li>";
    echo "<li><a href='/kasi/dashboard'>Kasi Dashboard</a></li>";
    echo "<li><a href='/lurah/dashboard'>Lurah Dashboard</a></li>";
    echo "<li><a href='/masyarakat/dashboard'>Masyarakat Dashboard</a></li>";
    echo "</ul>";

    echo "<p><a href='/dashboard'>🔙 Kembali ke Dashboard</a></p>";
});

Route::get('/test/all-dashboards', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    echo "<h1>🧪 TEST ALL DASHBOARDS</h1>";
    echo "<p>User: <strong>{$user->name}</strong> ({$user->role->name})</p>";

    echo "<h3>Coba Akses:</h3>";
    echo "<ul>";
    echo "<li><a href='/admin/dashboard'>Admin Dashboard</a></li>";
    echo "<li><a href='/rt/dashboard'>RT Dashboard</a></li>";
    echo "<li><a href='/kasi/dashboard'>Kasi Dashboard</a></li>";
    echo "<li><a href='/lurah/dashboard'>Lurah Dashboard</a></li>";
    echo "<li><a href='/masyarakat/dashboard'>Masyarakat Dashboard</a></li>";
    echo "</ul>";

    echo "<p><a href='/dashboard'>🔙 Kembali ke Dashboard</a></p>";
});

// ==================== DEBUG ROUTES ====================
Route::get('/debug/system', function () {
    echo "<h1>🐛 DEBUG SYSTEM</h1>";

    if (Auth::check()) {
        echo "<p style='color: green'>✅ LOGGED IN as: " . Auth::user()->name . "</p>";
    } else {
        echo "<p style='color: red'>❌ NOT LOGGED IN</p>";
    }

    echo "<h3>Available Routes:</h3>";
    echo "<ul>";
    echo "<li><a href='/'>Home</a></li>";
    echo "<li><a href='/login'>Login</a></li>";
    echo "<li><a href='/register'>Register</a></li>";
    if (Auth::check()) {
        echo "<li><a href='/dashboard'>Dashboard</a></li>";
        echo "<li><a href='/logout' onclick='event.preventDefault(); document.getElementById(\"logout-form\").submit();'>Logout</a></li>";
        echo "<form id='logout-form' action='/logout' method='POST' style='display: none;'>" . csrf_field() . "</form>";
    }
    echo "</ul>";
});

// ==================== TEST MIDDLEWARE ROUTES ====================
require __DIR__ . '/test-middleware.php';

// ==================== AUTH ROUTES (JANGAN DIUBAH) ====================
require __DIR__ . '/auth.php';
