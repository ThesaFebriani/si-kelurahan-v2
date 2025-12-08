@extends('components.layout')

@section('title', 'Jenis Surat')
@section('page-title', 'Daftar Jenis Surat')
@section('page-description', 'Kelola template dan jenis surat yang tersedia')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
    <!-- Toolbar -->
    <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
        <!-- Search/Filter -->
        <div class="relative w-full sm:w-auto">
            <input type="text" placeholder="Cari jenis surat..." class="pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 w-full sm:w-64">
            <i class="fas fa-search absolute left-3 top-3 text-slate-400 text-sm"></i>
        </div>

        <a href="{{ route('admin.jenis-surat.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 transition-all shadow-sm hover:shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Jenis Surat</span>
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-6 py-4">Kode & Nama</th>
                    <th class="px-6 py-4">Deskripsi</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($jenis_surats as $item)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-slate-900">{{ $item->name }}</div>
                                <div class="text-xs text-slate-500 font-mono bg-slate-100 px-1.5 py-0.5 rounded w-fit mt-0.5">
                                    {{ $item->code }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-slate-600 line-clamp-2 max-w-sm">{{ $item->description ?? '-' }}</p>
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
                            <a href="{{ route('admin.jenis-surat.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('admin.jenis-surat.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus jenis surat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <div class="h-16 w-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-folder-open text-2xl"></i>
                            </div>
                            <h3 class="text-base font-medium text-slate-900">Belum ada jenis surat</h3>
                            <p class="text-sm mt-1">Tambahkan template surat pertama Anda.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
