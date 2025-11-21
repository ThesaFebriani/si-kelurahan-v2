<?php

namespace Database\Seeders;

use App\Models\PermohonanSurat;
use App\Models\User;
use App\Models\JenisSurat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermohonanSuratSeeder extends Seeder
{
    public function run(): void
    {
        echo "Memulai PermohonanSuratSeeder...\n";

        // Cek user
        $user = User::where('email', 'warga@kelurahan.dev')->first();
        if (!$user) {
            echo "User warga@kelurahan.dev tidak ditemukan!\n";
            return;
        }
        echo "User ditemukan: " . $user->email . "\n";

        // Cek atau buat JenisSurat
        $jenisSurat = JenisSurat::first();
        if (!$jenisSurat) {
            echo "Membuat JenisSurat baru...\n";

            $jenisSurat = JenisSurat::create([
                'name' => 'Surat Keterangan Domisili',
                'kode_surat' => 'SKD',
                'persyaratan' => 'KTP, Kartu Keluarga, Surat Pengantar RT',
                'bidang' => 'kesra',
                'template_name' => 'template_domisili',
                'estimasi_hari' => 2,
                'is_active' => true
            ]);
        }
        echo "JenisSurat: " . $jenisSurat->name . "\n";

        // Buat PermohonanSurat - SESUAIKAN DENGAN STRUKTUR TABEL
        echo "Membuat PermohonanSurat...\n";

        $permohonan = PermohonanSurat::create([
            'user_id' => $user->id,
            'jenis_surat_id' => $jenisSurat->id,
            'nomor_tiket' => 'TKT-' . Str::random(8) . '-' . time(), // Gunakan nomor_tiket
            'status' => 'menunggu_rt',
            'data_pemohon' => json_encode([
                'nama_lengkap' => 'Warga Testing',
                'nik' => '1234567890123456',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Testing No. 123 RT 001 RW 001',
                'agama' => 'Islam',
                'status_perkawinan' => 'belum_menikah',
                'pekerjaan' => 'Karyawan Swasta',
                'tujuan' => 'Keperluan Administrasi Bank'
            ]),
            'keterangan_tolak' => null, // Kolom keterangan_tolak bukan keterangan
            'nomor_surat_final' => null,
            'file_surat_pengantar_rt' => null,
            'tanggal_pengajuan' => now(),
            'tanggal_selesai' => null,
        ]);

        echo "PermohonanSurat berhasil dibuat dengan ID: " . $permohonan->id . "\n";
        echo "Nomor Tiket: " . $permohonan->nomor_tiket . "\n";
        echo "Seeder selesai!\n";
    }
}
