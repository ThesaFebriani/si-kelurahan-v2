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
                <!-- Render Dynamic Content from Controller/Database -->
                <div class="prose max-w-none">
                    {!! $content !!}
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