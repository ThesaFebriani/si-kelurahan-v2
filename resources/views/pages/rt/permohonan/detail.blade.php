@extends('components.layout')

@section('title', 'Detail Permohonan - RT')
@section('page-title', 'Verifikasi Permohonan')
@section('page-description', 'Periksa detail permohonan surat warga')

@section('content')
    @php
        $dataPemohon = $permohonan->data_pemohon;
         // Handle jika string (legacy)
        if (is_string($dataPemohon)) {
            $decoded = json_decode($dataPemohon, true);
            $dataPemohon = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }
        $dataPemohon = is_array($dataPemohon) ? $dataPemohon : [];
        
        // Data Processing Logic
        $excludeCommon = ['tujuan', 'user_id', 'user_name', 'user_email', 'user_telepon', 'keterangan_tambahan', 'path_lampiran'];
        $mainFields = ['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'pekerjaan', 'agama', 'status_perkawinan', 'kewarganegaraan', 'alamat'];
        $allKeys = array_keys(is_array($dataPemohon) ? $dataPemohon : []);
        $dynamicFields = array_diff($allKeys, $mainFields, $excludeCommon);
        $displayKeys = array_merge($mainFields, $dynamicFields);
        
        // Fallback Mapping
        $user = $permohonan->user;
        $penduduk = $user->anggotaKeluarga;
        $userMapping = [
            'nama_lengkap' => $penduduk->nama_lengkap ?? $user->name,
            'nik' => $user->nik,
            'tempat_lahir' => $penduduk->tempat_lahir ?? $user->tempat_lahir,
            'tanggal_lahir' => $penduduk->tanggal_lahir ?? $user->tanggal_lahir,
            'pekerjaan' => $penduduk->pekerjaan ?? $user->pekerjaan,
            'jenis_kelamin' => $penduduk->jk ?? $user->jk,
            'agama' => $penduduk->agama ?? $user->agama,
            'alamat' => $penduduk && $penduduk->keluarga ? $penduduk->keluarga->alamat : $user->alamat_lengkap,
            'status_perkawinan' => $penduduk->status_perkawinan ?? $user->status_perkawinan,
            'kewarganegaraan' => $penduduk->kewarganegaraan ?? $user->kewarganegaraan,
        ];
        
        // Merge into dataPemohon if key missing or empty
        foreach($userMapping as $k => $v) {
            if((empty($dataPemohon[$k]) || $dataPemohon[$k] === '-') && !empty($v)) {
                $dataPemohon[$k] = $v;
            }
        }
    @endphp

    <div class="space-y-6">
        <!-- Header Page & Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-md text-[10px] font-bold uppercase tracking-wide">
                        {{ $permohonan->jenisSurat->bidang_display }}
                    </span>
                    <span class="text-slate-300 text-sm">|</span>
                    <span class="text-slate-500 font-mono text-sm font-medium bg-slate-50 px-2 py-0.5 rounded border border-slate-200">{{ $permohonan->nomor_tiket }}</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $permohonan->jenisSurat->name }}</h2>
                <p class="text-sm text-slate-500 mt-1 flex items-center">
                    <i class="fas fa-clock mr-1.5 text-slate-400"></i>
                    Diajukan oleh <span class="font-bold text-slate-700 mx-1">{{ $permohonan->user->name }}</span> 
                    pada <span class="text-slate-600 ml-1">{{ $permohonan->created_at->format('d F Y • H:i') }}</span>
                </p>
            </div>

            <div class="flex items-center gap-4">
                 <div class="text-right hidden md:block">
                    <span class="block text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Status Saat Ini</span>
                     <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border 
                        @if($permohonan->status == 'menunggu_rt') bg-yellow-50 text-yellow-700 border-yellow-200
                        @elseif(str_contains($permohonan->status, 'disetujui')) bg-green-50 text-green-700 border-green-200
                        @elseif(str_contains($permohonan->status, 'ditolak')) bg-red-50 text-red-700 border-red-200
                        @else bg-blue-50 text-blue-700 border-blue-200 @endif">
                        {{ $permohonan->status_display }}
                    </div>
                </div>
                
                @if($permohonan->isMenungguRT())
                <a href="{{ route('rt.permohonan.approve', $permohonan->id) }}" 
                   class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold shadow-lg shadow-green-600/20 transition-all transform hover:-translate-y-0.5 flex items-center">
                    <i class="fas fa-pen-to-square mr-2"></i> Proses Permohonan
                </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Data Pemohon (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                 <!-- Card Data Diri -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 flex items-center text-sm">
                        <i class="fas fa-user-check text-blue-500 mr-2.5"></i> Data Pemohon
                    </h3>
                    <span class="text-[10px] font-bold uppercase text-slate-400 bg-white border border-slate-200 px-2 py-0.5 rounded tracking-wider">Read-Only</span>
                </div>
                <div class="p-6">
                    @php
                        // Helper for grid layout logic
                        $colSpans = [
                            'alamat' => 'col-span-12',
                            'tujuan' => 'col-span-12',
                            'rt' => 'col-span-6 md:col-span-2 lg:col-span-2',
                            'rw' => 'col-span-6 md:col-span-2 lg:col-span-2',
                            'penghasilan' => 'col-span-12 md:col-span-4 lg:col-span-4',
                            'jumlah_tanggungan' => 'col-span-12 md:col-span-4 lg:col-span-4',
                            'keterangan_lain' => 'col-span-12',
                        ];
                    @endphp

                     <div class="grid grid-cols-12 gap-x-5 gap-y-5">
                        @foreach($displayKeys as $key)
                            @if(isset($dataPemohon[$key]) && !empty($dataPemohon[$key]) && !is_array($dataPemohon[$key]))
                                @if(in_array($key, $excludeCommon)) @continue @endif
                                
                                @php
                                    $spanClass = $colSpans[$key] ?? 'col-span-12 md:col-span-6';
                                @endphp

                                <div class="{{ $spanClass }}">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block pl-0.5">
                                        {{ str_replace('_', ' ', $key) }}
                                    </label>
                                    <div class="bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-slate-700 font-bold text-sm truncate w-full shadow-sm shadow-slate-100" title="{{ $dataPemohon[$key] }}">
                                        @if($key === 'tanggal_lahir')
                                            {{ \Carbon\Carbon::parse($dataPemohon[$key])->format('d F Y') }}
                                        @elseif($key === 'jenis_kelamin')
                                            {{ $dataPemohon[$key] == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        @else
                                            {{ $dataPemohon[$key] }}
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @if(isset($dataPemohon['tujuan']))
                    <div class="mt-6 pt-6 border-t border-slate-100">
                         <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block pl-0.5">
                            Tujuan Permohonan
                        </label>
                        <div class="bg-blue-50/50 border border-blue-100 rounded-lg p-4 text-slate-700 font-medium text-sm leading-relaxed shadow-sm shadow-blue-50">
                            {{ $dataPemohon['tujuan'] }}
                        </div>
                    </div>
                    @endif
                </div>
                </div>

                 <!-- Card Lampiran -->
                 @if($permohonan->lampirans->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center">
                            <i class="fas fa-paperclip text-orange-500 mr-2"></i> Berkas Lampiran
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($permohonan->lampirans as $lampiran)
                        <div class="flex items-center p-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="w-10 h-10 bg-red-100 text-red-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $lampiran->nama_file }}</p>
                                <p class="text-xs text-slate-500">{{ number_format($lampiran->file_size / 1024, 0) }} KB</p>
                            </div>
                             @if($lampiran->file_path)
                            <a href="{{ route('documents.show', ['filename' => basename($lampiran->file_path)]) }}" target="_blank"
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

            <!-- Right Column: Timeline & Info (1/3) -->
            <div class="space-y-6">
                
                <!-- Timeline Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800">Riwayat Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6 border-l-2 border-dashed border-slate-200 ml-3 pl-6 relative">
                            @foreach($permohonan->timeline->sortByDesc('created_at')->take(5) as $timeline)
                            <div class="relative">
                                <div class="absolute -left-[31px] top-1 w-4 h-4 rounded-full border-2 border-white 
                                    {{ str_contains($timeline->keterangan, 'Disetujui') ? 'bg-green-500' : (str_contains($timeline->keterangan, 'Ditolak') ? 'bg-red-500' : 'bg-blue-500') }} shadow-sm"></div>
                                <p class="text-xs text-slate-500 mb-0.5">{{ $timeline->created_at->format('d M, H:i') }}</p>
                                <p class="text-sm font-bold text-slate-800">{{ $timeline->status_display }}</p>
                                <p class="text-xs text-slate-600 mt-1 bg-slate-50 p-2 rounded border border-slate-100">{{ $timeline->keterangan }}</p>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-100 text-center">
                            <a href="#" class="text-xs text-blue-600 hover:underline font-medium">Lihat Semua Riwayat</a>
                        </div>
                    </div>
                </div>
                
                 <a href="{{ route('rt.permohonan.index') }}" class="flex items-center justify-center w-full p-3 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 hover:text-slate-800 transition-colors shadow-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>

            </div>
        </div>
    </div>
@endsection