<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pengantar RT</title>
</head>
<body style="font-family: 'Times New Roman', Times, serif; font-size: 11pt; line-height: 1.15;">

    <!-- 1. KONTEN SURAT (Dinamis dari Database) -->
    <!-- Template ini sekarang sepenuhnya diatur oleh Admin melalui menu Pengaturan Surat Pengantar -->
    <!-- Variable $isi_surat berisi HTML lengkap mulai dari Kop, Isi, hingga Tanda Tangan -->
    
    <div class="content">
        {!! $isi_surat !!}
    </div>

</body>
</html>