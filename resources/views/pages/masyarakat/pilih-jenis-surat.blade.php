@extends('components.layout')

@section('title', 'Pilih Jenis Surat - Sistem Kelurahan')
@section('page-title', 'Pilih Jenis Surat')
@section('page-description', 'Pilih jenis surat yang ingin diajukan')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                Pilih Jenis Surat
            </h3>
            <p class="text-gray-600 mt-1">Silakan pilih jenis surat yang ingin Anda ajukan</p>
        </div>

        <div class="p-6">
            @if($jenis_surats->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jenis_surats as $jenis)
                <a href="{{ route('masyarakat.permohonan.create.form', $jenis->id) }}"
                    class="block border border-gray-200 rounded-lg p-6 hover:shadow-md hover:border-blue-300 transition-all duration-200 group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-file-contract text-blue-600 text-xl"></i>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($jenis->bidang == 'kesra') bg-green-100 text-green-800
                            @elseif($jenis->bidang == 'pemerintahan') bg-blue-100 text-blue-800
                            @else bg-purple-100 text-purple-800 @endif">
                            {{ $jenis->bidang_display }}
                        </span>
                    </div>

                    <h4 class="font-semibold text-gray-800 text-lg mb-2 group-hover:text-blue-600 transition-colors">
                        {{ $jenis->name }}
                    </h4>

                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        {{ $jenis->persyaratan }}
                    </p>

                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>
                            <i class="fas fa-clock mr-1"></i>
                            {{ $jenis->estimasi_hari }} hari
                        </span>
                        <span class="flex items-center group-hover:text-blue-600 transition-colors">
                            Ajukan <i class="fas fa-arrow-right ml-1"></i>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500 text-lg">Belum ada jenis surat tersedia</p>
                <p class="text-gray-400 text-sm mt-1">Silakan hubungi admin untuk menambahkan jenis surat</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Informasi -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
            <i class="fas fa-info-circle mr-2"></i> Informasi Pengajuan
        </h4>
        <ul class="text-sm text-blue-700 space-y-1">
            <li>• Pastikan data yang diisi sesuai dengan dokumen asli</li>
            <li>• Siapkan dokumen pendukung yang diperlukan</li>
            <li>• Proses verifikasi membutuhkan waktu 1-3 hari kerja</li>
            <li>• Anda akan mendapat notifikasi via WhatsApp</li>
        </ul>
    </div>
</div>
@endsection