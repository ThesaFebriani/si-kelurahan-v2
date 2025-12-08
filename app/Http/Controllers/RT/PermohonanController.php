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
        // Hitung total semua status untuk statistik
        $allPermohonan = PermohonanSurat::whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })->get();

        $stats = [
            'pending' => $allPermohonan->where('status', PermohonanSurat::MENUNGGU_RT)->count(),
            'approved' => $allPermohonan->whereIn('status', [
                PermohonanSurat::MENUNGGU_KASI,
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])->count(),
            'rejected' => $allPermohonan->whereIn('status', [
                PermohonanSurat::DITOLAK_RT,
                PermohonanSurat::DITOLAK_KASI
            ])->count(),
            'total' => $allPermohonan->count(),
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

        // Default content for editing
        $defaultContent = $this->getDefaultSuratPengantarContent($permohonan);

        return view('pages.rt.permohonan.approve', compact('permohonan', 'nomorSurat', 'defaultContent'));
    }

    private function getDefaultSuratPengantarContent($permohonan)
    {
        // 1. Cek apakah ada Template Khusus untuk Pengantar RT di Database
        $template = \App\Models\SuratTemplate::where('jenis_surat_id', $permohonan->jenis_surat_id)
            ->where('type', 'pengantar_rt')
            ->where('is_active', true)
            ->first();
            
        $user = $permohonan->user;
        $rt = $user->rt;
        $rw = $rt->rw; 
        $dataPemohon = $permohonan->data_pemohon;
        
        // Ensure array
        if (is_string($dataPemohon)) $dataPemohon = json_decode($dataPemohon, true);
        if (!is_array($dataPemohon)) $dataPemohon = [];

        // Jika Template DB Ditemukan, render template tersebut
        if ($template && !empty($template->template_content)) {
            // Render string blade template (sederhana)
            $content = $template->template_content;
            
            // Kita gunakan Blade::render jika Laravel versi baru, atau str_replace manual untuk keamanan/kesederhanaan
            // Karena ini controller, kita replace manual variable-variable umum
            // Catatan: Untuk fitur full blade di DB butuh library khusus atau eval (berbahaya).
            // Disini kita pakai str_replace sederhana sesuai format di seeder.
            
            // Format Tanggal Lahir
            $tglLahirUser = $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') : '-';
            
            $replacements = [
                '{{ $rt->nomor_rt }}' => $rt->nomor_rt,
                '{{ $user->name }}' => $user->name,
                '{{ $user->nik }}' => $user->nik,
                '{{ $user->alamat_lengkap }}' => $user->alamat_lengkap,
                '{!! $user->alamat_lengkap !!}' => $user->alamat_lengkap,
                
                // DATA PROFIL YANG HILANG
                '{{ $user->pekerjaan }}' => $dataPemohon['pekerjaan'] ?? $user->pekerjaan ?? '-',
                '{{ $user->tempat_lahir }}' => $user->tempat_lahir ?? '-',
                '{{ $user->agama }}' => $user->agama ?? '-',
                
                // Data Pemohon Dynamic
                '{{ $data_pemohon[\'nama_usaha\'] ?? \'-\' }}' => $dataPemohon['nama_usaha'] ?? '-',
                '{{ $data_pemohon[\'jenis_usaha\'] ?? \'-\' }}' => $dataPemohon['jenis_usaha'] ?? '-',
                '{{ $data_pemohon[\'alamat_usaha\'] ?? \'-\' }}' => $dataPemohon['alamat_usaha'] ?? '-',
                '{{ $data_pemohon[\'status_tempat_usaha\'] ?? \'-\' }}' => $dataPemohon['status_tempat_usaha'] ?? '-',
                // Fallback umum
                '{{ $data_pemohon[\'tujuan\'] ?? \'-\' }}' => $dataPemohon['tujuan'] ?? '-',
            ];
            
            return strtr($content, $replacements);
        }

        // --- FALLBACK KE DEFAULT HARDCODED (JIKA TIDAK ADA TEMPLATE DB) ---

        // Fallback Logic: robust check for empty strings
        $nik = !empty($dataPemohon['nik']) ? $dataPemohon['nik'] : ($user->nik ?? '-');
        $tempatLahir = !empty($dataPemohon['tempat_lahir']) ? $dataPemohon['tempat_lahir'] : ($user->tempat_lahir ?? '-');
        
        $tglLahir = '-';
        if (!empty($dataPemohon['tanggal_lahir'])) {
             $tglLahir = \Carbon\Carbon::parse($dataPemohon['tanggal_lahir'])->format('d/m/Y');
        } elseif (!empty($user->tanggal_lahir)) {
             $tglLahir = \Carbon\Carbon::parse($user->tanggal_lahir)->format('d/m/Y');
        }

        // Handle Gender Logic
        $jkRaw = !empty($dataPemohon['jenis_kelamin']) ? $dataPemohon['jenis_kelamin'] : 
                (!empty($dataPemohon['jk']) ? $dataPemohon['jk'] : ($user->jk ?? '-'));
        
        $jk = match(strtolower($jkRaw)) {
            'l', 'laki-laki' => 'Laki-laki',
            'p', 'perempuan' => 'Perempuan',
            default => $jkRaw
        };

        $pekerjaan = !empty($dataPemohon['pekerjaan']) ? $dataPemohon['pekerjaan'] : ($user->pekerjaan ?? '-');
        $agama = !empty($dataPemohon['agama']) ? $dataPemohon['agama'] : ($user->agama ?? '-');
        
        // Alamat fallback
        $alamat = !empty($dataPemohon['alamat']) ? $dataPemohon['alamat'] : 
                 ($user->alamat ?? '-');

        $html = '<p>Yang bertanda tangan di bawah ini Ketua RT ' . $rt->nomor_rt . ' Kelurahan Pematang Gubernur, Kecamatan Muara Bangkahulu, Kota Bengkulu, menerangkan bahwa:</p>';

        $html .= '<table style="width: 100%; margin-top: 10px; margin-bottom: 10px;">';
        $html .= '<tr><td style="width: 140px;">Nama</td><td style="width: 10px;">:</td><td><strong>' . $user->name . '</strong></td></tr>';
        $html .= '<tr><td>NIK</td><td>:</td><td>' . $nik . '</td></tr>';
        $html .= '<tr><td>Tempat/Tgl Lahir</td><td>:</td><td>' . $tempatLahir . ', ' . $tglLahir . '</td></tr>';
        $html .= '<tr><td>Jenis Kelamin</td><td>:</td><td>' . $jk . '</td></tr>';
        $html .= '<tr><td>Pekerjaan</td><td>:</td><td>' . $pekerjaan . '</td></tr>';
        $html .= '<tr><td>Agama</td><td>:</td><td>' . $agama . '</td></tr>';
        $html .= '<tr><td>Alamat</td><td>:</td><td>' . $alamat . '</td></tr>';
        $html .= '</table>';

        $rwNumber = $rw ? $rw->nomor_rw : '-';
        $html .= '<p>Orang tersebut diatas adalah benar-benar warga kami yang berdomisili di RT ' . $rt->nomor_rt . ' RW ' . $rwNumber . ' Kelurahan Pematang Gubernur. Surat pengantar ini diberikan untuk keperluan:</p>';

        $html .= '<div style="margin: 10px 0; padding: 10px; border: 1px solid #eee; background: #f9f9f9; font-weight: bold; text-align: center;">' . ($dataPemohon['tujuan'] ?? $permohonan->jenisSurat->name) . '</div>';

        $html .= '<p>Demikian surat pengantar ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>';

        return $html;
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

        $permohonan = PermohonanSurat::with(['user.rt', 'jenisSurat'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('rt_id', $user->rt_id);
            })
            ->where('status', PermohonanSurat::MENUNGGU_RT)
            ->findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'nomor_surat_pengantar' => 'required_if:action,approve|string|max:100',
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
                    $request->isi_surat // Pass edited content
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
