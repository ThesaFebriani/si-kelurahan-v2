<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use App\Models\ApprovalFlow;
use App\Models\TimelinePermohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    public function index()
    {
        $permohonan = PermohonanSurat::with(['user.rt', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_KASI)
            ->latest()
            ->get();

        $stats = [
            'pending' => PermohonanSurat::where('status', PermohonanSurat::MENUNGGU_KASI)->count(),
            'approved' => PermohonanSurat::where('status', PermohonanSurat::DISETUJUI_KASI)->count(),
            'rejected' => PermohonanSurat::where('status', PermohonanSurat::DITOLAK_KASI)->count(),
            'total' => PermohonanSurat::whereIn('status', [
                PermohonanSurat::MENUNGGU_KASI,
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::DITOLAK_KASI
            ])->count(),
        ];

        return view('pages.kasi.permohonan.index', compact('permohonan', 'stats'));
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
            ->where('status', PermohonanSurat::MENUNGGU_KASI)
            ->findOrFail($id);

        return view('pages.kasi.permohonan.detail', compact('permohonan'));
    }

    public function verify($id)
    {
        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_KASI)
            ->findOrFail($id);

        return view('pages.kasi.permohonan.verify', compact('permohonan'));
    }

    public function processVerification(Request $request, $id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_KASI)
            ->findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            if ($request->action === 'approve') {
                $permohonan->update([
                    'status' => PermohonanSurat::MENUNGGU_LURAH
                ]);

                ApprovalFlow::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'step' => ApprovalFlow::STEP_KASI,
                    'status' => ApprovalFlow::STATUS_APPROVED,
                    'catatan' => $request->catatan,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'urutan' => 2,
                ]);

                TimelinePermohonan::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'status' => PermohonanSurat::MENUNGGU_LURAH,
                    'keterangan' => 'Disetujui Kasi - ' . ($request->catatan ?: 'Tidak ada catatan'),
                    'updated_by' => $user->id,
                ]);

                $message = 'Permohonan berhasil disetujui dan diteruskan ke Lurah';
            } else {
                $permohonan->update([
                    'status' => PermohonanSurat::DITOLAK_KASI,
                    'keterangan_tolak' => $request->catatan
                ]);

                ApprovalFlow::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'step' => ApprovalFlow::STEP_KASI,
                    'status' => ApprovalFlow::STATUS_REJECTED,
                    'catatan' => $request->catatan,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'urutan' => 2,
                ]);

                TimelinePermohonan::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'status' => PermohonanSurat::DITOLAK_KASI,
                    'keterangan' => 'Ditolak Kasi - ' . $request->catatan,
                    'updated_by' => $user->id,
                ]);

                $message = 'Permohonan berhasil ditolak';
            }

            return redirect()->route('kasi.permohonan.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memproses permohonan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
