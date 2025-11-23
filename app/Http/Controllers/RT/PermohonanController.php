<?php

namespace App\Http\Controllers\RT;

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
            'catatan' => 'nullable|string|max:500',
        ]);

        try {
            if ($request->action === 'approve') {
                // Update status permohonan
                $permohonan->update([
                    'status' => PermohonanSurat::MENUNGGU_KASI
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
                    'keterangan' => 'Disetujui RT - ' . ($request->catatan ?: 'Tidak ada catatan'),
                    'updated_by' => $user->id,
                ]);

                $message = 'Permohonan berhasil disetujui dan diteruskan ke Kasi';
            } else {
                // Update status permohonan ditolak
                $permohonan->update([
                    'status' => PermohonanSurat::DITOLAK_RT,
                    'keterangan_tolak' => $request->catatan
                ]);

                // Buat approval flow
                ApprovalFlow::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'step' => ApprovalFlow::STEP_RT,
                    'status' => ApprovalFlow::STATUS_REJECTED,
                    'catatan' => $request->catatan,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'urutan' => 1,
                ]);

                // Buat timeline
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
