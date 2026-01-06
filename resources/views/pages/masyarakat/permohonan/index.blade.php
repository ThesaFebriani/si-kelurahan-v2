@extends('components.layout')

@section('title', 'Riwayat Permohonan - Sistem Kelurahan')
@section('page-title', 'Riwayat Permohonan')
@section('page-description', 'Pantau status dan riwayat pengajuan surat Anda')

@section('content')
<div class="space-y-6">
    
    <!-- Hero Section / Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- New Application CTA -->
        <div class="md:col-span-4 lg:col-span-1 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden flex flex-col justify-between">
            <div class="relative z-10">
                <h3 class="font-bold text-lg mb-1">Butuh Surat Baru?</h3>
                <p class="text-blue-100 text-xs mb-4">Ajukan surat keterangan dengan mudah secara online.</p>
                <a href="{{ route('masyarakat.permohonan.create') }}" 
                   class="inline-flex justify-center items-center w-full py-2 bg-white text-blue-600 rounded-lg text-sm font-bold shadow-sm hover:bg-blue-50 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Ajukan Sekarang
                </a>
            </div>
            <div class="absolute bottom-0 right-0 opacity-10">
                <i class="fas fa-file-signature text-8xl -mb-4 -mr-4"></i>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total</p>
                    <h4 class="text-2xl font-bold text-gray-800">{{ $permohonan->total() }}</h4>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
             <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Proses</p>
                    <h4 class="text-2xl font-bold text-gray-800">
                        {{ $permohonan->whereIn('status', ['menunggu_rt', 'menunggu_kasi', 'menunggu_lurah', 'disetujui_rt', 'disetujui_kasi'])->count() }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Selesai</p>
                    <h4 class="text-2xl font-bold text-gray-800">
                         {{ $permohonan->where('status', 'selesai')->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Tabs & Header -->
        <div class="border-b border-gray-100">
            <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-lg font-bold text-gray-800">Daftar Riwayat</h3>
                
                <!-- Active Search Filter -->
                 <form action="{{ route('masyarakat.permohonan.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                    <!-- Preserve Tab -->
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                    
                    <div class="relative w-full md:w-72">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor tiket..." 
                               class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-400 w-full transition-all">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                    </div>
                    @if(request('search'))
                    <a href="{{ route('masyarakat.permohonan.index', ['tab' => request('tab')]) }}" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Hapus Filter">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                 </form>
            </div>

            <!-- Tab Navigation -->
            <div class="flex px-5 space-x-6 overflow-x-auto">
                <a href="{{ route('masyarakat.permohonan.index', ['tab' => 'semua']) }}" 
                   class="pb-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('tab', 'semua') == 'semua' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Semua Pengajuan
                </a>
                <a href="{{ route('masyarakat.permohonan.index', ['tab' => 'proses']) }}" 
                   class="pb-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('tab') == 'proses' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Dalam Proses
                    @if($countProses = $permohonan->whereIn('status', ['menunggu_rt', 'menunggu_kasi', 'menunggu_lurah', 'disetujui_rt', 'disetujui_kasi'])->count() > 0 && request('tab') != 'proses')
                       <!-- Badge count removed to avoid confusion with paginated results, unless passed from controller separately. 
                            Keeping it clean for now. -->
                    @endif
                </a>
                <a href="{{ route('masyarakat.permohonan.index', ['tab' => 'selesai']) }}" 
                   class="pb-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ request('tab') == 'selesai' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Selesai / Arsip
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            @if($permohonan->count() > 0)
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 w-1/3">Detail Surat</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($permohonan as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 text-sm mb-1">{{ $item->jenisSurat->name }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded border border-gray-200">
                                        {{ $item->nomor_tiket }}
                                    </span>
                                    <span class="text-xs text-gray-400">• {{ $item->jenisSurat->bidang_display }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusContext = match($item->status) {
                                    'menunggu_rt' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Menunggu RT'],
                                    'disetujui_rt' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Disetujui RT'],
                                    'menunggu_kasi', 'disetujui_kasi', 'menunggu_lurah' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Proses Kelurahan'],
                                    'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Selesai'],
                                    'ditolak_rt', 'ditolak_kasi' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                    default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Pending'],
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusContext['bg'] }} {{ $statusContext['text'] }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5 opacity-60"></span>
                                {{ $statusContext['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col text-xs">
                                <span class="font-medium text-gray-700">{{ $item->created_at->format('d M Y') }}</span>
                                <span class="text-gray-400">{{ $item->created_at->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end items-center gap-3">
                                <a href="{{ route('masyarakat.permohonan.detail', $item->id) }}" 
                                   class="text-xs font-medium text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1" title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                @if($item->status === 'selesai' && $item->surat)
                                <a href="{{ Storage::url($item->surat->file_path) }}" target="_blank"
                                   class="px-3 py-1.5 bg-green-50 text-green-600 rounded-lg text-xs font-bold hover:bg-green-100 transition-all flex items-center gap-2 border border-green-200 shadow-sm" title="Unduh Surat Resmi">
                                    <i class="fas fa-file-download text-green-500"></i> Unduh
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $permohonan->withQueryString()->links() }}
            </div>

            @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-search text-gray-300 text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-gray-800 mb-1">Tidak ditemukan</h3>
                <p class="text-gray-500 text-sm mb-6">Tidak ada riwayat pengajuan yang cocok dengan pencarian Anda.</p>
                <a href="{{ route('masyarakat.permohonan.index') }}" class="text-blue-600 text-sm font-medium hover:underline">
                    Reset Pencarian
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection