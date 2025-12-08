<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Kelurahan</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; }
        .content { margin-top: 20px; text-align: justify; }
        .signature-block { margin-top: 50px; float: right; width: 40%; text-align: center; }
        .qr-code { margin-bottom: 10px; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <!-- Optional: Header Kop Surat -->
    </div>
    
    <div class="content">
        {!! $isi_surat !!}
    </div>
    
    <div class="signature-block">
        <p>Karawang, {{ $tanggal_surat }}</p>
        <p>Lurah Desa</p>
        
        <div class="qr-code">
            <img src="data:image/svg+xml;base64, {{ $qr_code }}" alt="QR Code" width="100">
        </div>
        
        <p style="font-weight: bold; text-decoration: underline;">{{ $verificator_name }}</p>
        <p>Dokumen ini telah ditandatangani secara elektronik</p>
    </div>
    
    <div class="clear"></div>
</body>
</html>
