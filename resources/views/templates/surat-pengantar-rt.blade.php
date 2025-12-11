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
            line-height: 1.5; /* 1.5 Spacing */
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            /* Ensure logo doesn't overlap text if we use absolute, or use grid/flex (not supported well in DomPDF sometimes). 
               Standard approach: Absolute positioning for logo is fine if text has padding or is centered irrelevant of logo.
            */
        }

        .header img {
            position: absolute;
            left: 0;
            top: 0;
            width: 70px; 
            height: auto;
        }

        /* Adjust text container to not overlap logo if needed, but usually centered text is fine. 
           If logo is floating left, we might need to ensure text is truly centered relative to page, not remaining space.
           Absolute logo allows text to be centered to page.
        */

        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 13pt; /* Slightly bigger for Kecamatan/Kelurahan */
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header h3 {
             font-size: 11pt;
             font-weight: bold; 
             margin: 0;
             text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 10pt;
            font-style: normal;
        }

        .header-line {
            /* Double line style */
            border-top: 1px solid #000;
            border-bottom: 3px solid #000; /* Create effect of specific double line thickness logic if needed, or use border-style: double */
            border-bottom-style: double;
            border-bottom-width: 3px; 
             /* Or simpler: Use a standard double border */
            border: 0;
            border-bottom: 3px double #000;
            margin-top: 10px;
            margin-bottom: 25px;
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
    <!-- Isi Surat -->
    <div class="content">
        @if(!empty($isi_surat))
            {!! $isi_surat !!}
        @else
            <p style="color: red; text-align: center;">[Konten Surat Kosong]</p>
        @endif
    </div>

    <!-- Footer / Tanda Tangan -->
    <div class="footer clearfix">
        <div class="signature">
            <p>Bengkulu, {{ $tanggal_surat }}</p>
            <p>Ketua RT.{{ $rt->nomor_rt }} / RW.{{ $rt->rw->nomor_rw }} Kelurahan Padang Jati</p>
            
            <div style="height: 80px; margin: 10px 0;">
                <!-- QR Code Signature Placeholder -->
                @if(isset($qr_code))
                    <img src="data:image/svg+xml;base64,{{ $qr_code }}" alt="QR Code" width="80" height="80">
                @endif
            </div>

            <p style="text-decoration: underline; font-weight: bold; text-transform: uppercase;">{{ $verificator_name }}</p>
        </div>
    </div>
</body>
</html>