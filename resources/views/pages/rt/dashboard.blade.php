@extends('components.layout')

@section('title', 'RT Dashboard - Sistem Kelurahan')
@section('page-title', 'RT Dashboard')
@section('page-description', 'Dashboard Ketua RT')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Permohonan Pending</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">5</h3>
                    <p class="text-yellow-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-clock"></i> Needs review
                    </p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Disetujui</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">12</h3>
                    <p class="text-green-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-check-circle"></i> Approved
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Total Keluarga</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">45</h3>
                    <p class="text-blue-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-home"></i> In your area
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-home text-blue-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-4 lg:p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-bolt text-blue-600 mr-2"></i>
                Quick Actions
            </h3>
        </div>
        <div class="p-4 lg:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="#" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-file-signature text-blue-600 text-xl"></i>
                        <div>
                            <h4 class="font-semibold text-gray-800">Permohonan Surat</h4>
                            <p class="text-gray-600 text-sm">Kelola permohonan surat</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('rt.keluarga.index') }}" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-home text-green-600 text-xl"></i>
                        <div>
                            <h4 class="font-semibold text-gray-800">Data Keluarga</h4>
                            <p class="text-gray-600 text-sm">Lihat data keluarga</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-4 lg:p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-history text-gray-600 mr-2"></i>
                Aktivitas Terbaru
            </h3>
        </div>
        <div class="p-4 lg:p-6">
            <div class="space-y-4">
                <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                    <i class="fas fa-file-plus text-blue-600"></i>
                    <div>
                        <p class="font-medium text-gray-800">Permohonan baru dari Budi Santoso</p>
                        <p class="text-gray-600 text-sm">Surat Keterangan Domisili</p>
                    </div>
                    <span class="ml-auto bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                </div>

                <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <div>
                        <p class="font-medium text-gray-800">Permohonan disetujui</p>
                        <p class="text-gray-600 text-sm">Surat Keterangan Usaha - Siti Rahayu</p>
                    </div>
                    <span class="ml-auto bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Approved</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection