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

        <!-- Galeri Foto -->
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Galeri Foto Berita</label>
            
            <!-- Existing Images -->
            @if($beritum->semua_gambar && count($beritum->semua_gambar) > 0)
            <div class="mb-4">
                <p class="text-xs text-slate-500 mb-2 font-bold uppercase tracking-wider">Foto Saat Ini:</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($beritum->semua_gambar as $index => $imgUrl)
                    <div class="relative group rounded-lg overflow-hidden border border-slate-200 shadow-sm aspect-video" x-data="{ markedForDelete: false }">
                        <img src="{{ $imgUrl }}" class="w-full h-full object-cover" :class="{ 'opacity-50 grayscale': markedForDelete }">
                        
                        <!-- Overlay for Delete -->
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                             <!-- Since path is needed for deletion, we need raw path. `semua_gambar` returns full URL. 
                                  We need a way to get raw path. Model accessor was `getSemuaGambarAttribute`.
                                  Let's access raw `gambar` attribute directly from blade if casted properly to array.
                             -->
                             @php 
                                $rawPaths = $beritum->gambar; 
                                $currentPath = is_array($rawPaths) ? ($rawPaths[$index] ?? '') : $rawPaths;
                             @endphp

                            <label class="cursor-pointer bg-red-500 text-white px-3 py-1 rounded-full text-xs hover:bg-red-600 transition shadow-md">
                                <input type="checkbox" name="delete_images[]" value="{{ $currentPath }}" class="hidden" @change="markedForDelete = !markedForDelete">
                                <span x-text="markedForDelete ? 'Batal Hapus' : 'Hapus Foto'">Hapus Foto</span>
                            </label>
                        </div>
                        
                        <!-- Status Badge -->
                        <div x-show="markedForDelete" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg">AKAN DIHAPUS</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-slate-400 mt-2 italic">* Centang "Hapus Foto" dan simpan perubahan untuk menghapus foto permanen.</p>
            </div>
            @endif

            <!-- Upload New Images -->
            <div x-data="imageUploader()">
                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Tambah Foto Baru:</label>
                
                <!-- Upload Box -->
                <div @click="$refs.fileInput.click()" class="border-2 border-dashed border-slate-300 rounded-xl p-6 flex flex-col items-center justify-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all group">
                    <i class="fas fa-plus-circle text-2xl text-slate-400 group-hover:text-blue-500 mb-1 transition-colors"></i>
                    <p class="text-sm font-bold text-slate-600 group-hover:text-blue-600">Tambah Foto Lain</p>
                    <p class="text-xs text-slate-400">JPG/PNG Max 5MB</p>
                </div>
                
                <input type="file" name="gambar[]" multiple x-ref="fileInput" class="hidden" accept="image/*" @change="handleFiles($event)">

                <!-- Preview New Images -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4" x-show="images.length > 0">
                    <template x-for="(img, index) in images" :key="index">
                        <div class="relative aspect-video rounded-lg overflow-hidden border border-slate-200 shadow-sm border-blue-400 ring-2 ring-blue-100">
                            <img :src="img.url" class="w-full h-full object-cover">
                            <button type="button" @click="removeImage(index)" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 shadow-sm">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                            <span class="absolute bottom-1 left-1 bg-blue-600 text-white text-[10px] px-1.5 py-0.5 rounded font-bold">BARU</span>
                        </div>
                    </template>
                </div>

                <!-- Validation Message -->
                <p x-show="errorMessage" x-text="errorMessage" class="text-red-500 text-sm mt-2 font-bold"></p>
            </div>
        </div>

        @push('scripts')
        <script>
            function imageUploader() {
                return {
                    images: [],
                    errorMessage: '',
                    handleFiles(event) {
                        const files = event.target.files;
                        // Append to existing check? No, standard behaviour for file input is replace selection. 
                        // So we just show preview of current selection.
                        this.images = []; 
                        this.errorMessage = '';

                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];
                            if (file.size > 5 * 1024 * 1024) {
                                this.errorMessage = `File "${file.name}" terlalu besar! Maksimal 5MB.`;
                                alert(this.errorMessage);
                                this.$refs.fileInput.value = '';
                                this.images = [];
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.images.push({ url: e.target.result });
                            };
                            reader.readAsDataURL(file);
                        }
                    },
                    removeImage(index) {
                         if(confirm('Reset pilihan foto baru?')) {
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
