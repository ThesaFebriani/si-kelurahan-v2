<?php

namespace App\Http\Controllers\Lurah;

use App\Http\Controllers\Controller;
use App\Models\PermohonanSurat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Existing Stats
        $stats = [
            'pending_permohonan' => PermohonanSurat::where('status', PermohonanSurat::MENUNGGU_LURAH)->count(),
            'completed_permohonan' => PermohonanSurat::where('status', PermohonanSurat::SELESAI)->count(),
            'total_permohonan' => PermohonanSurat::whereIn('status', [
                PermohonanSurat::MENUNGGU_LURAH,
                PermohonanSurat::SELESAI
            ])->count(),
        ];

        // 2. Executive Insights (New)

        // A. Service Level Agreement (SLA): Rata-rata waktu proses (Jam)
        // Hitung selisih created_at (pengajuan) sampai updated_at (saat status changed to selesai)
        $completedSurats = PermohonanSurat::where('status', PermohonanSurat::SELESAI)
            ->whereNotNull('updated_at')
            ->get();

        $totalHours = 0;
        $countSLA = $completedSurats->count();

        foreach ($completedSurats as $surat) {
            // Gunakan updated_at sebagai proxy 'tanggal_selesai' jika null
            $end = $surat->tanggal_selesai ?? $surat->updated_at;
            $start = $surat->created_at;
            // Abs method ensures positive value regardless of order
            $totalHours += $start->diffInHours($end, true);
        }

        $avgSLA = $countSLA > 0 ? round($totalHours / $countSLA, 1) : 0;

        // B1. Demografi per RW (Doughnut Chart)
        $demografiRW = PermohonanSurat::join('users', 'permohonan_surats.user_id', '=', 'users.id')
            ->join('rt', 'users.rt_id', '=', 'rt.id')
            ->join('rw', 'rt.rw_id', '=', 'rw.id')
            ->selectRaw('rw.nomor_rw, count(*) as total')
            ->groupBy('rw.nomor_rw')
            ->orderBy('total', 'desc')
            ->pluck('total', 'rw.nomor_rw')
            ->toArray();

        // B2. Demografi per RT (Bar Chart - Top 10)
        $demografiRT = PermohonanSurat::join('users', 'permohonan_surats.user_id', '=', 'users.id')
            ->join('rt', 'users.rt_id', '=', 'rt.id')
            ->join('rw', 'rt.rw_id', '=', 'rw.id')
            ->selectRaw('concat("RT ", rt.nomor_rt, " / RW ", rw.nomor_rw) as label, count(*) as total')
            ->groupBy('label')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->pluck('total', 'label')
            ->toArray();

        // B3. Demografi per Jenis Surat (Pie Chart - Top 5)
        $jenisSuratChart = PermohonanSurat::join('jenis_surats', 'permohonan_surats.jenis_surat_id', '=', 'jenis_surats.id')
            ->selectRaw('jenis_surats.name as label, count(*) as total')
            ->groupBy('label')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->pluck('total', 'label')
            ->toArray();

        // C. Tren Layanan Bulanan (Line Chart)
        $monthlyTrend = PermohonanSurat::selectRaw('MONTH(created_at) as month, count(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill zero for missing months
        $trendData = [];
        for ($i = 1; $i <= 12; $i++) {
            $trendData[] = $monthlyTrend[$i] ?? 0;
        }

        // D. Bottleneck Analysis (Rata-rata waktu per tahap)
        $bottleneckData = [
            'rt' => 0,
            'kasi' => 0,
            'lurah' => 0
        ];

        $timelines = \App\Models\TimelinePermohonan::select('permohonan_surat_id', 'status', 'created_at')
            ->orderBy('permohonan_surat_id')
            ->orderBy('created_at')
            ->get()
            ->groupBy('permohonan_surat_id');

        $counts = ['rt' => 0, 'kasi' => 0, 'lurah' => 0];

        foreach ($timelines as $id => $logs) {
            $startRT = $logs->where('status', PermohonanSurat::MENUNGGU_RT)->first();
            $endRT = $logs->where('status', PermohonanSurat::DISETUJUI_RT)->first();

            $startKasi = $logs->where('status', PermohonanSurat::MENUNGGU_KASI)->first();
            $endKasi = $logs->where('status', PermohonanSurat::DISETUJUI_KASI)->first();

            $startLurah = $logs->where('status', PermohonanSurat::MENUNGGU_LURAH)->first();
            $endLurah = $logs->where('status', PermohonanSurat::SELESAI)->first();

            if ($startRT && $endRT) {
                // Use absolute difference to prevent negative values
                $bottleneckData['rt'] += $startRT->created_at->diffInHours($endRT->created_at, true);
                $counts['rt']++;
            }
            if ($startKasi && $endKasi) {
                $bottleneckData['kasi'] += $startKasi->created_at->diffInHours($endKasi->created_at, true);
                $counts['kasi']++;
            }
            if ($startLurah && $endLurah) {
                $bottleneckData['lurah'] += $startLurah->created_at->diffInHours($endLurah->created_at, true);
                $counts['lurah']++;
            }
        }

        // Average
        $bottleneckData['rt'] = $counts['rt'] > 0 ? round($bottleneckData['rt'] / $counts['rt'], 1) : 0;
        $bottleneckData['kasi'] = $counts['kasi'] > 0 ? round($bottleneckData['kasi'] / $counts['kasi'], 1) : 0;
        $bottleneckData['lurah'] = $counts['lurah'] > 0 ? round($bottleneckData['lurah'] / $counts['lurah'], 1) : 0;

        // E. Indeks Kepuasan Masyarakat (IKM)
        $ratingData = \App\Models\SurveiKepuasan::selectRaw('avg(rating) as rata_rata, count(*) as total')->first();
        $indeksKepuasan = $ratingData ? round($ratingData->rata_rata, 1) : 0;
        $totalResponden = $ratingData ? $ratingData->total : 0;

        $recentPermohonan = PermohonanSurat::with(['user.rt.rw', 'jenisSurat'])
            ->where('status', PermohonanSurat::MENUNGGU_LURAH)
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.lurah.dashboard', compact('stats', 'recentPermohonan', 'avgSLA', 'demografiRW', 'demografiRT', 'jenisSuratChart', 'trendData', 'bottleneckData', 'indeksKepuasan', 'totalResponden'));
    }
}
