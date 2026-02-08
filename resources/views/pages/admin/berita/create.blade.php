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

        <!-- Gambar Berita (Multiple) -->
        <div x-data="imageUploader()">
            <label class="block text-sm font-bold text-slate-700 mb-2">Galeri Foto Berita</label>
            
            <!-- Upload Box -->
            <div @click="$refs.fileInput.click()" class="border-2 border-dashed border-slate-300 rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                <i class="fas fa-images text-3xl text-slate-400 group-hover:text-blue-500 mb-2 transition-colors"></i>
                <p class="text-sm font-bold text-slate-600 group-hover:text-blue-600">Klik untuk pilih banyak foto</p>
                <p class="text-xs text-slate-400 mt-1">Dukung JPG, PNG (Max 5MB per foto)</p>
            </div>
            
            <input type="file" name="gambar[]" multiple x-ref="fileInput" class="hidden" accept="image/*" @change="handleFiles($event)">

            <!-- Preview Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4" x-show="images.length > 0">
                <template x-for="(img, index) in images" :key="index">
                    <div class="relative aspect-video rounded-lg overflow-hidden border border-slate-200 shadow-sm group">
                        <img :src="img.url" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button type="button" @click="removeImage(index)" class="bg-red-500 text-white p-1.5 rounded-full hover:bg-red-600 transition-colors">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                        <span class="absolute bottom-1 left-1 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded" x-text="(img.size/1024/1024).toFixed(2) + ' MB'"></span>
                    </div>
                </template>
            </div>
            
            <!-- Validation Message -->
            <p x-show="errorMessage" x-text="errorMessage" class="text-red-500 text-sm mt-2 font-bold"></p>
        </div>

        @push('scripts')
        <script>
            function imageUploader() {
                return {
                    images: [],
                    errorMessage: '',
                    handleFiles(event) {
                        const files = event.target.files;
                        this.images = []; 
                        this.errorMessage = '';

                        // Loop through all selected files
                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];
                            
                            // Validate Size (5MB = 5 * 1024 * 1024 bytes)
                            if (file.size > 5 * 1024 * 1024) {
                                this.errorMessage = `File "${file.name}" terlalu besar! Maksimal 5MB.`;
                                alert(this.errorMessage);
                                // Reset input
                                this.$refs.fileInput.value = '';
                                this.images = [];
                                return;
                            }

                            // Create preview URL
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.images.push({
                                    url: e.target.result,
                                    size: file.size,
                                    file: file
                                });
                            };
                            reader.readAsDataURL(file);
                        }
                    },
                    removeImage(index) {
                        // Note: We cannot remove single file from input[type=file] programmatically due to security.
                        // So for simple implementation, we just clear the preview.
                        // Ideally we use DataTransfer/Ajax upload, but standard form submit logic simplest is to clear all if user wants to remove one.
                        // Or just explain this limitation. 
                        // For now, let's just allow clearing logical preview but warn user.
                        
                        // FIX: Since simple file input replacement is hard, we'll prompt "Reset photos?" or just clear all
                         if(confirm('Karena keterbatasan browser, menghapus foto akan mereset semua pilihan. Lanjutkan?')) {
                             this.images = [];
                             this.$refs.fileInput.value = '';
                         }
                    }
                }
            }
        </script>
        @endpush

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
