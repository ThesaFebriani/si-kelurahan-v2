@extends('components.layout')

@section('title', 'Verifikasi Warga')
@section('page-title', 'Verifikasi Warga Baru')
@section('page-description', 'Verifikasi pendaftaran akun warga di lingkungan RT Anda.')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Menunggu Verifikasi</h3>
            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                {{ $pendingUsers->count() }} Permintaan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama & NIK</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pendingUsers as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ $user->name }}</div>
                                <div class="text-sm text-slate-500 font-mono">{{ $user->nik }}</div>
                                <div class="text-xs text-slate-400 mt-1">{{ ucwords($user->jk) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-600">{{ $user->email }}</div>
                                <div class="text-sm text-slate-600">{{ $user->telepon }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600 line-clamp-2">{{ $user->alamat }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <form action="{{ route('rt.warga.verification.process', $user->id) }}" method="POST" class="inline-flex gap-2">
                                    @csrf
                                    <button type="submit" name="action" value="approve" 
                                        class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors"
                                        onclick="return confirm('Apakah Anda yakin data warga ini valid?')">
                                        <i class="fas fa-check mr-1"></i> Terima
                                    </button>
                                    <button type="submit" name="action" value="reject" 
                                        class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 text-xs font-medium rounded-lg transition-colors"
                                        onclick="return confirm('Tolak pendaftaran ini?')">
                                        <i class="fas fa-times mr-1"></i> Tolak
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                <i class="fas fa-check-circle text-4xl text-green-200 mb-3 block"></i>
                                Tidak ada pendaftaran warga yang menunggu verifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
