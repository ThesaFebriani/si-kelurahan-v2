@extends('components.layout')

@section('title', 'Ajukan ' . $jenisSurat->name . ' - Sistem Kelurahan')
@section('page-title', 'Ajukan Layanan')
@section('page-description', 'Formulir pengajuan ' . $jenisSurat->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Back Button -->
    <div>
        <a href="{{ route('masyarakat.permohonan.create') }}" class="text-gray-500 hover:text-blue-600 text-sm font-medium flex items-center transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Layanan
        </a>
    </div>

    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 uppercase tracking-wide border border-blue-100">
                        {{ $jenisSurat->bidang_display }}
                    </span>
                    <span class="flex items-center text-[10px] text-slate-500 font-bold bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                        <i class="far fa-clock mr-1"></i> {{ $jenisSurat->estimasi_hari }} Hari Kerja
                    </span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $jenisSurat->name }}</h2>
                <p class="text-slate-500 text-sm mt-1">Silakan lengkapi formulir di bawah ini dengan data yang valid.</p>
            </div>
        </div>
    </div>

    <!-- Warning Alert (Q13 Reliability) -->
    @if(session('warning'))
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-yellow-800">Perhatian: Di Luar Jam Kerja</h3>
                <div class="mt-1 text-sm text-yellow-700">
                    {!! session('warning') !!}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Alert -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-red-800">Terdapat Kesalahan Input</h3>
                <div class="mt-1 text-sm text-red-700">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('masyarakat.permohonan.store.dinamis', $jenisSurat->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- Data Isian Form -->
        <div class="space-y-6">
        <div class="space-y-6">
            
            <!-- 1 KK 1 Akun: PILIH ANGGOTA KELUARGA -->
            @if(isset($keluargaMembers) && $keluargaMembers->count() > 0)
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -mr-16 -mt-16"></div>
                
                <h3 class="text-sm font-bold text-blue-800 mb-4 flex items-center gap-2 relative z-10">
                    <i class="fas fa-users"></i> SURAT INI UNTUK SIAPA?
                </h3>
                
                <div class="relative z-10">
                    <select id="pilih_anggota_keluarga" class="w-full h-11 border-2 border-blue-200 rounded-lg shadow-sm focus:ring-0 focus:border-blue-500 text-sm px-4 font-bold text-blue-900 bg-white">
                        <option value="">-- Pilih Anggota Keluarga --</option>
                        @foreach($keluargaMembers as $member)
                            <option value="{{ $member->nik }}" 
                                data-nama="{{ $member->nama_lengkap }}"
                                data-nik="{{ $member->nik }}"
                                data-pekerjaan="{{ $member->pekerjaan ?? '-' }}"
                                data-tempatlahir="{{ $member->tempat_lahir ?? '-' }}"
                                data-tanggallahir="{{ $member->tanggal_lahir ?? '-' }}"
                                data-agama="{{ $member->agama ?? '-' }}"
                                data-jk="{{ $member->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}"
                                data-statuskawin="{{ $member->status_perkawinan ?? '-' }}"
                                data-kewarganegaraan="{{ $member->kewarganegaraan ?? 'WNI' }}"
                                {{ (Auth::user()->nik == $member->nik) ? 'selected' : '' }}
                            >
                                {{ $member->nama_lengkap }} ({{ ucfirst($member->status_hubungan ?? 'Sendiri') }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-blue-600 mt-2 font-medium">
                        <i class="fas fa-info-circle mr-1"></i> Data formulir otomatis terisi sesuai pilihan di atas.
                    </p>
                </div>
            </div>
            @endif

            <div class="bg-slate-50 p-6 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-6 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-edit"></i> DATA FORMULIR
                </h3>
                
                <div class="grid grid-cols-1 gap-6">
                    @foreach($jenisSurat->templateFields as $field)
                    <div class="form-group">
                         <label for="field_{{ $field->id }}" class="block text-xs font-bold text-slate-700 uppercase mb-2">
                            {{ $field->field_label }}
                            @if($field->required) <span class="text-red-500">*</span> @endif
                        </label>

                        @php
                            $value = old('data.'.$field->field_name);
                            $isAutoFilled = false;
                            
                                // Jika nilai kosong, coba ambil dari database
                                if (empty($value)) {
                                    $user = Auth::user();
                                    $penduduk = $user->anggotaKeluarga;
                                    $dbValue = null;

                                    if ($field->field_name === 'nama_lengkap') {
                                        $dbValue = $penduduk->nama_lengkap ?? $user->name;
                                    } elseif ($field->field_name === 'nik') {
                                        $dbValue = $user->nik;
                                    } elseif ($field->field_name === 'alamat') {
                                        $dbValue = $penduduk && $penduduk->keluarga ? $penduduk->keluarga->alamat : ($user->alamat_lengkap ?? $user->alamat);
                                    } elseif ($field->field_name === 'pekerjaan') {
                                        $dbValue = $penduduk->pekerjaan ?? $user->pekerjaan;
                                    } elseif ($field->field_name === 'tempat_lahir') {
                                        // User table tidak punya tempat_lahir, jadi hanya bisa dari penduduk
                                        $dbValue = $penduduk->tempat_lahir ?? $user->tempat_lahir ?? null;
                                    } elseif ($field->field_name === 'tanggal_lahir') {
                                        // User table tidak punya tanggal_lahir
                                        $dbValue = $penduduk->tanggal_lahir ?? $user->tanggal_lahir ?? null;
                                    } elseif ($field->field_name === 'jenis_kelamin') {
                                        // Normalisasi data JK (User: laki-laki/perempuan, Penduduk: L/P)
                                        $rawJk = $penduduk->jk ?? $user->jk;
                                        if ($rawJk === 'L' || $rawJk === 'laki-laki') $dbValue = 'Laki-laki';
                                        elseif ($rawJk === 'P' || $rawJk === 'perempuan') $dbValue = 'Perempuan';
                                        else $dbValue = $rawJk;
                                    } elseif ($field->field_name === 'agama') {
                                        $dbValue = $penduduk->agama ?? $user->agama;
                                    } elseif ($field->field_name === 'status_perkawinan') {
                                        $dbValue = $penduduk->status_perkawinan ?? $user->status_perkawinan;
                                    } elseif ($field->field_name === 'kewarganegaraan') {
                                        $dbValue = $penduduk->kewarganegaraan ?? $user->kewarganegaraan;
                                    } elseif ($field->field_name === 'pendidikan') {
                                        $dbValue = $penduduk->pendidikan ?? '';
                                    }
                                    
                                    // Jika ketemu, set value dan kunci field
                                    if (!empty($dbValue)) {
                                        $value = $dbValue;
                                        $isAutoFilled = true;
                                    }
                                }
                        @endphp

                        @if($field->field_type == 'text' || $field->field_type == 'date')
                            <input type="{{ $field->field_type }}" 
                                   name="data[{{ $field->field_name }}]"
                                   id="field_{{ $field->id }}"
                                   value="{{ $value }}"
                                   class="w-full h-11 border-2 border-slate-200 rounded-lg shadow-sm focus:ring-0 focus:border-blue-500 text-sm px-4 {{ $isAutoFilled ? 'bg-slate-50 text-slate-800 font-medium cursor-not-allowed border-slate-300' : 'bg-white text-slate-700 font-medium' }}"
                                   {{ $field->required ? 'required' : '' }}
                                   {{ $isAutoFilled ? 'readonly' : '' }}>

                        @elseif($field->field_type == 'number' || $field->field_type == 'currency')
                            <input type="number" 
                                   name="data[{{ $field->field_name }}]"
                                   id="field_{{ $field->id }}"
                                   value="{{ $value }}"
                                   min="0"
                                   class="w-full h-11 border-2 border-slate-200 rounded-lg shadow-sm focus:ring-0 focus:border-blue-500 text-sm px-4 {{ $isAutoFilled ? 'bg-slate-50 text-slate-800 font-medium cursor-not-allowed border-slate-300' : 'bg-white text-slate-700 font-medium' }}"
                                   {{ $field->required ? 'required' : '' }}
                                   {{ $isAutoFilled ? 'readonly' : '' }}>

                        @elseif($field->field_type == 'textarea')
                            <textarea name="data[{{ $field->field_name }}]"
                                      id="field_{{ $field->id }}"
                                      rows="3"
                                      class="w-full border-2 border-slate-200 rounded-lg shadow-sm focus:ring-0 focus:border-blue-500 text-sm p-4 {{ $isAutoFilled ? 'bg-slate-50 text-slate-800 font-medium cursor-not-allowed border-slate-300' : 'bg-white text-slate-700 font-medium' }}"
                                      {{ $field->required ? 'required' : '' }}
                                      {{ $isAutoFilled ? 'readonly' : '' }}>{{ $value }}</textarea>

                        @elseif($field->field_type == 'select')
                            @if($isAutoFilled)
                                {{-- If Select is autofilled, we show a disabled select for display, and a hidden input to submit the value --}}
                                <input type="hidden" name="data[{{ $field->field_name }}]" value="{{ $value }}">
                                <select disabled
                                        class="w-full h-11 border-2 border-slate-300 rounded-lg shadow-sm bg-slate-50 text-slate-800 font-medium cursor-not-allowed text-sm px-4 opacity-100">
                                    <option value="{{ $value }}" selected>{{ $value }}</option>
                                </select>
                            @else
                                <div class="relative">
                                    <select name="data[{{ $field->field_name }}]"
                                            id="field_{{ $field->id }}"
                                            class="w-full h-11 border-2 border-slate-200 rounded-lg shadow-sm focus:ring-0 focus:border-blue-500 text-sm px-4 appearance-none font-medium text-slate-700"
                                            {{ $field->required ? 'required' : '' }}>
                                        <option value="">-- PILIH --</option>
                                        @foreach($field->options_array as $option)
                                        <option value="{{ $option }}" {{ strcasecmp($value, $option) == 0 ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            @endif
                        
                         @elseif($field->field_type == 'file')
                            <input type="file" 
                                   name="data[{{ $field->field_name }}]"
                                   id="field_{{ $field->id }}"
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:text-xs file:font-bold file:uppercase file:bg-blue-600 file:text-white hover:file:bg-blue-700 {{ $isAutoFilled ? 'cursor-not-allowed opacity-60' : '' }} border-2 border-slate-200 rounded-lg"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   {{ $field->required ? 'required' : '' }}
                                   {{ $isAutoFilled ? 'disabled' : '' }}>
                             <p class="mt-2 text-[10px] text-slate-500 font-medium">Format: JPG, PNG, PDF (Max 2MB)</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Dokumen Pendukung -->
            @if($jenisSurat->requiredDocuments->count() > 0)
            <div class="bg-slate-50 p-6 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-6 pb-2 border-b border-blue-100 flex items-center justify-between">
                    <span class="flex items-center gap-2"><i class="fas fa-folder-open"></i> DOKUMEN PENDUKUNG</span>
                    <span class="text-[10px] bg-green-100 text-green-700 px-2 py-1 rounded ml-2 border border-green-200">
                        <i class="fas fa-lock text-[9px] mr-1"></i> Terenkripsi
                    </span>
                </h3>
                
                <div class="space-y-5">
                    @foreach($jenisSurat->requiredDocuments as $doc)
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 py-2 border-b border-slate-200 last:border-0">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase">
                                {{ $doc->document_label }}
                                @if($doc->required) <span class="text-red-500">*</span> @endif
                            </label>
                            <p class="text-[10px] text-slate-500 mt-1 font-medium">Format: JPG/PDF, Max 2MB</p>
                        </div>
                        
                        <div class="w-full md:w-1/2">
                             <input type="file" 
                                   name="documents[{{ $doc->document_name }}]"
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:uppercase file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300"
                                   {{ $doc->required ? 'required' : '' }}
                                   accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Informasi Tambahan -->
            <div class="bg-slate-50 p-6 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-sticky-note"></i> CATATAN TAMBAHAN
                </h3>
                <div>
                    <textarea name="keterangan_tambahan"
                              rows="3"
                              class="w-full border-2 border-slate-200 rounded-lg shadow-sm focus:ring-0 focus:border-blue-500 text-sm placeholder-slate-400"
                              placeholder="Tulis catatan jika ada...">{{ old('keterangan_tambahan') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
             <button type="button" onclick="window.history.back()" 
                    class="px-5 py-2.5 bg-white text-slate-600 border border-slate-300 rounded-lg font-bold text-sm hover:bg-slate-50 transition-colors">
                Batal
            </button>
            <button type="submit" 
                    class="px-8 py-2.5 bg-blue-600 text-white rounded-lg font-bold shadow-md hover:bg-blue-700 transition-all text-sm flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fas fa-paper-plane"></i> KIRIM PERMOHONAN
            </button>
        </div>

    </form>
</div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAnggota = document.getElementById('pilih_anggota_keluarga');
        if(!selectAnggota) return;

        // Fungsi Auto Fill
        function fillForm(data) {
            // Mapping Field Name (Database) -> Input Name (Form)
            const map = {
                'nama_lengkap': data.nama,
                'nik': data.nik,
                'pekerjaan': data.pekerjaan,
                'tempat_lahir': data.tempatlahir,
                'tanggal_lahir': data.tanggallahir,
                'agama': data.agama,
                'jenis_kelamin': data.jk,
                'status_perkawinan': data.statuskawin,
                'kewarganegaraan': data.kewarganegaraan
            };

            // Loop setiap mapping dan isi inputnya
            for (const [key, value] of Object.entries(map)) {
                // Cari input dengan name="data[key]"
                const input = document.querySelector(`[name="data[${key}]"]`);
                if (input) {
                    // Isi nilai
                    if(input.tagName === 'SELECT') {
                         // Untuk Select, coba cari option yg cocok
                         // Reset dulu
                         input.value = value; 
                         // Jika tidak ketemu persis (case sensitive), coba cari manual
                         if(!input.value) {
                             Array.from(input.options).forEach(opt => {
                                 if(opt.text.toLowerCase() === value.toLowerCase()) {
                                     input.value = opt.value;
                                 }
                             });
                         }
                    } else if (input.type !== 'file') {
                        input.value = value;
                    }

                    // Efek Visual (Kuning sebentar tanda berubah)
                    input.classList.add('bg-yellow-50', 'transition-colors', 'duration-500');
                    setTimeout(() => {
                        input.classList.remove('bg-yellow-50');
                    }, 1000);
                }
            }
        }

        // Event Listener Change
        selectAnggota.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if(selectedOption.value) {
                const data = selectedOption.dataset; // Ambil semua data- attributes
                fillForm(data);
            }
        });

        // Trigger saat load pertama kali (agar terisi data diri sendiri/default select)
        selectAnggota.dispatchEvent(new Event('change'));
    });
</script>
@endpush
@endsection