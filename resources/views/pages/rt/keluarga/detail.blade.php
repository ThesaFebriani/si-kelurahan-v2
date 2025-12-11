@extends('components.layout')

@section('title', 'Detail Keluarga')
@section('page-title', 'Detail Kartu Keluarga')

@section('content')
<div class="space-y-6">
    <!-- Info KK -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
        <div class="flex justify-between items-start mb-6 border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $keluarga->no_kk }}</h2>
                <p class="text-slate-500">Kepala Keluarga: <span class="font-semibold text-slate-700">{{ $keluarga->kepala_keluarga }}</span></p>
            </div>
            <a href="{{ route('rt.keluarga.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <p class="text-slate-500 mb-1">Alamat Lengkap</p>
                <p class="font-medium text-slate-800">{{ $keluarga->alamat_lengkap }}</p>
            </div>
            <div>
                <p class="text-slate-500 mb-1">Kode Pos</p>
                <p class="font-medium text-slate-800">{{ $keluarga->kodepos }}</p>
            </div>
        </div>
    </div>

    <!-- Anggota Keluarga -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800">Daftar Anggota Keluarga</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">NIK</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Nama Lengkap</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">L/P</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Hubungan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">TTL</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Pekerjaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($keluarga->anggotaKeluarga as $member)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-mono text-slate-600 font-medium">{{ $member->nik }}</td>
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $member->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm">{{ $member->jk }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $member->status_hubungan == 'kepala_keluarga' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucwords(str_replace('_', ' ', $member->status_hubungan)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $member->tempat_lahir }}, {{ date('d-m-Y', strtotime($member->tanggal_lahir)) }}
                        </td>
                         <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $member->pekerjaan }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 italic">
                            Belum ada anggota keluarga yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
