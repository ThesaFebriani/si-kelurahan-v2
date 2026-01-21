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
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h4 class="font-bold text-gray-800 text-lg">Informasi Permohonan</h4>
            <span class="text-[10px] font-bold uppercase text-gray-400 bg-white border border-gray-200 px-2 py-1 rounded tracking-wider">Read-Only</span>
        </div>
        
        <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Pemohon</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-700 font-medium text-sm">{{ $permohonan->user->name }}</div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Jenis Surat</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-700 font-medium text-sm">{{ $permohonan->jenisSurat->name }}</div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Tanggal Pengajuan</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-700 font-medium text-sm">{{ $permohonan->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Alamat</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-700 font-medium text-sm">{{ $permohonan->user->alamat_lengkap ?? 'Tidak ada alamat' }}</div>
                </div>
            </div>

            <!-- Data Pemohon -->
            <div class="mt-8">
                <h5 class="flex items-center text-sm font-bold text-slate-800 uppercase tracking-wide mb-4 pb-2 border-b border-gray-100">
                    <i class="fas fa-user-circle text-blue-500 mr-2"></i> Data Pemohon
                </h5>
                <div class="bg-white rounded-lg">
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

                    // Fallback to User profile if data is incomplete
                    $user = $permohonan->user;
                    $penduduk = $user->anggotaKeluarga;
                    
                    $userMapping = [
                        'nama_lengkap' => $penduduk->nama_lengkap ?? $user->name,
                        'nik' => $user->nik,
                        'tempat_lahir' => $penduduk->tempat_lahir ?? $user->tempat_lahir,
                        'tanggal_lahir' => $penduduk->tanggal_lahir ?? $user->tanggal_lahir,
                        'pekerjaan' => $penduduk->pekerjaan ?? $user->pekerjaan,
                        'jenis_kelamin' => $penduduk->jk ?? $user->jk,
                        'agama' => $penduduk->agama ?? $user->agama,
                        'alamat' => $penduduk && $penduduk->keluarga ? $penduduk->keluarga->alamat : ($user->alamat_lengkap ?? $user->alamat),
                    ];

                    foreach($userMapping as $k => $v) {
                        if((empty($dataPemohon[$k]) || $dataPemohon[$k] === '-') && !empty($v)) {
                            $dataPemohon[$k] = $v;
                        }
                    }
                    @endphp

                    @if(!empty($dataPemohon) && count($dataPemohon) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">NIK</label>
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-700 font-medium text-sm font-mono">{{ $dataPemohon['nik'] ?? '-' }}</div>
                        </div>
                        <div>
                             <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Tempat Lahir</label>
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-700 font-medium text-sm">{{ $dataPemohon['tempat_lahir'] ?? '-' }}</div>
                        </div>
                        <div>
                             <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Tanggal Lahir</label>
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-700 font-medium text-sm">
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
                        </div>
                        <div>
                             <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Jenis Kelamin</label>
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-700 font-medium text-sm">
                                @if(isset($dataPemohon['jenis_kelamin']))
                                {{ $dataPemohon['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                @else
                                -
                                @endif
                            </div>
                        </div>
                        <div>
                             <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Agama</label>
                             <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-700 font-medium text-sm">{{ $dataPemohon['agama'] ?? $permohonan->user->agama ?? '-' }}</div>
                        </div>
                        <div>
                             <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Pekerjaan</label>
                             <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-slate-700 font-medium text-sm">{{ $dataPemohon['pekerjaan'] ?? $permohonan->user->pekerjaan ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 block">Keperluan / Tujuan</label>
                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 text-slate-700 font-medium text-sm leading-relaxed border-l-4 border-l-blue-400">
                             {{ $dataPemohon['tujuan'] ?? 'Tidak ada keterangan' }}
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                        <i class="fas fa-info-circle text-slate-300 text-2xl mb-2"></i>
                        <p class="text-slate-500 text-sm font-medium">Data pemohon tidak tersedia</p>
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
                <div class="space-y-6">
                    <!-- Action Radio (Cards) -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wide">Pilih Tindakan</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Approve Card -->
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="action" value="approve" class="peer sr-only" required>
                                <div class="p-4 rounded-xl border-2 border-slate-200 hover:border-blue-400 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-bold text-slate-800">Setujui Permohonan</h6>
                                            <p class="text-xs text-slate-500">Teruskan ke Kasi/Lurah</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-4 right-4 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                            </label>

                            <!-- Reject Card -->
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="action" value="reject" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 border-slate-200 hover:border-red-400 peer-checked:border-red-600 peer-checked:bg-red-50 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-colors">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-bold text-slate-800">Tolak Permohonan</h6>
                                            <p class="text-xs text-slate-500">Kembalikan ke pemohon</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-4 right-4 text-red-600 opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                            </label>
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

        // Real-time Update Nomor Surat in TinyMCE
        nomorInput.addEventListener('input', function() {
            var inputVal = this.value;
            if (tinymce.activeEditor && !tinymce.activeEditor.isHidden()) {
                var doc = tinymce.activeEditor.getDoc();
                var span = doc.getElementById('nomor-surat-display');
                if (span) {
                    span.innerText = inputVal;
                    tinymce.activeEditor.save(); // Sync back to textarea
                }
            }
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