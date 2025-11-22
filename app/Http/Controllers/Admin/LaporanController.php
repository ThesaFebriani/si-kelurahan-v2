<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;

class LaporanController extends Controller
{
    public function permohonan()
    {
        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->latest()
            ->get();

        return view('pages.admin.laporan.permohonan', compact('permohonan'));
    }

    public function kinerja()
    {
        // Logic untuk laporan kinerja
        return view('pages.admin.laporan.kinerja');
    }
}
