@extends('components.layout')

@section('title', 'Detail Permohonan - Sistem Kelurahan')
@section('page-title', 'Detail Permohonan')
@section('page-description', 'Pantau status dan detail permohonan surat Anda')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    
    <!-- Header Banner -->
    <div class="relative bg-gradient-to-r from-blue-700 to-indigo-800 rounded-2xl p-8 shadow-lg overflow-hidden text-white">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                 <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold uppercase tracking-wider border border-white/20">
                        {{ $permohonan->jenisSurat->bidang_display }}
                    </span>
                    <span class="flex items-center text-sm font-medium opacity-90">
                        <i class="far fa-calendar-alt mr-1.5"></i> Diajukan: {{ $permohonan->created_at->format('d M Y') }}
                    </span>
                </div>
                <h2 class="text-3xl font-bold mb-1">{{ $permohonan->jenisSurat->name }}</h2>
                <div class="flex items-center space-x-2 text-blue-100 font-mono text-sm">
                    <span class="opacity-70">No. Tiket:</span>
                    <span class="bg-white/10 px-2 py-0.5 rounded text-white font-semibold tracking-wide">{{ $permohonan->nomor_tiket }}</span>
                </div>
            </div>

             <div class="flex flex-col items-start md:items-end">
                <span class="text-blue-200 text-sm mb-1">Status Terkini</span>
                @php
                    $statusConfig = match($permohonan->status) {
                        'menunggu_rt' => ['color' => 'bg-yellow-400 text-yellow-900', 'icon' => 'fa-clock', 'text' => 'Menunggu Verifikasi RT'],
                        'disetujui_rt' => ['color' => 'bg-blue-400 text-blue-900', 'icon' => 'fa-check', 'text' => 'Disetujui RT'],
                        'ditolak_rt' => ['color' => 'bg-red-500 text-white', 'icon' => 'fa-times', 'text' => 'Ditolak RT'],
                        'menunggu_kasi' => ['color' => 'bg-orange-400 text-orange-900', 'icon' => 'fa-hourglass-half', 'text' => 'Verifikasi Kasi'],
                        'disetujui_kasi' => ['color' => 'bg-indigo-400 text-indigo-900', 'icon' => 'fa-check-double', 'text' => 'Disetujui Kasi'],
                        'menunggu_lurah' => ['color' => 'bg-purple-400 text-purple-900', 'icon' => 'fa-signature', 'text' => 'Menunggu TTE Lurah'],
                        'selesai' => ['color' => 'bg-green-400 text-green-900', 'icon' => 'fa-check-circle', 'text' => 'Selesai Diterbitkan'],
                         default => ['color' => 'bg-gray-400 text-gray-900', 'icon' => 'fa-question', 'text' => 'Status Tidak Diketahui'],
                    };
                @endphp
                <div class="px-4 py-2 rounded-lg {{ $statusConfig['color'] }} font-bold shadow-sm flex items-center">
                    <i class="fas {{ $statusConfig['icon'] }} mr-2"></i> {{ $statusConfig['text'] }}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Tracking & Download -->
        <div class="space-y-6">
            
            <!-- Download Card (If Done) -->
            @if($permohonan->status === 'selesai' && $permohonan->surat && $permohonan->surat->file_path)
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200 shadow-sm text-center">
                 <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <i class="fas fa-file-check text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-green-900 mb-2">Surat Siap Diunduh!</h3>
                <p class="text-green-700 text-sm mb-6">Dokumen resmi Anda telah diterbitkan dan ditandatangani secara elektronik.</p>
                <a href="{{ Storage::url($permohonan->surat->file_path) }}" target="_blank" 
                   class="block w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-green-600/20 transform hover:-translate-y-1">
                    <i class="fas fa-download mr-2"></i> Unduh Surat PDF
                </a>
            </div>
            @endif

            <!-- Timeline Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/50 p-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <i class="fas fa-history text-blue-500 mr-2"></i> Riwayat Proses
                    </h3>
                </div>
                <div class="p-6">
                     <div class="space-y-6 relative before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                        @foreach($permohonan->timeline->sortByDesc('created_at') as $index => $timeline)
                            <div class="relative flex items-start group">
                                <div class="absolute left-0 top-1 h-5 w-5 rounded-full border-2 border-white bg-white shadow-sm flex items-center justify-center z-10">
                                    <div class="h-2.5 w-2.5 rounded-full {{ $index === 0 ? 'bg-blue-500 animate-pulse' : 'bg-gray-300' }}"></div>
                                </div>
                                <div class="ml-8 w-full">
                                    <p class="text-sm font-bold text-gray-800">{{ $timeline->status_display }}</p>
                                    <p class="text-xs text-gray-500 mb-1 flex items-center">
                                        <i class="far fa-clock mr-1 text-[10px]"></i> {{ $timeline->created_at->format('d M Y, H:i') }}
                                    </p>
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 text-xs text-gray-600">
                                        {{ $timeline->keterangan }}
                                        <div class="mt-1 pt-1 border-t border-gray-200 text-[10px] text-gray-400 font-medium">
                                            Oleh: {{ $timeline->updatedBy->name ?? 'Sistem' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <a href="{{ route('masyarakat.permohonan.index') }}" class="flex items-center justify-center w-full p-3 text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-gray-700 transition-colors shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>

        </div>

        <!-- Right Column: Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Data Pemohon Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/50 p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Data Pemohon & Isian Surat</h3>
                        <p class="text-sm text-gray-500">Informasi lengkap data diri dan isian formulir</p>
                    </div>
                </div>
                <div class="p-6">
                     @php
                        $data = $permohonan->data_pemohon;
                        // Fields that are handled separately or should be hidden
                        $excludeCommon = ['tujuan', 'user_id', 'user_name', 'user_email', 'user_telepon', 'keterangan_tambahan'];
                        
                        // Standard fields order (Will be shown first)
                        $mainFields = ['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'status_perkawinan', 'alamat'];
                        
                        // Collect all keys from data
                        $allKeys = array_keys(is_array($data) ? $data : []);
                        
                        // Identify dynamic fields (those in data but not in mainFields and not excluded/lampiran)
                        $dynamicFields = array_diff($allKeys, $mainFields, $excludeCommon);
                        
                        // Merge logic: Main fields first, then dynamic fields
                        $displayKeys = array_merge($mainFields, $dynamicFields);
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                         @foreach($displayKeys as $field)
                            @if(isset($data[$field]) && !empty($data[$field]) && !is_array($data[$field]))
                                {{-- Skip if it's explicitly excluded (double check) --}}
                                @if(in_array($field, $excludeCommon)) @continue @endif

                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                                        {{ str_replace('_', ' ', $field) }}
                                    </label>
                                    <p class="text-gray-800 font-medium border-b border-gray-100 pb-2">
                                        @if($field == 'tanggal_lahir')
                                            {{ \Carbon\Carbon::parse($data[$field])->format('d F Y') }}
                                        @elseif($field == 'jenis_kelamin')
                                            {{ $data[$field] == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        @else
                                            {{ $data[$field] }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

             <!-- Tujuan Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                        <i class="fas fa-bullseye mr-1 text-purple-500"></i> Tujuan Permohonan
                    </label>
                    <div class="bg-purple-50 rounded-xl p-4 text-purple-900 text-sm leading-relaxed border border-purple-100">
                        {{ $data['tujuan'] ?? '-' }}
                    </div>

                    @if(!empty($data['keterangan_tambahan']))
                     <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">
                        <i class="fas fa-info-circle mr-1 text-blue-500"></i> Keterangan Tambahan
                    </label>
                    <div class="bg-blue-50 rounded-xl p-4 text-blue-900 text-sm leading-relaxed border border-blue-100">
                        {{ $data['keterangan_tambahan'] }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Lampiran Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/50 p-6 border-b border-gray-100">
                     <h3 class="font-bold text-gray-800 text-lg">Lampiran Dokumen</h3>
                </div>
                <div class="p-6">
                     <div class="space-y-3">
                        @php
                            $lampiranFound = false;
                        @endphp

                         @if(is_array($data) || is_object($data))
                            @foreach($data as $key => $value)
                                @if(is_array($value) && isset($value['path']) && !empty($value['path']))
                                    @php $lampiranFound = true; @endphp
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 group hover:border-blue-300 transition-colors">
                                        <div class="flex items-center overflow-hidden">
                                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm text-red-500 mr-3 flex-shrink-0">
                                                <i class="fas fa-file-pdf text-xl"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-gray-800 truncate">
                                                    {{ Str::title(str_replace('_', ' ', $key)) }}
                                                </p>
                                                <p class="text-xs text-gray-500 truncate">{{ $value['original_name'] ?? 'Dokumen' }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/'.$value['path']) }}" target="_blank" 
                                            class="px-3 py-1.5 bg-blue-100 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-200 transition-colors flex-shrink-0">
                                            Lihat <i class="fas fa-external-link-alt ml-1"></i>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        @if(!$lampiranFound)
                            <div class="text-center py-6 text-gray-400">
                                <i class="fas fa-folder-open text-3xl mb-2 opacity-50"></i>
                                <p>Tidak ada lampiran dokumen.</p>
                            </div>
                        @endif
                     </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection