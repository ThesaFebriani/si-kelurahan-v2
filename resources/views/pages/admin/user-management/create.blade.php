@extends('components.layout')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User')
@section('page-description', 'Isi formulir berikut untuk menambahkan pengguna baru.')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tambah Pengguna Baru</h2>
            <p class="text-slate-500 text-sm mt-1">Isi formulir berikut untuk menambahkan pengguna baru.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <!-- Informasi Akun -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-slate-800 border-b border-slate-100 pb-2 mb-4">Informasi Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="Contoh: Budi Santoso">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">NIP (Opsional)</label>
                        <input type="text" name="nip" value="{{ old('nip') }}"
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="Kusus untuk PNS/Lurah/Kasi">
                        @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" required minlength="16" maxlength="16"
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="16 digit NIK">
                        @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="nama@email.com">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap (Wajib untuk Masyarakat)</label>
                        <textarea name="alamat" rows="2"
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="Jalan, RT/RW, Kelurahan...">{{ old('alamat') }}</textarea>
                        @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Peran & Jabatan -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-slate-800 border-b border-slate-100 pb-2 mb-4">Peran & Wilayah</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Role (Peran)</label>
                        <select name="role_id" required
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            <option value="">Pilih Role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Bidang (Khusus Kasi) -->
                    <div id="wrapper-bidang" style="display: none;">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Bidang (Khusus Kasi)</label>
                        <select name="bidang" id="bidang"
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            <option value="">-- Pilih Bidang --</option>
                            @foreach($bidangs as $bidang)
                                <option value="{{ $bidang->code }}" {{ old('bidang') == $bidang->code ? 'selected' : '' }}>
                                    {{ $bidang->name }}
                                </option>
                            @endforeach
                        </select>
                         <p class="text-xs text-slate-400 mt-1">Wajib diisi jika Role adalah Kepala Seksi (Kasi)</p>
                        @error('bidang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">RT (Khusus RT/Warga)</label>
                        <select name="rt_id"
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            <option value="">Tidak Ada / Lintas Wilayah</option>
                            @foreach($rt_list as $rt)
                                <option value="{{ $rt->id }}" {{ old('rt_id') == $rt->id ? 'selected' : '' }}>
                                    RT {{ $rt->nomor_rt }} (RW {{ $rt->rw ? $rt->rw->nomor_rw : '-' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-400 mt-1">*Hanya wajib diisi jika Role adalah RT atau Masyarakat</p>
                        @error('rt_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jabatan (Opsional)</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                            placeholder="Contoh: Ketua RT, Staff Administrasi">
                        @error('jabatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Keamanan -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-slate-800 border-b border-slate-100 pb-2 mb-4">Keamanan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end pt-6 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-md hover:shadow-lg">
                    Simpan User Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.querySelector('select[name="role_id"]');
        const bidangWrapper = document.getElementById('wrapper-bidang');
        
        function toggleBidang() {
            // Asumsi ID Role Kasi bisa berbeda, tapi textnya 'Kasi' atau kita cek backend ID
            // Tapi karena di frontend kita loop, kita cek text content atau pass variable role kasi ID via PHP
            // Cara termudah: Check selected option text
            const selectedText = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase();
            if (selectedText.includes('kasi') || selectedText.includes('kepala seksi')) {
                bidangWrapper.style.display = 'block';
            } else {
                bidangWrapper.style.display = 'none';
            }
        }

        roleSelect.addEventListener('change', toggleBidang);
        toggleBidang(); // Run on load (for old input)
    });
</script>
@endpush
