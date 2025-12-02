<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator System',
                'permissions' => json_encode(['*']),
                'is_active' => true,
            ],
            [
                'name' => 'masyarakat',
                'description' => 'Warga Masyarakat',
                'permissions' => json_encode(['ajukan_surat', 'lihat_surat']),
                'is_active' => true,
            ],
            [
                'name' => 'rt',
                'description' => 'Ketua RT',
                'permissions' => json_encode(['approve_rt', 'lihat_permohonan']),
                'is_active' => true,
            ],
            [
                'name' => 'kasi',
                'description' => 'Kepala Seksi',
                'permissions' => json_encode(['verify_kasi', 'generate_surat']),
                'is_active' => true,
            ],
            [
                'name' => 'lurah',
                'description' => 'Lurah',
                'permissions' => json_encode(['tte_lurah', 'lihat_laporan']),
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']], // Cari berdasarkan name
                $role // Update atau create dengan data ini
            );
        }

        $this->command->info('Seeder Role berhasil! (UpdateOrCreate)');
    }
}
