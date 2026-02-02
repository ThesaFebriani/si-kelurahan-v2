<?php

namespace Database\Seeders;

use App\Models\Rw;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RwSeeder extends Seeder
{
    public function run(): void
    {
        $rw = [
            [
                'nomor_rw' => '001', 
                'nama_ketua_rw' => 'SYAMSINAR', 
                'telepon_ketua_rw' => '081111111111',
                'alamat_ketua_rw' => 'JL. BUKIT BARISAN 3 NO.11 RT.02 RW.01'
            ],
            [
                'nomor_rw' => '002', 
                'nama_ketua_rw' => 'NAHARUDDIN', 
                'telepon_ketua_rw' => '082222222222',
                'alamat_ketua_rw' => 'JL. S. PARMAN 6 NO.23 RT.07 RW.02'
            ],
            [
                'nomor_rw' => '003', 
                'nama_ketua_rw' => 'AHMAD SERI', 
                'telepon_ketua_rw' => '083333333333',
                'alamat_ketua_rw' => 'JL. BERINGIN NO.29 RT.06 RW.03'
            ],
            [
                'nomor_rw' => '004', 
                'nama_ketua_rw' => 'ENGKI TRINAWATI', 
                'telepon_ketua_rw' => '084444444444',
                'alamat_ketua_rw' => 'JL. MAHONI NO.13H RT.10 RW.04'
            ],
        ];

        foreach ($rw as $data) {
            Rw::updateOrCreate(['nomor_rw' => $data['nomor_rw']], $data);
        }

        $this->command->info('Seeder RW berhasil!');
    }
}
