<?php

namespace Database\Seeders;

use App\Models\ApprovalFlow;
use App\Models\JenisSurat;
use App\Models\PermohonanSurat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DummyAnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Pastikan ada Users dan JenisSurats
        $users = User::whereHas('role', function ($q) {
            $q->where('name', 'masyarakat');
        })->get();

        if ($users->isEmpty()) {
            $this->command->error("Tidak ada user masyarakat. Jalankan UserSeeder dulu.");
            return;
        }

        $jenisSurats = JenisSurat::where('is_active', true)->get();
        if ($jenisSurats->isEmpty()) {
            $this->command->error("Tidak ada jenis surat. Jalankan JenisSuratSeeder dulu.");
            return;
        }

        // Ambil ID RT, Kasi, Lurah untuk Approval
        $rtUser = User::whereHas('role', fn($q) => $q->where('name', 'rt'))->first();
        $kasiUser = User::whereHas('role', fn($q) => $q->where('name', 'kasi'))->first();
        $lurahUser = User::whereHas('role', fn($q) => $q->where('name', 'lurah'))->first();

        $statuses = [
            PermohonanSurat::SELESAI,
            PermohonanSurat::SELESAI, // Bobot lebih besar ke selesai
            PermohonanSurat::SELESAI,
            PermohonanSurat::DITOLAK_RT,
            PermohonanSurat::DITOLAK_KASI,
            PermohonanSurat::MENUNGGU_RT,
            PermohonanSurat::MENUNGGU_KASI
        ];

        $this->command->info("Generating 50 dummy permohonan for analytics...");

        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $jenis = $jenisSurats->random();
            $status = $statuses[array_rand($statuses)];

            // Random Created At (30 hari terakhir)
            $createdAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));
            
            // Estimasi selesai: 1-5 hari setelah created
            $tanggalSelesai = $status === PermohonanSurat::SELESAI 
                ? (clone $createdAt)->addDays(rand(1, 5))->addHours(rand(1, 10)) 
                : null;

            $permohonan = PermohonanSurat::create([
                'user_id' => $user->id,
                'jenis_surat_id' => $jenis->id,
                'nomor_tiket' => 'T-DUMMY-' . rand(1000, 9999),
                'status' => $status,
                'data_pemohon' => [
                    'nama_lengkap' => $user->name,
                    'nik' => $user->nik,
                    'alamat' => $user->alamat,
                    'keterangan' => $faker->sentence
                ],
                'tanggal_pengajuan' => $createdAt,
                'created_at' => $createdAt,
                'updated_at' => $tanggalSelesai ?? $createdAt,
                'tanggal_selesai' => $tanggalSelesai
            ]);

            // Jika status selesai, buatkan flow approval dummy
            if ($status === PermohonanSurat::SELESAI) {
                // 1. RT Approve (1-24 jam setelah submit)
                $rtApproveTime = (clone $createdAt)->addHours(rand(1, 24));
                ApprovalFlow::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'step' => 'rt',
                    'status' => 'approved',
                    'approved_by' => $rtUser->id,
                    'approved_at' => $rtApproveTime,
                    'created_at' => $rtApproveTime
                ]);

                // 2. Kasi Approve (1-24 jam setelah RT)
                $kasiApproveTime = (clone $rtApproveTime)->addHours(rand(1, 24));
                ApprovalFlow::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'step' => 'kasi',
                    'status' => 'approved',
                    'approved_by' => $kasiUser->id,
                    'approved_at' => $kasiApproveTime,
                    'created_at' => $kasiApproveTime
                ]);

                // 3. Lurah Approve (1-24 jam setelah Kasi)
                $lurahApproveTime = (clone $kasiApproveTime)->addHours(rand(1, 24));
                ApprovalFlow::create([
                    'permohonan_surat_id' => $permohonan->id,
                    'step' => 'lurah',
                    'status' => 'approved',
                    'approved_by' => $lurahUser->id,
                    'approved_at' => $lurahApproveTime,
                    'created_at' => $lurahApproveTime
                ]);
            }
        }
        
        $this->command->info("✅ Dummy Analytics Data Created!");
    }
}
