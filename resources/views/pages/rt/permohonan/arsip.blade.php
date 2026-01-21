@extends('components.layout')

@section('title', 'Arsip Permohonan - RT')
@section('page-title', 'Arsip Permohonan Surat')
@section('page-description', 'Riwayat permohonan surat yang telah diproses')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-archive text-gray-600 mr-2"></i>
                Daftar Arsip Permohonan
            </h3>
        </div>

        <div class="p-6">
            @if($permohonan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Tiket</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surat Pengantar RT</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($permohonan as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <code class="bg-gray-100 px-2 py-1 rounded text-xs font-mono">{{ $item->nomor_tiket }}</code>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->jenisSurat->name }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                $statusColors = [
                                    'menunggu_kasi' => 'bg-orange-100 text-orange-800',
                                    'disetujui_kasi' => 'bg-green-100 text-green-800',
                                    'ditolak_kasi' => 'bg-red-100 text-red-800',
                                    'menunggu_lurah' => 'bg-purple-100 text-purple-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                    'ditolak_rt' => 'bg-red-100 text-red-800',
                                ];
                                $color = $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                    {{ $item->status_display }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                @if($item->file_surat_pengantar_rt)
                                    <a href="{{ route('documents.show', ['filename' => basename($item->file_surat_pengantar_rt)]) }}" target="_blank" class="text-red-600 hover:text-red-900 flex items-center">
                                        <i class="fas fa-file-pdf mr-1"></i> Unduh
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('rt.permohonan.detail', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-900 flex items-center">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-archive text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Belum ada arsip permohonan</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
