@extends('components.layout')

@section('title', 'Pilih Jenis Surat - Sistem Kelurahan')
@section('page-title', 'Pilih Jenis Surat')
@section('page-description', 'Pilih jenis surat yang ingin Anda ajukan')

@section('content')
<div class="space-y-8">
    <!-- Header Page -->
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 shadow-lg overflow-hidden text-white">
        <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>
        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-3">Ajukan Permohonan Surat 📝</h2>
            <p class="text-blue-100 max-w-2xl text-lg">Silakan pilih jenis surat yang sesuai dengan kebutuhan Anda. Pastikan Anda telah melengkapi data diri di halaman profil sebelum melanjutkan.</p>
        </div>
    </div>

    <!-- Grid Jenis Surat -->
    @if($jenis_surats->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($jenis_surats as $jenis)
            @php
                // Tentukan warna tema berdasarkan bidang
                $theme = match($jenis->bidang) {
                    'pemerintahan' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100', 'icon' => 'fa-landmark'],
                    'kesra' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-100', 'icon' => 'fa-hand-holding-heart'],
                    'trantib' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-100', 'icon' => 'fa-shield-alt'],
                    'ekobang' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'border' => 'border-yellow-100', 'icon' => 'fa-chart-line'],
                    default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-100', 'icon' => 'fa-file-alt'],
                };
            @endphp
        
        <a href="{{ route('masyarakat.permohonan.create.form', $jenis->id) }}" 
           class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-full">
            
            <div class="p-6 flex-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl {{ $theme['bg'] }} {{ $theme['text'] }} flex items-center justify-center text-xl shadow-inner">
                        <i class="fas {{ $theme['icon'] }}"></i>
                    </div>
                    <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full {{ $theme['bg'] }} {{ $theme['text'] }}">
                        {{ $jenis->bidang_display }}
                    </span>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors">
                    {{ $jenis->name }}
                </h3>
                
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    {{ Str::limit($jenis->persyaratan, 100) }}
                </p>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center text-sm text-gray-500 font-medium">
                    <i class="far fa-clock mr-2 text-gray-400"></i>
                    Estimasi {{ $jenis->estimasi_hari }} Hari
                </div>
                <span class="text-blue-600 font-semibold text-sm group-hover:underline flex items-center">
                    Ajukan Sekarang <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                </span>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-dashed border-gray-300">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Layanan Surat</h3>
        <p class="text-gray-500 max-w-md mx-auto">Saat ini belum ada jenis surat yang tersedia untuk diajukan. Silakan hubungi pihak kelurahan untuk informasi lebih lanjut.</p>
    </div>
    @endif

    <!-- Information Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-l-4 border-l-blue-500 p-6 flex items-start space-x-4">
        <div class="hidden sm:flex w-12 h-12 bg-blue-50 text-blue-600 rounded-full items-center justify-center flex-shrink-0">
            <i class="fas fa-info text-xl"></i>
        </div>
        <div>
            <h4 class="text-lg font-bold text-gray-800 mb-2">Panduan Pengajuan Surat</h4>
            <div class="text-gray-600 text-sm space-y-1">
                <p>1. Pilih jenis surat yang sesuai dengan kebutuhan Anda di atas.</p>
                <p>2. Lengkapi formulir isian dengan data yang valid dan benar.</p>
                <p>3. Unggah dokumen persyaratan yang diminta (Format: PDF/JPG, Max 2MB).</p>
                <p>4. Pantau status permohonan Anda melalui Dashboard atau menu Riwayat.</p>
            </div>
        </div>
    </div>
</div>
@endsection