@auth
@php
$user = Auth::user();
$roleName = $user->role->name ?? 'guest';

// Menu data definition
$menus = [
    'admin' => [
        ['header' => 'UTAMA'],
        ['name' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'fas fa-tachometer-alt'],
        
        ['header' => 'KEPENDUDUKAN & WILAYAH'],
        [
            'name' => 'Kependudukan', 
            'icon' => 'fas fa-users',
            'id' => 'menu-kependudukan',
            'submenu' => [
                ['name' => 'Data Warga', 'route' => 'admin.kependudukan.keluarga.index'],
                ['name' => 'Data RT', 'route' => 'admin.wilayah.rt.index'],
                ['name' => 'Data RW', 'route' => 'admin.wilayah.rw.index'],
                ['name' => 'Data Bidang (Kasi)', 'route' => 'admin.bidang.index'],
            ]
        ],

        ['header' => 'LAYANAN DIGITAL'],
        [
            'name' => 'Pengaturan Surat',
            'icon' => 'fas fa-file-invoice',
            'id' => 'menu-surat',
            'submenu' => [
                ['name' => 'Jenis Surat', 'route' => 'admin.jenis-surat.index'],
                ['name' => 'Template Surat', 'route' => 'admin.templates.index'],
                ['name' => 'Format Pengantar', 'route' => 'admin.settings.surat-pengantar'],
            ]
        ],

        ['header' => 'INFORMASI & PUBLIKASI'],
        ['name' => 'Kelola Berita', 'route' => 'admin.berita.index', 'icon' => 'fas fa-bullhorn'],
        ['name' => 'FAQ & Bantuan', 'route' => 'admin.faqs.index', 'icon' => 'fas fa-question-circle'],
        ['name' => 'Profil Instansi', 'route' => 'admin.settings.index', 'icon' => 'fas fa-landmark'],

        ['header' => 'MANAJEMEN SISTEM'],
        ['name' => 'Laporan & Statistik', 'route' => 'admin.reports.index', 'icon' => 'fas fa-chart-pie'],
        [
            'name' => 'Keamanan & Log',
            'icon' => 'fas fa-shield-alt',
            'id' => 'menu-system',
            'submenu' => [
                ['name' => 'Manajemen Pengguna', 'route' => 'admin.users.index'],
                ['name' => 'Log Aktivitas', 'route' => 'admin.audit-logs.index'],
            ]
        ],
    ],
    'rt' => [
        ['name' => 'Dashboard', 'route' => 'rt.dashboard', 'icon' => 'fas fa-tachometer-alt'],
        ['name' => 'Data Keluarga', 'route' => 'rt.keluarga.index', 'icon' => 'fas fa-users-cog'],
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

// Get active menu IDs from server side to initialize
$activeMenuIds = collect($currentMenu)
    ->filter(fn($m) => isset($m['submenu']) && collect($m['submenu'])->pluck('route')->contains($currentRoute))
    ->pluck('id')
    ->toArray();

// Pending count logic
$pendingCount = 0;
if ($roleName === 'rt') {
    $pendingCount = App\Models\PermohonanSurat::whereHas('user', function($q) use ($user) {
        $q->where('rt_id', $user->rt_id);
    })->where('status', 'menunggu_rt')->count();
}
if ($roleName === 'kasi') {
    $pendingCount = App\Models\PermohonanSurat::where('status', 'menunggu_kasi')
        ->when($user->bidang, function($q) use ($user) {
            $q->whereHas('jenisSurat', function($sub) use ($user) {
                $sub->where('bidang', $user->bidang);
            });
        })->count();
}
if ($roleName === 'lurah') {
    $pendingCount = App\Models\PermohonanSurat::where('status', 'menunggu_lurah')->count();
}
@endphp

<aside x-data="sidebarData()"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transform -translate-x-full lg:translate-x-0 sidebar-transition shadow-2xl flex flex-col justify-between overflow-y-auto custom-scrollbar">
    
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
                    @if(isset($menu['header']))
                        <li class="px-4 mt-6 mb-2">
                            <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider pl-2">{{ $menu['header'] }}</span>
                        </li>
                    @elseif(isset($menu['submenu']))
                        {{-- Dropdown Menu --}}
                        @php 
                            $isChildActive = collect($menu['submenu'])->pluck('route')->contains($currentRoute);
                        @endphp
                        <li>
                            <button @click="toggleMenu('{{ $menu['id'] }}')"
                                    class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-200 group relative
                                    {{ $isChildActive ? 'text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                
                                <i class="{{ $menu['icon'] }} w-5 text-center transition-transform group-hover:scale-110 {{ $isChildActive ? 'text-blue-400' : 'text-slate-400 group-hover:text-blue-400' }}"></i>
                                <span class="font-medium text-sm tracking-wide">{{ $menu['name'] }}</span>
                                
                                <i class="fas fa-chevron-right ml-auto text-[10px] transition-transform duration-200"
                                   :class="openMenus.includes('{{ $menu['id'] }}') ? 'rotate-90' : ''"></i>
                            </button>

                            <ul x-show="openMenus.includes('{{ $menu['id'] }}')" 
                                x-cloak
                                class="mt-1 space-y-1 ml-9 border-l border-slate-700/50 pl-2">
                                @foreach($menu['submenu'] as $sub)
                                    <li>
                                        <a href="{{ route($sub['route']) }}" 
                                           class="block px-4 py-2 text-xs font-medium rounded-lg transition-colors
                                           {{ $currentRoute === $sub['route'] ? 'text-blue-400 bg-blue-400/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                                            {{ $sub['name'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        {{-- Regular Menu --}}
                        <li>
                            <a href="{{ route($menu['route']) }}"
                                class="flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-200 group relative overflow-hidden
                                {{ $currentRoute === $menu['route'] 
                                    ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-500/30' 
                                    : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                
                                @if($currentRoute === $menu['route'])
                                <div class="absolute inset-y-0 left-0 w-1 bg-white rounded-r-full"></div>
                                @endif

                                <i class="{{ $menu['icon'] }} w-5 text-center transition-transform group-hover:scale-110 {{ $currentRoute === $menu['route'] ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}"></i>
                                <span class="font-medium text-sm tracking-wide">{{ $menu['name'] }}</span>

                                {{-- Badges --}}
                                @php 
                                    $badgeCount = 0; 
                                    if(in_array($roleName, ['rt', 'kasi', 'lurah']) && $menu['route'] === "{$roleName}.permohonan.index") {
                                        $badgeCount = $pendingCount;
                                    }
                                @endphp

                                @if($badgeCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                                    {{ $badgeCount }}
                                </span>
                                @endif
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            {{-- External Links --}}
            <ul class="space-y-1.5 border-t border-slate-800/50 mt-6 pt-4">
                <li>
                    <a href="{{ route('privacy.policy') }}"
                        class="flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-200 text-slate-400 hover:bg-slate-800 hover:text-white">
                        <i class="fas fa-shield-alt w-5 text-center text-slate-500"></i>
                        <span class="font-medium text-sm tracking-wide">Kebijakan Privasi</span>
                    </a>
                </li>
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

<script>
    function sidebarData() {
        return {
            mobileOpen: false,
            openMenus: [],
            init() {
                const stored = localStorage.getItem('sidebar_open_menus');
                if (stored) {
                    this.openMenus = JSON.parse(stored);
                } else {
                    this.openMenus = @json($activeMenuIds);
                }
                const activeIds = @json($activeMenuIds);
                activeIds.forEach(id => {
                    if (!this.openMenus.includes(id)) this.openMenus.push(id);
                });
            },
            toggleMenu(id) {
                if (this.openMenus.includes(id)) {
                    this.openMenus = this.openMenus.filter(i => i !== id);
                } else {
                    this.openMenus.push(id);
                }
                localStorage.setItem('sidebar_open_menus', JSON.stringify(this.openMenus));
            }
        };
    }
</script>
@endauth