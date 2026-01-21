@extends('components.layout')

@section('title', 'Detail Permohonan - Sistem Kelurahan')
@section('page-title', 'Detail Permohonan')
@section('page-description', 'Pantau status dan detail permohonan surat Anda')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Header Card (Clean Admin Style) -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
            <div>
                 <div class="flex items-center space-x-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-200">
                        {{ $permohonan->jenisSurat->bidang_display }}
                    </span>
                    <span class="text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-1"></i> {{ $permohonan->created_at->format('d M Y') }}
                    </span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $permohonan->jenisSurat->name }}</h2>
                <div class="flex items-center space-x-2 text-sm text-gray-500 font-mono">
                    <span>Tiket:</span>
                    <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-700 font-semibold">{{ $permohonan->nomor_tiket }}</span>
                </div>
            </div>

             <div class="flex flex-col items-start md:items-end">
                <span class="text-gray-500 text-xs uppercase tracking-wide mb-1 font-semibold">Status Terkini</span>
                @php
                    $statusConfig = match($permohonan->status) {
                        'menunggu_rt' => ['color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-clock', 'text' => 'Menunggu Verifikasi RT'],
                        'disetujui_rt' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => 'fa-check', 'text' => 'Disetujui RT'],
                        'ditolak_rt' => ['color' => 'bg-red-100 text-red-800', 'icon' => 'fa-times', 'text' => 'Ditolak RT'],
                        'menunggu_kasi' => ['color' => 'bg-purple-100 text-purple-800', 'icon' => 'fa-hourglass-half', 'text' => 'Verifikasi Kasi'],
                        'disetujui_kasi' => ['color' => 'bg-indigo-100 text-indigo-800', 'icon' => 'fa-check-double', 'text' => 'Disetujui Kasi'],
                        'menunggu_lurah' => ['color' => 'bg-pink-100 text-pink-800', 'icon' => 'fa-signature', 'text' => 'Menunggu TTE Lurah'],
                        'selesai' => ['color' => 'bg-green-100 text-green-800', 'icon' => 'fa-check-circle', 'text' => 'Selesai Diterbitkan'],
                         default => ['color' => 'bg-gray-100 text-gray-800', 'icon' => 'fa-question', 'text' => 'Status Tidak Diketahui'],
                    };
                @endphp
                <div class="px-3 py-1.5 rounded-full {{ $statusConfig['color'] }} font-semibold text-sm flex items-center border border-transparent">
                    <i class="fas {{ $statusConfig['icon'] }} mr-2"></i> {{ $statusConfig['text'] }}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Tracking & Download -->
        <div class="space-y-6">
            
            <!-- Download Card (If Done) -->
            @if($permohonan->status === 'selesai' && $permohonan->surat && $permohonan->surat->file_path)
            <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                <div class="text-center">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600 mb-4">
                        <i class="fas fa-file-check text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-green-900">Surat Siap Diunduh</h3>
                    <p class="text-green-700 text-sm mb-4">Dokumen resmi Anda telah diterbitkan.</p>
                    <a href="{{ route('documents.show', ['filename' => basename($permohonan->surat->file_path)]) }}" target="_blank" 
                       class="block w-full py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold transition-colors shadow-sm">
                        <i class="fas fa-download mr-1"></i> Unduh PDF
                    </a>
                </div>
            </div>
            @endif

            <!-- Survei Kepuasan Card (Jika Selesai) -->
            @if($permohonan->status === 'selesai')
                <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 flex items-center text-sm">
                            <i class="fas fa-star text-yellow-500 mr-2"></i> Survei Kepuasan
                        </h3>
                    </div>
                    <div class="p-5">
                        @if($permohonan->survei)
                            {{-- Sudah mengisi survei --}}
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-yellow-50 text-yellow-500 mb-3">
                                    <i class="fas fa-laugh-beam text-2xl"></i>
                                </div>
                                <h4 class="text-base font-bold text-gray-800">Terima Kasih!</h4>
                                <p class="text-gray-500 text-xs mb-3">Penilaian Anda:</p>
                                
                                <div class="flex justify-center items-center space-x-1 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-lg {{ $i <= $permohonan->survei->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                    @endfor
                                </div>
                                @if($permohonan->survei->kritik_saran)
                                <div class="text-xs text-gray-600 bg-gray-50 px-3 py-2 rounded border border-gray-100 italic">
                                    "{{ $permohonan->survei->kritik_saran }}"
                                </div>
                                @endif
                            </div>
                        @else
                            {{-- Belum mengisi survei --}}
                            <form action="{{ route('masyarakat.feedback.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="permohonan_id" value="{{ $permohonan->id }}">
                                
                                <div class="mb-4 text-center">
                                    <p class="text-gray-600 text-sm mb-3">Bagaimana pelayanan kami?</p>
                                    <div class="flex flex-row-reverse justify-center gap-1 group">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" class="peer hidden" required>
                                            <label for="star{{ $i }}" class="cursor-pointer text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 peer-hover:text-yellow-400 transition-colors px-1">
                                                <i class="fas fa-star text-2xl"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <textarea name="kritik_saran" rows="2" 
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-xs"
                                        placeholder="Kritik & saran (opsional)..."></textarea>
                                </div>

                                <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                    Kirim Penilaian
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Timeline Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 p-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center text-sm">
                        <i class="fas fa-history text-blue-600 mr-2"></i> Riwayat Proses
                    </h3>
                </div>
                <div class="p-5">
                     <div class="space-y-6 relative before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gray-200">
                        @foreach($permohonan->timeline->sortByDesc('created_at') as $index => $timeline)
                            <div class="relative flex items-start group">
                                <div class="absolute left-0 top-1 h-5 w-5 rounded-full border-2 border-white bg-white shadow-sm flex items-center justify-center z-10">
                                    <div class="h-2.5 w-2.5 rounded-full {{ $index === 0 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>
                                </div>
                                <div class="ml-8 w-full">
                                    <p class="text-sm font-bold text-gray-800">{{ $timeline->status_display }}</p>
                                    <p class="text-[10px] text-gray-500 mb-1">
                                        {{ $timeline->created_at->format('d M Y, H:i') }}
                                    </p>
                                    <div class="bg-gray-50 p-2.5 rounded border border-gray-100 text-xs text-gray-600">
                                        {{ $timeline->keterangan }}
                                        <div class="mt-1 pt-1 border-t border-gray-200 text-[10px] text-gray-400">
                                            Oleh: {{ $timeline->updatedBy->name ?? 'Sistem' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <a href="{{ route('masyarakat.permohonan.index') }}" class="flex items-center justify-center w-full p-2.5 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <!-- Right Column: Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Data Pemohon Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 p-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-800 text-sm">Data Isian Surat</h3>
                </div>
                <div class="p-6">
                     @php
                        $data = $permohonan->data_pemohon;
                        $excludeCommon = ['tujuan', 'user_id', 'user_name', 'user_email', 'user_telepon', 'keterangan_tambahan'];
                        $mainFields = ['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'status_perkawinan', 'alamat'];
                        $allKeys = array_keys(is_array($data) ? $data : []);
                        $dynamicFields = array_diff($allKeys, $mainFields, $excludeCommon);
                        $displayKeys = array_merge($mainFields, $dynamicFields);
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-8">
                         @foreach($displayKeys as $field)
                            @if(isset($data[$field]) && !empty($data[$field]) && !is_array($data[$field]))
                                @if(in_array($field, $excludeCommon)) @continue @endif

                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                                        {{ str_replace('_', ' ', $field) }}
                                    </label>
                                    <p class="text-gray-900 font-medium text-sm border-b border-gray-100 pb-1">
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
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                        Tujuan Permohonan
                    </label>
                    <div class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm border border-gray-200">
                        {{ $data['tujuan'] ?? '-' }}
                    </div>

                    @if(!empty($data['keterangan_tambahan']))
                     <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mt-5 mb-2">
                        Keterangan Tambahan
                    </label>
                    <div class="bg-gray-50 rounded-lg p-3 text-gray-800 text-sm border border-gray-200">
                        {{ $data['keterangan_tambahan'] }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Lampiran Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 p-4 border-b border-gray-200">
                     <h3 class="font-bold text-gray-800 text-sm">Lampiran Dokumen</h3>
                </div>
                <div class="p-6">
                     <div class="space-y-3">
                        @php $lampiranFound = false; @endphp
                         @if(is_array($data) || is_object($data))
                            @foreach($data as $key => $value)
                                @if(is_array($value) && isset($value['path']) && !empty($value['path']))
                                    @php $lampiranFound = true; @endphp
                                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center overflow-hidden">
                                            <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-800 truncate">
                                                    {{ Str::title(str_replace('_', ' ', $key)) }}
                                                </p>
                                                <p class="text-xs text-gray-500 truncate">{{ $value['original_name'] ?? 'Dokumen' }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('documents.show', ['filename' => basename($value['path'])]) }}" target="_blank" 
                                            class="text-blue-600 hover:text-blue-800 text-xs font-bold">
                                            Lihat <i class="fas fa-external-link-alt ml-1"></i>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        @if(!$lampiranFound)
                            <div class="text-center py-4 text-gray-400 text-sm">
                                Tidak ada lampiran.
                            </div>
                        @endif
                     </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection