@extends('components.layout')

@section('title', 'Buat Berita Baru')
@section('page-title', 'Tulis Berita Baru')
@section('page-description', 'Bagikan informasi terbaru kepada masyarakat.')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center text-slate-500 hover:text-slate-800 font-bold text-sm transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Berita
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf

        <!-- Judul -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Berita</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="Contoh: Jadwal Posyandu Bulan Februari">
            @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Gambar Utama -->
        <div x-data="{ imagePreview: null }">
            <label class="block text-sm font-bold text-slate-700 mb-2">Gambar Utama (Opsional)</label>
            
            <!-- Preview Area -->
            <template x-if="imagePreview">
                <div class="mb-4 relative w-full h-48 sm:h-64 rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                    <img :src="imagePreview" class="w-full h-full object-cover">
                    <button type="button" @click="imagePreview = null; $refs.imageInput.value = ''" 
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 shadow-md">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </template>

            <div x-show="!imagePreview" 
                 @click="$refs.imageInput.click()"
                 class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 transition-all cursor-pointer group">
                <div class="space-y-1 text-center">
                    <i class="fas fa-cloud-upload-alt text-slate-400 text-4xl mb-2 group-hover:text-blue-500 transition-colors"></i>
                    <div class="flex text-sm text-slate-600">
                        <span class="relative cursor-pointer bg-white rounded-md font-bold text-blue-600 hover:text-blue-500 focus-within:outline-none">
                            Klik untuk Upload Gambar
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">PNG, JPG up to 2MB</p>
                </div>
            </div>
            <input type="file" name="gambar" x-ref="imageInput" class="hidden" accept="image/*" 
                   @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result; }; reader.readAsDataURL(file); }">
            @error('gambar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Konten (Isi Berita) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Berita</label>
            <textarea name="konten" rows="10" required
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-4 font-normal"
                placeholder="Tulis detail informasi di sini...">{{ old('konten') }}</textarea>
            <p class="mt-2 text-xs text-gray-500">Tip: Gunakan enter untuk paragraf baru.</p>
            @error('konten') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Status & Tombol -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-100">
            <div class="flex items-center gap-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="status" value="published" class="text-blue-600 focus:ring-blue-500" checked>
                    <span class="ml-2 text-sm text-gray-700">Langsung Terbit</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="status" value="draft" class="text-gray-600 focus:ring-gray-500">
                    <span class="ml-2 text-sm text-gray-700">Simpan Draft</span>
                </label>
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <i class="fas fa-paper-plane mr-2 mt-0.5"></i> Simpan & Terbitkan
            </button>
        </div>
    </form>
</div>
@endsection
