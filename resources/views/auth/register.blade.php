<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - Sistem Pelayanan Kelurahan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-10 flex items-center justify-center">

    <div class="fixed top-6 left-6 z-10">
        <a href="/" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors bg-white/80 px-4 py-2 rounded-lg backdrop-blur-sm shadow-sm">
            <i class="fas fa-arrow-left"></i>
            <span class="font-medium">Kembali ke Beranda</span>
        </a>
    </div>

    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl p-8 lg:p-10 border border-gray-100 mx-4">
        <div class="text-center mb-10">
            <div class="inline-flex bg-green-600 text-white p-3 rounded-xl mb-4 shadow-lg shadow-green-500/30">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Buat Akun Baru</h2>
            <p class="text-gray-500 mt-2">Lengkapi data diri Anda untuk mengakses layanan kelurahan</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- NIK Section (Prominent) -->
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK (16 Digit - Sesuai KTP)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-400"></i>
                        </div>
                        <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required maxlength="16" autofocus
                            class="block w-full pl-10 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-3"
                            placeholder="Contoh: 3278...">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Data diri Anda akan diambil otomatis berdasarkan NIK ini.</p>
                    @error('nik')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2.5 px-4"
                            placeholder="nama@email.com">
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No Telepon -->
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon / WA</label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2.5 px-4"
                            placeholder="081234567890">
                        @error('telepon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" id="password" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2.5 px-4"
                            placeholder="••••••••">
                        @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2.5 px-4"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t pt-6">
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:text-blue-800 transition-colors">Masuk disini</a>
        </div>
    </div>
</body>
</html>