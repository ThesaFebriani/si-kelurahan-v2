<!DOCTYPE html>
<html>
<head>
    <title>Laporan Statistik Pelayanan</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; color: #000; line-height: 1.4; }
        
        /* KOP SURAT */
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .header img { position: absolute; top: 0; left: 0; width: 65px; height: auto; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; font-weight: bold; }
        .header h3 { margin: 2px 0; font-size: 18px; text-transform: uppercase; font-weight: bold; }
        .header p { margin: 0; font-size: 11px; font-style: italic; }
        
        /* META INFO */
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        .meta-table td { padding: 4px; vertical-align: top; }
        .meta-label { width: 150px; font-weight: bold; }
        
        /* SECTION HEADERS */
        .section-header { 
            background-color: #f0f0f0; 
            border: 1px solid #000; 
            padding: 5px 10px; 
            font-weight: bold; 
            text-transform: uppercase;
            font-size: 11px;
            margin-top: 15px; 
            margin-bottom: 10px;
        }

        /* TABLES */
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px; text-align: left; }
        table.data th { background-color: #e0e0e0; font-weight: bold; text-align: center; font-size: 11px; }
        table.data td { font-size: 11px; }
        
        /* SUMMARY STATS (SIMPLE TABLE) */
        .summary-box { border: 1px solid #000; padding: 15px; margin-bottom: 20px; }
        .summary-title { font-weight: bold; text-decoration: underline; margin-bottom: 10px; }
        
        /* SIGNATURE */
        .signature { margin-top: 50px; page-break-inside: avoid; }
        .signature-table { width: 100%; }
        .signature-col { width: 40%; text-align: center; }

        /* UTILS */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <!-- KOP SURAT FORMAL -->
    <div class="header">
        <!-- Placeholder Logo -->
        <!-- <img src="{{ public_path('images/logo_bengkulu.png') }}" alt="Logo"> -->
        
        <h2>PEMERINTAH KOTA BENGKULU</h2>
        <h3>KECAMATAN RATU SAMBAN</h3>
        <h3>KELURAHAN PADANG JATI</h3>
        <p>Jl. S. Parman, Padang Jati, Kec. Ratu Samban, Kota Bengkulu 38227</p>
        <p>Website: padangjati.bengkulu.go.id | Email: kelurahan.padangjati@bengkulu.go.id</p>
    </div>

    <!-- JUDUL LAPORAN -->
    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="text-decoration: underline; margin: 0;">LAPORAN STATISTIK PELAYANAN</h3>
        <p style="margin: 5px 0 0;">Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</p>
    </div>

    <!-- RINGKASAN DATA -->
    <div class="section-header">I. RINGKASAN DATA</div>
    <table class="data">
        <thead>
            <tr>
                <th>Total Surat Masuk</th>
                <th>Selesai (Diambil)</th>
                <th>Ditolak / Batal</th>
                <th>Sedang Diproses</th>
                <th>Rata-rata Waktu (SLA)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center" style="font-size: 14px;"><strong>{{ $totalSurat }}</strong></td>
                <td align="center">{{ $suratSelesai }}</td>
                <td align="center">{{ $suratDitolak }}</td>
                <td align="center">{{ $suratProses }}</td>
                <td align="center">{{ $avgSLA }}</td>
            </tr>
        </tbody>
    </table>

    <!-- DETAIL LAYANAN -->
    <div class="section-header">II. STATISTIK PERMOHONAN</div>
    
    <table style="width: 100%; vertical-align: top;">
        <tr>
            <td style="width: 55%; padding-right: 15px; vertical-align: top;">
                <!-- TOP LAYANAN -->
                <strong>A. 5 Jenis Layanan Terpopuler</strong>
                <table class="data" style="margin-top: 5px;">
                    <thead>
                        <tr>
                            <th width="30">No</th>
                            <th>Jenis Surat</th>
                            <th width="50">Jml</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSurat as $index => $row)
                        <tr>
                            <td align="center">{{ $loop->iteration }}</td>
                            <td>{{ $row->jenisSurat->name }}</td>
                            <td align="center">{{ $row->total }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" align="center">- Data Kosong -</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- KINERJA RT -->
                <strong>B. Kinerja Persetujuan RT</strong>
                <table class="data" style="margin-top: 5px;">
                    <thead>
                        <tr>
                            <th>Nama RT</th>
                            <th width="50">Jml</th>
                            <th width="80">Avg. Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rtPerformance as $rt)
                        <tr>
                            <td>{{ $rt['nama'] }}</td>
                            <td align="center">{{ $rt['total'] }}</td>
                            <td align="center">{{ $rt['avg_time'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" align="center">- Data Kosong -</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
            
            <td style="width: 45%; vertical-align: top;">
                <!-- DEMOGRAFI -->
                <strong>C. Profil Pemohon (Berdasarkan Pekerjaan)</strong>
                <table class="data" style="margin-top: 5px;">
                    <thead>
                        <tr>
                            <th>Pekerjaan</th>
                            <th width="50">Jml</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demographics['jobs'] as $job => $count)
                        <tr>
                            <td>{{ $job ?: 'Lainnya' }}</td>
                            <td align="center">{{ $count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                 <!-- DEMOGRAFI USIA -->
                 <strong>D. Profil Pemohon (Berdasarkan Usia)</strong>
                 <table class="data" style="margin-top: 5px;">
                    <thead>
                        <tr>
                            <th>Kategori Usia</th>
                            <th width="50">Jml</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demographics['ageGroups'] as $group => $count)
                        <tr>
                            <td>{{ $group }}</td>
                            <td align="center">{{ $count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- PENGESAHAN -->
    <div class="signature">
        <table class="signature-table">
            <tr>
                <td class="signature-col">
                    <br>
                </td>
                <td style="width: 20%;"></td>
                <td class="signature-col">
                    Ditetapkan di: Bengkulu<br>
                    Pada Tanggal: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>
                    <br>
                    <strong>LURAH PADANG JATI</strong>
                    <br><br><br><br><br>
                    <strong><u>{{ $lurah ? $lurah->name : '.........................' }}</u></strong><br>
                    NIP. {{ $lurah->nip ?? '.........................' }}
                </td>
            </tr>
        </table>
    </div>

    <div style="font-size: 9px; margin-top: 30px; text-align: left; color: #666; font-style: italic;">
        Laporan ini dicetak otomatis oleh Sistem Informasi Kelurahan (SI-KELURAHAN) pada {{ now()->format('d/m/Y H:i:s') }}.<br>
        Oleh Pengguna: {{ $meta['user'] }}
    </div>

</body>
</html>
