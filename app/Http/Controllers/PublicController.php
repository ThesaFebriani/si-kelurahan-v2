<?php

namespace App\Http\Controllers;

use App\Models\PermohonanSurat;
use App\Models\Surat;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Verify Final Surat (Lurah) by Nomor Surat
     */
    public function verifySurat($nomorSurat)
    {
        // Decode slash if encoded (usually browser handles this, but careful with routing)
        $nomorSurat = urldecode($nomorSurat);
        
        $surat = Surat::where('nomor_surat', $nomorSurat)
            ->with(['permohonan.user', 'permohonan.jenisSurat'])
            ->first();

        // Fallback search with replaced slashes if not found (Handle different encoding scenarios)
        if (!$surat) {
            $altNomor = str_replace(['/', '-'], ['-', '/'], $nomorSurat);
            // This is a naive toggle, logic: if input has /, try -, if input has -, try /. 
            // Better: strip non-alphanumeric and compare? For now, trust urldecode.
        }

        // Get Agency Profile for Header
        $settings = SystemSetting::pluck('value', 'key');

        return view('pages.public.verification', [
            'isValid' => $surat ? true : false,
            'type' => 'Surat Kelurahan',
            'data' => $surat,
            'nomor' => $nomorSurat,
            'settings' => $settings
        ]);
    }

    /**
     * View Original Surat File (Public Access via Verification)
     */
    public function viewSurat($nomorSurat)
    {
        $nomorSurat = urldecode($nomorSurat);
        $surat = Surat::where('nomor_surat', $nomorSurat)->firstOrFail();
        
        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($surat->file_path)) {
            abort(404, 'File fisik tidak ditemukan.');
        }

        return \Illuminate\Support\Facades\Storage::disk('local')->response($surat->file_path);
    }

    /**
     * Verify Surat Pengantar RT by ID
     */
    public function verifyPengantar($id)
    {
        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])->find($id);
        $settings = SystemSetting::pluck('value', 'key');

        $isValid = $permohonan && 
                   in_array($permohonan->status, [
                       PermohonanSurat::DISETUJUI_RT, 
                       PermohonanSurat::MENUNGGU_KASI, 
                       PermohonanSurat::DISETUJUI_KASI,
                       PermohonanSurat::MENUNGGU_LURAH,
                       PermohonanSurat::SELESAI
                   ]);

        return view('pages.public.verification', [
            'isValid' => $isValid,
            'type' => 'Surat Pengantar RT',
            'data' => $permohonan,
            'nomor' => $permohonan ? $permohonan->nomor_surat_pengantar_rt : '-',
            'settings' => $settings
        ]);
    }

    /**
     * View Original Surat Pengantar File (Public Access via Verification)
     */
    public function viewPengantar($id)
    {
        $permohonan = PermohonanSurat::findOrFail($id);
        
        if (!$permohonan->file_surat_pengantar_rt || !\Illuminate\Support\Facades\Storage::disk('local')->exists($permohonan->file_surat_pengantar_rt)) {
             abort(404, 'File fisik tidak ditemukan.');
        }

        return \Illuminate\Support\Facades\Storage::disk('local')->response($permohonan->file_surat_pengantar_rt);
    }

    /**
     * Public FAQ Page
     */
    public function faq()
    {
        $faqs = \App\Models\Faq::where('is_published', true)
            ->latest()
            ->get()
            ->groupBy('category');
            
        return view('pages.public.faq', compact('faqs'));
    }
}
