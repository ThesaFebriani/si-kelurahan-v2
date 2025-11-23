@extends('components.layout')

@section('title', 'Kasi Dashboard - Sistem Kelurahan')
@section('page-title', 'Kasi Dashboard')
@section('page-description', 'Dashboard Kepala Seksi')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Verifikasi Pending</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">8</h3>
                    <p class="text-orange-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-clock"></i> Needs action
                    </p>
                </div>
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-tasks text-orange-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Terverifikasi</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">25</h3>
                    <p class="text-green-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-check-double"></i> Completed
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-double text-green-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Surat Diterbitkan</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">18</h3>
                    <p class="text-purple-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-file-pdf"></i> Generated
                    </p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-file-pdf text-purple-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Bidang</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1 capitalize">{{ Auth::user()->bidang ?? 'Kesra' }}</h3>
                    <p class="text-blue-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-network-wired"></i> Your domain
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-network-wired text-blue-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Verification Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-4 lg:p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-list-check text-orange-600 mr-2"></i>
                Permohonan Perlu Verifikasi
            </h3>
        </div>
        <div class="p-4 lg:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Tiket</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RT</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <code class="bg-gray-100 px-2 py-1 rounded text-xs">TKT-ABC123</code>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-semibold mr-3">
                                        B
                                    </div>
                                    Budi Santoso
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                Surat Keterangan Domisili
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                RT 001
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                20/11/2025
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('kasi.permohonan.verify', 1) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 transition-colors">
                                    Verifikasi
                                </a>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <code class="bg-gray-100 px-2 py-1 rounded text-xs">TKT-DEF456</code>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xs font-semibold mr-3">
                                        S
                                    </div>
                                    Siti Rahayu
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                Surat Keterangan Usaha
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                RT 002
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                19/11/2025
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('kasi.permohonan.verify', 2) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 transition-colors">
                                    Verifikasi
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
        <a href="{{ route('kasi.permohonan.index') }}" class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Verifikasi Permohonan</h3>
                    <p class="text-gray-600 text-sm mt-1">Kelola semua permohonan yang perlu verifikasi</p>
                </div>
            </div>
        </a>

        <a href="{{ route('kasi.template.index') }}" class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-file-contract text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Template Surat</h3>
                    <p class="text-gray-600 text-sm mt-1">Kelola template surat</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection