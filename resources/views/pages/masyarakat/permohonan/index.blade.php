@extends('components.layout')

@section('title', 'Riwayat Permohonan - Sistem Kelurahan')
@section('page-title', 'Riwayat Permohonan')
@section('page-description', 'Pantau status dan riwayat pengajuan surat Anda')

@section('content')
<div class="space-y-8">
    
    <!-- Hero Section / Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- New Application CTA -->
        <div class="md:col-span-4 lg:col-span-1 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group">
            <div class="absolute top-0 right-0 opacity-10 transform translate-x-10 -translate-y-10">
                <i class="fas fa-file-signature text-9xl"></i>
            </div>
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div>
                    <h3 class="font-bold text-xl mb-2">Butuh Surat Baru?</h3>
                    <p class="text-blue-100 text-sm mb-6">Ajukan surat keterangan dengan mudah dan cepat melalui sistem online.</p>
                </div>
                <a href="{{ route('masyarakat.permohonan.create') }}" 
                   class="inline-flex justify-center items-center w-full py-3 bg-white text-blue-600 rounded-xl font-bold shadow-md hover:bg-blue-50 transition-all transform hover:-translate-y-0.5">
                    <i class="fas fa-plus-circle mr-2"></i> Ajukan Sekarang
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:border-blue-200 transition-colors">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Pengajuan</p>
                    <h4 class="text-2xl font-bold text-gray-800">{{ $permohonan->count() }}</h4>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between text-xs text-gray-400">
                <span>Total riwayat</span>
                <span><i class="fas fa-chart-line"></i> All time</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:border-yellow-200 transition-colors">
             <div class="flex items-center space-x-4">
                <div class="p-3 bg-yellow-50 text-yellow-600 rounded-xl">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Dalam Proses</p>
                    <h4 class="text-2xl font-bold text-gray-800">
                        {{ $permohonan->whereIn('status', ['menunggu_rt', 'menunggu_kasi', 'menunggu_lurah', 'disetujui_rt', 'disetujui_kasi'])->count() }}
                    </h4>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between text-xs text-gray-400">
                <span>Menunggu verifikasi</span>
                <span class="text-yellow-500 font-medium"><i class="fas fa-hourglass-start"></i> Active</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between hover:border-green-200 transition-colors">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Selesai</p>
                    <h4 class="text-2xl font-bold text-gray-800">
                         {{ $permohonan->where('status', 'selesai')->count() }}
                    </h4>
                </div>
            </div>
             <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between text-xs text-gray-400">
                <span>Siap diunduh</span>
                <span class="text-green-500 font-medium"><i class="fas fa-file-download"></i> Completed</span>
            </div>
        </div>
    </div>

    <!-- Main Content List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-history text-blue-500 mr-2"></i> Riwayat Pengajuan
            </h3>
            
            <!-- Simple Filter (Visual Only for now, or use JS) -->
             <div class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" placeholder="Cari nomor tiket..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-400 w-full md:w-64 transition-all">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                </div>
             </div>
        </div>

        <div class="overflow-x-auto">
            @if($permohonan->count() > 0)
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4">Nomor Tiket & Info</th>
                        <th class="px-6 py-4">Status Terkini</th>
                        <th class="px-6 py-4">Tanggal Pengajuan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($permohonan as $item)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg mr-4 border border-blue-100 group-hover:scale-110 transition-transform">
                                    {{ substr($item->jenisSurat->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-bold text-gray-800">{{ $item->jenisSurat->name }}</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-gray-100 text-gray-500 border border-gray-200">
                                            {{ $item->nomor_tiket }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $item->jenisSurat->bidang_display }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusStyle = match($item->status) {
                                    'menunggu_rt' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                    'disetujui_rt' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'menunggu_kasi' => 'bg-orange-50 text-orange-700 border-orange-100',
                                    'disetujui_kasi' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'menunggu_lurah' => 'bg-purple-50 text-purple-700 border-purple-100',
                                    'selesai' => 'bg-green-50 text-green-700 border-green-100',
                                    'ditolak_rt', 'ditolak_kasi' => 'bg-red-50 text-red-700 border-red-100',
                                    default => 'bg-gray-50 text-gray-700 border-gray-100',
                                };
                            @endphp
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold border {{ $statusStyle }}">
                                {{ $item->status_display }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-700">{{ $item->created_at->format('d M Y') }}</span>
                                <span class="text-xs text-gray-400">{{ $item->created_at->format('H:i') }} WIB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('masyarakat.permohonan.detail', $item->id) }}" 
                                   class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($item->status === 'selesai' && $item->surat)
                                <a href="{{ Storage::url($item->surat->file_path) }}" target="_blank"
                                   class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all" title="Unduh Surat">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination Placeholder if needed in future -->
            @if($permohonan->hasPages() ?? false)
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $permohonan->links() }}
            </div>
            @endif

            @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-inbox text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Pengajuan</h3>
                <p class="text-gray-500 max-w-sm mb-8">Anda belum pernah mengajukan surat keterangan apapun. Mulai ajukan sekarang untuk melihat riwayat prosesnya di sini.</p>
                <a href="{{ route('masyarakat.permohonan.create') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-1">
                    <i class="fas fa-plus mr-2"></i> Buat Pengajuan Baru
                </a>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Legend Status -->
     <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-5">
        <h4 class="text-sm font-bold text-blue-800 mb-4 flex items-center">
            <i class="fas fa-info-circle mr-2"></i> Panduan Status Permohonan
        </h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
             @php
                $legends = [
                    ['color' => 'bg-yellow-100 text-yellow-700', 'label' => 'Menunggu RT', 'desc' => 'Diverifikasi Ketua RT'],
                    ['color' => 'bg-blue-100 text-blue-700', 'label' => 'Disetujui RT', 'desc' => 'Lanjut ke Kelurahan'],
                    ['color' => 'bg-purple-100 text-purple-700', 'label' => 'Proses Kelurahan', 'desc' => 'Verifikasi & TTE Lurah'],
                    ['color' => 'bg-green-100 text-green-700', 'label' => 'Selesai', 'desc' => 'Surat Terbit'],
                ];
            @endphp
            @foreach($legends as $legend)
            <div class="flex items-start space-x-3">
                <span class="w-3 h-3 rounded-full {{ $legend['color'] }} mt-1 flex-shrink-0"></span>
                <div>
                    <p class="text-xs font-bold text-gray-700">{{ $legend['label'] }}</p>
                    <p class="text-[10px] text-gray-500">{{ $legend['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection