<?php

namespace App\Http\Controllers\Kasi;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use App\Models\ApprovalFlow;
use App\Models\TimelinePermohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Surat;
use App\Models\Rt;
use App\Services\PDFGeneratorService;

class PermohonanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Hanya yang menunggu approval Kasi
        // FILTER: Hanya tampilkan surat yang sesuai BIDANG Kasi tersebut
        $permohonan = PermohonanSurat::with(['user.rt', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_KASI)
            ->when($user->bidang, function($q) use ($user) {
                $q->whereHas('jenisSurat', function($sub) use ($user) {
                    $sub->where('bidang', $user->bidang);
                });
            })
            ->latest()
            ->get();

        $stats = $this->getStats();

        return view('pages.kasi.permohonan.index', compact('permohonan', 'stats'));
    }

    public function arsip()
    {
        $user = Auth::user();

        // Yang sudah diproses (Disetujui/Ditolak/Lanjut)
        $permohonan = PermohonanSurat::with(['user.rt', 'jenisSurat'])
            ->whereIn('status', [
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::DITOLAK_KASI,
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])
            ->when($user->bidang, function($q) use ($user) {
                $q->whereHas('jenisSurat', function($sub) use ($user) {
                    $sub->where('bidang', $user->bidang);
                });
            })
            ->latest()
            ->get();

        $stats = $this->getStats();

        return view('pages.kasi.permohonan.arsip', compact('permohonan', 'stats'));
    }

    private function getStats()
    {
        $user = Auth::user();

        // Base Query untuk Kasi (sesuai bidang)
        $baseQuery = PermohonanSurat::query()
            ->when($user->bidang, function($q) use ($user) {
                $q->whereHas('jenisSurat', function($sub) use ($user) {
                    $sub->where('bidang', $user->bidang);
                });
            });

        return [
            'pending' => (clone $baseQuery)->where('status', PermohonanSurat::MENUNGGU_KASI)->count(),
            'approved' => (clone $baseQuery)->whereIn('status', [
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])->count(),
            'rejected' => (clone $baseQuery)->where('status', PermohonanSurat::DITOLAK_KASI)->count(),
            'total' => (clone $baseQuery)->whereIn('status', [
                PermohonanSurat::MENUNGGU_KASI,
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::DITOLAK_KASI,
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])->count(),
        ];
    }
    
    // ... (show, verify, verifyProcess methods unchanged) ...

    public function show($id)
    {
        $user = Auth::user();
        
        $permohonan = PermohonanSurat::with([
            'user.rt.rw',
            'jenisSurat',
            'lampirans',
            'timeline' => function ($q) {
                $q->latest();
            },
            'approvalFlows.approvedBy'
        ])
        ->when($user->bidang, function($q) use ($user) {
            $q->whereHas('jenisSurat', function($sub) use ($user) {
                $sub->where('bidang', $user->bidang);
            });
        })
        ->findOrFail($id);

        return view('pages.kasi.permohonan.detail', compact('permohonan'));
    }

    public function verify($id)
    {
        $user = Auth::user();
        
        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat', 'surat'])
            ->where('status', PermohonanSurat::MENUNGGU_KASI)
            ->when($user->bidang, function($q) use ($user) {
                $q->whereHas('jenisSurat', function($sub) use ($user) {
                    $sub->where('bidang', $user->bidang);
                });
            })
            ->findOrFail($id);

        // Prepare Default Content for Editor (Merged from draft method)
        $defaultContent = $permohonan->surat->isi_surat ?? $this->getDefaultTemplate($permohonan);
        
        // Generate Suggested Number
        $suggestedNomorSurat = $permohonan->surat->nomor_surat ?? null;
        if (!$suggestedNomorSurat) {
             $monthRoman = $this->getRomanMonth(now()->month);
             $year = now()->year;
             $count = \App\Models\Surat::whereYear('created_at', $year)->count() + 1;
             $seq = str_pad($count, 3, '0', STR_PAD_LEFT);
             $suggestedNomorSurat = "{$seq}/KL/{$monthRoman}/{$year}";
        }

        return view('pages.kasi.permohonan.verify', compact('permohonan', 'defaultContent', 'suggestedNomorSurat'));
    }

    public function previewSurat($id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat', 'surat'])
            ->when($user->bidang, function($q) use ($user) {
                $q->whereHas('jenisSurat', function($sub) use ($user) {
                    $sub->where('bidang', $user->bidang);
                });
            })
            ->findOrFail($id);
            
        // Prepare content
        $suratContent = $permohonan->surat->isi_surat ?? '<p class="text-center text-gray-500">Konten surat tidak tersedia</p>';
        
        // If final (SELESAI) and has file, user might prefer download, but this view is for quick HTML preview if available
        // We will assume isi_surat is preserved in DB even after PDF generation

        return view('pages.kasi.permohonan.preview-surat', compact('permohonan', 'suratContent'));
    }

    public function processVerification(Request $request, $id)
    {
        $user = Auth::user();

        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_KASI)
            ->when($user->bidang, function($q) use ($user) {
                $q->whereHas('jenisSurat', function($sub) use ($user) {
                    $sub->where('bidang', $user->bidang);
                });
            })
            ->findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:500',
            // Validation conditional for Approve
            'nomor_surat' => 'required_if:action,approve',
            'isi_surat'   => 'required_if:action,approve',
        ]);

        try {
            if ($request->action === 'approve') {
                
                // 1. Simpan Data Surat (Merged from storeDraft)
                $surat = Surat::updateOrCreate(
                    ['permohonan_surat_id' => $permohonan->id],
                    [
                        'nomor_surat' => $request->nomor_surat,
                        'isi_surat' => $request->isi_surat,
                        'file_path' => 'draft', 
                        'signed_by' => null, 
                    ]
                );

                // 2. Update Status & Flow
                $permohonan->update([
                    'status' => PermohonanSurat::MENUNGGU_LURAH // Langsung ke Lurah
                ]);

                ApprovalFlow::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'step' => ApprovalFlow::STEP_KASI,
                    'status' => ApprovalFlow::STATUS_APPROVED,
                    'catatan' => $request->catatan ?? 'Surat dibuat dan diteruskan ke Lurah',
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'urutan' => 2,
                ]);

                TimelinePermohonan::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'status' => PermohonanSurat::MENUNGGU_LURAH,
                    'keterangan' => 'Disetujui Kasi - Surat Diteruskan ke Lurah',
                    'updated_by' => $user->id,
                ]);

                // WA Notification
                if ($permohonan->user->telepon) {
                    $waMsg = "*STATUS: DISETUJUI KASI* ✅\n\n" .
                             "Yth. Saudara/i *{$permohonan->user->name}*,\n\n" .
                             "Permohonan Anda:\n" .
                             "📄 *{$permohonan->jenisSurat->name}*\n\n" .
                             "Telah diverifikasi oleh Kasi dan diteruskan ke Lurah untuk ditandatangani.\n\n" .
                             "Mohon kesediaannya menunggu.";
                    \App\Services\WhatsAppService::sendMessage($permohonan->user->telepon, $waMsg);
                }

                // --- NOTIFIKASI WHATSAPP KE LURAH ---
                $lurahUsers = \App\Models\User::whereHas('role', function($q) {
                    $q->where('name', 'lurah');
                })->where('status', 'active')->get();

                foreach ($lurahUsers as $lurah) {
                    if ($lurah->telepon) {
                        $msgLurah = "Yth. Pak Lurah ({$lurah->name}),\n\n" .
                                    "Terdapat permohonan surat yang telah diverifikasi Kasi dan MENUNGGU TANDA TANGAN (TTE) Anda:\n" .
                                    "Pemohon: *{$permohonan->user->name}*\n" .
                                    "Jenis Surat: *{$permohonan->jenisSurat->name}*\n\n" .
                                    "Mohon segera cek dashboard Lurah untuk proses penandatanganan.";
                        \App\Services\WhatsAppService::sendMessage($lurah->telepon, $msgLurah);
                    }
                }

                return redirect()->route('kasi.permohonan.index')
                    ->with('success', 'Permohonan disetujui dan Surat telah diteruskan ke Lurah.');

            } else {
                // REJECT LOGIC (Unchanged mostly)
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

                // WA Notification
                if ($permohonan->user->telepon) {
                    $waMsg = "*STATUS: DITOLAK KASI* ❌\n\n" .
                             "Yth. Saudara/i *{$permohonan->user->name}*,\n\n" .
                             "Permohonan Anda:\n" .
                             "📄 *{$permohonan->jenisSurat->name}*\n\n" .
                             "Ditolak setelah verifikasi Kasi dengan catatan:\n" .
                             "_{$request->catatan}_\n\n" .
                             "Mohon lengkapi sesuai catatan tersebut.";
                    \App\Services\WhatsAppService::sendMessage($permohonan->user->telepon, $waMsg);
                }

                return redirect()->route('kasi.permohonan.index')
                    ->with('success', 'Permohonan berhasil ditolak.');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memproses permohonan: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function getDefaultTemplate($permohonan)
    {
        // 1. Cek User & RT Info
        $user = $permohonan->user;
        $rt = $user->rt;
        $rw = $rt->rw; 
        $dataPemohon = $permohonan->data_pemohon;
        // Ensure array
        if (is_string($dataPemohon)) $dataPemohon = json_decode($dataPemohon, true);
        if (!is_array($dataPemohon)) $dataPemohon = [];

        // 2. Data Fallback (Robust)
        $nama = strtoupper($dataPemohon['nama_lengkap'] ?? $user->name ?? '-');
        $ttl = ($dataPemohon['tempat_lahir'] ?? $user->tempat_lahir ?? '-') . ', ' . 
               (isset($dataPemohon['tanggal_lahir']) ? \Carbon\Carbon::parse($dataPemohon['tanggal_lahir'])->isoFormat('D MMMM Y') : 
               ($user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->isoFormat('D MMMM Y') : '-'));
        $jk = $dataPemohon['jenis_kelamin'] ?? $dataPemohon['jk'] ?? $user->jk ?? '-';
        $alamat = $dataPemohon['alamat'] ?? $user->alamat_lengkap ?? '-';
        $pekerjaan = $dataPemohon['pekerjaan'] ?? $user->pekerjaan ?? '-';
        $agama = $dataPemohon['agama'] ?? $user->agama ?? '-';

        // 3. Cek Template DB
        $template = \App\Models\SuratTemplate::where('jenis_surat_id', $permohonan->jenis_surat_id)
            ->where('type', 'surat_kelurahan')
            ->where('is_active', true)
            ->first();

        // 4. Jika Template DB Ada
        if ($template && !empty($template->template_content)) {
            // Prepend Kop Surat only if not present in template
            $content = $template->template_content;
            if (!str_contains($content, 'PEMERINTAH KOTA') && !str_contains($content, 'Area Kop Surat')) {
                $content = $this->getKopSuratHTML() . $content;
            }
            
            // Append Signature (Penutup) ONLY if not present in template
            if (!str_contains($content, '[NAMA_LURAH]') && !str_contains($content, 'NIP.')) {
                 $content .= $this->getPenutupSuratHTML($permohonan);
            }
            
            // --- LOGIC BARU: TAG REPLACEMENT (Centralized) ---
            $pdfService = new PDFGeneratorService();
            // Pass Kasi-specific placeholder format
            $nomorPlaceholder = '... / ... / ... / ' . date('Y');
            return $pdfService->applyContentVariables($content, $permohonan, $nomorPlaceholder);
        }


        // --- FALLBACK OLD HARDCODED LOGIC ---
        // (Tetap biarkan logic lama jika belum ada template di DB)

        $jenisSurat = strtoupper($permohonan->jenisSurat->name ?? '');

        // 1. Ambil Kop Surat (Sama untuk semua)
        $html = $this->getKopSuratHTML();

        // 2. Ambil Judul & Nomor (Sama pola, beda teks)
        $html .= $this->getJudulSuratHTML($permohonan);

        // 3. Ambil Body Surat (Beda-beda tergantung jenis - UPDATE DATA PAKE ROBUST VARIABLES)
        switch ($jenisSurat) {
            case 'SURAT KETERANGAN TIDAK MAMPU':
            case 'SKTM':
                $html .= $this->getBodySKTM($permohonan);
                break;
            
            case 'SURAT KETERANGAN USAHA':
            case 'SKU':
                $html .= $this->getBodySKU($permohonan);
                break;

            case 'SURAT KETERANGAN DOMISILI':
            case 'SKD':
                $html .= $this->getBodyDomisili($permohonan);
                break;
            
            case 'SURAT PENGANTAR KTP':
            case 'SURAT PENGANTAR KK':
                $html .= $this->getBodyPengantarKependudukan($permohonan);
                break;

            default:
                $html .= $this->getBodyDefault($permohonan);
                break;
        }

        // 4. Ambil Tanda Tangan (Sama untuk semua)
        $html .= $this->getPenutupSuratHTML($permohonan);

        return $html;
    }

    // --- HELPER TEMPLATES ---

    private function getKopSuratHTML()
    {
        $path = public_path('images/logo-kota-bengkulu.png');
        $logoSrc = '';
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoSrc = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        return "
        <div style=\"font-family: 'Times New Roman', serif; color: #000; padding: 20px;\">
            <table style=\"width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 25px;\">
                <tr>
                    <td style=\"width: 15%; text-align: center; vertical-align: middle;\">
                        <img src=\"" . $logoSrc . "\" alt=\"Logo\" style=\"height: 90px;\">
                    </td>
                    <td style=\"text-align: center; vertical-align: middle;\">
                        <h3 style=\"margin: 0; font-size: 14pt; font-weight: normal;\">PEMERINTAH KOTA BENGKULU</h3>
                        <h2 style=\"margin: 0; font-size: 16pt; font-weight: bold;\">KECAMATAN RATU SAMBAN</h2>
                        <h1 style=\"margin: 0; font-size: 18pt; font-weight: bold;\">KELURAHAN PADANG JATI</h1>
                        <p style=\"margin: 0; font-size: 10pt; font-style: italic;\">Jl. Jati No. ... Kelurahan Padang Jati Kecamatan Ratu Samban Kota Bengkulu</p>
                    </td>
                </tr>
            </table>
        ";
    }

    private function getJudulSuratHTML($permohonan)
    {
        return "
            <div style=\"text-align: center; margin-bottom: 30px;\">
                <h3 style=\"text-decoration: underline; margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase;\">
                    " . ($permohonan->jenisSurat->name ?? 'SURAT KETERANGAN') . "
                </h3>
                <p style=\"margin: 2px 0 0 0; font-size: 12pt;\">NOMOR: <span id=\"nomor_surat_placeholder\">" . ($permohonan->surat->nomor_surat ?? '... / ... / ... / ' . date('Y')) . "</span></p>
            </div>
        ";
    }

    private function getCommonPemohonData($permohonan)
    {
        $data = $permohonan->data_pemohon ?? [];
        $user = $permohonan->user;
        $penduduk = $user->anggotaKeluarga;

        // Ensure proper array
        if (is_string($data)) $data = json_decode($data, true);
        if (!is_array($data)) $data = [];

        $nama = strtoupper($data['nama_lengkap'] ?? $penduduk->nama_lengkap ?? $user->name ?? '-');
        
        $tmpLahir = $data['tempat_lahir'] ?? $penduduk->tempat_lahir ?? $user->tempat_lahir ?? '-';
        $tglLahirRaw = $data['tanggal_lahir'] ?? $penduduk->tanggal_lahir ?? $user->tanggal_lahir;
        $tglLahir = $tglLahirRaw ? \Carbon\Carbon::parse($tglLahirRaw)->isoFormat('D MMMM Y') : '-';
        
        $ttl = "$tmpLahir, $tglLahir";

        $jk = $data['jenis_kelamin'] ?? $data['jk'] ?? $penduduk->jk ?? $user->jk ?? '-';
        if(in_array(strtolower($jk), ['l', 'laki-laki'])) $jk = 'Laki-laki';
        if(in_array(strtolower($jk), ['p', 'perempuan'])) $jk = 'Perempuan';

        $alamat = $data['alamat'] ?? ($penduduk && $penduduk->keluarga ? $penduduk->keluarga->alamat : ($user->alamat_lengkap ?? '-'));
        $pekerjaan = $data['pekerjaan'] ?? $penduduk->pekerjaan ?? $user->pekerjaan ?? '-';
        $agama = $data['agama'] ?? $penduduk->agama ?? $user->agama ?? '-';

        return "
            <table style=\"width: 100%; margin-left: 40px; margin-bottom: 15px;\">
                <tr><td style=\"width: 200px;\">Nama</td><td>: <strong>" . $nama . "</strong></td></tr>
                <tr><td>Tempat, Tgl Lahir</td><td>: " . $ttl . "</td></tr>
                <tr><td>Jenis Kelamin</td><td>: " . $jk . "</td></tr>
                <tr><td>Pekerjaan</td><td>: " . $pekerjaan . "</td></tr>
                <tr><td>Alamat</td><td>: " . $alamat . "</td></tr>
            </table>
        ";
    }

    private function getRTInfo($permohonan)
    {
        $rt = $permohonan->user->rt ? $permohonan->user->rt->nomor_rt : '-';
        $nomorSuratRt = $permohonan->nomor_surat_pengantar_rt ?? '...';
        return "Berdasarkan Anggota RT " . $rt . " Nomor : " . $nomorSuratRt;
    }

    // BODY SKTM
    private function getBodySKTM($permohonan)
    {
        return "
            <div style=\"font-size: 12pt; line-height: 1.5;\">
                <p>Yang bertanda tangan di bawah ini :</p>
                <table style=\"width: 100%; margin-left: 40px; margin-bottom: 15px;\">
                    <tr><td style=\"width: 200px;\">Nama</td><td>: EDWIN KURNIAWAN, SH</td></tr>
                    <tr><td>NIP</td><td>: 198205272010011004</td></tr>
                    <tr><td>Jabatan</td><td>: Kepala Kelurahan</td></tr>
                </table>

                <p>Menerangkan dengan sesungguhnya bahwa :</p>
                " . $this->getCommonPemohonData($permohonan) . "

                <p style=\"text-align: justify; text-indent: 40px; margin-bottom: 15px;\">
                    " . $this->getRTInfo($permohonan) . " tanggal " . $permohonan->created_at->format('d F Y') . ", bahwa memang benar nama tersebut di atas adalah warga Kelurahan Padang Jati dan tergolong keluarga <strong>TIDAK MAMPU</strong>.
                </p>
                
                <p style=\"text-align: justify; text-indent: 40px; margin-bottom: 15px;\">
                    Surat Keterangan ini diberikan untuk keperluan: <strong>" . ($permohonan->data_pemohon['keperluan'] ?? '[ISI KEPERLUAN]') . "</strong>.
                </p>

                <p style=\"text-align: justify; text-indent: 40px;\">
                    Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
                </p>
            </div>
        ";
    }

    private function getBodySKU($permohonan)
    {
        $namaUsaha = $permohonan->data_pemohon['nama_usaha'] ?? '[NAMA USAHA]';
        $jenisUsaha = $permohonan->data_pemohon['jenis_usaha'] ?? '[JENIS USAHA]';
        $alamatUsaha = $permohonan->data_pemohon['alamat_usaha'] ?? '[ALAMAT USAHA]';

        return "
            <div style=\"font-size: 12pt; line-height: 1.5;\">
                <p>Yang bertanda tangan di bawah ini Lurah Padang Jati menerangkan bahwa :</p>
                " . $this->getCommonPemohonData($permohonan) . "

                <p style=\"text-align: justify; text-indent: 40px; margin-bottom: 15px;\">
                    " . $this->getRTInfo($permohonan) . ", bahwa benar yang bersangkutan di atas mempunyai usaha:
                </p>

                <table style=\"width: 100%; margin-left: 40px; margin-bottom: 15px;\">
                    <tr><td style=\"width: 200px;\">Nama Usaha</td><td>: <strong>" . strtoupper($namaUsaha) . "</strong></td></tr>
                    <tr><td>Jenis Usaha</td><td>: " . $jenisUsaha . "</td></tr>
                    <tr><td>Alamat Usaha</td><td>: " . $alamatUsaha . "</td></tr>
                </table>
                
                <p style=\"text-align: justify; text-indent: 40px; margin-bottom: 15px;\">
                    Demikian Surat Keterangan Usaha ini dibuat untuk keperluan: <strong>" . ($permohonan->data_pemohon['keperluan'] ?? 'Persyaratan Administrasi') . "</strong>.
                </p>
            </div>
        ";
    }

    private function getBodyDomisili($permohonan)
    {
        return "
            <div style=\"font-size: 12pt; line-height: 1.5;\">
                <p>Yang bertanda tangan di bawah ini Lurah Padang Jati menerangkan bahwa :</p>
                " . $this->getCommonPemohonData($permohonan) . "

                <p style=\"text-align: justify; text-indent: 40px; margin-bottom: 15px;\">
                    " . $this->getRTInfo($permohonan) . ", bahwa benar yang bersangkutan adalah Penduduk yang berdomisili di lingkungan RT " . ($permohonan->user->rt->nomor_rt ?? '-') . " Kelurahan Padang Jati, Kecamatan Ratu Samban, Kota Bengkulu.
                </p>
                
                <p style=\"text-align: justify; text-indent: 40px; margin-bottom: 15px;\">
                    Surat Keterangan ini diberikan untuk keperluan: <strong>" . ($permohonan->data_pemohon['keperluan'] ?? 'Administrasi Kependudukan') . "</strong>.
                </p>

                <p style=\"text-align: justify; text-indent: 40px;\">
                    Demikian surat keterangan domisili ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
                </p>
            </div>
        ";
    }

    private function getBodyPengantarKependudukan($permohonan)
    {
        return "
            <div style=\"font-size: 12pt; line-height: 1.5;\">
                <p>Yang bertanda tangan di bawah ini Lurah Padang Jati menerangkan bahwa :</p>
                " . $this->getCommonPemohonData($permohonan) . "

                <p style=\"text-align: justify; text-indent: 40px; margin-bottom: 15px;\">
                    " . $this->getRTInfo($permohonan) . ", bahwa benar nama tersebut di atas adalah warga Kelurahan Padang Jati.
                </p>
                
                <p style=\"text-align: justify; text-indent: 40px; margin-bottom: 15px;\">
                    Surat Pengantar ini diberikan sebagai syarat pengurusan <strong>" . ($permohonan->jenisSurat->name) . "</strong> di Dinas Kependudukan dan Pencatatan Sipil Kota Bengkulu.
                </p>

                <p style=\"text-align: justify; text-indent: 40px;\">
                    Demikian surat pengantar ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
                </p>
            </div>
        ";
    }

    private function getBodyDefault($permohonan)
    {
        return "
            <div style=\"font-size: 12pt; line-height: 1.5;\">
                <p>Yang bertanda tangan di bawah ini :</p>
                <table style=\"width: 100%; margin-left: 40px; margin-bottom: 15px;\">
                    <tr><td style=\"width: 200px;\">Nama</td><td>: EDWIN KURNIAWAN, SH</td></tr>
                    <tr><td>NIP</td><td>: 198205272010011004</td></tr>
                    <tr><td>Jabatan</td><td>: Kepala Kelurahan</td></tr>
                </table>

                <p>Menerangkan dengan sesungguhnya bahwa :</p>
                " . $this->getCommonPemohonData($permohonan) . "

                <p style=\"text-align: justify; text-indent: 40px; margin-bottom: 15px;\">
                    " . $this->getRTInfo($permohonan) . " tanggal " . $permohonan->created_at->format('d F Y') . ", bahwa benar warga tersebut bertempat tinggal dan berdomisili di Kelurahan Padang Jati.
                </p>
                
                <p style=\"text-align: justify; text-indent: 40px; margin-bottom: 15px;\">
                    Surat Keterangan ini dibuat untuk keperluan: <strong>[ISI KEPERLUAN]</strong>.
                </p>

                <p style=\"text-align: justify; text-indent: 40px;\">
                    Demikian surat keterangan ini dibuat untuk dipergunakan semestinya.
                </p>
            </div>
        ";
    }


    private function getPenutupSuratHTML($permohonan)
    {
        // Ambil data Lurah dari DB agar dinamis
        $lurah = \App\Models\User::whereHas('role', function($q){ $q->where('name', 'lurah'); })->first();
        $namaLurah = $lurah ? $lurah->name : 'EDWIN KURNIAWAN, SH';
        $nipLurah = $lurah ? $lurah->nip : '198205272010011004';
        
        return "
             <!-- TANDA TANGAN -->
            <div style=\"margin-top: 50px; float: right; width: 45%; text-align: center; font-size: 12pt;\">
                <p>Bengkulu, " . \Carbon\Carbon::now()->isoFormat('D MMMM Y') . "</p>
                <p style=\"margin-bottom: 60px;\">LURAH PEMATANG GUBERNUR</p>
                
                <p style=\"font-weight: bold; text-decoration: underline;\">" . strtoupper($namaLurah) . "</p>
                <p>NIP. " . $nipLurah . "</p>
            </div>
            
            <div style=\"clear: both;\"></div>
        </div>"; // Close main div
    }

    private function getRomanMonth($month)
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romans[$month] ?? 'I';
    }
}
