@extends('components.layout')

@section('title', 'Riwayat Permohonan - Sistem Kelurahan')
@section('page-title', 'Riwayat Permohonan')
@section('page-description', 'Daftar semua permohonan surat yang diajukan')

@section('content')
<div class="space-y-6">
    <!-- Header dengan Tombol Ajukan Baru -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Riwayat Permohonan</h2>
            <p class="text-gray-600">Lihat status dan riwayat semua permohonan surat Anda</p>
        </div>
        <a href="{{ route('masyarakat.permohonan.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
            <i class="fas fa-plus-circle mr-2"></i>
            Ajukan Permohonan Baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Permohonan</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $permohonan->count() }}</h3>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Dalam Proses</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">
                        {{ $permohonan->whereIn('status', ['menunggu_rt', 'menunggu_kasi', 'menunggu_lurah'])->count() }}
                    </h3>
                </div>
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Disetujui</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">
                        {{ $permohonan->whereIn('status', ['disetujui_rt', 'disetujui_kasi', 'selesai'])->count() }}
                    </h3>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Ditolak</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">
                        {{ $permohonan->whereIn('status', ['ditolak_rt', 'ditolak_kasi'])->count() }}
                    </h3>
                </div>
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Permohonan -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-list-check text-blue-600 mr-2"></i>
                Daftar Permohonan
            </h3>
        </div>

        <div class="p-6">
            @if($permohonan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Tiket</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($permohonan as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <code class="bg-gray-100 px-2 py-1 rounded text-xs font-mono">{{ $item->nomor_tiket }}</code>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->jenisSurat->name }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
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
                                $color = $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                    {{ $item->status_display }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('masyarakat.permohonan.detail', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                @if($item->isMenungguRT())
                                <span class="text-gray-400">
                                    <i class="fas fa-clock mr-1"></i> Menunggu RT
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500 text-lg">Belum ada permohonan surat</p>
                <p class="text-gray-400 text-sm mt-1">Ajukan permohonan pertama Anda untuk melihat riwayat di sini</p>
                <a href="{{ route('masyarakat.permohonan.create') }}"
                    class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus-circle mr-2"></i>Ajukan Permohonan Pertama
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Info Status -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
            <i class="fas fa-info-circle mr-2"></i> Informasi Status Permohonan
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700">
            <div class="flex items-center">
                <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                <span>Menunggu RT - Sedang diproses oleh Ketua RT</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                <span>Disetujui RT - Diteruskan ke Kasi</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span>
                <span>Menunggu Kasi - Sedang diverifikasi Kasi</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                <span>Selesai - Surat sudah bisa diambil</span>
            </div>
        </div>
    </div>
</div>
@endsection