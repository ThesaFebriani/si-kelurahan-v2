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
            ['nomor_rw' => '001', 'nama_ketua_rw' => 'Budi Santoso', 'telepon_ketua_rw' => '081234567801'],
            ['nomor_rw' => '002', 'nama_ketua_rw' => 'Siti Rahayu', 'telepon_ketua_rw' => '081234567802'],
            ['nomor_rw' => '003', 'nama_ketua_rw' => 'Ahmad Fauzi', 'telepon_ketua_rw' => '081234567803'],
        ];

        foreach ($rw as $data) {
            Rw::create($data);
        }

        $this->command->info('Seeder RW berhasil!');
    }
}
