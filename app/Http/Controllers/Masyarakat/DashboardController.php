<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_permohonan' => PermohonanSurat::where('user_id', $user->id)->count(),
            'permohonan_pending' => PermohonanSurat::where('user_id', $user->id)
                ->whereIn('status', ['menunggu_rt', 'menunggu_kasi', 'menunggu_lurah'])
                ->count(),
            'permohonan_selesai' => PermohonanSurat::where('user_id', $user->id)
                ->where('status', 'selesai')
                ->count(),
        ];

        $recent_permohonan = PermohonanSurat::with(['jenisSurat'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.masyarakat.dashboard', compact('stats', 'recent_permohonan'));
    }
}
