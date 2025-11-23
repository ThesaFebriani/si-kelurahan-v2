<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Kelurahan')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .sidebar-transition {
            transition: all 0.3s ease;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-64 sidebar-transition min-h-screen">
        <!-- Header -->
        @include('components.header')

        <!-- Page Content -->
        <main class="p-4 lg:p-6 min-h-screen bg-gray-50">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                {{ session('error') }}
            </div>
            @endif

            <!-- Page Header -->
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

            <!-- Content -->
            @yield('content')
        </main>
    </div>

    <!-- Mobile menu button -->
    <button id="mobileMenuButton" class="lg:hidden fixed top-4 left-4 z-50 p-3 bg-blue-600 text-white rounded-lg shadow-lg">
        <i class="fas fa-bars"></i>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const sidebar = document.querySelector('aside');
            const mainContent = document.querySelector('main').parentElement;

            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    if (sidebar.classList.contains('-translate-x-full')) {
                        // Open sidebar
                        sidebar.classList.remove('-translate-x-full');
                        mainContent.classList.remove('ml-0');
                        mainContent.classList.add('ml-64');
                        mobileMenuButton.innerHTML = '<i class="fas fa-times"></i>';
                    } else {
                        // Close sidebar
                        sidebar.classList.add('-translate-x-full');
                        mainContent.classList.remove('ml-64');
                        mainContent.classList.add('ml-0');
                        mobileMenuButton.innerHTML = '<i class="fas fa-bars"></i>';
                    }
                });
            }

            // Close sidebar when clicking on a link (mobile)
            const sidebarLinks = document.querySelectorAll('aside a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('-translate-x-full');
                        mainContent.classList.remove('ml-64');
                        mainContent.classList.add('ml-0');
                        mobileMenuButton.innerHTML = '<i class="fas fa-bars"></i>';
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>