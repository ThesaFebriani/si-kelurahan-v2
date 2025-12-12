<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratTemplate;
use App\Models\JenisSurat;

class ForceUpdateTemplateSeeder extends Seeder
{
    public function run()
    {
        // Cari template jenis SKTM atau ambil yang pertama
        $template = SuratTemplate::where('type', 'surat_kelurahan')->first();
        
        if ($template) {
            $template->update([
                'template_content' => '
<p>Yang bertanda tangan di bawah ini:</p>
<table style="width: 100%; border-collapse: collapse;" border="0">
    <tbody>
        <tr>
            <td style="width: 30%;">Nama</td>
            <td style="width: 2%;">:</td>
            <td>[NAMA_LURAH]</td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>:</td>
            <td>[NIP_LURAH]</td>
        </tr>
         <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>Lurah Padang Jati</td>
        </tr>
    </tbody>
</table>
<br>
<p>Menerangkan dengan sesungguhnya bahwa:</p>
<table style="width: 100%; border-collapse: collapse;" border="0">
    <tbody>
        <tr>
            <td style="width: 30%;">Nama</td>
            <td style="width: 2%;">:</td>
            <td>[NAMA_WARGA]</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>[NIK]</td>
        </tr>
        <tr>
            <td>Tempat/Tgl Lahir</td>
            <td>:</td>
            <td>[TTL]</td>
        </tr>
         <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>[JK]</td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>[PEKERJAAN]</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>[ALAMAT] RT [RT] RW [RW]</td>
        </tr>
    </tbody>
</table>
<br>
<p>Orang tersebut adalah benar warga kami yang: <strong>[KEPERLUAN]</strong></p>
<p>Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
'
            ]);
            $this->command->info('Template berhasil diupdate ke format [TAG].');
        } else {
            $this->command->error('Template tidak ditemukan.');
        }
    }
}
