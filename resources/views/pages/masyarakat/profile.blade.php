@extends('components.layout')

@section('title', 'Profil Saya - Sistem Kelurahan')
@section('page-title', 'Pengaturan Profil')

@section('content')
<div class="max-w-3xl mx-auto pb-10">

    @php
        // Logika Sinkronisasi Data (Prioritas: Master Data Penduduk > Data Akun User)
        $penduduk = $user->anggotaKeluarga;
        
        // Helper untuk format display
        $formatJK = function($val) {
            if ($val == 'L' || $val == 'laki-laki') return 'Laki-laki';
            if ($val == 'P' || $val == 'perempuan') return 'Perempuan';
            return $val;
        };

        // Helper untuk raw value (format database users) - Penting untuk hidden input
        $rawJK = function($val) {
             if ($val == 'L' || $val == 'laki-laki') return 'laki-laki'; // Lowercase sesuai enum DB Users
             if ($val == 'P' || $val == 'perempuan') return 'perempuan';
             return $val;
        };

        $formatStatus = function($val) {
            return ucwords(str_replace('_', ' ', $val));
        };

        // Data Display
        $d_nama = $penduduk ? $penduduk->nama_lengkap : $user->name;
        $d_tempat = $penduduk ? $penduduk->tempat_lahir : $user->tempat_lahir;
        $d_tgl = $penduduk ? $penduduk->tanggal_lahir : $user->tanggal_lahir;
        
        $d_jk_display = $penduduk ? $formatJK($penduduk->jk) : $formatJK($user->jk);
        $d_jk_raw = $penduduk ? $rawJK($penduduk->jk) : $rawJK($user->jk);

        $d_agama = $penduduk ? $penduduk->agama : $user->agama;
        $d_status = $penduduk ? $formatStatus($penduduk->status_perkawinan) : $formatStatus($user->status_perkawinan);
        $d_job = $penduduk ? $penduduk->pekerjaan : $user->pekerjaan;
        $d_warga = $penduduk ? $penduduk->kewarganegaraan : $user->kewarganegaraan;
        $d_pendidikan = $penduduk ? $penduduk->pendidikan : '-'; // Hanya ada di master penduduk
        $d_alamat = $penduduk ? $user->alamat : $user->alamat; 
    @endphp

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border-t-[10px] border-blue-600 p-8 mb-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 mt-4 mr-4">
             <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 tracking-wide uppercase">
                Profil Pengguna
            </span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2 mt-2">Profil Saya</h1>
        <p class="text-gray-600 text-lg leading-relaxed border-b border-gray-100 pb-6 mb-6">
            Informasi data diri Anda terintegrasi langsung dengan Data Kependudukan Desa.
        </p>

        <!-- Summary Badges -->
        <div class="flex flex-wrap gap-3">
             <div class="flex items-center text-sm font-medium text-gray-500 bg-gray-50 py-2 px-3 rounded-lg border border-gray-100">
                <i class="fas fa-id-badge mr-2 text-blue-500"></i> 
                {{ $user->nik }}
            </div>
             <div class="flex items-center text-sm font-medium text-gray-500 bg-gray-50 py-2 px-3 rounded-lg border border-gray-100">
                <i class="fas fa-envelope mr-2 text-indigo-500"></i> 
                {{ $user->email }}
            </div>
        </div>

        <!-- Info Alert -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Data dengan tanda kunci (<i class="fas fa-lock text-xs"></i>) adalah <strong>Data Resmi Kependudukan</strong>. 
                        Jika terdapat kesalahan, silakan hubungi Ketua RT atau Kantor Kelurahan untuk perbaikan data.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Container -->
    <form action="{{ route('masyarakat.profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Container 1: Data Isian Profil -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-blue-50/50 flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-user-edit text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-800">Data Pribadi (Sesuai KTP)</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 gap-6">
                <!-- NIK (Read Only) -->
                <div class="form-group group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        NIK <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" value="{{ $user->nik }}" disabled readonly
                            class="w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-xl cursor-not-allowed block p-3.5 pl-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Nama Lengkap (Read Only) -->
                <div class="form-group group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="name" value="{{ $d_nama }}" readonly
                            class="w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-xl cursor-not-allowed block p-3.5 pl-10">
                         <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Tempat Lahir (Read Only) -->
                <div class="form-group group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tempat Lahir
                    </label>
                    <div class="relative">
                        <input type="text" name="tempat_lahir" value="{{ $d_tempat }}" readonly
                            class="w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-xl cursor-not-allowed block p-3.5 pl-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Tanggal Lahir (Read Only) -->
                <div class="form-group group">
                     <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Lahir
                    </label>
                    <div class="relative">
                        <input type="date" name="tanggal_lahir" value="{{ $d_tgl }}" readonly
                            class="w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-xl cursor-not-allowed block p-3.5 pl-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Grid for smaller fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jenis Kelamin -->
                    <div class="form-group group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenis Kelamin
                        </label>
                        <div class="relative">
                             <input type="text" value="{{ $d_jk_display }}" readonly
                                class="w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-xl cursor-not-allowed block p-3.5 pl-10">
                             
                             <!-- Hidden Input untuk kirim data valid ke Controller -->
                             <input type="hidden" name="jk" value="{{ $d_jk_raw }}">

                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Agama -->
                    <div class="form-group group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Agama
                        </label>
                         <div class="relative">
                             <input type="text" name="agama" value="{{ $d_agama }}" readonly
                                class="w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-xl cursor-not-allowed block p-3.5 pl-10">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status Perkawinan -->
                    <div class="form-group group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status Perkawinan
                        </label>
                         <div class="relative">
                             <input type="text" name="status_perkawinan" value="{{ $d_status }}" readonly
                                class="w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-xl cursor-not-allowed block p-3.5 pl-10">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Kewarganegaraan -->
                    <div class="form-group group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Kewarganegaraan
                        </label>
                         <div class="relative">
                             <input type="text" name="kewarganegaraan" value="{{ $d_warga }}" readonly
                                class="w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-xl cursor-not-allowed block p-3.5 pl-10">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pendidikan Terakhir (NEW FIELD) -->
                <div class="form-group group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Pendidikan Terakhir
                    </label>
                    <div class="relative">
                        <input type="text" value="{{ $d_pendidikan }}" readonly
                            class="w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-xl cursor-not-allowed block p-3.5 pl-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Pekerjaan -->
                <div class="form-group group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Pekerjaan
                    </label>
                    <div class="relative">
                        <input type="text" name="pekerjaan" value="{{ $d_job }}" readonly
                            class="w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-xl cursor-not-allowed block p-3.5 pl-10">
                         <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="form-group group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Alamat Lengkap (Sesuai KTP)
                    </label>
                     <div class="relative">
                        <textarea name="alamat" rows="3" readonly
                            class="w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-xl cursor-not-allowed block p-3.5 pl-10">{{ $d_alamat }}</textarea>
                         <div class="absolute top-3 left-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container 2: Kontak & Akun (Editable) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
             <div class="px-6 py-4 border-b border-gray-100 bg-green-50/50 flex items-center space-x-3">
                 <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-address-book text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-800">Kontak & Keamanan (Dapat Diedit)</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 gap-6">
                <!-- Data Kontak yang boleh diubah -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email -->
                    <div class="form-group group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-green-600 transition-colors">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                         <div class="relative">
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full bg-green-50/30 border border-green-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 block p-3.5 transition-all" required>
                             @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- No HP -->
                    <div class="form-group group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-green-600 transition-colors">
                            No. Handphone / WhatsApp
                        </label>
                        <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}"
                            class="w-full bg-green-50/30 border border-green-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 block p-3.5 transition-all">
                    </div>
                </div>
                
                <hr class="border-gray-100 my-2">

                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div class="form-group group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-green-600 transition-colors">
                            Password Baru
                        </label>
                        <input type="password" name="password" placeholder="Kosongkan jika tetap"
                            class="w-full bg-green-50/30 border border-green-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 block p-3.5 transition-all">
                    </div>

                     <div class="form-group group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-green-600 transition-colors">
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                            class="w-full bg-green-50/30 border border-green-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 block p-3.5 transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-end gap-3">
             <button type="submit" 
                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 flex items-center text-sm">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan Kontak
            </button>
        </div>
    </form>
</div>
@endsection
