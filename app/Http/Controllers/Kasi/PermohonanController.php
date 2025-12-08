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
        // Hanya yang menunggu approval Kasi
        $permohonan = PermohonanSurat::with(['user.rt', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_KASI)
            ->latest()
            ->get();

        $stats = $this->getStats();

        return view('pages.kasi.permohonan.index', compact('permohonan', 'stats'));
    }

    public function arsip()
    {
        // Yang sudah diproses (Disetujui/Ditolak/Lanjut)
        $permohonan = PermohonanSurat::with(['user.rt', 'jenisSurat'])
            ->whereIn('status', [
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::DITOLAK_KASI,
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])
            ->latest()
            ->get();

        $stats = $this->getStats();

        return view('pages.kasi.permohonan.arsip', compact('permohonan', 'stats'));
    }

    private function getStats()
    {
        return [
            'pending' => PermohonanSurat::where('status', PermohonanSurat::MENUNGGU_KASI)->count(),
            'approved' => PermohonanSurat::whereIn('status', [
                PermohonanSurat::DISETUJUI_KASI,
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])->count(),
            'rejected' => PermohonanSurat::where('status', PermohonanSurat::DITOLAK_KASI)->count(),
            'total' => PermohonanSurat::whereIn('status', [
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
        $permohonan = PermohonanSurat::with([
            'user.rt.rw',
            'jenisSurat',
            'lampirans',
            'timeline' => function ($q) {
                $q->latest();
            },
            'approvalFlows.approvedBy'
        ])->findOrFail($id);

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
                    'status' => PermohonanSurat::DISETUJUI_KASI
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
                    'status' => PermohonanSurat::DISETUJUI_KASI,
                    'keterangan' => 'Disetujui Kasi - ' . ($request->catatan ?: 'Tidak ada catatan'),
                    'updated_by' => $user->id,
                ]);

                // Redirect ke halaman buat surat (draft) setelah disetujui
                return redirect()->route('kasi.permohonan.draft', $permohonan->id)
                    ->with('success', 'Permohonan disetujui. Silakan buat draft surat.');
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

    public function draft($id)
    {
        // Method ini hanya untuk MEMBUAT/MENGEDIT draft (untuk status MENUNGGU_KASI)
        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat', 'surat'])
            ->whereIn('status', [
                 PermohonanSurat::MENUNGGU_KASI,
                 PermohonanSurat::DISETUJUI_KASI // Tambahkan ini agar bisa diakses setelah approve
            ])
            ->findOrFail($id);
            
        // Jika belum ada surat, buat dummy object atau ambil template default
        $draftContent = $permohonan->surat->isi_surat ?? $this->getDefaultTemplate($permohonan);
        
        return view('pages.kasi.permohonan.draft', compact('permohonan', 'draftContent'));
    }

    public function previewSurat($id)
    {
        // Method ini untuk MELIHAT surat yang sudah dibuat (Read Only)
        $permohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat', 'surat'])
            ->whereIn('status', [
                PermohonanSurat::MENUNGGU_LURAH, 
                PermohonanSurat::SELESAI
            ])
            ->findOrFail($id);

        // Jika status sudah selesai dan ada filenya, redirect ke file PDF final
        if ($permohonan->status == PermohonanSurat::SELESAI && $permohonan->nomor_surat_final) {
             // Logic view PDF final (akan dihandle di view baru atau return file response)
             // Sementara return view preview yang sama tapi dengan info "Final"
        }

        $suratContent = $permohonan->surat->isi_surat ?? 'Konten surat belum tersedia.';
        return view('pages.kasi.permohonan.preview-surat', compact('permohonan', 'suratContent'));
    }

    public function storeDraft(Request $request, $id)
    {
        $permohonan = PermohonanSurat::with(['user.rt'])
            ->whereIn('status', [
                PermohonanSurat::MENUNGGU_KASI,
                PermohonanSurat::DISETUJUI_KASI
            ])
            ->findOrFail($id);

        $request->validate([
            'isi_surat' => 'required|string',
            'nomor_surat' => 'required|string',
        ]);

        // Simpan atau update surat
        $surat = Surat::updateOrCreate(
            ['permohonan_surat_id' => $permohonan->id],
            [
                'nomor_surat' => $request->nomor_surat,
                'isi_surat' => $request->isi_surat,
                'file_path' => 'draft', 
                'signed_by' => null, 
            ]
        );

        // Update status permohonan ke Menunggu Lurah
        $permohonan->update([
            'status' => PermohonanSurat::MENUNGGU_LURAH
        ]);

        // Catat Approval Flow Kasi
        ApprovalFlow::create([
            'permohonan_surat_id' => $permohonan->id,
            'step' => ApprovalFlow::STEP_KASI,
            'status' => ApprovalFlow::STATUS_APPROVED,
            'catatan' => 'Surat telah dibuat dan diteruskan ke Lurah',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'urutan' => 2,
        ]);

        // Catat Timeline
        TimelinePermohonan::create([
            'permohonan_surat_id' => $permohonan->id,
            'status' => PermohonanSurat::MENUNGGU_LURAH,
            'keterangan' => 'Disetujui Kasi & Surat Dibuat',
            'updated_by' => Auth::id(),
        ]);
        
        return redirect()->route('kasi.permohonan.index')
            ->with('success', 'Draft surat berhasil disimpan dan diteruskan ke Lurah.');
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
            // Prepend Kop Surat & Close Wrapper
            $content = $this->getKopSuratHTML() . $template->template_content . '</div>';
            
            $replacements = [
                // Header
                '{{ $logo_url }}' => asset('images/logo-kota-bengkulu.png'), // Pastikan file ada atau gunakan external url sementara
                '{{ $nomor_surat }}' => '<span id="nomor_surat_placeholder">... / ... / ... / ' . date('Y') . '</span>', // Placeholder ID untuk JS replacement
                
                // Data Warga Common
                '{{ $user->name }}' => $nama,
                '{{ $user->nik }}' => $dataPemohon['nik'] ?? $user->nik ?? '-',
                '{{ $user->tempat_lahir }}' => $dataPemohon['tempat_lahir'] ?? $user->tempat_lahir ?? '-',
                '{{ $user->tanggal_lahir }}' => isset($dataPemohon['tanggal_lahir']) ? $dataPemohon['tanggal_lahir'] : ($user->tanggal_lahir ?? '-'), // Raw date usually? No, template expects string probably
                // Composite replacements used in Seeder template
                '{{ $user->pekerjaan }}' => $pekerjaan,
                '{{ $user->alamat_lengkap }}' => $alamat,
                
                // Fix for raw PHP code in DB template (Carbon)
                "{{ \Carbon\Carbon::parse(\$user->tanggal_lahir)->isoFormat('D MMMM Y') }}" => isset($dataPemohon['tanggal_lahir']) ? \Carbon\Carbon::parse($dataPemohon['tanggal_lahir'])->isoFormat('D MMMM Y') : ($user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->isoFormat('D MMMM Y') : '-'),
                "{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}" => \Carbon\Carbon::now()->isoFormat('D MMMM Y'),
                
                // Data Khusus
                '{{ $data_pemohon[\'nama_usaha\'] ?? \'-\' }}' => strtoupper($dataPemohon['nama_usaha'] ?? '-'),
                '{{ $data_pemohon[\'jenis_usaha\'] ?? \'-\' }}' => $dataPemohon['jenis_usaha'] ?? '-',
                '{{ $data_pemohon[\'alamat_usaha\'] ?? \'-\' }}' => $dataPemohon['alamat_usaha'] ?? '-',
                '{{ $data_pemohon[\'tujuan\'] ?? \'-\' }}' => $dataPemohon['tujuan'] ?? 'Administrasi',
                
                // Extra Replacements
                '{{ $rt->nomor_rt }}' => $rt->nomor_rt ?? '000',
                '{{ $user->ttl_formatted }}' => $ttl,
                '{{ $date_now_formatted }}' => \Carbon\Carbon::now()->isoFormat('D MMMM Y'),
                
                // MISSING KEYS FIX
                '{{ $data_pemohon[\'jenis_kelamin\'] ?? \'-\' }}' => $dataPemohon['jenis_kelamin'] ?? $dataPemohon['jk'] ?? $user->jk ?? '-',
                '{{ $data_pemohon[\'nomor_surat_pengantar\'] ?? \'...\' }}' => $permohonan->nomor_surat_pengantar_rt ?? '...',

                // LURAH DATA DYNAMIC
                '{{ $lurah->nama }}' => ($lurah = \App\Models\User::whereHas('role', function($q){ $q->where('name', 'lurah'); })->first()) ? $lurah->name : 'EDWIN KURNIAWAN, SH',
                '{{ $lurah->nip }}' => $lurah->nip ?? '198205272010011004',
                '{{ $lurah->jabatan }}' => $lurah->jabatan ?? 'Kepala Kelurahan',
            ];

             return strtr($content, $replacements);
        }


        // --- FALLBACK OLD HARDCODED LOGIC ---
        // (Tetap biarkan logic lama jika belum ada template di DB)

        $jenisSurat = strtoupper($permohonan->jenisSurat->name ?? '');

        // 1. Ambil Kop Surat (Sama untuk semua)
        $html = $this->getKopSuratHTML();

        // 2. Ambil Judul & Nomor (Sama pola, beda teks)
        $html .= $this->getJudulSuratHTML($permohonan);

        // 3. Ambil Body Surat (Beda-beda tergantung jenis - UPDATE DATA PAKE ROBUST VARIABLES)
        // Kita inject logic baru ke helper methods atau replace disini
        // Agar cepat, kita biarkan switch case lama TAPI data common nya kita perbaiki manual di helper
        // ATAU better: Replace getCommonPemohonData logic
        
        // ... (Switch case lama)
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
        return "
        <div style=\"font-family: 'Times New Roman', serif; color: #000; padding: 20px;\">
            <table style=\"width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 25px;\">
                <tr>
                    <td style=\"width: 15%; text-align: center; vertical-align: middle;\">
                        <img src=\"" . asset('images/logo-kota-bengkulu.png') . "\" alt=\"Logo\" style=\"height: 90px;\">
                    </td>
                    <td style=\"text-align: center; vertical-align: middle;\">
                        <h3 style=\"margin: 0; font-size: 14pt; font-weight: normal;\">PEMERINTAH KOTA BENGKULU</h3>
                        <h2 style=\"margin: 0; font-size: 16pt; font-weight: bold;\">KECAMATAN MUARA BANGKAHULU</h2>
                        <h1 style=\"margin: 0; font-size: 18pt; font-weight: bold;\">KELURAHAN PEMATANG GUBERNUR</h1>
                        <p style=\"margin: 0; font-size: 10pt; font-style: italic;\">Jl. WR. Supratman, Kandang Limun, Kec. Muara Bangkahulu, Kota Bengkulu, Bengkulu 38119</p>
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
                <p style=\"margin: 2px 0 0 0; font-size: 12pt;\">NOMOR: " . ($permohonan->surat->nomor_surat ?? '... / ... / ... / ' . date('Y')) . "</p>
            </div>
        ";
    }

    private function getCommonPemohonData($permohonan)
    {
        $nama = strtoupper($permohonan->data_pemohon['nama_lengkap'] ?? $permohonan->user->name ?? '-');
        $ttl = ($permohonan->data_pemohon['tempat_lahir'] ?? '') . ', ' . ($permohonan->data_pemohon['tanggal_lahir'] ?? '-');
        $jk = $permohonan->data_pemohon['jenis_kelamin'] ?? '-';
        $alamat = $permohonan->data_pemohon['alamat'] ?? '-';
        $pekerjaan = $permohonan->data_pemohon['pekerjaan'] ?? '-';
        $agama = $permohonan->data_pemohon['agama'] ?? '-';

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
        return "
             <!-- TANDA TANGAN -->
            <div style=\"margin-top: 50px; float: right; width: 45%; text-align: center; font-size: 12pt;\">
                <p>Bengkulu, " . date('d F Y') . "</p>
                <p style=\"margin-bottom: 60px;\">KEPALA KELURAHAN PADANG JATI</p>
                
                <p style=\"font-weight: bold; text-decoration: underline;\">EDWIN KURNIAWAN, SH</p>
                <p>NIP. 198205272010011004</p>
            </div>
            
            <div style=\"clear: both;\"></div>
        </div>"; // Close main div
    }
}
