@extends('components.layout')

@section('title', 'Profil Lurah - Sistem Kelurahan')
@section('page-title', 'Pengaturan Profil')

@section('content')
<div class="max-w-3xl mx-auto pb-10">

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border-t-[10px] border-blue-600 p-8 mb-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 mt-4 mr-4">
             <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 tracking-wide uppercase">
                Profil Pejabat
            </span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2 mt-2">Profil Lurah</h1>
        <p class="text-gray-600 text-lg leading-relaxed border-b border-gray-100 pb-6 mb-6">
            Kelola informasi identitas pejabat, NIP, Jabatan, dan keamanan akun Anda.
        </p>

        <!-- Summary Badges -->
        <div class="flex flex-wrap gap-3">
             <div class="flex items-center text-sm font-medium text-gray-500 bg-gray-50 py-2 px-3 rounded-lg border border-gray-100">
                <i class="fas fa-id-badge mr-2 text-blue-500"></i> 
                {{ $user->nip ?? 'Belum ada NIP' }}
            </div>
             <div class="flex items-center text-sm font-medium text-gray-500 bg-gray-50 py-2 px-3 rounded-lg border border-gray-100">
                <i class="fas fa-briefcase mr-2 text-indigo-500"></i> 
                {{ $user->jabatan ?? 'Jabatan belum diisi' }}
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
                        Pastikan data <strong>Nama Lengkap (termasuk Gelar)</strong> dan <strong>NIP</strong> terisi dengan benar karena akan digunakan sebagai <strong>Tanda Tangan Digital (TTE)</strong> pada surat kelurahan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Container -->
    <form action="{{ route('lurah.profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Container 1: Data Pejabat -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-blue-50/50 flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-user-tie text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-800">Informasi Pejabat & Akun</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 gap-6">
                <!-- Nama Lengkap -->
                <div class="form-group group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-blue-600 transition-colors">
                        Nama Lengkap & Gelar <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full bg-blue-50/30 border border-blue-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-3.5 pl-10 transition-all placeholder-gray-400"
                            placeholder="Contoh: EDWIN KURNIAWAN, SH">
                         <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-400">
                            <i class="fas fa-user-edit"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- NIP -->
                    <div class="form-group group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-blue-600 transition-colors">
                            NIP
                        </label>
                        <div class="relative">
                             <input type="text" name="nip" value="{{ old('nip', $user->nip) }}"
                                class="w-full bg-blue-50/30 border border-blue-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-3.5 pl-10 transition-all placeholder-gray-400"
                                placeholder="1982...">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-400">
                                <i class="fas fa-id-card"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Jabatan -->
                    <div class="form-group group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-blue-600 transition-colors">
                            Jabatan
                        </label>
                         <div class="relative">
                             <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}"
                                class="w-full bg-blue-50/30 border border-blue-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-3.5 pl-10 transition-all placeholder-gray-400"
                                placeholder="Kepala Kelurahan">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-400">
                                <i class="fas fa-briefcase"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-blue-600 transition-colors">
                        Alamat Email
                    </label>
                     <div class="relative">
                        <textarea name="email" rows="1"
                            class="w-full bg-blue-50/30 border border-blue-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-3.5 pl-10 transition-all">{{ old('email', $user->email) }}</textarea>
                         <div class="absolute top-3.5 left-3 flex items-center pointer-events-none text-blue-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container 2: Keamanan (Editable) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
             <div class="px-6 py-4 border-b border-gray-100 bg-green-50/50 flex items-center space-x-3">
                 <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-800">Keamanan Akun</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 gap-6">
                <!-- Data Kontak yang boleh diubah -->
                <div class="bg-green-50 text-green-800 p-4 rounded-xl text-sm mb-2 flex items-start">
                    <i class="fas fa-lock mr-2 mt-0.5"></i>
                    <span>Kosongkan kolom password jika Anda tidak ingin mengganti password login Anda saat ini.</span>
                </div>

                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div class="form-group group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-green-600 transition-colors">
                            Password Baru
                        </label>
                        <input type="password" name="password" placeholder="Min. 8 Karakter"
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
             <button type="button" onclick="window.history.back()" 
                    class="px-6 py-3 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl font-bold transition-colors text-sm">
                Batal
            </button>
             <button type="submit" 
                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 flex items-center text-sm">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
