<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Pelayanan Kelurahan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-blue-50/50 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Decorative Blurred Background -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-blue-400/20 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[50%] h-[50%] bg-purple-400/20 rounded-full blur-[120px]"></div>
    </div>

    <!-- Login Card -->
    <div class="bg-white/80 w-full max-w-[450px] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white/50 relative z-10 backdrop-blur-xl p-8 lg:p-10">
        
        <!-- Header Section -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 bg-[#1d4ed8] rounded-full flex items-center justify-center mb-4 shadow-lg shadow-blue-600/20">
                <i class="fas fa-user text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Masuk ke Akun</h1>
            <p class="text-gray-500 text-sm">Silakan masuk untuk mengakses sistem</p>
            <div class="h-1 w-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mt-4"></div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                <div class="relative">
                    <input type="email" name="email" id="email" required autofocus
                        class="w-full rounded-lg bg-gray-50 border border-gray-200 text-gray-900 px-4 py-3 outline-none focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-gray-400"
                        placeholder="Masukkan email anda">
                </div>
                @error('email')
                <p class="text-red-500 text-xs mt-1.5 font-medium ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" name="password" id="password" required
                        class="w-full rounded-lg bg-gray-50 border border-gray-200 text-gray-900 pl-10 pr-10 py-3 outline-none focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all placeholder-gray-400"
                        placeholder="Masukkan kata sandi">
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition-colors">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                <p class="text-red-500 text-xs mt-1.5 font-medium ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-[#1d4ed8] hover:bg-blue-800 text-white font-bold py-3.5 rounded-lg transition-all shadow-lg shadow-blue-700/20 active:transform active:scale-[0.98]">
                Masuk
            </button>
        </form>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50/50 border border-blue-100 rounded-xl p-4 flex gap-3">
            <i class="fas fa-info-circle text-blue-600 mt-0.5 text-lg flex-shrink-0"></i>
            <p class="text-xs text-blue-800 leading-relaxed font-medium">
                Untuk keamanan akun, proses reset dan pemulihan kata sandi dilakukan secara terpusat melalui admin kelurahan. Silakan hubungi staf jika mengalami kendala.
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
