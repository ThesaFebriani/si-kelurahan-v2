<?php

namespace App\Http\Controllers\RT;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use App\Models\Keluarga;
use App\Models\AnggotaKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rtId = $user->rt_id;

        // Stats untuk dashboard
        $stats = [
            'pending_permohonan' => PermohonanSurat::whereHas('user', function ($q) use ($rtId) {
                $q->where('rt_id', $rtId);
            })->where('status', PermohonanSurat::MENUNGGU_RT)->count(),

            'approved_permohonan' => PermohonanSurat::whereHas('user', function ($q) use ($rtId) {
                $q->where('rt_id', $rtId);
            })->where('status', PermohonanSurat::DISETUJUI_RT)->count(),

            'total_keluarga' => Keluarga::where('rt_id', $rtId)->count(),

            'total_warga' => AnggotaKeluarga::whereHas('keluarga', function ($q) use ($rtId) {
                $q->where('rt_id', $rtId);
            })->count(),
        ];

        // Recent permohonan
        $recentPermohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($rtId) {
                $q->where('rt_id', $rtId);
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.rt.dashboard', compact('stats', 'recentPermohonan'));
    }
}
