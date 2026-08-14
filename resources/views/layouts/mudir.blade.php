<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpg') }}">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Mudir') — SUNTRI</title>
    <meta name="description" content="@yield('meta_description', 'Dashboard Mudir SUNTRI — Laporan Eksekutif Pimpinan Pesantren.')"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#004532",
                        "primary-container": "#065f46",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#8bd6b7",
                        "secondary": "#4059aa",
                        "secondary-container": "#8fa7fe",
                        "on-secondary": "#ffffff",
                        "tertiary": "#393d3f",
                        "background": "#f8f9ff",
                        "on-background": "#0d1c2e",
                        "surface": "#f8f9ff",
                        "on-surface": "#0d1c2e",
                        "on-surface-variant": "#3f4944",
                        "surface-container-low": "#eff4ff",
                        "surface-container": "#e6eeff",
                        "surface-container-high": "#dce9ff",
                        "outline": "#6f7973",
                        "outline-variant": "#bec9c2",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                        "on-error": "#ffffff",
                        "gold-accent": "#fbbf24",
                    },
                    fontFamily: { "sans": ["Inter", "sans-serif"] },
                }
            }
        };
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }
        .glassmorphism {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l5.878 18.09h19.022l-15.39 11.18 5.878 18.09L30 36.18l-15.39 11.18 5.878-18.09-15.39-11.18h19.022L30 0z' fill='%23004532' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #065f46; border-radius: 10px; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp 0.5s ease-out both; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .dropdown-enter { animation: dropdownIn 0.18s ease-out both; }
        @keyframes dropdownIn {
            from { opacity: 0; transform: translateY(-8px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        /* Gold shimmer for Mudir header */
        .mudir-header-bg {
            background: linear-gradient(135deg, #004532 0%, #065f46 50%, #004532 100%);
        }
        .gold-badge {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
        }
    </style>
    @stack('styles')
</head>
<body class="islamic-pattern min-h-screen bg-surface text-on-surface">

    {{-- Sidebar Mudir --}}
    <div id="sidebarBackdrop" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>
    <aside id="sidebar" class="h-screen w-64 fixed left-0 top-0 bg-primary text-on-primary shadow-xl border-r border-white/20 z-50 flex flex-col py-6 overflow-y-auto scrollbar-hide -translate-x-full lg:translate-x-0 transition-transform duration-300" style="background: linear-gradient(180deg,#004532 0%,#003326 100%);">

        {{-- Logo --}}
        <div class="px-6 mb-2 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center overflow-hidden" style="background:rgba(251,191,36,0.2); border:1px solid rgba(251,191,36,0.3);">
                <img src="{{ asset('logo.jpg') }}" alt="Logo" class="w-full h-full object-cover">
            </div>
            <div>
                <h1 class="text-xl font-bold text-white leading-tight tracking-tight">SUNTRI</h1>
                <p class="text-[10px] uppercase tracking-widest font-bold" style="color:rgba(251,191,36,0.8);">Mudir Portal</p>
            </div>
        </div>

        {{-- Gold badge --}}
        <div class="mx-4 mb-6 mt-2 px-3 py-2 rounded-xl flex items-center gap-2" style="background:rgba(251,191,36,0.12); border:1px solid rgba(251,191,36,0.25);">
            <span class="material-symbols-outlined text-sm" style="color:#fbbf24; font-variation-settings:'FILL' 1;">workspace_premium</span>
            <span class="text-xs font-bold" style="color:#fbbf24;">Dashboard Eksekutif</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 space-y-0.5 px-2">
            @php
                $mudirNav = [
                    ['route' => 'mudir.dashboard', 'icon' => 'dashboard',         'label' => 'Dashboard'],
                    ['route' => 'mudir.hafalan',   'icon' => 'menu_book',          'label' => 'Laporan Hafalan'],
                    ['route' => 'mudir.kehadiran', 'icon' => 'how_to_reg',         'label' => 'Laporan Kehadiran'],
                    ['route' => 'mudir.keuangan',  'icon' => 'payments',           'label' => 'Laporan Keuangan'],
                    ['route' => 'mudir.santri',    'icon' => 'group',              'label' => 'Data Santri'],
                    ['route' => 'mudir.pengumuman','icon' => 'campaign',           'label' => 'Pengumuman'],
                ];
            @endphp
            @foreach($mudirNav as $item)
                @php
                    $isActive = request()->routeIs($item['route']);
                    $routeExists = \Illuminate\Support\Facades\Route::has($item['route']);
                @endphp
                @if($routeExists)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 group
                       {{ $isActive
                           ? 'text-white border-l-4'
                           : 'text-white/60 hover:text-white border-l-4 border-transparent hover:bg-white/5' }}"
                   style="{{ $isActive ? 'background:rgba(251,191,36,0.15); border-color:#fbbf24;' : '' }}">
                    <span class="material-symbols-outlined text-xl"
                        style="{{ $isActive ? 'font-variation-settings: \'FILL\' 1; color:#fbbf24;' : '' }}">{{ $item['icon'] }}</span>
                    <span class="text-sm font-medium {{ $isActive ? 'text-white' : '' }}">{{ $item['label'] }}</span>
                </a>
                @else
                <div class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/30 cursor-not-allowed border-l-4 border-transparent">
                    <span class="material-symbols-outlined text-xl">{{ $item['icon'] }}</span>
                    <span class="text-sm font-medium">{{ $item['label'] }}</span>
                    <span class="ml-auto text-[9px] bg-white/10 px-1.5 py-0.5 rounded font-bold">Segera</span>
                </div>
                @endif
            @endforeach
        </nav>

        {{-- User Footer --}}
        <div class="mt-auto px-4 pt-4 border-t border-white/10">
            <div class="p-4 rounded-xl border border-white/10" style="background:rgba(255,255,255,0.05);">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background:rgba(251,191,36,0.2);">
                        <span class="material-symbols-outlined text-sm" style="color:#fbbf24; font-variation-settings:'FILL' 1;">person</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-white">{{ auth()->check() ? auth()->user()->name : 'Mudir' }}</p>
                        <p class="text-[10px]" style="color:rgba(251,191,36,0.7);">Kepala Pesantren</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="w-full mb-2 py-1.5 text-white/60 text-[10px] font-bold rounded-lg flex items-center justify-center gap-1.5 hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-xs">manage_accounts</span> EDIT PROFIL
                </a>
                <a href="{{ route('logout') }}" class="w-full py-2 text-white text-[10px] font-bold rounded-lg flex items-center justify-center gap-2 hover:opacity-90 transition-opacity" style="background:#ba1a1a;">
                    <span class="material-symbols-outlined text-sm">logout</span> KELUAR
                </a>
            </div>
        </div>
    </aside>

    {{-- Top Header --}}
    <header class="flex justify-between items-center w-full px-4 lg:px-8 h-20 lg:ml-64 lg:max-w-[calc(100%-16rem)] sticky top-0 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 z-30">
        <div class="flex items-center gap-3">
            {{-- Mobile Menu Button --}}
            <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl hover:bg-surface-container-high transition-colors text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1;">menu</span>
            </button>
            <div class="px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5" style="background:rgba(251,191,36,0.15); color:#b45309; border:1px solid rgba(251,191,36,0.3);">
                <span class="material-symbols-outlined text-xs" style="font-variation-settings:'FILL' 1; color:#b45309;">workspace_premium</span>
                <span class="hidden sm:inline">Mudir — View Only</span>
            </div>
            <div class="text-sm text-on-surface-variant hidden lg:block">
                {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </div>

        <div class="flex items-center gap-2">
            {{-- Profile --}}
            <div class="relative">
                <button id="btnProfile" onclick="toggleDropdown('dropdownProfile')"
                    class="flex items-center gap-3 px-3 py-1.5 rounded-full hover:bg-surface-container-high transition-colors">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-primary leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] leading-tight" style="color:#b45309;">Kepala Pesantren</p>
                    </div>
                    <div class="w-9 h-9 rounded-full flex items-center justify-center gold-badge">
                        <span class="material-symbols-outlined text-white text-base" style="font-variation-settings:'FILL' 1;">person</span>
                    </div>
                </button>
                <div id="dropdownProfile" class="hidden dropdown-enter absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-2xl border border-outline-variant/20 overflow-hidden z-50">
                    <div class="px-4 py-4" style="background: linear-gradient(135deg, #004532, #065f46);">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-white/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-xl" style="font-variation-settings:'FILL' 1;">person</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-white/70">{{ auth()->user()->email }}</p>
                                <span class="text-[9px] font-bold px-2 py-0.5 rounded-full inline-block mt-1" style="background:rgba(251,191,36,0.25); color:#fbbf24;">Kepala Pesantren</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined text-on-surface-variant text-xl">manage_accounts</span>
                            <span class="text-sm text-on-surface">Edit Profil</span>
                        </a>
                        <a href="{{ route('profile.password') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined text-on-surface-variant text-xl">lock</span>
                            <span class="text-sm text-on-surface">Ganti Password</span>
                        </a>
                        <hr class="border-outline-variant/10 my-1">
                        <a href="{{ route('logout') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-50 transition-colors">
                            <span class="material-symbols-outlined text-error text-xl">logout</span>
                            <span class="text-sm text-error font-semibold">Keluar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div id="dropdownBackdrop" class="fixed inset-0 z-30 hidden" onclick="closeAllDropdowns()"></div>

    {{-- Main Content --}}
    <main class="lg:ml-64 p-4 lg:p-8 space-y-6 min-h-screen">
        @if(session('success'))
        <div id="flash-success" class="flex items-center gap-3 bg-primary text-white px-5 py-3.5 rounded-xl shadow-lg text-sm font-medium">
            <span class="material-symbols-outlined text-white" style="font-variation-settings:'FILL' 1;">check_circle</span>
            {{ session('success') }}
        </div>
        @endif
        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        function toggleDropdown(id) {
            const el = document.getElementById(id);
            const backdrop = document.getElementById('dropdownBackdrop');
            const isHidden = el.classList.contains('hidden');
            closeAllDropdowns();
            if (isHidden) {
                el.classList.remove('hidden');
                el.classList.add('dropdown-enter');
                backdrop.classList.remove('hidden');
            }
        }
        function closeAllDropdowns() {
            ['dropdownProfile'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            });
            const backdrop = document.getElementById('dropdownBackdrop');
            if (backdrop) backdrop.classList.add('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
