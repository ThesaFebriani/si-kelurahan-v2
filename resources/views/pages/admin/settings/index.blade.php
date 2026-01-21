@extends('components.layout')

@section('title', 'Pengaturan Instansi')
@section('page-title', 'Pengaturan Profil Instansi')
@section('page-description', 'Sesuaikan identitas kelurahan, alamat, dan logo yang akan tampil di Kop Surat.')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Preview Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Preview Kop Surat</h3>
                <p class="text-xs text-slate-500">Tampilan estimasi pada surat cetak.</p>
            </div>
            <div class="p-6 flex flex-col items-center text-center space-y-2">
                
                @if(isset($settings['logo_instansi']) && $settings['logo_instansi'])
                    <img src="{{ asset($settings['logo_instansi']) }}?t={{ time() }}" alt="Logo Instansi" class="h-20 w-auto mb-2">
                @else
                    <div class="h-20 w-20 bg-slate-100 rounded-lg flex items-center justify-center mb-2">
                        <i class="fas fa-image text-slate-300 text-3xl"></i>
                    </div>
                @endif
                
                <div class="text-xs font-semibold tracking-wider uppercase text-slate-600">
                    {{ $settings['nama_instansi'] ?? 'PEMERINTAH BENGKULU' }}
                </div>
                <div class="text-sm font-bold uppercase text-slate-800">
                    {{ $settings['nama_kecamatan'] ?? 'KECAMATAN ...' }}
                </div>
                <div class="text-base font-extrabold uppercase text-slate-900">
                    {{ $settings['nama_kelurahan'] ?? 'KELURAHAN ...' }}
                </div>
                <div class="text-xs text-slate-500 italic mt-2">
                    {{ $settings['alamat_instansi'] ?? 'Alamat instansi...' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="lg:col-span-2">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" 
            class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="space-y-6 p-6">
                <!-- Group: Identitas Surat -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                        <i class="fas fa-building"></i> Identitas Kop Surat
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Instansi (Header 1) <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_instansi" value="{{ $settings['nama_instansi'] ?? '' }}" 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Contoh: PEMERINTAH KOTA BENGKULU">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kecamatan (Header 2) <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kecamatan" value="{{ $settings['nama_kecamatan'] ?? '' }}" 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Contoh: KECAMATAN GADING CEMPAKA">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kelurahan (Header 3) <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kelurahan" value="{{ $settings['nama_kelurahan'] ?? '' }}" 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Contoh: KELURAHAN PADANG JATI">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="alamat_instansi" rows="2" 
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400">{{ $settings['alamat_instansi'] ?? '' }}</textarea>
                            <p class="text-[10px] text-slate-500 mt-1">* Sertakan nama jalan, nomor, kota, dan telepon.</p>
                        </div>
                    </div>
                </div>

                <!-- Group: Konfigurasi Logo -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                        <i class="fas fa-image"></i> Konfigurasi Logo
                    </h3>
                    
                    <div class="flex items-start gap-5">
                        <div class="shrink-0">
                             <p class="text-[10px] font-bold text-slate-500 uppercase mb-2 text-center">Logo Saat Ini</p>
                            @if(isset($settings['logo_instansi']) && $settings['logo_instansi'])
                                <img src="{{ asset($settings['logo_instansi']) }}" class="h-24 w-24 object-contain border-2 border-white shadow-md rounded-lg bg-white p-2">
                            @else
                                <div class="h-24 w-24 bg-slate-200 rounded-lg border-2 border-slate-300 border-dashed flex items-center justify-center">
                                    <span class="text-xs text-slate-400 font-medium">No Logo</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Upload Logo Baru</label>
                            <input type="file" name="logo_instansi" accept="image/png, image/jpeg" 
                                class="w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-xs file:font-bold file:uppercase
                                file:bg-blue-600 file:text-white
                                hover:file:bg-blue-700 transition-all cursor-pointer
                            ">
                            <p class="text-[10px] text-slate-500 mt-2 leading-relaxed">
                                Format: <span class="font-bold text-slate-700">PNG / JPG</span>. <br>
                                Disarankan menggunakan gambar dengan latar belakang transparan (PNG) untuk hasil terbaik pada kop surat.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Action Bar -->
            <div class="mt-2 flex justify-end p-4 border-t border-slate-100 sticky bottom-0 bg-white/95 backdrop-blur-sm -mx-0 -mb-0 rounded-b-xl z-20 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
