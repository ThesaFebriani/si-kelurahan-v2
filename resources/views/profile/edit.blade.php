<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Top Section: Edit Profile Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="bg-[#1e3a8a] px-6 py-4 flex justify-between items-center">
                    <h3 class="text-white font-semibold text-lg flex items-center gap-2">
                        <i class="fas fa-user-edit"></i> Informasi Profil
                    </h3>
                    <span class="text-blue-100 text-sm bg-blue-800/50 px-3 py-1 rounded-full">Data Pengguna</span>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Middle Section: Profile Summary (Read Only) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="bg-[#1e3a8a] px-6 py-4 flex justify-between items-center">
                    <h3 class="text-white font-semibold text-lg flex items-center gap-2">
                        <i class="fas fa-id-card"></i> Ringkasan Profil
                    </h3>
                    <span class="text-blue-100 text-sm bg-blue-800/50 px-3 py-1 rounded-full">Total: 4 Informasi</span>
                </div>
                <div class="p-6">
                     <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Field</th>
                                    <th scope="col" class="px-6 py-3">Value</th>
                                    <th scope="col" class="px-6 py-3 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-user text-blue-600"></i> Peran
                                    </td>
                                    <td class="px-6 py-4">Masyarakat</td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded border border-orange-200">User</span>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-envelope text-blue-600"></i> Verifikasi Email
                                    </td>
                                    <td class="px-6 py-4">{{ Auth::user()->email }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if(Auth::user()->hasVerifiedEmail())
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Terverifikasi</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-200">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-id-badge text-blue-600"></i> NIK
                                    </td>
                                    <td class="px-6 py-4">{{ Auth::user()->nik ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Tersedia</span>
                                    </td>
                                </tr>
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900 flex items-center gap-2">
                                        <i class="fas fa-toggle-on text-blue-600"></i> Status Akun
                                    </td>
                                    <td class="px-6 py-4">Aktif</td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Aktif</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Update Password -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="bg-[#1e3a8a] px-6 py-4 flex justify-between items-center">
                     <h3 class="text-white font-semibold text-lg flex items-center gap-2">
                        <i class="fas fa-shield-alt"></i> Keamanan Akun
                     </h3>
                     <span class="text-blue-100 text-sm bg-blue-800/50 px-3 py-1 rounded-full">Update Password</span>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User currently hidden/minimized as it's less used, or we keep it plain styled at bottom -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
