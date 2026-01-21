@extends('components.layout')

@section('title', 'Dashboard Lurah')
@section('page-title', 'Dashboard Lurah')
@section('page-description', 'Selamat datang di dashboard Lurah')

@section('content')
<div class="space-y-6">
    
    <!-- ROW 1: OPERATIONAL COUNTS (PRIORITY) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Menunggu TTE -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 relative overflow-hidden">
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Menunggu TTE</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['pending_permohonan'] ?? 0 }}</h3>
                </div>
                <div class="p-2.5 bg-purple-50 rounded-xl">
                    <i class="fas fa-signature text-purple-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('lurah.tanda-tangan.index') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 mt-3 inline-flex items-center">
                Proses Sekarang <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Selesai Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['completed_permohonan'] ?? 0 }}</h3>
                </div>
                <div class="p-2.5 bg-green-50 rounded-xl">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-slate-400 text-[10px] mt-3">Dokumen diterbitkan</p>
        </div>

        <!-- Total -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Total Permohonan</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_permohonan'] ?? 0 }}</h3>
                </div>
                <div class="p-2.5 bg-blue-50 rounded-xl">
                    <i class="fas fa-folder-open text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-slate-400 text-[10px] mt-3">Akumulasi tahun berjalan</p>
        </div>
    </div>

    <!-- ROW 2: EXECUTIVE METRICS (QUALITY & SPEED) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- SLA Monitoring -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl shadow-lg p-4 text-white flex items-center justify-between relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-indigo-100 text-[10px] font-medium uppercase tracking-wider mb-1">Rata-rata Waktu Proses</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold">{{ $avgSLA }}</h3>
                    <span class="text-xs font-medium opacity-80">Jam</span>
                </div>
                <p class="text-indigo-200 text-[10px] mt-2 opacity-80">Dari pengajuan s.d. selesai</p>
            </div>
            <div class="p-2.5 bg-white/20 rounded-xl backdrop-blur-sm z-10">
                <i class="fas fa-stopwatch text-white text-xl"></i>
            </div>
            <!-- Decorative Circle -->
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full"></div>
        </div>

        <!-- Indeks Kepuasan (SKM) -->
        <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl shadow-lg p-4 text-white flex items-center justify-between relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-yellow-100 text-[10px] font-medium uppercase tracking-wider mb-1">Indeks Kepuasan</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold">{{ $indeksKepuasan }}</h3>
                    <span class="text-xs font-medium opacity-80">/ 5.0</span>
                </div>
                <p class="text-yellow-100 text-[10px] mt-2 opacity-80">{{ $totalResponden }} Responden</p>
            </div>
            <div class="p-2.5 bg-white/20 rounded-xl backdrop-blur-sm z-10">
                <i class="fas fa-star text-white text-xl"></i>
            </div>
             <!-- Decorative Circle -->
             <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full"></div>
        </div>
    </div>

    <!-- CHARTS SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Main Chart: Tren Layanan (Lebih kecil) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <h3 class="font-bold text-slate-700 mb-2 flex items-center text-sm">
                <i class="fas fa-chart-line text-blue-500 mr-2"></i> Tren Layanan Bulanan
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Demografi Jenis Surat (New) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <h3 class="font-bold text-slate-700 mb-2 flex items-center text-sm">
                <i class="fas fa-file-alt text-purple-500 mr-2"></i> Jenis Surat Terpopuler
            </h3>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="jenisSuratChart"></canvas>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 text-center">Top 5 jenis surat yang diajukan.</p>
        </div>
    </div>

    <!-- ROW 3: RW, RT, Bottleneck (3 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Demografi RW -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <h3 class="font-bold text-slate-700 mb-2 flex items-center text-sm">
                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i> Asal Permohonan (RW)
            </h3>
            <div class="relative h-48 w-full">
                <canvas id="rwChart"></canvas>
            </div>
        </div>

        <!-- Demografi RT (Top 5) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <h3 class="font-bold text-slate-700 mb-2 flex items-center text-sm">
                <i class="fas fa-home text-green-500 mr-2"></i> Top 5 RT Teraktif
            </h3>
            <div class="relative h-48 w-full">
                <canvas id="rtChart"></canvas>
            </div>
        </div>

        <!-- Bottleneck Analysis -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <h3 class="font-bold text-slate-700 mb-2 flex items-center text-sm">
                <i class="fas fa-hourglass-half text-yellow-500 mr-2"></i> Waktu Proses (Rata-rata)
            </h3>
            <div class="relative h-48 w-full">
                <canvas id="bottleneckChart"></canvas>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 text-center">Durasi jam kerja (Office Hours).</p>
        </div>
    </div>

    <!-- Recent Permohonan Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white rounded-lg border border-slate-200 flex items-center justify-center shadow-sm text-slate-500">
                    <i class="fas fa-history text-sm"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Menunggu Tanda Tangan</h3>
                    <p class="text-[10px] text-slate-500 font-medium">Permohonan terbaru yang perlu tindakan</p>
                </div>
            </div>
            <a href="{{ route('lurah.permohonan.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-[10px] shadow-sm transition-all">
                LIHAT SEMUA
            </a>
        </div>

        <div class="overflow-x-auto">
            @if($recentPermohonan->count() > 0)
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pemohon</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis Surat</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @foreach($recentPermohonan as $permohonan)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-md">
                                    {{ strtoupper(substr($permohonan->user->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-bold text-slate-800">{{ $permohonan->user->name }}</div>
                                    <div class="text-[10px] text-slate-500 font-medium">
                                        @if($permohonan->user->rt)
                                        RT {{ $permohonan->user->rt->nomor_rt }} / RW {{ $permohonan->user->rt->rw_id }}
                                        @else
                                        -
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700">{{ $permohonan->jenisSurat->name }}</span>
                                <span class="text-[10px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded w-fit mt-1 border border-slate-200">
                                    {{ $permohonan->nomor_tiket }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $statusContext = match($permohonan->status) {
                                    'menunggu_rt' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'label' => 'Menunggu RT', 'dot' => 'bg-yellow-500'],
                                    'disetujui_rt' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'Disetujui RT', 'dot' => 'bg-blue-500'],
                                    'menunggu_kasi' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'label' => 'Menunggu Verifikasi', 'dot' => 'bg-orange-500'],
                                    'disetujui_kasi' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'label' => 'Disetujui Kasi', 'dot' => 'bg-indigo-500'],
                                    'menunggu_lurah' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'label' => 'Menunggu TTE', 'dot' => 'bg-purple-500'],
                                    'selesai' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'label' => 'Selesai', 'dot' => 'bg-green-500'],
                                    'ditolak_rt', 'ditolak_kasi', 'ditolak_lurah' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'label' => 'Ditolak', 'dot' => 'bg-red-500'],
                                    default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'label' => 'Pending', 'dot' => 'bg-slate-500'],
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusContext['bg'] }} {{ $statusContext['text'] }} {{ $statusContext['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusContext['dot'] }} mr-1.5"></span>
                                {{ $statusContext['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-500 font-medium">
                            {{ $permohonan->created_at->format('d M Y') }}
                            <div class="text-[10px] text-slate-400">{{ $permohonan->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-xs font-medium">
                            <a href="{{ route('lurah.permohonan.detail', $permohonan->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg shadow-lg shadow-blue-500/30 transition-all">
                                <i class="fas fa-search mr-1.5"></i> Proses
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <i class="fas fa-check-circle text-slate-300 text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Semua Beres!</h3>
                <p class="text-slate-500 mt-1">Tidak ada permohonan yang menunggu tanda tangan saat ini.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    Chart.register(ChartDataLabels); // Register plugin globally or per chart

    // Common Datalabels config to avoid repetition
    const commonDataLabels = {
        color: '#fff',
        font: { weight: 'bold', size: 10 },
        formatter: Math.round
    };

    // 1. Trend Chart
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Jumlah Permohonan',
                data: {!! json_encode($trendData) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                datalabels: {
                    align: 'top',
                    anchor: 'end',
                    color: '#3b82f6', // Match line color
                    display: function(context) {
                        return context.dataset.data[context.dataIndex] > 0; // Only show if > 0
                    }
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } },
                x: { ticks: { font: { size: 10 } } }
            }
        }
    });

    // 2. RW Chart
    const ctxRW = document.getElementById('rwChart').getContext('2d');
    const rwData = {!! json_encode($demografiRW) !!};
    const rwLabels = Object.keys(rwData).map(rw => 'RW ' + rw);
    const rwValues = Object.values(rwData);

    new Chart(ctxRW, {
        type: 'doughnut',
        data: {
            labels: rwLabels,
            datasets: [{
                data: rwValues,
                backgroundColor: [
                    '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold' },
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => { sum += data; });
                        let percentage = (value*100 / sum).toFixed(0)+"%";
                        return percentage;
                    }
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 10, usePointStyle: true, font: { size: 10 } } }
            },
            cutout: '60%',
        }
    });

    // 2B. RT Chart (Top 5)
    // Note: Controller sends Top 10, we can slice it here or strictly strictly adhere to Top 5 label
    const ctxRT = document.getElementById('rtChart').getContext('2d');
    const rtData = {!! json_encode($demografiRT) !!};
    // Take only top 5 for compactness
    const rtLabels = Object.keys(rtData).slice(0, 5); 
    const rtValues = Object.values(rtData).slice(0, 5);

    new Chart(ctxRT, {
        type: 'bar',
        data: {
            labels: rtLabels,
            datasets: [{
                label: 'Jumlah Permohonan',
                data: rtValues,
                backgroundColor: '#10b981',
                borderRadius: 4,
                barThickness: 15,
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    color: '#059669',
                    font: { size: 10, weight: 'bold' },
                    clip: false
                }
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { right: 40 }
            },
            plugins: { legend: { display: false } }, 
            scales: { 
                x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } },
                y: { ticks: { font: { size: 10 } } }
            }
        }
    });

    // 3. Bottleneck Analysis Chart
    const ctxBottleneck = document.getElementById('bottleneckChart').getContext('2d');
    new Chart(ctxBottleneck, {
        type: 'bar',
        data: {
            labels: ['RT', 'Kasi', 'Lurah'], // Shorten labels
            datasets: [{
                label: 'Rata-rata Durasi (Jam)',
                data: [{!! $bottleneckData['rt'] !!}, {!! $bottleneckData['kasi'] !!}, {!! $bottleneckData['lurah'] !!}],
                backgroundColor: [
                    'rgba(245, 158, 11, 0.8)', // RT - Orange
                    'rgba(139, 92, 246, 0.8)', // Kasi - Violet
                    'rgba(16, 185, 129, 0.8)'  // Lurah - Green
                ],
                borderColor: [
                    'rgba(245, 158, 11, 1)',
                    'rgba(139, 92, 246, 1)',
                    'rgba(16, 185, 129, 1)'
                ],
                borderWidth: 1,
                borderRadius: 4,
                barThickness: 25,
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    color: '#475569',
                    font: { weight: 'bold', size: 10 },
                    formatter: function(value) { return value + ' Jam'; },
                    clip: false, // Prevent cutting off text
                    offset: 4
                }
            }]
        },
        options: {
            indexAxis: 'y', 
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { right: 40 } // Give space for labels
            },
            plugins: { 
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' Jam';
                        }
                    }
                }
            },
            scales: { 
                x: { 
                    beginAtZero: true, 
                    grid: { borderDash: [2, 4] },
                    ticks: { font: { size: 10 } },
                    suggestedMax: 24 // Give visual room, but it will auto-expand
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { size: 11, weight: 'bold' } }
                }
            }
        }
    });
    // 4. Jenis Surat Chart
    const ctxJenis = document.getElementById('jenisSuratChart').getContext('2d');
    const jenisData = {!! json_encode($jenisSuratChart) !!};
    const jenisLabels = Object.keys(jenisData);
    const jenisValues = Object.values(jenisData);

    new Chart(ctxJenis, {
        type: 'doughnut',
        data: {
            labels: jenisLabels,
            datasets: [{
                data: jenisValues,
                backgroundColor: [
                    '#8b5cf6', '#ec4899', '#3b82f6', '#10b981', '#f59e0b'
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold' },
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => { sum += data; });
                        let percentage = (value*100 / sum).toFixed(0)+"%";
                        return percentage;
                    }
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true, font: { size: 9 } } }
            },
            cutout: '60%',
        }
    });

</script>
@endpush
@endsection