@extends('components.layout')

@section('title', 'Profil Saya - Sistem Kelurahan')
@section('page-title', 'Profil Saya')
@section('page-description', 'Kelola informasi pribadi dan keamanan akun Anda')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    @php
        // Helper Data
        $penduduk = $user->anggotaKeluarga;
        
        // Data Display Helpers
        $getData = fn($field, $default = '-') => $penduduk ? ($penduduk->$field ?? $default) : ($user->$field ?? $default);
        $formatDate = fn($date) => $date ? \Carbon\Carbon::parse($date)->format('d F Y') : '-';
        
        $d_nama = $getData('nama_lengkap', $user->name);
        $d_nik = $user->nik;
        $d_tempat = $getData('tempat_lahir');
        $d_tgl = $formatDate($getData('tanggal_lahir'));
        $d_jk = $getData('jk') == 'L' ? 'Laki-laki' : 'Perempuan';
        $d_tgl = $getData('tanggal_lahir'); // Keep as raw date for Carbon::parse later
        $d_jk = $getData('jk');
        $d_jk_display = $d_jk == 'L' ? 'Laki-laki' : ($d_jk == 'P' ? 'Perempuan' : '-');
        $d_alamat = $getData('alamat', $user->alamat);
        
        $d_agama = $getData('agama');
        $d_status = $penduduk ? ucwords(str_replace('_', ' ', $penduduk->status_perkawinan)) : '-';
        $d_job = $getData('pekerjaan');
        $d_warga = $getData('kewarganegaraan');
    @endphp
    


    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- LEFT COLUMN: Profile Summary Card -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <!-- Decoration Banner -->
                <div class="h-32 bg-gradient-to-br from-blue-600 to-indigo-600 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-8 -mt-8 blur-2xl"></div>
                </div>
                
                <div class="px-6 relative">
                    <!-- Avatar -->
                    <div class="w-28 h-28 bg-white rounded-full border-4 border-white shadow-lg absolute -top-14 flex items-center justify-center">
                        <span class="text-4xl font-bold text-blue-600">{{ substr($d_nama, 0, 1) }}</span>
                    </div>
                </div>

                <div class="px-6 pt-16 pb-8 text-center">
                    <h2 class="text-xl font-bold text-gray-900 leading-tight mb-1">{{ $d_nama }}</h2>
                    <p class="text-gray-500 font-medium text-sm mb-4">{{ $user->nik }}</p>
                    
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wide border border-blue-100">
                        WARGA
                    </div>

                    <div class="mt-6 flex items-center justify-center text-gray-500 text-sm">
                        <i class="fas fa-map-marker-alt text-red-400 mr-2"></i>
                        <span class="truncate max-w-[200px]">{{ $d_alamat }}</span>
                    </div>
                </div>

                <!-- Vertical Stats/Info -->
                <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-4">
                     <div class="flex justify-between items-center text-sm mb-2">
                        <span class="text-gray-500">Status Akun</span>
                        <span class="text-green-600 font-bold flex items-center">
                            <i class="fas fa-check-circle mr-1.5"></i> Aktif
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                         <span class="text-gray-500">Bergabung</span>
                         <span class="text-gray-800 font-medium">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Tabs and Content -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Tab Navigation (Pills Style) -->
            <div class="bg-white p-1.5 rounded-xl border border-gray-200 shadow-sm flex overflow-x-auto">
                <button @click="activeTab = 'biodata'" 
                        :class="{ 'bg-blue-600 text-white shadow-sm': activeTab === 'biodata', 'text-gray-600 hover:bg-gray-50': activeTab !== 'biodata' }"
                        class="flex-1 px-4 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center space-x-2 whitespace-nowrap">
                    <i class="fas fa-id-card"></i>
                    <span>Biodata Kependudukan</span>
                </button>
                <button @click="activeTab = 'akun'" 
                         :class="{ 'bg-blue-600 text-white shadow-sm': activeTab === 'akun', 'text-gray-600 hover:bg-gray-50': activeTab !== 'akun' }"
                        class="flex-1 px-4 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center space-x-2 whitespace-nowrap">
                    <i class="fas fa-user-shield"></i>
                    <span>Pengaturan Akun</span>
                </button>
            </div>

            <!-- Tab Content: Biodata -->
            <div x-show="activeTab === 'biodata'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-6">
                
                <!-- Alert Info -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                     <i class="fas fa-info-circle text-blue-600 mt-0.5 text-lg"></i>
                     <div>
                        <h4 class="text-sm font-bold text-blue-900">Informasi Data Diri</h4>
                        <p class="text-sm text-blue-700 mt-1 leading-relaxed">
                            Data di bawah ini disinkronisasi langsung dari database kependudukan desa. 
                            Jika terdapat kesalahan, silakan hubungi petugas kelurahan untuk perbaikan.
                        </p>
                     </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-gray-800 text-lg">Detail Biodata</h3>
                        <span class="text-[10px] font-bold uppercase text-gray-400 bg-white border border-gray-200 px-2 py-1 rounded tracking-wider">Read-Only</span>
                    </div>
                    <div class="p-6">
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Nama Lengkap</label>
                                <div class="text-slate-800 font-semibold text-base">{{ $d_nama }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">NIK</label>
                                <div class="text-slate-800 font-semibold text-base font-mono">{{ $user->nik }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Tempat, Tanggal Lahir</label>
                                <div class="text-slate-800 font-medium text-base">{{ $d_tempat }}, {{ $d_tgl ? \Carbon\Carbon::parse($d_tgl)->translatedFormat('d F Y') : '-' }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Jenis Kelamin</label>
                                <div class="text-slate-800 font-medium text-base">{{ $d_jk_display }}</div>
                            </div>
                            <div>
                                 <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Agama</label>
                                <div class="text-slate-800 font-medium text-base">{{ $d_agama ?? '-' }}</div>
                            </div>
                             <div>
                                 <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Status Perkawinan</label>
                                <div class="text-slate-800 font-medium text-base">{{ $d_status ?? '-' }}</div>
                            </div>
                             <div>
                                 <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Pekerjaan</label>
                                <div class="text-slate-800 font-medium text-base">{{ $d_job ?? '-' }}</div>
                            </div>
                             <div>
                                 <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Kewarganegaraan</label>
                                <div class="text-slate-800 font-medium text-base">{{ $d_warga ?? '-' }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Alamat Lengkap</label>
                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-slate-700 text-sm leading-relaxed border-l-4 border-l-blue-500">
                                    {{ $d_alamat }}
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Akun -->
            <div x-show="activeTab === 'akun'" style="display: none;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="bg-white rounded-2xl shadow-sm border border-yellow-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-yellow-200 bg-yellow-50 flex items-center justify-between">
                         <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-yellow-900 text-lg">Keamanan Akun</h3>
                                <p class="text-xs text-yellow-700">Update email & password login anda</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('masyarakat.profile.update') }}" method="POST" class="p-6 md:p-8 space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Email -->
                            <div class="form-group md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Alamat Email Aktif
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="pl-10 block w-full border-gray-200 rounded-lg focus:ring-yellow-500 focus:border-yellow-500 transition-colors"
                                        placeholder="contoh@email.com" required>
                                </div>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- No HP -->
                             <div class="form-group md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    No. Handphone / WhatsApp
                                </label>
                                <div class="relative">
                                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}"
                                        class="pl-10 block w-full border-gray-200 rounded-lg focus:ring-yellow-500 focus:border-yellow-500 transition-colors"
                                        placeholder="08xxxxxxxxxx">
                                </div>
                            </div>


                            <div class="md:col-span-2 border-t border-dashed border-gray-200 my-2"></div>

                            <!-- Password Fields -->
                            <div class="form-group">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Password Baru
                                </label>
                                <input type="password" name="password" placeholder="••••••••"
                                    class="block w-full border-gray-200 rounded-lg focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                            </div>

                            <div class="form-group">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Ulangi Password Baru
                                </label>
                                <input type="password" name="password_confirmation" placeholder="••••••••"
                                    class="block w-full border-gray-200 rounded-lg focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex justify-end">
                            <button type="submit" 
                                    class="px-8 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-bold shadow-sm shadow-yellow-200 transition-all transform hover:-translate-y-0.5 flex items-center">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
