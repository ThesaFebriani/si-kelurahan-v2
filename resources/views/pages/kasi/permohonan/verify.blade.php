@extends('components.layout')

@section('title', 'Verifikasi Permohonan - Kasi')
@section('page-title', 'Verifikasi Permohonan Surat')
@section('page-description', 'Verifikasi dan proses permohonan surat')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                Verifikasi Permohonan Surat
            </h3>
            <p class="text-gray-600 mt-1">Nomor Tiket: <code class="bg-gray-100 px-2 py-1 rounded">{{ $permohonan->nomor_tiket }}</code></p>
        </div>

        <!-- Informasi Permohonan -->
        <div class="p-6 border-b border-gray-200">
            <h4 class="text-md font-semibold text-gray-800 mb-4">Informasi Permohonan</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pemohon</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $permohonan->user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Surat</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $permohonan->jenisSurat->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Pengajuan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $permohonan->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">RT/RW</label>
                    <p class="mt-1 text-sm text-gray-900">
                        @if($permohonan->user->rt && $permohonan->user->rt->rw)
                        RT {{ $permohonan->user->rt->nomor_rt }} / RW {{ $permohonan->user->rt->rw->nomor_rw }}
                        @else
                        Tidak terdaftar
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Lampiran -->
        <div class="p-6 border-b border-gray-200">
            <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-paperclip text-orange-600 mr-2"></i>
                Lampiran Dokumen
            </h4>

            <div class="space-y-4">
                <!-- Surat Pengantar RT -->
                <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-500 mr-3 text-lg"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Surat Pengantar RT</p>
                                <p class="text-xs text-gray-500">Nomor: {{ $permohonan->nomor_surat_pengantar_rt ?? '-' }}</p>
                            </div>
                        </div>
                        @if($permohonan->file_surat_pengantar_rt)
                        <a href="{{ Storage::url($permohonan->file_surat_pengantar_rt) }}"
                            target="_blank"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                            <i class="fas fa-external-link-alt mr-1"></i> Lihat
                        </a>
                        @else
                        <span class="text-gray-400 text-sm italic">Belum tersedia</span>
                        @endif
                    </div>
                </div>

                <!-- Lampiran Warga -->
                @if($permohonan->lampirans && $permohonan->lampirans->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($permohonan->lampirans as $lampiran)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file text-gray-400 mr-3 text-lg"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $lampiran->nama_file }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $lampiran->file_type ?? 'Unknown' }} •
                                        {{ $lampiran->file_size ? number_format($lampiran->file_size / 1024, 2) . ' KB' : 'Unknown size' }}
                                    </p>
                                </div>
                            </div>
                            @if($lampiran->file_path)
                            <a href="{{ Storage::url($lampiran->file_path) }}"
                                target="_blank"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat
                            </a>
                            @else
                            <span class="text-gray-400 text-sm">File tidak tersedia</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <p class="text-sm text-gray-500">Tidak ada lampiran tambahan dari pemohon.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Form Verification -->
        <form action="{{ route('kasi.permohonan.process', $permohonan->id) }}" method="POST">
            @csrf
            <div class="p-6 border-b border-gray-200">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Tindakan Verifikasi</h4>

                <div class="space-y-4">
                    <!-- Action Radio -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Tindakan</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="approve" name="action" value="approve" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300" required>
                                <label for="approve" class="ml-3 block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                        Setujui Permohonan
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="reject" name="action" value="reject" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300" required>
                                <label for="reject" class="ml-3 block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                        Tolak permohonan
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Input Nomor Surat (Muncul jika Approve dipilih) -->
                    <div id="approve-section" class="hidden space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h5 class="text-blue-800 font-semibold mb-2 flex items-center">
                                <i class="fas fa-edit mr-2"></i> Draft Surat Kelurahan
                            </h5>
                            <p class="text-sm text-blue-700 mb-4">
                                Silakan periksa dan edit isi surat di bawah ini sebelum meneruskan ke Lurah.
                            </p>
                            
                            <!-- Nomor Surat -->
                            <div class="mb-4">
                                <label for="nomor_surat" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Surat
                                </label>
                                <input type="text" name="nomor_surat" id="nomor_surat" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    value="{{ $suggestedNomorSurat ?? '' }}">
                                <p class="text-xs text-gray-500 mt-1">Nomor surat akan otomatis terupdate di dalam dokumen.</p>
                            </div>

                            <!-- Editor Surat -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Isi Surat</label>
                                <textarea name="isi_surat" id="isi_surat" rows="15">{{ $defaultContent ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan" id="catatan" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors"
                            placeholder="Berikan catatan verifikasi..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-6 bg-gray-50 flex justify-between items-center">
                <a href="{{ route('kasi.permohonan.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>

                <div class="space-x-3">
                    <button type="button" onclick="history.back()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Proses & Kirim
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.5.1/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init TinyMCE
        tinymce.init({
            selector: '#isi_surat',
            license_key: 'gpl',
            height: 600,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat',
            content_style: 'body { font-family:Times New Roman,Times,serif; font-size:12pt; line-height: 1.5; }',
            
            // Sync logic initial
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save(); 
                });
                
                function updateNomorSurat() {
                    var input = document.getElementById('nomor_surat');
                    if(!input) return;
                    var nomorBaru = input.value;
                    
                    try {
                        var doc = editor.getDoc();
                        if (doc) {
                            var placeholder = doc.getElementById('nomor-surat-display');
                            if (placeholder) {
                                placeholder.innerText = nomorBaru;
                            }
                        }
                    } catch (err) {
                        console.log('Wait context...');
                    }
                }

                editor.on('init', updateNomorSurat);
                // Also update on keyup of "nomor_surat"
                var nomorInput = document.getElementById('nomor_surat');
                if(nomorInput){
                    nomorInput.addEventListener('keyup', updateNomorSurat);
                    nomorInput.addEventListener('change', updateNomorSurat);
                }
            }
        });

        const form = document.querySelector('form');
        const actionRadios = document.querySelectorAll('input[name="action"]');
        const approveSection = document.getElementById('approve-section');
        const nomorInput = document.getElementById('nomor_surat');
        const suratInput = document.getElementById('isi_surat');

        // Toggle visibility input nomor surat
        actionRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'approve') {
                    approveSection.classList.remove('hidden');
                    nomorInput.setAttribute('required', 'required');
                } else {
                    approveSection.classList.add('hidden');
                    nomorInput.removeAttribute('required');
                }
            });
        });

        form.addEventListener('submit', function(e) {
            const actionSelected = document.querySelector('input[name="action"]:checked');
            if (!actionSelected) {
                e.preventDefault();
                alert('Pilih tindakan verifikasi terlebih dahulu!');
                return false;
            }
        });
    });
</script>
@endsection