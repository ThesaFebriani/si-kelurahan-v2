@extends('components.layout')

@section('title', 'Edit Template Surat')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Template Surat</h1>
        <a href="{{ route('admin.templates.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.templates.update', $template->id) }}" method="POST" class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="p-6 border-b border-gray-200 bg-gray-50 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Jenis Surat (Read Only) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                <input type="text" value="{{ $template->jenisSurat->name }} ({{ $template->jenisSurat->code }})" disabled class="w-full border-gray-300 rounded-lg bg-gray-100 text-gray-500">
                <p class="text-xs text-gray-500 mt-1">Jenis surat tidak dapat diubah setelah dibuat.</p>
            </div>

            <!-- Status -->
            <div class="flex items-center pt-8">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $template->is_active ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Aktifkan Template Ini
                </label>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row h-[calc(100vh-200px)]">
            
            <!-- EDITOR COLUMN (PAPER SIMULATOR) -->
            <div class="flex-1 bg-gray-200 p-8 overflow-y-auto flex justify-center relative">
                
                <!-- A4 PAPER CONTAINER -->
                <div class="w-[210mm] min-h-[297mm] bg-white shadow-xl flex flex-col">
                    
                    <!-- 1. STATIC HEADER (KOP SURAT PREVIEW) -->
                    <div class="px-8 pt-8 select-none pointer-events-none opacity-80 cursor-not-allowed bg-gray-50 border-b border-dashed border-gray-300">
                        <div class="text-center text-xs text-gray-400 mb-2 uppercase tracking-widest font-bold">--- Area Kop Surat (Tidak Dapat Diedit) ---</div>
                        <table style="width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; font-family: 'Times New Roman', serif;">
                            <tr>
                                <td style="width: 15%; text-align: center; vertical-align: middle;">
                                    <img src="{{ $logo_url }}" alt="Logo" style="height: 80px;">
                                </td>
                                <td style="text-align: center; vertical-align: middle; color: #000;">
                                    <h3 style="margin: 0; font-size: 14pt; font-weight: bold;">PEMERINTAH KOTA BENGKULU</h3>
                                    <h2 style="margin: 0; font-size: 14pt; font-weight: bold;">KECAMATAN RATU SAMBAN</h2>
                                    <h1 style="margin: 0; font-size: 18pt; font-weight: bold;">KELURAHAN PADANG JATI</h1>
                                    <p style="margin: 0; font-size: 10pt;">JL. Beringin No.01 Telp (0736) 27515 Bengkulu – Kode Pos 38227</p>
                                </td>
                            </tr>
                        </table>
                        <div style="text-align: center; font-family: 'Times New Roman', serif;">
                            <h3 style="text-decoration: underline; margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; color: #000;">
                                {{ $template->jenisSurat->name ?? 'JUDUL SURAT' }}
                            </h3>
                            <p style="margin: 2px 0 20px 0; font-size: 12pt; color: #000;">NOMOR: ... / ... / ... / {{ date('Y') }}</p>
                        </div>
                    </div>

                    <!-- 2. EDITABLE BODY (TinyMCE) -->
                    <div class="flex-1 relative">
                        <textarea name="template_content" id="template_content" class="h-full w-full border-none focus:ring-0">{{ old('template_content', $template->template_content) }}</textarea>
                    </div>

                    <!-- 3. STATIC FOOTER (SIGNATURE PREVIEW) -->
                    <div class="px-12 pb-12 pt-4 select-none pointer-events-none opacity-80 cursor-not-allowed bg-gray-50 border-t border-dashed border-gray-300 font-family-serif">
                        <div class="text-center text-xs text-gray-400 mb-4 uppercase tracking-widest font-bold">--- Area Tanda Tangan (Otomatis) ---</div>
                        <div style="float: right; width: 45%; text-align: center; font-family: 'Times New Roman', serif; color: #000; font-size: 12pt;">
                            <p>Bengkulu, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                            <p style="margin-bottom: 60px;">LURAH PEMATANG GUBERNUR</p>
                            
                            <p style="font-weight: bold; text-decoration: underline;">{{ strtoupper($lurah->name ?? 'EDWIN KURNIAWAN, SH') }}</p>
                            <p>NIP. {{ $lurah->nip ?? '198205272010011004' }}</p>
                        </div>
                        <div style="clear: both;"></div>
                    </div>

                </div>
            </div>

            <!-- RIGHT SIDEBAR: TAG HELPERS -->
            <div class="w-full lg:w-80 bg-white border-l border-gray-200 flex flex-col h-full shadow-lg z-10">
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center">
                        <i class="fas fa-tags text-blue-600 mr-2"></i> Data Otomatis
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Klik tombol untuk menyisipkan data.</p>
                </div>
                
                <div class="flex-1 overflow-y-auto p-4 space-y-6">
                    <!-- Group: Data Pribadi -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Data Pribadi</h4>
                        <div class="space-y-2">
                             @foreach([
                                ['[NAMA_WARGA]', 'Nama Lengkap', 'bg-blue-50 text-blue-700'],
                                ['[NIK]', 'NIK', 'bg-blue-50 text-blue-700'],
                                ['[NO_KK]', 'No. KK', 'bg-blue-50 text-blue-700'],
                                ['[TTL]', 'Tempat/Tgl Lahir', 'bg-blue-50 text-blue-700'],
                                ['[JK]', 'Jenis Kelamin', 'bg-blue-50 text-blue-700'],
                                ['[AGAMA]', 'Agama', 'bg-blue-50 text-blue-700'],
                                ['[PEKERJAAN]', 'Pekerjaan', 'bg-blue-50 text-blue-700'],
                                ['[ALAMAT]', 'Alamat Lengkap', 'bg-blue-50 text-blue-700'],
                                ['[STATUS_PERKAWINAN]', 'Status Kawin', 'bg-blue-50 text-blue-700'],
                                ['[KEWARGANEGARAAN]', 'Kewarganegaraan', 'bg-blue-50 text-blue-700'],
                             ] as $tag)
                            <button type="button" onclick="insertTag('{{ $tag[0] }}')" class="w-full text-left px-3 py-2 bg-white border border-gray-200 rounded-md text-sm hover:border-blue-400 hover:shadow-sm transition flex justify-between items-center group">
                                <span class="font-medium text-gray-700">{{ $tag[1] }}</span>
                                <code class="text-[10px] {{ $tag[2] }} px-1.5 py-0.5 rounded">{{ $tag[0] }}</code>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Group: Data Orang Tua -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Data Orang Tua</h4>
                        <div class="space-y-2">
                             @foreach([
                                ['[NAMA_AYAH]', 'Nama Ayah', 'bg-green-50 text-green-700'],
                                ['[NAMA_IBU]', 'Nama Ibu', 'bg-green-50 text-green-700'],
                             ] as $tag)
                            <button type="button" onclick="insertTag('{{ $tag[0] }}')" class="w-full text-left px-3 py-2 bg-white border border-gray-200 rounded-md text-sm hover:border-green-400 hover:shadow-sm transition flex justify-between items-center group">
                                <span class="font-medium text-gray-700">{{ $tag[1] }}</span>
                                <code class="text-[10px] {{ $tag[2] }} px-1.5 py-0.5 rounded">{{ $tag[0] }}</code>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Group: Data Surat -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Data Surat</h4>
                        <div class="space-y-2">
                             @foreach([
                                ['[NOMOR_SURAT]', 'Nomor Surat', 'bg-purple-50 text-purple-700'],
                                ['[TANGGAL_SURAT]', 'Tanggal Hari Ini', 'bg-purple-50 text-purple-700'],
                                ['[KEPERLUAN]', 'Keperluan', 'bg-purple-50 text-purple-700'],
                             ] as $tag)
                            <button type="button" onclick="insertTag('{{ $tag[0] }}')" class="w-full text-left px-3 py-2 bg-white border border-gray-200 rounded-md text-sm hover:border-purple-400 hover:shadow-sm transition flex justify-between items-center group">
                                <span class="font-medium text-gray-700">{{ $tag[1] }}</span>
                                <code class="text-[10px] {{ $tag[2] }} px-1.5 py-0.5 rounded">{{ $tag[0] }}</code>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Bar (Sticky Bottom) -->
        <div class="bg-white p-4 border-t border-gray-200 flex justify-end items-center sticky bottom-0 z-20 shadow-[0_-5px_15px_-5px_rgba(0,0,0,0.1)]">
            <span class="text-sm text-gray-500 mr-auto italic"><i class="fas fa-info-circle mr-1"></i> Mode Preview: Kop & Tanda tangan di atas hanya ilustrasi.</span>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition shadow-md flex items-center font-medium">
                <i class="fas fa-save mr-2"></i> Simpan Template
            </button>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.5.1/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#template_content',
        license_key: 'gpl',
        height: '100%',
        plugins: 'lists link table code help wordcount image',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | removeformat',
        content_style: 'body { font-family: Times New Roman, Times, serif; font-size: 12pt; line-height: 1.5; padding: 2cm; }'
    });

    function insertTag(tag) {
        tinymce.activeEditor.execCommand('mceInsertContent', false, tag);
    }
</script>
@endsection
