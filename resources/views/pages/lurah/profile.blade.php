@extends('components.layout')

@section('title', 'Profil Lurah - Sistem Kelurahan')
@section('page-title', 'Pengaturan Profil Lurah')
@section('page-description', 'Kelola informasi pribadi, NIP, Jabatan, dan keamanan akun')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Header Banner -->
    <div class="relative bg-gradient-to-br from-indigo-800 to-purple-900 rounded-3xl p-8 shadow-xl overflow-hidden text-white">
        <!-- Abstract Background -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-white/10 blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-8">
            <!-- Avatar Section -->
            <div class="flex-shrink-0">
                <div class="w-32 h-32 rounded-full border-4 border-white/20 shadow-2xl bg-indigo-500 flex items-center justify-center text-4xl font-bold text-white relative group overflow-hidden">
                    <span>{{ substr($user->name, 0, 1) }}</span>
                </div>
            </div>

            <!-- User Info -->
            <div class="flex-1 text-center md:text-left space-y-3">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">{{ $user->name }}</h2>
                    <div class="flex flex-col md:flex-row items-center gap-2 text-indigo-200">
                        <span class="font-medium">{{ $user->jabatan ?? 'Jabatan belum diisi' }}</span>
                        <span class="hidden md:inline">•</span>
                        <span class="font-mono text-sm opacity-80">{{ $user->nip ?? 'NIP belum diisi' }}</span>
                    </div>
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <span class="px-4 py-1.5 rounded-full bg-white/20 border border-white/20 text-white text-sm font-semibold backdrop-blur-sm">
                        <i class="fas fa-user-tie mr-2"></i>Akun Lurah
                    </span>
                    <span class="px-4 py-1.5 rounded-full bg-green-500/20 border border-green-400/30 text-green-200 text-sm font-semibold backdrop-blur-sm">
                        <i class="fas fa-check-circle mr-2"></i>Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Update Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Edit Informasi Profil</h3>
                <p class="text-sm text-gray-500">Perbarui data nama, jabatan, NIP, dan password</p>
            </div>
        </div>

        <form action="{{ route('lurah.profile.update') }}" method="POST" class="p-8">
            @csrf
            @method('PUT')

            <!-- Data Jabatan -->
            <h4 class="text-sm uppercase tracking-wider text-gray-400 font-bold mb-6 flex items-center">
                <span class="bg-white pr-2">Data Jabatan Formal</span>
                <div class="h-px bg-gray-200 flex-grow"></div>
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap & Gelar</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-user-tie"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none" placeholder="Contoh: EDWIN KURNIAWAN, SH">
                    </div>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Akun</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Induk Pegawai (NIP)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-id-badge"></i>
                        </span>
                        <input type="text" name="nip" value="{{ old('nip', $user->nip) }}"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none" placeholder="198xxxxxxxxx">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-briefcase"></i>
                        </span>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none" placeholder="Kepala Kelurahan">
                    </div>
                </div>
            </div>

            <!-- Keamanan -->
             <h4 class="text-sm uppercase tracking-wider text-gray-400 font-bold mb-6 flex items-center pt-4">
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
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
