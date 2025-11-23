<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\RT\DashboardController as RTDashboardController;
use App\Http\Controllers\Kasi\DashboardController as KasiDashboardController;
use App\Http\Controllers\Lurah\DashboardController as LurahDashboardController;
use App\Http\Controllers\Masyarakat\DashboardController as MasyarakatDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\WilayahController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\RT\PermohonanController as RTPermohonanController;
use App\Http\Controllers\RT\KeluargaController as RTKeluargaController;
use App\Http\Controllers\Kasi\PermohonanController as KasiPermohonanController;
use App\Http\Controllers\Kasi\TemplateController as KasiTemplateController;
use App\Http\Controllers\Lurah\TandaTanganController as LurahTandaTanganController;
use App\Http\Controllers\Lurah\LaporanController as LurahLaporanController;
use App\Http\Controllers\Lurah\PermohonanController as LurahPermohonanController;
use App\Http\Controllers\Masyarakat\PermohonanController as MasyarakatPermohonanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ==================== ROUTE PUBLIC ====================
Route::get('/', function () {
    return view('welcome');
});

// ==================== ROUTE AUTH DASHBOARD ====================
Route::get('/dashboard', function () {
    if (Auth::check()) {
        $user = Auth::user();
        $roleName = $user->role->name;

        return match ($roleName) {
            'admin' => redirect()->route('admin.dashboard'),
            'rt' => redirect()->route('rt.dashboard'),
            'kasi' => redirect()->route('kasi.dashboard'),
            'lurah' => redirect()->route('lurah.dashboard'),
            'masyarakat' => redirect()->route('masyarakat.dashboard'),
            default => abort(403, 'Role tidak dikenali'),
        };
    }
    return redirect('/login');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Administrator yang dapat mengakses halaman ini.');
        }
        return app(AdminDashboardController::class)->index();
    })->name('dashboard');

    // User Management
    Route::get('/users', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'admin') abort(403);
        return app(UserManagementController::class)->index();
    })->name('users.index');

    // Jenis Surat
    Route::get('/jenis-surat', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'admin') abort(403);
        return app(JenisSuratController::class)->index();
    })->name('jenis-surat.index');

    // Wilayah
    Route::get('/wilayah/rw', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'admin') abort(403);
        return app(WilayahController::class)->rwIndex();
    })->name('wilayah.rw.index');

    Route::get('/wilayah/rt', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'admin') abort(403);
        return app(WilayahController::class)->rtIndex();
    })->name('wilayah.rt.index');

    // Laporan
    Route::get('/laporan/permohonan', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'admin') abort(403);
        return app(LaporanController::class)->permohonan();
    })->name('laporan.permohonan');
});

// ==================== RT ROUTES ====================
Route::middleware(['auth'])->prefix('rt')->name('rt.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'rt') abort(403);
        return app(App\Http\Controllers\RT\DashboardController::class)->index();
    })->name('dashboard');

    // Permohonan Routes
    Route::get('/permohonan', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'rt') abort(403);
        return app(App\Http\Controllers\RT\PermohonanController::class)->index();
    })->name('permohonan.index');

    Route::get('/permohonan/{id}', function ($id) {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'rt') abort(403);
        return app(App\Http\Controllers\RT\PermohonanController::class)->show($id);
    })->name('permohonan.detail');

    Route::get('/permohonan/{id}/approve', function ($id) {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'rt') abort(403);
        return app(App\Http\Controllers\RT\PermohonanController::class)->approve($id);
    })->name('permohonan.approve');

    Route::post('/permohonan/{id}/process', function ($id) {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'rt') abort(403);
        return app(App\Http\Controllers\RT\PermohonanController::class)->processApproval(request(), $id);
    })->name('permohonan.process');

    // NOTE: Route rt.keluarga.index sengaja tidak dibuat dulu
    // sampai fitur data keluarga diimplementasikan
});

// ==================== KASI ROUTES ====================
Route::middleware(['auth'])->prefix('kasi')->name('kasi.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'kasi') abort(403);
        return app(App\Http\Controllers\Kasi\DashboardController::class)->index();
    })->name('dashboard');

    // Permohonan Routes
    Route::get('/permohonan', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'kasi') abort(403);
        return app(App\Http\Controllers\Kasi\PermohonanController::class)->index();
    })->name('permohonan.index');

    Route::get('/permohonan/{id}', function ($id) {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'kasi') abort(403);
        return app(App\Http\Controllers\Kasi\PermohonanController::class)->show($id);
    })->name('permohonan.detail');

    Route::get('/permohonan/{id}/verify', function ($id) {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'kasi') abort(403);
        return app(App\Http\Controllers\Kasi\PermohonanController::class)->verify($id);
    })->name('permohonan.verify');

    Route::post('/permohonan/{id}/process', function ($id) {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'kasi') abort(403);
        return app(App\Http\Controllers\Kasi\PermohonanController::class)->processVerification(request(), $id);
    })->name('permohonan.process');
});

// ==================== LURAH ROUTES ====================
Route::middleware(['auth'])->prefix('lurah')->name('lurah.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'lurah') abort(403);
        return app(\App\Http\Controllers\Lurah\DashboardController::class)->index();
    })->name('dashboard');

    // Permohonan Routes - GUNAKAN FORMAT INI
    Route::get('/permohonan', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'lurah') abort(403);
        return app(\App\Http\Controllers\Lurah\PermohonanController::class)->index();
    })->name('permohonan.index');

    Route::get('/permohonan/{id}', function ($id) {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'lurah') abort(403);
        return app(\App\Http\Controllers\Lurah\PermohonanController::class)->show($id);
    })->name('permohonan.detail');

    Route::get('/permohonan/{id}/sign', function ($id) {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'lurah') abort(403);
        return app(\App\Http\Controllers\Lurah\PermohonanController::class)->sign($id);
    })->name('permohonan.sign');

    Route::post('/permohonan/{id}/process', function ($id) {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'lurah') abort(403);
        return app(\App\Http\Controllers\Lurah\PermohonanController::class)->processSign(request(), $id);
    })->name('permohonan.process');
});
// ==================== MASYARAKAT ROUTES ====================
Route::middleware(['auth'])->prefix('masyarakat')->name('masyarakat.')->group(function () {
    // Dashboard dengan manual check
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'masyarakat') abort(403);
        return app(MasyarakatDashboardController::class)->index();
    })->name('dashboard');

    // Permohonan dengan manual check
    Route::get('/permohonan', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'masyarakat') abort(403);
        return app(MasyarakatPermohonanController::class)->index();
    })->name('permohonan.index');

    Route::get('/permohonan/create', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'masyarakat') abort(403);
        return app(MasyarakatPermohonanController::class)->create();
    })->name('permohonan.create');

    Route::post('/permohonan', function () {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'masyarakat') abort(403);
        return app(MasyarakatPermohonanController::class)->store(request());
    })->name('permohonan.store');

    Route::get('/permohonan/{id}', function ($id) {
        $user = Auth::user();
        if (!$user->role || $user->role->name !== 'masyarakat') abort(403);
        return app(MasyarakatPermohonanController::class)->show($id);
    })->name('permohonan.detail');
});

// Temporary debug route
Route::get('/debug-test', function () {
    // Test jika controller bisa diakses
    try {
        $controller = app(\App\Http\Controllers\Lurah\PermohonanController::class);
        echo "✅ Controller bisa diakses<br>";

        // Test method
        if (method_exists($controller, 'processSign')) {
            echo "✅ Method processSign ada<br>";
        } else {
            echo "❌ Method processSign tidak ada<br>";
        }
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }

    // Test route
    echo "Route URL: " . route('lurah.permohonan.process', 1) . "<br>";
});

// ==================== AUTH ROUTES ====================
require __DIR__ . '/auth.php';
