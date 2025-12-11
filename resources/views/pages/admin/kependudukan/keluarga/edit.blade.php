@extends('components.layout')

@section('title', 'Edit Kartu Keluarga')
@section('page-title', 'Edit Kartu Keluarga')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm border border-slate-200 p-6">
    <form action="{{ route('admin.kependudukan.keluarga.update', $keluarga->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Section 1: Data Utama -->
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-id-card"></i> Identitas Kartu Keluarga
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- No KK -->
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Kartu Keluarga (16 Digit) <span class="text-red-500">*</span></label>
                        <input type="text" name="no_kk" value="{{ old('no_kk', $keluarga->no_kk) }}" required minlength="16" maxlength="16"
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400" placeholder="Masukkan 16 digit Nomor KK">
                        @error('no_kk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kepala Keluarga -->
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kepala Keluarga <span class="text-red-500">*</span></label>
                        <input type="text" name="kepala_keluarga" value="{{ old('kepala_keluarga', $keluarga->kepala_keluarga) }}" required
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400" placeholder="Sesuai yang tertera di KK">
                        @error('kepala_keluarga') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Alamat -->
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt"></i> Alamat & Lokasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Wilayah -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Wilayah RT/RW <span class="text-red-500">*</span></label>
                        <select name="rt_id" required class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Wilayah...</option>
                            @foreach($rts as $rt)
                                <option value="{{ $rt->id }}" {{ old('rt_id', $keluarga->rt_id) == $rt->id ? 'selected' : '' }}>
                                    RT {{ $rt->nomor_rt }} / RW {{ $rt->rw ? $rt->rw->nomor_rw : '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('rt_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kode Pos -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Pos <span class="text-red-500">*</span></label>
                        <input type="text" name="kodepos" value="{{ old('kodepos', $keluarga->kodepos) }}" required maxlength="5"
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                        @error('kodepos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Detail Alamat Wilayah -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Desa/Kelurahan <span class="text-red-500">*</span></label>
                        <input type="text" name="desa_kelurahan" value="{{ old('desa_kelurahan', $keluarga->desa_kelurahan) }}" required
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kecamatan <span class="text-red-500">*</span></label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan', $keluarga->kecamatan) }}" required
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                        <input type="text" name="kabupaten_kota" value="{{ old('kabupaten_kota', $keluarga->kabupaten_kota) }}" required
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Provinsi <span class="text-red-500">*</span></label>
                        <input type="text" name="provinsi" value="{{ old('provinsi', $keluarga->provinsi) }}" required
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">
                    </div>

                    <!-- Alamat -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Lengkap (Jalan/Gang/No.Rumah) <span class="text-red-500">*</span></label>
                        <textarea name="alamat_lengkap" rows="3" required placeholder="Contoh: Jl. Merpati No. 15, Gang Ceria"
                            class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('alamat_lengkap', $keluarga->alamat_lengkap) }}</textarea>
                        @error('alamat_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
            <a href="{{ route('admin.kependudukan.keluarga.show', $keluarga->id) }}" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium text-sm">Batal</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
