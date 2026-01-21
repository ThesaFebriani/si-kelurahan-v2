@extends('components.layout')

@section('title', 'Edit Jenis Surat')
@section('page-title', 'Edit Jenis Surat')
@section('page-description', 'Perbarui informasi jenis surat.')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- HEADER & BACK BUTTON -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Jenis Surat</h2>
            <p class="text-slate-500 text-sm mt-1">Perbarui informasi jenis surat.</p>
        </div>
        <a href="{{ route('admin.jenis-surat.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- CARD 1: EDIT FORM DATA -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
        <form action="{{ route('admin.jenis-surat.update', $jenis_surat->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                
                <!-- Informasi Utama -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Informasi Utama
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Surat (Unique) <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_surat" value="{{ old('kode_surat', $jenis_surat->kode_surat) }}" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Contoh: surat_keterangan_usaha">
                            <p class="text-[10px] text-slate-500 mt-0.5">Gunakan huruf kecil dan underscore, tanpa spasi.</p>
                            @error('kode_surat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Surat <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $jenis_surat->name) }}" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Contoh: Surat Keterangan Usaha">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi</label>
                            <textarea name="description" rows="3"
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400"
                                placeholder="Deskripsi singkat tentang kegunaan surat ini...">{{ old('description', $jenis_surat->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Pengaturan & Validasi -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                        <i class="fas fa-cogs"></i> Pengaturan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bidang (Tujuan Kasi) <span class="text-red-500">*</span></label>
                            <select name="bidang" required
                                class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" disabled>-- Pilih Bidang / Kasi --</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->code }}" {{ old('bidang', $jenis_surat->bidang) == $bidang->code ? 'selected' : '' }}>
                                        {{ $bidang->name }} (Kode: {{ $bidang->code }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-500 mt-0.5">Surat ini akan masuk ke dashboard Kasi yang dipilih.</p>
                            @error('bidang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $jenis_surat->is_active) ? 'checked' : '' }}
                                    class="h-5 w-5 text-blue-600 border-2 border-slate-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-bold text-slate-700">Aktifkan Surat Ini?</span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Submit -->
            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white/95 backdrop-blur-sm p-4 -mx-6 -mb-6 rounded-b-lg shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm shadow-md hover:shadow-lg transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <!-- CARD 2: DOCUMENT REQUIREMENTS SECTION -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Persyaratan Dokumen</h3>
                <p class="text-slate-500 text-sm mt-1">Kelola dokumen yang wajib diunggah warga.</p>
            </div>
            <button onclick="document.getElementById('addDocumentModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fas fa-plus"></i> Tambah Syarat
            </button>
        </div>

        @if($jenis_surat->requiredDocuments->count() > 0)
            <div class="overflow-x-auto border border-slate-200 rounded-lg">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 font-medium">Nama Dokumen</th>
                            <th class="px-4 py-3 font-medium text-center">Wajib?</th>
                            <th class="px-4 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($jenis_surat->requiredDocuments as $doc)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-700">{{ $doc->document_name }}</div>
                                @if($doc->description)
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $doc->description }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($doc->is_required)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Wajib
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        Opsional
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('admin.required-documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Hapus persyaratan ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 border-2 border-dashed border-slate-200 rounded-lg bg-slate-50">
                <div class="text-slate-400 mb-2">
                    <i class="far fa-folder-open text-3xl"></i>
                </div>
                <p class="text-slate-500 text-sm">Belum ada persyaratan dokumen.</p>
            </div>
        @endif
    </div>

    <!-- CARD 3: TEMPLATE FIELDS SECTION -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Formulir Isian Surat</h3>
                <p class="text-slate-500 text-sm mt-1">Kelola kolom isian khusus untuk jenis surat ini.</p>
            </div>
            <button onclick="document.getElementById('addTemplateFieldModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fas fa-plus"></i> Tambah Kolom
            </button>
        </div>

        @if($jenis_surat->templateFields->count() > 0)
            <div class="overflow-x-auto border border-slate-200 rounded-lg">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 font-medium">Label Kolom</th>
                            <th class="px-4 py-3 font-medium">Key (Variable)</th>
                            <th class="px-4 py-3 font-medium">Tipe Input</th>
                            <th class="px-4 py-3 font-medium text-center">Wajib?</th>
                            <th class="px-4 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($jenis_surat->templateFields as $field)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-700">{{ $field->field_label }}</div>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-slate-500">
                                {{ $field->field_key }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="capitalize">{{ $field->field_type }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($field->is_required)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Wajib
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        Opsional
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('admin.template-fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Hapus kolom ini?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 border-2 border-dashed border-slate-200 rounded-lg bg-slate-50">
                <div class="text-slate-400 mb-2">
                    <i class="far fa-list-alt text-3xl"></i>
                </div>
                <p class="text-slate-500 text-sm">Belum ada kolom isian khusus.</p>
            </div>
        @endif
    </div>

</div>

<!-- MODAL ADD TEMPLATE FIELD -->
<div id="addTemplateFieldModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('addTemplateFieldModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('admin.template-fields.store') }}" method="POST">
                @csrf
                <input type="hidden" name="jenis_surat_id" value="{{ $jenis_surat->id }}">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Kolom Isian</h3>
                        <button type="button" onclick="document.getElementById('addTemplateFieldModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label Kolom</label>
                            <input type="text" name="field_label" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Contoh: Nama Usaha">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Key Variable (Tanpa Spasi)</label>
                            <input type="text" name="field_key" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Contoh: nama_usaha">
                            <p class="text-xs text-gray-400 mt-1">Gunakan huruf kecil & underscore. Dipakai di template surat [TAG].</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Input</label>
                            <select name="field_type" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="text">Teks Singkat (Text)</option>
                                <option value="textarea">Teks Panjang (Textarea)</option>
                                <option value="number">Angka (Number)</option>
                                <option value="date">Tanggal (Date)</option>
                                <!-- <option value="dropdown">Dropdown Options</option> -->
                            </select>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_required" value="1" id="field_is_required_check" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="field_is_required_check" class="ml-2 block text-sm text-gray-900">
                                Wajib diisi (Required)
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="document.getElementById('addTemplateFieldModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ADD DOCUMENT -->
<div id="addDocumentModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('addDocumentModal').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('admin.required-documents.store') }}" method="POST">
                @csrf
                <input type="hidden" name="jenis_surat_id" value="{{ $jenis_surat->id }}">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Tambah Persyaratan Dokumen
                        </h3>
                        <button type="button" onclick="document.getElementById('addDocumentModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dokumen</label>
                            <input type="text" name="document_name" required class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Contoh: Lampiran KTP">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                            <textarea name="description" rows="2" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Instruksi tambahan..."></textarea>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_required" value="1" id="is_required_check" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_required_check" class="ml-2 block text-sm text-gray-900">
                                Wajib diunggah (Required)
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="document.getElementById('addDocumentModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
