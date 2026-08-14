<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpg') }}">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'SUNTRI') — Portal Musyrif</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <script>
    tailwind.config = {
        theme: { extend: {
            colors: {
                "primary":"#2563eb","primary-container":"#dbeafe","on-primary":"#ffffff", // Blue theme
                "secondary":"#10b981","on-secondary":"#ffffff","secondary-container":"#d1fae5",
                "surface":"#f8fafc","on-surface":"#0f172a","on-surface-variant":"#334155",
                "surface-container-low":"#f1f5f9","surface-container":"#e2e8f0",
                "surface-container-high":"#cbd5e1","surface-container-highest":"#94a3b8",
                "outline":"#64748b","outline-variant":"#cbd5e1",
                "error":"#ef4444","on-error":"#ffffff",
            },
            fontFamily: { sans:["Inter","sans-serif"] },
        }},
    };
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family:'Inter',sans-serif; background:#f8fafc; min-height:100vh; }

        /* Dot-matrix background */
        .dot-bg {
            background-color: #f8fafc;
            background-image: radial-gradient(rgba(37,99,235,0.08) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* Sidebar */
        .sidebar { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
        .nav-item { transition: all 0.2s ease; }
        .nav-item.active { background: rgba(255,255,255,0.18); border-left: 3px solid #fde047; }
        .nav-item:not(.active) { border-left: 3px solid transparent; }
        .nav-item:not(.active):hover { background: rgba(255,255,255,0.10); }

        /* Animations */
        .fade-up { animation: fadeUp 0.5s ease both; }
        .fade-up-1 { animation-delay: 0.05s; }
        .fade-up-2 { animation-delay: 0.1s; }
        .fade-up-3 { animation-delay: 0.15s; }
        .fade-up-4 { animation-delay: 0.2s; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }

        /* Material icons */
        .material-symbols-outlined { font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24; user-select:none; }

        /* Scrollbar */
        ::-webkit-scrollbar { width:3px; height:3px; }
        ::-webkit-scrollbar-thumb { background:rgba(37,99,235,0.3); border-radius:99px; }
    </style>
    @stack('styles')
</head>
<body class="dot-bg text-on-surface">

{{-- ===================== SIDEBAR ===================== --}}
<aside id="sidebar"
    class="sidebar fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-primary to-blue-800 z-40 flex flex-col shadow-2xl
           -translate-x-full lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10 shrink-0">
        <div class="w-10 h-10 rounded-2xl bg-white/15 flex items-center justify-center shadow-inner overflow-hidden border border-white/20">
            <img src="{{ asset('logo.jpg') }}" alt="Logo" class="w-full h-full object-cover">
        </div>
        <div>
            <p class="text-white font-black text-lg leading-tight tracking-tight">SUNTRI</p>
            <p class="text-white/70 text-[9px] font-bold uppercase tracking-widest">Portal Musyrif</p>
        </div>
    </div>

    {{-- Nav Links --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        @php
            $navItems = [
                ['route'=>'musyrif.dashboard', 'icon'=>'dashboard',     'label'=>'Dashboard'],
                ['route'=>'musyrif.tahfizh',   'icon'=>'menu_book',     'label'=>'Tahfizh & Hafalan'],
                ['route'=>'musyrif.rekap',     'icon'=>'assessment',    'label'=>'Rekap Data'],
                ['route'=>'musyrif.pengumuman','icon'=>'campaign',      'label'=>'Pengumuman'],
                ['route'=>'musyrif.chat',      'icon'=>'chat',          'label'=>'Pusat Pesan'],
            ];
        @endphp
        @foreach($navItems as $item)
        @php $active = request()->routeIs($item['route']); @endphp
        <a href="{{ route($item['route']) }}"
           class="nav-item {{ $active ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl text-white group">
            <span class="material-symbols-outlined text-xl shrink-0 {{ $active ? 'text-yellow-300' : 'text-white/60 group-hover:text-white' }}"
                  style="{{ $active ? "font-variation-settings:'FILL' 1;" : '' }}">{{ $item['icon'] }}</span>
            <span class="text-sm font-{{ $active ? 'bold' : 'medium' }} {{ $active ? 'text-white' : 'text-white/70 group-hover:text-white' }}">{{ $item['label'] }}</span>
            @if($active)
            <span class="ml-auto text-[9px] font-black text-yellow-300">●</span>
            @endif
        </a>
        @endforeach
    </nav>

    {{-- User Profile Footer --}}
    <div class="border-t border-white/10 p-4 shrink-0">
        <div class="flex items-center gap-3 mb-3 p-3 rounded-xl bg-white/10">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-white text-base" style="font-variation-settings:'FILL' 1;">person</span>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Musyrif' }}</p>
                <p class="text-[10px] text-white/70">Pembina / Musyrif</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full py-2.5 rounded-xl bg-error/90 hover:bg-error flex items-center justify-center gap-2 text-white transition-colors group">
                <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">logout</span>
                <span class="text-xs font-bold tracking-wider">KELUAR</span>
            </button>
        </form>
    </div>
</aside>

{{-- ===================== TOP BAR (Mobile) ===================== --}}
<header class="lg:hidden fixed top-0 inset-x-0 z-30 bg-primary shadow-lg flex items-center justify-between px-5 h-14">
    <div class="flex items-center gap-2">
        <div class="w-6 h-6 rounded-md overflow-hidden bg-white">
            <img src="{{ asset('logo.jpg') }}" alt="Logo" class="w-full h-full object-cover">
        </div>
        <span class="text-white font-bold text-base tracking-tight">SUNTRI <span class="text-white/50 font-normal text-sm">Musyrif</span></span>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('profile.edit') }}" class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 border border-white/10 flex items-center justify-center transition-colors shadow-sm">
            <span class="material-symbols-outlined text-white text-[18px]" style="font-variation-settings:'FILL' 1;">person</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
            @csrf
            <button type="submit" class="w-9 h-9 rounded-full bg-error/90 hover:bg-error flex items-center justify-center text-white transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[16px]">logout</span>
            </button>
        </form>
    </div>
</header>

{{-- ===================== DESKTOP TOP BAR ===================== --}}
<div class="hidden lg:block fixed top-0 left-64 right-0 z-30 bg-white/80 backdrop-blur-md border-b border-outline-variant/20 h-14 px-8 flex items-center shadow-sm">
    <div class="h-14 flex items-center justify-between">
        <p class="text-sm text-on-surface-variant font-medium">
            <span class="font-bold text-on-surface">@yield('title', 'Dashboard')</span>
            <span class="mx-2 text-outline">·</span>
            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMM Y') }}
        </p>
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-primary/5 border border-primary/10">
                <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings:'FILL' 1;">person</span>
                <span class="text-xs font-bold text-primary">{{ auth()->user()->name ?? 'Musyrif' }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MAIN CONTENT ===================== --}}
<div class="lg:ml-64">
    <main class="pt-14 lg:pt-14 pb-24 lg:pb-8 px-4 sm:px-6 lg:px-8 py-6 min-h-screen">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mt-4 mb-2 flex items-center gap-3 bg-green-500 text-white px-4 py-3 rounded-2xl shadow-lg text-sm font-medium fade-up">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">check_circle</span>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mt-4 mb-2 flex items-center gap-3 bg-error text-white px-4 py-3 rounded-2xl shadow-lg text-sm font-medium fade-up">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">error</span>
            {{ session('error') }}
        </div>
        @endif

        <div class="pt-4 lg:pt-6">
            @yield('content')
        </div>
    </main>
</div>

{{-- ===================== BOTTOM NAV (Mobile) ===================== --}}
<nav class="lg:hidden fixed bottom-0 inset-x-0 z-30 bg-white border-t border-outline-variant/20 shadow-2xl flex safe-bottom">
    @php
        $mobileItems = [
            ['route'=>'musyrif.dashboard', 'icon'=>'dashboard',  'label'=>'Beranda'],
            ['route'=>'musyrif.tahfizh',   'icon'=>'menu_book',  'label'=>'Tahfizh'],
            ['route'=>'musyrif.rekap',     'icon'=>'assessment', 'label'=>'Rekap'],
            ['route'=>'musyrif.pengumuman','icon'=>'campaign',   'label'=>'Info'],
            ['route'=>'musyrif.chat',      'icon'=>'forum',      'label'=>'Chat'],
        ];
    @endphp
    @foreach($mobileItems as $item)
    @php $active = request()->routeIs($item['route']); @endphp
    <a href="{{ route($item['route']) }}"
       class="flex-1 flex flex-col items-center justify-center py-2 gap-0.5 relative {{ $active ? 'text-primary' : 'text-on-surface-variant' }}">
        @if($active)
        <span class="absolute top-0 left-1/2 -translate-x-1/2 w-10 h-0.5 bg-primary rounded-b-full"></span>
        @endif
        <span class="material-symbols-outlined text-[22px]" style="{{ $active ? "font-variation-settings:'FILL' 1;" : '' }}">{{ $item['icon'] }}</span>
        <span class="text-[10px] font-{{ $active ? 'bold' : 'medium' }}">{{ $item['label'] }}</span>
    </a>
    @endforeach
</nav>

@stack('scripts')
</body>
</html>
