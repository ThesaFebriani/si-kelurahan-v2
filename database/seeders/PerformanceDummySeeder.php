<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PermohonanSurat;
use App\Models\TimelinePermohonan;
use Carbon\Carbon;

class PerformanceDummySeeder extends Seeder
{
    public function run()
    {
        $user = User::whereHas('role', fn($q) => $q->where('name', 'masyarakat'))->first();
        if(!$user) {
            $this->command->error("No Masyarakat User found!");
            return;
        }

        $jenisSurat = \App\Models\JenisSurat::first();
        if(!$jenisSurat) {
            $this->command->error("No Jenis Surat found!");
            return;
        }

        $this->command->info("Seeding Performance Data for User: {$user->name}, Jenis Surat: {$jenisSurat->name}");

        // Create 5 Dummy Completed Surats with different durations
        for ($i = 0; $i < 5; $i++) {
            $start = Carbon::now()->subDays(rand(1, 10));
            
            // 1. Submit
            $permohonan = PermohonanSurat::create([
                'user_id' => $user->id,
                'jenis_surat_id' => $jenisSurat->id,
                'nomor_tiket' => 'TEST-PERF-' . time() . '-' . $i,
                'status' => PermohonanSurat::SELESAI,
                'data_pemohon' => ['nama' => 'Dummy Performance'],
                'created_at' => $start,
                'updated_at' => $start->copy()->addHours(20),
            ]);

            // 2. Timeline: Masuk (Menunggu RT)
            TimelinePermohonan::create([
                'permohonan_surat_id' => $permohonan->id,
                'status' => PermohonanSurat::MENUNGGU_RT,
                'keterangan' => 'Baru diajukan',
                'created_at' => $start,
                'updated_by' => $user->id
            ]);

            // 3. Timeline: RT Approve (Duration: 2-5 hours)
            $rtApproveTime = $start->copy()->addHours(rand(2, 5));
            TimelinePermohonan::create([
                'permohonan_surat_id' => $permohonan->id,
                'status' => PermohonanSurat::DISETUJUI_RT,
                'keterangan' => 'ACC RT',
                'created_at' => $rtApproveTime,
                'updated_by' => $user->id // Hack: reuse user id to avoid missing user error
            ]);

            // 4. Timeline: Masuk Kelurahan (Menunggu Kasi) - Instant after RT
            TimelinePermohonan::create([
                'permohonan_surat_id' => $permohonan->id,
                'status' => PermohonanSurat::MENUNGGU_KASI,
                'keterangan' => 'Masuk Kelurahan',
                'created_at' => $rtApproveTime,
                'updated_by' => $user->id
            ]);

            // 5. Timeline: Kasi Approve (Duration: 10-20 hours - BOTTLENECK SIMULATION)
            $kasiApproveTime = $rtApproveTime->copy()->addHours(rand(10, 20));
            TimelinePermohonan::create([
                'permohonan_surat_id' => $permohonan->id,
                'status' => PermohonanSurat::DISETUJUI_KASI,
                'keterangan' => 'ACC Kasi',
                'created_at' => $kasiApproveTime,
                'updated_by' => $user->id
            ]);
            
            // 6. Timeline: Menunggu Lurah (Instant)
            TimelinePermohonan::create([
                'permohonan_surat_id' => $permohonan->id,
                'status' => PermohonanSurat::MENUNGGU_LURAH,
                'keterangan' => 'Naik ke Lurah',
                'created_at' => $kasiApproveTime,
                'updated_by' => $user->id
            ]);

            // 7. Timeline: Lurah TTE (Duration: 1-2 hours)
            $finishTime = $kasiApproveTime->copy()->addHours(rand(1, 2));
            TimelinePermohonan::create([
                'permohonan_surat_id' => $permohonan->id,
                'status' => PermohonanSurat::SELESAI,
                'keterangan' => 'Selesai TTE',
                'created_at' => $finishTime,
                'updated_by' => $user->id
            ]);
        }
    }
}
