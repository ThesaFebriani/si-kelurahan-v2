@extends('components.layout')

@section('title', 'Laporan Permohonan')
@section('page-title', 'Laporan Permohonan')
@section('page-description', 'Rekapitulasi permohonan surat masuk dan statusnya.')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
    <!-- Toolbar -->
    <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
        <!-- Search/Filter -->
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-auto">
                <input type="text" placeholder="Cari permohonan..." class="pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400 text-sm"></i>
            </div>
            <select class="border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 py-2 pl-3 pr-8">
                <option value="">Semua Status</option>
                <option value="selesai">Selesai</option>
                <option value="proses">Dalam Proses</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-medium text-sm rounded-lg hover:bg-slate-50 transition-all flex items-center gap-2">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            <button class="px-4 py-2 bg-emerald-600 text-white font-medium text-sm rounded-lg hover:bg-emerald-700 transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                <i class="fas fa-file-excel"></i>
                <span>Export Excel</span>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-6 py-4">Nomor Tiket</th>
                    <th class="px-6 py-4">Pemohon</th>
                    <th class="px-6 py-4">Jenis Surat</th>
                    <th class="px-6 py-4 text-center">Tanggal</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($permohonan as $item)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs font-semibold bg-slate-100 px-2 py-1 rounded text-slate-600">
                            {{ $item->nomor_tiket }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                {{ substr($item->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-slate-900">{{ $item->user->name }}</div>
                                <div class="text-xs text-slate-500">NIK: {{ $item->user->nik }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-700">{{ $item->jenisSurat->name }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm text-slate-600">{{ $item->created_at->format('d/m/Y') }}</span>
                        <div class="text-xs text-slate-400">{{ $item->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $statusClass = match($item->status) {
                                'selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'ditolak_rt', 'ditolak_kasi', 'ditolak_lurah' => 'bg-red-50 text-red-600 border-red-100',
                                default => 'bg-amber-50 text-amber-600 border-amber-100'
                            };
                            $statusLabel = $item->status_display;
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-slate-400 hover:text-blue-600 transition-colors" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        Belum ada data permohonan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
