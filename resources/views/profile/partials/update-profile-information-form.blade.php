<section>
    <div class="mb-4">
        <h2 class="text-lg font-medium text-gray-900">
            Perbarui Informasi Profil
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Perbarui informasi profil dan alamat surel akun Anda.
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- NIK (Read Only usually, or editable if desired. Making it Read Only styled for safety unless explicit) -->
            <div>
                <x-input-label for="nik" :value="__('NIK')" />
                <div class="relative">
                    <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full bg-gray-100 text-gray-500 cursor-not-allowed" :value="old('nik', $user->nik)" readonly />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">NIK tidak dapat diubah.</p>
            </div>

            <!-- No HP -->
            <div>
                <x-input-label for="telepon" :value="__('Nomor HP')" />
                <x-text-input id="telepon" name="telepon" type="text" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" :value="old('telepon', $user->telepon)" placeholder="08xxxxxxxxxx" />
                <x-input-error class="mt-2" :messages="$errors->get('telepon')" />
            </div>
        </div>

        <!-- Profile Photo Dummy UI -->
        <div class="flex items-center space-x-6 mt-4 p-4 border rounded-lg border-dashed border-gray-300 bg-gray-50">
            <div class="shrink-0">
                <div class="h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 text-2xl">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <label class="block">
                <span class="sr-only">Choose profile photo</span>
                <input type="file" disabled class="block w-full text-sm text-slate-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100
                cursor-not-allowed opacity-60
                "/>
                <span class="text-xs text-gray-400 mt-1 block">Foto profil belum tersedia di versi ini (PNG, JPG, GIF hingga 2MB)</span>
            </label>
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button class="bg-[#1e3a8a] text-white px-6 py-2.5 rounded-lg hover:bg-blue-800 transition-colors shadow-lg shadow-blue-500/30">
                <i class="fas fa-save mr-2"></i> {{ __('Simpan Perubahan') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium flex items-center gap-1"
                ><i class="fas fa-check-circle"></i> {{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
