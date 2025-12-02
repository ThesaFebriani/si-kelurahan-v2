<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        $jenisSurats = [
            [
                'name' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'kode_surat' => 'SKTM',
                'persyaratan' => 'KTP, Kartu Keluarga, Surat Pengantar RT, Data Penghasilan',
                'bidang' => 'kesra',
                'template_name' => 'sktm_template',
                'estimasi_hari' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Surat Keterangan Domisili',
                'kode_surat' => 'DOM',
                'persyaratan' => 'KTP, Kartu Keluarga, Surat Pengantar RT, Bukti Tempat Tinggal',
                'bidang' => 'pemerintahan',
                'template_name' => 'domisili_template',
                'estimasi_hari' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Surat Keterangan Usaha',
                'kode_surat' => 'SKU',
                'persyaratan' => 'KTP, Foto Usaha, Surat Pengantar RT',
                'bidang' => 'pemerintahan',
                'template_name' => 'usaha_template',
                'estimasi_hari' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Surat Pengantar Nikah (N1)',
                'kode_surat' => 'N1',
                'persyaratan' => 'KTP kedua calon, Kartu Keluarga, Foto Berdua, Akta Cerai (jika ada)',
                'bidang' => 'kesra',
                'template_name' => 'nikah_template',
                'estimasi_hari' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Surat Keterangan Penghasilan',
                'kode_surat' => 'SKP',
                'persyaratan' => 'KTP, Kartu Keluarga, Surat Pengantar RT',
                'bidang' => 'kesra',
                'template_name' => 'penghasilan_template',
                'estimasi_hari' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Surat Keterangan Kelahiran',
                'kode_surat' => 'SKK',
                'persyaratan' => 'KTP Orang Tua, Kartu Keluarga, Surat Keterangan Lahir dari Bidan/Rumah Sakit',
                'bidang' => 'kesra',
                'template_name' => 'kelahiran_template',
                'estimasi_hari' => 3,
                'is_active' => true,
            ]
        ];

        foreach ($jenisSurats as $jenis) {
            JenisSurat::updateOrCreate(
                ['kode_surat' => $jenis['kode_surat']], // Cari berdasarkan kode_surat
                $jenis // Update atau create dengan data ini
            );
        }

        $this->command->info('Seeder JenisSurat berhasil! (UpdateOrCreate)');
    }
}
