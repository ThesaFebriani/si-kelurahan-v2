<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSurat;
use App\Models\RequiredDocument;

class DefaultRequiredDocumentsSeeder extends Seeder
{
    public function run()
    {
        // 1. SKTM (Surat Keterangan Tidak Mampu)
        $sktm = JenisSurat::where('kode_surat', 'SKTM')->first();
        if ($sktm) {
            $this->createReq($sktm->id, 'KTP (Kartu Tanda Penduduk)', true);
            $this->createReq($sktm->id, 'Kartu Keluarga (KK)', true);
            $this->createReq($sktm->id, 'Foto Rumah (Tampak Depan)', true);
        }

        // 2. SKU (Surat Keterangan Usaha)
        $sku = JenisSurat::where('kode_surat', 'SKU')->first();
        if ($sku) {
            $this->createReq($sku->id, 'KTP Pemohon', true);
            $this->createReq($sku->id, 'Foto Usaha', true);
            $this->createReq($sku->id, 'Bukti Kepemilikan Tempat Usaha (PBB/Sewa)', false);
        }

        // 3. DOMISILI
        $dom = JenisSurat::where('kode_surat', 'DOM')->first();
        if ($dom) {
            $this->createReq($dom->id, 'KTP Pemohon', true);
            $this->createReq($dom->id, 'Kartu Keluarga', true);
            $this->createReq($dom->id, 'Surat Pindah (Jika dari luar kota)', false);
        }

        // 4. KTP (Pengantar KTP)
        $ktp = JenisSurat::where('kode_surat', 'PENGANTAR_KTP')->first();
        if ($ktp) {
            $this->createReq($ktp->id, 'Kartu Keluarga', true);
            $this->createReq($ktp->id, 'Akta Kelahiran / Ijazah Terakhir', true);
        }

        // 5. KK (Pengantar KK)
        $kk = JenisSurat::where('kode_surat', 'PENGANTAR_KK')->first();
        if ($kk) {
            $this->createReq($kk->id, 'KTP Orang Tua / Pelapor', true);
            $this->createReq($kk->id, 'Buku Nikah (Bagi yang sudah menikah)', true);
            $this->createReq($kk->id, 'Surat Keterangan Lahir (Jika penambahan anak)', false);
        }

        // 6. N1 (Surat Pengantar Nikah)
        $n1 = JenisSurat::where('kode_surat', 'N1')->first();
        if ($n1) {
            $this->createReq($n1->id, 'KTP Calon Suami & Istri', true);
            $this->createReq($n1->id, 'Kartu Keluarga Calon Suami & Istri', true);
            $this->createReq($n1->id, 'Buku Nikah Orang Tua (Jika anak pertama)', false);
            $this->createReq($n1->id, 'Akta Cerai / Surat Kematian (Jika Status Janda/Duda)', false);
            $this->createReq($n1->id, 'Pas Foto 2x3 & 3x4 (Latar Biru)', true);
        }
        $this->command->info('Berhasil mengisi default required documents!');
    }

    private function createReq($jenis_id, $name, $required = true, $desc = null)
    {
        // Cek duplikat agar aman dijalankan berkali-kali
        RequiredDocument::firstOrCreate(
            [
                'jenis_surat_id' => $jenis_id,
                'document_name' => $name
            ],
            [
                'is_required' => $required,
                'description' => $desc
            ]
        );
    }
}
