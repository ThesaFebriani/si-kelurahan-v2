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
['name' => 'Data Keluarga', 'route' => 'rt.keluarga.index', 'icon' => 'fas fa-home'],
],
'kasi' => [
['name' => 'Dashboard', 'route' => 'kasi.dashboard', 'icon' => 'fas fa-tachometer-alt'],
['name' => 'Verifikasi Permohonan', 'route' => 'kasi.permohonan.index', 'icon' => 'fas fa-check-circle'],
['name' => 'Template Surat', 'route' => 'kasi.template.index', 'icon' => 'fas fa-file-contract'],
],
'lurah' => [
['name' => 'Dashboard', 'route' => 'lurah.dashboard', 'icon' => 'fas fa-tachometer-alt'],
['name' => 'Tanda Tangan Digital', 'route' => 'lurah.tanda-tangan.index', 'icon' => 'fas fa-signature'],
['name' => 'Laporan', 'route' => 'lurah.laporan.index', 'icon' => 'fas fa-chart-bar'],
],
'masyarakat' => [
['name' => 'Dashboard', 'route' => 'masyarakat.dashboard', 'icon' => 'fas fa-tachometer-alt'],
['name' => 'Ajukan Permohonan', 'route' => 'masyarakat.permohonan.create', 'icon' => 'fas fa-plus-circle'],
['name' => 'Riwayat Permohonan', 'route' => 'masyarakat.permohonan.index', 'icon' => 'fas fa-history'],
]
];

$currentMenu = $menus[$roleName] ?? [];
$currentRoute = Route::currentRouteName();
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
                    </a>
                </li>
                @endforeach

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