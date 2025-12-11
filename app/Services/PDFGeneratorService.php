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
            'isi_surat' => $isiSurat, // Konten kustom/edit user
            'verificator_name' => Auth::user()->name,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('templates.surat-pengantar-rt', $data);

        // Set paper size dan orientation
        // F4 size: 215mm x 330mm
        $pdf->setPaper([0, 0, 609.4488, 935.433], 'portrait');

        // Simpan file
        $filename = "surat-pengantar-rt-{$permohonan->nomor_tiket}.pdf";
        $directory = 'surat-pengantar';
        $path = "{$directory}/{$filename}";

        // Simpan ke storage
        Storage::disk('public')->put($path, $pdf->output());

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
            'tanggal_surat' => now()->translatedFormat('d F Y'),
        ];
        
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
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return $path;
    }
}
