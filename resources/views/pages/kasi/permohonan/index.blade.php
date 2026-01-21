@extends('components.layout')

@section('title', 'Verifikasi Permohonan - Kasi')
@section('page-title', 'Verifikasi Permohonan Surat')
@section('page-description', 'Verifikasi permohonan surat yang sudah disetujui RT')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Menunggu</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['pending'] ?? 0 }}</h3>
                </div>
                <div class="p-2.5 bg-yellow-50 rounded-lg border border-yellow-100">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Disetujui</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['approved'] ?? 0 }}</h3>
                </div>
                <div class="p-2.5 bg-green-50 rounded-lg border border-green-100">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Ditolak</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['rejected'] ?? 0 }}</h3>
                </div>
                <div class="p-2.5 bg-red-50 rounded-lg border border-red-100">
                    <i class="fas fa-times-circle text-red-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total'] ?? 0 }}</h3>
                </div>
                <div class="p-2.5 bg-blue-50 rounded-lg border border-blue-100">
                    <i class="fas fa-file-alt text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Permohonan -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800 flex items-center">
                <i class="fas fa-list-check text-blue-600 mr-2.5"></i>
                Daftar Permohonan Surat
            </h3>
            <a href="{{ route('kasi.permohonan.arsip') }}" class="text-xs bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 px-3 py-1.5 rounded-lg font-bold uppercase tracking-wide transition-colors shadow-sm">
                <i class="fas fa-archive mr-1.5"></i> Lihat Arsip
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">RT</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
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
                                    <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold mr-3 border-2 border-white shadow-sm">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">{{ $item->user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $item->user->alamat_lengkap ?? 'Tidak ada alamat' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-700">
                                {{ $item->jenisSurat->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                @if($item->user->rt)
                                <span class="font-bold">RT {{ $item->user->rt->nomor_rt }}</span>
                                @else
                                -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    {{ $item->status_display }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($item->isMenungguKasi())
                                <a href="{{ route('kasi.permohonan.verify', $item->id) }}"
                                    class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm shadow-blue-200">
                                    <i class="fas fa-search mr-1.5"></i> Periksa Detail
                                </a>
                                @elseif($item->isMenungguLurah() || $item->isSelesai())
                                <a href="{{ route('kasi.permohonan.preview', $item->id) }}"
                                    class="text-purple-600 hover:text-purple-800 border border-purple-200 hover:bg-purple-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fas fa-file-alt mr-1.5"></i> Lihat Surat
                                </a>
                                @else
                                <a href="{{ route('kasi.permohonan.detail', $item->id) }}"
                                    class="text-slate-600 hover:text-slate-800 border border-slate-200 hover:bg-slate-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                    <i class="fas fa-eye mr-1.5"></i> Detail
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
                <i class="fas fa-inbox text-slate-300 text-5xl mb-4"></i>
                <p class="text-slate-500 font-medium">Tidak ada permohonan menunggu verifikasi</p>
                <p class="text-slate-400 text-sm mt-1">Permohonan yang sudah disetujui RT akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection