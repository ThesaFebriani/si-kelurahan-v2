@extends('components.layout')

@section('title', 'Masyarakat Dashboard - Sistem Kelurahan')
@section('page-title', 'Masyarakat Dashboard')
@section('page-description', 'Dashboard Masyarakat')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Total Permohonan</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">8</h3>
                    <p class="text-blue-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-file-alt"></i> All requests
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-alt text-blue-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 lg:p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm lg:text-base">Dalam Proses</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">2</h3>
                    <p class="text-yellow-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-clock"></i> In progress
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
                    <p class="text-gray-600 text-sm lg:text-base">Selesai</p>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-800 mt-1">6</h3>
                    <p class="text-green-600 text-xs lg:text-sm mt-1">
                        <i class="fas fa-check-circle"></i> Completed
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
        <a href="{{ route('masyarakat.permohonan.create') }}" class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-plus-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Ajukan Permohonan</h3>
                    <p class="text-gray-600 text-sm mt-1">Ajukan permohonan surat baru</p>
                </div>
            </div>
        </a>

        <a href="{{ route('masyarakat.permohonan.index') }}" class="bg-white rounded-lg shadow p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-history text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Riwayat Permohonan</h3>
                    <p class="text-gray-600 text-sm mt-1">Lihat riwayat permohonan</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection