<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratTemplate;
use App\Models\JenisSurat;

class UpdateTemplateSeeder extends Seeder
{
    public function run()
    {
        // Coba cari berdasarkan kode_surat atau nama
        $jenis = JenisSurat::where('kode_surat', 'SKTM')
            ->orWhere('name', 'like', '%Tidak Mampu%')
            ->first() ?? JenisSurat::first();
        
        if ($jenis) {
            SuratTemplate::updateOrCreate(
                [
                    'jenis_surat_id' => $jenis->id,
                    'type' => 'surat_kelurahan',
                    'rt_id' => null
                ],
                [
                    'is_active' => true,
                    'template_content' => '
<p>Yang bertanda tangan di bawah ini Lurah Padang Jati, Kecamatan Ratu Samban, Kota Bengkulu, menerangkan bahwa:</p>
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
<td>Agama</td>
<td>:</td>
<td>[AGAMA]</td>
</tr>
<tr>
<td>Status Perkawinan</td>
<td>:</td>
<td>[STATUS_PERKAWINAN]</td>
</tr>
<tr>
<td>Alamat</td>
<td>:</td>
<td>[ALAMAT] RT [RT] RW [RW]</td>
</tr>
</tbody>
</table>
<p>Orang tersebut di atas adalah benar-benar warga kami yang tergolong keluarga kurang mampu (Pra Sejahtera).</p>
<p>Demikian surat keterangan ini dibuat untuk keperluan: <strong>[KEPERLUAN]</strong>.</p>
'
                ]
            );
        }
    }
}
