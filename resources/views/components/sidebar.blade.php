@php
$user = Auth::user();
$roleName = $user->role->name;

// Menu berdasarkan role
$menus = [
'admin' => [
['name' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'fas fa-tachometer-alt'],
['name' => 'Management User', 'route' => 'admin.users.index', 'icon' => 'fas fa-users'],
['name' => 'Jenis Surat', 'route' => 'admin.jenis-surat.index', 'icon' => 'fas fa-file-alt'],
['name' => 'Data Wilayah', 'route' => 'admin.wilayah.rt.index', 'icon' => 'fas fa-map-marker-alt'],
['name' => 'Laporan', 'route' => 'admin.laporan.permohonan', 'icon' => 'fas fa-chart-bar'],
],
'rt' => [
['name' => 'Dashboard', 'route' => 'rt.dashboard', 'icon' => 'fas fa-tachometer-alt'],
['name' => 'Permohonan Surat', 'route' => 'rt.permohonan.index', 'icon' => 'fas fa-file-signature'],
],
'kasi' => [
['name' => 'Dashboard', 'route' => 'kasi.dashboard', 'icon' => 'fas fa-tachometer-alt'],
['name' => 'Verifikasi Permohonan', 'route' => 'kasi.permohonan.index', 'icon' => 'fas fa-check-circle'],
],
'lurah' => [
['name' => 'Dashboard', 'route' => 'lurah.dashboard', 'icon' => 'fas fa-tachometer-alt'],
['name' => 'Permohonan TTE', 'route' => 'lurah.permohonan.index', 'icon' => 'fas fa-signature'],
// HAPUS menu yang tidak diperlukan
// ['name' => 'Tanda Tangan Digital', 'route' => 'lurah.tanda-tangan.index', 'icon' => 'fas fa-signature'],
// ['name' => 'Laporan', 'route' => 'lurah.laporan.index', 'icon' => 'fas fa-chart-bar'],
],
'masyarakat' => [
['name' => 'Dashboard', 'route' => 'masyarakat.dashboard', 'icon' => 'fas fa-tachometer-alt'],
['name' => 'Ajukan Permohonan', 'route' => 'masyarakat.permohonan.create', 'icon' => 'fas fa-plus-circle'],
['name' => 'Riwayat Permohonan', 'route' => 'masyarakat.permohonan.index', 'icon' => 'fas fa-history'],
]
];

$currentMenu = $menus[$roleName] ?? [];
$currentRoute = Route::currentRouteName();

// Hitung permohonan pending untuk RT
if ($roleName === 'rt') {
$pendingCount = App\Models\PermohonanSurat::whereHas('user', function($q) use ($user) {
$q->where('rt_id', $user->rt_id);
})->where('status', 'menunggu_rt')->count();
}

// Hitung permohonan pending untuk Kasi
if ($roleName === 'kasi') {
$pendingCount = App\Models\PermohonanSurat::where('status', 'menunggu_kasi')->count();
}

// Hitung permohonan pending untuk Lurah
if ($roleName === 'lurah') {
$pendingCount = App\Models\PermohonanSurat::where('status', 'menunggu_lurah')->count();
}
@endphp

<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-800 text-white transform -translate-x-full lg:translate-x-0 sidebar-transition">
    <div class="p-4">
        <!-- Logo -->
        <div class="flex items-center space-x-3 mb-8">
            <i class="fas fa-building text-2xl text-blue-300"></i>
            <div>
                <h1 class="text-xl font-bold">Sistem Kelurahan</h1>
                <p class="text-blue-200 text-sm capitalize">{{ $roleName }}</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav>
            <ul class="space-y-2">
                @foreach($currentMenu as $menu)
                <li>
                    <a href="{{ route($menu['route']) }}"
                        class="flex items-center space-x-3 p-3 rounded-lg transition-colors {{ $currentRoute === $menu['route'] ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <i class="{{ $menu['icon'] }} w-5 text-center"></i>
                        <span>{{ $menu['name'] }}</span>

                        <!-- Badge untuk permohonan pending RT -->
                        @if($roleName === 'rt' && $menu['route'] === 'rt.permohonan.index' && $pendingCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ $pendingCount }}
                        </span>
                        @endif

                        <!-- Badge untuk permohonan pending Kasi -->
                        @if($roleName === 'kasi' && $menu['route'] === 'kasi.permohonan.index' && $pendingCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ $pendingCount }}
                        </span>
                        @endif

                        <!-- Badge untuk permohonan pending Lurah -->
                        @if($roleName === 'lurah' && $menu['route'] === 'lurah.permohonan.index' && $pendingCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ $pendingCount }}
                        </span>
                        @endif
                    </a>
                </li>
                @endforeach

                <!-- Menu tambahan untuk RT (Coming Soon) -->
                @if($roleName === 'rt')
                <li>
                    <div class="flex items-center space-x-3 p-3 rounded-lg text-blue-300 opacity-75 cursor-not-allowed">
                        <i class="fas fa-home w-5 text-center"></i>
                        <span>Data Keluarga</span>
                        <span class="ml-auto bg-blue-600 text-blue-200 text-xs px-2 py-1 rounded-full">
                            Soon
                        </span>
                    </div>
                </li>
                <li>
                    <div class="flex items-center space-x-3 p-3 rounded-lg text-blue-300 opacity-75 cursor-not-allowed">
                        <i class="fas fa-users w-5 text-center"></i>
                        <span>Data Warga</span>
                        <span class="ml-auto bg-blue-600 text-blue-200 text-xs px-2 py-1 rounded-full">
                            Soon
                        </span>
                    </div>
                </li>
                @endif

                <!-- Menu tambahan untuk Kasi (Coming Soon) -->
                @if($roleName === 'kasi')
                <li>
                    <div class="flex items-center space-x-3 p-3 rounded-lg text-blue-300 opacity-75 cursor-not-allowed">
                        <i class="fas fa-file-contract w-5 text-center"></i>
                        <span>Template Surat</span>
                        <span class="ml-auto bg-blue-600 text-blue-200 text-xs px-2 py-1 rounded-full">
                            Soon
                        </span>
                    </div>
                </li>
                @endif

                <!-- Menu tambahan untuk Lurah (Coming Soon) -->
                @if($roleName === 'lurah')
                <li>
                    <div class="flex items-center space-x-3 p-3 rounded-lg text-blue-300 opacity-75 cursor-not-allowed">
                        <i class="fas fa-chart-bar w-5 text-center"></i>
                        <span>Laporan</span>
                        <span class="ml-auto bg-blue-600 text-blue-200 text-xs px-2 py-1 rounded-full">
                            Soon
                        </span>
                    </div>
                </li>
                @endif

                <!-- Logout -->
                <li class="pt-4 mt-4 border-t border-blue-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-3 p-3 rounded-lg text-blue-100 hover:bg-blue-700 transition-colors w-full text-left">
                            <i class="fas fa-sign-out-alt w-5 text-center"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>