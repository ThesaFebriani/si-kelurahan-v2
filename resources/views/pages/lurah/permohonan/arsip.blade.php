@extends('components.layout')

@section('title', 'Arsip Surat Selesai - Lurah')
@section('page-title', 'Arsip Surat Selesai')
@section('page-description', 'Daftar permohonan surat yang telah selesai diproses dan ditandatangani')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Menunggu TTE</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['pending'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-signature text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Selesai (Arsip)</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['completed'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Permohonan</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Permohonan -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-archive text-green-600 mr-2"></i>
                Arsip Surat Selesai
            </h3>
             <a href="{{ route('lurah.permohonan.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Menu Utama
            </a>
        </div>

        <div class="p-6">
            @if($permohonan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Surat Final</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Selesai</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($permohonan as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <code class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-mono font-bold">{{ $item->nomor_surat_final ?? '-' }}</code>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-semibold mr-3">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->user->rt->nomor_rt ?? '-' }} / {{ $item->user->rt->rw->nomor_rw ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->jenisSurat->name }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('lurah.permohonan.detail', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3 border border-blue-600 px-2 py-1 rounded text-xs">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                @if($item->surat && $item->surat->file_path)
                                    <a href="{{ route('documents.show', ['filename' => basename($item->surat->file_path)]) }}" target="_blank"
                                        class="text-green-600 hover:text-green-900 border border-green-600 px-2 py-1 rounded text-xs">
                                        <i class="fas fa-file-pdf mr-1"></i> Unduh PDF
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-archive text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Belum ada arsip surat selesai</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
