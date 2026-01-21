@extends('components.layout')

@section('title', 'Admin Dashboard - Sistem Kelurahan')
@section('page-title', 'Admin Dashboard')
@section('page-description', 'Overview lengkap sistem kelurahan')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Users Card -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Users</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total_users'] }}</h3>
                    <p class="text-green-600 text-xs mt-1">
                        <i class="fas fa-arrow-up"></i> All users
                    </p>
                </div>
                <div class="p-2.5 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Total Permohonan Card -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Permohonan</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total_permohonan'] }}</h3>
                    <p class="text-blue-600 text-xs mt-1">
                        <i class="fas fa-file-alt"></i> All requests
                    </p>
                </div>
                <div class="p-2.5 bg-green-100 rounded-lg">
                    <i class="fas fa-file-alt text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Pending Approval Card -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Pending Approval</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['permohonan_pending'] }}</h3>
                    <p class="text-yellow-600 text-xs mt-1">
                        <i class="fas fa-clock"></i> Needs action
                    </p>
                </div>
                <div class="p-2.5 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Jenis Surat Card -->
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Jenis Surat</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $stats['jenis_surat'] }}</h3>
                    <p class="text-purple-600 text-xs mt-1">
                        <i class="fas fa-list"></i> Available types
                    </p>
                </div>
                <div class="p-2.5 bg-purple-100 rounded-lg">
                    <i class="fas fa-file-contract text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Permohonan Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50/50">
            <h3 class="text-base font-semibold text-gray-800 flex items-center">
                <i class="fas fa-history text-blue-600 mr-2 text-sm"></i>
                Permohonan Terbaru
            </h3>
        </div>
        <div class="overflow-x-auto">
            @if($recent_permohonan->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Tiket</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recent_permohonan as $permohonan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-gray-900">
                            <code class="bg-gray-100 px-2 py-1 rounded">{{ $permohonan->nomor_tiket }}</code>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900">
                            <div class="flex items-center">
                                <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-[10px] font-semibold mr-2">
                                    {{ strtoupper(substr($permohonan->user->name, 0, 1)) }}
                                </div>
                                {{ $permohonan->user->name }}
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900">
                            {{ $permohonan->jenisSurat->name }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php
                            $statusContext = match($permohonan->status) {
                                'menunggu_rt' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'label' => 'Menunggu RT', 'dot' => 'bg-yellow-500'],
                                'disetujui_rt' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'Disetujui RT', 'dot' => 'bg-blue-500'],
                                'menunggu_kasi' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'label' => 'Menunggu Verifikasi', 'dot' => 'bg-orange-500'],
                                'disetujui_kasi' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'label' => 'Disetujui Kasi', 'dot' => 'bg-indigo-500'],
                                'menunggu_lurah' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'label' => 'Menunggu TTE', 'dot' => 'bg-purple-500'],
                                'selesai' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'label' => 'Selesai', 'dot' => 'bg-green-500'],
                                'ditolak_rt', 'ditolak_kasi', 'ditolak_lurah' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'label' => 'Ditolak', 'dot' => 'bg-red-500'],
                                default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'label' => 'Pending', 'dot' => 'bg-slate-500'],
                            };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $statusContext['bg'] }} {{ $statusContext['text'] }} {{ $statusContext['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusContext['dot'] }} mr-1.5"></span>
                                {{ $statusContext['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900">
                            {{ $permohonan->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500">Belum ada permohonan surat</p>
                <p class="text-gray-400 text-sm mt-1">Permohonan yang diajukan akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.users.index') }}" class="bg-white rounded-lg shadow p-4 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-2.5 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Management User</h3>
                    <p class="text-gray-600 text-xs mt-1">Kelola semua user sistem</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.jenis-surat.index') }}" class="bg-white rounded-lg shadow p-4 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-2.5 bg-green-100 rounded-lg">
                    <i class="fas fa-file-alt text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Jenis Surat</h3>
                    <p class="text-gray-600 text-xs mt-1">Kelola template surat</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.reports.index') }}" class="bg-white rounded-lg shadow p-4 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-2.5 bg-purple-100 rounded-lg">
                    <i class="fas fa-chart-pie text-purple-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Laporan & Statistik</h3>
                    <p class="text-gray-600 text-xs mt-1">Insight & Analitik</p>
                </div>
            </div>
        </a>

        <!-- BARU: Kependudukan -->
        <a href="{{ route('admin.kependudukan.keluarga.index') }}" class="bg-white rounded-lg shadow p-4 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-2.5 bg-orange-100 rounded-lg">
                    <i class="fas fa-id-card text-orange-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Data Kependudukan</h3>
                    <p class="text-gray-600 text-xs mt-1">Kelola KK & Warga</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection