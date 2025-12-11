@extends('components.layout')

@section('title', 'Admin Dashboard - Sistem Kelurahan')
@section('page-title', 'Admin Dashboard')
@section('page-description', 'Overview lengkap sistem kelurahan')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <!-- Total Users Card -->
        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Total Users</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_users'] }}</h3>
                    <p class="text-green-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-arrow-up"></i> All users
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Permohonan Card -->
        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Total Permohonan</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_permohonan'] }}</h3>
                    <p class="text-blue-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-file-alt"></i> All requests
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-file-alt text-green-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Approval Card -->
        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Pending Approval</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">{{ $stats['permohonan_pending'] }}</h3>
                    <p class="text-yellow-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-clock"></i> Needs action
                    </p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Jenis Surat Card -->
        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Jenis Surat</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">{{ $stats['jenis_surat'] }}</h3>
                    <p class="text-purple-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-list"></i> Available types
                    </p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-file-contract text-purple-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Permohonan Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-4 lg:p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-history text-blue-600 mr-2"></i>
                Permohonan Terbaru
            </h3>
        </div>
        <div class="p-4 lg:p-6">
            @if($recent_permohonan->count() > 0)
            <div class="overflow-x-auto">
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
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $permohonan->nomor_tiket }}</code>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-semibold mr-3">
                                        {{ strtoupper(substr($permohonan->user->name, 0, 1)) }}
                                    </div>
                                    {{ $permohonan->user->name }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $permohonan->jenisSurat->name }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                $statusColors = [
                                'menunggu_rt' => 'bg-yellow-100 text-yellow-800',
                                'disetujui_rt' => 'bg-blue-100 text-blue-800',
                                'ditolak_rt' => 'bg-red-100 text-red-800',
                                'menunggu_kasi' => 'bg-orange-100 text-orange-800',
                                'disetujui_kasi' => 'bg-green-100 text-green-800',
                                'selesai' => 'bg-green-100 text-green-800'
                                ];
                                $color = $statusColors[$permohonan->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                    {{ $permohonan->getStatusDisplayAttribute() }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $permohonan->created_at->format('d/m/Y H:i') }}
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
                <p class="text-gray-400 text-sm mt-1">Permohonan yang diajukan akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
        <a href="{{ route('admin.users.index') }}" class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Management User</h3>
                    <p class="text-gray-600 text-sm mt-1">Kelola semua user sistem</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.jenis-surat.index') }}" class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-file-alt text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Jenis Surat</h3>
                    <p class="text-gray-600 text-sm mt-1">Kelola template surat</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.laporan.permohonan') }}" class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Laporan</h3>
                    <p class="text-gray-600 text-sm mt-1">Lihat laporan sistem</p>
                </div>
            </div>
        </a>

        <!-- BARU: Kependudukan -->
        <a href="{{ route('admin.kependudukan.keluarga.index') }}" class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-id-card text-orange-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Data Kependudukan</h3>
                    <p class="text-gray-600 text-sm mt-1">Kelola KK & Warga</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection