@extends('components.layout')

@section('title', 'Detail Permohonan - Lurah')
@section('page-title', 'Tanda Tangan Elektronik')
@section('page-description', 'Validasi akhir dan tanda tangan surat')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <div class="flex items-center space-x-3 mb-1">
                <span class="px-2.5 py-1 bg-purple-100 text-purple-700 rounded-md text-xs font-bold uppercase tracking-wide">
                    {{ $permohonan->jenisSurat->bidang_display }}
                </span>
                <span class="text-gray-400 text-sm">|</span>
                <span class="text-gray-500 font-mono text-sm">{{ $permohonan->nomor_tiket }}</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $permohonan->jenisSurat->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">
                Diajukan oleh <span class="font-semibold text-gray-700">{{ $permohonan->user->name }}</span> 
                | RT {{ $permohonan->user->rt->nomor_rt ?? '-' }} / RW {{ $permohonan->user->rt->rw->nomor_rw ?? '-' }}
            </p>
        </div>

        <div class="flex items-center space-x-3">
             <div class="text-right mr-2 hidden md:block">
                <span class="block text-xs text-gray-400 uppercase font-semibold">Status Saat Ini</span>
                 <span class="block font-bold 
                    @if(str_contains($permohonan->status, 'menunggu_lurah')) text-purple-600
                    @elseif(str_contains($permohonan->status, 'selesai')) text-green-600
                    @else text-gray-600 @endif">
                    {{ $permohonan->status_display }}
                </span>
            </div>
            
            @if($permohonan->isMenungguLurah())
            <a href="{{ route('lurah.tanda-tangan.sign', $permohonan->id) }}" 
               class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold shadow-lg shadow-purple-500/30 transition-all transform hover:-translate-y-0.5 flex items-center">
                <i class="fas fa-file-signature mr-2"></i> Tanda Tangani Surat
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Data Pemohon -->
        <div class="lg:col-span-2 space-y-6">
             <!-- Card Data Diri -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <i class="fas fa-user-check text-purple-600 mr-2"></i> Data Pemohon
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $dataPemohon = $permohonan->data_pemohon;
                        if (is_string($dataPemohon)) {
                            $decoded = json_decode($dataPemohon, true);
                            $dataPemohon = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
                        }
                        $dataPemohon = is_array($dataPemohon) ? $dataPemohon : [];
                        $priorityKeys = ['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'pekerjaan', 'agama', 'alamat'];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        @foreach($priorityKeys as $key)
                            @if(isset($dataPemohon[$key]))
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                </label>
                                <p class="text-gray-800 font-medium border-b border-gray-50 pb-1">
                                    @if($key === 'tanggal_lahir')
                                        {{ \Carbon\Carbon::parse($dataPemohon[$key])->format('d F Y') }}
                                    @elseif($key === 'jenis_kelamin')
                                        {{ $dataPemohon[$key] == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    @else
                                        {{ $dataPemohon[$key] }}
                                    @endif
                                </p>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Preview Surat Sementara (Jika ada/perlu ditampilkan) -->
             <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <i class="fas fa-file-contract text-blue-500 mr-2"></i> Draft Surat
                    </h3>
                </div>
                <div class="p-8 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-800 text-lg mb-2">Pratinjau Surat</h4>
                    <p class="text-gray-500 text-sm max-w-md mb-6">
                        Silakan periksa draft surat sebelum melakukan tanda tangan elektronik. Pastikan semua data sudah benar.
                    </p>
                    <a href="{{ route('lurah.tanda-tangan.sign', $permohonan->id) }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                        <i class="fas fa-eye mr-2"></i> Lihat Draft Surat
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column: Timeline & Info -->
        <div class="space-y-6">
            
            <!-- Timeline Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Riwayat Status</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6 border-l-2 border-dashed border-gray-200 ml-3 pl-6 relative">
                        @foreach($permohonan->timeline->sortByDesc('created_at')->take(5) as $timeline)
                        <div class="relative">
                            <div class="absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 border-white 
                                {{ str_contains($timeline->keterangan, 'selesai') ? 'bg-green-500' : 'bg-purple-500' }} shadow-sm"></div>
                            <p class="text-xs text-gray-500 mb-0.5">{{ $timeline->created_at->format('d M, H:i') }}</p>
                            <p class="text-sm font-bold text-gray-800">{{ $timeline->status_display }}</p>
                            <p class="text-xs text-gray-600 mt-1 bg-gray-50 p-2 rounded border border-gray-100">{{ $timeline->keterangan }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
             <a href="{{ route('lurah.permohonan.index') }}" class="flex items-center justify-center w-full p-3 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 hover:text-gray-800 transition-colors shadow-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>

        </div>
    </div>
</div>
@endsection