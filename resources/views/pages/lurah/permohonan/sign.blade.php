@extends('components.layout')

@section('title', 'TTE Permohonan - Lurah')
@section('page-title', 'Tanda Tangan Elektronik')
@section('page-description', 'Proses tanda tangan elektronik untuk surat')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-signature text-purple-600 mr-2"></i>
                Tanda Tangan Elektronik
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

        <!-- Form TTE -->
        <form action="{{ route('lurah.permohonan.process', $permohonan->id) }}" method="POST" id="tteForm">
            @csrf
            @method('POST') {{-- Explicit method declaration --}}

            <div class="p-6 border-b border-gray-200">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Proses Tanda Tangan</h4>

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
                                        Setujui dan tanda tangani surat
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="reject" name="action" value="reject" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300" required>
                                <label for="reject" class="ml-3 block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                        Tolak dan kembalikan ke Kasi
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Surat (muncul hanya jika approve) -->
                    <div id="suratInfo" class="hidden space-y-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h5 class="font-medium text-blue-800 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informasi Surat
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nomor_surat" class="block text-sm font-medium text-gray-700">Nomor Surat *</label>
                                <input type="text" name="nomor_surat" id="nomor_surat"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="Contoh: 001/SK/VI/2024">
                            </div>
                            <div>
                                <label for="tanggal_surat" class="block text-sm font-medium text-gray-700">Tanggal Surat *</label>
                                <input type="date" name="tanggal_surat" id="tanggal_surat"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    value="{{ date('Y-m-d') }}">
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
                <a href="{{ route('lurah.permohonan.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>

                <div class="space-x-3">
                    <button type="button" onclick="history.back()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-signature mr-2"></i>Proses TTE
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('tteForm');
        const suratInfo = document.getElementById('suratInfo');
        const approveRadio = document.getElementById('approve');
        const rejectRadio = document.getElementById('reject');
        const submitBtn = document.getElementById('submitBtn');

        // Toggle surat info berdasarkan pilihan
        function toggleSuratInfo() {
            if (approveRadio.checked) {
                suratInfo.classList.remove('hidden');
                document.getElementById('nomor_surat').required = true;
                document.getElementById('tanggal_surat').required = true;
            } else {
                suratInfo.classList.add('hidden');
                document.getElementById('nomor_surat').required = false;
                document.getElementById('tanggal_surat').required = false;
            }
        }

        approveRadio.addEventListener('change', toggleSuratInfo);
        rejectRadio.addEventListener('change', toggleSuratInfo);

        // Form submission dengan debug
        form.addEventListener('submit', function(e) {
            console.log('Form submitted');

            const actionSelected = document.querySelector('input[name="action"]:checked');
            if (!actionSelected) {
                e.preventDefault();
                alert('Pilih tindakan TTE terlebih dahulu!');
                return false;
            }

            if (approveRadio.checked) {
                const nomorSurat = document.getElementById('nomor_surat').value;
                const tanggalSurat = document.getElementById('tanggal_surat').value;

                if (!nomorSurat || !tanggalSurat) {
                    e.preventDefault();
                    alert('Harap isi nomor surat dan tanggal surat!');
                    return false;
                }
            }

            // Show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        });
    });
</script>
@endsection