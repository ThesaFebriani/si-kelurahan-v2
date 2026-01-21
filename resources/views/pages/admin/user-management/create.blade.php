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

            <div class="space-y-6">
                
                <!-- Informasi Akun -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                        <i class="fas fa-id-card"></i> Informasi Akun
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Contoh: Budi Santoso">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="nama@email.com">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIK (16 Digit) <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" value="{{ old('nik') }}" required minlength="16" maxlength="16"
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="16 digit NIK">
                            @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIP (Opsional)</label>
                            <input type="text" name="nip" value="{{ old('nip') }}"
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Kosongkan jika bukan pegawai">
                        </div>
                        <div>
                             <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Lengkap (Wajib untuk Masyarakat)</label>
                            <input type="text" name="alamat" value="{{ old('alamat') }}"
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Jalan, RT/RW, Kelurahan...">
                             @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Peran & Jabatan -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                        <i class="fas fa-briefcase"></i> Peran & Wilayah
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Role Akun <span class="text-red-500">*</span></label>
                            <select name="role_id" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Role --</option>
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
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bidang (Khusus Kasi)</label>
                            <select name="bidang" id="bidang"
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Bidang --</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->code }}" {{ old('bidang') == $bidang->code ? 'selected' : '' }}>
                                        {{ $bidang->name }}
                                    </option>
                                @endforeach
                            </select>
                             <p class="text-[10px] text-slate-500 mt-0.5">Wajib diisi jika Role adalah Kepala Seksi (Kasi)</p>
                            @error('bidang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Wilayah RT (Opsional)</label>
                            <select name="rt_id"
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih RT (Jika Perlu) --</option>
                                @foreach($rt_list as $rt)
                                    <option value="{{ $rt->id }}" {{ old('rt_id') == $rt->id ? 'selected' : '' }}>
                                        RT {{ $rt->nomor_rt }} (RW {{ $rt->rw ? $rt->rw->nomor_rw : '-' }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-500 mt-0.5">Wajib untuk Role RT atau Masyarakat.</p>
                            @error('rt_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jabatan (Teks Manual)</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Contoh: Ketua RT, Staff Tata Usaha">
                            @error('jabatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Keamanan -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                        <i class="fas fa-lock"></i> Keamanan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Submit -->
            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white/95 backdrop-blur-sm p-4 -mx-6 -mb-6 rounded-b-lg shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium text-sm transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm shadow-md hover:shadow-lg transition-all">Simpan User Baru</button>
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
