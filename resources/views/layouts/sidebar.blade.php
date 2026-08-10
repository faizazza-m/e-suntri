{{-- Sidebar Component --}}
<aside class="h-screen w-64 fixed left-0 top-0 bg-primary text-on-primary shadow-xl glassmorphism border-r border-white/20 z-50 flex flex-col py-6 overflow-y-auto scrollbar-hide">

    {{-- Logo --}}
    <div class="px-6 mb-8 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">mosque</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-on-primary leading-tight tracking-tight">SUNTRI</h1>
            <p class="text-[10px] opacity-70 uppercase tracking-widest font-bold">Islamic Management</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-0.5 px-2">
        @php
            $navItems = [
                ['route' => 'dashboard',      'icon' => 'dashboard',            'label' => 'Dashboard'],
                ['route' => 'pengguna',       'icon' => 'manage_accounts',      'label' => 'Data Pengguna'],
                ['route' => 'akademik',       'icon' => 'school',               'label' => 'Akademik'],
                ['route' => 'kehadiran',      'icon' => 'how_to_reg',           'label' => 'Kehadiran Santri'],
                ['route' => 'tahfizh',        'icon' => 'menu_book',            'label' => 'Tahfizh Center'],
                ['route' => 'keuangan',       'icon' => 'payments',             'label' => 'Keuangan'],
                ['route' => 'perizinan',      'icon' => 'assignment_turned_in', 'label' => 'Perizinan'],
                ['route' => 'kesehatan',      'icon' => 'medical_services',     'label' => 'Kesehatan'],
                ['route' => 'pengumuman',     'icon' => 'campaign',             'label' => 'Pengumuman'],
                ['route' => 'chat',           'icon' => 'chat',                 'label' => 'Pusat Pesan'],
                ['route' => 'ppdb',           'icon' => 'person_add',           'label' => 'PPDB'],
                ['route' => 'prestasi',       'icon' => 'military_tech',        'label' => 'Prestasi'],
            ];
        @endphp

        @foreach($navItems as $item)
            @php
                $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*');
            @endphp
            <a
                href="{{ route($item['route']) }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-300 group
                    {{ $isActive
                        ? 'bg-white/15 text-on-primary border-l-4 border-secondary-fixed'
                        : 'text-on-primary/70 hover:bg-white/8 hover:text-on-primary border-l-4 border-transparent' }}"
            >
                <span class="material-symbols-outlined text-xl"
                    style="{{ $isActive ? 'font-variation-settings: \'FILL\' 1;' : '' }}">{{ $item['icon'] }}</span>
                <span class="text-sm font-medium">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- User Profile Footer --}}
    <div class="mt-auto px-4 pt-4 border-t border-white/10">
        <div class="p-4 bg-white/5 rounded-xl border border-white/10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">person</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-white">{{ auth()->check() ? auth()->user()->name : 'User' }}</p>
                    <p class="text-[10px] text-white/50">{{ auth()->check() ? (auth()->user()->role_id == 1 ? 'Administrator' : (auth()->user()->role_id == 2 ? 'Musyrif' : 'User')) : 'Role' }}</p>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="w-full py-2 bg-error text-white text-[10px] font-bold rounded-lg flex items-center justify-center gap-2 hover:opacity-90 transition-opacity">
                <span class="material-symbols-outlined text-sm">logout</span> KELUAR
            </a>
        </div>
    </div>
</aside>
