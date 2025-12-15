@extends('components.layout')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas Sistem')
@section('page-description', 'Pantau semua aktivitas dan perubahan data dalam sistem secara real-time.')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
    <!-- Toolbar & Filter -->
    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            
            <!-- Date Filter -->
            <div class="flex gap-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                <span class="self-center text-slate-400">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
            </div>

            <!-- Action Filter -->
            <select name="action" class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                <option value="">Semua Aktivitas</option>
                <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create Data</option>
                <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update Data</option>
                <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete Data</option>
                <option value="approve" {{ request('action') == 'approve' ? 'selected' : '' }}>Approval</option>
                <option value="reject" {{ request('action') == 'reject' ? 'selected' : '' }}>Rejection</option>
                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
            </select>

            <!-- User Filter -->
            <select name="user_id" class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                <option value="">Semua User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role_display }})</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 transition-all shadow-sm">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 font-medium text-sm rounded-lg hover:bg-slate-50 transition-all">
                    Reset
                </a>
                <a href="{{ route('admin.audit-logs.export', request()->query()) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white font-medium text-sm rounded-lg hover:bg-red-700 transition-all shadow-sm flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">User (Pelaku)</th>
                    <th class="px-6 py-4">Aktivitas</th>
                    <th class="px-6 py-4">Target Data</th>
                    <th class="px-6 py-4">IP Address</th>
                    <th class="px-6 py-4 text-center">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-slate-900 font-medium">{{ $log->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-slate-500">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($log->user)
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 uppercase">
                                    {{ substr($log->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-900">{{ $log->user->name }}</div>
                                    <div class="text-[10px] text-slate-500 uppercase">{{ $log->user->role_display ?? 'Unknown' }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-slate-400 italic text-sm">System / Deleted User</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $log->action_color }}-50 text-{{ $log->action_color }}-700 border border-{{ $log->action_color }}-100">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-600">
                            {{ Str::afterLast($log->model_type, '\\') }}
                            <span class="text-slate-400 text-xs">#{{ $log->model_id }}</span>
                        </div>
                        <div class="text-xs text-slate-500 truncate max-w-[200px]" title="{{ $log->description }}">
                            {{ $log->description }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500 font-mono">
                        {{ $log->ip_address ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="showDetail({{ $log->id }})" class="text-blue-600 hover:text-blue-800 text-xs font-medium hover:underline">
                            Lihat
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        Tidak ada aktivitas yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $logs->links() }}
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div id="modalContent" class="bg-white p-6">
                <!-- Content loaded via AJAX -->
                <div class="flex justify-center py-10">
                    <i class="fas fa-circle-notch fa-spin text-blue-500 text-2xl"></i>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showDetail(id) {
    document.getElementById('detailModal').classList.remove('hidden');
    
    // Fetch content
    // Fetch content
    fetch(`{{ url('admin/audit-logs') }}/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalContent').innerHTML = '<p class="text-red-500 text-center">Gagal memuat data detail.</p>';
        });
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('modalContent').innerHTML = '<div class="flex justify-center py-10"><i class="fas fa-circle-notch fa-spin text-blue-500 text-2xl"></i></div>';
}
</script>
@endsection
