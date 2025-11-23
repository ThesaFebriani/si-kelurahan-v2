@php
$user = Auth::user();
@endphp

<header class="bg-white shadow-sm border-b">
    <div class="flex items-center justify-between p-4 lg:p-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <p class="text-gray-600 text-sm lg:text-base">@yield('page-description', 'Selamat datang di Sistem Kelurahan')</p>
        </div>

        <!-- User Info -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="relative p-2 text-gray-600 hover:text-blue-600 transition-colors">
                <i class="fas fa-bell"></i>
                <span class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-4 h-4 text-xs flex items-center justify-center transform translate-x-1 -translate-y-1">3</span>
            </button>

            <!-- User Dropdown -->
            <div class="relative">
                <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="text-left hidden lg:block">
                        <div class="font-semibold text-gray-800 text-sm">{{ $user->name }}</div>
                        <div class="text-xs text-gray-600 capitalize">{{ $user->role->name }}</div>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-sm hidden lg:block"></i>
                </div>
            </div>
        </div>
    </div>
</header>