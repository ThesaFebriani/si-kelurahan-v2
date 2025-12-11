@extends('components.layout')

@section('title', 'Data Kependudukan')
@section('page-title', 'Data Kartu Keluarga')
@section('page-description', 'Kelola data Kartu Keluarga dan Anggota Keluarga per wilayah.')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow border border-slate-200">
        <!-- Filter Form -->
        <form action="{{ route('admin.kependudukan.keluarga.index') }}" method="GET" class="flex items-center gap-2">
            <!-- Filter RW -->
            <select name="rw_id" class="rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500 font-bold text-slate-700" onchange="this.form.rd_id.value=''; this.form.submit()">
                <option value="">Semua RW</option>
                @foreach($rws as $rw)
                    <option value="{{ $rw->id }}" {{ request('rw_id') == $rw->id ? 'selected' : '' }}>
                        RW {{ $rw->nomor_rw }}
                    </option>
                @endforeach
            </select>

            <!-- Filter RT -->
            <select name="rt_id" class="rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                <option value="">Semua RT</option>
                @foreach($rts as $rt)
                    <option value="{{ $rt->id }}" {{ request('rt_id') == $rt->id ? 'selected' : '' }}>
                        RT {{ $rt->nomor_rt }}
                    </option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('admin.kependudukan.keluarga.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah KK Baru
        </a>
    </div>

    <!-- KK List -->
    <div class="bg-white rounded-lg shadow overflow-hidden border border-slate-200">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Nomor KK</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Kepala Keluarga</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Wilayah</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Alamat</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($keluargas as $kk)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-mono text-blue-600 font-medium">{{ $kk->no_kk }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-700">
                        {{ $kk->kepala_keluarga ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            RT {{ $kk->rt->nomor_rt }} / RW {{ $kk->rt->rw->nomor_rw }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 line-clamp-1 max-w-xs">{{ $kk->alamat_lengkap }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.kependudukan.keluarga.show', $kk->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Detail & Anggota <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                        Belum ada data Kartu Keluarga. Silakan tambah data baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
