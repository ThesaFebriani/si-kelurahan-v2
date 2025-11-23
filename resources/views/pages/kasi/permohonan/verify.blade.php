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
                                        Setujui dan teruskan ke Lurah
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

                    <!-- Catatan -->
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Verifikasi (Opsional)
                        </label>
                        <textarea name="catatan" id="catatan" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors"
                            placeholder="Berikan catatan verifikasi atau alasan persetujuan/penolakan..."></textarea>
                        <p class="text-sm text-gray-500 mt-1">Catatan akan ditampilkan di timeline permohonan</p>
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
                        <i class="fas fa-paper-plane mr-2"></i>Proses Verifikasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');

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