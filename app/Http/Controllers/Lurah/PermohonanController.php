<?php

namespace App\Http\Controllers\Lurah;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;

class PermohonanController extends Controller
{
    public function index()
    {
        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereIn('status', ['menunggu_lurah', 'selesai'])
            ->latest()
            ->get();

        return view('pages.lurah.permohonan.index', compact('permohonan'));
    }

    public function show($id)
    {
        $permohonan = PermohonanSurat::with(['user', 'jenisSurat', 'lampirans', 'timeline', 'approvalFlows', 'surat'])
            ->findOrFail($id);

        return view('pages.lurah.permohonan.detail', compact('permohonan'));
    }
}
