@extends('components.layout')

@section('title', 'Edit Anggota Keluarga')
@section('page-title', 'Edit Data Penduduk')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm border border-slate-200 p-6">
    <div class="mb-6 pb-4 border-b border-slate-100">
        <h2 class="text-xl font-bold text-slate-800">Edit Data: {{ $penduduk->nama_lengkap }}</h2>
        <p class="text-slate-500 text-sm">Anggota dari Keluarga KK: <strong>{{ $penduduk->keluarga->no_kk }}</strong></p>
    </div>

    <form action="{{ route('admin.kependudukan.penduduk.update', $penduduk->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Data Utama -->
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-id-card"></i> Identitas Utama
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIK (16 Digit) <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik', $penduduk->nik) }}" required maxlength="16" minlength="16" 
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                        @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $penduduk->nama_lengkap) }}" required 
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                        @error('nama_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Data Personal -->
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-user"></i> Data Pribadi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jk" required class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="L" {{ old('jk', $penduduk->jk) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jk', $penduduk->jk) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Hubungan <span class="text-red-500">*</span></label>
                        <select name="status_hubungan" required class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="kepala_keluarga" {{ old('status_hubungan', $penduduk->status_hubungan) == 'kepala_keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                            <option value="istri" {{ old('status_hubungan', $penduduk->status_hubungan) == 'istri' ? 'selected' : '' }}>Istri</option>
                            <option value="anak" {{ old('status_hubungan', $penduduk->status_hubungan) == 'anak' ? 'selected' : '' }}>Anak</option>
                            <option value="lainnya" {{ old('status_hubungan', $penduduk->status_hubungan) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('status_hubungan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $penduduk->tempat_lahir) }}" required 
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                        @error('tempat_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $penduduk->tanggal_lahir) }}" required 
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('tanggal_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Agama <span class="text-red-500">*</span></label>
                        <select name="agama" required class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                            <option value="{{ $agama }}" {{ old('agama', $penduduk->agama) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                        @error('agama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pendidikan <span class="text-red-500">*</span></label>
                        <input type="text" name="pendidikan" value="{{ old('pendidikan', $penduduk->pendidikan) }}" required 
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                        @error('pendidikan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pekerjaan <span class="text-red-500">*</span></label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $penduduk->pekerjaan) }}" required 
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                        @error('pekerjaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Status & Dokumen -->
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-file-alt"></i> Status & Dokumen
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Perkawinan <span class="text-red-500">*</span></label>
                        <select name="status_perkawinan" required class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="belum_kawin" {{ old('status_perkawinan', $penduduk->status_perkawinan) == 'belum_kawin' ? 'selected' : '' }}>Belum Kawin</option>
                            <option value="kawin" {{ old('status_perkawinan', $penduduk->status_perkawinan) == 'kawin' ? 'selected' : '' }}>Kawin</option>
                            <option value="cerai_hidup" {{ old('status_perkawinan', $penduduk->status_perkawinan) == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                            <option value="cerai_mati" {{ old('status_perkawinan', $penduduk->status_perkawinan) == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati</option>
                        </select>
                        @error('status_perkawinan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Perkawinan</label>
                        <input type="date" name="tanggal_perkawinan" value="{{ old('tanggal_perkawinan', $penduduk->tanggal_perkawinan) }}" 
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-[10px] text-slate-500 mt-0.5">* Isi jika status Kawin/Cerai</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. Paspor</label>
                        <input type="text" name="no_paspor" value="{{ old('no_paspor', $penduduk->no_paspor) }}" 
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400" placeholder="Nomor Paspor (Jika ada)">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. KITAP</label>
                        <input type="text" name="no_kitap" value="{{ old('no_kitap', $penduduk->no_kitap) }}" 
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400" placeholder="Nomor KITAP (Jika ada)">
                    </div>
                </div>
            </div>

            <!-- Orang Tua -->
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-users"></i> Nama Orang Tua
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Ayah <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $penduduk->nama_ayah) }}" required
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400" placeholder="Nama Ayah Kandung">
                        @error('nama_ayah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Ibu <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $penduduk->nama_ibu) }}" required
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400" placeholder="Nama Ibu Kandung">
                        @error('nama_ibu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3 pt-6 border-t border-slate-100">
            <a href="{{ route('admin.kependudukan.keluarga.show', $penduduk->keluarga_id) }}" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium text-sm">Batal</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
