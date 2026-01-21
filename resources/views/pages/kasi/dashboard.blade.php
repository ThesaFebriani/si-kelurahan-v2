@extends('components.layout')

@section('title', 'Dashboard Kasi')
@section('page-title', 'Dashboard Kasi')
@section('page-description', 'Selamat datang di dashboard Kasi')

@section('content')
<div class="space-y-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Permohonan Menunggu -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Menunggu Verifikasi</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['pending_permohonan'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
            <a href="{{ route('kasi.permohonan.index') }}"
                class="mt-3 inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                Lihat permohonan
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Permohonan Disetujui -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Disetujui</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['approved_permohonan'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Permohonan Ditolak -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Ditolak</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['rejected_permohonan'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Permohonan -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Permohonan</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total_permohonan'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600"></i>
                </div>
            </div>
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
                    <h3 class="text-base font-bold text-slate-800">Menunggu Verifikasi</h3>
                    <p class="text-[10px] text-slate-500 font-medium">Permohonan terbaru yang perlu tindakan</p>
                </div>
            </div>
            <a href="{{ route('kasi.permohonan.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-[10px] shadow-sm transition-all">
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
                                $statusContext = match(true) {
                                    $permohonan->status == 'menunggu_kasi' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'label' => 'Menunggu Verifikasi', 'dot' => 'bg-yellow-500'],
                                    $permohonan->status == 'disetujui_kasi' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'label' => 'Disetujui', 'dot' => 'bg-green-500'],
                                    default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'label' => 'Status Lain', 'dot' => 'bg-slate-500'],
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
                            <a href="{{ route('kasi.permohonan.detail', $permohonan->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg shadow-lg shadow-blue-500/30 transition-all">
                                <i class="fas fa-search mr-1.5"></i> Verifikasi
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
                <p class="text-slate-500 mt-1">Tidak ada permohonan yang menunggu verifikasi saat ini.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection