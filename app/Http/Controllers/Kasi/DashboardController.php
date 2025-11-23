<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_permohonan' => PermohonanSurat::where('status', PermohonanSurat::MENUNGGU_KASI)->count(),
            'approved_permohonan' => PermohonanSurat::where('status', PermohonanSurat::DISETUJUI_KASI)->count(),
            'rejected_permohonan' => PermohonanSurat::where('status', PermohonanSurat::DITOLAK_KASI)->count(),
            'total_permohonan' => PermohonanSurat::whereIn('status', [
                PermohonanSurat::MENUNGGU_KASI,
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::DITOLAK_KASI
            ])->count(),
        ];

        $recentPermohonan = PermohonanSurat::with(['user.rt', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_KASI)
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.kasi.dashboard', compact('stats', 'recentPermohonan'));
    }
}
