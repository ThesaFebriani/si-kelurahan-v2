<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Daftar - Sistem Pelayanan Kelurahan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-blue-50/50 min-h-screen flex items-center justify-center p-4 relative">

    <!-- Decorative Blurred Background -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute -top-[10%] -right-[10%] w-[50%] h-[50%] bg-blue-400/20 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-[10%] -left-[10%] w-[50%] h-[50%] bg-indigo-400/20 rounded-full blur-[120px]"></div>
    </div>

    <!-- Register Card -->
    <div class="bg-white/80 w-full max-w-[500px] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/50 relative z-10 backdrop-blur-xl p-8 lg:p-10 my-8">
        
        <!-- Header Section -->
        <div class="flex flex-col items-center mb-8">
            <h1 class="text-2xl font-bold text-blue-700 mb-1">Daftar Akun</h1>
            <p class="text-gray-500 text-sm">Buat akun baru untuk mengakses sistem</p>
            <div class="h-1 w-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mt-4"></div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- NIK -->
            <div>
                <label for="nik" class="block text-sm font-bold text-gray-700 mb-2">NIK (Nomor Induk Kependudukan)</label>
                <div class="relative">
                     <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required maxlength="16" autofocus
                        class="w-full rounded-lg bg-gray-50 border border-gray-200 text-gray-900 px-4 py-3 outline-none focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-gray-400"
                        placeholder="Masukkan NIK Anda (Sesuai KTP)">
                </div>
                <!-- Helper Text -->
                <p class="text-xs text-blue-600 mt-1.5 flex items-center gap-1 font-medium">
                    <i class="fas fa-info-circle"></i> Data diri akan terisi otomatis berdasarkan NIK.
                </p>
                @error('nik')
                <p class="text-red-500 text-xs mt-1.5 font-medium ml-1">{{ $message }}</p>
                @enderror
            </div>



            <!-- Telepon -->
            <div>
                <label for="telepon" class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon / WA</label>
                <div class="relative">
                     <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}" required
                        class="w-full rounded-lg bg-gray-50 border border-gray-200 text-gray-900 px-4 py-3 outline-none focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-gray-400"
                        placeholder="Masukkan nomor telepon anda">
                </div>
                @error('telepon')
                <p class="text-red-500 text-xs mt-1.5 font-medium ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi</label>
                <div class="relative">
                     <input type="password" name="password" id="password" required
                        class="w-full rounded-lg bg-gray-50 border border-gray-200 text-gray-900 px-4 py-3 outline-none focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-gray-400"
                        placeholder="Masukkan kata sandi yang kuat">
                </div>
                @error('password')
                <p class="text-red-500 text-xs mt-1.5 font-medium ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                <div class="relative">
                     <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full rounded-lg bg-gray-50 border border-gray-200 text-gray-900 px-4 py-3 outline-none focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-gray-400"
                        placeholder="Ulangi kata sandi anda">
                </div>
            </div>

            <button type="submit" class="w-full bg-[#1d4ed8] hover:bg-blue-800 text-white font-bold py-3.5 rounded-lg transition-all shadow-lg shadow-blue-700/20 active:transform active:scale-[0.98] mt-4">
                Daftar Sekarang
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col items-center">
            <p class="text-sm text-gray-500 mb-3">Sudah punya akun?</p>
            <a href="{{ route('login') }}" class="px-6 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold rounded-lg border border-gray-200 transition-all text-sm">
                Masuk di sini
            </a>
        </div>
    </div>
    <!-- Floating Help Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="https://wa.me/{{ $adminPhone }}" target="_blank" 
            class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white rounded-full px-4 py-3 shadow-lg transition-transform hover:scale-105">
            <i class="fab fa-whatsapp text-2xl"></i>
            <span class="font-semibold hidden md:inline">Bantuan</span>
        </a>
    </div>
</body>
</html>