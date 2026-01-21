@extends('components.layout')

@section('page-title', 'Kebijakan Privasi')
@section('page-description', 'Informasi mengenai keamanan dan penggunaan data Anda.')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Header Section -->
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Kebijakan Privasi & Keamanan Data
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Komitmen Kelurahan Padang Jati dalam melindungi data pribadi dan dokumen warga.
                </p>
            </div>

            <!-- Content -->
            <div class="px-4 py-5 sm:p-6 space-y-8">
                
                <!-- Section 1 -->
                <div>
                    <h4 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold mr-2">1</span>
                        Pengumpulan Data
                    </h4>
                    <div class="ml-8 text-sm text-gray-600 space-y-2">
                        <p>Sistem Informasi Kelurahan (SI-KELURAHAN) hanya mengumpulkan data yang <strong>benar-benar diperlukan</strong> untuk pelayanan administrasi, meliputi:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Identitas Diri (Nama, NIK, KK) untuk verifikasi kependudukan.</li>
                            <li>Dokumen Pendukung (Foto KTP, Surat Pengantar RT) sebagai syarat layanan.</li>
                            <li>Kontak (Nomor HP/Email) untuk notifikasi status layanan.</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 2 -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <h4 class="text-base font-bold text-blue-900 mb-3 flex items-center">
                        <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
                        Keamanan Dokumen (Data Security)
                    </h4>
                    <div class="text-sm text-blue-800 space-y-2 ml-1">
                        <p>Kami menerapkan standar keamanan ketat untuk menjamin kerahasiaan dokumen anda:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Private Storage (Penyimpanan Privat):</strong> Seluruh dokumen kependudukan disimpan dalam server terisolasi yang tidak dapat diakses publik secara langsung.</li>
                            <li><strong>Akses Terbatas:</strong> Dokumen hanya dapat dibuka oleh <strong>Pemilik Data (Anda)</strong> dan <strong>Petugas Kelurahan</strong> yang berwenang memproses surat Anda. Pihak lain tidak memiliki akses.</li>
                            <li><strong>Enkripsi Transmisi:</strong> Pertukaran data dilindungi menggunakan protokol enkripsi standar.</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 3 -->
                <div>
                    <h4 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold mr-2">2</span>
                        Penggunaan Data
                    </h4>
                    <div class="ml-8 text-sm text-gray-600 space-y-2">
                        <p>Kami menjamin integritas penggunaan data anda:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Data hanya digunakan untuk memproses permohonan surat ketersediaan layanan publik.</li>
                            <li>Data <strong>TIDAK AKAN</strong> disebarluaskan, dijual, atau diberikan kepada pihak ketiga yang tidak berkepentingan tanpa persetujuan Anda atau perintah hukum yang sah.</li>
                            <li>Data arsip disimpan sebagai rekam jejak pelayanan pemerintah sesuai regulasi kearsipan negara.</li>
                        </ul>
                    </div>
                </div>

                 <!-- Section 4 -->
                 <div>
                    <h4 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold mr-2">3</span>
                        Hak Anda
                    </h4>
                    <div class="ml-8 text-sm text-gray-600 space-y-2">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Mengakses dan melihat kembali riwayat data yang pernah Anda kirimkan.</li>
                            <li>Memperbarui data profil jika terjadi perubahan (Pindah alamat, ganti status, dll).</li>
                            <li>Mengajukan pertanyaan atau keluhan terkait privasi melalui petugas pelayanan.</li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Footer Action Removed (Redundant with Sidebar) -->
        </div>
    </div>
</div>
@endsection
