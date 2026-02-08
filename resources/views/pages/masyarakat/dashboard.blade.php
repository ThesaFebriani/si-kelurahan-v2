@extends('components.layout')

@section('title', 'Dashboard - Sistem Kelurahan')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview pelayanan surat Anda')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Card Total -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Pengajuan</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total_permohonan'] }}</h3>
                    <p class="text-blue-600 text-xs mt-1">
                        <i class="fas fa-file-alt"></i> Semua surat
                    </p>
                </div>
                <div class="p-2.5 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Card Pending -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Dalam Proses</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['permohonan_pending'] }}</h3>
                    <p class="text-yellow-600 text-xs mt-1">
                        <i class="fas fa-clock"></i> Belum selesai
                    </p>
                </div>
                <div class="p-2.5 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Card Selesai -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Selesai</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['permohonan_selesai'] }}</h3>
                    <p class="text-green-600 text-xs mt-1">
                        <i class="fas fa-check-circle"></i> Sudah diterbitkan
                    </p>
                </div>
                <div class="p-2.5 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
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
                    <h3 class="text-base font-bold text-slate-800">Permohonan Terbaru</h3>
                    <p class="text-[10px] text-slate-500 font-medium">Pantau status pengajuan Anda</p>
                </div>
            </div>
            <a href="{{ route('masyarakat.permohonan.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-[10px] shadow-sm transition-all">
                LIHAT SEMUA
            </a>
        </div>
        <div class="overflow-x-auto">
            @if($recent_permohonan->count() > 0)
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Tiket</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis Surat</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @foreach($recent_permohonan as $permohonan)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                {{ $permohonan->nomor_tiket }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm font-bold text-slate-700">{{ $permohonan->jenisSurat->name }}</span>
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
                            {{ $permohonan->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-8">
                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-100">
                    <i class="fas fa-inbox text-slate-300 text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800">Belum ada riwayat permohonan</h3>
                <a href="{{ route('masyarakat.permohonan.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-xs shadow-lg shadow-blue-500/30 transition-all">
                    Ajukan Sekarang
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection