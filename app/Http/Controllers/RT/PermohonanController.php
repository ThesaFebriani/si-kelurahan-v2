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

        // Hanya tampilkan yang MENUNGGU_RT di halaman utama
        $permohonan = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->where('status', PermohonanSurat::MENUNGGU_RT)
            ->latest()
            ->get();

        // Stats untuk dashboard RT
        // Stats untuk dashboard RT
        // Hitung total semua status untuk statistik dengan query terpisah (Optimasi Memory)
        
        // Base query helper
        $baseQuery = PermohonanSurat::whereHas('user', function ($q) use ($user) {
            $q->where('rt_id', $user->rt_id);
        });

        $stats = [
            'pending' => (clone $baseQuery)->where('status', PermohonanSurat::MENUNGGU_RT)->count(),
            'approved' => (clone $baseQuery)->whereIn('status', [
                PermohonanSurat::MENUNGGU_KASI,
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])->count(),
            'rejected' => (clone $baseQuery)->whereIn('status', [
                PermohonanSurat::DITOLAK_RT,
                PermohonanSurat::DITOLAK_KASI
            ])->count(),
            'total' => (clone $baseQuery)->count(),
        ];

        return view('pages.rt.permohonan.index', compact('permohonan', 'stats'));
    }

    // ... (rest of methods)

    public function arsip(Request $request)
    {
        $user = Auth::user();
        $filterStatus = $request->query('status');

        $query = PermohonanSurat::with(['user', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            });

        if ($filterStatus === 'approved') {
            $query->whereIn('status', [
                PermohonanSurat::MENUNGGU_KASI,
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ]);
        } elseif ($filterStatus === 'rejected') {
            $query->whereIn('status', [
                PermohonanSurat::DITOLAK_RT,
                PermohonanSurat::DITOLAK_KASI
            ]);
        } else {
            // Default: Tampilkan semua yang sudah diproses (Approved & Rejected)
            $query->whereIn('status', [
                PermohonanSurat::MENUNGGU_KASI,
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI,
                PermohonanSurat::DITOLAK_RT,
                PermohonanSurat::DITOLAK_KASI
            ]);
        }

        $permohonan = $query->latest()->get();

        return view('pages.rt.permohonan.arsip', compact('permohonan'));
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

        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->where('status', PermohonanSurat::MENUNGGU_RT)
            ->findOrFail($id);

        // Generate nomor surat otomatis untuk pre-fill
        $pdfService = new PDFGeneratorService();
        $nomorSurat = $pdfService->generateNomorSuratPengantar($user->rt);

        // Default content for editing (LOAD FROM CONTROLLER HELPER WHICH USES DB TEMPLATE)
        $defaultContent = $this->getDefaultSuratPengantarContent($permohonan, $nomorSurat);

        return view('pages.rt.permohonan.approve', compact('permohonan', 'nomorSurat', 'defaultContent'));
    }

    private function getDefaultSuratPengantarContent($permohonan, $nomorSurat = null)
    {
        // 1. Ambil Template Global Khusus Pengantar RT (Satu untuk semua)
        $template = \App\Models\SuratTemplate::where('type', 'pengantar_rt')
            ->whereNull('jenis_surat_id') // Pastikan ambil yang global
            ->whereNull('rt_id')
            ->first();

        // Jika entah kenapa template tidak ada (belum seed), fallback ke pesan error atau default minimal
        if (!$template || empty($template->template_content)) {
            return "<p>Error: Template Surat Pengantar RT belum disetting oleh Admin. Harap hubungi Operator Kelurahan.</p>";
        }
            
        // Use Centralized Service Logic for cleaner code
        $pdfService = new PDFGeneratorService();
        return $pdfService->applyContentVariables($template->template_content, $permohonan, $nomorSurat);
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

        $content = $this->getDefaultSuratPengantarContent($permohonan, $nomorSurat);

        return view('pages.rt.preview-surat-pengantar', compact('permohonan', 'nomorSurat', 'content'));
    }
    public function processApproval(Request $request, $id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user.rt', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->where('status', PermohonanSurat::MENUNGGU_RT)
            ->findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'nomor_surat_pengantar' => [
                'required_if:action,approve',
                'nullable',
                'string',
                'max:100',
                function ($attribute, $value, $fail) use ($request, $id) {
                    if ($request->action === 'approve') {
                        $exists = PermohonanSurat::where('nomor_surat_pengantar_rt', $value)
                            ->where('id', '!=', $id)
                            ->exists();
                        if ($exists) {
                            $fail('Nomor Surat Pengantar ini sudah digunakan oleh permohonan lain.');
                        }
                    }
                },
            ],
            'catatan' => 'nullable|string|max:500',
            'isi_surat' => 'nullable|string', // Allow HTML content
        ]);

        try {
            if ($request->action === 'approve') {
                // Generate surat pengantar RT
                $pdfService = new PDFGeneratorService();
                $suratPengantarPath = $pdfService->generateSuratPengantarRT(
                    $permohonan,
                    $request->nomor_surat_pengantar,
                    $user->rt,
                    // IZINKAN IMG DAN TABLE AGAR FORMAT TIDAK HILANG DAN BASE64 IMAGE BEKERJA
                    strip_tags($request->isi_surat, '<div><p><br><b><i><u><strong><em><ul><ol><li><span><img><table><tbody><tr><td><th><h1><h2><h3><h4><h5><h6>') 
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

                $message = 'Permohonan berhasil disetujui, surat pengantar telah dibuat dan ditandatangani secara digital.';

                // WA Notification
                if ($permohonan->user->telepon) {
                    $waMsg = "*STATUS: DISETUJUI RT* ✅\n\n" .
                             "Yth. Saudara/i *{$permohonan->user->name}*,\n\n" .
                             "Permohonan Anda:\n" .
                             "📄 *{$permohonan->jenisSurat->name}*\n\n" .
                             "Telah *DISETUJUI* oleh Ketua RT dan diteruskan ke Kasi untuk verifikasi.\n\n" .
                             "Mohon menunggu informasi selanjutnya melalui sistem.";
                    \App\Services\WhatsAppService::sendMessage($permohonan->user->telepon, $waMsg);
                }

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

                // WA Notification
                if ($permohonan->user->telepon) {
                    $waMsg = "*STATUS: DITOLAK RT* ❌\n\n" .
                             "Yth. Saudara/i *{$permohonan->user->name}*,\n\n" .
                             "Permohonan Anda:\n" .
                             "📄 *{$permohonan->jenisSurat->name}*\n\n" .
                             "Ditolak oleh Ketua RT dengan catatan:\n" .
                             "_{$request->catatan}_\n\n" .
                             "Silakan perbaiki data dan ajukan kembali.";
                    \App\Services\WhatsAppService::sendMessage($permohonan->user->telepon, $waMsg);
                }
            }

            return redirect()->route('rt.permohonan.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memproses permohonan: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function regenerateSuratPengantar($id)
    {
        $user = Auth::user();
        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->findOrFail($id);

        try {
            // Generate nomor surat jika belum ada
            if (!$permohonan->nomor_surat_pengantar_rt) {
                $pdfService = new PDFGeneratorService();
                $nomorSurat = $pdfService->generateNomorSuratPengantar($user->rt);
                $permohonan->nomor_surat_pengantar_rt = $nomorSurat;
            }

            $pdfService = new PDFGeneratorService();
            // Gunakan default content karena konten asli tidak disimpan di DB jika hilang
            $isiSurat = $this->getDefaultSuratPengantarContent($permohonan);
            
            $suratPengantarPath = $pdfService->generateSuratPengantarRT(
                $permohonan,
                $permohonan->nomor_surat_pengantar_rt,
                $user->rt,
                $isiSurat
            );

            $permohonan->update([
                'file_surat_pengantar_rt' => $suratPengantarPath
            ]);

            return redirect()->back()->with('success', 'Surat Pengantar berhasil digenerate ulang.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal generate ulang: ' . $e->getMessage());
        }
    }
}
