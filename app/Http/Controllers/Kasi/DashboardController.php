<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Helper query agar tidak berulang
        $baseQuery = PermohonanSurat::query()
            ->when($user->bidang, function($q) use ($user) {
                $q->whereHas('jenisSurat', function($sub) use ($user) {
                     $sub->where('bidang', $user->bidang);
                });
            });

        $stats = [
            'pending_permohonan' => (clone $baseQuery)->where('status', PermohonanSurat::MENUNGGU_KASI)->count(),
            'approved_permohonan' => (clone $baseQuery)->where('status', PermohonanSurat::DISETUJUI_KASI)->count(),
            'rejected_permohonan' => (clone $baseQuery)->where('status', PermohonanSurat::DITOLAK_KASI)->count(),
            'total_permohonan' => (clone $baseQuery)->whereIn('status', [
                PermohonanSurat::MENUNGGU_KASI,
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::DITOLAK_KASI
            ])->count(),
        ];

        $recentPermohonan = (clone $baseQuery)->with(['user.rt', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_KASI)
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.kasi.dashboard', compact('stats', 'recentPermohonan'));
    }
}
