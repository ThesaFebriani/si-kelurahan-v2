<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereHas('jenisSurat', function ($q) use ($user) {
                $q->where('bidang', $user->bidang);
            })
            ->whereIn('status', ['menunggu_kasi', 'disetujui_kasi', 'ditolak_kasi'])
            ->latest()
            ->get();

        return view('pages.kasi.permohonan.index', compact('permohonan'));
    }

    public function show($id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user', 'jenisSurat', 'lampirans', 'timeline', 'approvalFlows'])
            ->whereHas('jenisSurat', function ($q) use ($user) {
                $q->where('bidang', $user->bidang);
            })
            ->findOrFail($id);

        return view('pages.kasi.permohonan.detail', compact('permohonan'));
    }

    public function verify($id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereHas('jenisSurat', function ($q) use ($user) {
                $q->where('bidang', $user->bidang);
            })
            ->where('status', 'menunggu_kasi')
            ->findOrFail($id);

        return view('pages.kasi.permohonan.verify', compact('permohonan'));
    }
}
