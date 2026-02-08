<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pelayanan Kelurahan Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- Navbar -->
    <nav class="bg-white/90 backdrop-blur-md fixed w-full z-50 border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 text-white p-2 rounded-lg">
                        <i class="fas fa-building text-xl"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-700 to-blue-500">
                            SIP <span class="text-gray-800">Kelurahan</span>
                        </span>
                        <p class="text-xs text-gray-500 font-medium">Sistem Informasi Pelayanan</p>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#fitur" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Layanan</a>
                    <a href="{{ route('public.faq') }}" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Panduan & FAQ</a>
                    
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
                    <button class="mobile-menu-button p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu hidden md:hidden bg-white border-b border-gray-100">
            <a href="#fitur" class="block py-3 px-4 text-sm hover:bg-gray-50 text-gray-700">Layanan</a>
            <a href="#panduan" class="block py-3 px-4 text-sm hover:bg-gray-50 text-gray-700">Panduan</a>
            @auth
                 <a href="{{ url('/dashboard') }}" class="block py-3 px-4 text-sm font-bold text-blue-600">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block py-3 px-4 text-sm hover:bg-gray-50 text-gray-700">Masuk</a>
                <a href="{{ route('register') }}" class="block py-3 px-4 text-sm font-bold text-blue-600">Daftar Sekarang</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center pt-20 overflow-hidden relative bg-white">
        <!-- Background Decorations -->
        <div class="absolute top-0 right-0 -mr-64 -mt-64 w-128 h-128 rounded-full bg-blue-50/50 blur-3xl opacity-60"></div>
        <div class="absolute bottom-0 left-0 -ml-64 -mb-64 w-128 h-128 rounded-full bg-indigo-50/50 blur-3xl opacity-60"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <div class="space-y-8 text-center lg:text-left">
                    <div>
                        <span class="inline-block py-1 px-3 rounded-full bg-blue-50 text-blue-600 text-sm font-semibold mb-6 border border-blue-100">
                            Smart Village System
                        </span>
                        <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 leading-tight">
                            Urus Surat Jadi <br>
                            <span class="text-blue-600">Lebih Mudah</span> & <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">Cepat</span>
                        </h1>
                        <p class="mt-6 text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                            Tidak perlu lagi antre panjang. Ajukan surat pengantar RT/RW hingga kelurahan langsung dari smartphone atau laptop Anda.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 transform hover:-translate-y-1">
                            <i class="fas fa-rocket mr-2"></i> Mulai Sekarang
                        </a>
                        <a href="#panduan" class="inline-flex justify-center items-center px-8 py-4 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                            <i class="fas fa-play-circle mr-2 text-gray-400"></i> Lihat Panduan
                        </a>
                    </div>
                    
                    <div class="pt-8 flex items-center justify-center lg:justify-start space-x-8 text-gray-400">
                        <div class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Gratis</div>
                        <div class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Cepat</div>
                        <div class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Resmi</div>
                    </div>
                </div>

                <div class="relative lg:h-[600px] flex items-center justify-center">
                    <!-- Glassmorphism Card as 'Image' Placeholder since we don't have assets -->
                    <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-gray-100 p-8 transform rotate-2 hover:rotate-0 transition-all duration-500">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600"><i class="fas fa-user"></i></div>
                                <div>
                                    <div class="h-3 w-24 bg-gray-200 rounded mb-1"></div>
                                    <div class="h-2 w-16 bg-gray-100 rounded"></div>
                                </div>
                            </div>
                            <div class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">SELESAI</div>
                        </div>
                        <div class="space-y-4">
                            <div class="h-4 bg-gray-100 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-100 rounded w-full"></div>
                            <div class="h-4 bg-gray-100 rounded w-5/6"></div>
                        </div>
                        <div class="mt-8 pt-6 border-t border-gray-50 flex justify-between items-center">
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 rounded-full bg-blue-500 border-2 border-white"></div>
                                <div class="w-8 h-8 rounded-full bg-green-500 border-2 border-white"></div>
                                <div class="w-8 h-8 rounded-full bg-purple-500 border-2 border-white"></div>
                            </div>
                            <div class="text-sm text-gray-400">Telah diproses</div>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 bg-white p-4 rounded-2xl shadow-xl animate-bounce" style="animation-duration: 3s;">
                        <i class="fas fa-bell text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="absolute -bottom-8 -left-8 bg-blue-600 text-white p-4 rounded-2xl shadow-xl">
                        <div class="font-bold text-2xl">24/7</div>
                        <div class="text-xs opacity-75">Online Service</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section (Q6: Up-to-date News) -->
    @if($beritas->count() > 0)
    <section id="berita" class="py-16 bg-white border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                <div class="max-w-xl">
                    <span class="inline-block py-1 px-3 rounded-full bg-blue-50 text-blue-600 text-xs font-bold mb-3 uppercase tracking-wider">
                        Pusat Informasi
                    </span>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Berita & Pengumuman Terbaru</h2>
                    <p class="text-gray-600">Dapatkan informasi terkini mengenai kegiatan dan kebijakan di lingkungan Kelurahan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($beritas as $berita)
                <div class="group bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Image -->
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute top-4 left-4">
                            <span class="bg-blue-600 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                                {{ $berita->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">
                            {{ $berita->judul }}
                        </h3>
                        <p class="text-gray-500 text-sm mb-6 line-clamp-3">
                            {{ $berita->excerpt }}
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">
                                <i class="fas fa-user-circle mr-1"></i> Admin Kelurahan
                            </span>
                            <a href="#" class="text-blue-600 font-bold text-xs flex items-center group-hover:underline">
                                Baca Selengkapnya <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Fitur Section -->
    <section id="fitur" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Layanan Digital Terpadu</h2>
                <p class="text-gray-600">Kami menghadirkan berbagai fitur untuk memudahkan kebutuhan administrasi kependudukan Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-shadow border border-gray-100">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl mb-6">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pengajuan Surat Online</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Ajukan Surat Pengantar RT, Surat Kelurahan (SKTM, Keterangan Usaha, dll) langsung dari rumah.
                    </p>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-shadow border border-gray-100">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-2xl mb-6">
                        <i class="fas fa-search-location"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Tracking Real-time</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pantau status permohonan Anda secara real-time. Ketahui posisi surat apakah di RT, Kasi, atau Lurah.
                    </p>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-shadow border border-gray-100">
                    <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-2xl mb-6">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Tanda Tangan Digital</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Dokumen yang diterbitkan menggunakan QR Code validasi tanda tangan elektronik Lurah yang sah.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Panduan Section -->
    <section id="panduan" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-50 text-blue-600 text-sm font-semibold mb-4 border border-blue-100">
                    Mudah & Praktis
                </span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Cara Pengajuan Surat</h2>
                <p class="text-gray-600">Ikuti 4 langkah mudah berikut untuk mendapatkan layanan administrasi.</p>
            </div>

            <div class="relative">
                <!-- Connecting Line (Desktop) -->
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-gray-100 -translate-y-1/2 z-0"></div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10">
                    <!-- Step 1 -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                        <div class="w-16 h-16 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mb-4 shadow-lg shadow-blue-500/30">
                            1
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Daftar / Masuk</h3>
                        <p class="text-gray-600 text-sm">Buat akun menggunakan NIK dan Email, atau masuk jika sudah punya akun.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                        <div class="w-16 h-16 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mb-4 shadow-lg shadow-blue-500/30">
                            2
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Ajukan Surat</h3>
                        <p class="text-gray-600 text-sm">Pilih jenis surat, isi formulir, dan upload dokumen persyaratan.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                        <div class="w-16 h-16 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mb-4 shadow-lg shadow-blue-500/30">
                            3
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Verifikasi</h3>
                        <p class="text-gray-600 text-sm">Permohonan akan divalidasi oleh Ketua RT, Kasi, hingga Lurah.</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                        <div class="w-16 h-16 mx-auto bg-green-500 text-white rounded-full flex items-center justify-center text-2xl font-bold mb-4 shadow-lg shadow-green-500/30">
                            4
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Selesai</h3>
                        <p class="text-gray-600 text-sm">Terima notifikasi dan unduh surat digital yang sah langsung dari web.</p>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all shadow-md">
                    Coba Sekarang <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <!-- Footer -->
    <x-landing-footer />

    <script>
        const btn = document.querySelector('.mobile-menu-button');
        const menu = document.querySelector('.mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
