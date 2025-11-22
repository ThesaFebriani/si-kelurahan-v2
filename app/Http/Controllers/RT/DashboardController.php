<?php

namespace App\Http\Controllers\RT;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use App\Models\Keluarga;
use App\Models\AnggotaKeluarga;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'permohonan_pending' => PermohonanSurat::whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })->where('status', 'menunggu_rt')->count(),

            'permohonan_disetujui' => PermohonanSurat::whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })->where('status', 'disetujui_rt')->count(),

            'total_keluarga' => Keluarga::where('rt_id', $user->rt_id)->count(),

            'total_penduduk' => AnggotaKeluarga::whereHas('keluarga', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })->count(),
        ];

        return view('pages.rt.dashboard', compact('stats'));
    }
}
