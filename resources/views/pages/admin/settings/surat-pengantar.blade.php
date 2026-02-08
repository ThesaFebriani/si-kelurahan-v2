@extends('components.layout')

@section('title', 'Format Surat Pengantar RT')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6 pl-12 md:pl-0">
        <h1 class="text-2xl font-bold text-gray-800">Format Surat Pengantar RT Global</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.settings.surat-pengantar.update') }}" method="POST" class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center space-x-2 text-yellow-600 bg-yellow-50 p-4 rounded-lg mb-0 border border-yellow-200">
                <i class="fas fa-exclamation-triangle text-xl"></i>
                <div class="text-sm">
                    <strong>PENTING:</strong> Template ini digunakan sebagai standar baku untuk <strong>SEMUA</strong> jenis surat pengantar dari RT. Perubahan di sini akan berdampak pada seluruh sistem.
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row h-[calc(100vh-250px)]">
            
            <!-- EDITOR COLUMN -->
            <div class="flex-1 bg-gray-200 p-4 md:p-8 overflow-auto flex justify-center relative">
                
                <!-- A4 PAPER CONTAINER -->
                <div class="w-[210mm] min-h-[297mm] bg-white shadow-xl flex flex-col p-[2cm]">
                    <textarea name="template_content" id="template_content" class="h-full w-full border-none focus:ring-0">{{ old('template_content', $template->template_content) }}</textarea>
                </div>
            </div>

            <!-- RIGHT SIDEBAR: TAG HELPERS -->
            <div class="w-full lg:w-80 bg-white border-l border-gray-200 flex flex-col h-full shadow-lg z-10">
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center">
                        <i class="fas fa-tags text-blue-600 mr-2"></i> Variabel Otomatis
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Sistem akan mengganti kode dibawah ini dengan data asli.</p>
                </div>
                
                <div class="flex-1 overflow-y-auto p-4 space-y-6">
                    
                    <!-- Group: Data Kop Surat -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Data Wilayah (Kop Surat)</h4>
                        <div class="space-y-2">
                             @foreach([
                                ['[NOMOR_RT]', 'Nomor RT'],
                                ['[NOMOR_RW]', 'Nomor RW'],
                                ['[ALAMAT_SEKRETARIAT]', 'Alamat Sekretariat'],
                                ['[NO_HP_RT]', 'No. HP RT'],
                             ] as $tag)
                            <button type="button" onclick="insertTag('{{ $tag[0] }}')" class="w-full text-left px-3 py-2 bg-white border border-gray-200 rounded-md text-sm hover:border-blue-400 group flex justify-between">
                                <span>{{ $tag[1] }}</span> <code class="text-[10px] bg-gray-100 px-1">{{ $tag[0] }}</code>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Group: Data Warga -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Data Warga</h4>
                        <div class="space-y-2">
                             @foreach([
                                ['[NAMA_WARGA]', 'Nama Lengkap'],
                                ['[NIK]', 'NIK'],
                                ['[TTL_WARGA]', 'Tempat/Tgl Lahir'],
                                ['[JENIS_KELAMIN]', 'Jenis Kelamin'],
                                ['[AGAMA]', 'Agama'],
                                ['[PEKERJAAN]', 'Pekerjaan'],
                                ['[ALAMAT_WARGA]', 'Alamat Domisili'],
                                ['[STATUS_PERKAWINAN]', 'Status Kawin'],
                                ['[PENDIDIKAN]', 'Pendidikan'],
                                ['[BANGSA]', 'Bangsa'],
                                ['[KEPALA_KELUARGA]', 'Kepala Keluarga'],
                             ] as $tag)
                            <button type="button" onclick="insertTag('{{ $tag[0] }}')" class="w-full text-left px-3 py-2 bg-white border border-gray-200 rounded-md text-sm hover:border-blue-400 hover:shadow-sm transition flex justify-between items-center group">
                                <span class="font-medium text-gray-700">{{ $tag[1] }}</span>
                                <code class="text-[10px] bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded">{{ $tag[0] }}</code>
                            </button>
                            @endforeach
                        </div>
                    </div>

                     <!-- Group: Data Surat -->
                     <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Data Surat & TTD</h4>
                        <div class="space-y-2">
                             @foreach([
                                ['[NOMOR_SURAT]', 'Nomor Surat'],
                                ['[TAHUN]', 'Tahun Ini'],
                                ['[TANGGAL_SURAT]', 'Tanggal Hari Ini'],
                                ['[NAMA_KETUA_RT]', 'Nama Ketua RT'],
                                ['[QR_CODE_SPACE]', 'Area QR Code/TTD'],
                             ] as $tag)
                            <button type="button" onclick="insertTag('{{ $tag[0] }}')" class="w-full text-left px-3 py-2 bg-white border border-gray-200 rounded-md text-sm hover:border-purple-400 hover:shadow-sm transition flex justify-between items-center group">
                                <span class="font-medium text-gray-700">{{ $tag[1] }}</span>
                                <code class="text-[10px] bg-purple-50 text-purple-700 px-1.5 py-0.5 rounded">{{ $tag[0] }}</code>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="bg-white p-4 border-t border-gray-200 flex justify-end items-center sticky bottom-0 z-20 shadow-[0_-5px_15px_-5px_rgba(0,0,0,0.1)]">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition shadow-md flex items-center font-medium">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
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
        content_style: 'body { font-family: Times New Roman, Times, serif; font-size: 12pt; line-height: 1.15; }' // Matches the seeder default
    });

    function insertTag(tag) {
        tinymce.activeEditor.execCommand('mceInsertContent', false, tag);
    }
</script>
@endsection
