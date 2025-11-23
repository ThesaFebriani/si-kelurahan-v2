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
            ->where('status', PermohonanSurat::MENUNGGU_LURAH)
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


    private function generateSuratContent($permohonan)
    {
        $dataPemohon = $permohonan->data_pemohon;

        $content = "SURAT {$permohonan->jenisSurat->name}\n\n";
        $content .= "Yang bertanda tangan di bawah ini:\n\n";
        $content .= "Nama: {$permohonan->user->name}\n";
        $content .= "NIK: " . ($dataPemohon['nik'] ?? '-') . "\n";
        $content .= "Tempat/Tanggal Lahir: " . ($dataPemohon['tempat_lahir'] ?? '-') . ", " .
            (isset($dataPemohon['tanggal_lahir']) ? \Carbon\Carbon::parse($dataPemohon['tanggal_lahir'])->format('d/m/Y') : '-') . "\n";
        $content .= "Alamat: " . ($dataPemohon['alamat'] ?? $permohonan->user->alamat_lengkap ?? '-') . "\n\n";

        if (!empty($dataPemohon['tujuan'])) {
            $content .= "Dengan ini menerangkan bahwa pemohon membutuhkan surat ini untuk: {$dataPemohon['tujuan']}\n\n";
        }

        $content .= "Demikian surat ini dibuat untuk dapat dipergunakan sebagaimana mestinya.\n\n";
        $content .= "Hormat kami,\n";
        $content .= "LURAH\n\n";
        $content .= "Tanda Tangan Digital\n";
        $content .= "Ditandatangani secara elektronik oleh: " . Auth::user()->name . "\n";
        $content .= "Pada: " . now()->format('d F Y H:i:s');

        return $content;
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
                // ... kode approve yang sudah ada
                Log::info('Surat approved successfully');
                $message = 'Permohonan telah disetujui';
            } else {
                // ... kode reject yang sudah ada
                Log::info('Surat rejected');
                $message = 'Permohonan telah ditolak';
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
