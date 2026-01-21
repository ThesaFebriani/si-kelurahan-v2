@extends('components.layout')

@section('title', 'Permohonan Surat - RT')
@section('page-title', 'Permohonan Surat')
@section('page-description', 'Kelola permohonan surat dari warga')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('rt.permohonan.index') }}" class="block transform transition duration-200 hover:scale-[1.02]">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Menunggu</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="p-2.5 bg-yellow-50 rounded-lg border border-yellow-100">
                        <i class="fas fa-clock text-yellow-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('rt.permohonan.arsip', ['status' => 'approved']) }}" class="block transform transition duration-200 hover:scale-[1.02]">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Disetujui</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['approved'] }}</h3>
                    </div>
                    <div class="p-2.5 bg-green-50 rounded-lg border border-green-100">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('rt.permohonan.arsip', ['status' => 'rejected']) }}" class="block transform transition duration-200 hover:scale-[1.02]">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Ditolak</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['rejected'] }}</h3>
                    </div>
                    <div class="p-2.5 bg-red-50 rounded-lg border border-red-100">
                        <i class="fas fa-times-circle text-red-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </a>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total'] }}</h3>
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
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
                                        <div class="text-xs text-gray-500">RT {{ $item->user->rt->nomor_rt }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-700">
                                {{ $item->jenisSurat->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $statusContext = match($item->status) {
                                    'menunggu_rt' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'label' => 'Menunggu RT', 'dot' => 'bg-yellow-500'],
                                    'disetujui_rt' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'Disetujui RT', 'dot' => 'bg-blue-500'],
                                    'menunggu_kasi' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'label' => 'Menunggu Verifikasi', 'dot' => 'bg-orange-500'],
                                    'disetujui_kasi' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'label' => 'Disetujui Kasi', 'dot' => 'bg-indigo-500'],
                                    // Handle legacy/alternate spelling if needed, but standardizing on these keys
                                    'ditolak_kasi' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'label' => 'Ditolak', 'dot' => 'bg-red-500'],
                                    'menunggu_lurah' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'label' => 'Menunggu TTE', 'dot' => 'bg-purple-500'],
                                    'selesai' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'label' => 'Selesai', 'dot' => 'bg-green-500'],
                                    'ditolak_rt', 'ditolak_lurah' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'label' => 'Ditolak', 'dot' => 'bg-red-500'],
                                    default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'label' => 'Pending', 'dot' => 'bg-slate-500'],
                                };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusContext['bg'] }} {{ $statusContext['text'] }} {{ $statusContext['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusContext['dot'] }} mr-1.5"></span>
                                    {{ $statusContext['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('rt.permohonan.detail', $item->id) }}"
                                    class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm shadow-blue-200">
                                    <i class="fas fa-eye mr-1.5"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Belum ada permohonan surat</p>
                <p class="text-gray-400 text-sm mt-1">Permohonan dari warga akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection