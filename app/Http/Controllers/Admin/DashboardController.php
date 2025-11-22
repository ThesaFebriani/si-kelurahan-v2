<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PermohonanSurat;
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
        ];

        return view('pages.admin.dashboard', compact('stats'));
    }
}
