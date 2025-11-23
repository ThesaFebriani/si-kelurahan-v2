@extends('components.layout')

@section('title', 'Lurah Dashboard - Sistem Kelurahan')
@section('page-title', 'Lurah Dashboard')
@section('page-description', 'Dashboard Lurah')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Tanda Tangan Pending</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">3</h3>
                    <p class="text-purple-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-signature"></i> Needs signing
                    </p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-signature text-purple-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Surat Ditandatangani</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">42</h3>
                    <p class="text-green-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-stamp"></i> Signed
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-stamp text-green-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Total Surat</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">45</h3>
                    <p class="text-blue-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-file-contract"></i> All documents
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-contract text-blue-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Bulan Ini</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">12</h3>
                    <p class="text-orange-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-calendar"></i> This month
                    </p>
                </div>
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-calendar text-orange-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
        <a href="{{ route('lurah.tanda-tangan.index') }}" class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-signature text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Tanda Tangan Digital</h3>
                    <p class="text-gray-600 text-sm mt-1">Tandatangani surat-surat</p>
                </div>
            </div>
        </a>

        <a href="{{ route('lurah.laporan.index') }}" class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-chart-bar text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Laporan</h3>
                    <p class="text-gray-600 text-sm mt-1">Lihat laporan sistem</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection