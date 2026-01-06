<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key' => 'nama_instansi',
                'value' => 'PEMERINTAH KOTA BENGKULU',
                'type' => 'text',
                'description' => 'Nama Instansi (Baris 1 Kop Surat)'
            ],
            [
                'key' => 'nama_kecamatan',
                'value' => 'KECAMATAN RATU SAMBAN',
                'type' => 'text',
                'description' => 'Nama Kecamatan (Baris 2 Kop Surat)'
            ],
            [
                'key' => 'nama_kelurahan',
                'value' => 'KELURAHAN PADANG JATI',
                'type' => 'text',
                'description' => 'Nama Kelurahan (Baris 3 Kop Surat)'
            ],
            [
                'key' => 'alamat_instansi',
                'value' => 'Jalan Jati II No.43 – Bengkulu Telp. (0736) 26364',
                'type' => 'text', // In future can be JSON if we split phone
                'description' => 'Alamat Lengkap Instansi di Kop Surat'
            ],
            [
                'key' => 'logo_instansi',
                'value' => 'images/logo-kota-bengkulu.png', // Default path
                'type' => 'image',
                'description' => 'Path Logo Instansi'
            ]
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
