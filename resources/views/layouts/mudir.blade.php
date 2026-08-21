<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Mudir') — SUNTRI</title>
    <meta name="description" content="@yield('meta_description', 'Dashboard Mudir SUNTRI — Laporan Eksekutif Pimpinan Pesantren.')"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

    {{-- Mobile-style Top App Bar --}}
    <header class="fixed top-0 w-full z-40 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 flex justify-between items-center px-4 lg:px-8 h-16">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                <img src="{{ asset('logo.jpg') }}" alt="Logo" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-medium text-on-surface-variant truncate">Assalamu'alaikum,</p>
                <h1 class="text-sm font-bold text-primary truncate max-w-[120px]">Dr. Ilham</h1>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <div class="hidden sm:flex px-3 py-1 rounded-full text-xs font-bold items-center gap-1.5 mr-2" style="background:rgba(251,191,36,0.15); color:#b45309; border:1px solid rgba(251,191,36,0.3);">
                <span class="material-symbols-outlined text-xs" style="font-variation-settings:'FILL' 1; color:#b45309;">workspace_premium</span>
                <span>Mudir — View Only</span>
            </div>
            <a href="{{ route('profile.edit') }}" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-surface-container-high transition-colors text-primary focus:outline-none" title="Profil">
                <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                @csrf
                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-red-50 text-error transition-colors focus:outline-none" title="Keluar">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                </button>
            </form>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="pt-20 pb-24 p-4 lg:px-8 space-y-6 min-h-screen max-w-7xl mx-auto">
        @if(session('success'))
        <div id="flash-success" class="flex items-center gap-3 bg-primary text-white px-5 py-3.5 rounded-xl shadow-lg text-sm font-medium">
            <span class="material-symbols-outlined text-white" style="font-variation-settings:'FILL' 1;">check_circle</span>
            {{ session('success') }}
        </div>
        @endif
        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 w-full z-50 bg-white/90 backdrop-blur-lg border-t border-outline-variant/30 shadow-[0_-4px_20px_-2px_rgba(0,0,0,0.05)] grid grid-cols-5 items-center px-1 py-2 pb-safe">
        @php
            $mudirNav = [
                ['route' => 'mudir.dashboard', 'icon' => 'dashboard',         'label' => 'Beranda'],
                ['route' => 'mudir.hafalan',   'icon' => 'menu_book',          'label' => 'Hafalan'],
                ['route' => 'mudir.kehadiran', 'icon' => 'how_to_reg',         'label' => 'Kehadiran'],
                ['route' => 'mudir.keuangan',  'icon' => 'payments',           'label' => 'Keuangan'],
                ['route' => 'mudir.pengumuman','icon' => 'campaign',           'label' => 'Info'],
            ];
        @endphp
        @foreach($mudirNav as $item)
            @php $isActive = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}"
               class="relative flex flex-col items-center justify-center w-full h-full transition-all duration-200 group
                   {{ $isActive ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }}">
                <div class="relative flex items-center justify-center w-14 h-8 rounded-full mb-1 transition-colors {{ $isActive ? 'bg-primary-container' : 'bg-transparent group-hover:bg-gray-100' }}">
                    <span class="material-symbols-outlined text-[24px]" style="{{ $isActive ? 'font-variation-settings: \'FILL\' 1;' : '' }}">{{ $item['icon'] }}</span>
                </div>
                <span class="text-[10px] w-full text-center px-0.5 {{ $isActive ? 'font-bold text-primary' : 'font-medium' }}">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    @stack('scripts')
</body>
</html>
