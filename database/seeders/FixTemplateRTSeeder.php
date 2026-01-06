<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratTemplate;

class FixTemplateRTSeeder extends Seeder
{
    public function run()
    {
        // Hapus template lama agar terganti dengan yang baru
        SuratTemplate::where('type', 'pengantar_rt')
            ->whereNull('jenis_surat_id')
            ->whereNull('rt_id')
            ->delete();

        SuratTemplate::create([
            'type' => 'pengantar_rt',
            'jenis_surat_id' => null, // Global
            'rt_id' => null, // Global
            'template_content' => '
                <div style="font-family: \'Times New Roman\', Times, serif; font-size: 12pt; line-height: 1.5;">
                    <!-- KOP SURAT RT -->
                    <table style="width: 100%; border-bottom: 3px solid black; margin-bottom: 2px;">
                        <tr>
                            <td style="width: 15%; text-align: center; vertical-align: middle;">
                                <img src="{{ $logo_src }}" alt="Logo" style="width: 75px; height: auto;">
                            </td>
                            <td style="width: 85%; text-align: center; vertical-align: middle;">
                                <h3 style="margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase;">PEMERINTAH KOTA BENGKULU</h3>
                                <h3 style="margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase;">KECAMATAN GADING CEMPAKA</h3>
                                <h3 style="margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase;">KELURAHAN PADANG JATI</h3>
                                <h4 style="margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase;">RUKUN TETANGGA {{ $rt_nomor }} / RUKUN WARGA {{ $rw_nomor }}</h4>
                                <p style="margin: 0; font-size: 10pt; font-style: italic;">Sekretariat: {{ $alamat }}</p>
                            </td>
                        </tr>
                    </table>
                    <div style="border-top: 1px solid black; margin-bottom: 20px;"></div>

                    <!-- HEADER SURAT -->
                    <table style="width: 100%; margin-bottom: 20px;">
                        <tr>
                            <td style="width: 15%;">Nomor</td>
                            <td style="width: 2%;">:</td>
                            <td style="width: 43%;">{{ $nomor_surat }}</td>
                            <td style="width: 40%; text-align: right;">Kepada Yth,</td>
                        </tr>
                        <tr>
                            <td>Lampiran</td>
                            <td>:</td>
                            <td>-</td>
                            <td style="text-align: right;">Bapak Lurah Padang Jati</td>
                        </tr>
                        <tr>
                            <td>Perihal</td>
                            <td>:</td>
                            <td><span style="text-decoration: underline;">Surat Pengantar</span></td>
                            <td style="text-align: right;">Di-</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right; padding-right: 30px;">BENGKULU</td>
                        </tr>
                    </table>

                    <p>Dengan Hormat,</p>

                    <p style="text-align: justify; text-indent: 40px;">
                        Yang bertanda tangan dibawah ini, Ketua RT.{{ $rt_nomor }}/RW.{{ $rw_nomor }} Kelurahan Padang Jati Kecamatan Gading Cempaka Kota Bengkulu, dengan ini menerangkan bahwa :
                    </p>

                    <!-- BIODATA -->
                    <table style="width: 100%; margin-left: 30px; margin-bottom: 10px;">
                        <tr>
                            <td style="width: 25%;">Nama</td>
                            <td style="width: 2%;">:</td>
                            <td style="font-weight: bold;">{{ $nama_warga }}</td>
                        </tr>
                        <tr>
                            <td>Tempat, Tanggal Lahir</td>
                            <td>:</td>
                            <td>{{ $ttl }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td>{{ $jenis_kelamin }}</td>
                        </tr>
                        <tr>
                            <td>Nama Kepala Keluarga</td>
                            <td>:</td>
                            <td>{{ $kepala_keluarga }}</td>
                        </tr>
                        <tr>
                            <td>Bangsa</td>
                            <td>:</td>
                            <td>{{ $bangsa }}</td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td>:</td>
                            <td>{{ $agama }}</td>
                        </tr>
                        <tr>
                            <td>Status Perkawinan</td>
                            <td>:</td>
                            <td>{{ $status_perkawinan }}</td>
                        </tr>
                        <tr>
                            <td>Pendidikan Terakhir</td>
                            <td>:</td>
                            <td>{{ $pendidikan }}</td>
                        </tr>
                        <tr>
                            <td>Pekerjaan</td>
                            <td>:</td>
                            <td>{{ $pekerjaan }}</td>
                        </tr>
                        <tr>
                            <td>No. NIK</td>
                            <td>:</td>
                            <td>{{ $nik }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Alamat</td>
                            <td style="vertical-align: top;">:</td>
                            <td style="text-align: justify;">{{ $alamat }} RT.{{ $rt_nomor }}/RW.{{ $rw_nomor }} Kelurahan Padang Jati Kecamatan Gading Cempaka</td>
                        </tr>
                    </table>

                    <p style="text-align: justify; text-indent: 40px;">
                        Bahwa nama tersebut diatas adalah benar warga RT.{{ $rt_nomor }}/RW.{{ $rw_nomor }} Kelurahan Padang Jati Kecamatan Gading Cempaka dan tercatat dalam Buku Kependudukan. Yang bersangkutan datang menghadap Bapak Lurah untuk mengurus :
                    </p>

                    <!-- KOTAK KEPERLUAN -->
                    <div style="border: 2px solid black; padding: 10px; margin: 10px 0; min-height: 40px;">
                        <strong>{{ $keperluan }}</strong>
                    </div>

                    <p style="text-align: justify; text-indent: 40px;">
                        Demikian surat pengantar ini kami buat, untuk mendapatkan penyelesaian selanjutnya dan atas bantuannya diucapkan terima kasih.
                    </p>

                    <br>

                    <!-- SIGNATURE -->
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;"></td> <!-- Kosong Kiri -->
                            <td style="width: 50%; text-align: center;">
                                <p>Bengkulu, ' . date('d F Y') . '</p>
                                <p>Ketua RT.{{ $rt_nomor }} / RW.{{ $rw_nomor }}</p>
                                <p>Kelurahan Padang Jati</p>
                                <br><br>
                                <!-- Space for QR Code or Signature -->
                                <div style="height: 60px;">[QR_CODE_SPACE]</div>
                                <br>
                                <p style="font-weight: bold; text-decoration: underline;">[NAMA_KETUA_RT]</p>
                            </td>
                        </tr>
                    </table>
                </div>
            '
        ]);
        $this->command->info('Template Surat Pengantar RT (Official Format) berhasil diperbarui!');
    }
}
