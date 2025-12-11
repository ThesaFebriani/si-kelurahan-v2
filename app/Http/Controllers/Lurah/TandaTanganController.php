<?php

namespace App\Http\Controllers\Lurah;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use App\Models\ApprovalFlow;
use App\Models\TimelinePermohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PDFGeneratorService;

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
    public function processSign(Request $request, $id)
    {
        $permohonan = PermohonanSurat::with(['surat'])
            ->where('status', PermohonanSurat::MENUNGGU_LURAH)
            ->findOrFail($id);
            
        $request->validate(['passphrase' => 'required']);

        if (!\Illuminate\Support\Facades\Hash::check($request->passphrase, Auth::user()->password)) {
            return redirect()->back()
                ->with('error', 'Passphrase (Password Login) salah. Silakan coba lagi.')
                ->withInput();
        }
        
        try {
            $user = Auth::user();
            $surat = $permohonan->surat;
            
            // Generate PDF Final
            $pdfService = new PDFGeneratorService();
            $path = $pdfService->generateSuratKelurahan($surat, $user->name);
            
            // Update Surat
            $surat->update([
                'file_path' => $path,
                'signed_by' => $user->id,
                'signed_at' => now(),
                'qr_code_data' => $path, // Atau data lain yang relevan
            ]);
            
            // Update Permohonan
            $permohonan->update([
                'status' => PermohonanSurat::SELESAI,
                'tanggal_selesai' => now(),
                'nomor_surat_final' => $surat->nomor_surat,
            ]);
            
            // Log Approval
             ApprovalFlow::create([
                'permohonan_surat_id' => $permohonan->id,
                'step' => ApprovalFlow::STEP_LURAH,
                'status' => ApprovalFlow::STATUS_APPROVED,
                'catatan' => 'Surat ditandatangani secara elektronik',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'urutan' => 3,
            ]);

            TimelinePermohonan::create([
                'permohonan_surat_id' => $permohonan->id,
                'status' => PermohonanSurat::SELESAI,
                'keterangan' => 'Selesai - Surat dapat diunduh',
                'updated_by' => $user->id,
            ]);
            
            return redirect()->route('lurah.tanda-tangan.index')
                ->with('success', 'Surat berhasil ditandatangani dan diterbitkan.');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses tanda tangan: ' . $e->getMessage());
        }
    }
}
