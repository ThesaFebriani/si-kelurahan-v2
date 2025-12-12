<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratTemplate;

class GlobalSuratPengantarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Hardcoded HTML from RT/PermohonanController
        // Table-based Header to prevent overlap
        $defaultContent = '
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
            <tr>
                <td style="width: 15%; vertical-align: top; text-align: center;">
                    <img src="{{ $logo_src }}" alt="Logo" style="width: 80px; height: auto;">
                </td>
                <td style="width: 85%; text-align: center; vertical-align: middle; font-family: \'Times New Roman\', serif;">
                    <h2 style="font-size: 14pt; font-weight: bold; margin: 0; text-transform: uppercase;">PEMERINTAH KOTA BENGKULU</h2>
                    <h2 style="font-size: 13pt; font-weight: bold; margin: 0; text-transform: uppercase;">KECAMATAN RATU SAMBAN</h2>
                    <h2 style="font-size: 13pt; font-weight: bold; margin: 0; text-transform: uppercase;">KELURAHAN PADANG JATI</h2>
                    <h3 style="font-size: 11pt; font-weight: bold; margin: 0; text-transform: uppercase;">RUKUN TETANGGA {{ $rt_nomor }} RUKUN WARGA {{ $rw_nomor }}</h3>
                    <p style="font-size: 10pt; margin: 2px 0 0 0;">Jl. Jati No. ... Kelurahan Padang Jati Kecamatan Ratu Samban Kota Bengkulu</p>
                </td>
            </tr>
        </table>
        <div style="border-top: 1px solid #000; border-bottom: 3px double #000; margin-bottom: 15px; height: 3px;"></div>


        <div style="margin-bottom: 15px; overflow: auto; font-family: \'Times New Roman\', serif; line-height: 1.15;">
            <div style="float: left; width: 60%;">
                <table style="width: 100%;">
                    <tr><td style="width: 80px;">Nomor</td><td style="width: 10px;">:</td><td><span id="nomor-surat-display">{{ $nomor_surat }}</span></td></tr>
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
        </div>

        <div style="font-family: \'Times New Roman\', serif; line-height: 1.15; text-align: justify;">
            <p style="margin-bottom: 5px;">Dengan Hormat,</p>
            <p style="text-indent: 40px; margin-bottom: 5px;">Yang bertanda tangan dibawah ini, Ketua RT.{{ $rt_nomor }}/RW.{{ $rw_nomor }} Kelurahan Padang Jati Kecamatan Ratu Samban Kota Bengkulu, dengan ini menerangkan bahwa :</p>

            <table style="width: 100%; margin-top: 5px; margin-bottom: 5px; margin-left: 20px; border-collapse: collapse;">
                <tr><td style="width: 180px; padding: 1px 0;">Nama</td><td style="width: 10px; padding: 1px 0;">:</td><td style="padding: 1px 0;"><strong>{{ $nama_warga }}</strong></td></tr>
                <tr><td style="padding: 1px 0;">Tempat, Tanggal Lahir</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">{{ $ttl }}</td></tr>
                <tr><td style="padding: 1px 0;">Jenis Kelamin</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">{{ $jenis_kelamin }}</td></tr>
                <tr><td style="padding: 1px 0;">Nama Kepala Keluarga</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">{{ $kepala_keluarga }}</td></tr>
                <tr><td style="padding: 1px 0;">Bangsa</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">Indonesia / WNA</td></tr>
                <tr><td style="padding: 1px 0;">Agama</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">{{ $agama }}</td></tr>
                <tr><td style="padding: 1px 0;">Status Perkawinan</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">{{ $status_perkawinan }}</td></tr>
                <tr><td style="padding: 1px 0;">Pendidikan Terakhir</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">-</td></tr>
                <tr><td style="padding: 1px 0;">Pekerjaan</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">{{ $pekerjaan }}</td></tr>
                <tr><td style="padding: 1px 0;">No. NIK</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">{{ $nik }}</td></tr>
                <tr><td style="padding: 1px 0;">Alamat</td><td style="padding: 1px 0;">:</td><td style="padding: 1px 0;">{{ $alamat }} Kelurahan Padang Jati Kecamatan Ratu Samban</td></tr>
            </table>

            <p style="text-indent: 40px; margin-bottom: 5px;">Bahwa nama tersebut diatas adalah benar warga RT.{{ $rt_nomor }}/RW.{{ $rw_nomor }} Kelurahan Padang Jati Kecamatan Ratu Samban dan tercatat dalam Buku Kependudukan. Yang bersangkutan datang menghadap Bapak Lurah untuk mengurus :</p>

            <div style="margin: 5px 0 10px 0; padding: 10px; border: 2px solid #000; min-height: 50px; font-weight: bold; text-align: center;"></div>

            <p style="text-indent: 40px; margin-bottom: 0;">Demikian surat pengantar ini kami buat, untuk mendapatkan penyelesaian selanjutnya dan atas bantuannya diucapkan terima kasih.</p>
        </div>
        ';

        // Check if exists
        $template = SuratTemplate::where('type', 'pengantar_rt')
            ->whereNull('jenis_surat_id')
            ->whereNull('rt_id')
            ->first();

        if ($template) {
            $template->update([
                'template_content' => $defaultContent,
                'is_active' => true
            ]);
        } else {
            SuratTemplate::create([
                'jenis_surat_id' => null, // NULL for Global
                'type' => 'pengantar_rt',
                'template_content' => $defaultContent,
                'is_active' => true,
                'rt_id' => null
            ]);
        }
    }
}
