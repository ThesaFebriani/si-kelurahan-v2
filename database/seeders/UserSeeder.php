<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::create([
            'role_id' => 1, // admin
            'rt_id' => null,
            'nik' => '1234567890123451',
            'name' => 'Administrator System',
            'jk' => 'laki-laki',
            'telepon' => '081234567890',
            'alamat' => 'Kantor Kelurahan',
            'email' => 'admin@kelurahan.dev',
            'password' => Hash::make('password123'),
            'jabatan' => 'Administrator System',
        ]);

        // RT 001
        User::create([
            'role_id' => 3, // rt
            'rt_id' => 1, // RT 001
            'nik' => '1234567890123452',
            'name' => 'Joko Widodo',
            'jk' => 'laki-laki',
            'telepon' => '081234567011',
            'alamat' => 'Jl. Merdeka No. 1',
            'email' => 'rt001@kelurahan.dev',
            'password' => Hash::make('password123'),
            'jabatan' => 'Ketua RT 001',
        ]);

        // KASI KESRA
        User::create([
            'role_id' => 4, // kasi
            'rt_id' => null,
            'nik' => '1234567890123453',
            'name' => 'Siti Rahayu',
            'jk' => 'perempuan',
            'telepon' => '081234567892',
            'alamat' => 'Kantor Kelurahan',
            'email' => 'kasi.kesra@kelurahan.dev',
            'password' => Hash::make('password123'),
            'jabatan' => 'Kepala Seksi Kesejahteraan Rakyat',
            'bidang' => 'kesra',
        ]);

        // KASI PEMERINTAHAN
        User::create([
            'role_id' => 4, // kasi
            'rt_id' => null,
            'nik' => '1234567890123454',
            'name' => 'Ahmad Fauzi',
            'jk' => 'laki-laki',
            'telepon' => '081234567893',
            'alamat' => 'Kantor Kelurahan',
            'email' => 'kasi.pemerintahan@kelurahan.dev',
            'password' => Hash::make('password123'),
            'jabatan' => 'Kepala Seksi Pemerintahan',
            'bidang' => 'pemerintahan',
        ]);

        // LURAH
        User::create([
            'role_id' => 5, // lurah
            'rt_id' => null,
            'nik' => '1234567890123455',
            'name' => 'Dr. Rina Wijaya, M.Si',
            'jk' => 'perempuan',
            'telepon' => '081234567894',
            'alamat' => 'Kantor Kelurahan',
            'email' => 'lurah@kelurahan.dev',
            'password' => Hash::make('password123'),
            'jabatan' => 'Lurah',
        ]);

        // MASYARAKAT CONTOH
        User::create([
            'role_id' => 2, // masyarakat
            'rt_id' => 1, // RT 001
            'nik' => '1234567890123456',
            'name' => 'Warga Contoh',
            'jk' => 'laki-laki',
            'telepon' => '081234567895',
            'alamat' => 'Jl. Contoh No. 123',
            'email' => 'warga@kelurahan.dev',
            'password' => Hash::make('password123'),
        ]);

        $this->command->info('Seeder User berhasil!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@kelurahan.dev / password123');
        $this->command->info('RT 001: rt001@kelurahan.dev / password123');
        $this->command->info('Kasi Kesra: kasi.kesra@kelurahan.dev / password123');
        $this->command->info('Lurah: lurah@kelurahan.dev / password123');
        $this->command->info('Masyarakat: warga@kelurahan.dev / password123');
    }
}
