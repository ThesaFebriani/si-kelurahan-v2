@extends('components.layout')

@section('title', 'Detail Permohonan - RT')
@section('page-title', 'Detail Permohonan Surat')
@section('page-description', 'Lihat detail lengkap permohonan surat')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                        Detail Permohonan Surat
                    </h3>
                    <p class="text-gray-600 mt-1">
                        Nomor Tiket: <code class="bg-gray-100 px-2 py-1 rounded">{{ $permohonan->nomor_tiket }}</code>
                    </p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                        @if($permohonan->isMenungguRT()) bg-yellow-100 text-yellow-800
                        @elseif($permohonan->isDisetujuiRT()) bg-blue-100 text-blue-800
                        @elseif($permohonan->isDitolakRT()) bg-red-100 text-red-800
                        @elseif($permohonan->isMenungguKasi()) bg-orange-100 text-orange-800
                        @elseif($permohonan->isDisetujuiKasi()) bg-green-100 text-green-800
                        @elseif($permohonan->isDitolakKasi()) bg-red-100 text-red-800
                        @elseif($permohonan->isMenungguLurah()) bg-purple-100 text-purple-800
                        @elseif($permohonan->isSelesai()) bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $permohonan->status_display }}
                    </span>
                    <p class="text-sm text-gray-500 mt-1">{{ $permohonan->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Informasi Pemohon -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Informasi Pemohon
                    </h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permohonan->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permohonan->user->alamat_lengkap ?? 'Tidak ada alamat' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">RT</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($permohonan->user->rt)
                                RT {{ $permohonan->user->rt->nomor_rt }}
                                @else
                                Tidak terdaftar RT
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-signature text-green-600 mr-2"></i>
                        Informasi Surat
                    </h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Surat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permohonan->jenisSurat->name ?? 'Tidak ada jenis surat' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pengajuan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permohonan->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($permohonan->keterangan_tolak)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 text-red-600">Alasan Penolakan</label>
                            <p class="mt-1 text-sm text-red-600">{{ $permohonan->keterangan_tolak }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Data Pemohon -->
            <div>
                <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-id-card text-purple-600 mr-2"></i>
                    Data Pemohon
                </h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    @php
                    // Handle data pemohon dengan safe approach
                    $dataPemohon = $permohonan->data_pemohon;

                    // Jika data_pemohon adalah string, coba decode
                    if (is_string($dataPemohon)) {
                    $decoded = json_decode($dataPemohon, true);
                    $dataPemohon = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
                    }

                    // Pastikan $dataPemohon adalah array
                    $dataPemohon = is_array($dataPemohon) ? $dataPemohon : [];
                    @endphp

                    @if(!empty($dataPemohon) && count($dataPemohon) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        <div><strong>NIK:</strong> {{ $dataPemohon['nik'] ?? '-' }}</div>
                        <div><strong>Tempat Lahir:</strong> {{ $dataPemohon['tempat_lahir'] ?? '-' }}</div>
                        <div><strong>Tanggal Lahir:</strong>
                            @if(!empty($dataPemohon['tanggal_lahir']))
                            @php
                            try {
                            $tanggalLahir = \Carbon\Carbon::parse($dataPemohon['tanggal_lahir'])->format('d/m/Y');
                            } catch (Exception $e) {
                            $tanggalLahir = $dataPemohon['tanggal_lahir'];
                            }
                            @endphp
                            {{ $tanggalLahir }}
                            @else
                            -
                            @endif
                        </div>
                        <div><strong>Jenis Kelamin:</strong>
                            @if(isset($dataPemohon['jenis_kelamin']))
                            {{ $dataPemohon['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            @else
                            -
                            @endif
                        </div>
                        <div><strong>Agama:</strong> {{ $dataPemohon['agama'] ?? '-' }}</div>
                        <div><strong>Pekerjaan:</strong> {{ $dataPemohon['pekerjaan'] ?? '-' }}</div>
                        <div><strong>Status Perkawinan:</strong> {{ $dataPemohon['status_perkawinan'] ?? '-' }}</div>
                        <div><strong>Kewarganegaraan:</strong> {{ $dataPemohon['kewarganegaraan'] ?? 'WNI' }}</div>
                        <div class="md:col-span-3">
                            <strong>Alamat Lengkap:</strong>
                            <p class="text-sm text-gray-700 mt-1">{{ $dataPemohon['alamat'] ?? ($permohonan->user->alamat_lengkap ?? 'Tidak ada alamat') }}</p>
                        </div>
                        @if(!empty($dataPemohon['tujuan']))
                        <div class="md:col-span-3">
                            <strong>Tujuan Pembuatan Surat:</strong>
                            <p class="text-sm text-gray-700 mt-1">{{ $dataPemohon['tujuan'] }}</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-6 text-gray-500">
                        <i class="fas fa-info-circle text-gray-300 text-3xl mb-3"></i>
                        <p class="text-lg">Data pemohon tidak tersedia</p>
                        <p class="text-sm mt-1">Data formulir pemohon belum diisi atau format tidak sesuai</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Lampiran -->
            @if($permohonan->lampirans && $permohonan->lampirans->count() > 0)
            <div>
                <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-paperclip text-orange-600 mr-2"></i>
                    Lampiran
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($permohonan->lampirans as $lampiran)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file text-gray-400 mr-3 text-lg"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $lampiran->nama_file }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $lampiran->tipe_file ?? 'Unknown' }} •
                                        {{ $lampiran->ukuran_file ? number_format($lampiran->ukuran_file / 1024, 2) . ' KB' : 'Unknown size' }}
                                    </p>
                                </div>
                            </div>
                            @if($lampiran->path_file)
                            <a href="{{ Storage::url($lampiran->path_file) }}"
                                target="_blank"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat
                            </a>
                            @else
                            <span class="text-gray-400 text-sm">File tidak tersedia</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div>
                <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history text-indigo-600 mr-2"></i>
                    Timeline Permohonan
                </h4>
                <div class="space-y-4">
                    @forelse($permohonan->timeline->take(10) as $timeline)
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas 
                                    @if(str_contains($timeline->keterangan, 'Disetujui')) fa-check-circle text-green-600
                                    @elseif(str_contains($timeline->keterangan, 'Ditolak')) fa-times-circle text-red-600
                                    @elseif(str_contains($timeline->keterangan, 'diajukan')) fa-paper-plane text-blue-600
                                    @elseif(str_contains($timeline->keterangan, 'dibatalkan')) fa-ban text-gray-600
                                    @else fa-clock text-gray-600 @endif
                                    text-sm">
                                </i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $timeline->keterangan }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $timeline->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-gray-500">
                        <i class="fas fa-clock text-gray-300 text-3xl mb-3"></i>
                        <p class="text-lg">Belum ada timeline</p>
                        <p class="text-sm mt-1">Riwayat status permohonan akan muncul di sini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
            <a href="{{ route('rt.permohonan.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
            </a>

            @if($permohonan->isMenungguRT())
            <a href="{{ route('rt.permohonan.approve', $permohonan->id) }}"
                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-check-circle mr-2"></i>Proses Permohonan
            </a>
            @endif
        </div>
    </div>
</div>
@endsection