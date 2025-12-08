@extends('components.layout')

@section('title', 'Dashboard Lurah')
@section('page-title', 'Dashboard Lurah')
@section('page-description', 'Selamat datang di dashboard Lurah')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Permohonan Menunggu TTE -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Menunggu TTE</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['pending_permohonan'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-signature text-purple-600"></i>
                </div>
            </div>
            <a href="{{ route('lurah.tanda-tangan.index') }}"
                class="mt-3 inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                Mulai Tanda Tangan
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Permohonan Selesai -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Selesai</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['completed_permohonan'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Permohonan -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total_permohonan'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Permohonan -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-history text-blue-600 mr-2"></i>
                Permohonan Menunggu Tanda Tangan
            </h3>
            <a href="{{ route('lurah.permohonan.index') }}"
                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat semua
            </a>
        </div>

        <div class="p-6">
            @if($recentPermohonan->count() > 0)
            <div class="space-y-4">
                @foreach($recentPermohonan as $permohonan)
                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold">
                            {{ strtoupper(substr($permohonan->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $permohonan->user->name }}</h4>
                            <p class="text-sm text-gray-500">
                                {{ $permohonan->jenisSurat->name }} •
                                @if($permohonan->user->rt && $permohonan->user->rt->rw)
                                RT {{ $permohonan->user->rt->nomor_rt }} / RW {{ $permohonan->user->rt->rw->nomor_rw }}
                                @else
                                Tidak terdaftar
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $permohonan->status_display }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $permohonan->created_at->format('d/m H:i') }}</span>
                        <a href="{{ route('lurah.permohonan.detail', $permohonan->id) }}"
                            class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Tidak ada permohonan menunggu tanda tangan</p>
                <p class="text-gray-400 text-sm mt-1">Permohonan yang sudah disetujui Kasi akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection