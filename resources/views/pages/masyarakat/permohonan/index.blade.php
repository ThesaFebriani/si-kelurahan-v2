@extends('components.layout')

@section('title', 'Riwayat Permohonan - Sistem Kelurahan')
@section('page-title', 'Riwayat Permohonan')
@section('page-description', 'Pantau status dan riwayat pengajuan surat Anda')

@section('content')
<div class="space-y-8">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pengajuan</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $permohonan->total() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Dalam Proses -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Dalam Proses</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">
                        {{ $permohonan->whereIn('status', ['menunggu_rt', 'menunggu_kasi', 'menunggu_lurah', 'disetujui_rt', 'disetujui_kasi'])->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center border border-yellow-100">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Selesai</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-2">
                        {{ $permohonan->where('status', 'selesai')->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center border border-green-100">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content List -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        <!-- Header & Filter -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-lg border border-slate-200 flex items-center justify-center shadow-sm text-slate-500">
                    <i class="fas fa-history"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Daftar Riwayat</h3>
                    <p class="text-xs text-slate-500 font-medium">Semua permohonan yang Anda ajukan</p>
                </div>
            </div>
            
            <form action="{{ route('masyarakat.permohonan.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <input type="hidden" name="tab" value="{{ request('tab') }}">
                <div class="relative w-full md:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor tiket..." 
                           class="pl-10 pr-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-medium text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 w-full transition-all shadow-sm">
                    <i class="fas fa-search absolute left-3.5 top-3 text-slate-400 text-sm"></i>
                </div>
            </form>
        </div>

        <!-- Tab Navigation (Pills Style) -->
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
            <nav class="flex space-x-2" aria-label="Tabs">
                <a href="{{ route('masyarakat.permohonan.index', ['tab' => 'semua']) }}" 
                   class="px-4 py-2 text-sm font-bold rounded-full transition-all {{ request('tab', 'semua') == 'semua' ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200' }}">
                    Semua
                </a>
                <a href="{{ route('masyarakat.permohonan.index', ['tab' => 'proses']) }}" 
                   class="px-4 py-2 text-sm font-bold rounded-full transition-all {{ request('tab') == 'proses' ? 'bg-yellow-500 text-white shadow-md shadow-yellow-200' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200' }}">
                    Dalam Proses
                </a>
                <a href="{{ route('masyarakat.permohonan.index', ['tab' => 'selesai']) }}" 
                   class="px-4 py-2 text-sm font-bold rounded-full transition-all {{ request('tab') == 'selesai' ? 'bg-green-600 text-white shadow-md shadow-green-200' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200' }}">
                    Selesai
                </a>
            </nav>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            @if($permohonan->count() > 0)
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Detail Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @foreach($permohonan as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800">{{ $item->jenisSurat->name }}</span>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-mono text-[10px] font-bold border border-slate-200 tracking-wide">{{ $item->nomor_tiket }}</span>
                                    <span class="text-xs text-slate-500 font-medium">{{ $item->jenisSurat->bidang_display }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusContext = match($item->status) {
                                    'menunggu_rt' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'label' => 'Menunggu RT'],
                                    'disetujui_rt' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'Disetujui RT'],
                                    'menunggu_kasi' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'label' => 'Menunggu Verifikasi'],
                                    'disetujui_kasi' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'label' => 'Disetujui Kasi'],
                                    'menunggu_lurah' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'label' => 'Menunggu TTE'],
                                    'selesai' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'label' => 'Selesai'],
                                    'ditolak_rt', 'ditolak_kasi', 'ditolak_lurah' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'label' => 'Ditolak'],
                                    default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'label' => 'Pending'],
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusContext['bg'] }} {{ $statusContext['text'] }} {{ $statusContext['border'] }}">
                                {{ $statusContext['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-medium">
                            {{ $item->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('masyarakat.permohonan.detail', $item->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors text-xs font-bold shadow-sm mr-2">
                                Detail
                            </a>
                            @if($item->status === 'selesai' && $item->surat)
                            <a href="{{ route('documents.show', ['filename' => basename($item->surat->file_path)]) }}" target="_blank" 
                               class="inline-flex items-center px-3 py-1.5 bg-green-50 border border-green-200 rounded-lg text-green-700 hover:bg-green-100 transition-colors text-xs font-bold shadow-sm">
                                <i class="fas fa-download mr-1.5"></i> Unduh
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                {{ $permohonan->withQueryString()->links() }}
            </div>

            @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <i class="fas fa-inbox text-slate-300 text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum ada permohonan</h3>
                <p class="text-slate-500 mt-1">Anda belum mengajukan permohonan surat apapun.</p>
                <a href="{{ route('masyarakat.permohonan.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold text-sm shadow-lg shadow-blue-500/30 transition-all">
                    <i class="fas fa-plus mr-2"></i> Ajukan Permohonan
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- FEEDBACK MODAL (SKM) -->
@if(isset($pendingSurvey) && $pendingSurvey)
<div x-data="{ show: true, rating: 0 }" 
     x-show="show" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     style="display: none;">
    
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
            <form action="{{ route('masyarakat.feedback.store') }}" method="POST">
                @csrf
                <input type="hidden" name="permohonan_id" value="{{ $pendingSurvey->id }}">
                
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="text-center">
                        <div class="mx-auto flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-green-50 sm:mx-0 sm:h-16 sm:w-16 mb-4 inline-flex border border-green-100 shadow-sm">
                            <i class="fas fa-smile text-green-500 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold leading-6 text-slate-800 mb-2" id="modal-title">Bagaimana Pelayanan Kami?</h3>
                        <p class="text-sm text-slate-500 px-4">
                            Surat Anda <strong class="text-slate-800">{{ $pendingSurvey->jenisSurat->name }}</strong> telah selesai. <br>
                            Mohon berikan penilaian untuk pelayanan Kelurahan.
                        </p>

                        <div class="flex justify-center gap-3 my-8">
                            <template x-for="i in 5">
                                <button type="button" @click="rating = i" class="focus:outline-none transition-all hover:scale-110 active:scale-95">
                                    <i class="fas fa-star text-4xl" :class="rating >= i ? 'text-yellow-400 drop-shadow-sm' : 'text-slate-200'"></i>
                                </button>
                            </template>
                            <input type="hidden" name="rating" x-model="rating" required>
                        </div>

                        <div class="mt-4">
                            <textarea name="kritik_saran" rows="3" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 border placeholder-slate-400 font-medium text-slate-700" placeholder="Tulis kritik & saran (opsional)..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50/50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                    <button type="submit" :disabled="rating === 0" 
                            class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-700 sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        Kirim Penilaian
                    </button>
                    <button type="button" @click="show = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-all">
                        Nanti Saja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection