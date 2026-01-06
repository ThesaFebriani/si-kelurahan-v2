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
                <!-- Kode Surat -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kode Surat (Unique)</label>
                    <input type="text" name="kode_surat" value="{{ old('kode_surat') }}" required
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                        placeholder="Contoh: surat_keterangan_usaha">
                    <p class="text-xs text-slate-400 mt-1">Gunakan huruf kecil dan underscore, tanpa spasi.</p>
                    @error('kode_surat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nama Surat -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Surat</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                        placeholder="Contoh: Surat Keterangan Usaha">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Bidang (Kasi) -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Bidang (Tujuan Kasi)</label>
                    <select name="bidang" required
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        <option value="" disabled selected>-- Pilih Bidang / Kasi --</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->code }}" {{ old('bidang') == $bidang->code ? 'selected' : '' }}>
                                {{ $bidang->name }} (Kode: {{ $bidang->code }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1">Surat ini akan masuk ke dashboard Kasi yang dipilih setelah disetujui RT.</p>
                    @error('bidang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                        placeholder="Deskripsi singkat tentang kegunaan surat ini...">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status Aktif -->
                <div>
                    <label class="flex items-center space-x-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" checked
                            class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-slate-700 font-medium">Aktifkan Surat Ini?</span>
                    </label>
                    <p class="text-xs text-slate-400 mt-1 ml-8">Jika tidak dicentang, warga tidak akan bisa memilih jenis surat ini.</p>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end pt-6 border-t border-slate-100 mt-6">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-md hover:shadow-lg">
                    Simpan Jenis Surat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
