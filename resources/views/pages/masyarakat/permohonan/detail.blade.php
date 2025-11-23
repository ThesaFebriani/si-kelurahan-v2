@extends('components.layout')

@section('title', 'Detail Permohonan - Sistem Kelurahan')
@section('page-title', 'Detail Permohonan')
@section('page-description', 'Detail lengkap permohonan surat')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header dengan Tombol Kembali -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Permohonan</h2>
            <p class="text-gray-600">Nomor Tiket: <code class="bg-gray-100 px-2 py-1 rounded">{{ $permohonan->nomor_tiket }}</code></p>
        </div>
        <a href="{{ route('masyarakat.permohonan.index') }}"
            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Status Permohonan</h3>
                <p class="text-gray-600">Track progress permohonan Anda</p>
            </div>
            <div>
                @php
                $statusColors = [
                'menunggu_rt' => 'bg-yellow-100 text-yellow-800',
                'disetujui_rt' => 'bg-blue-100 text-blue-800',
                'ditolak_rt' => 'bg-red-100 text-red-800',
                'menunggu_kasi' => 'bg-orange-100 text-orange-800',
                'disetujui_kasi' => 'bg-green-100 text-green-800',
                'ditolak_kasi' => 'bg-red-100 text-red-800',
                'menunggu_lurah' => 'bg-purple-100 text-purple-800',
                'selesai' => 'bg-green-100 text-green-800'
                ];
                $color = $statusColors[$permohonan->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $color }}">
                    {{ $permohonan->status_display }}
                </span>
            </div>
        </div>
    </div>

    <!-- Informasi Permohonan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Data Pemohon -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Data Pemohon
                </h3>
            </div>
            <div class="p-4 space-y-3">
                @php $dataPemohon = $permohonan->data_pemohon; @endphp
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama Lengkap:</span>
                    <span class="font-medium">{{ $dataPemohon['nama_lengkap'] ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">NIK:</span>
                    <span class="font-mono">{{ $dataPemohon['nik'] ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tempat/Tgl Lahir:</span>
                    <span class="font-medium">{{ $dataPemohon['tempat_lahir'] ?? '-' }}, {{ isset($dataPemohon['tanggal_lahir']) ? \Carbon\Carbon::parse($dataPemohon['tanggal_lahir'])->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Jenis Kelamin:</span>
                    <span class="font-medium">{{ $dataPemohon['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Agama:</span>
                    <span class="font-medium">{{ $dataPemohon['agama'] ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status Perkawinan:</span>
                    <span class="font-medium">{{ $dataPemohon['status_perkawinan'] ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Pekerjaan:</span>
                    <span class="font-medium">{{ $dataPemohon['pekerjaan'] ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Informasi Surat -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-file-alt text-green-600 mr-2"></i>
                    Informasi Surat
                </h3>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Jenis Surat:</span>
                    <span class="font-medium">{{ $permohonan->jenisSurat->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Bidang:</span>
                    <span class="font-medium">{{ $permohonan->jenisSurat->bidang_display }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Pengajuan:</span>
                    <span class="font-medium">{{ $permohonan->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Estimasi Selesai:</span>
                    <span class="font-medium">{{ $permohonan->created_at->addDays($permohonan->jenisSurat->estimasi_hari)->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tujuan Permohonan -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-bullseye text-purple-600 mr-2"></i>
                Tujuan Permohonan
            </h3>
        </div>
        <div class="p-4">
            <p class="text-gray-700">{{ $permohonan->data_pemohon['tujuan'] ?? 'Tidak ada keterangan tujuan' }}</p>
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-history text-orange-600 mr-2"></i>
                Timeline Permohonan
            </h3>
        </div>
        <div class="p-4">
            @if($permohonan->timeline->count() > 0)
            <div class="space-y-4">
                @foreach($permohonan->timeline->sortBy('created_at') as $timeline)
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <p class="font-medium text-gray-800">{{ $timeline->status_display }}</p>
                            <span class="text-sm text-gray-500">{{ $timeline->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="text-gray-600 text-sm mt-1">{{ $timeline->keterangan }}</p>
                        <p class="text-gray-400 text-xs mt-1">Oleh: {{ $timeline->updatedBy->name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-4">Belum ada aktivitas timeline</p>
            @endif
        </div>
    </div>
</div>
@endsection