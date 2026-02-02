@extends('components.layout')

@section('title', 'Dashboard RT')
@section('page-title', 'Dashboard RT')
@section('page-description', 'Selamat datang di dashboard RT')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Permohonan Menunggu -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Menunggu Persetujuan</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['pending_permohonan'] }}</h3>
                </div>
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
            <a href="{{ route('rt.permohonan.index') }}"
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
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['approved_permohonan'] }}</h3>
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

        <!-- Total Keluarga -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Keluarga</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total_keluarga'] }}</h3>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
            <!-- HAPUS LINK YANG ERROR -->
            <div class="mt-3 text-sm text-gray-500">
                Data kependudukan
            </div>
        </div>

        <!-- Total Warga -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Warga</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total_warga'] }}</h3>
                </div>
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-user-friends text-purple-600"></i>
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
                    <p class="text-[10px] text-slate-500 font-medium">Daftar permohonan masuk terbaru</p>
                </div>
            </div>
            <a href="{{ route('rt.permohonan.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-[10px] shadow-sm transition-all">
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
                                        NIK: {{ $permohonan->user->nik }}
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
                            <a href="{{ route('rt.permohonan.detail', $permohonan->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors text-[10px] font-bold shadow-sm">
                                <i class="fas fa-eye mr-1.5"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <i class="fas fa-inbox text-slate-300 text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum ada permohonan</h3>
                <p class="text-slate-500 mt-1">Permohonan dari warga akan muncul di sini.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection