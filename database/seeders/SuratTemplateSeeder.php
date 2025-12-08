<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSurat;
use App\Models\SuratTemplate;

class SuratTemplateSeeder extends Seeder
{
    public function run()
    {
        // 1. Template Surat Keterangan Usaha (SKU)
        $sku = JenisSurat::where('kode_surat', 'SKU')->first();
        if ($sku) {
            // Template Pengantar RT
            SuratTemplate::create([
                'jenis_surat_id' => $sku->id,
                'nama_template' => 'Pengantar RT - SKU',
                'type' => 'pengantar_rt',
                'file_path' => 'templates.surat-pengantar-rt', // fallback path if content is used as base
                'fields_mapping' => json_encode([
                    'nama_usaha' => 'nama_usaha',
                    'alamat_usaha' => 'alamat_usaha',
                    'bidang_usaha' => 'bidang_usaha'
                ]),
                'template_content' => '
                    <p style="text-align: justify;">Yang bertanda tangan di bawah ini Ketua RT {{ $rt->nomor_rt }} Kelurahan Pematang Gubernur, Kecamatan Muara Bangkahulu, Kota Bengkulu, menerangkan bahwa warga kami:</p>
                    
                    <table style="width: 100%; margin: 10px 0;">
                        <tr><td style="width: 140px;">Nama</td><td>:</td><td><strong>{{ $user->name }}</strong></td></tr>
                        <tr><td>NIK</td><td>:</td><td>{{ $user->nik }}</td></tr>
                        <tr><td>Alamat</td><td>:</td><td>{{ $user->alamat_lengkap }}</td></tr>
                    </table>

                    <p style="text-align: justify;">Orang tersebut diatas adalah benar-benar warga kami yang memiliki usaha sebagai berikut:</p>

                    <table style="width: 100%; margin: 10px 0; border: 1px solid #ddd; padding: 10px;">
                        <tr><td style="width: 140px;">Nama Usaha</td><td>:</td><td><strong>{{ $data_pemohon[\'nama_usaha\'] ?? \'-\' }}</strong></td></tr>
                        <tr><td>Bidang Usaha</td><td>:</td><td>{{ $data_pemohon[\'jenis_usaha\'] ?? \'-\' }}</td></tr>
                        <tr><td>Alamat Usaha</td><td>:</td><td>{{ $data_pemohon[\'alamat_usaha\'] ?? \'-\' }}</td></tr>
                         <tr><td>Status Tempat</td><td>:</td><td>{{ $data_pemohon[\'status_tempat_usaha\'] ?? \'-\' }}</td></tr>
                    </table>

                    <p style="text-align: justify;">Demikian surat pengantar ini dibuat untuk keperluan pengurusan <strong>Surat Keterangan Usaha</strong> di Tingkat Kelurahan.</p>
                ',
                'is_active' => true,
            ]);

            // Template Surat Kelurahan Final
            SuratTemplate::create([
                'jenis_surat_id' => $sku->id,
                'nama_template' => 'Surat Keterangan Usaha (Final)',
                'type' => 'surat_kelurahan',
                'file_path' => 'templates.surat-kelurahan',
                'fields_mapping' => json_encode([]),
                'template_content' => '
                    <p style="text-align: center; font-weight: bold; text-decoration: underline; font-size: 14pt; margin-bottom: 5px;">SURAT KETERANGAN USAHA</p>
                    <p style="text-align: center; margin-top: 0;">Nomor: {{ $nomor_surat }}</p>

                    <p style="text-align: justify; margin-top: 20px;">Yang bertanda tangan di bawah ini Lurah Pematang Gubernur, Kecamatan Muara Bangkahulu, Kota Bengkulu, menerangkan bahwa:</p>

                     <table style="width: 100%; margin: 10px 0;">
                        <tr><td style="width: 140px;">Nama</td><td>:</td><td><strong>{{ $user->name }}</strong></td></tr>
                        <tr><td>NIK</td><td>:</td><td>{{ $user->nik }}</td></tr>
                        <tr><td>Tempat, Tgl Lahir</td><td>:</td><td>{{ $user->tempat_lahir }}, {{ \Carbon\Carbon::parse($user->tanggal_lahir)->isoFormat(\'D MMMM Y\') }}</td></tr>
                        <tr><td>Pekerjaan</td><td>:</td><td>{{ $user->pekerjaan }}</td></tr>
                        <tr><td>Alamat</td><td>:</td><td>{{ $user->alamat_lengkap }}</td></tr>
                    </table>

                    <p style="text-align: justify;">Benar nama tersebut diatas mempunyai usaha:</p>
                    
                    <div style="margin: 10px 20px; padding: 10px; border: 1px solid #000;">
                         <table style="width: 100%;">
                            <tr><td style="width: 130px;">Nama Usaha</td><td>:</td><td><strong>{{ $data_pemohon[\'nama_usaha\'] ?? \'-\' }}</strong></td></tr>
                            <tr><td>Jenis/Bidang</td><td>:</td><td>{{ $data_pemohon[\'jenis_usaha\'] ?? \'-\' }}</td></tr>
                            <tr><td>Alamat Usaha</td><td>:</td><td>{{ $data_pemohon[\'alamat_usaha\'] ?? \'-\' }}</td></tr>
                        </table>
                    </div>

                    <p style="text-align: justify;">Surat Keterangan ini diberikan kepada yang bersangkutan untuk melengkapi persyaratan Administrasi Perbankan / Lainnya.</p>
                    <p style="text-align: justify;">Demikian Surat Keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>
                ',
                'is_active' => true,
            ]);
        }

        // 2. Template Surat Keterangan Tidak Mampu (SKTM)
        $sktm = JenisSurat::where('kode_surat', 'SKTM')->first();
        if ($sktm) {
            // RT
            SuratTemplate::create([
                'jenis_surat_id' => $sktm->id,
                'nama_template' => 'Pengantar RT - SKTM',
                'type' => 'pengantar_rt',
                'file_path' => 'templates.surat-pengantar-rt',
                'fields_mapping' => json_encode([]),
                'template_content' => '
                    <p style="text-align: justify;">Yang bertanda tangan di bawah ini Ketua RT {{ $rt->nomor_rt }} Kelurahan Pematang Gubernur, Kecamatan Muara Bangkahulu, Kota Bengkulu, menerangkan bahwa:</p>
                    
                    <table style="width: 100%; margin: 10px 0;">
                        <tr><td style="width: 140px;">Nama</td><td>:</td><td><strong>{{ $user->name }}</strong></td></tr>
                        <tr><td>NIK</td><td>:</td><td>{{ $user->nik }}</td></tr>
                         <tr><td>Pekerjaan</td><td>:</td><td>{{ $user->pekerjaan }}</td></tr>
                        <tr><td>Alamat</td><td>:</td><td>{{ $user->alamat_lengkap }}</td></tr>
                    </table>

                    <p style="text-align: justify;">Orang tersebut diatas adalah benar-benar warga kami yang tergolong keluarga <strong>DENGAN EKONOMI LEMAH / TIDAK MAMPU</strong>.</p>
                    
                    <p style="text-align: justify;">Demikian surat pengantar ini dibuat untuk keperluan pengurusan <strong>Surat Keterangan Tidak Mampu (SKTM)</strong> di Tingkat Kelurahan.</p>
                ',
                'is_active' => true,
            ]);
            
            // Kelurahan
            SuratTemplate::create([
                'jenis_surat_id' => $sktm->id,
                'nama_template' => 'Surat Keterangan Tidak Mampu (Final)',
                'type' => 'surat_kelurahan',
                'file_path' => 'templates.surat-kelurahan',
                'fields_mapping' => json_encode([]),
                'template_content' => '
                    <p style="text-align: center; font-weight: bold; text-decoration: underline; font-size: 14pt; margin-bottom: 5px;">SURAT KETERANGAN TIDAK MAMPU (SKTM)</p>
                    <p style="text-align: center; margin-top: 0;">Nomor: {{ $nomor_surat }}</p>

                    <p>Yang bertanda tangan di bawah ini :</p>
                    <table style="width: 100%; margin: 10px 0;">
                        <tr><td style="width: 200px;">Nama</td><td>: <strong>{{ $lurah->nama }}</strong></td></tr>
                        <tr><td>NIP</td><td>: {{ $lurah->nip }}</td></tr>
                        <tr><td>Jabatan</td><td>: {{ $lurah->jabatan }}</td></tr>
                    </table>

                    <p style="text-align: justify;">Menerangkan dengan sesungguhnya bahwa :</p>

                     <table style="width: 100%; margin: 10px 0;">
                        <tr><td style="width: 200px;">Nama</td><td>: <strong>{{ $user->name }}</strong></td></tr>
                        <tr><td>Tempat, Tgl Lahir</td><td>: {{ $user->ttl_formatted }}</td></tr>
                        <tr><td>Jenis Kelamin</td><td>: {{ $data_pemohon[\'jenis_kelamin\'] ?? \'-\' }}</td></tr>
                        <tr><td>Pekerjaan</td><td>: {{ $user->pekerjaan }}</td></tr>
                        <tr><td>Alamat</td><td>: {{ $user->alamat_lengkap }}</td></tr>
                    </table>

                    <p style="text-align: justify;">Berdasarkan Surat Pengantar Ketua RT {{ $rt->nomor_rt }} Nomor : {{ $data_pemohon[\'nomor_surat_pengantar\'] ?? \'...\' }} tanggal {{ $date_now_formatted }}, bahwa memang benar nama tersebut di atas adalah warga Kelurahan Padang Jati dan tergolong keluarga <strong>TIDAK MAMPU</strong>.</p>

                    <p style="text-align: justify;">Surat Keterangan ini diberikan untuk keperluan: <strong>{{ $data_pemohon[\'tujuan\'] ?? \'-\' }}</strong>.</p>
                    <p style="text-align: justify;">Demikian Surat Keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>
                ',
                'is_active' => true,
            ]);
        }

        // 3. Template Surat Keterangan Domisili (SKD)
        $skd = JenisSurat::where('kode_surat', 'SKD')->first();
        if ($skd) {
             // RT
            SuratTemplate::create([
                'jenis_surat_id' => $skd->id,
                'nama_template' => 'Pengantar RT - SKD',
                'type' => 'pengantar_rt',
                'file_path' => 'templates.surat-pengantar-rt',
                'fields_mapping' => json_encode([]),
                'template_content' => '
                    <p style="text-align: justify;">Yang bertanda tangan di bawah ini Ketua RT {{ $rt->nomor_rt }} Kelurahan Pematang Gubernur, Kecamatan Muara Bangkahulu, Kota Bengkulu, menerangkan bahwa:</p>
                    
                    <table style="width: 100%; margin: 10px 0;">
                        <tr><td style="width: 140px;">Nama</td><td>:</td><td><strong>{{ $user->name }}</strong></td></tr>
                        <tr><td>NIK</td><td>:</td><td>{{ $user->nik }}</td></tr>
                        <tr><td>Alamat</td><td>:</td><td>{{ $user->alamat_lengkap }}</td></tr>
                    </table>

                    <p style="text-align: justify;">Orang tersebut diatas adalah benar-benar warga kami dan <strong>BERDOMISILI</strong> di alamat tersebut diatas.</p>
                    
                    <p style="text-align: justify;">Demikian surat pengantar ini dibuat untuk keperluan pengurusan <strong>Surat Keterangan Domisili</strong> di Tingkat Kelurahan.</p>
                ',
                'is_active' => true,
            ]);

            // Kelurahan
             SuratTemplate::create([
                'jenis_surat_id' => $skd->id,
                'nama_template' => 'Surat Keterangan Domisili (Final)',
                'type' => 'surat_kelurahan',
                'file_path' => 'templates.surat-kelurahan',
                'fields_mapping' => json_encode([]),
                'template_content' => '
                    <p style="text-align: center; font-weight: bold; text-decoration: underline; font-size: 14pt; margin-bottom: 5px;">SURAT KETERANGAN DOMISILI</p>
                    <p style="text-align: center; margin-top: 0;">Nomor: {{ $nomor_surat }}</p>

                    <p>Yang bertanda tangan di bawah ini :</p>
                    <table style="width: 100%; margin: 10px 0;">
                        <tr><td style="width: 200px;">Nama</td><td>: <strong>{{ $lurah->nama }}</strong></td></tr>
                        <tr><td>NIP</td><td>: {{ $lurah->nip }}</td></tr>
                        <tr><td>Jabatan</td><td>: {{ $lurah->jabatan }}</td></tr>
                    </table>

                     <p style="text-align: justify;">Menerangkan dengan sesungguhnya bahwa :</p>

                     <table style="width: 100%; margin: 10px 0;">
                        <tr><td style="width: 200px;">Nama</td><td>: <strong>{{ $user->name }}</strong></td></tr>
                        <tr><td>Tempat, Tgl Lahir</td><td>: {{ $user->ttl_formatted }}</td></tr>
                        <tr><td>Jenis Kelamin</td><td>: {{ $data_pemohon[\'jenis_kelamin\'] ?? \'-\' }}</td></tr>
                        <tr><td>Pekerjaan</td><td>: {{ $user->pekerjaan }}</td></tr>
                        <tr><td>Alamat</td><td>: {{ $user->alamat_lengkap }}</td></tr>
                    </table>

                     <p style="text-align: justify;">Berdasarkan Pengantar Ketua RT {{ $rt->nomor_rt }} Nomor : {{ $data_pemohon[\'nomor_surat_pengantar\'] ?? \'...\' }}, bahwa benar yang bersangkutan adalah Penduduk yang berdomisili di lingkungan RT {{ $rt->nomor_rt }} Kelurahan Pematang Gubernur, Kecamatan Muara Bangkahulu, Kota Bengkulu.</p>

                    <p style="text-align: justify;">Surat Keterangan ini diberikan untuk keperluan: <strong>{{ $data_pemohon[\'tujuan\'] ?? \'-\' }}</strong>.</p>
                    <p style="text-align: justify;">Demikian Surat Keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>
                ',
                'is_active' => true,
            ]);
        }
    }
}
