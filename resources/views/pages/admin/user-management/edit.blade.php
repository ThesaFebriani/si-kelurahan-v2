@extends('components.layout')

@section('title', 'Edit User')
@section('page-title', 'Edit Data Pengguna')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm border border-slate-200 p-6">
    <div class="mb-6 pb-4 border-b border-slate-100 flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-bold text-slate-800">Edit User: {{ $user->name }}</h2>
                @if($user->status == 'active')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> AKTIF
                    </span>
                @elseif($user->status == 'pending')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span> PENDING
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> BLOKIR
                    </span>
                @endif
            </div>
            <p class="text-slate-500 text-sm mt-1">Role saat ini: <strong>{{ ucfirst($user->role->name) }}</strong></p>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            
            <!-- Informasi Akun -->
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-id-card"></i> Informasi Akun
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIK (16 Digit) <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" required minlength="16" maxlength="16"
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                        @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. WhatsApp</label>
                        <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}" placeholder="08..."
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                        @error('telepon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIP (Opsional)</label>
                        <input type="text" name="nip" value="{{ old('nip', $user->nip) }}" 
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400" placeholder="Kosongkan jika bukan pegawai">
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
                        <select name="role_id" required class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Bidang (Kasi) -->
                    <div id="wrapper-bidang" style="{{ $user->role->name == 'kasi' ? '' : 'display: none;' }}">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bidang (Khusus Kasi)</label>
                        <select name="bidang" id="bidang" class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Bidang --</option>
                            @foreach($bidangs as $bidang)
                                <option value="{{ $bidang->code }}" {{ old('bidang', $user->bidang) == $bidang->code ? 'selected' : '' }}>
                                    {{ $bidang->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- RT Selection -->
                    <div id="wrapper-rt" class="w-full">
                         <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Wilayah RT</label>
                         <select name="rt_id" class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih RT (Jika Perlu) --</option>
                            @foreach($rt_list as $rt)
                                <option value="{{ $rt->id }}" {{ old('rt_id', $user->rt_id) == $rt->id ? 'selected' : '' }}>
                                    RT {{ $rt->nomor_rt }} (RW {{ $rt->rw->nomor_rw ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jabatan (Teks Manual)</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" 
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400" placeholder="Contoh: Staff Tata Usaha">
                    </div>
                </div>
            </div>

            <!-- Status & Keamanan -->
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-lock"></i> Status & Keamanan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Akun <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif (Bisa Login)</option>
                            <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>Non-Aktif / Blokir</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                         <div class="p-3 bg-yellow-50 text-yellow-800 text-xs rounded border border-yellow-200 mb-2">
                            <i class="fas fa-info-circle mr-1"></i> Kosongkan jika tidak ingin mengubah password.
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password Baru</label>
                        <input type="password" name="password" class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white/95 backdrop-blur-sm p-4 -mx-6 -mb-6 rounded-b-lg shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium text-sm transition-colors">Batal</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm shadow-md hover:shadow-lg transition-all">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.querySelector('select[name="role_id"]');
        const bidangWrapper = document.getElementById('wrapper-bidang');
        
        function toggleFields() {
            const selectedText = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase();
            
            // Toggle Bidang for Kasi
            if (selectedText.includes('kasi') || selectedText.includes('kepala seksi')) {
                bidangWrapper.style.display = 'block';
            } else {
                bidangWrapper.style.display = 'none';
            }
        }

        roleSelect.addEventListener('change', toggleFields);
        toggleFields(); // Init
    });
</script>
@endpush
