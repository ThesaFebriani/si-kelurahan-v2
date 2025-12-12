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

        // --- TAMBAHAN DATA BARU (Integrasi dari AdditionalKKSeeder) ---
        
        // 1. DATA UNTUK RT 02 / RW 01 (Hendra Gunawan)
        $rw01 = \App\Models\Rw::where('nomor_rw', '001')->first();
        if ($rw01) {
            $rt02_rw01 = Rt::where('rw_id', $rw01->id)->where('nomor_rt', '002')->first();
            
            if ($rt02_rw01) {
                // Buat KK
                $kk1 = Keluarga::firstOrCreate(
                    ['no_kk' => '17710101010002'], 
                    [
                        'rt_id' => $rt02_rw01->id,
                        'kepala_keluarga' => 'Hendra Gunawan',
                        'alamat_lengkap' => 'Jl. Manggis No. 25, RT 002 RW 001',
                        'kodepos' => '38222',
                    ]
                );

                // Buat Kepala Keluarga
                AnggotaKeluarga::firstOrCreate(
                    ['nik' => '1771010101000002'],
                    [
                        'keluarga_id' => $kk1->id,
                        'nama_lengkap' => 'Hendra Gunawan',
                        'jk' => 'L',
                        'tempat_lahir' => 'Bengkulu',
                        'tanggal_lahir' => '1980-05-15',
                        'status_hubungan' => 'kepala_keluarga',
                        'status_perkawinan' => 'kawin',
                        'agama' => 'Islam',
                        'pendidikan' => 'S1',
                        'pekerjaan' => 'Wiraswasta',
                        'kewarganegaraan' => 'WNI',
                    ]
                );
            }
        }

        // 2. DATA UNTUK RT 01 / RW 02 (Bambang Pamungkas)
        $rw02 = \App\Models\Rw::where('nomor_rw', '002')->first();
        if ($rw02) {
            $rt01_rw02 = Rt::where('rw_id', $rw02->id)->where('nomor_rt', '001')->first();
            
            if ($rt01_rw02) {
                // Buat KK
                $kk2 = Keluarga::firstOrCreate(
                    ['no_kk' => '17710101020001'], 
                    [
                        'rt_id' => $rt01_rw02->id,
                        'kepala_keluarga' => 'Bambang Pamungkas',
                        'alamat_lengkap' => 'Jl. Kenanga No. 10, RT 001 RW 002',
                        'kodepos' => '38223',
                    ]
                );

                // Buat Kepala Keluarga
                AnggotaKeluarga::firstOrCreate(
                    ['nik' => '1771010102000001'],
                    [
                        'keluarga_id' => $kk2->id,
                        'nama_lengkap' => 'Bambang Pamungkas',
                        'jk' => 'L',
                        'tempat_lahir' => 'Jakarta',
                        'tanggal_lahir' => '1985-06-10',
                        'status_hubungan' => 'kepala_keluarga',
                        'status_perkawinan' => 'kawin',
                        'agama' => 'Islam',
                        'pendidikan' => 'SMA',
                        'pekerjaan' => 'Karyawan Swasta',
                        'kewarganegaraan' => 'WNI',
                    ]
                );
            }
        }

        $this->command->info('Data Penduduk Dummy berhasil dibuat!');
        $this->command->info('- NIK Test: ' . $nikTest . ' (Gunakan NIK ini untuk Register)');
    }
}
