<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Kelurahan')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Custom styles akan ditambahkan di sini */
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
    <div class="ml-0 lg:ml-64 sidebar-transition">
        <!-- Header -->
        @include('components.header')

        <!-- Page Content -->
        <main class="p-4 lg:p-6">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Mobile menu button (akan di-handle JavaScript) -->
    <button id="mobileMenuButton" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-blue-600 text-white rounded">
        <i class="fas fa-bars"></i>
    </button>

    <script>
        // Mobile sidebar toggle
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            const sidebar = document.querySelector('aside');
            const mainContent = document.querySelector('main').parentElement;

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                mainContent.classList.remove('ml-0');
                mainContent.classList.add('ml-64');
            } else {
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-0');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>