<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Pengantar RT - {{ $nomor_surat }}</title>
    <style>
        @page {
            margin: 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
        }

        .header-line {
            border-top: 2px solid #000;
            margin: 5px auto;
            width: 80%;
        }

        .nomor-surat {
            text-align: right;
            margin-bottom: 20px;
        }

        .content {
            text-align: justify;
        }

        .signature {
            text-align: center;
            margin-top: 60px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 40px auto 0;
            padding-top: 5px;
        }

        .qr-code {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 80px;
            height: 80px;
        }

        .verification-info {
            position: absolute;
            bottom: 20px;
            left: 20px;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>

<body>
    <!-- Header/Kop Surat -->
    <div class="header">
        <h1>Pemerintah Kelurahan</h1>
        <h2>Rukun Tetangga (RT) {{ $rt->nomor_rt }}</h2>
        <p>RW {{ $rt->rw->nomor_rw }}</p>
        <div class="header-line"></div>
    </div>

    <!-- Nomor dan Tanggal -->
    <div class="nomor-surat">
        <p><strong>Nomor : {{ $nomor_surat }}</strong></p>
        <p>Tanggal : {{ $tanggal_surat }}</p>
    </div>

    <!-- Perihal -->
    <div>
        <p><strong>Perihal : Surat Pengantar</strong></p>
        <p><strong>Lampiran : -</strong></p>
    </div>

    <!-- Tujuan -->
    <div style="margin-top: 20px;">
        <p>Kepada Yth.</p>
        <p><strong>Kepala Seksi {{ $permohonan->jenisSurat->bidang_display }}</strong></p>
        <p>di</p>
        <p><strong>Kelurahan Contoh</strong></p>
        <p><strong>di Tempat</strong></p>
    </div>

    <!-- Isi Surat -->
    <div class="content" style="margin-top: 20px;">
        <p>Dengan hormat,</p>

        <p style="text-indent: 40px;">
            Yang bertanda tangan di bawah ini Ketua RT {{ $rt->nomor_rt }},
            menerangkan bahwa:
        </p>

        <div style="margin-left: 60px; margin-top: 10px;">
            <p>Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                <strong>{{ $user->name }}</strong>
            </p>
            <p>NIK &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                <strong>{{ $data_pemohon['nik'] ?? '-' }}</strong>
            </p>
            <p>Tempat/Tgl Lahir &nbsp;:
                <strong>{{ $data_pemohon['tempat_lahir'] ?? '-' }},
                    {{ isset($data_pemohon['tanggal_lahir']) ? \Carbon\Carbon::parse($data_pemohon['tanggal_lahir'])->format('d/m/Y') : '-' }}</strong>
            </p>
            <p>Alamat &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                <strong>{{ $user->alamat_lengkap }}</strong>
            </p>
            <p>Jenis Surat &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                <strong>{{ $permohonan->jenisSurat->name }}</strong>
            </p>
        </div>

        <p style="text-indent: 40px; margin-top: 10px;">
            Adalah benar warga RT {{ $rt->nomor_rt }} dan berdasarkan data yang ada,
            yang bersangkutan membutuhkan {{ $permohonan->jenisSurat->name }} untuk keperluan:
        </p>

        <div style="margin-left: 60px; margin-top: 10px; padding: 10px; background-color: #f5f5f5;">
            <p><strong>{{ $data_pemohon['tujuan'] ?? 'Keperluan administrasi' }}</strong></p>
        </div>

        <p style="text-indent: 40px; margin-top: 10px;">
            Demikian surat pengantar ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature">
        <p>Hormat kami,</p>
        <p>Ketua RT {{ $rt->nomor_rt }}</p>
        <div class="signature-line"></div>
        <p><strong>{{ Auth::check() ? Auth::user()->name : 'Ketua RT' }}</strong></p>
    </div>

    <!-- QR Code untuk verifikasi (akan diimplementasi nanti) -->
    <div class="verification-info">
        <p>Verifikasi: scan QR code</p>
        <p>Nomor Tiket: {{ $permohonan->nomor_tiket }}</p>
        <p>Tanggal: {{ $tanggal_surat }}</p>
    </div>

    <!-- QR Code Placeholder -->
    <div class="qr-code">
        <!-- QR Code akan ditambahkan nanti -->
        <div style="border: 1px dashed #ccc; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 8pt; color: #999;">
            QR Code<br>Verifikasi
        </div>
    </div>
</body>

</html>