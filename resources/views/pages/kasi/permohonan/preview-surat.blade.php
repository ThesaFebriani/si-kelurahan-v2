@extends('components.layout')

@section('title', 'Preview Surat - Kasi')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-file-alt text-purple-600 mr-2"></i>
                Preview Surat
            </h3>
            <div class="flex space-x-2">
                @if($permohonan->isMenungguLurah())
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Draft (Menunggu TTD Lurah)</span>
                @else
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Final (Selesai)</span>
                @endif
                <button onclick="window.close()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="p-6 bg-gray-50">
            <!-- Informasi Singkat -->
            <div class="mb-6 bg-white p-4 rounded border border-gray-200 text-sm">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-500 block">Nomor Surat:</span>
                        <span class="font-medium">{{ $permohonan->surat->nomor_surat ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Pemohon:</span>
                        <span class="font-medium">{{ $permohonan->user->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Konten Surat -->
            <div class="bg-white p-8 shadow-sm border border-gray-200 min-h-[800px]">
                {!! $suratContent !!}
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 bg-gray-50 flex justify-between">
            <a href="{{ url()->previous() }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
                Kembali
            </a>
            @if($permohonan->isMenungguLurah())
            <a href="{{ route('kasi.permohonan.draft', $permohonan->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <i class="fas fa-edit mr-1"></i> Edit Draft
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
