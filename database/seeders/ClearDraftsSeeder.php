<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermohonanSurat;
use App\Models\Surat;

class ClearDraftsSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua permohonan yang statusnya menunggu Kasi
        $permohonanIds = PermohonanSurat::where('status', 'menunggu_kasi')->pluck('id');
        
        // Hapus draft surat yang terkait dengan permohonan ini
        // Ini akan memaksa sistem untuk men-generate ulang konten dari Template baru
        if ($permohonanIds->count() > 0) {
            $deleted = Surat::whereIn('permohonan_surat_id', $permohonanIds)->delete();
            $this->command->info("Berhasil menghapus {$deleted} draft surat lama.");
        } else {
            $this->command->info("Tidak ada permohonan 'menunggu_kasi' yang ditemukan.");
        }
    }
}
