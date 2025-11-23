<?php

namespace App\Http\Controllers\Lurah;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending_permohonan' => PermohonanSurat::where('status', PermohonanSurat::MENUNGGU_LURAH)->count(),
            'completed_permohonan' => PermohonanSurat::where('status', PermohonanSurat::SELESAI)->count(),
            'total_permohonan' => PermohonanSurat::whereIn('status', [
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])->count(),
        ];

        $recentPermohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_LURAH)
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.lurah.dashboard', compact('stats', 'recentPermohonan'));
    }
}
