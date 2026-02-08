@extends('components.layout')

@section('title', 'Manajemen Berita')
@section('page-title', 'Pusat Informasi & Berita')
@section('page-description', 'Kelola pengumuman dan berita untuk warga.')

@section('content')
<div class="sm:flex sm:items-center sm:justify-end mb-8">
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/30 transition-all">
            <i class="fas fa-plus mr-2"></i> Buat Berita Baru
        </a>
    </div>
</div>

<div class="bg-white shadow-sm overflow-hidden rounded-xl border border-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Judul Berita</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Penulis</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($beritas as $berita)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-16 w-24 flex-shrink-0">
                                <img class="h-full w-full rounded-lg object-cover border border-slate-200" src="{{ $berita->gambar_url }}" alt="">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-slate-800 line-clamp-1">{{ $berita->judul }}</div>
                                <div class="text-[10px] text-slate-500 font-medium tracking-tight">{{ Str::limit($berita->excerpt, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-slate-600">{{ $berita->author->name ?? 'Admin' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($berita->status == 'published')
                            <span class="px-2.5 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full bg-green-50 text-green-700 border border-green-100">
                                Terbit
                            </span>
                        @else
                            <span class="px-2.5 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-medium">
                        {{ $berita->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.berita.edit', $berita->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-indigo-600 hover:bg-slate-50 font-bold text-xs shadow-sm mr-2 transition-all">
                            Edit
                        </a>
                        <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-red-600 hover:bg-red-50 font-bold text-xs shadow-sm transition-all">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-bullhorn text-4xl text-slate-200 mb-3"></i>
                            <p class="text-sm font-bold text-slate-400">Belum ada berita yang diterbitkan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
        {{ $beritas->links() }}
    </div>
</div>
@endsection
