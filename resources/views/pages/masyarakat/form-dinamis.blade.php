@extends('components.layout')

@section('title', 'Ajukan ' . $jenisSurat->name . ' - Sistem Kelurahan')
@section('page-title', 'Ajukan ' . $jenisSurat->name)
@section('page-description', 'Isi form pengajuan ' . $jenisSurat->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">

        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                        Form Pengajuan {{ $jenisSurat->name }}
                    </h3>
                    <p class="text-gray-600 mt-1">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($jenisSurat->bidang == 'kesra') bg-green-100 text-green-800
                            @elseif($jenisSurat->bidang == 'pemerintahan') bg-blue-100 text-blue-800
                            @else bg-purple-100 text-purple-800 @endif">
                            {{ $jenisSurat->bidang_display }}
                        </span>
                        • Estimasi: {{ $jenisSurat->estimasi_hari }} hari
                    </p>
                </div>
                <a href="{{ route('masyarakat.permohonan.create') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-arrow-left mr-1"></i> Ganti Jenis Surat
                </a>
            </div>
        </div>

        @if ($errors->any())
        <div class="p-4 mb-4 text-red-800 bg-red-100 rounded-lg">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('masyarakat.permohonan.store.dinamis', $jenisSurat->id) }}"
            method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 space-y-8">

                <!-- Data Diri Pemohon -->
                <div class="border-b pb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Data Diri Pemohon
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($jenisSurat->templateFields as $field)
                        <div class="form-field">
                            <label for="field_{{ $field->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $field->field_label }}
                                @if($field->required)
                                <span class="text-red-500">*</span>
                                @endif
                            </label>

                            @if($field->field_type == 'text')
                            <input type="text"
                                name="data[{{ $field->field_name }}]"
                                id="field_{{ $field->id }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500"
                                {{ $field->required ? 'required' : '' }}>

                            @elseif($field->field_type == 'number')
                            <input type="number"
                                name="data[{{ $field->field_name }}]"
                                id="field_{{ $field->id }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500"
                                {{ $field->required ? 'required' : '' }}>

                            @elseif($field->field_type == 'date')
                            <input type="date"
                                name="data[{{ $field->field_name }}]"
                                id="field_{{ $field->id }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500"
                                {{ $field->required ? 'required' : '' }}>

                            @elseif($field->field_type == 'textarea')
                            <textarea name="data[{{ $field->field_name }}]"
                                id="field_{{ $field->id }}"
                                rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500"
                                {{ $field->required ? 'required' : '' }}></textarea>

                            @elseif($field->field_type == 'select')
                            <select name="data[{{ $field->field_name }}]"
                                id="field_{{ $field->id }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500"
                                {{ $field->required ? 'required' : '' }}>
                                <option value="">-- Pilih {{ $field->field_label }} --</option>
                                @foreach($field->options_array as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>

                            @elseif($field->field_type == 'file')
                            <input type="file"
                                name="data[{{ $field->field_name }}]"
                                id="field_{{ $field->id }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 
                                        file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 
                                        file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700"
                                accept=".jpg,.jpeg,.png,.pdf"
                                {{ $field->required ? 'required' : '' }}>
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF (Maks. 2MB)</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Dokumen Wajib -->
                @if($jenisSurat->requiredDocuments->count() > 0)
                <div class="border-b pb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-paperclip text-orange-600 mr-2"></i>
                        Dokumen Pendukung Wajib
                    </h4>

                    <div class="space-y-4">
                        @foreach($jenisSurat->requiredDocuments as $doc)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file text-gray-400 mr-3 text-lg"></i>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $doc->document_label }}</p>
                                    <p class="text-sm text-gray-500">
                                        @if($doc->required)
                                        <span class="text-red-500">Wajib diunggah</span>
                                        @else Opsional @endif
                                    </p>
                                </div>
                            </div>

                            <input type="file"
                                name="documents[{{ $doc->document_name }}]"
                                class="border border-gray-300 rounded-lg px-3 py-1 text-sm 
                                       file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 
                                       file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700"
                                {{ $doc->required ? 'required' : '' }}
                                accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Informasi Tambahan -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                        Informasi Tambahan
                    </h4>

                    <textarea name="keterangan_tambahan"
                        rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500"
                        placeholder="Berikan keterangan tambahan jika diperlukan..."></textarea>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-6 border-t bg-gray-50 flex justify-between">
                <a href="{{ route('masyarakat.permohonan.create') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <div class="space-x-3">
                    <button type="button" onclick="document.querySelector('form').reset();"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                        Reset
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-paper-plane mr-2"></i>Ajukan Permohonan
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>


@endsection