<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan & FAQ - SIP Kelurahan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- Navbar -->
    <nav class="bg-white/90 backdrop-blur-md fixed w-full z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-2 group">
                        <div class="bg-blue-600 text-white p-2 rounded-lg group-hover:bg-blue-700 transition-colors">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                        <div>
                            <span class="text-xl font-bold text-gray-900">Pusat Bantuan</span>
                            <p class="text-xs text-gray-500 font-medium">SIP Kelurahan</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <div class="bg-blue-600 pt-32 pb-20 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10">
            <h1 class="text-4xl font-bold text-white mb-4">Bagaimana kami bisa membantu Anda?</h1>
            <p class="text-blue-100 text-lg mb-8">Temukan jawaban atas pertanyaan seputar layanan administrasi kelurahan.</p>
            
            <!-- Search Bar (Optional Visual Only for now as we have full list below) -->
            <div class="max-w-xl mx-auto relative">
                <input type="text" placeholder="Cari pertanyaan (contoh: cara daftar, lupa password)..." 
                       class="w-full pl-12 pr-4 py-4 rounded-full shadow-lg border-2 border-transparent focus:border-blue-300 focus:outline-none text-gray-700"
                       x-data @input="$dispatch('search-faq', $el.value.toLowerCase())">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Content -->
    <div class="max-w-4xl mx-auto px-4 py-12 -mt-10 relative z-20 space-y-8" 
         x-data="{ search: '' }" 
         @search-faq.window="search = $event.detail">
        
        @forelse($faqs as $category => $items)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" 
             x-show="search === '' || '{{ $category }}'.toLowerCase().includes(search) || $el.querySelectorAll('.faq-item').length > 0">
            
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center">
                <i class="fas fa-folder text-blue-500 mr-2"></i>
                <h3 class="font-bold text-gray-800 uppercase tracking-wide text-sm">{{ $category }}</h3>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($items as $faq)
                <div class="faq-item" 
                     x-data="{ open: false }" 
                     x-show="search === '' || '{{ strtolower($faq->question) }}'.includes(search) || '{{ strtolower($faq->answer) }}'.includes(search)">
                    
                    <button @click="open = !open" class="w-full text-left px-6 py-4 focus:outline-none flex justify-between items-center hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-700 pr-8">{{ $faq->question }}</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="open" x-collapse class="px-6 pb-6 pt-2 text-gray-600 leading-relaxed bg-gray-50/50">
                        {!! nl2br(e($faq->answer)) !!}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-2xl shadow-sm">
            <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-800">Belum ada FAQ</h3>
            <p class="text-gray-500">Saat ini belum ada data pertanyaan yang ditambahkan.</p>
        </div>
        @endforelse

        <!-- Empty State Search -->
        <div x-show="search !== '' && $el.previousElementSibling.querySelectorAll('[x-show=\'true\']').length === 0" 
             style="display: none;"
             class="text-center py-12 bg-white rounded-2xl shadow-sm">
            <p class="text-gray-500">Tidak ditemukan hasil pencarian.</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-8 text-center text-sm text-gray-500">
        <p>&copy; {{ date('Y') }} Sistem Informasi Pelayanan Kelurahan. All rights reserved.</p>
    </footer>

</body>
</html>
