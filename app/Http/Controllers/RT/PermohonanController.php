<?php

namespace App\Http\Controllers\RT;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use App\Models\ApprovalFlow;
use App\Models\TimelinePermohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PDFGeneratorService;

class PermohonanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->latest()
            ->get();

        // Stats untuk dashboard RT
        $stats = [
            'pending' => $permohonan->where('status', 'menunggu_rt')->count(),
            'approved' => $permohonan->where('status', 'disetujui_rt')->count(),
            'rejected' => $permohonan->whereIn('status', ['ditolak_rt', 'ditolak_kasi'])->count(),
            'total' => $permohonan->count(),
        ];

        return view('pages.rt.permohonan.index', compact('permohonan', 'stats'));
    }

    public function show($id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with([
            'user',
            'jenisSurat',
            'lampirans',
            'timeline' => function ($q) {
                $q->latest();
            },
            'approvalFlows.approvedBy'
        ])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->findOrFail($id);

        return view('pages.rt.permohonan.detail', compact('permohonan'));
    }

    public function approve($id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->where('status', PermohonanSurat::MENUNGGU_RT)
            ->findOrFail($id);

        return view('pages.rt.permohonan.approve', compact('permohonan'));
    }


    public function previewSuratPengantar($id)
    {
        $user = Auth::user();
        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->where('status', PermohonanSurat::MENUNGGU_RT)
            ->findOrFail($id);

        // Generate nomor surat otomatis
        $pdfService = new PDFGeneratorService();
        $nomorSurat = $pdfService->generateNomorSuratPengantar($user->rt);

        return view('pages.rt.preview-surat-pengantar', compact('permohonan', 'nomorSurat'));
    }
    public function processApproval(Request $request, $id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->where('status', PermohonanSurat::MENUNGGU_RT)
            ->findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'nomor_surat_pengantar' => 'required_if:action,approve|string|max:100',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            if ($request->action === 'approve') {
                // Generate surat pengantar RT
                $pdfService = new PDFGeneratorService();
                $suratPengantarPath = $pdfService->generateSuratPengantarRT(
                    $permohonan,
                    $request->nomor_surat_pengantar,
                    $user->rt
                );

                // Update permohonan
                $permohonan->update([
                    'status' => PermohonanSurat::MENUNGGU_KASI,
                    'file_surat_pengantar_rt' => $suratPengantarPath,
                    'nomor_surat_pengantar_rt' => $request->nomor_surat_pengantar,
                ]);

                // Buat approval flow
                ApprovalFlow::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'step' => ApprovalFlow::STEP_RT,
                    'status' => ApprovalFlow::STATUS_APPROVED,
                    'catatan' => $request->catatan,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'urutan' => 1,
                ]);

                // Buat timeline
                TimelinePermohonan::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'status' => PermohonanSurat::MENUNGGU_KASI,
                    'keterangan' => 'Disetujui RT dengan nomor pengantar: ' . $request->nomor_surat_pengantar,
                    'updated_by' => $user->id,
                ]);

                $message = 'Permohonan berhasil disetujui dan surat pengantar telah digenerate';
            } else {
                // Reject logic
                $permohonan->update([
                    'status' => PermohonanSurat::DITOLAK_RT,
                    'keterangan_tolak' => $request->catatan
                ]);

                ApprovalFlow::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'step' => ApprovalFlow::STEP_RT,
                    'status' => ApprovalFlow::STATUS_REJECTED,
                    'catatan' => $request->catatan,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'urutan' => 1,
                ]);

                TimelinePermohonan::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'status' => PermohonanSurat::DITOLAK_RT,
                    'keterangan' => 'Ditolak RT - ' . $request->catatan,
                    'updated_by' => $user->id,
                ]);

                $message = 'Permohonan berhasil ditolak';
            }

            return redirect()->route('rt.permohonan.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memproses permohonan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
