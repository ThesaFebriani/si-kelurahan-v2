<x-guest-layout>
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-6">
            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-slate-800 mb-2">Menunggu Verifikasi</h2>
        <p class="text-slate-600 mb-8">
            Terima kasih telah mendaftar. Akun Anda saat ini sedang dalam status <span class="font-semibold text-yellow-600">MENUNGGU VERIFIKASI</span> oleh Ketua RT setempat.
        </p>

        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-left mb-8">
            <h4 class="font-semibold text-blue-800 mb-2">Apa selanjutnya?</h4>
            <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                <li>Ketua RT akan memeriksa data kependudukan Anda.</li>
                <li>Proses ini biasanya memakan waktu 1x24 jam.</li>
                <li>Setelah diverifikasi, Anda dapat login untuk mengajukan surat.</li>
            </ul>
        </div>

        <div class="mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
