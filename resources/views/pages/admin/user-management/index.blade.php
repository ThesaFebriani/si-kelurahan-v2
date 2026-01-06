@extends('components.layout')

@section('title', 'Manajemen User')
@section('page-title', 'Daftar Pengguna')
@section('page-description', 'Kelola data pengguna, peran, dan hak akses dalam sistem.')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
    <!-- Toolbar -->
    <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
        <!-- Search/Filter -->
        <div class="relative w-full sm:w-auto">
            <input type="text" placeholder="Cari user..." class="pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 w-full sm:w-64">
            <i class="fas fa-search absolute left-3 top-3 text-slate-400 text-sm"></i>
        </div>

        <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 transition-all shadow-sm hover:shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>Tambah User Baru</span>
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="pl-4 pr-3 py-3">User Info</th>
                    <th class="px-3 py-3">Role & Jabatan</th>
                    <th class="px-3 py-3">Wilayah</th>
                    <th class="px-3 py-3 text-center w-px whitespace-nowrap">Status</th>
                    <th class="px-3 py-3 text-center w-px whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="pl-4 pr-3 py-3">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">{{ $user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5 font-mono">NIK: {{ $user->nik }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                            {{ $user->role->name === 'admin' ? 'bg-purple-50 text-purple-700 border-purple-100' : '' }}
                            {{ $user->role->name === 'rt' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                            {{ $user->role->name === 'lurah' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                            {{ $user->role->name === 'kasi' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                            {{ $user->role->name === 'masyarakat' ? 'bg-slate-100 text-slate-600 border-slate-200' : '' }}
                        ">
                            {{ ucfirst($user->role->name) }}
                        </span>
                        @if($user->jabatan)
                            <div class="text-xs text-slate-500 mt-1.5 line-clamp-1 max-w-[150px]" title="{{ $user->jabatan }}">
                                {{ $user->jabatan }}
                            </div>
                        @endif
                    </td>
                    <td class="px-3 py-3 align-middle">
                        @if($user->rt)
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded w-fit">RT {{ $user->rt->nomor_rt }}</span>
                                <span class="text-[10px] text-slate-400 mt-1">RW {{ $user->rt->rw ? $user->rt->rw->nomor_rw : '-' }}</span>
                            </div>
                        @else
                            <span class="text-slate-400 text-xs italic">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-center w-px whitespace-nowrap">
                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-100">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Aktif
                        </span>
                    </td>
                    <td class="px-3 py-3 text-center w-px whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="h-8 w-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            
                            @if(Auth::id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Hapus">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <div class="h-16 w-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                            <h3 class="text-base font-medium text-slate-900">Belum ada user</h3>
                            <p class="text-sm mt-1">Silakan tambahkan user baru untuk memulai.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
