@extends('components.layout')

@section('title', 'Edit Berita')
@section('page-title', 'Edit Berita')
@section('page-description', 'Perbarui informasi berita yang sudah ada.')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center text-slate-500 hover:text-slate-800 font-bold text-sm transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Berita
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <form action="{{ route('admin.berita.update', $beritum->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Judul -->
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Judul Berita</label>
            <input type="text" name="judul" value="{{ old('judul', $beritum->judul) }}" required
                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm font-medium"
                placeholder="Contoh: Jadwal Posyandu Bulan Februari">
            @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Gambar Utama -->
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Gambar Utama</label>
            @if($beritum->gambar)
            <div class="mb-3">
                <p class="text-xs text-slate-500 mb-1">Gambar Saat Ini:</p>
                <img src="{{ $beritum->gambar_url }}" alt="Preview" class="h-32 rounded-lg border border-slate-200 object-cover">
            </div>
            @endif
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 transition-colors">
                <div class="space-y-1 text-center">
                    <i class="fas fa-image text-slate-400 text-3xl mb-2"></i>
                    <div class="flex text-sm text-slate-600">
                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-bold text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                            <span>Ganti Gambar</span>
                            <input id="file-upload" name="gambar" type="file" class="sr-only" accept="image/*">
                        </label>
                        <p class="pl-1 text-slate-500 font-medium">atau tarik dan lepas</p>
                    </div>
                    <p class="text-xs text-slate-500">PNG, JPG up to 2MB (Kosongkan jika tidak ingin mengganti)</p>
                </div>
            </div>
        </div>

        <!-- Konten (Isi Berita) -->
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Isi Berita</label>
            <textarea name="konten" rows="10" required
                class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-4 font-medium"
                placeholder="Tulis detail informasi di sini...">{{ old('konten', $beritum->konten) }}</textarea>
            @error('konten') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Status & Tombol -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-6 border-t border-slate-100 gap-4">
            <div class="flex items-center gap-6">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="status" value="published" class="text-blue-600 focus:ring-blue-500" {{ $beritum->status == 'published' ? 'checked' : '' }}>
                    <span class="ml-2 text-sm font-bold text-slate-700">Terbitkan</span>
                </label>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="status" value="draft" class="text-slate-400 focus:ring-slate-500" {{ $beritum->status == 'draft' ? 'checked' : '' }}>
                    <span class="ml-2 text-sm font-bold text-slate-700">Simpan Draft</span>
                </label>
            </div>
            <button type="submit" class="inline-flex justify-center py-2.5 px-8 border border-transparent shadow-lg shadow-blue-500/30 text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <i class="fas fa-save mr-2 mt-0.5"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
