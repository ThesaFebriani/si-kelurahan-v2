<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pengantar RT</title>
</head>
<body style="font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.15;">

    <!-- 1. KOP SURAT (Disederhanakan untuk Placeholder Mode: Biarkan Admin lihat Placeholdernya saja untuk data RT) -->
    <table style="width: 100%; border-bottom: 3px double #000; margin-bottom: 5px; padding-bottom: 5px;">
        <tr>
            <td style="width: 80px; text-align: center; vertical-align: top;">
                <img src="{{ public_path('images/logo_kota_bengkulu.png') }}" alt="Logo" style="width: 65px; height: auto;">
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <h3 style="margin: 0; font-size: 12pt; font-weight: normal; letter-spacing: 1px; text-transform: uppercase;">PEMERINTAH KOTA BENGKULU</h3>
                <h2 style="margin: 0; font-size: 13pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px;">KECAMATAN GADING CEMPAKA</h2> 
                <h1 style="margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px;">KELURAHAN PADANG JATI</h1>
                <h4 style="margin: 0; font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px;">RUKUN TETANGGA [NOMOR_RT] / RUKUN WARGA [NOMOR_RW]</h4>
                <p style="margin: 0; font-size: 9pt; font-style: normal;">Sekretariat: [ALAMAT_SEKRETARIAT] HP. [NO_HP_RT]</p>
            </td>
        </tr>
    </table>

    <!-- 2. INFO SURAT -->
    <table style="width: 100%; margin-top: 5px; margin-bottom: 10px;">
        <tr>
            <td style="width: 55%; vertical-align: top; font-size: 11pt;">
                <table style="width: 100%;">
                    <tr><td style="width: 80px; padding-bottom: 2px;">Nomor</td><td>: ... / RT.[NOMOR_RT] / ... / [TAHUN]</td></tr>
                    <tr><td style="padding-bottom: 2px;">Lampiran</td><td>: -</td></tr>
                    <tr><td style="padding-bottom: 2px;">Perihal</td><td>: <span style="text-decoration: underline;">Surat Pengantar</span></td></tr>
                </table>
            </td>
            <td style="width: 45%; vertical-align: top; padding-left: 10px; font-size: 11pt;">
                Kepada Yth,<br>
                Bapak Lurah Padang Jati<br>
                Di-<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BENGKULU
            </td>
        </tr>
    </table>

    <!-- 3. ISI SURAT (Pakai Placeholder!) -->
    <div style="margin-top: 5px; text-align: justify;">
        <p style="margin-bottom: 5px;">Dengan Hormat,</p>
        <p style="text-indent: 40px; margin-bottom: 10px;">Yang bertanda tangan dibawah ini, Ketua RT.[NOMOR_RT]/RW.[NOMOR_RW] Kelurahan Padang Jati Kecamatan Gading Cempaka Kota Bengkulu, dengan ini menerangkan bahwa :</p>

        <table style="width: 100%; margin-left: 40px; margin-top: 5px; margin-bottom: 10px; font-size: 11pt;">
            <tr><td style="width: 170px; padding-bottom: 3px;">Nama</td><td style="width: 15px; text-align: center;">:</td><td style="font-weight: bold;">[NAMA_WARGA]</td></tr>
            <tr><td style="padding-bottom: 3px;">Tempat, Tanggal Lahir</td><td style="text-align: center;">:</td><td>[TTL_WARGA]</td></tr>
            <tr><td style="padding-bottom: 3px;">Jenis Kelamin</td><td style="text-align: center;">:</td><td>[JENIS_KELAMIN]</td></tr>
            <tr><td style="padding-bottom: 3px;">Nama Kepala Keluarga</td><td style="text-align: center;">:</td><td>[KEPALA_KELUARGA]</td></tr>
            <tr><td style="padding-bottom: 3px;">Bangsa</td><td style="text-align: center;">:</td><td>Indonesia</td></tr>
            <tr><td style="padding-bottom: 3px;">Agama</td><td style="text-align: center;">:</td><td>[AGAMA]</td></tr>
            <tr><td style="padding-bottom: 3px;">Status Perkawinan</td><td style="text-align: center;">:</td><td>[STATUS_PERKAWINAN]</td></tr>
            <tr><td style="padding-bottom: 3px;">Pendidikan Terakhir</td><td style="text-align: center;">:</td><td>[PENDIDIKAN]</td></tr>
            <tr><td style="padding-bottom: 3px;">Pekerjaan</td><td style="text-align: center;">:</td><td>[PEKERJAAN]</td></tr>
            <tr><td style="padding-bottom: 3px;">No. NIK</td><td style="text-align: center;">:</td><td>[NIK]</td></tr>
            <tr><td style="padding-bottom: 3px; vertical-align: top;">Alamat</td><td style="text-align: center; vertical-align: top;">:</td><td>[ALAMAT_WARGA] <br> RT.[NOMOR_RT]/RW.[NOMOR_RW] Kelurahan Padang Jati <br> Kecamatan Gading Cempaka</td></tr>
        </table>

        <p style="text-indent: 40px; margin-bottom: 10px;">Bahwa nama tersebut diatas adalah benar warga RT.[NOMOR_RT]/RW.[NOMOR_RW] Kelurahan Padang Jati Kecamatan Gading Cempaka dan tercatat dalam Buku Kependudukan. Yang bersangkutan datang menghadap Bapak Lurah untuk mengurus :</p>

        <!-- KOTAK KOSONG UNTUK TULIS TANGAN (Tanpa Placeholder karena memang harus kosong) -->
        <div style="border: 1px solid #000; padding: 5px 10px; margin: 5px 0 15px 0; min-height: 40px;">
            &nbsp;
        </div>

        <p style="text-indent: 40px;">Demikian surat pengantar ini kami buat, untuk mendapatkan penyelesaian selanjutnya dan atas bantuannya diucapkan terima kasih.</p>
    </div>

    <!-- 4. TANDA TANGAN -->
    <div style="margin-top: 20px; float: right; width: 40%; text-align: center; font-size: 11pt;">
        <p style="margin: 0;">Bengkulu, [TANGGAL_SURAT]</p>
        <p style="margin: 0;">Ketua RT.[NOMOR_RT] / RW.[NOMOR_RW] Kelurahan Padang Jati</p>
        
        <div style="height: 70px;">
             <!-- LOGIKA TTD & QR CODE TETAP DISIMPAN TAPI ADMIN LIHAT PLACEHOLDER SAJA -->
             <!-- Di Controller nanti kita akan replace [QR_CODE_SPACE] dengan logika PHP asli -->
             [QR_CODE_SPACE]
        </div>

        <p style="text-decoration: underline; font-weight: bold; text-transform: uppercase; margin: 0;">[NAMA_KETUA_RT]</p>
    </div>

</body>
</html>