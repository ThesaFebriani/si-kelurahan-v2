@extends('components.layout')

@section('title', 'Tambah Jenis Surat')
@section('page-title', 'Tambah Jenis Surat')
@section('page-description', 'Buat jenis layanan surat baru.')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tambah Jenis Surat</h2>
            <p class="text-slate-500 text-sm mt-1">Buat jenis layanan surat baru.</p>
        </div>
        <a href="{{ route('admin.jenis-surat.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
        <form action="{{ route('admin.jenis-surat.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Informasi Utama -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Informasi Utama
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Surat (Unique) <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_surat" value="{{ old('kode_surat') }}" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Contoh: surat_keterangan_usaha">
                            <p class="text-[10px] text-slate-500 mt-0.5">Gunakan huruf kecil dan underscore, tanpa spasi.</p>
                            @error('kode_surat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Surat <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Contoh: Surat Keterangan Usaha">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi</label>
                            <textarea name="description" rows="3"
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Deskripsi singkat tentang kegunaan surat ini...">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Pengaturan & Validasi -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                        <i class="fas fa-cogs"></i> Pengaturan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bidang (Tujuan Kasi) <span class="text-red-500">*</span></label>
                            <select name="bidang" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" disabled selected>-- Pilih Bidang / Kasi --</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->code }}" {{ old('bidang') == $bidang->code ? 'selected' : '' }}>
                                        {{ $bidang->name }} (Kode: {{ $bidang->code }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-500 mt-0.5">Surat ini akan masuk ke dashboard Kasi yang dipilih.</p>
                            @error('bidang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" checked
                                    class="h-5 w-5 text-blue-600 border-2 border-slate-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-bold text-slate-700">Aktifkan Surat Ini?</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white/95 backdrop-blur-sm p-4 -mx-6 -mb-6 rounded-b-lg shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm shadow-md hover:shadow-lg transition-all">
                    Simpan Jenis Surat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
