<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Kelurahan')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .sidebar-transition {
            transition: all .3s ease;
        }
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans">

    @include('components.sidebar')

    <div class="{{ auth()->check() ? 'lg:ml-64' : '' }} sidebar-transition min-h-screen">

        @auth
            @include('components.header')
        @else
            @include('components.landing-navbar')
            <!-- Spacer for fixed navbar -->
            <div class="h-20"></div>
        @endauth

        <main class="p-4 lg:p-4 min-h-screen bg-gray-50">

            @if(session('success'))
            @php
            $flash = session('success');
            $title = is_array($flash) && isset($flash['title']) ? $flash['title'] : 'Berhasil';
            $message = is_array($flash) && isset($flash['message']) ? $flash['message'] : (is_string($flash) ? $flash : '');
            @endphp

            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="font-bold mb-1">{{ $title }}</div>
                <div>{{ $message }}</div>
            </div>
            @endif

            @if(session('error'))
            @php
            $flash = session('error');
            $title = is_array($flash) && isset($flash['title']) ? $flash['title'] : 'Terjadi Kesalahan';
            $message = is_array($flash) && isset($flash['message']) ? $flash['message'] : (is_string($flash) ? $flash : '');
            @endphp

            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <div class="font-bold mb-1">{{ $title }}</div>
                <div>{{ $message }}</div>
            </div>
            @endif




            <!-- Page Title Removed to prevent duplication with Header -->
            <!-- 
            <div class="mb-6">
                @hasSection('page-title')
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    @hasSection('page-icon')
                    <i class="fas @yield('page-icon') text-blue-600 mr-3"></i>
                    @endif
                    @yield('page-title')
                </h1>
                @endif

                @hasSection('page-description')
                <p class="text-gray-600 mt-2">@yield('page-description')</p>
                @endif
            </div> 
            -->

            @yield('content')
        </main>
    </div>

    <button id="mobileMenuButton"
        class="lg:hidden fixed top-4 left-4 z-50 p-3 bg-blue-600 text-white rounded-lg shadow-lg">
        <i class="fas fa-bars"></i>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobileMenuButton');
            const sidebar = document.querySelector('aside');
            const main = document.querySelector('main').parentElement;

            btn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                main.classList.toggle('ml-64');
            });
        });
    </script>

    @stack('scripts')
    
    <!-- Floating Help Button (Hanya tampil untuk Warga / Tamu) -->
    @if(!auth()->check() || (auth()->check() && auth()->user()->role_id == 2))
    <div class="fixed bottom-6 right-6 z-50">
        <a href="https://wa.me/{{ $adminPhone }}" target="_blank" 
            class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white rounded-full px-4 py-3 shadow-lg transition-transform hover:scale-105">
            <i class="fab fa-whatsapp text-2xl"></i>
            <span class="font-semibold hidden md:inline">Bantuan</span>
        </a>
    </div>
    @endif
</body>

</html>