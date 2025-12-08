@extends('components.layout')

@section('title', 'Edit Jenis Surat')
@section('page-title', 'Edit Jenis Surat')
@section('page-description', 'Perbarui informasi jenis surat.')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Jenis Surat</h2>
            <p class="text-slate-500 text-sm mt-1">Perbarui informasi jenis surat.</p>
        </div>
        <a href="{{ route('admin.jenis-surat.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
        <form action="{{ route('admin.jenis-surat.update', $jenis_surat->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Kode Surat -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kode Surat (Unique)</label>
                    <input type="text" name="code" value="{{ old('code', $jenis_surat->code) }}" required
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                        placeholder="Contoh: surat_keterangan_usaha">
                    <p class="text-xs text-slate-400 mt-1">Gunakan huruf kecil dan underscore, tanpa spasi.</p>
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nama Surat -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Surat</label>
                    <input type="text" name="name" value="{{ old('name', $jenis_surat->name) }}" required
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                        placeholder="Contoh: Surat Keterangan Usaha">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                        placeholder="Deskripsi singkat tentang kegunaan surat ini...">{{ old('description', $jenis_surat->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status Aktif -->
                <div>
                    <label class="flex items-center space-x-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $jenis_surat->is_active) ? 'checked' : '' }}
                            class="h-5 w-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-slate-700 font-medium">Aktifkan Surat Ini?</span>
                    </label>
                    <p class="text-xs text-slate-400 mt-1 ml-8">Jika tidak dicentang, warga tidak akan bisa memilih jenis surat ini.</p>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end pt-6 border-t border-slate-100 mt-6">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-md hover:shadow-lg">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
