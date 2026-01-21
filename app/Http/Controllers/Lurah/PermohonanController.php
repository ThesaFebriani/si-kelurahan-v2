<?php

namespace App\Http\Controllers\Lurah;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use App\Models\ApprovalFlow;
use App\Models\TimelinePermohonan;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermohonanController extends Controller
{
    public function index()
    {
        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_LURAH)
            ->latest()
            ->get();

        $stats = [
            'pending' => PermohonanSurat::where('status', PermohonanSurat::MENUNGGU_LURAH)->count(),
            'completed' => PermohonanSurat::where('status', PermohonanSurat::SELESAI)->count(),
            'total' => PermohonanSurat::whereIn('status', [
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])->count(),
        ];

        return view('pages.lurah.permohonan.index', compact('permohonan', 'stats'));
    }

    public function arsip()
    {
        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat', 'surat'])
            ->where('status', PermohonanSurat::SELESAI)
            ->latest()
            ->get();

        $stats = [
            'pending' => PermohonanSurat::where('status', PermohonanSurat::MENUNGGU_LURAH)->count(),
            'completed' => PermohonanSurat::where('status', PermohonanSurat::SELESAI)->count(),
            'total' => PermohonanSurat::whereIn('status', [
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])->count(),
        ];

        return view('pages.lurah.permohonan.arsip', compact('permohonan', 'stats'));
    }

    public function show($id)
    {
        $permohonan = PermohonanSurat::with([
            'user.rt.rw',
            'jenisSurat',
            'lampirans',
            'timeline' => function ($q) {
                $q->latest();
            },
            'approvalFlows.approvedBy'
        ])
            ->whereIn('status', [
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])
            ->findOrFail($id);

        return view('pages.lurah.permohonan.detail', compact('permohonan'));
    }

    public function sign($id)
    {
        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_LURAH)
            ->findOrFail($id);

        return view('pages.lurah.permohonan.sign', compact('permohonan'));
    }



    public function processSign(Request $request, $id)
    {
        Log::info('=== PROCESS SIGN STARTED ===');
        Log::info('Permohonan ID: ' . $id);
        Log::info('User ID: ' . Auth::id());
        Log::info('Request Data: ', $request->all());

        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_LURAH)
            ->findOrFail($id);

        Log::info('Permohonan found: ' . $permohonan->id);

        // Validasi
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:500',
            'nomor_surat' => 'required_if:action,approve|string|max:100',
            'tanggal_surat' => 'required_if:action,approve|date',
        ]);

        try {
            Log::info('Processing action: ' . $request->action);

            if ($request->action === 'approve') {
                $surat = $permohonan->surat;
                
                // Verifikasi Kasi sudah membuat Draft
                if (!$surat) {
                    throw new \Exception('Draft Surat belum dibuat oleh Kasi.');
                }
                
                // Update Tanggal Surat jika diubah Lurah
                if ($request->filled('tanggal_surat')) {
                     // Kita tidak update isi_html karena tanggal ada di placeholder [TANGGAL_SURAT]
                     // Tapi jika Kasi sudah replace jd hardcoded text, maka repot.
                     // Asumsi: Kita pakai tanggal hari ini (TTE).
                }

                // Generate Final PDF with QR Code
                $pdfService = new \App\Services\PDFGeneratorService();
                $finalPath = $pdfService->generateSuratKelurahan($surat, $user->name);

                // Update Surat Record
                $surat->update([
                    'file_path' => $finalPath,
                    'signed_by' => $user->id,
                    'signed_at' => now(),
                    // 'nomor_surat' => $request->nomor_surat ?? $surat->nomor_surat // Use request if allowed to edit
                ]);

                // Update Permohonan Status
                $permohonan->update([
                    'status' => PermohonanSurat::SELESAI,
                    'tanggal_selesai' => now()
                ]);

                // Create Approval Flow
                ApprovalFlow::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'step' => ApprovalFlow::STEP_LURAH,
                    'status' => ApprovalFlow::STATUS_APPROVED,
                    'catatan' => $request->catatan,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'urutan' => 3 
                ]);

                // Timeline
                TimelinePermohonan::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'status' => PermohonanSurat::SELESAI,
                    'keterangan' => 'Surat Selesai - Ditandatangani secara Elektronik oleh Lurah',
                    'updated_by' => $user->id,
                ]);
                
                Log::info('Surat signed and generated: ' . $finalPath);
                $message = 'Dokumen berhasil ditandatangani dan diterbitkan.';

                // WA Notification (Selesai)
            if ($permohonan->user->telepon) {
                $waMsg = "*STATUS: SELESAI* 🎉\n\n" .
                         "Yth. Saudara/i *{$permohonan->user->name}*,\n\n" .
                         "Permohonan Anda:\n" .
                         "📄 *{$permohonan->jenisSurat->name}*\n\n" .
                         "Telah *SELESAI* dan ditandatangani Elektronik (TTE) oleh Lurah.\n\n" .
                         "Silakan unduh dokumen melalui Dashboard Warga.";
                \App\Services\WhatsAppService::sendMessage($permohonan->user->telepon, $waMsg);
            }
            } else {
                // REJECT Logic
                $permohonan->update([
                    'status' => PermohonanSurat::DITOLAK_LURAH, // Or back to Kasi? Usually RejectFinal or BackToKasi
                    'keterangan_tolak' => $request->catatan
                ]);
                
                 ApprovalFlow::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'step' => ApprovalFlow::STEP_LURAH,
                    'status' => ApprovalFlow::STATUS_REJECTED,
                    'catatan' => $request->catatan,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'urutan' => 3
                ]);

                TimelinePermohonan::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'status' => PermohonanSurat::DITOLAK_LURAH,
                    'keterangan' => 'Ditolak Lurah - ' . $request->catatan,
                    'updated_by' => $user->id,
                ]);

                Log::info('Surat rejected by Lurah');
                $message = 'Permohonan ditolak oleh Lurah.';

                // WA Notification
                if ($permohonan->user->telepon) {
                    $waMsg = "*STATUS: DITOLAK LURAH* ❌\n\n" .
                             "Yth. Saudara/i *{$permohonan->user->name}*,\n\n" .
                             "Permohonan Anda:\n" .
                             "📄 *{$permohonan->jenisSurat->name}*\n\n" .
                             "Tidak disetujui oleh Lurah dengan catatan:\n" .
                             "_{$request->catatan}_\n\n" .
                             "Silakan hubungi Kelurahan untuk info lanjut.";
                    \App\Services\WhatsAppService::sendMessage($permohonan->user->telepon, $waMsg);
                }
            }

            Log::info('=== PROCESS SIGN COMPLETED ===');
            return redirect()->route('lurah.permohonan.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error in processSign: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Gagal memproses permohonan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
