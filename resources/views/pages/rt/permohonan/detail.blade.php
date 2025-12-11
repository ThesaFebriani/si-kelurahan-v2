@extends('components.layout')

@section('title', 'Detail Permohonan - RT')
@section('page-title', 'Verifikasi Permohonan')
@section('page-description', 'Periksa detail permohonan surat warga')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <div class="flex items-center space-x-3 mb-1">
                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-bold uppercase tracking-wide">
                    {{ $permohonan->jenisSurat->bidang_display }}
                </span>
                <span class="text-gray-400 text-sm">|</span>
                <span class="text-gray-500 font-mono text-sm">{{ $permohonan->nomor_tiket }}</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $permohonan->jenisSurat->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">
                Diajukan oleh <span class="font-semibold text-gray-700">{{ $permohonan->user->name }}</span> 
                pada {{ $permohonan->created_at->format('d F Y H:i') }}
            </p>
        </div>

        <div class="flex items-center space-x-3">
             <div class="text-right mr-2 hidden md:block">
                <span class="block text-xs text-gray-400 uppercase font-semibold">Status Saat Ini</span>
                 <span class="block font-bold 
                    @if($permohonan->status == 'menunggu_rt') text-yellow-600
                    @elseif(str_contains($permohonan->status, 'disetujui')) text-green-600
                    @elseif(str_contains($permohonan->status, 'ditolak')) text-red-600
                    @else text-blue-600 @endif">
                    {{ $permohonan->status_display }}
                </span>
            </div>
            
            @if($permohonan->isMenungguRT())
            <a href="{{ route('rt.permohonan.approve', $permohonan->id) }}" 
               class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold shadow-lg shadow-green-500/30 transition-all transform hover:-translate-y-0.5 flex items-center">
                <i class="fas fa-check-circle mr-2"></i> Proses Permohonan
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
                        <i class="fas fa-user-check text-blue-500 mr-2"></i> Data Pemohon
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $dataPemohon = $permohonan->data_pemohon;
                         // Handle jika string (legacy)
                        if (is_string($dataPemohon)) {
                            $decoded = json_decode($dataPemohon, true);
                            $dataPemohon = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
                        }
                        $dataPemohon = is_array($dataPemohon) ? $dataPemohon : [];
                         
                        // Kunci prioritas
                        $priorityKeys = ['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'pekerjaan', 'agama', 'status_perkawinan', 'kewarganegaraan', 'alamat'];
                    @endphp

                     <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        @php
                            // Fields handled separately or hidden
                            $excludeCommon = ['tujuan', 'user_id', 'user_name', 'user_email', 'user_telepon', 'keterangan_tambahan', 'path_lampiran'];
                            
                            // Standard fields order
                            $mainFields = ['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'pekerjaan', 'agama', 'status_perkawinan', 'kewarganegaraan', 'alamat'];
                            
                            // Get all keys
                            $allKeys = array_keys(is_array($dataPemohon) ? $dataPemohon : []);
                            
                            // Dynamic fields
                            $dynamicFields = array_diff($allKeys, $mainFields, $excludeCommon);
                            
                            // Merged fields
                            $displayKeys = array_merge($mainFields, $dynamicFields);
                            
                            // fallback mapping from User model
                            $userMapping = [
                                'nama_lengkap' => $permohonan->user->name,
                                'nik' => $permohonan->user->nik,
                                'tempat_lahir' => $permohonan->user->tempat_lahir,
                                'tanggal_lahir' => $permohonan->user->tanggal_lahir,
                                'pekerjaan' => $permohonan->user->pekerjaan,
                                'jenis_kelamin' => $permohonan->user->jk,
                                'agama' => $permohonan->user->agama,
                                'alamat' => $permohonan->user->alamat_lengkap, // Assumed accessor
                                'status_perkawinan' => $permohonan->user->status_perkawinan,
                                'kewarganegaraan' => $permohonan->user->kewarganegaraan,
                            ];
                            
                            // Merge into dataPemohon if key missing or empty
                            foreach($userMapping as $k => $v) {
                                if((empty($dataPemohon[$k]) || $dataPemohon[$k] === '-') && !empty($v)) {
                                    $dataPemohon[$k] = $v;
                                }
                            }
                        @endphp

                        @foreach($displayKeys as $key)
                            @if(isset($dataPemohon[$key]) && !empty($dataPemohon[$key]) && !is_array($dataPemohon[$key]))
                                @if(in_array($key, $excludeCommon)) @continue @endif
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                                        {{ str_replace('_', ' ', $key) }}
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
                    
                    @if(isset($dataPemohon['tujuan']))
                    <div class="mt-6 pt-6 border-t border-gray-100">
                         <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                            Tujuan Permohonan
                        </label>
                        <div class="bg-blue-50 text-blue-900 p-4 rounded-xl text-sm leading-relaxed border border-blue-100">
                            {{ $dataPemohon['tujuan'] }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Card Lampiran -->
             @if($permohonan->lampirans->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <i class="fas fa-paperclip text-orange-500 mr-2"></i> Berkas Lampiran
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($permohonan->lampirans as $lampiran)
                    <div class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-red-100 text-red-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $lampiran->nama_file }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($lampiran->file_size / 1024, 0) }} KB</p>
                        </div>
                         @if($lampiran->file_path)
                        <a href="{{ Storage::url($lampiran->file_path) }}" target="_blank"
                           class="ml-2 p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Lihat File">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
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
                                {{ str_contains($timeline->keterangan, 'Disetujui') ? 'bg-green-500' : (str_contains($timeline->keterangan, 'Ditolak') ? 'bg-red-500' : 'bg-blue-500') }} shadow-sm"></div>
                            <p class="text-xs text-gray-500 mb-0.5">{{ $timeline->created_at->format('d M, H:i') }}</p>
                            <p class="text-sm font-bold text-gray-800">{{ $timeline->status_display }}</p>
                            <p class="text-xs text-gray-600 mt-1 bg-gray-50 p-2 rounded border border-gray-100">{{ $timeline->keterangan }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                        <a href="#" class="text-xs text-blue-600 hover:underline font-medium">Lihat Semua Riwayat</a>
                    </div>
                </div>
            </div>
            
             <a href="{{ route('rt.permohonan.index') }}" class="flex items-center justify-center w-full p-3 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 hover:text-gray-800 transition-colors shadow-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>

        </div>
    </div>
</div>
@endsection