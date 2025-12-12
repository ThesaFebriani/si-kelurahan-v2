<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratTemplate;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateTemplatesSeeder extends Seeder
{
    public function run()
    {
        // 1. Ambil ID template terbaru untuk setiap jenis_surat (di level kelurahan)
        $latestIds = SuratTemplate::where('type', 'surat_kelurahan')
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('jenis_surat_id')
            ->pluck('id');

        // 2. Hapus semua template kelurahan YANG BUKAN id terbaru
        SuratTemplate::where('type', 'surat_kelurahan')
            ->whereNotIn('id', $latestIds)
            ->delete();
    }
}
