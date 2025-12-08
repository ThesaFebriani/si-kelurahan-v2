@extends('components.layout')

@section('title', 'Arsip Permohonan - Kasi')
@section('page-title', 'Arsip Permohonan')
@section('page-description', 'Riwayat permohonan yang sudah diproses (Disetujui/Ditolak)')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-archive text-gray-600 mr-2"></i>
                Data Arsip Permohonan
            </h3>
            <a href="{{ route('kasi.permohonan.index') }}" class="text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Aktif
            </a>
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
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Proses</th>
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
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->jenisSurat->name }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($item->isDitolakRT() || $item->isDitolakKasi()) bg-red-100 text-red-800 
                                    @elseif($item->isMenungguLurah()) bg-purple-100 text-purple-800
                                    @elseif($item->isSelesai()) bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $item->status_display }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->updated_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('kasi.permohonan.detail', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                @if($item->isMenungguLurah() || $item->isSelesai())
                                <a href="{{ route('kasi.permohonan.preview', $item->id) }}"
                                    class="text-purple-600 hover:text-purple-800 border border-purple-600 px-2 py-1 rounded text-xs">
                                    <i class="fas fa-file-alt mr-1"></i> Lihat Surat
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
                <p class="text-gray-500">Belum ada arsip permohonan</p>
                <p class="text-gray-400 text-sm mt-1">Permohonan yang sudah disetujui atau ditolak akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
