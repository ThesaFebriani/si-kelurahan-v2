<?php

namespace Database\Seeders;

use App\Models\Keluarga;
use App\Models\AnggotaKeluarga;
use App\Models\Rt;
use Illuminate\Database\Seeder;

class PendudukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil RT pertama
        $rt = Rt::first();

        if (!$rt) {
            $this->command->error('Tabel RT kosong! Jalankan RtSeeder terlebih dahulu.');
            return;
        }

        // Cek apakah data keluarga dengan No KK ini sudah ada
        $noKk = '3278121101900099';
        $keluarga = Keluarga::where('no_kk', $noKk)->first();

        if (!$keluarga) {
            // 1. Buat Data Keluarga (KK)
            $keluarga = Keluarga::create([
                'rt_id' => $rt->id,
                'no_kk' => $noKk, 
                'kepala_keluarga' => 'Budi Santoso',
                'alamat_lengkap' => 'Jl. Merdeka No. 10, RT 001 RW 001',
                'kodepos' => '40287',
            ]);
        }

        // 2. Buat Data Anggota Keluarga (Untuk NIK yang dites User: 3278121101900001)
        $nikTest = '3278121101900001';
        if (!AnggotaKeluarga::where('nik', $nikTest)->exists()) {
            AnggotaKeluarga::create([
                'keluarga_id' => $keluarga->id,
                'nik' => $nikTest,
                'nama_lengkap' => 'Budi Santoso',
                'jk' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1990-01-01',
                'status_hubungan' => 'kepala_keluarga',
                'status_perkawinan' => 'kawin',
                'agama' => 'Islam',
                'pendidikan' => 'S1',
                'pekerjaan' => 'Wiraswasta',
                'kewarganegaraan' => 'WNI',
            ]);
        }

        // Tambahkan anggota keluarga lain (istri) untuk testing
        $nikIstri = '3278121101900002';
        if (!AnggotaKeluarga::where('nik', $nikIstri)->exists()) {
            AnggotaKeluarga::create([
                'keluarga_id' => $keluarga->id,
                'nik' => $nikIstri,
                'nama_lengkap' => 'Siti Aminah',
                'jk' => 'P',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1992-05-15',
                'status_hubungan' => 'istri',
                'status_perkawinan' => 'kawin',
                'agama' => 'Islam',
                'pendidikan' => 'D3',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'kewarganegaraan' => 'WNI',
            ]);
        }

        $this->command->info('Data Penduduk Dummy berhasil dibuat!');
        $this->command->info('- NIK Test: ' . $nikTest . ' (Gunakan NIK ini untuk Register)');
    }
}
