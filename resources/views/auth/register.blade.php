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
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Data Akun Section -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">
                        <i class="fas fa-lock mr-2 text-blue-500"></i> Informasi Akun
                    </h3>
                </div>

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

                <!-- Data Pribadi Section -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">
                        <i class="fas fa-id-card mr-2 text-green-500"></i> Data Pribadi
                    </h3>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2.5 px-4"
                        placeholder="Sesuai KTP">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIK -->
                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK (16 Digit)</label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required maxlength="16"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2.5 px-4"
                        placeholder="1234567890123456">
                    @error('nik')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jk" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                    <select name="jk" id="jk" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2.5 px-4 bg-white" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="laki-laki" {{ old('jk') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ old('jk') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jk')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RT / RW -->
                <div>
                    <label for="rt_id" class="block text-sm font-medium text-gray-700 mb-2">Wilayah RT / RW</label>
                    <select name="rt_id" id="rt_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2.5 px-4 bg-white" required>
                        <option value="">-- Pilih RT --</option>
                        @foreach($rts as $rt)
                            <option value="{{ $rt->id }}" {{ old('rt_id') == $rt->id ? 'selected' : '' }}>
                                RT {{ $rt->nomor_rt }} / RW {{ $rt->rw->nomor_rw }}
                            </option>
                        @endforeach
                    </select>
                    @error('rt_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="col-span-1 md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="2" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm py-2.5 px-4"
                        placeholder="Nama jalan, nomor rumah, blok, dll.">{{ old('alamat') }}</textarea>
                    @error('alamat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
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