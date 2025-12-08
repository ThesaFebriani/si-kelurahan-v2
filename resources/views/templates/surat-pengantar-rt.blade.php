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
            position: relative;
        }

        .header img {
            position: absolute;
            left: 0;
            top: 0;
            height: 75px;
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

        .header p {
            margin: 0;
            font-size: 11pt;
        }

        .header-line {
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            margin-top: 5px;
            margin-bottom: 20px;
            height: 2px;
        }

        .nomor-surat {
            text-align: center;
            margin-bottom: 20px;
            text-decoration: underline;
            font-weight: bold;
        }

        .content {
            text-align: justify;
        }
    
        /* Helper classes for tables in content if used */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .table-data td {
            vertical-align: top;
            padding: 2px 0;
        }
        .table-data td:first-child {
            width: 140px;
        }
        .table-data td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .signature {
            float: right;
            width: 40%;
            text-align: center;
            margin-top: 30px;
        }

        /* Clearfix */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .footer {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <!-- Header/Kop Surat -->
    <div class="header">
        <!-- Logo -->
        <img src="{{ public_path('images/logo-kota-bengkulu.png') }}" alt="Logo">
        
        <h1>PEMERINTAH KOTA BENGKULU</h1>
        <h2>KECAMATAN MUARA BANGKAHULU</h2>
        <h2>KELURAHAN PEMATANG GUBERNUR</h2>
        <h2>RUKUN TETANGGA {{ $rt->nomor_rt }} RUKUN WARGA {{ $rt->rw->nomor_rw }}</h2>
        <div class="header-line"></div>
    </div>

    <!-- Judul Surat -->
    <div class="nomor-surat">
        SURAT PENGANTAR
    </div>
    <div style="text-align: center; margin-top: -15px; margin-bottom: 20px;">
        Nomor: {{ $nomor_surat }}
    </div>

    <!-- Isi Surat -->
    <div class="content">
        @if(!empty($isi_surat))
            {!! $isi_surat !!}
        @else
            <!-- Fallback Default Content jika isi_surat kosong (untuk backward compatibility) -->
            <p>Yang bertanda tangan di bawah ini Ketua RT {{ $rt->nomor_rt }} Kelurahan Pematang Gubernur, Kecamatan Muara Bangkahulu, Kota Bengkulu, menerangkan bahwa:</p>

            <table class="table-data">
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><strong>{{ $user->name }}</strong></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $data_pemohon['nik'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tempat/Tgl Lahir</td>
                    <td>:</td>
                    <td>{{ $data_pemohon['tempat_lahir'] ?? '-' }}, {{ isset($data_pemohon['tanggal_lahir']) ? \Carbon\Carbon::parse($data_pemohon['tanggal_lahir'])->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ isset($data_pemohon['jenis_kelamin']) ? ($data_pemohon['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan') : '-' }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ $data_pemohon['pekerjaan'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>{{ $data_pemohon['agama'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $user->alamat_lengkap }}</td>
                </tr>
            </table>

            <p>Orang tersebut diatas adalah benar-benar warga kami yang berdomisili di RT {{ $rt->nomor_rt }} RW {{ $rt->rw->nomor_rw }} Kelurahan Pematang Gubernur. Surat pengantar ini diberikan untuk keperluan:</p>

            <div style="margin: 10px 0; padding: 10px; border: 1px solid #eee; background: #f9f9f9; font-weight: bold; text-align: center;">
                {{ $data_pemohon['tujuan'] ?? 'Pengurusan Administrasi' }}
            </div>

            <p>Demikian surat pengantar ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
        @endif
    </div>

    <!-- Footer / Tanda Tangan -->
    <div class="footer clearfix">
        <div class="signature">
            <p>Bengkulu, {{ $tanggal_surat }}</p>
            <p>Ketua RT {{ $rt->nomor_rt }}</p>
            
            <!-- QR Code Signature -->
            <div style="margin: 10px auto;">
                @if(isset($qr_code))
                    <img src="data:image/svg+xml;base64,{{ $qr_code }}" alt="QR Code" width="80" height="80">
                @endif
            </div>

            <p style="text-decoration: underline; font-weight: bold;">{{ $verificator_name }}</p>
            <p style="font-size: 10pt;">Dokumen ini ditandatangani secara elektronik</p>
        </div>
    </div>
</body>
</html>