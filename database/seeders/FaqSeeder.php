<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            // Kategori: Akun & Login
            [
                'category' => 'Akun & Login',
                'question' => 'Bagaimana cara mendaftar akun baru?',
                'answer' => 'Klik tombol "Daftar" di halaman utama. Masukkan NIK, Nama Lengkap sesuai KTP, Email aktif, dan Password. Pastikan NIK Anda valid dan terdaftar di data kelurahan kami.'
            ],
            [
                'category' => 'Akun & Login',
                'question' => 'Apakah satu akun bisa digunakan untuk seluruh keluarga?',
                'answer' => 'Ya. Sistem ini mendukung konsep "Satu Akun per Keluarga". Cukup satu anggota keluarga (misalnya Kepala Keluarga) yang mendaftar, dan akun tersebut dapat digunakan untuk mengajukan surat bagi seluruh anggota keluarga yang terdaftar dalam satu Kartu Keluarga (KK). Pilih nama anggota keluarga saat mengisi formulir pengajuan.'
            ],
            [
                'category' => 'Akun & Login',
                'question' => 'Mengapa NIK saya tidak ditemukan saat mendaftar?',
                'answer' => 'Data penduduk disinkronisasi secara berkala. Jika NIK tidak ditemukan, kemungkinan data Anda belum masuk ke sistem digital kami. Silakan lapor ke Ketua RT untuk pembaharuan data kependudukan.'
            ],
            [
                'category' => 'Akun & Login',
                'question' => 'Saya lupa password, bagaimana cara resetnya?',
                'answer' => 'Saat ini fitur reset password otomatis belum tersedia demi keamanan data. Silakan hubungi Admin Kelurahan atau Ketua RT setempat dengan membawa bukti identitas (KTP/KK) untuk mereset password akun Anda.'
            ],

            // Kategori: Pengajuan Surat
            [
                'category' => 'Pengajuan Surat',
                'question' => 'Surat apa saja yang bisa diajukan secara online?',
                'answer' => 'Saat ini Anda dapat mengajukan: Surat Pengantar RT/RW, Surat Keterangan Tidak Mampu (SKTM), Surat Keterangan Usaha (SKU), Surat Keterangan Domisili, dan Surat Keterangan Belum Menikah.'
            ],
            [
                'category' => 'Pengajuan Surat',
                'question' => 'Berapa lama proses pembuatan surat selesai?',
                'answer' => 'Estimasi waktu standar adalah 1-2 hari kerja. Namun, jika persyaratan lengkap dan pejabat berwenang ada di tempat, surat bisa selesai dalam hitungan jam.'
            ],
            [
                'category' => 'Pengajuan Surat',
                'question' => 'Apakah saya perlu datang ke kantor kelurahan?',
                'answer' => 'Tidak perlu untuk pengambilan surat. Surat yang sudah ditandatangani secara elektronik (TTE) dapat diunduh langsung dari dashboard Anda dan dicetak mandiri (print). Dokumen tersebut sah dan valid.'
            ],
            
            // Kategori: Teknis
            [
                'category' => 'Teknis',
                'question' => 'Bagaimana cara mengecek status surat saya?',
                'answer' => 'Login ke dashboard, pilih menu "Riwayat Permohonan". Status akan terlihat: "Menunggu Verifikasi RT", "Diproses Kelurahan", atau "Selesai".'
            ],
            [
                'category' => 'Teknis',
                'question' => 'Apa arti QR Code di surat yang saya cetak?',
                'answer' => 'QR Code tersebut adalah fitur keamanan (Tanda Tangan Elektronik). Siapapun dapat memindai (scan) kode tersebut untuk memverifikasi keaslian surat langsung ke server kelurahan.'
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create([
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'category' => $faq['category'],
                'is_published' => true
            ]);
        }
    }
}
