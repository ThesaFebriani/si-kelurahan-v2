@extends('components.layout')

@section('title', 'Edit FAQ')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.faqs.index') }}" class="text-slate-500 hover:text-blue-600 text-sm flex items-center mb-4 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Manajemen FAQ
    </a>
    <h1 class="text-2xl font-bold text-slate-800">Edit FAQ</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-3xl">
    <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Question -->
        <div>
            <label for="question" class="block text-sm font-medium text-slate-700 mb-1">Pertanyaan <span class="text-red-500">*</span></label>
            <input type="text" name="question" id="question" value="{{ old('question', $faq->question) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Bagaimana cara reset password?" required>
            @error('question')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Answer -->
        <div>
            <label for="answer" class="block text-sm font-medium text-slate-700 mb-1">Jawaban <span class="text-red-500">*</span></label>
            <textarea name="answer" id="answer" rows="5" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Tulis jawaban lengkap di sini..." required>{{ old('answer', $faq->answer) }}</textarea>
            @error('answer')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Category & Publish -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="category" id="category" list="category_list" value="{{ old('category', $faq->category) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Pilih atau ketik kategori baru..." required>
                <datalist id="category_list">
                    <option value="Umum">
                    <option value="Akun & Login">
                    <option value="Pengajuan Surat">
                    <option value="Teknis">
                    <option value="Lainnya">
                </datalist>
                 <p class="text-xs text-slate-500 mt-1">Ketik nama kategori baru jika tidak ada di pilihan.</p>
            </div>

            <!-- Is Published -->
            <div>
                <label for="is_published" class="block text-sm font-medium text-slate-700 mb-1">Status Publikasi</label>
                <select name="is_published" id="is_published" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="1" {{ old('is_published', $faq->is_published) == '1' ? 'selected' : '' }}>Published (Tampil)</option>
                    <option value="0" {{ old('is_published', $faq->is_published) == '0' ? 'selected' : '' }}>Draft (Sembunyikan)</option>
                </select>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
            <a href="{{ route('admin.faqs.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 font-medium transition-colors">Batal</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition-all transform hover:-translate-y-0.5">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
