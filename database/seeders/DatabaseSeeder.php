<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan File Storage (Lampiran & Surat Pengantar)
        $folders = [
            'lampiran', 
            'surat_pengantar', 
            'surat-pengantar', // Add dash version
            'tanda_tangan',
            'dokumen-pendukung',
            'surat-final'
        ];

        foreach ($folders as $folder) {
            // Gunakan disk 'public' agar path sesuai dengan storage/app/public
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($folder)) {
                // Hapus seluruh direktori dan isinya
                \Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory($folder);
            }
            // Buat ulang direktori kosong agar siap pakai
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($folder);
        }

        // 2. Jalankan Seeder Database
        $this->call([
            RoleSeeder::class,
            KelurahanSeeder::class,
            RwSeeder::class,
            RtSeeder::class,
            UserSeeder::class,
            PendudukSeeder::class, // ADDED: Seeder Penduduk Dummy
            JenisSuratSeeder::class,
            TemplateFieldSeeder::class,
            SuratTemplateSeeder::class,
            FaqSeeder::class, // ADDED: Seeder FAQ
        ]);
    }
}
