@extends('components.layout')

@section('title', 'Arsip Permohonan - Kasi')
@section('page-title', 'Arsip Permohonan')
@section('page-description', 'Riwayat permohonan yang sudah diproses (Disetujui/Ditolak)')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800 flex items-center">
                <i class="fas fa-archive text-slate-500 mr-2.5"></i>
                Data Arsip Permohonan
            </h3>
            <a href="{{ route('kasi.permohonan.index') }}" class="text-xs bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 px-3 py-1.5 rounded-lg font-bold uppercase tracking-wide transition-colors shadow-sm">
                <i class="fas fa-arrow-left mr-1.5"></i> Kembali ke Aktif
            </a>
        </div>

        <div>
            @if($permohonan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Tiket</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pemohon</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis Surat</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Proses</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($permohonan as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded font-mono text-xs font-bold border border-slate-200">{{ $item->nomor_tiket }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 text-xs font-bold mr-3 border-2 border-white shadow-sm">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                    <div class="text-sm font-bold text-slate-800">{{ $item->user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-700">
                                {{ $item->jenisSurat->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                    @if($item->isDitolakRT() || $item->isDitolakKasi()) bg-red-50 text-red-700 border border-red-200
                                    @elseif($item->isMenungguLurah()) bg-purple-50 text-purple-700 border border-purple-200
                                    @elseif($item->isSelesai()) bg-green-50 text-green-700 border border-green-200
                                    @else bg-slate-50 text-slate-600 border border-slate-200 @endif">
                                    {{ $item->status_display }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $item->updated_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('kasi.permohonan.detail', $item->id) }}"
                                    class="text-slate-600 hover:text-slate-800 border border-slate-200 hover:bg-slate-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors mr-2">
                                    <i class="fas fa-eye mr-1.5"></i> Detail
                                </a>
                                @if($item->isMenungguLurah() || $item->isSelesai())
                                <a href="{{ route('kasi.permohonan.preview', $item->id) }}"
                                    class="text-purple-600 hover:text-purple-800 border border-purple-200 hover:bg-purple-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                    <i class="fas fa-file-alt mr-1.5"></i> Lihat Surat
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-archive text-slate-300 text-5xl mb-4"></i>
                <p class="text-slate-500 font-medium">Belum ada arsip permohonan</p>
                <p class="text-slate-400 text-sm mt-1">Permohonan yang sudah disetujui atau ditolak akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
