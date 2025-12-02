// resources/views/pages/rt/preview-surat-pengantar.blade.php
@extends('components.layout')

@section('title', 'Preview Surat Pengantar - RT')
@section('page-title', 'Preview Surat Pengantar RT')
@section('page-description', 'Preview surat pengantar sebelum generate PDF')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                Preview Surat Pengantar RT
            </h3>
            <p class="text-gray-600 mt-1">
                Nomor Tiket: <code class="bg-gray-100 px-2 py-1 rounded">{{ $permohonan->nomor_tiket }}</code>
            </p>
        </div>

        <!-- Preview Section -->
        <div class="p-6 border-b border-gray-200">
            <div class="bg-gray-50 border border-gray-300 rounded-lg p-6">
                <!-- Kop Surat -->
                <div class="text-center mb-6">
                    <h1 class="text-lg font-bold uppercase">Pemerintah Kelurahan</h1>
                    <h2 class="text-md font-semibold">Rukun Tetangga (RT) {{ Auth::user()->rt->nomor_rt }}</h2>
                    <p class="text-sm">RW {{ Auth::user()->rt->rw->nomor_rw }}</p>
                    <div class="border-t-2 border-black my-3"></div>
                </div>

                <!-- Nomor Surat -->
                <div class="text-right mb-6">
                    <p class="font-semibold">Nomor : {{ $nomorSurat }}</p>
                    <p>Tanggal : {{ now()->format('d F Y') }}</p>
                </div>

                <!-- Perihal -->
                <div class="mb-6">
                    <p class="font-semibold">Perihal : Surat Pengantar</p>
                    <p class="font-semibold">Lampiran : -</p>
                </div>

                <!-- Salam Pembuka -->
                <div class="mb-6">
                    <p>Kepada Yth.</p>
                    <p class="font-semibold">Kepala Seksi {{ $permohonan->jenisSurat->bidang_display }}</p>
                    <p>di</p>
                    <p class="font-semibold">Kelurahan Contoh</p>
                </div>

                <!-- Isi Surat -->
                <div class="mb-6 leading-relaxed">
                    <p class="mb-4">Dengan hormat,</p>

                    <p class="mb-4">
                        Yang bertanda tangan di bawah ini Ketua RT {{ Auth::user()->rt->nomor_rt }},
                        menerangkan bahwa:
                    </p>

                    <div class="ml-6 mb-4">
                        <p>Nama : <span class="font-semibold">{{ $permohonan->user->name }}</span></p>
                        <p>NIK : <span class="font-semibold">{{ $permohonan->data_pemohon['nik'] ?? '-' }}</span></p>
                        <p>Alamat : <span class="font-semibold">{{ $permohonan->user->alamat_lengkap }}</span></p>
                        <p>Jenis Surat : <span class="font-semibold">{{ $permohonan->jenisSurat->name }}</span></p>
                    </div>

                    <p class="mb-4">
                        Adalah benar warga RT {{ Auth::user()->rt->nomor_rt }} dan berdasarkan data yang ada,
                        yang bersangkutan membutuhkan {{ $permohonan->jenisSurat->name }} untuk keperluan:
                    </p>

                    <div class="bg-gray-100 p-4 rounded mb-4">
                        <p class="font-medium">{{ $permohonan->data_pemohon['tujuan'] ?? 'Keperluan administrasi' }}</p>
                    </div>

                    <p class="mb-4">
                        Demikian surat pengantar ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
                    </p>
                </div>

                <!-- Tanda Tangan -->
                <div class="mt-12 text-center">
                    <p>Hormat kami,</p>
                    <p class="font-semibold mt-8">Ketua RT {{ Auth::user()->rt->nomor_rt }}</p>
                    <div class="mt-16">
                        <p class="font-semibold border-t border-black pt-2 inline-block">
                            {{ Auth::user()->name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Action -->
        <form action="{{ route('rt.permohonan.process', $permohonan->id) }}" method="POST">
            @csrf
            <div class="p-6 border-b border-gray-200">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Surat Pengantar <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            name="nomor_surat_pengantar"
                            value="{{ $nomorSurat }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors"
                            required>
                        <p class="text-sm text-gray-500 mt-1">Format: 001/RT-01/IX/2024</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan"
                            rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors"
                            placeholder="Berikan catatan jika diperlukan..."></textarea>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Tindakan</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="approve" name="action" value="approve"
                                    class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300"
                                    required checked>
                                <label for="approve" class="ml-3 block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                        Setujui dan generate PDF
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="reject" name="action" value="reject"
                                    class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300"
                                    required>
                                <label for="reject" class="ml-3 block text-sm font-medium text-gray-700">
                                    <span class="flex items-center">
                                        <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                        Tolak permohonan
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-6 bg-gray-50 flex justify-between items-center">
                <a href="{{ route('rt.permohonan.detail', $permohonan->id) }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Detail
                </a>

                <div class="space-x-3">
                    <button type="button" onclick="history.back()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-file-pdf mr-2"></i>Proses & Generate PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Validasi sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const nomorSurat = document.querySelector('input[name="nomor_surat_pengantar"]').value;
        const action = document.querySelector('input[name="action"]:checked');

        if (!action) {
            e.preventDefault();
            alert('Pilih tindakan terlebih dahulu!');
            return false;
        }

        if (!nomorSurat.trim() && action.value === 'approve') {
            e.preventDefault();
            alert('Nomor surat pengantar harus diisi!');
            return false;
        }
    });
</script>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection