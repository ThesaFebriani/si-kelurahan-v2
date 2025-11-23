<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PermohonanSurat;
use App\Models\JenisSurat;
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
            'jenis_surat' => JenisSurat::where('is_active', true)->count(),
        ];

        $recent_permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->latest()
            ->take(5)
            ->get();

        // Return view dengan data
        return view('pages.admin.dashboard', compact('stats', 'recent_permohonan'));
    }
}
