<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSurat;
use App\Models\TemplateField;

class DefaultTemplateFieldsSeeder extends Seeder
{
    public function run()
    {
        // 1. SKTM (Surat Keterangan Tidak Mampu)
        $sktm = JenisSurat::where('kode_surat', 'SKTM')->first();
        if ($sktm) {
            $this->createField($sktm->id, 'pekerjaan_suami', 'Pekerjaan Suami', 'text');
            $this->createField($sktm->id, 'penghasilan_rata_rata', 'Penghasilan Rata-rata (Rp)', 'number');
            $this->createField($sktm->id, 'jumlah_tanggungan', 'Jumlah Tanggungan', 'number');
            $this->createField($sktm->id, 'alasan_tidak_mampu', 'Alasan / Keperluan SKTM', 'textarea');
        }

        // 2. SKU (Surat Keterangan Usaha)
        $sku = JenisSurat::where('kode_surat', 'SKU')->first();
        if ($sku) {
            $this->createField($sku->id, 'nama_usaha', 'Nama Usaha', 'text');
            $this->createField($sku->id, 'jenis_usaha', 'Jenis Usaha', 'text');
            $this->createField($sku->id, 'alamat_usaha', 'Alamat Usaha', 'textarea');
            $this->createField($sku->id, 'lama_usaha', 'Lama Usaha (Tahun)', 'number');
            $this->createField($sku->id, 'status_bangunan', 'Status Bangunan Tempat Usaha', 'text', false); // Opsional
        }

        // 3. DOMISILI
        $dom = JenisSurat::where('kode_surat', 'DOM')->first();
        if ($dom) {
            $this->createField($dom->id, 'status_tempat_tinggal', 'Status Tempat Tinggal', 'text');
            $this->createField($dom->id, 'sejak_tanggal', 'Tinggal Sejak Tanggal', 'date');
            $this->createField($dom->id, 'keperluan', 'Keperluan Surat', 'textarea');
        }
        
        // 4. N1 (Surat Pengantar Nikah)
        $n1 = JenisSurat::where('kode_surat', 'N1')->first();
        if ($n1) {
            $this->createField($n1->id, 'nama_calon_pasangan', 'Nama Lengkap Calon Pasangan', 'text');
            $this->createField($n1->id, 'bin_binti_pasangan', 'Bin/Binti Pasangan', 'text');
            $this->createField($n1->id, 'tempat_lahir_pasangan', 'Tempat Lahir Pasangan', 'text');
            $this->createField($n1->id, 'tanggal_lahir_pasangan', 'Tanggal Lahir Pasangan', 'date');
            $this->createField($n1->id, 'alamat_pasangan', 'Alamat Pasangan', 'textarea');
            $this->createField($n1->id, 'pekerjaan_pasangan', 'Pekerjaan Pasangan', 'text');
        }

        $this->command->info('Berhasil mengisi default template fields (isian form)!');
    }

    private function createField($jenis_id, $key, $label, $type = 'text', $required = true)
    {
        TemplateField::firstOrCreate(
            [
                'jenis_surat_id' => $jenis_id,
                'field_key' => $key
            ],
            [
                'field_label' => $label,
                'field_type' => $type,
                'is_required' => $required
            ]
        );
    }
}
