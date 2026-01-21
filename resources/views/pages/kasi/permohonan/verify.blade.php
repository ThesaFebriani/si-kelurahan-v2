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

    <div class="space-y-6">
        <!-- Applicant Data Section (Readonly) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
             <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center">
                    <i class="fas fa-user-check text-cyan-600 mr-2.5"></i> Review Data Pemohon
                </h3>
            </div>
            <div class="p-6">
                @php
                    $dataPemohon = $permohonan->data_pemohon;
                    if (is_string($dataPemohon)) $dataPemohon = json_decode($dataPemohon, true) ?? [];
                    $dataPemohon = is_array($dataPemohon) ? $dataPemohon : [];
                    
                    // Fallback Logic
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
                        'alamat' => $penduduk && $penduduk->keluarga ? $penduduk->keluarga->alamat : $user->alamat_lengkap,
                        'status_perkawinan' => $penduduk->status_perkawinan ?? $user->status_perkawinan,
                        'kewarganegaraan' => $penduduk->kewarganegaraan ?? $user->kewarganegaraan,
                    ];
                    foreach($userMapping as $k => $v) {
                        if(empty($dataPemohon[$k]) || $dataPemohon[$k] === '-') $dataPemohon[$k] = $v;
                    }

                    // Grid Config
                    $excludeCommon = ['tujuan', 'user_id', 'user_name'];
                    $priorityKeys = ['nik', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'pekerjaan', 'agama', 'alamat'];
                    
                     $colSpans = [
                        'alamat' => 'col-span-12',
                        'tempat_lahir' => 'col-span-6',
                        'tanggal_lahir' => 'col-span-6',
                        'nik' => 'col-span-12 md:col-span-6',
                        'nama_lengkap' => 'col-span-12 md:col-span-6',
                        'jenis_kelamin' => 'col-span-6',
                        'pekerjaan' => 'col-span-12 md:col-span-6',
                        'agama' => 'col-span-6',
                    ];
                @endphp

                <div class="grid grid-cols-12 gap-5">
                     @foreach($priorityKeys as $key)
                        @if(!empty($dataPemohon[$key]))
                             @php $span = $colSpans[$key] ?? 'col-span-12 md:col-span-6'; @endphp
                            <div class="{{ $span }}">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block pl-0.5">
                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                </label>
                                <div class="bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-slate-700 font-bold text-sm shadow-sm">
                                    @if($key === 'tanggal_lahir')
                                        {{ \Carbon\Carbon::parse($dataPemohon[$key])->format('d F Y') }}
                                    @elseif($key === 'jenis_kelamin')
                                        {{ $dataPemohon[$key] == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    @else
                                        {{ $dataPemohon[$key] }}
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Lampiran Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
             <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800 flex items-center">
                    <i class="fas fa-paperclip text-orange-500 mr-2"></i> Kelengkapan Berkas
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Surat Pengantar RT -->
                    @if($permohonan->file_surat_pengantar_rt)
                    <div class="flex items-center p-3 border border-blue-100 bg-blue-50/20 rounded-xl">
                        <div class="w-10 h-10 bg-white text-blue-600 rounded-lg flex items-center justify-center mr-3 shadow-sm border border-blue-100">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800">Surat Pengantar RT</p>
                            <p class="text-xs text-blue-600">Nomor: {{ $permohonan->nomor_surat_pengantar_rt }}</p>
                        </div>
                        <a href="{{ route('documents.show', ['filename' => basename($permohonan->file_surat_pengantar_rt)]) }}" target="_blank"
                           class="ml-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm">
                            <i class="fas fa-eye mr-1"></i> Cek
                        </a>
                    </div>
                    @else
                    <div class="flex items-center p-3 border border-red-200 bg-red-50 rounded-xl">
                         <div class="w-10 h-10 bg-white text-red-500 rounded-lg flex items-center justify-center mr-3 shadow-sm">
                             <i class="fas fa-exclamation-triangle"></i>
                         </div>
                         <div class="flex-1">
                             <p class="text-sm font-bold text-red-700">Surat Pengantar RT Belum Ada</p>
                             <p class="text-xs text-red-500">Mohon cek status approval RT.</p>
                         </div>
                    </div>
                    @endif

                    <!-- Lampiran Lain -->
                    @foreach($permohonan->lampirans as $lampiran)
                    <div class="flex items-center p-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 bg-red-100 text-red-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $lampiran->nama_file }}</p>
                            <p class="text-xs text-slate-500">{{ number_format($lampiran->file_size / 1024, 0) }} KB</p>
                        </div>
                        @if($lampiran->file_path)
                        <a href="{{ route('documents.show', ['filename' => basename($lampiran->file_path)]) }}" target="_blank"
                           class="ml-2 p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Form Verification -->
        <form action="{{ route('kasi.permohonan.process', $permohonan->id) }}" method="POST">
            @csrf
            <div class="p-6 border-b border-slate-100 bg-white">
                <h4 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider">Tindakan Verifikasi</h4>

                <div class="space-y-6">
                    <!-- Action Radio -->
                    <!-- Action Radio (Cards) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Pilih Tindakan</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Approve Card -->
                            <label class="relative cursor-pointer group">
                                <input type="radio" id="approve" name="action" value="approve" class="peer sr-only" required>
                                <div class="p-4 rounded-xl border-2 border-slate-200 hover:border-blue-400 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-bold text-slate-800 text-sm">Setujui Permohonan</h6>
                                            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wide">Teruskan ke Lurah</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-4 right-4 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity transform peer-checked:scale-110">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                            </label>

                            <!-- Reject Card -->
                            <label class="relative cursor-pointer group">
                                <input type="radio" id="reject" name="action" value="reject" class="peer sr-only" required>
                                <div class="p-4 rounded-xl border-2 border-slate-200 hover:border-red-400 peer-checked:border-red-600 peer-checked:bg-red-50 transition-all shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-colors">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-bold text-slate-800 text-sm">Tolak Permohonan</h6>
                                            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wide">Kembalikan ke Pemohon</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute top-4 right-4 text-red-600 opacity-0 peer-checked:opacity-100 transition-opacity transform peer-checked:scale-110">
                                    <i class="fas fa-times-circle text-xl"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Input Nomor Surat (Muncul jika Approve dipilih) -->
                    <div id="approve-section" class="hidden space-y-4">
                        <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-6">
                            <h5 class="text-blue-800 font-bold mb-2 flex items-center text-sm uppercase">
                                <i class="fas fa-edit mr-2"></i> Draft Surat Kelurahan
                            </h5>
                            <p class="text-xs text-blue-600 mb-4 leading-relaxed">
                                Silakan periksa dan edit isi surat di bawah ini sebelum meneruskan ke Lurah. Pastikan nomor surat sudah sesuai.
                            </p>
                            
                            <!-- Nomor Surat -->
                            <div class="mb-4">
                                <label for="nomor_surat" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Nomor Surat
                                </label>
                                <input type="text" name="nomor_surat" id="nomor_surat" 
                                    class="w-full border border-slate-300 rounded-lg px-4 py-2 text-slate-800 font-medium focus:border-blue-500 focus:ring focus:ring-blue-100 transition-all shadow-sm"
                                    value="{{ $suggestedNomorSurat ?? '' }}">
                                <p class="text-[10px] text-slate-400 mt-1">Nomor surat akan otomatis terupdate di dalam dokumen preview di bawah.</p>
                            </div>

                            <!-- Editor Surat -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Isi Surat</label>
                                <textarea name="isi_surat" id="isi_surat" rows="15">{{ $defaultContent ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label for="catatan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2 text-slate-700 focus:border-blue-500 focus:ring focus:ring-blue-100 transition-all shadow-sm"
                            placeholder="Berikan catatan tambahan untuk pemohon atau Lurah..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-6 bg-slate-50 flex justify-between items-center rounded-b-2xl border-t border-slate-200">
                <a href="{{ route('kasi.permohonan.index') }}"
                    class="px-5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-600 font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition-all shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>

                <div class="flex items-center gap-3">
                    <button type="button" onclick="history.back()"
                        class="px-5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-600 font-bold text-sm hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all shadow-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30">
                        <i class="fas fa-paper-plane mr-2"></i>Proses & Kirim
                    </button>
                </div>
            </div>
        </form>

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