@php
$user = Auth::user();
// Fetch notifications directly here for simplicity in this component or passed from layout
// We will use a View Composer ideally, but for now let's query raw if not passed
$unreadNotifications = \App\Models\Notification::where('user_id', $user->id)
    ->where('is_read', false)
    ->latest()
    ->limit(5)
    ->get();
$unreadCount = \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count();
@endphp

<div class="relative" x-data="{ open: false, count: {{ $unreadCount }} }">
    <!-- Bell Button -->
    <button @click="open = !open" @click.away="open = false" class="relative p-2 text-gray-600 hover:text-blue-600 transition-colors">
        <i class="fas fa-bell text-xl"></i>
        <span x-show="count > 0" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-4 h-4 text-[10px] flex items-center justify-center transform translate-x-1 -translate-y-1 animate-pulse">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-100 z-50 overflow-hidden" 
         style="display: none;">
        
        <div class="p-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h6 class="font-semibold text-sm text-gray-700">Notifikasi</h6>
            @if($unreadCount > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">Tandai sudah dibaca</button>
            </form>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($unreadNotifications as $notif)
            <div class="p-3 border-b border-gray-50 hover:bg-blue-50 transition-colors relative group">
                <div class="flex gap-3">
                    <div class="flex-shrink-0 mt-1">
                        @if($notif->type == 'info')
                            <i class="fas fa-info-circle text-blue-500"></i>
                        @elseif($notif->type == 'success')
                            <i class="fas fa-check-circle text-green-500"></i>
                        @elseif($notif->type == 'warning')
                            <i class="fas fa-exclamation-triangle text-amber-500"></i>
                        @elseif($notif->type == 'danger')
                            <i class="fas fa-times-circle text-red-500"></i>
                        @else
                            <i class="fas fa-bell text-gray-400"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $notif->title }}</p>
                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $notif->message }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                {{-- Helper link (if exists) --}}
                @if($notif->related_id)
                     <a href="#" class="absolute inset-0 z-10"></a> {{-- Placeholder for click --}}
                @endif
            </div>
            @empty
            <div class="p-8 text-center">
                <div class="bg-gray-100 rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-bell-slash text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-sm">Tidak ada notifikasi baru</p>
            </div>
            @endforelse
        </div>
        
        <div class="p-2 border-t border-gray-100 text-center bg-gray-50">
            <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-800">Lihat Semua</a>
        </div>
    </div>
</div>
