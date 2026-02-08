@extends('components.layout')

@section('title', 'Tambah Data RT')
@section('page-title', 'Tambah Data RT')
@section('page-description', 'Tambahkan data Rukun Tetangga baru.')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form action="{{ route('admin.wilayah.rt.store') }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            <!-- Identitas Wilayah -->
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt"></i> Identitas Wilayah
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih RW Induk <span class="text-red-500">*</span></label>
                        <select name="rw_id" class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 @error('rw_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih RW --</option>
                            @foreach($rw as $item)
                                <option value="{{ $item->id }}" {{ old('rw_id') == $item->id ? 'selected' : '' }}>RW {{ $item->nomor_rw }}</option>
                            @endforeach
                        </select>
                        @error('rw_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor RT (3 Digit) <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_rt" class="w-full rounded-md border-2 border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 @error('nomor_rt') border-red-500 @enderror" placeholder="Contoh: 001" value="{{ old('nomor_rt') }}" required>
                        @error('nomor_rt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>



            <!-- Pengaturan Status -->
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-100 flex items-center gap-2">
                    <i class="fas fa-cog"></i> Pengaturan Status
                </h3>
                 <label class="flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="h-5 w-5 text-blue-600 border-2 border-slate-300 rounded focus:ring-blue-500" checked>
                    <span class="text-sm font-bold text-slate-700">Aktifkan Wilayah RT Ini?</span>
                 </label>
                 <p class="text-[10px] text-slate-500 mt-1 ml-7">Jika non-aktif, RT ini tidak akan muncul di pilihan form warga.</p>
            </div>
        </div>

        <!-- Sticky Action Bar -->
        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100 sticky bottom-0 bg-white/95 backdrop-blur-sm p-4 -mx-6 -mb-6 rounded-b-lg shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
            <a href="{{ route('admin.wilayah.rt.index') }}" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium text-sm transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm shadow-md hover:shadow-lg transition-all">
                Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection
