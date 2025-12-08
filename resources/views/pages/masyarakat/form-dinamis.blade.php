@extends('components.layout')

@section('title', 'Ajukan ' . $jenisSurat->name . ' - Sistem Kelurahan')
@section('page-title', 'Ajukan ' . $jenisSurat->name)
@section('page-description', 'Isi formulir pengajuan surat keterangan')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    
    <!-- Header Banner -->
    <div class="relative bg-gradient-to-r from-blue-700 to-indigo-800 rounded-2xl p-8 shadow-lg overflow-hidden text-white">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between">
            <div>
                 <div class="flex items-center space-x-3 mb-3">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold uppercase tracking-wider border border-white/20">
                        {{ $jenisSurat->bidang_display }}
                    </span>
                    <span class="flex items-center text-sm font-medium opacity-90">
                        <i class="far fa-clock mr-1.5"></i> Estimasi {{ $jenisSurat->estimasi_hari }} Hari
                    </span>
                </div>
                <h2 class="text-3xl font-bold mb-2">{{ $jenisSurat->name }}</h2>
                <p class="text-blue-100 max-w-2xl">Silakan lengkapi formulir di bawah ini dengan data yang benar dan valid.</p>
            </div>
             <a href="{{ route('masyarakat.permohonan.create') }}" 
               class="mt-6 md:mt-0 inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg transition-colors text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Ganti Jenis Surat
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start space-x-3">
        <i class="fas fa-exclamation-circle text-red-600 mt-1"></i>
        <div>
            <h4 class="font-bold text-red-800 text-sm">Terjadi Kesalahan Validasi</h4>
            <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('masyarakat.permohonan.store.dinamis', $jenisSurat->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Form Column -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Section: Data Diri -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fas fa-user-edit text-sm"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Data Isian Surat</h3>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 gap-6">
                        @foreach($jenisSurat->templateFields as $field)
                        <div class="form-group group">
                             <label for="field_{{ $field->id }}" class="block text-sm font-semibold text-gray-700 mb-2 group-focus-within:text-blue-600 transition-colors">
                                {{ $field->field_label }}
                                @if($field->required) <span class="text-red-500">*</span> @endif
                            </label>

                            @php
                                $value = old('data.'.$field->field_name);
                                if (empty($value)) {
                                    if ($field->field_name === 'nama_lengkap') $value = Auth::user()->name;
                                    elseif ($field->field_name === 'nik') $value = Auth::user()->nik;
                                    elseif ($field->field_name === 'alamat') $value = Auth::user()->alamat;
                                    elseif ($field->field_name === 'pekerjaan') $value = Auth::user()->pekerjaan;
                                    elseif ($field->field_name === 'tempat_lahir') $value = Auth::user()->tempat_lahir;
                                    elseif ($field->field_name === 'tanggal_lahir') $value = Auth::user()->tanggal_lahir;
                                    elseif ($field->field_name === 'jenis_kelamin') $value = Auth::user()->jk;
                                    elseif ($field->field_name === 'agama') $value = Auth::user()->agama;
                                    elseif ($field->field_name === 'status_perkawinan') $value = Auth::user()->status_perkawinan;
                                    elseif ($field->field_name === 'kewarganegaraan') $value = Auth::user()->kewarganegaraan;
                                }
                            @endphp

                            @if($field->field_type == 'text' || $field->field_type == 'number' || $field->field_type == 'date')
                                <div class="relative">
                                    <input type="{{ $field->field_type }}" 
                                           name="data[{{ $field->field_name }}]"
                                           id="field_{{ $field->id }}"
                                           value="{{ $value }}"
                                           class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-3 transition-all"
                                           {{ $field->required ? 'required' : '' }}>
                                </div>

                            @elseif($field->field_type == 'textarea')
                                <textarea name="data[{{ $field->field_name }}]"
                                          id="field_{{ $field->id }}"
                                          rows="4"
                                          class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-3 transition-all"
                                          {{ $field->required ? 'required' : '' }}>{{ $value }}</textarea>

                            @elseif($field->field_type == 'select')
                                <div class="relative">
                                    <select name="data[{{ $field->field_name }}]"
                                            id="field_{{ $field->id }}"
                                            class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-3 transition-all appearance-none"
                                            {{ $field->required ? 'required' : '' }}>
                                        <option value="">-- Pilih {{ $field->field_label }} --</option>
                                        @foreach($field->options_array as $option)
                                        <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            
                             @elseif($field->field_type == 'file')
                                <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition-colors">
                                    <input type="file" 
                                           name="data[{{ $field->field_name }}]"
                                           id="field_{{ $field->id }}"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                           accept=".jpg,.jpeg,.png,.pdf"
                                           {{ $field->required ? 'required' : '' }}>
                                    
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-sm font-medium text-gray-700">Klik untuk unggah {{ $field->field_label }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF (Max 2MB)</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                 <!-- Section: Keterangan Tambahan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center space-x-3">
                         <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                            <i class="fas fa-comment-alt text-sm"></i>
                        </div>
                         <h3 class="font-bold text-gray-800">Informasi Tambahan</h3>
                    </div>
                    <div class="p-6">
                        <textarea name="keterangan_tambahan"
                                  rows="3"
                                  class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 block p-3 transition-all placeholder-gray-400"
                                  placeholder="Tambahkan catatan khusus untuk petugas jika diperlukan...">{{ old('keterangan_tambahan') }}</textarea>
                    </div>
                </div>

            </div>

            <!-- Sidebar Column: Dokumen & Persyaratan -->
            <div class="space-y-8">
                 <!-- Dokumen Wajib -->
                @if($jenisSurat->requiredDocuments->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-orange-50/50 flex items-center space-x-3">
                         <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                            <i class="fas fa-folder text-sm"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Dokumen Pendukung</h3>
                    </div>
                    
                    <div class="p-4 space-y-4">
                        @foreach($jenisSurat->requiredDocuments as $doc)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $doc->document_label }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        @if($doc->required) <span class="text-red-500 font-medium">Wajib</span> @else Opsional @endif
                                    </p>
                                </div>
                                <i class="fas fa-file-pdf text-gray-300 text-xl"></i>
                            </div>
                            
                            <input type="file" 
                                   name="documents[{{ $doc->document_name }}]"
                                   class="block w-full text-xs text-gray-500
                                          file:mr-2 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-xs file:font-semibold
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100 transition-all"
                                   {{ $doc->required ? 'required' : '' }}
                                   accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Tombol Submit -->
                 <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    <h4 class="font-bold text-gray-800 mb-2">Konfirmasi Pengajuan</h4>
                    <p class="text-xs text-gray-500 mb-4">Pastikan seluruh data yang Anda masukkan sudah benar sebelum mengirim permohonan.</p>
                    
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Permohonan
                    </button>
                    
                    <button type="button" onclick="window.history.back()" 
                            class="w-full mt-3 py-3 px-4 bg-gray-50 text-gray-600 hover:bg-gray-100 rounded-xl font-semibold transition-colors flex items-center justify-center border border-gray-200">
                        Batal
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection