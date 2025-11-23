@extends('components.layout')

@section('title', 'Permohonan TTE - Lurah')
@section('page-title', 'Permohonan Tanda Tangan Elektronik')
@section('page-description', 'Kelola permohonan surat yang menunggu TTE')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Menunggu TTE</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['pending'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-signature text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Selesai</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['completed'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Permohonan -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-signature text-purple-600 mr-2"></i>
                Daftar Permohonan Menunggu TTE
            </h3>
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
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RT/RW</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
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
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-semibold mr-3">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->user->alamat_lengkap ?? 'Tidak ada alamat' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->jenisSurat->name }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($item->user->rt && $item->user->rt->rw)
                                RT {{ $item->user->rt->nomor_rt }} / RW {{ $item->user->rt->rw->nomor_rw }}
                                @else
                                -
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $item->status_display }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('lurah.permohonan.detail', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                @if($item->isMenungguLurah())
                                <a href="{{ route('lurah.permohonan.sign', $item->id) }}"
                                    class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-signature mr-1"></i> TTE
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
                <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Tidak ada permohonan menunggu TTE</p>
                <p class="text-gray-400 text-sm mt-1">Permohonan yang sudah disetujui Kasi akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection