@extends('components.layout')

@section('title', 'Data Keluarga RT')
@section('page-title', 'Data Kartu Keluarga')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow overflow-hidden border border-slate-200">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">No KK</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Kepala Keluarga</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Alamat</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Jml Anggota</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($keluarga as $kk)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-mono text-blue-600 font-medium">{{ $kk->no_kk }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-700">
                        {{ $kk->kepala_keluarga ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 line-clamp-1 max-w-xs">{{ $kk->alamat_lengkap }}</td>
                    <td class="px-6 py-4 text-sm text-center">
                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-bold">
                            {{ $kk->anggota_keluarga_count ?? $kk->anggotaKeluarga->count() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('rt.keluarga.show', $kk->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Detail <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                        Belum ada data Kartu Keluarga di RT ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
