<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelurahanSeeder extends Seeder
{
    public function run(): void
    {
        Kelurahan::create([
            'nama_kelurahan' => 'Kelurahan Contoh',
            'kode_kelurahan' => 'KLH001',
            'alamat_kantor' => 'Jl. Contoh No. 123',
            'telepon' => '(021) 1234567',
            'email' => 'kelurahan@contoh.dev',
            'kecamatan' => 'Kecamatan Contoh',
            'kota' => 'Kota Contoh',
            'provinsi' => 'Provinsi Contoh',
            'kodepos' => '12345',
            'nama_lurah' => 'Dr. Contoh Lurah, M.Si',
            'nip_lurah' => '196512312345678901',
        ]);

        $this->command->info('Seeder Kelurahan berhasil!');
    }
}
