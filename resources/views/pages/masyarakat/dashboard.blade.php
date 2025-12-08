@extends('components.layout')

@section('title', 'Masyarakat Dashboard - Sistem Kelurahan')
@section('page-title', 'Masyarakat Dashboard')
@section('page-description', 'Dashboard Masyarakat')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Banner/Greeting -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 lg:p-8 text-white flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
         <!-- Deco circles -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full bg-white/10 blur-2xl"></div>

        <div class="relative z-10">
            <h2 class="text-2xl lg:text-3xl font-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-blue-100 max-w-xl">Selamat datang di Dashboard Pelayanan Digital. Pantau status surat dan ajukan permohonan baru dengan mudah.</p>
        </div>
        <div class="relative z-10 mt-6 md:mt-0">
            <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 text-sm font-medium">
                <i class="fas fa-calendar-alt mr-2"></i> {{ \Carbon\Carbon::now()->format('d F Y') }}
            </span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card Total -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 relative group overflow-hidden transition-all hover:shadow-lg">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span class="text-gray-500 font-medium text-sm uppercase tracking-wide">Total Pengajuan</span>
                </div>
                <h3 class="text-4xl font-bold text-gray-800">{{ $stats['total_permohonan'] }}</h3>
            </div>
        </div>

        <!-- Card Pending -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 relative group overflow-hidden transition-all hover:shadow-lg">
            <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="text-gray-500 font-medium text-sm uppercase tracking-wide">Dalam Proses</span>
                </div>
                <h3 class="text-4xl font-bold text-gray-800">{{ $stats['permohonan_pending'] }}</h3>
            </div>
        </div>

        <!-- Card Selesai -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 relative group overflow-hidden transition-all hover:shadow-lg">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <span class="text-gray-500 font-medium text-sm uppercase tracking-wide">Selesai</span>
                </div>
                <h3 class="text-4xl font-bold text-gray-800">{{ $stats['permohonan_selesai'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i> Akses Cepat
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('masyarakat.permohonan.create') }}" class="group bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-start space-x-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 group-hover:text-green-600 transition-colors">Ajukan Surat Baru</h4>
                        <p class="text-gray-500 text-sm mt-1 leading-relaxed">Buat permohonan surat pengantar atau keterangan baru.</p>
                    </div>
                    <div class="ml-auto flex items-center justify-center h-full">
                         <i class="fas fa-chevron-right text-gray-300 group-hover:text-green-600 transition-colors"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('masyarakat.permohonan.index') }}" class="group bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-start space-x-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors">Lihat Riwayat</h4>
                        <p class="text-gray-500 text-sm mt-1 leading-relaxed">Pantau proses dan histori pengajuan yang telah dibuat.</p>
                    </div>
                    <div class="ml-auto flex items-center justify-center h-full">
                         <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-600 transition-colors"></i>
                    </div>
                </div>
            </a>
            
             <a href="{{ route('masyarakat.profile.index') }}" class="group bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-start space-x-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 group-hover:text-purple-600 transition-colors">Kelola Profil</h4>
                        <p class="text-gray-500 text-sm mt-1 leading-relaxed">Update data diri untuk pengisian formulir otomatis.</p>
                    </div>
                    <div class="ml-auto flex items-center justify-center h-full">
                         <i class="fas fa-chevron-right text-gray-300 group-hover:text-purple-600 transition-colors"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection