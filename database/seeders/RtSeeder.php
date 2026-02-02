<?php

namespace Database\Seeders;

use App\Models\Rt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RtSeeder extends Seeder
{
    public function run(): void
    {
        $rts = [
            // RW 001
            ['nomor_rw' => '001', 'nomor_rt' => '001', 'nama_ketua_rt' => 'IRWANDI', 'telepon_ketua_rt' => '081100000001', 'alamat_ketua_rt' => 'JL. KINIBALU RT.01 RW.01'],
            ['nomor_rw' => '001', 'nomor_rt' => '002', 'nama_ketua_rt' => 'NODHERMAN HAREFA', 'telepon_ketua_rt' => '081100000002', 'alamat_ketua_rt' => null],
            ['nomor_rw' => '001', 'nomor_rt' => '003', 'nama_ketua_rt' => 'JUNAIDI', 'telepon_ketua_rt' => '081100000003', 'alamat_ketua_rt' => 'JL. SEMERU NO.21 RT.03 RW.01'],

            // RW 002
            ['nomor_rw' => '002', 'nomor_rt' => '004', 'nama_ketua_rt' => 'ARHAN', 'telepon_ketua_rt' => '082200000004', 'alamat_ketua_rt' => 'JL. S. PARMAN RT.04 RW.02'],
            ['nomor_rw' => '002', 'nomor_rt' => '007', 'nama_ketua_rt' => 'PARMAN', 'telepon_ketua_rt' => '082200000007', 'alamat_ketua_rt' => 'JL. S. PARMAN 6 NO.27 RT.07 RW.02'],
            ['nomor_rw' => '002', 'nomor_rt' => '008', 'nama_ketua_rt' => 'A. RAHMAN', 'telepon_ketua_rt' => '082200000008', 'alamat_ketua_rt' => 'JL. JATI GANG NO.57'],

            // RW 003
            ['nomor_rw' => '003', 'nomor_rt' => '006', 'nama_ketua_rt' => 'MHD. HEANHAR', 'telepon_ketua_rt' => '083300000006', 'alamat_ketua_rt' => 'JL. MAHONI NO.11 RT.06 RW.03'],
            ['nomor_rw' => '003', 'nomor_rt' => '009', 'nama_ketua_rt' => 'HERMAN SULISTIYONO', 'telepon_ketua_rt' => '083300000009', 'alamat_ketua_rt' => 'JL. JATI RT.09 RW.03'],

            // RW 004
            ['nomor_rw' => '004', 'nomor_rt' => '005', 'nama_ketua_rt' => 'YULIANA', 'telepon_ketua_rt' => '084400000005', 'alamat_ketua_rt' => 'JL. BERINGIN NO.40 RT.05 RW. 04'],
            ['nomor_rw' => '004', 'nomor_rt' => '010', 'nama_ketua_rt' => 'SYAKIRIN', 'telepon_ketua_rt' => '084400000010', 'alamat_ketua_rt' => 'JL. MAHONI RT.10 RW.04'],
            ['nomor_rw' => '004', 'nomor_rt' => '011', 'nama_ketua_rt' => 'ELPA SUSIYANTI', 'telepon_ketua_rt' => '084400000011', 'alamat_ketua_rt' => 'JL. BERINGIN RT.11 RW.04'],
        ];

        foreach ($rts as $data) {
            $rw = \App\Models\Rw::where('nomor_rw', $data['nomor_rw'])->first();
            if ($rw) {
                Rt::updateOrCreate(
                    ['rw_id' => $rw->id, 'nomor_rt' => $data['nomor_rt']],
                    [
                        'nama_ketua_rt' => $data['nama_ketua_rt'],
                        'telepon_ketua_rt' => $data['telepon_ketua_rt'],
                        'alamat_ketua_rt' => $data['alamat_ketua_rt'],
                    ]
                );
            }
        }

        $this->command->info('Seeder RT berhasil!');
    }
}
