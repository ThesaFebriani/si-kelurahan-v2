<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanSuratController extends Controller
{
    /**
     * Show the form to edit the Global Surat Pengantar RT.
     */
    public function index()
    {
        $template = SuratTemplate::where('type', 'pengantar_rt')
            ->whereNull('jenis_surat_id')
            ->whereNull('rt_id')
            ->first();

        // If not exists (should be seeded, but just in case), create dummy
        // If not exists (should be seeded, but just in case), create dummy or load from file
        if (!$template) {
            $template = new SuratTemplate();
            
            // Generate Base64 Logo for Default Content
            $logo_b64 = '';
            if (file_exists(public_path('images/logo-kota-bengkulu.png'))) {
                $logo_b64 = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo-kota-bengkulu.png')));
            }

            // DEFAULT HARDCODED CONTENT (Official Padang Jati Format)
            // Ini agar kita bisa mengubah file blade menjadi wrapper tanpa kehilangan konten default ini.
            $template->template_content = '
            <div style="font-family: \'Times New Roman\', serif; font-size: 11pt; line-height: 1.15; padding: 10px;">
                <!-- KOP SURAT -->
                <table style="width: 100%; border-bottom: 3px double #000; margin-bottom: 5px; padding-bottom: 5px;">
                    <tr>
                        <td style="width: 80px; text-align: center; vertical-align: top;">
                            <img src="[LOGO_SRC]" alt="Logo" style="width: 65px; height: auto;">
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <h3 style="margin: 0; font-size: 12pt; font-weight: normal; letter-spacing: 1px; text-transform: uppercase;">[NAMA_INSTANSI]</h3>
                            <h2 style="margin: 0; font-size: 13pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px;">[NAMA_KECAMATAN]</h2> 
                            <h1 style="margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px;">[NAMA_KELURAHAN]</h1>
                            <h4 style="margin: 0; font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px;">RUKUN TETANGGA [NOMOR_RT] / RUKUN WARGA [NOMOR_RW]</h4>
                            <p style="margin: 0; font-size: 9pt; font-style: normal;">Sekretariat: [ALAMAT_SEKRETARIAT] HP. [NO_HP_RT]</p>
                        </td>
                    </tr>
                </table>

                <!-- INFO SURAT -->
                <table style="width: 100%; margin-top: 5px; margin-bottom: 10px;">
                    <tr>
                        <td style="width: 55%; vertical-align: top; font-size: 11pt;">
                            <table style="width: 100%;">
                                <tr><td style="width: 80px; padding-bottom: 2px;">Nomor</td><td>: [NOMOR_SURAT]</td></tr>
                                <tr><td style="padding-bottom: 2px;">Lampiran</td><td>: -</td></tr>
                                <tr><td style="padding-bottom: 2px;">Perihal</td><td>: <span style="text-decoration: underline;">Surat Pengantar</span></td></tr>
                            </table>
                        </td>
                        <td style="width: 45%; vertical-align: top; padding-left: 10px; font-size: 11pt;">
                            Kepada Yth,<br>
                            Bapak Lurah Padang Jati<br>
                            Di-<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BENGKULU
                        </td>
                    </tr>
                </table>

                <!-- ISI SURAT -->
                <div style="margin-top: 5px; text-align: justify;">
                    <p style="margin-bottom: 5px;">Dengan Hormat,</p>
                    <p style="text-indent: 40px; margin-bottom: 10px;">Yang bertanda tangan dibawah ini, Ketua RT.[NOMOR_RT]/RW.[NOMOR_RW] Kelurahan Padang Jati Kecamatan Gading Cempaka Kota Bengkulu, dengan ini menerangkan bahwa :</p>

                    <table style="width: 100%; margin-left: 40px; margin-top: 5px; margin-bottom: 10px; font-size: 11pt;">
                        <tr><td style="width: 170px; padding-bottom: 3px;">Nama</td><td style="width: 15px; text-align: center;">:</td><td style="font-weight: bold;">[NAMA_WARGA]</td></tr>
                        <tr><td style="padding-bottom: 3px;">Tempat, Tanggal Lahir</td><td style="text-align: center;">:</td><td>[TTL_WARGA]</td></tr>
                        <tr><td style="padding-bottom: 3px;">Jenis Kelamin</td><td style="text-align: center;">:</td><td>[JENIS_KELAMIN]</td></tr>
                        <tr><td style="padding-bottom: 3px;">Bangsa</td><td style="text-align: center;">:</td><td>[BANGSA]</td></tr>
                        <tr><td style="padding-bottom: 3px;">Agama</td><td style="text-align: center;">:</td><td>[AGAMA]</td></tr>
                        <tr><td style="padding-bottom: 3px;">Status Perkawinan</td><td style="text-align: center;">:</td><td>[STATUS_PERKAWINAN]</td></tr>
                        <tr><td style="padding-bottom: 3px;">Pendidikan Terakhir</td><td style="text-align: center;">:</td><td>[PENDIDIKAN]</td></tr>
                        <tr><td style="padding-bottom: 3px;">Pekerjaan</td><td style="text-align: center;">:</td><td>[PEKERJAAN]</td></tr>
                        <tr><td style="padding-bottom: 3px;">No. NIK</td><td style="text-align: center;">:</td><td>[NIK]</td></tr>
                        <tr><td style="padding-bottom: 3px; vertical-align: top;">Alamat</td><td style="text-align: center; vertical-align: top;">:</td><td>[ALAMAT_WARGA] <br> RT.[NOMOR_RT]/RW.[NOMOR_RW] Kelurahan Padang Jati <br> Kecamatan Gading Cempaka</td></tr>
                    </table>

                    <p style="text-indent: 40px; margin-bottom: 10px;">Bahwa nama tersebut diatas adalah benar warga RT.[NOMOR_RT]/RW.[NOMOR_RW] Kelurahan Padang Jati Kecamatan Gading Cempaka dan tercatat dalam Buku Kependudukan. Yang bersangkutan datang menghadap Bapak Lurah untuk mengurus :</p>

                    <div style="border: 1px solid #000; padding: 5px 10px; margin: 5px 0 15px 0; min-height: 40px;">
                        [KEPERLUAN]
                    </div>

                    <p style="text-indent: 40px;">Demikian surat pengantar ini kami buat, untuk mendapatkan penyelesaian selanjutnya dan atas bantuannya diucapkan terima kasih.</p>
                </div>

                <!-- TANDA TANGAN -->
                <div style="margin-top: 20px; float: right; width: 40%; text-align: center; font-size: 11pt;">
                    <p style="margin: 0;">Bengkulu, [TANGGAL_SURAT]</p>
                    <p style="margin: 0;">Ketua RT.[NOMOR_RT] / RW.[NOMOR_RW] Kelurahan Padang Jati</p>
                    
                    <div style="height: 70px;">
                        <!-- QR Code / TTD Space -->
                        [QR_CODE_SPACE]
                    </div>

                    <p style="text-decoration: underline; font-weight: bold; text-transform: uppercase; margin: 0;">[NAMA_KETUA_RT]</p>
                </div>
            </div>';
        }

        // Data for Preview Look & Feel
        $logo_path = public_path('images/logo-kota-bengkulu.png');
        $logo_b64 = '';
        if (file_exists($logo_path)) {
            $logo_b64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logo_path));
        }

        // Replace Variable with Real Image for Editor
        // This ensures the user sees the logo, and when saved, it's saved as Base64 (perfect for PDF)
        // Replace Variable with Real Image for Editor
        if ($template) {
            // Kita ganti variabel {{ $logo_src }} dengan Base64 agar tampil visual di Editor
            // Saat admin simpan, gambar akan tersimpan sebagai Base64 hardcoded di template (HTML)
            // Ini tidak masalah, bahkan simplify logic di controller RT nanti.
            $template->template_content = str_replace('{{ $logo_src }}', $logo_b64, $template->template_content);
        }

        // Fetch Dynamic Settings
        $settings = \App\Models\SystemSetting::pluck('value', 'key');
        
        $logo_url = isset($settings['logo_instansi']) ? asset($settings['logo_instansi']) : asset('images/logo-kota-bengkulu.png');
        
        // Dummy Data for Tags
        $rt_nomor = '001';
        $rw_nomor = '002';
        $nomor_surat = '.../RT-.../.../'.date('Y');
        $nama_warga = 'Contoh Warga';
        $nik = '1771xxxxxxxxxxxx';
        $ttl = 'Bengkulu, 01-01-1990';
        $jenis_kelamin = 'Laki-laki';
        $agama = 'Islam';
        $pekerjaan = 'Wiraswasta';
        $alamat = 'Jl. Contoh No. 1';
        $status_perkawinan = 'Kawin';
        $kepala_keluarga = 'Contoh Kepala Keluarga';
        $keperluan = '(Keperluan Warga)';
        $tanggal_surat = date('d F Y');
        
        return view('pages.admin.settings.surat-pengantar', get_defined_vars());
    }

    /**
     * Update the Global Surat Pengantar RT.
     */
    public function update(Request $request)
    {
        $request->validate([
            'template_content' => 'required|string',
        ]);

        $template = SuratTemplate::where('type', 'pengantar_rt')
            ->whereNull('jenis_surat_id')
            ->whereNull('rt_id')
            ->first();

        if (!$template) {
            // Create if missing
            $template = SuratTemplate::create([
                'type' => 'pengantar_rt',
                'jenis_surat_id' => null,
                'rt_id' => null,
                'template_content' => $request->template_content,
                'is_active' => true
            ]);
        } else {
            $template->update([
                'template_content' => $request->template_content
            ]);
        }

        return redirect()->back()->with('success', 'Format Surat Pengantar RT berhasil diperbarui untuk seluruh sistem!');
    }
}
