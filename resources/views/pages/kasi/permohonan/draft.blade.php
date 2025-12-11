@extends('components.layout')

@section('title', 'Draft Surat - Kasi')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                Draft Surat: {{ $permohonan->jenisSurat->name ?? 'Surat' }}
            </h3>
            <a href="{{ route('kasi.permohonan.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times"></i>
            </a>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('kasi.permohonan.store-draft', $permohonan->id) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data Pemohon</label>
                        <div class="bg-gray-50 p-4 rounded-md border text-sm">
                            <p><span class="font-semibold">Nama:</span> {{ $permohonan->data_pemohon['nama_lengkap'] ?? $permohonan->user->name ?? '-' }}</p>
                            <p><span class="font-semibold">NIK:</span> {{ (empty($permohonan->data_pemohon['nik']) || $permohonan->data_pemohon['nik'] == '-') ? $permohonan->user->nik : $permohonan->data_pemohon['nik'] }}</p>
                            <p><span class="font-semibold">Tgl Lahir:</span> {{ (empty($permohonan->data_pemohon['tanggal_lahir']) || $permohonan->data_pemohon['tanggal_lahir'] == '-') ? ($permohonan->user->tanggal_lahir ? \Carbon\Carbon::parse($permohonan->user->tanggal_lahir)->format('d F Y') : '-') : $permohonan->data_pemohon['tanggal_lahir'] }}</p>
                            <p><span class="font-semibold">Alamat:</span> {{ (empty($permohonan->data_pemohon['alamat']) || $permohonan->data_pemohon['alamat'] == '-') ? ($permohonan->user->alamat_lengkap ?? $permohonan->user->alamat) : $permohonan->data_pemohon['alamat'] }}</p>
                        </div>
                        </div>
                        <div>
                            <label for="nomor_surat" class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat (Manual)</label>
                            <input type="text" name="nomor_surat" id="nomor_surat" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('nomor_surat', $suggestedNomorSurat) }}" placeholder="Contoh: 140/KL/IX/2024" required>
                            <p class="text-xs text-gray-500 mt-1">Masukkan nomor surat sesuai buku register.</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="isi_surat" class="block text-sm font-medium text-gray-700 mb-2">Isi Surat</label>
                        <textarea name="isi_surat" id="isi_surat" rows="20">{{ old('isi_surat', $draftContent) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Simpan Draft
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.5.1/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#isi_surat',
        license_key: 'gpl',
        plugins: 'lists link table code help wordcount',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        menubar: false,
        height: 600,
        branding: false,
        promotion: false,
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save(); 
                });

                function updateNomorSurat() {
                    var input = document.getElementById('nomor_surat');
                    var nomorBaru = input.value;
                    if(!nomorBaru) nomorBaru = '... / ... / ... / ' + new Date().getFullYear();

                    try {
                        var doc = editor.getDoc();
                        if (doc) {
                            var placeholder = doc.getElementById('nomor_surat_placeholder');
                            if (placeholder) {
                                placeholder.innerText = nomorBaru;
                            }
                        }
                    } catch (err) {
                        console.log('Error updating placeholder:', err);
                    }
                }
                
                // Update saat editor siap (Initial Load)
                editor.on('init', function() {
                    updateNomorSurat();
                });

                // Update saat mengetik
                document.getElementById('nomor_surat').addEventListener('keyup', updateNomorSurat);
                // Update saat paste/change
                document.getElementById('nomor_surat').addEventListener('change', updateNomorSurat);
            }
    });
</script>
@endsection
