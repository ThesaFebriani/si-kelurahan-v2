<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use Illuminate\Http\Request;

class SuratGeneratorController extends Controller
{
    public function generatePDF($id)
    {
        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])->findOrFail($id);

        // Logic untuk generate PDF akan diisi nanti
        return response()->json([
            'message' => 'PDF generated successfully',
            'permohonan_id' => $id
        ]);
    }

    public function previewSurat($id)
    {
        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])->findOrFail($id);

        // Logic untuk preview surat
        return view('templates.surat.preview', compact('permohonan'));
    }

    public function downloadSurat($id)
    {
        $permohonan = PermohonanSurat::with(['user', 'jenisSurat', 'surat'])->findOrFail($id);

        // Logic untuk download surat
        return response()->json([
            'message' => 'Download surat',
            'permohonan_id' => $id
        ]);
    }
}
