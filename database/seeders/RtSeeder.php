<?php

namespace Database\Seeders;

use App\Models\Rt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RtSeeder extends Seeder
{
    public function run(): void
    {
        $rt = [
            // RW 001
            ['rw_id' => 1, 'nomor_rt' => '001', 'nama_ketua_rt' => 'Joko Widodo', 'telepon_ketua_rt' => '081234567011'],
            ['rw_id' => 1, 'nomor_rt' => '002', 'nama_ketua_rt' => 'Ani Susanti', 'telepon_ketua_rt' => '081234567012'],
            ['rw_id' => 1, 'nomor_rt' => '003', 'nama_ketua_rt' => 'Rudi Hartono', 'telepon_ketua_rt' => '081234567013'],

            // RW 002
            ['rw_id' => 2, 'nomor_rt' => '001', 'nama_ketua_rt' => 'Dewi Sartika', 'telepon_ketua_rt' => '081234567021'],
            ['rw_id' => 2, 'nomor_rt' => '002', 'nama_ketua_rt' => 'Eko Prasetyo', 'telepon_ketua_rt' => '081234567022'],

            // RW 003  
            ['rw_id' => 3, 'nomor_rt' => '001', 'nama_ketua_rt' => 'Fitri Handayani', 'telepon_ketua_rt' => '081234567031'],
        ];

        foreach ($rt as $data) {
            Rt::create($data);
        }

        $this->command->info('Seeder RT berhasil!');
    }
}
