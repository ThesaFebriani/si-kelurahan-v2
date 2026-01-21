<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen - SI-KELURAHAN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
        <!-- Header -->
        <div class="bg-white p-6 border-b border-slate-100 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            
            <img src="{{ isset($settings['logo_instansi']) ? asset($settings['logo_instansi']) : asset('images/logo-kota-bengkulu.png') }}" 
                 alt="Logo" 
                 class="h-16 mx-auto mb-3 object-contain">
            
            <h1 class="text-slate-800 font-bold text-lg uppercase leading-tight">
                {{ $settings['nama_instansi'] ?? 'Pemerintah Kota Bengkulu' }}
            </h1>
            <p class="text-slate-500 text-xs font-medium uppercase tracking-wide">
                {{ $settings['nama_kecamatan'] ?? 'Kecamatan Gading Cempaka' }} • {{ $settings['nama_kelurahan'] ?? 'Kelurahan Padang Jati' }}
            </p>
        </div>

        <!-- Verification Status -->
        <div class="p-8 text-center bg-slate-50/50">
            @if($isValid)
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                    <i class="fas fa-check-circle text-4xl text-green-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-green-700 mb-1">DOKUMEN VALID</h2>
                <p class="text-slate-500 text-sm">Dokumen ini terdaftar secara resmi di sistem kami.</p>
            @else
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                    <i class="fas fa-times-circle text-4xl text-red-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-red-700 mb-1">DOKUMEN TIDAK DITEMUKAN</h2>
                <p class="text-slate-500 text-sm">
                    Maaf, kami tidak dapat memverifikasi keaslian dokumen ini.<br>
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded mt-2 inline-block">Ref: {{ $nomor }}</span>
                </p>
            @endif
        </div>

        <!-- Document Details -->
        @if($isValid && $data)
        <div class="p-6 space-y-4">
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                <p class="text-blue-600 text-xs font-bold uppercase tracking-wider mb-2">Informasi Surat</p>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Jenis Dokumen</span>
                        <span class="font-bold text-slate-800 text-right">{{ $type == 'Surat Kelurahan' ? 'Surat Keterangan' : 'Surat Pengantar RT' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Nomor Surat</span>
                        <span class="font-mono font-bold text-slate-800 text-right">{{ $nomor }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Tanggal Terbit</span>
                        <span class="font-bold text-slate-800 text-right">
                            {{ $type == 'Surat Kelurahan' 
                                ? \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y H:i') 
                                : ($data->updated_at ? \Carbon\Carbon::parse($data->updated_at)->translatedFormat('d F Y H:i') : '-') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Identitas Pemohon</p>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Nama Lengkap</span>
                        <span class="font-bold text-slate-800 uppercase text-right">
                            {{ $type == 'Surat Kelurahan' ? $data->permohonan->user->name : $data->user->name }}
                        </span>
                    </div>
                    @if($type == 'Surat Pengantar RT')
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">RT / RW</span>
                        <span class="font-bold text-slate-800 text-right">
                            RT {{ $data->user->rt->nomor_rt ?? '-' }} / RW {{ $data->user->rt->rw->nomor_rw ?? '-' }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="text-center pt-4 space-y-3">
                @if($type == 'Surat Kelurahan')
                <a href="{{ route('public.verify.surat.view', ['nomor_surat' => $nomor]) }}" target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i> Lihat Dokumen Asli
                </a>
                @elseif($type == 'Surat Pengantar RT' && isset($data->id))
                <a href="{{ route('public.verify.pengantar.view', ['id' => $data->id]) }}" target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i> Lihat Dokumen Asli
                </a>
                @endif

                <p class="text-xs text-slate-400">
                    Verifikasi dilakukan secara otomatis oleh sistem SI-KELURAHAN.
                    <br>Scan QR Code ini kapan saja untuk memastikan validitas dokumen.
                    <br><strong>Bandingkan dokumen fisik dengan dokumen asli di atas.</strong>
                </p>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="bg-slate-50 p-4 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-400 font-medium tracking-wide">
                &copy; {{ date('Y') }} {{ $settings['nama_instansi'] ?? 'Pemerintah Kota Bengkulu' }}.
            </p>
        </div>
    </div>

</body>
</html>
