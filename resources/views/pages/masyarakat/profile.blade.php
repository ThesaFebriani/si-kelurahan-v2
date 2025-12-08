@extends('components.layout')

@section('title', 'Profil Saya - Sistem Kelurahan')
@section('page-title', 'Pengaturan Profil')
@section('page-description', 'Kelola informasi pribadi dan keamanan akun Anda')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <!-- Profile Header / Banner -->
    <div class="relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-8 shadow-xl overflow-hidden text-white">
        <!-- Abstract Background -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-60 h-60 rounded-full bg-purple-500/20 blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-8">
            <!-- Avatar Section -->
            <div class="flex-shrink-0">
                <div class="w-32 h-32 rounded-full border-4 border-white/20 shadow-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-4xl font-bold text-white relative group overflow-hidden">
                    <span>{{ substr($user->name, 0, 1) }}</span>
                    <!-- Hover effect hint for future upload feature -->
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <i class="fas fa-camera text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="flex-1 text-center md:text-left space-y-3">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">{{ $user->name }}</h2>
                    <p class="text-slate-300 font-medium text-lg">{{ $user->email }}</p>
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <span class="px-4 py-1.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-sm font-semibold backdrop-blur-sm">
                        <i class="fas fa-id-card mr-2"></i>NIK: {{ $user->nik }}
                    </span>
                    <span class="px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-sm font-semibold backdrop-blur-sm">
                        <i class="fas fa-user-check mr-2"></i>Warga Terverifikasi
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Sidebar: Menu / Status -->
        <div class="space-y-6">
            <!-- Navigation Card (Visual only for now, acts as anchor links) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-2 space-y-1">
                    <a href="#main-form" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl bg-blue-50 text-blue-700 font-medium transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-white text-blue-600 flex items-center justify-center shadow-sm">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <span>Edit Profil</span>
                    </a>
                    <a href="#keamanan" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium transition-colors cursor-pointer" onclick="document.getElementById('keamanan').scrollIntoView({behavior: 'smooth'}); return false;">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span>Keamanan</span>
                    </a>
                    <a href="{{ route('masyarakat.permohonan.index') }}" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 font-medium transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">
                            <i class="fas fa-history"></i>
                        </div>
                        <span>Riwayat Aktivitas</span>
                    </a>
                </div>
            </div>

            <!-- Profile Completion Stats -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-purple-500 mr-2"></i> Kelengkapan Data
                </h4>
                @php
                    $fields = ['name', 'email', 'nik', 'telepon', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'pekerjaan', 'agama', 'jk'];
                    $filled = 0;
                    foreach($fields as $field) {
                        if(!empty($user->$field)) $filled++;
                    }
                    $percentage = round(($filled / count($fields)) * 100);
                @endphp
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <span class="text-xs font-semibold inline-block text-purple-600">
                            {{ $percentage }}% Lengkap
                        </span>
                    </div>
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-purple-100">
                        <div style="width:{{ $percentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-purple-500 transition-all duration-1000"></div>
                    </div>
                    @if($percentage < 100)
                    <p class="text-xs text-gray-500">
                        Lengkapi profil Anda untuk memudahkan proses pengajuan surat.
                    </p>
                    @else
                    <p class="text-xs text-green-600 font-medium flex items-center">
                         <i class="fas fa-check-circle mr-1"></i> Data Anda sudah lengkap!
                    </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content: Update Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Edit Informasi Pribadi</h3>
                        <p class="text-sm text-gray-500">Perbarui data diri Anda secara berkala</p>
                    </div>
                </div>

                <form action="{{ route('masyarakat.profile.update') }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <!-- Personal Info Section -->
                    <h4 class="text-sm uppercase tracking-wider text-gray-400 font-bold mb-6 flex items-center">
                        <span class="bg-white pr-2">Data Diri Utama</span>
                        <div class="h-px bg-gray-200 flex-grow"></div>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none" placeholder="Nama Lengkap">
                            </div>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">NIK (Tidak dapat diubah)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" value="{{ $user->nik }}" disabled
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-100 bg-gray-100 text-gray-500 cursor-not-allowed">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user->tempat_lahir) }}"
                                class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}"
                                class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Agama</label>
                            <select name="agama" class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none bg-white">
                                <option value="">Pilih Agama</option>
                                @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agama)
                                <option value="{{ $agama }}" {{ old('agama', $user->agama) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan</label>
                            <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $user->pekerjaan) }}"
                                class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none" placeholder="Contoh: Wiraswasta, PNS, Petani">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                            <select name="jk" class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none appearance-none bg-white">
                                <option value="laki-laki" {{ old('jk', $user->jk) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ old('jk', $user->jk) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                         <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Perkawinan</label>
                            <select name="status_perkawinan" class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none bg-white">
                                <option value="">Pilih Status</option>
                                @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $status)
                                <option value="{{ $status }}" {{ old('status_perkawinan', $user->status_perkawinan) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kewarganegaraan</label>
                            <select name="kewarganegaraan" class="w-full px-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none bg-white">
                                <option value="WNI" {{ old('kewarganegaraan', $user->kewarganegaraan) == 'WNI' ? 'selected' : '' }}>WNI</option>
                                <option value="WNA" {{ old('kewarganegaraan', $user->kewarganegaraan) == 'WNA' ? 'selected' : '' }}>WNA</option>
                            </select>
                        </div>
                    </div>

                    <!-- Contact & Address -->
                     <h4 class="text-sm uppercase tracking-wider text-gray-400 font-bold mb-6 flex items-center">
                        <span class="bg-white pr-2">Kontak & Alamat</span>
                        <div class="h-px bg-gray-200 flex-grow"></div>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Akun</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. Handphone / WA</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                             <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                             <div class="relative">
                                <span class="absolute top-3 left-3 text-gray-400">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <textarea name="alamat" rows="3"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none resize-none leading-relaxed">{{ old('alamat', $user->alamat) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Security Section (Collapsible or just separated) -->
                     <h4 id="keamanan" class="text-sm uppercase tracking-wider text-gray-400 font-bold mb-6 flex items-center pt-8 scroll-mt-24">
                        <span class="bg-white pr-2">Keamanan (Ganti Password)</span>
                        <div class="h-px bg-gray-200 flex-grow"></div>
                    </h4>
                    
                    <div class="bg-orange-50 rounded-xl p-6 border border-orange-100 mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" name="password" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all outline-none bg-white" 
                                        placeholder="Min. 8 karakter">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">*Kosongkan jika tidak ingin mengganti</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all outline-none bg-white"
                                        placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                        <button type="button" onclick="window.history.back()" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-600 font-semibold hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
