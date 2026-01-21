@extends('components.layout')

@section('title', 'Pilih Jenis Surat - Sistem Kelurahan')
@section('page-title', 'Pilih Jenis Surat')
@section('page-description', 'Silakan pilih jenis surat yang ingin Anda ajukan')

@section('content')
<div class="space-y-6">
    
    <!-- Info/Guide Card (Simple Admin Style) -->
    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-blue-800">Panduan Pengajuan Surat</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Pilih jenis surat yang sesuai dengan kebutuhan Anda.</li>
                        <li>Isi formulir dengan data yang valid dan lengkap.</li>
                        <li>Unggah dokumen persyaratan (jika ada) dalam format PDF atau JPG.</li>
                        <li>Pantau status pengajuan Anda melalui menu <strong>Riwayat Permohonan</strong>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Jenis Surat -->
    @if($jenis_surats->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($jenis_surats as $jenis)
            @php
                // Tentukan warna tema sderhana (hanya garis border atas)
                $borderClass = match($jenis->bidang) {
                    'pemerintahan' => 'border-blue-500',
                    'kesra' => 'border-green-500',
                    'trantib' => 'border-red-500',
                    'ekobang' => 'border-yellow-500',
                    default => 'border-gray-500',
                };
                
                 $iconClass = match($jenis->bidang) {
                    'pemerintahan' => 'text-blue-500',
                    'kesra' => 'text-green-500',
                    'trantib' => 'text-red-500',
                    'ekobang' => 'text-yellow-500',
                    default => 'text-gray-500',
                };
                
                 $icon = match($jenis->bidang) {
                    'pemerintahan' => 'fa-landmark',
                    'kesra' => 'fa-hand-holding-heart',
                    'trantib' => 'fa-shield-alt',
                    'ekobang' => 'fa-chart-line',
                    default => 'fa-file-alt',
                };
            @endphp
        
        <a href="{{ route('masyarakat.permohonan.create.form', $jenis->id) }}" 
           class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col hover:shadow-md transition-shadow relative overflow-hidden group">
            
            <!-- Bidang Badge (Simple) -->
            <div class="absolute top-0 right-0 mt-4 mr-4">
                 <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                    {{ $jenis->bidang_display }}
                </span>
            </div>

            <div class="w-12 h-12 rounded-lg bg-gray-50 flex items-center justify-center mb-4 group-hover:bg-gray-100 transition-colors">
                <i class="fas {{ $icon }} {{ $iconClass }} text-xl"></i>
            </div>

            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                {{ $jenis->name }}
            </h3>
            
            <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-1">
                {{ Str::limit($jenis->persyaratan, 100) }}
            </p>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-auto">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="far fa-clock mr-1.5"></i>
                    {{ $jenis->estimasi_hari }} Hari
                </div>
                <span class="text-blue-600 font-semibold text-sm flex items-center group-hover:underline">
                    Ajukan <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </span>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-lg shadow border border-gray-200 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-search text-gray-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">Layanan Belum Tersedia</h3>
        <p class="text-gray-500 text-sm">Mohon maaf, saat ini belum ada layanan surat yang dapat diajukan secara online.</p>
    </div>
    @endif
</div>
@endsection