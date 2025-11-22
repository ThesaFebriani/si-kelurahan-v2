<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ==================== ROUTE PUBLIC ====================
Route::get('/', function () {
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Sistem Kelurahan</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; text-align: center; }
            .card { background: #f8f9fa; padding: 30px; border-radius: 8px; margin: 20px 0; }
            .btn { display: inline-block; padding: 10px 20px; margin: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
            .btn:hover { background: #0056b3; }
        </style>
    </head>
    <body>
        <h1>🏢 SISTEM KELURAHAN</h1>
        <p>Sistem Pelayanan Surat Menggunakan 4 Role</p>
        
        <div class="card">
            <h2>🚀 Mulai Menggunakan Sistem</h2>
            <a href="/login" class="btn">🔐 Login</a>
            <a href="/simple-login" class="btn">🧪 Simple Login</a>
            <a href="/debug/system" class="btn">🐛 Debug System</a>
        </div>

        <div class="card">
            <h3>👥 Role yang Tersedia:</h3>
            <p>Admin • RT • Kasi • Lurah • Masyarakat</p>
        </div>
    </body>
    </html>
    ';
});

// ==================== MANUAL LOGIN ROUTES (BACKUP) ====================
Route::get('/simple-login', function () {
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login - Sistem Kelurahan</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 400px; margin: 100px auto; padding: 20px; }
            .form-group { margin-bottom: 15px; }
            label { display: block; margin-bottom: 5px; }
            input[type="email"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
            button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
            button:hover { background: #0056b3; }
            .user-list { margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px; }
        </style>
    </head>
    <body>
        <h1>🔐 Login Sistem Kelurahan</h1>
        
        <form method="POST" action="/login">
            ' . csrf_field() . '
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="admin@kelurahan.dev" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" value="password123" required>
            </div>
            
            <button type="submit">Login</button>
        </form>

        <div class="user-list">
            <h3>📋 User Testing:</h3>
            <ul>
                <li><strong>Admin:</strong> admin@kelurahan.dev / password123</li>
                <li><strong>RT:</strong> rt001@kelurahan.dev / password123</li>
                <li><strong>Kasi:</strong> kasi.kesra@kelurahan.dev / password123</li>
                <li><strong>Lurah:</strong> lurah@kelurahan.dev / password123</li>
                <li><strong>Masyarakat:</strong> warga@kelurahan.dev / password123</li>
            </ul>
        </div>

        <p><a href="/">🏠 Home</a> | <a href="/debug/system">🐛 Debug</a></p>
    </body>
    </html>
    ';
})->name('simple.login');

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

        echo "<hr>";
        echo "<p><a href='/logout' onclick='event.preventDefault(); document.getElementById(\"logout-form\").submit();'>🚪 Logout</a></p>";
        echo "<form id='logout-form' action='/logout' method='POST' style='display: none;'>" . csrf_field() . "</form>";

        return;
    }
    return redirect('/login');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==================== ROUTE PROFILE ====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== ROUTE DASHBOARD BERDASARKAN ROLE ====================

// Admin Dashboard
Route::get('/admin/dashboard', function () {
    if (!Auth::check()) return redirect('/login');
    $user = Auth::user();
    if ($user->role->name !== 'admin') abort(403, 'Akses admin ditolak');

    echo "<h1>📊 ADMIN DASHBOARD</h1>";
    echo "<p>Selamat datang, <strong>{$user->name}</strong>!</p>";
    echo "<p>Role: <strong>{$user->role->name}</strong></p>";

    echo "<h3>📈 Statistik Sistem:</h3>";
    echo "<ul>";
    echo "<li>Total Users: " . \App\Models\User::count() . "</li>";
    echo "<li>Total Permohonan: " . \App\Models\PermohonanSurat::count() . "</li>";
    echo "<li>Permohonan Pending: " . \App\Models\PermohonanSurat::where('status', 'menunggu_rt')->count() . "</li>";
    echo "</ul>";

    echo "<h3>🔧 Menu Admin:</h3>";
    echo "<ul>";
    echo "<li><a href='/dashboard'>🔙 Dashboard Utama</a></li>";
    echo "<li><a href='/test/simple-role-check'>🧪 Test Role</a></li>";
    echo "</ul>";
    return;
})->middleware('auth')->name('admin.dashboard');

// RT Dashboard
Route::get('/rt/dashboard', function () {
    if (!Auth::check()) return redirect('/login');
    $user = Auth::user();
    if ($user->role->name !== 'rt') abort(403, 'Akses RT ditolak');

    echo "<h1>🏠 RT DASHBOARD</h1>";
    echo "<p>Selamat datang, <strong>{$user->name}</strong>!</p>";
    echo "<p>Role: <strong>{$user->role->name}</strong></p>";
    echo "<p>RT ID: <strong>{$user->rt_id}</strong></p>";

    echo "<h3>📊 Statistik RT:</h3>";
    echo "<ul>";
    echo "<li>Permohonan Pending: " . \App\Models\PermohonanSurat::where('status', 'menunggu_rt')->count() . "</li>";
    echo "<li>Permohonan Disetujui: " . \App\Models\PermohonanSurat::where('status', 'disetujui_rt')->count() . "</li>";
    echo "</ul>";

    echo "<h3>📋 Menu RT:</h3>";
    echo "<ul>";
    echo "<li><a href='/dashboard'>🔙 Dashboard Utama</a></li>";
    echo "<li><a href='/test/simple-role-check'>🧪 Test Role</a></li>";
    echo "</ul>";
    return;
})->middleware('auth')->name('rt.dashboard');

// Kasi Dashboard
Route::get('/kasi/dashboard', function () {
    if (!Auth::check()) return redirect('/login');
    $user = Auth::user();
    if ($user->role->name !== 'kasi') abort(403, 'Akses Kasi ditolak');

    echo "<h1>📋 KASI DASHBOARD</h1>";
    echo "<p>Selamat datang, <strong>{$user->name}</strong>!</p>";
    echo "<p>Role: <strong>{$user->role->name}</strong></p>";
    echo "<p>Bidang: <strong>{$user->bidang}</strong></p>";

    echo "<h3>📊 Statistik Kasi:</h3>";
    echo "<ul>";
    echo "<li>Permohonan Pending: " . \App\Models\PermohonanSurat::where('status', 'menunggu_kasi')->count() . "</li>";
    echo "<li>Permohonan Disetujui: " . \App\Models\PermohonanSurat::where('status', 'disetujui_kasi')->count() . "</li>";
    echo "</ul>";

    echo "<h3>📝 Menu Kasi:</h3>";
    echo "<ul>";
    echo "<li><a href='/dashboard'>🔙 Dashboard Utama</a></li>";
    echo "<li><a href='/test/simple-role-check'>🧪 Test Role</a></li>";
    echo "</ul>";
    return;
})->middleware('auth')->name('kasi.dashboard');

// LURAH Dashboard - YANG INI YANG HILANG
Route::get('/lurah/dashboard', function () {
    if (!Auth::check()) return redirect('/login');
    $user = Auth::user();
    if ($user->role->name !== 'lurah') abort(403, 'Akses Lurah ditolak');

    echo "<h1>📝 LURAH DASHBOARD</h1>";
    echo "<p>Selamat datang, <strong>{$user->name}</strong>!</p>";
    echo "<p>Role: <strong>{$user->role->name}</strong></p>";

    echo "<h3>📊 Statistik Lurah:</h3>";
    echo "<ul>";
    echo "<li>Permohonan Pending: " . \App\Models\PermohonanSurat::where('status', 'menunggu_lurah')->count() . "</li>";
    echo "<li>Permohonan Selesai: " . \App\Models\PermohonanSurat::where('status', 'selesai')->count() . "</li>";
    echo "<li>Total Surat Terbit: " . \App\Models\Surat::count() . "</li>";
    echo "</ul>";

    echo "<h3>🖊️ Menu Lurah:</h3>";
    echo "<ul>";
    echo "<li><a href='/dashboard'>🔙 Dashboard Utama</a></li>";
    echo "<li><a href='/test/simple-role-check'>🧪 Test Role</a></li>";
    echo "<li><a href='/lurah/tanda-tangan'>✍️ Tanda Tangan Digital</a></li>";
    echo "</ul>";
    return;
})->middleware('auth')->name('lurah.dashboard');

// MASYARAKAT Dashboard - YANG INI JUGA HILANG
Route::get('/masyarakat/dashboard', function () {
    if (!Auth::check()) return redirect('/login');
    $user = Auth::user();
    if ($user->role->name !== 'masyarakat') abort(403, 'Akses Masyarakat ditolak');

    echo "<h1>👥 MASYARAKAT DASHBOARD</h1>";
    echo "<p>Selamat datang, <strong>{$user->name}</strong>!</p>";
    echo "<p>Role: <strong>{$user->role->name}</strong></p>";

    echo "<h3>📊 Statistik Permohonan Anda:</h3>";
    echo "<ul>";
    echo "<li>Total Permohonan: " . \App\Models\PermohonanSurat::where('user_id', $user->id)->count() . "</li>";
    echo "<li>Permohonan Pending: " . \App\Models\PermohonanSurat::where('user_id', $user->id)
        ->whereIn('status', ['menunggu_rt', 'menunggu_kasi', 'menunggu_lurah'])->count() . "</li>";
    echo "<li>Permohonan Selesai: " . \App\Models\PermohonanSurat::where('user_id', $user->id)
        ->where('status', 'selesai')->count() . "</li>";
    echo "</ul>";

    echo "<h3>📨 Menu Masyarakat:</h3>";
    echo "<ul>";
    echo "<li><a href='/dashboard'>🔙 Dashboard Utama</a></li>";
    echo "<li><a href='/test/simple-role-check'>🧪 Test Role</a></li>";
    echo "<li><a href='/masyarakat/permohonan'>📄 Ajukan Permohonan</a></li>";
    echo "<li><a href='/masyarakat/riwayat'>📋 Riwayat Permohonan</a></li>";
    echo "</ul>";
    return;
})->middleware('auth')->name('masyarakat.dashboard');

// ==================== ROUTE DEBUG ====================
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
    echo "<li><a href='/login'>Login (Breeze)</a></li>";
    echo "<li><a href='/simple-login'>Simple Login</a></li>";
    echo "<li><a href='/register'>Register</a></li>";
    if (Auth::check()) {
        echo "<li><a href='/dashboard'>Dashboard</a></li>";
        echo "<li><a href='/logout' onclick='event.preventDefault(); document.getElementById(\"logout-form\").submit();'>Logout</a></li>";
        echo "<form id='logout-form' action='/logout' method='POST' style='display: none;'>" . csrf_field() . "</form>";
    }
    echo "</ul>";
});

// ==================== AUTH ROUTES ====================
require __DIR__ . '/auth.php';
