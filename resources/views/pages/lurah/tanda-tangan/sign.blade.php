@extends('components.layout')

@section('title', 'Tanda Tangan Elektronik - Lurah')
@section('page-title', 'Tanda Tangan Elektronik')

@section('content')
<div class="">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Tanda Tangan Elektronik
                    </h2>
                    <a href="{{ route('lurah.tanda-tangan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Kembali
                    </a>
                </div>

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Preview Surat -->
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <h3 class="text-md font-semibold mb-4 text-center">Preview Surat</h3>
                        <div class="bg-white p-8 shadow-sm border min-h-[500px] text-sm leading-relaxed">
                            @if($permohonan->surat && $permohonan->surat->isi_surat)
                                {!! $permohonan->surat->isi_surat !!}
                            @else
                                <p class="text-center text-gray-500 mt-20">Konten surat belum tersedia</p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Panel -->
                    <div>
                        <div class="bg-white p-6 rounded-lg border shadow-sm">
                            <h3 class="text-md font-semibold mb-4">Konfirmasi Tanda Tangan</h3>
                            
                            <dl class="mb-6 space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Nomor Surat:</dt>
                                    <dd class="font-medium">{{ $permohonan->surat->nomor_surat ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Pemohon:</dt>
                                    <dd class="font-medium">{{ $permohonan->user->name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Jenis Surat:</dt>
                                    <dd class="font-medium">{{ $permohonan->jenisSurat->name }}</dd>
                                </div>
                            </dl>

                            <form action="{{ route('lurah.tanda-tangan.process', $permohonan->id) }}" method="POST">
                                @csrf
                                
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Passphrase / PIN Keamanan
                                    </label>
                                    <input type="password" name="passphrase" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan Passphrase (Opsional Demo)" >
                                    <p class="text-xs text-gray-500 mt-1">
                                        Dengan menekan tombol di bawah, Anda menyatakan setuju menandatangani dokumen ini secara elektronik.
                                    </p>
                                </div>

                                <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                    <i class="fas fa-file-signature mr-2"></i> Tanda Tangani Dokumen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
