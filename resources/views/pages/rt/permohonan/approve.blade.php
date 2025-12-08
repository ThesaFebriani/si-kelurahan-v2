@extends('components.layout')

@section('title', 'Proses Permohonan - RT')
@section('page-title', 'Proses Permohonan Surat')
@section('page-description', 'Approve atau reject permohonan surat')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                Proses Permohonan Surat
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
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $permohonan->user->alamat_lengkap ?? 'Tidak ada alamat' }}</p>
                </div>
            </div>

            <!-- Data Pemohon -->
            <div class="mt-6">
                <h5 class="text-sm font-medium text-gray-700 mb-2">Data Pemohon</h5>
                <div class="bg-gray-50 rounded-lg p-4">
                    @php
                    // Handle data pemohon dengan safe approach
                    $dataPemohon = $permohonan->data_pemohon;

                    // Jika data_pemohon adalah string, coba decode
                    if (is_string($dataPemohon)) {
                    $decoded = json_decode($dataPemohon, true);
                    $dataPemohon = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
                    }

                    // Pastikan $dataPemohon adalah array
                    $dataPemohon = is_array($dataPemohon) ? $dataPemohon : [];
                    @endphp

                    @if(!empty($dataPemohon) && count($dataPemohon) > 0)
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><strong>NIK:</strong> {{ $dataPemohon['nik'] ?? '-' }}</div>
                        <div><strong>Tempat Lahir:</strong> {{ $dataPemohon['tempat_lahir'] ?? '-' }}</div>
                        <div><strong>Tanggal Lahir:</strong>
                            @if(!empty($dataPemohon['tanggal_lahir']))
                            @php
                            try {
                            $tanggalLahir = \Carbon\Carbon::parse($dataPemohon['tanggal_lahir'])->format('d/m/Y');
                            } catch (Exception $e) {
                            $tanggalLahir = $dataPemohon['tanggal_lahir'];
                            }
                            @endphp
                            {{ $tanggalLahir }}
                            @else
                            -
                            @endif
                        </div>
                        <div><strong>Jenis Kelamin:</strong>
                            @if(isset($dataPemohon['jenis_kelamin']))
                            {{ $dataPemohon['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            @else
                            -
                            @endif
                        </div>
                        <div><strong>Agama:</strong> {{ $dataPemohon['agama'] ?? $permohonan->user->agama ?? '-' }}</div>
                        <div><strong>Pekerjaan:</strong> {{ $dataPemohon['pekerjaan'] ?? $permohonan->user->pekerjaan ?? '-' }}</div>
                    </div>
                    <div class="mt-3">
                        <strong>Tujuan:</strong>
                        <p class="text-sm text-gray-700 mt-1">{{ $dataPemohon['tujuan'] ?? 'Tidak ada keterangan' }}</p>
                    </div>
                    @else
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-info-circle text-gray-300 text-xl mb-2"></i>
                        <p>Data pemohon tidak tersedia</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Approval -->
        <form action="{{ route('rt.permohonan.process', $permohonan->id) }}" method="POST">
            @csrf
            <div class="p-6 border-b border-gray-200">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Tindakan</h4>

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
                                        Setujui dan teruskan ke Kasi
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
                                <i class="fas fa-edit mr-2"></i> Draft Surat Pengantar
                            </h5>
                            <p class="text-sm text-blue-700 mb-4">
                                Silakan periksa dan edit isi surat pengantar di bawah ini sebelum menandatangani.
                            </p>
                            
                            <!-- Nomor Surat -->
                            <div class="mb-4">
                                <label for="nomor_surat_pengantar" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Surat Pengantar
                                </label>
                                <input type="text" name="nomor_surat_pengantar" id="nomor_surat_pengantar" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    value="{{ $nomorSurat }}" required>
                            </div>

                            <!-- Editor Surat -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Isi Surat</label>
                                <textarea name="isi_surat" id="isi_surat" rows="15">{{ $defaultContent }}</textarea>
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
                            placeholder="Berikan catatan atau alasan persetujuan/penolakan..."></textarea>
                        <p class="text-sm text-gray-500 mt-1">Catatan akan ditampilkan di timeline permohonan</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-6 bg-gray-50 flex justify-between items-center">
                <a href="{{ route('rt.permohonan.index') }}"
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
                        <i class="fas fa-paper-plane mr-2"></i>Proses Permohonan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.5.1/tinymce.min.js" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init TinyMCE
        tinymce.init({
            selector: '#isi_surat',
            height: 400,
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
            content_style: 'body { font-family:Times New Roman,Times,serif; font-size:12pt }'
        });

        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        const actionRadios = document.querySelectorAll('input[name="action"]');
        const approveSection = document.getElementById('approve-section');
        const nomorInput = document.getElementById('nomor_surat_pengantar');

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
                alert('Pilih tindakan terlebih dahulu!');
                return false;
            }
        });
    });
</script>
@endsection