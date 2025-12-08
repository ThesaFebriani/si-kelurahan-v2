@php
$user = Auth::user();
$roleName = $user->role->name;

// Menu berdasarkan role
$menus = [
    'admin' => [
        ['name' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'fas fa-tachometer-alt'],
        ['name' => 'Management User', 'route' => 'admin.users.index', 'icon' => 'fas fa-users'],
        ['name' => 'Jenis Surat', 'route' => 'admin.jenis-surat.index', 'icon' => 'fas fa-file-alt'],
        ['name' => 'Data RT', 'route' => 'admin.wilayah.rt.index', 'icon' => 'fas fa-map-marker-alt'],
        ['name' => 'Data RW', 'route' => 'admin.wilayah.rw.index', 'icon' => 'fas fa-map'],
        ['name' => 'Laporan', 'route' => 'admin.laporan.permohonan', 'icon' => 'fas fa-chart-bar'],
    ],
    'rt' => [
        ['name' => 'Dashboard', 'route' => 'rt.dashboard', 'icon' => 'fas fa-tachometer-alt'],
        ['name' => 'Permohonan Surat', 'route' => 'rt.permohonan.index', 'icon' => 'fas fa-file-signature'],
        ['name' => 'Arsip Surat', 'route' => 'rt.permohonan.arsip', 'icon' => 'fas fa-archive'],
    ],
    'kasi' => [
        ['name' => 'Dashboard', 'route' => 'kasi.dashboard', 'icon' => 'fas fa-tachometer-alt'],
        ['name' => 'Verifikasi Permohonan', 'route' => 'kasi.permohonan.index', 'icon' => 'fas fa-check-circle'],
        ['name' => 'Arsip Permohonan', 'route' => 'kasi.permohonan.arsip', 'icon' => 'fas fa-archive'],
    ],
    'lurah' => [
        ['name' => 'Dashboard', 'route' => 'lurah.dashboard', 'icon' => 'fas fa-tachometer-alt'],
        ['name' => 'Permohonan TTE', 'route' => 'lurah.permohonan.index', 'icon' => 'fas fa-signature'],
        ['name' => 'Arsip Surat', 'route' => 'lurah.permohonan.arsip', 'icon' => 'fas fa-archive'],
        ['name' => 'Profil Lurah', 'route' => 'lurah.profile', 'icon' => 'fas fa-user-tie'],
    ],
    'masyarakat' => [
        ['name' => 'Dashboard', 'route' => 'masyarakat.dashboard', 'icon' => 'fas fa-tachometer-alt'],
        ['name' => 'Ajukan Permohonan', 'route' => 'masyarakat.permohonan.create', 'icon' => 'fas fa-plus-circle'],
        ['name' => 'Riwayat Permohonan', 'route' => 'masyarakat.permohonan.index', 'icon' => 'fas fa-history'],
        ['name' => 'Profil Saya', 'route' => 'masyarakat.profile.index', 'icon' => 'fas fa-user-circle'],
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

<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transform -translate-x-full lg:translate-x-0 sidebar-transition shadow-2xl flex flex-col justify-between">
    <div class="p-0">
        <!-- Logo -->
        <div class="flex items-center space-x-3 p-6 bg-gradient-to-r from-blue-700 to-blue-900 border-b border-blue-800/50">
            <div class="bg-white/10 p-2 rounded-lg">
                <i class="fas fa-building text-2xl text-blue-300"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-wide">SIP Kelurahan</h1>
                <p class="text-blue-200 text-xs uppercase tracking-wider font-medium opacity-80 mt-0.5">{{ $roleName == 'masyarakat' ? 'Warga' : $roleName }}</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="p-4 mt-2">
            <ul class="space-y-1.5">
                @foreach($currentMenu as $menu)
                <li>
                    <a href="{{ route($menu['route']) }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group relative overflow-hidden
                        {{ $currentRoute === $menu['route'] 
                            ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-500/30' 
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        
                        @if($currentRoute === $menu['route'])
                        <div class="absolute inset-y-0 left-0 w-1 bg-white rounded-r-full"></div>
                        @endif

                        <i class="{{ $menu['icon'] }} w-5 text-center transition-transform group-hover:scale-110 {{ $currentRoute === $menu['route'] ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}"></i>
                        <span class="font-medium text-sm tracking-wide">{{ $menu['name'] }}</span>

                        <!-- Badges -->
                        @php $badgeCount = 0; @endphp
                        @if($roleName === 'rt' && $menu['route'] === 'rt.permohonan.index') @php $badgeCount = $pendingCount ?? 0; @endphp @endif
                        @if($roleName === 'kasi' && $menu['route'] === 'kasi.permohonan.index') @php $badgeCount = $pendingCount ?? 0; @endphp @endif
                        @if($roleName === 'lurah' && $menu['route'] === 'lurah.permohonan.index') @php $badgeCount = $pendingCount ?? 0; @endphp @endif

                        @if($badgeCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                            {{ $badgeCount }}
                        </span>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>

            <!-- Coming Soon / Additional Sections -->
             @if(in_array($roleName, ['rt', 'kasi', 'lurah']))
            <div class="mt-8 mb-2 px-4">
                <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Fitur Mendatang</span>
            </div>
            @endif

            <ul class="space-y-1.5">
                 @if($roleName === 'rt')
                <li>
                    <div class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-500 opacity-60 cursor-not-allowed hover:bg-slate-800/50">
                        <i class="fas fa-home w-5 text-center"></i>
                        <span class="text-sm">Data Keluarga</span>
                        <i class="fas fa-lock ml-auto text-xs"></i>
                    </div>
                </li>
                @endif
            </ul>
        </nav>
    </div>

    <!-- User Profile / Logout Section -->
    <div class="p-4 border-t border-slate-800 bg-slate-900/50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center space-x-3 w-full px-4 py-3 rounded-xl text-red-300 hover:bg-red-500/10 hover:text-red-400 transition-colors group">
                <i class="fas fa-sign-out-alt w-5 text-center group-hover:translate-x-1 transition-transform"></i>
                <span class="font-medium text-sm">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>