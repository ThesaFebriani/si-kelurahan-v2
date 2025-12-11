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
        $defaultContent = $this->getDefaultSuratPengantarContent($permohonan, $nomorSurat);

        return view('pages.rt.permohonan.approve', compact('permohonan', 'nomorSurat', 'defaultContent'));
    }

    private function getDefaultSuratPengantarContent($permohonan, $nomorSurat = null)
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
                '{{ $nomor_surat }}' => '.../RT-.../.../'.date('Y'),
            ];
            
            return strtr($content, $replacements);
        }

        // --- PREPARE DATA ---
        $rwNumber = $rw ? $rw->nomor_rw : '-';
        $rtNumber = $rt->nomor_rt;
        
        $nik = !empty($dataPemohon['nik']) ? $dataPemohon['nik'] : ($user->nik ?? '-');
        $tempatLahir = !empty($dataPemohon['tempat_lahir']) ? $dataPemohon['tempat_lahir'] : ($user->tempat_lahir ?? '-');
        
        $tglLahir = '-';
        if (!empty($dataPemohon['tanggal_lahir'])) {
             $tglLahir = \Carbon\Carbon::parse($dataPemohon['tanggal_lahir'])->isoFormat('D MMMM Y');
        } elseif (!empty($user->tanggal_lahir)) {
             $tglLahir = \Carbon\Carbon::parse($user->tanggal_lahir)->isoFormat('D MMMM Y');
        }

        $jkRaw = !empty($dataPemohon['jenis_kelamin']) ? $dataPemohon['jenis_kelamin'] : 
                (!empty($dataPemohon['jk']) ? $dataPemohon['jk'] : ($user->jk ?? '-'));
        $jk = match(strtolower($jkRaw)) {
            'l', 'laki-laki' => 'Laki-laki',
            'p', 'perempuan' => 'Perempuan',
            default => $jkRaw
        };

        $pekerjaan = !empty($dataPemohon['pekerjaan']) ? $dataPemohon['pekerjaan'] : ($user->pekerjaan ?? '-');
        $agama = !empty($dataPemohon['agama']) ? $dataPemohon['agama'] : ($user->agama ?? '-');
        $statusPerkawinan = !empty($dataPemohon['status_perkawinan']) ? $dataPemohon['status_perkawinan'] : ($user->status_perkawinan ?? '-');
        $alamat = !empty($dataPemohon['alamat']) ? $dataPemohon['alamat'] : ($user->alamat_lengkap ?? '-');
        $namaKepalaKeluarga = $user->keluarga ? $user->keluarga->kepala_keluarga : '-';


        // --- BUILD HTML CONTENT ---
        
        // 1. HEADER / KOP SURAT
        $logoPath = public_path('images/logo-kota-bengkulu.png');
        $logoSrc = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;
        }

        // Adjust logo position and size, slightly bigger and better aligned.
        $html = '
        <div style="text-align: center; position: relative; font-family: \'Times New Roman\', serif; line-height: 1;">
            <img src="' . $logoSrc . '" alt="Logo" style="position: absolute; left: 40px; top: 5px; width: 65px; height: auto;">
            <h2 style="font-size: 14pt; font-weight: bold; margin: 0; text-transform: uppercase;">PEMERINTAH KOTA BENGKULU</h2>
            <h2 style="font-size: 13pt; font-weight: bold; margin: 0; text-transform: uppercase;">KECAMATAN RATU SAMBAN</h2>
            <h2 style="font-size: 13pt; font-weight: bold; margin: 0; text-transform: uppercase;">KELURAHAN PADANG JATI</h2>
            <h3 style="font-size: 11pt; font-weight: bold; margin: 0; text-transform: uppercase;">RUKUN TETANGGA ' . str_pad($rtNumber, 3, '0', STR_PAD_LEFT) . ' RUKUN WARGA ' . str_pad($rwNumber, 3, '0', STR_PAD_LEFT) . '</h3>
            <p style="font-size: 10pt; margin: 2px 0 0 0;">Jl. Jati No. ... Kelurahan Padang Jati Kecamatan Ratu Samban Kota Bengkulu</p>
            <div style="border-top: 1px solid #000; border-bottom: 3px double #000; margin-top: 5px; margin-bottom: 15px; height: 3px;"></div>
        </div>';

        // 2. META INFO (Nomor, Kepada Yth) - Reduced spacing
        $html .= '
        <div style="margin-bottom: 15px; overflow: auto; font-family: \'Times New Roman\', serif; line-height: 1.15;">
            <div style="float: left; width: 60%;">
                <table style="width: 100%;">
                    <tr><td style="width: 80px;">Nomor</td><td style="width: 10px;">:</td><td><span id="nomor-surat-display">' . ($nomorSurat ?? '...v') . '</span></td></tr>
                    <tr><td>Lampiran</td><td>:</td><td>-</td></tr>
                    <tr><td>Perihal</td><td>:</td><td style="text-decoration: underline;">Surat Pengantar</td></tr>
                </table>
            </div>
            <div style="float: right; width: 40%;">
                <p style="margin: 0;">Kepada Yth,</p>
                <p style="margin: 0;">Bapak Lurah</p>
                <p style="margin: 0;">Kepala Kelurahan Padang Jati</p>
                <p style="margin: 0;">Di -</p>
                <p style="text-indent: 20px; margin: 0;">Bengkulu</p>
            </div>
            <div style="clear: both;"></div>
        </div>';

        // 3. BODY CONTENT - Compact Layout for 1 Page
        $html .= '<div style="font-family: \'Times New Roman\', serif; line-height: 1.15; text-align: justify;">';
        $html .= '<p style="margin-bottom: 5px;">Dengan Hormat,</p>';
        $html .= '<p style="text-indent: 40px; margin-bottom: 5px;">Yang bertanda tangan dibawah ini, Ketua RT.' . $rtNumber . '/RW.' . $rwNumber . ' Kelurahan Padang Jati Kecamatan Ratu Samban Kota Bengkulu, dengan ini menerangkan bahwa :</p>';

        $html .= '<table style="width: 100%; margin-top: 5px; margin-bottom: 5px; margin-left: 20px; border-collapse: collapse;">';
        $html .= '<tr><td style="width: 180px; padding: 1px 0;">Nama</td><td style="width: 10px; padding: 1px 0;">:</td><td style="padding: 1px 0;"><strong>' . $user->name . '</strong></td></tr>';
        $html .= '<tr><td style="padding: 1px 0;">Tempat, Tanggal Lahir</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">' . $tempatLahir . ', ' . $tglLahir . '</td></tr>';
        $html .= '<tr><td style="padding: 1px 0;">Jenis Kelamin</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">' . $jk . '</td></tr>';
        $html .= '<tr><td style="padding: 1px 0;">Nama Kepala Keluarga</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">' . $namaKepalaKeluarga . '</td></tr>';
        $html .= '<tr><td style="padding: 1px 0;">Bangsa</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">Indonesia / WNA</td></tr>';
        $html .= '<tr><td style="padding: 1px 0;">Agama</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">' . $agama . '</td></tr>';
        $html .= '<tr><td style="padding: 1px 0;">Status Perkawinan</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">' . $statusPerkawinan . '</td></tr>';
        $html .= '<tr><td style="padding: 1px 0;">Pendidikan Terakhir</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">-</td></tr>';
        $html .= '<tr><td style="padding: 1px 0;">Pekerjaan</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">' . $pekerjaan . '</td></tr>';
        $html .= '<tr><td style="padding: 1px 0;">No. NIK</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">' . $nik . '</td></tr>';
        $html .= '<tr><td style="padding: 1px 0;">Alamat</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">' . $alamat . ' Kelurahan Padang Jati Kecamatan Ratu Samban</td></tr>';
        $html .= '</table>';

        $html .= '<p style="text-indent: 40px; margin-bottom: 5px;">Bahwa nama tersebut diatas adalah benar warga RT.' . $rtNumber . '/RW.' . $rwNumber . ' Kelurahan Padang Jati Kecamatan Ratu Samban dan tercatat dalam Buku Kependudukan. Yang bersangkutan datang menghadap Bapak Lurah untuk mengurus :</p>';

        $html .= '<div style="margin: 5px 0 10px 0; padding: 10px; border: 2px solid #000; min-height: 30px; font-weight: bold; text-align: center;">&nbsp;</div>';

        $html .= '<p style="text-indent: 40px; margin-bottom: 0;">Demikian surat pengantar ini kami buat, untuk mendapatkan penyelesaian selanjutnya dan atas bantuannya diucapkan terima kasih.</p>';
        $html .= '</div>';

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
