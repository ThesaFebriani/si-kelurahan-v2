<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'permohonan_pending' => PermohonanSurat::whereHas('jenisSurat', function ($q) use ($user) {
                $q->where('bidang', $user->bidang);
            })->where('status', 'menunggu_kasi')->count(),

            'permohonan_verified' => PermohonanSurat::whereHas('jenisSurat', function ($q) use ($user) {
                $q->where('bidang', $user->bidang);
            })->where('status', 'disetujui_kasi')->count(),

            'total_jenis_surat' => JenisSurat::where('bidang', $user->bidang)
                ->where('is_active', true)
                ->count(),
        ];

        return view('pages.kasi.dashboard', compact('stats'));
    }
}
