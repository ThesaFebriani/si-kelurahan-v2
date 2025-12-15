<!DOCTYPE html>
<html>
<head>
    <title>Laporan Audit Log Sistem</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; }
        
        .meta { margin-bottom: 20px; font-size: 11px; }
        .meta table { width: 100%; border: none; }
        .meta td { padding: 2px; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        table.data th { bg-color: #f0f0f0; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        table.data tr:nth-child(even) { bg-color: #fafafa; }
        
        .badge { display: inline-block; padding: 2px 5px; border-radius: 3px; font-size: 10px; color: white; background-color: #6c757d; } /* Default gray */
        .bg-create, .bg-approve, .bg-restore { background-color: #28a745; }
        .bg-update, .bg-login, .bg-view_report { background-color: #17a2b8; } /* Cyan */
        .bg-delete, .bg-reject { background-color: #dc3545; } /* Red */
        .bg-logout { background-color: #6c757d; } /* Gray */
        .bg-change_password { background-color: #ffc107; color: #000; } /* Yellow */
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Aktivitas Sistem (Audit Log)</h1>
        <p>Sistem Informasi Pelayanan Kelurahan</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td width="15%"><strong>Periode:</strong></td>
                <td>{{ $meta['range'] }}</td>
                <td width="15%"><strong>Dicetak Oleh:</strong></td>
                <td>{{ $meta['user'] }}</td>
            </tr>
            <tr>
                <td><strong>Total Record:</strong></td>
                <td>{{ $logs->count() }} baris</td>
                <td><strong>Tanggal Cetak:</strong></td>
                <td>{{ $meta['generated_at'] }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="15%">Waktu</th>
                <th width="20%">User (Pelaku)</th>
                <th width="10%">Aksi</th>
                <th width="20%">Target Data</th>
                <th width="20%">Deskripsi</th>
                <th width="10%">IP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $index => $log)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                <td>
                    <b>{{ $log->user->name ?? 'SYSTEM' }}</b><br>
                    <small>{{ $log->user->role->name ?? '-' }}</small>
                </td>
                <td>
                    <span class="badge bg-{{ $log->action }}">
                        {{ strtoupper($log->action) }}
                    </span>
                </td>
                <td>
                    {{ basename(str_replace('\\', '/', $log->model_type)) }} #{{ $log->model_id }}
                </td>
                <td>{{ $log->description }}</td>
                <td>{{ $log->ip_address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right; font-size: 11px;">
        <p><em>Dokumen ini digenerate otomatis oleh sistem.</em></p>
    </div>
</body>
</html>
