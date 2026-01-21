<nav class="bg-white/90 backdrop-blur-md fixed w-full z-50 border-b border-gray-100 transition-all duration-300 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <div class="flex items-center space-x-3">
                <a href="{{ route('landing') }}" class="flex items-center space-x-3">
                    <div class="bg-blue-600 text-white p-2 rounded-lg">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-700 to-blue-500">
                            SIP <span class="text-gray-800">Kelurahan</span>
                        </span>
                        <p class="text-xs text-gray-500 font-medium">Sistem Informasi Pelayanan</p>
                    </div>
                </a>
            </div>
            
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('landing') }}#fitur" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Layanan</a>
                <a href="{{ route('landing') }}#panduan" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Panduan</a>
                
                @if (Route::has('login'))
                    <div class="flex items-center space-x-3 ml-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 font-semibold hover:text-blue-600">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium px-4 py-2">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-full font-medium transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 transform hover:-translate-y-0.5">
                                    Daftar Sekarang
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="publicMobileMenuBtn" class="p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="publicMobileMenu" class="hidden md:hidden bg-white border-b border-gray-100">
        <a href="{{ route('landing') }}#fitur" class="block py-3 px-4 text-sm hover:bg-gray-50 text-gray-700">Layanan</a>
        <a href="{{ route('landing') }}#panduan" class="block py-3 px-4 text-sm hover:bg-gray-50 text-gray-700">Panduan</a>
        @auth
             <a href="{{ url('/dashboard') }}" class="block py-3 px-4 text-sm font-bold text-blue-600">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="block py-3 px-4 text-sm hover:bg-gray-50 text-gray-700">Masuk</a>
            <a href="{{ route('register') }}" class="block py-3 px-4 text-sm font-bold text-blue-600">Daftar Sekarang</a>
        @endauth
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('publicMobileMenuBtn');
            const menu = document.getElementById('publicMobileMenu');
            if(btn && menu) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                });
            }
        });
    </script>
</nav>
