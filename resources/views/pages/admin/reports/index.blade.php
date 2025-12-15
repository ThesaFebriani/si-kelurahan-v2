@extends('components.layout')

@section('title', 'Laporan & Statistik')
@section('page-title', 'Laporan & Statistik')
@section('page-description', 'Insight mendalam tentang kinerja pelayanan surat dan demografi warga.')

@section('content')
<div class="space-y-6">

    <!-- 1. FILTER SECTION -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <!-- Start Date -->
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-sm">
            </div>

            <!-- End Date -->
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-sm">
            </div>

            <!-- Jenis Surat -->
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Jenis Surat</label>
                <select name="jenis_surat_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 text-sm">
                    <option value="">-- Semua Jenis Surat --</option>
                    @foreach($jenisSuratList as $jenis)
                    <option value="{{ $jenis->id }}" {{ $jenisSuratSelected == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Button -->
            <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                <i class="fas fa-filter"></i> Terapkan Filter
            </button>
            
            <!-- Reset Button -->
            <a href="{{ route('admin.reports.index') }}" class="w-full sm:w-auto px-6 py-2 bg-slate-100 text-slate-600 font-medium rounded-lg hover:bg-slate-200 transition flex items-center justify-center gap-2">
                <i class="fas fa-undo"></i> Reset
            </a>

             <!-- Export Button -->
             <a href="{{ route('admin.reports.export', request()->query()) }}" target="_blank" class="w-full sm:w-auto px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                <i class="fas fa-file-pdf"></i> Export Laporan
            </a>
        </form>
    </div>

    <!-- 2. SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="text-slate-500 text-sm font-medium mb-1">Total Surat Masuk</div>
                <div class="text-3xl font-bold text-slate-800">{{ $totalSurat }}</div>
            </div>
            <div class="absolute right-0 top-0 h-full w-24 bg-blue-50 group-hover:bg-blue-100 transition-colors skew-x-12 -mr-6"></div>
            <div class="absolute right-5 top-5 text-blue-500 text-2xl z-20"><i class="fas fa-inbox"></i></div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="text-slate-500 text-sm font-medium mb-1">Selesai (Approved)</div>
                <div class="text-3xl font-bold text-emerald-600">{{ $suratSelesai }}</div>
            </div>
            <div class="absolute right-0 top-0 h-full w-24 bg-emerald-50 group-hover:bg-emerald-100 transition-colors skew-x-12 -mr-6"></div>
            <div class="absolute right-5 top-5 text-emerald-500 text-2xl z-20"><i class="fas fa-check-circle"></i></div>
        </div>

        <!-- Card 3: SLA -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 relative overflow-hidden group ring-2 ring-indigo-50">
            <div class="relative z-10">
                <div class="text-indigo-900/60 text-sm font-medium mb-1">Rata-rata Durasi (SLA)</div>
                <div class="text-3xl font-bold text-indigo-600">{{ $avgSLA }}</div>
            </div>
            <div class="absolute right-0 top-0 h-full w-24 bg-indigo-50 group-hover:bg-indigo-100 transition-colors skew-x-12 -mr-6"></div>
            <div class="absolute right-5 top-5 text-indigo-500 text-2xl z-20"><i class="fas fa-stopwatch"></i></div>
        </div>
        
        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="text-slate-500 text-sm font-medium mb-1">Ditolak / Batal</div>
                <div class="text-3xl font-bold text-red-600">{{ $suratDitolak }}</div>
            </div>
            <div class="absolute right-0 top-0 h-full w-24 bg-red-50 group-hover:bg-red-100 transition-colors skew-x-12 -mr-6"></div>
            <div class="absolute right-5 top-5 text-red-500 text-2xl z-20"><i class="fas fa-times-circle"></i></div>
        </div>
    </div>

    <!-- 3. MAIN CHARTS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Trends & Demographics -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Daily Heatmap Chart -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Tren Kesibukan Harian</h3>
                        <p class="text-slate-500 text-sm">Jumlah permohonan surat per hari</p>
                    </div>
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                        <i class="fas fa-chart-line mr-1"></i> Heatmap
                    </div>
                </div>
                <!-- Canvas Chart -->
                <div class="h-64">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <!-- RT Performance -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                   <div>
                        <h3 class="font-bold text-slate-800 text-lg">Kinerja Per-RT</h3>
                        <p class="text-slate-500 text-sm">Volume persetujuan surat & kecepatan respon</p>
                   </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">Nama RT</th>
                                <th class="px-6 py-3 text-center">Total Surat Diproses</th>
                                <th class="px-6 py-3 text-right">Avg. Kecepatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($rtPerformance as $rt)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-700">{{ $rt['nama'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded text-xs font-bold">{{ $rt['total'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="{{ strpos($rt['avg_time'], 'Jam') !== false ? 'text-emerald-600' : 'text-slate-600' }} font-bold text-sm">
                                        {{ $rt['avg_time'] }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada data kinerja RT</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right Column: Sidebar Insights -->
        <div class="space-y-6">
            
            <!-- Top Surat -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-800 mb-4">🏆 Top 5 Layanan</h3>
                <div class="space-y-4">
                    @forelse($topSurat as $index => $row)
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                            {{ $loop->iteration }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-slate-700 truncate" title="{{ $row->jenisSurat->name }}">
                                {{ $row->jenisSurat->name }}
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ ($row->total / $totalSurat * 100) }}%"></div>
                            </div>
                        </div>
                        <div class="text-sm font-bold text-slate-900">{{ $row->total }}</div>
                    </div>
                    @empty
                    <div class="text-slate-400 text-sm text-center py-4">Belum ada data</div>
                    @endforelse
                </div>
            </div>

            <!-- Demographics: Jobs -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-800 mb-4">👥 Pekerjaan Pemohon</h3>
                <ul class="space-y-3">
                    @foreach($demographics['jobs'] as $job => $count)
                    <li class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">{{ $job ?: 'Tidak Disebutkan' }}</span>
                        <span class="font-bold text-slate-800">{{ $count }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Demographics: Age -->
             <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <h3 class="font-bold text-slate-800 mb-4">🎂 Kategori Usia</h3>
                <div class="h-48 relative">
                     <canvas id="ageChart"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Daily Heatmap Chart
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        const dailyData = @json($dailyHeatmap);
        
        // Sort days: Sunday to Saturday
        const daysMap = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const dataValues = daysMap.map(day => dailyData[day] || 0);
        
        // Translate labels to ID
        const dayLabels = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dayLabels,
                datasets: [{
                    label: 'Jumlah Permohonan',
                    data: dataValues,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4] }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. Age Pie Chart
        const ageCtx = document.getElementById('ageChart').getContext('2d');
        const ageGroups = @json($demographics['ageGroups']);
        
        new Chart(ageCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(ageGroups),
                datasets: [{
                    data: Object.values(ageGroups),
                    backgroundColor: [
                        '#60a5fa', // Remaja - Blue
                        '#34d399', // Dewasa - Green
                        '#fbbf24'  // Lansia - Yellow
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endsection
