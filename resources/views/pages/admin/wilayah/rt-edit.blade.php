@extends('components.layout')

@section('title', 'Edit Data RT')
@section('page-title', 'Edit Data RT')
@section('page-description', 'Perbarui data Rukun Tetangga.')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form action="{{ route('admin.wilayah.rt.update', $rt->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-5">
            <label for="rw_id" class="block text-sm font-medium text-slate-700 mb-1">Pilih RW <span class="text-red-500">*</span></label>
            <select name="rw_id" id="rw_id" class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('rw_id') border-red-500 @enderror" required>
                <option value="">-- Pilih RW --</option>
                @foreach($rw as $item)
                    <option value="{{ $item->id }}" {{ old('rw_id', $rt->rw_id) == $item->id ? 'selected' : '' }}>RW {{ $item->nomor_rw }}</option>
                @endforeach
            </select>
            @error('rw_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="nomor_rt" class="block text-sm font-medium text-slate-700 mb-1">Nomor RT <span class="text-red-500">*</span></label>
            <input type="text" name="nomor_rt" id="nomor_rt" class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 @error('nomor_rt') border-red-500 @enderror" placeholder="Contoh: 001" value="{{ old('nomor_rt', $rt->nomor_rt) }}" required>
            @error('nomor_rt')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-slate-500 text-xs mt-1">Masukkan 3 digit nomor RT.</p>
        </div>

        <div class="mb-6">
             <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ $rt->is_active ? 'checked' : '' }}>
                <span class="text-sm text-slate-700">Aktifkan RT ini?</span>
             </label>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.wilayah.rt.index') }}" class="px-5 py-2.5 text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 font-medium rounded-lg text-sm transition-colors">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
