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

            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-800">Edit Identitas</h3>
                    <p class="text-xs text-slate-500">Perubahan akan langsung diterapkan ke semua surat baru.</p>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm shadow-blue-200 transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>

            <div class="p-6 space-y-6">
                <!-- Data Instansi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Instansi (Header 1)</label>
                        <input type="text" name="nama_instansi" value="{{ $settings['nama_instansi'] ?? '' }}" 
                            class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm transition-shadow hover:border-slate-400 placeholder-slate-400"
                            placeholder="Contoh: PEMERINTAH KOTA BENGKULU">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Kecamatan (Header 2)</label>
                        <input type="text" name="nama_kecamatan" value="{{ $settings['nama_kecamatan'] ?? '' }}" 
                            class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm transition-shadow hover:border-slate-400 placeholder-slate-400"
                            placeholder="Contoh: KECAMATAN GADING CEMPAKA">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Kelurahan (Header 3)</label>
                        <input type="text" name="nama_kelurahan" value="{{ $settings['nama_kelurahan'] ?? '' }}" 
                            class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm transition-shadow hover:border-slate-400 placeholder-slate-400"
                            placeholder="Contoh: KELURAHAN PADANG JATI">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat_instansi" rows="2" 
                            class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm transition-shadow hover:border-slate-400 placeholder-slate-400">{{ $settings['alamat_instansi'] ?? '' }}</textarea>
                        <p class="text-xs text-slate-400 mt-1">Sertakan nama jalan, nomor, kota, dan telepon.</p>
                    </div>
                </div>

                <!-- Logo Config -->
                <div class="border-t border-slate-100 pt-6">
                    <h4 class="font-bold text-slate-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-image text-slate-400"></i> Konfigurasi Logo
                    </h4>
                    
                    <div class="flex items-start gap-4">
                        <div class="shrink-0">
                            @if(isset($settings['logo_instansi']) && $settings['logo_instansi'])
                                <img src="{{ asset($settings['logo_instansi']) }}" class="h-16 w-16 object-contain border border-slate-200 rounded-lg p-1">
                            @else
                                <div class="h-16 w-16 bg-slate-100 rounded-lg border border-slate-200"></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-bold text-slate-700 mb-1">Upload Logo Baru</label>
                            <input type="file" name="logo_instansi" accept="image/png, image/jpeg" 
                                class="w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100 transition-all
                            ">
                            <p class="text-xs text-slate-400 mt-1">Format PNG/JPG. Disarankan background transparan.</p>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection
