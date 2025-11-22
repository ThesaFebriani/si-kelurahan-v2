<?php

namespace App\Http\Controllers\Lurah;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'permohonan_pending' => PermohonanSurat::where('status', 'menunggu_lurah')->count(),
            'permohonan_selesai' => PermohonanSurat::where('status', 'selesai')->count(),
        ];

        return view('pages.lurah.dashboard', compact('stats'));
    }
}
