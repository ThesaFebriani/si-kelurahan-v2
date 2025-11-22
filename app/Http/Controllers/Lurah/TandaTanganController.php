<?php

namespace App\Http\Controllers\Lurah;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;

class TandaTanganController extends Controller
{
    public function index()
    {
        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->where('status', 'menunggu_lurah')
            ->latest()
            ->get();

        return view('pages.lurah.tanda-tangan.index', compact('permohonan'));
    }

    public function sign($id)
    {
        $permohonan = PermohonanSurat::with(['user', 'jenisSurat', 'surat'])
            ->where('status', 'menunggu_lurah')
            ->findOrFail($id);

        return view('pages.lurah.tanda-tangan.sign', compact('permohonan'));
    }
}
