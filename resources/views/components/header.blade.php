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
            @auth
            <!-- Notifications -->
            @include('components.notification-dropdown')

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer focus:outline-none">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="text-left hidden lg:block">
                        <div class="font-semibold text-gray-800 text-sm">{{ $user->name }}</div>
                        <div class="text-xs text-gray-600 capitalize">{{ $user->role->name }}</div>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-sm hidden lg:block transition-transform duration-200" :class="{'transform rotate-180': open}"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50 pointer-events-auto" 
                     style="display: none;">
                    
                    @if($user->role->name === 'masyarakat')
                    <a href="{{ route('masyarakat.profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">
                        <i class="fas fa-user-circle mr-2"></i> Profil Saya
                    </a>
                    @endif

                    @if($user->role->name === 'lurah')
                    <a href="{{ route('lurah.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">
                        <i class="fas fa-user-tie mr-2"></i> Profil Lurah
                    </a>
                    @endif

                    <div class="border-t border-gray-100 my-1"></div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
            @else
            <!-- Guest View -->
            <a href="{{ route('login') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                Masuk
            </a>
            @endauth
        </div>
    </div>
</header>