<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PermohonanSurat;
use App\Models\Rt;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class PDFGeneratorService
{
    /**
     * Generate Surat Pengantar RT
     */
    public function generateSuratPengantarRT(PermohonanSurat $permohonan, string $nomorSurat, Rt $rt, $isiSurat = null)
    {
        // Generate QR Code data
        $verificationUrl = url("/verify/surat-pengantar/{$permohonan->id}"); 
        // Note: For RT, we might not have a dedicated public verify route yet, 
        // but let's assume valid URL structure for now or reuse existing structure.
        // Actually, let's use a simpler hash for now if no verify route exists.
        // But better to consistency use the URL.
        
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($verificationUrl));

        // Data untuk template
        $data = [
            'permohonan' => $permohonan,
            'nomor_surat' => $nomorSurat,
            'rt' => $rt,
            'tanggal_surat' => now()->translatedFormat('d F Y'),
            'user' => $permohonan->user,
            'data_pemohon' => $permohonan->data_pemohon ?? [],
            'qr_code' => $qrCode,
            'verificator_name' => Auth::user()->name,
        ];

        // Replace Placeholder with Real Image in Content if exists
        if ($isiSurat && str_contains($isiSurat, '[QR_CODE_SPACE]')) {
             $qrImg = '<img src="data:image/svg+xml;base64, '.$qrCode.'" alt="QR Code" width="90">';
             $data['isi_surat'] = str_replace('[QR_CODE_SPACE]', $qrImg, $isiSurat);
        } else {
             $data['isi_surat'] = $isiSurat;
        }

        // Generate PDF
        $pdf = Pdf::setOptions(['isRemoteEnabled' => true])
            ->loadView('templates.surat-pengantar-rt', $data);

        // Set paper size dan orientation
        // F4 size: 215mm x 330mm
        $pdf->setPaper([0, 0, 609.4488, 935.433], 'portrait');

        // Simpan file
        $filename = "surat-pengantar-rt-{$permohonan->nomor_tiket}.pdf";
        $directory = 'surat-pengantar';
        $path = "{$directory}/{$filename}";

        // Simpan ke storage
        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    /**
     * Generate QR Code untuk verifikasi
     */
    public function generateQRCodeData($permohonanId)
    {
        $verificationUrl = url("/verify/surat-pengantar/{$permohonanId}");
        $data = [
            'url' => $verificationUrl,
            'permohonan_id' => $permohonanId,
            'timestamp' => now()->timestamp,
            'checksum' => md5("surat-pengantar-{$permohonanId}-" . now()->timestamp)
        ];

        return json_encode($data);
    }

    /**
     * Generate nomor surat pengantar format: 001/RT-01/IX/2024
     */
    public function generateNomorSuratPengantar(Rt $rt)
    {
        $monthRoman = $this->getRomanMonth(now()->month);
        $year = now()->year;
        $rtNumber = str_pad($rt->nomor_rt, 2, '0', STR_PAD_LEFT);

        // Ambil nomor terakhir untuk RT ini di bulan ini
        $lastNumber = PermohonanSurat::whereNotNull('nomor_surat_pengantar_rt')
            ->whereHas('user', function ($q) use ($rt) {
                $q->where('rt_id', $rt->id);
            })
            ->whereMonth('updated_at', now()->month)
            ->count();

        $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return "{$nextNumber}/RT-{$rtNumber}/{$monthRoman}/{$year}";
    }

    /**
     * Konversi bulan ke romawi
     */
    private function getRomanMonth($month)
    {
        $romans = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        return $romans[$month] ?? 'I';
    }

    /**
     * Generate Surat Kelurahan Final (dengan TTE & QR Code)
     */
    public function generateSuratKelurahan($surat, $verificatorName)
    {
        // Generate QR Code
        $verificationUrl = url("/verify/surat/{$surat->nomor_surat}"); // URL validasi
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($verificationUrl));
        
        $data = [
            'isi_surat' => $surat->isi_surat,
            'nomor_surat' => $surat->nomor_surat,
            'qr_code' => $qrCode,
            'verificator_name' => $verificatorName,
            // Cari data user Lurah yg sedang login atau dari relasi jika ada (disini assume Auth user is Lurah approving)
            'verificator_nip' => \App\Models\User::where('name', $verificatorName)->value('nip') ?? '198205272010011004',
            'tanggal_surat' => now()->translatedFormat('d F Y'),
        ];
        
        // Replace [QR_CODE] in body if exists
        if (str_contains($data['isi_surat'], '[QR_CODE]')) {
             $qrImg = '<img src="data:image/svg+xml;base64, '.$qrCode.'" alt="QR Code" width="90">';
             $data['isi_surat'] = str_replace('[QR_CODE]', $qrImg, $data['isi_surat']);
        }
        
        // Generate PDF
        $pdf = Pdf::loadView('templates.surat-kelurahan', $data);
        // F4 size: 215mm x 330mm
        $pdf->setPaper([0, 0, 609.4488, 935.433], 'portrait');
        
        // Simpan File
        $filename = "surat-kelurahan-{$surat->nomor_surat}.pdf";
        // Clean filename logic needed for slashes
        $filename = str_replace(['/', '\\'], '-', $filename);
        $directory = 'surat-final';
        $path = "{$directory}/{$filename}";
        
        Storage::disk('local')->put($path, $pdf->output());
        
        return $path;
    }
    /**
     * Centralized Logic for Template Variable Replacement
     *
     * @param string $content
     * @param PermohonanSurat $permohonan
     * @param string|null $nomorSurat
     * @return string
     */
    public function applyContentVariables($content, PermohonanSurat $permohonan, $nomorSurat = null)
    {
        $user = $permohonan->user;
        $rt = $user->rt;
        $rw = $rt ? $rt->rw : null;
        $dataPemohon = $permohonan->data_pemohon;

        // Ensure array
        if (is_string($dataPemohon)) $dataPemohon = json_decode($dataPemohon, true);
        if (!is_array($dataPemohon)) $dataPemohon = [];

        // Data Preparation
        // Data Preparation Logic (Priority: Form Input > User Table > Master Penduduk)
        $penduduk = $user->anggotaKeluarga; // Cache the relation

        // 0. NIK (Fix Missing Variable)
        $nik = !empty($dataPemohon['nik']) 
            ? $dataPemohon['nik'] 
            : ($user->nik ?? '-');

        // 1. Tempat Lahir
        $tempatLahir = !empty($dataPemohon['tempat_lahir']) 
            ? $dataPemohon['tempat_lahir'] 
            : ($penduduk->tempat_lahir ?? ($user->tempat_lahir ?? '-'));

        // 2. Tanggal Lahir
        $tglLahirRaw = !empty($dataPemohon['tanggal_lahir']) 
            ? $dataPemohon['tanggal_lahir'] 
            : ($penduduk->tanggal_lahir ?? ($user->tanggal_lahir ?? null));
        
        $tglLahir = $tglLahirRaw ? \Carbon\Carbon::parse($tglLahirRaw)->isoFormat('D MMMM Y') : '-';

        // 3. Jenis Kelamin
        $jkRaw = !empty($dataPemohon['jenis_kelamin']) 
            ? $dataPemohon['jenis_kelamin'] 
            : (!empty($dataPemohon['jk']) ? $dataPemohon['jk'] : ($penduduk->jk ?? ($user->jk ?? '-')));
            
        $jk = match(strtolower($jkRaw)) {
            'l', 'laki-laki' => 'Laki-laki',
            'p', 'perempuan' => 'Perempuan',
            default => '-'
        };

        // 4. Pekerjaan
        $pekerjaan = !empty($dataPemohon['pekerjaan']) 
            ? $dataPemohon['pekerjaan'] 
            : ($penduduk->pekerjaan ?? ($user->pekerjaan ?? '-'));

        // 5. Agama
        $agama = !empty($dataPemohon['agama']) 
            ? $dataPemohon['agama'] 
            : ($penduduk->agama ?? ($user->agama ?? '-'));

        // 6. Status Perkawinan
        $statusPerkawinan = !empty($dataPemohon['status_perkawinan']) 
            ? $dataPemohon['status_perkawinan'] 
            : ($penduduk->status_perkawinan ?? ($user->status_perkawinan ?? '-'));

        // 7. Alamat (Prioritas: Form > Penduduk (via relasi) > User)
        // Note: Alamat user sering update domisili, tapi kita prioritaskan KTP (Penduduk) jika diminta "Sesuai KTP".
        // Namun biasanya surat butuh Alamat Domisili. Mari kita konsisten Master dulu.
        $alamat = !empty($dataPemohon['alamat']) 
            ? $dataPemohon['alamat'] 
            : ($penduduk && $penduduk->keluarga ? $penduduk->keluarga->alamat : ($user->alamat_lengkap ?? '-'));
        
        // 8. Kepala Keluarga
        $namaKepalaKeluarga = ($penduduk && $penduduk->keluarga) ? $penduduk->keluarga->kepala_keluarga : ($user->keluarga ? $user->keluarga->kepala_keluarga : '-');

        // Fetch System Settings
        $settings = \App\Models\SystemSetting::pluck('value', 'key');
        
        // Logo Logic (Dynamic)
        $logoPathRaw = $settings['logo_instansi'] ?? 'images/logo-kota-bengkulu.png';
        $logoPath = public_path($logoPathRaw);
        $logoSrc = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;
        }

        // Replacements Map
        $replacements = [
            '[LOGO_SRC]' => $logoSrc,
            
            // Agency Profile (Dynamic)
            '[NAMA_INSTANSI]' => $settings['nama_instansi'] ?? 'PEMERINTAH KOTA BENGKULU',
            '[NAMA_KECAMATAN]' => $settings['nama_kecamatan'] ?? 'KECAMATAN GADING CEMPAKA',
            '[NAMA_KELURAHAN]' => $settings['nama_kelurahan'] ?? 'KELURAHAN PADANG JATI',
            '[ALAMAT_INSTANSI]' => $settings['alamat_instansi'] ?? 'Jalan Mangga Raya Lingkar Timur – Bengkulu',
            
            // Compatibilty for existing hardcoded templates if they use these headers as placeholders (unlikely but good practice)
            // '[NAMA_KOTA]' => 'BENGKULU', 

            '[NOMOR_RT]' => $rt ? str_pad($rt->nomor_rt, 3, '0', STR_PAD_LEFT) : '000',
            '[NOMOR_RW]' => $rw ? str_pad($rw->nomor_rw, 3, '0', STR_PAD_LEFT) : '000',
            '[NOMOR_SURAT]' => '<span id="nomor-surat-display">' . ($nomorSurat ?? '.../RT-.../.../'.date('Y')) . '</span>',

            '[NAMA_WARGA]' => $user->name,
            '[TTL_WARGA]' => $tempatLahir . ', ' . $tglLahir,
            '[TTL]' => $tempatLahir . ', ' . $tglLahir, // Fallback
            '[JENIS_KELAMIN]' => $jk,
            '[JK]' => $jk, // Alias for template shortcut
            '[KEPALA_KELUARGA]' => $namaKepalaKeluarga,
            '[AGAMA]' => $agama,
            '[STATUS_PERKAWINAN]' => $statusPerkawinan,
            '[PEKERJAAN]' => $pekerjaan,
            '[PENDIDIKAN]' => $dataPemohon['pendidikan'] ?? ($user->anggotaKeluarga ? $user->anggotaKeluarga->pendidikan : '-'),
            '[BANGSA]' => $user->anggotaKeluarga ? ($user->anggotaKeluarga->kewarganegaraan == 'WNI' ? 'Indonesia' : $user->anggotaKeluarga->kewarganegaraan) : 'Indonesia',
            '[NIK]' => $nik,
            '[ALAMAT_WARGA]' => $alamat,
            '[ALAMAT]' => $alamat, // Fallback
            '[TAHUN]' => now()->year,

            // Dynamic Fields
            '[KEPERLUAN]' => $dataPemohon['tujuan'] ?? $dataPemohon['keperluan'] ?? '-',

            // Footer
            '[TANGGAL_SURAT]' => now()->translatedFormat('d F Y'),

            // RT Info
            '[ALAMAT_SEKRETARIAT]' => $rt ? (\App\Models\User::where('rt_id', $rt->id)->whereHas('role', function($q){ $q->where('name', 'rt'); })->value('alamat') ?? 'Jl. ...') : '-',
            '[NO_HP_RT]' => '-',

            '[NAMA_KETUA_RT]' => $rt ? (\App\Models\User::where('rt_id', $rt->id)->whereHas('role', function($q){ $q->where('name', 'rt'); })->value('name') ?? '(Nama Ketua RT)') : '-',
            
            // Kasi/Admin Specific Tags
            '[NO_KK]' => $dataPemohon['no_kk'] ?? $user->kk ?? '-',
            '[KEWARGANEGARAAN]' => $dataPemohon['kewarganegaraan'] ?? 'WNI',
            '[RT]' => $rt ? str_pad($rt->nomor_rt, 3, '0', STR_PAD_LEFT) : '000',
            '[RW]' => $rw ? str_pad($rw->nomor_rw, 3, '0', STR_PAD_LEFT) : '000',
            '[NAMA_AYAH]' => $dataPemohon['nama_ayah'] ?? '-',
            '[NAMA_IBU]' => $dataPemohon['nama_ibu'] ?? '-',
            '[TUJUAN]' => $dataPemohon['tujuan'] ?? $dataPemohon['keperluan'] ?? '-', // Alias
            '[NOMOR_SURAT_PENGANTAR]' => $permohonan->nomor_surat_pengantar_rt ?? '-',
            '[TANGGAL_SURAT_PENGANTAR]' => $permohonan->timeline->where('status', 'menunggu_kasi')->first() ? \Carbon\Carbon::parse($permohonan->timeline->where('status', 'menunggu_kasi')->first()->created_at)->translatedFormat('d F Y') : ($permohonan->updated_at->translatedFormat('d F Y')),
            
            // Lurah Info (Dynamic from DB if possible)
            '[NAMA_LURAH]' => (\App\Models\User::whereHas('role', function($q){ $q->where('name', 'lurah'); })->value('name')) ?? 'EDWIN KURNIAWAN, SH',
            '[NIP_LURAH]' => (\App\Models\User::whereHas('role', function($q){ $q->where('name', 'lurah'); })->value('nip')) ?? '198205272010011004',
            '[JABATAN_LURAH]' => 'Kepala Kelurahan',
        ];

        return strtr($content, $replacements);
    }
}
