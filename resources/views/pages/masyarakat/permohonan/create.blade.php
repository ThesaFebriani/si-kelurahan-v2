@extends('components.layout')

@section('title', 'Ajukan Permohonan Surat - Sistem Kelurahan')
@section('page-title', 'Ajukan Permohonan Surat')
@section('page-description', 'Form pengajuan surat keterangan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                Form Pengajuan Surat
            </h3>
        </div>

        <form action="{{ route('masyarakat.permohonan.store') }}" method="POST">
            @csrf

            <div class="p-6 space-y-6">
                <!-- Pilih Jenis Surat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Surat <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_surat_id" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors">
                        <option value="">-- Pilih Jenis Surat --</option>
                        @foreach($jenis_surats as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->name }} - {{ $jenis->bidang_display }}
                        </option>
                        @endforeach
                    </select>
                    @error('jenis_surat_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data Pemohon -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Data Pemohon</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="data_pemohon[nama_lengkap]" required
                                value="{{ old('data_pemohon.nama_lengkap', Auth::user()->name) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors">
                            @error('data_pemohon.nama_lengkap')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="data_pemohon[nik]" required
                                value="{{ old('data_pemohon.nik', Auth::user()->nik) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors">
                            @error('data_pemohon.nik')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="data_pemohon[tempat_lahir]" required
                                value="{{ old('data_pemohon.tempat_lahir') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors">
                            @error('data_pemohon.tempat_lahir')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="data_pemohon[tanggal_lahir]" required
                                value="{{ old('data_pemohon.tanggal_lahir') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors">
                            @error('data_pemohon.tanggal_lahir')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="data_pemohon[jenis_kelamin]" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('data_pemohon.jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('data_pemohon.jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('data_pemohon.jenis_kelamin')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Agama <span class="text-red-500">*</span></label>
                            <input type="text" name="data_pemohon[agama]" required
                                value="{{ old('data_pemohon.agama') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors">
                            @error('data_pemohon.agama')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="data_pemohon[alamat]" required rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors">{{ old('data_pemohon.alamat', Auth::user()->alamat_lengkap) }}</textarea>
                        @error('data_pemohon.alamat')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Perkawinan <span class="text-red-500">*</span></label>
                            <input type="text" name="data_pemohon[status_perkawinan]" required
                                value="{{ old('data_pemohon.status_perkawinan') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors">
                            @error('data_pemohon.status_perkawinan')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan <span class="text-red-500">*</span></label>
                            <input type="text" name="data_pemohon[pekerjaan]" required
                                value="{{ old('data_pemohon.pekerjaan') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors">
                            @error('data_pemohon.pekerjaan')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan Permohonan <span class="text-red-500">*</span></label>
                        <textarea name="data_pemohon[tujuan]" required rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-colors"
                            placeholder="Jelaskan tujuan pengajuan surat ini...">{{ old('data_pemohon.tujuan') }}</textarea>
                        @error('data_pemohon.tujuan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                <a href="{{ route('masyarakat.permohonan.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>Ajukan Permohonan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection