<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PermohonanSurat;
use App\Models\ApprovalFlow;
use App\Models\JenisSurat;
use App\Models\User;
use App\Models\Rt;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // LOG ACCESS
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'view_report',
            'description' => 'Melihat Laporan Statistik',
            'model_type' => 'System',
            'model_id' => 0,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        // 1. Filter Setup
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $jenisSuratId = $request->input('jenis_surat_id');

        // 2. Base Query
        $query = PermohonanSurat::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($jenisSuratId) {
            $query->where('jenis_surat_id', $jenisSuratId);
        }

        // 3. Data Gathering
        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'jenisSuratList' => JenisSurat::where('is_active', true)->get(),
            'jenisSuratSelected' => $jenisSuratId,
            
            // STATS CARDS
            'totalSurat' => (clone $query)->count(),
            'suratSelesai' => (clone $query)->where('status', PermohonanSurat::SELESAI)->count(),
            'suratDitolak' => (clone $query)->whereIn('status', [PermohonanSurat::DITOLAK_RT, PermohonanSurat::DITOLAK_KASI, PermohonanSurat::DITOLAK_LURAH])->count(),
            'suratProses' => (clone $query)->whereNotIn('status', [
                PermohonanSurat::SELESAI, 
                PermohonanSurat::DIBATALKAN,
                PermohonanSurat::DITOLAK_RT, 
                PermohonanSurat::DITOLAK_KASI, 
                PermohonanSurat::DITOLAK_LURAH
            ])->count(),

            // ADVANCED INSIGHTS
            'avgSLA' => $this->calculateAvgSLA($startDate, $endDate, $jenisSuratId),
            'topSurat' => $this->getTopSurat($startDate, $endDate),
            'rtPerformance' => $this->getRtPerformance($startDate, $endDate),
            'dailyHeatmap' => $this->getDailyHeatmap($startDate, $endDate),
            'demographics' => $this->getDemographics($startDate, $endDate),
        ];

        return view('pages.admin.reports.index', $data);
    }

    // --- PRIVATE ANALYTICS METHODS ---

    /**
     * Hitung rata-rata waktu penyelesaian (SLA) dalam jam
     */
    private function calculateAvgSLA($start, $end, $jenisId = null)
    {
        $q = PermohonanSurat::where('status', PermohonanSurat::SELESAI)
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);
            
        if ($jenisId) $q->where('jenis_surat_id', $jenisId);

        // SQLite & MySQL compatible diff (roughly) or use PHP logic for flexibility
        // Using PHP collection for simplicity and DB agnostic behavior on small datasets, 
        // but for performance better use DB raw. Let's use Raw MySQL.
        
        $avgSeconds = $q->select(DB::raw('AVG(TIMESTAMPDIFF(SECOND, created_at, tanggal_selesai)) as avg_time'))->value('avg_time');

        if (!$avgSeconds) return '0 Jam';

        $hours = round($avgSeconds / 3600, 1);
        $days = round($hours / 24, 1);
        
        return $days > 1 ? "{$days} Hari" : "{$hours} Jam";
    }

    /**
     * Top 5 Jenis Surat Terpopuler
     */
    private function getTopSurat($start, $end)
    {
        return PermohonanSurat::select('jenis_surat_id', DB::raw('count(*) as total'))
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->groupBy('jenis_surat_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('jenisSurat')
            ->get();
    }

    /**
     * Kinerja RT: Jumlah Surat & Avg Response Time
     */
    private function getRtPerformance($start, $end)
    {
        // Get RTs and their approval stats
        // Complex query: Join ApprovalFlows -> Users -> Role RT
        
        // Simplified approach: iterate active RTs (assuming not thousands)
        $rts = Rt::with(['rw', 'users'])->where('is_active', true)->get();
        
        $performance = $rts->map(function($rt) use ($start, $end) {
            // Find RT User IDs
            $rtUserIds = $rt->users->where('role.name', 'rt')->pluck('id');
            if($rtUserIds->isEmpty()) return null;

            // Count Approvals by these users
            $stats = ApprovalFlow::whereIn('approved_by', $rtUserIds)
                ->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end)
                ->select(
                    DB::raw('count(*) as total_approved'),
                    DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, approved_at)) as avg_minutes')
                )
                ->first();

            return [
                'nama' => 'RT ' . $rt->nomor_rt,
                'total' => $stats->total_approved,
                'avg_time' => $stats->avg_minutes ? round($stats->avg_minutes / 60, 1) . ' Jam' : '-'
            ];
        })->filter()->sortByDesc('total')->values();

        return $performance;
    }

    /**
     * Heatmap Hari
     */
    private function getDailyHeatmap($start, $end)
    {
        return PermohonanSurat::select(DB::raw('DAYNAME(created_at) as day'), DB::raw('count(*) as total'))
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->groupBy('day')
            ->get()
            ->pluck('total', 'day');
    }

    /**
     * Demografi Pemohon
     */
    private function getDemographics($start, $end)
    {
        // Select DISTINCT users who applied
        $userIds = PermohonanSurat::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->pluck('user_id')
            ->unique();
            
        $users = User::with('anggotaKeluarga')->whereIn('id', $userIds)->get();

        // 1. By Service Age Group (derived from Birth Date in AnggotaKeluarga logic)
        // Since we don't have direct age in User, we rely on AnggotaKeluarga link using NIK
        
        $ages = $users->map(function($u) {
            if(!$u->anggotaKeluarga) return 'Unknown';
            return Carbon::parse($u->anggotaKeluarga->tanggal_lahir)->age;
        });

        $ageGroups = [
            'Remaja (17-25)' => $ages->filter(fn($a) => $a >= 17 && $a <= 25)->count(),
            'Dewasa (26-45)' => $ages->filter(fn($a) => $a > 25 && $a <= 45)->count(),
            'Lansia (>45)' => $ages->filter(fn($a) => $a > 45)->count(),
        ];

        // 2. By Job (Pekerjaan)
        $jobs = $users->map(function($u) {
            return $u->anggotaKeluarga?->pekerjaan ?? 'Lainnya';
        })->countBy()->sortDesc()->take(5);

        return [
            'ageGroups' => $ageGroups,
            'jobs' => $jobs
        ];
    }
}
