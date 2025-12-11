<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PermohonanSurat;
use App\Models\JenisSurat;
use App\Models\Rt;
use App\Models\Rw;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_users' => User::count(),
            'total_permohonan' => PermohonanSurat::count(),
            'permohonan_pending' => PermohonanSurat::where('status', 'menunggu_rt')->count(),
            'permohonan_selesai' => PermohonanSurat::where('status', 'selesai')->count(),
            'permohonan_ditolak' => PermohonanSurat::where('status', 'ditolak_rt')->orWhere('status', 'ditolak_kasi')->count(),
            'jenis_surat' => JenisSurat::where('is_active', true)->count(),
            'total_rt' => Rt::where('is_active', true)->count(),
            'total_rw' => Rw::where('is_active', true)->count(),
            // Manajement Kependudukan
            'total_keluarga' => \App\Models\Keluarga::count(),
            'total_penduduk' => \App\Models\AnggotaKeluarga::count(),
        ];

        $recent_permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->latest()
            ->take(5)
            ->get();

        // Chart data - Permohonan per bulan
        $chart_data = PermohonanSurat::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        return view('pages.admin.dashboard', compact('stats', 'recent_permohonan', 'chart_data'));
    }
}
