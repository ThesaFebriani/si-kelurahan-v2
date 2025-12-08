@extends('components.layout')

@section('title', 'Data Wilayah RW')
@section('page-title', 'Data Wilayah RW')
@section('page-description', 'Kelola data Rukun Warga (RW) dan RT terkait.')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
    <!-- Toolbar -->
    <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
        <!-- Search/Filter -->
        <div class="relative w-full sm:w-auto">
            <input type="text" placeholder="Cari RW..." class="pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 w-full sm:w-64">
            <i class="fas fa-search absolute left-3 top-3 text-slate-400 text-sm"></i>
        </div>

        <button disabled class="px-5 py-2.5 bg-blue-400 text-white font-medium text-sm rounded-lg cursor-not-allowed flex items-center gap-2 opacity-70">
            <i class="fas fa-plus"></i>
            <span>Tambah RW (Coming Soon)</span>
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-6 py-4">Nomor RW</th>
                    <th class="px-6 py-4">Jumlah RT</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rw as $item)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                {{ $item->nomor_rw }}
                            </div>
                            <span class="font-semibold text-slate-700">RW {{ $item->nomor_rw }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-medium">
                            {{ $item->rt_count }} RT
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                            @if($item->is_active)
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Aktif
                            @else
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                Non-Aktif
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                            <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                        Belum ada data RW.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
