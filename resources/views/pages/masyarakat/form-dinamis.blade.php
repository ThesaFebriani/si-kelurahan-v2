@extends('components.layout')

@section('title', 'Ajukan ' . $jenisSurat->name . ' - Sistem Kelurahan')
@section('page-title', 'Ajukan ' . $jenisSurat->name)
@section('page-description', 'Isi formulir pengajuan surat keterangan')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    
    <!-- Navigation (Outside) -->
    <div class="mb-6">
        <a href="{{ route('masyarakat.permohonan.create') }}" 
           class="inline-flex items-center text-gray-500 hover:text-blue-600 transition-colors bg-white px-4 py-2 rounded-full shadow-sm text-sm font-medium border border-gray-100 hover:shadow-md">
            <i class="fas fa-arrow-left mr-2"></i> 
            Kembali ke Pilih Surat
        </a>
    </div>

    <!-- Title Card (Google Form Style) -->
    <div class="bg-white rounded-xl shadow-sm border-t-[10px] border-blue-600 p-8 mb-6 relative overflow-hidden">
        
        <!-- Bidang Badge (Floating Top Right) -->
        <div class="absolute top-0 right-0 mt-4 mr-4">
             <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 tracking-wide uppercase">
                {{ $jenisSurat->bidang_display }}
            </span>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-2 mt-2">{{ $jenisSurat->name }}</h1>
        <p class="text-gray-600 text-lg leading-relaxed border-b border-gray-100 pb-6 mb-6">
            Silakan lengkapi data di bawah ini dengan benar untuk mengajukan permohonan.
        </p>

        <div class="flex items-center text-sm font-medium text-gray-500 bg-gray-50 py-2 px-3 rounded-lg w-fit">
            <i class="far fa-clock mr-2 text-blue-500"></i> 
            Estimasi Proses: <span class="text-gray-800 ml-1">{{ $jenisSurat->estimasi_hari }} Hari Kerja</span>
        </div>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm mb-6 animation-shake">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-red-800">Mohon perbaiki kesalahan berikut:</h3>
                <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('masyarakat.permohonan.store.dinamis', $jenisSurat->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 2. Data Isian Surat -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-blue-50/50 flex items-center space-x-3">
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
                            $user = Auth::user();
                            $penduduk = $user->anggotaKeluarga;

                            if ($field->field_name === 'nama_lengkap') $value = $penduduk->nama_lengkap ?? $user->name;
                            elseif ($field->field_name === 'nik') $value = $user->nik;
                            elseif ($field->field_name === 'alamat') $value = $penduduk && $penduduk->keluarga ? $penduduk->keluarga->alamat : ($user->alamat_lengkap ?? $user->alamat);
                            elseif ($field->field_name === 'pekerjaan') $value = $penduduk->pekerjaan ?? $user->pekerjaan;
                            elseif ($field->field_name === 'tempat_lahir') $value = $penduduk->tempat_lahir ?? $user->tempat_lahir;
                            elseif ($field->field_name === 'tanggal_lahir') $value = $penduduk->tanggal_lahir ?? $user->tanggal_lahir;
                            elseif ($field->field_name === 'jenis_kelamin') $value = $penduduk->jk ?? $user->jk; 
                            elseif ($field->field_name === 'agama') $value = $penduduk->agama ?? $user->agama;
                            elseif ($field->field_name === 'status_perkawinan') $value = $penduduk->status_perkawinan ?? $user->status_perkawinan;
                            elseif ($field->field_name === 'kewarganegaraan') $value = $penduduk->kewarganegaraan ?? $user->kewarganegaraan;
                            elseif ($field->field_name === 'pendidikan') $value = $penduduk->pendidikan ?? '';
                        }
                    @endphp

                    @if($field->field_type == 'text' || $field->field_type == 'date')
                        <input type="{{ $field->field_type }}" 
                               name="data[{{ $field->field_name }}]"
                               id="field_{{ $field->id }}"
                               value="{{ $value }}"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-3.5 transition-all"
                               {{ $field->required ? 'required' : '' }}>

                    @elseif($field->field_type == 'number' || $field->field_type == 'currency')
                        <input type="number" 
                               name="data[{{ $field->field_name }}]"
                               id="field_{{ $field->id }}"
                               value="{{ $value }}"
                               min="0"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-3.5 transition-all"
                               {{ $field->required ? 'required' : '' }}>

                    @elseif($field->field_type == 'textarea')
                        <textarea name="data[{{ $field->field_name }}]"
                                  id="field_{{ $field->id }}"
                                  rows="4"
                                  class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-3.5 transition-all"
                                  {{ $field->required ? 'required' : '' }}>{{ $value }}</textarea>

                    @elseif($field->field_type == 'select')
                        <div class="relative">
                            <select name="data[{{ $field->field_name }}]"
                                    id="field_{{ $field->id }}"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block p-3.5 transition-all appearance-none"
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

        <!-- 3. Dokumen Pendukung (Moved to Main Flow) -->
        @if($jenisSurat->requiredDocuments->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-orange-50/50 flex items-center space-x-3">
                 <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                    <i class="fas fa-folder text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-800">Dokumen Pendukung</h3>
            </div>
            
            <div class="p-6 space-y-4">
                @foreach($jenisSurat->requiredDocuments as $doc)
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-sm font-bold text-gray-800">{{ $doc->document_label }}</p>
                             @if($doc->required) <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full">Wajib</span> @else <span class="bg-gray-200 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded-full">Opsional</span> @endif
                        </div>
                        <p class="text-xs text-gray-500">Format: JPG/PDF, Max 2MB</p>
                    </div>
                    
                    <div class="relative w-full md:w-64">
                         <input type="file" 
                               name="documents[{{ $doc->document_name }}]"
                               class="block w-full text-xs text-gray-500
                                      file:mr-2 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-xs file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100 transition-all cursor-pointer"
                               {{ $doc->required ? 'required' : '' }}
                               accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- 4. Informasi Tambahan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-purple-50/50 flex items-center space-x-3">
                 <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-comment-alt text-sm"></i>
                </div>
                 <h3 class="font-bold text-gray-800">Informasi Tambahan</h3>
            </div>
            <div class="p-6">
                <textarea name="keterangan_tambahan"
                          rows="3"
                          class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 block p-3.5 transition-all placeholder-gray-400"
                          placeholder="Tambahkan catatan khusus untuk petugas jika diperlukan...">{{ old('keterangan_tambahan') }}</textarea>
            </div>
        </div>

        <!-- 5. Footer Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
             <div>
                <h4 class="font-bold text-gray-800">Konfirmasi Pengajuan</h4>
                <p class="text-xs text-gray-500 mt-1">Pastikan seluruh data yang Anda masukkan sudah benar.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                 <button type="button" onclick="window.history.back()" 
                        class="px-6 py-2.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl font-bold transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" 
                        class="px-8 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 flex items-center text-sm">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Permohonan
                </button>
            </div>
        </div>

    </form>
</div>
@endsection