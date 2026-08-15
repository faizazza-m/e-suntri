<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'SUNTRI')</title>
    <meta name="description" content="@yield('meta_description', 'SUNTRI – Platform digital untuk wali santri.')"/>
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
                        "primary-container": "#e0f2eb",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#002116",
                        "surface": "#f8f9fc",
                        "surface-container": "#ffffff",
                        "on-surface": "#111827",
                        "on-surface-variant": "#4b5563",
                        "outline": "#e5e7eb",
                        "error": "#ef4444",
                        "success": "#10b981",
                        "warning": "#f59e0b",
                        "info": "#3b82f6"
                    },
                    fontFamily: { "sans": ["Inter", "sans-serif"] },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'nav': '0 -4px 20px -2px rgba(0, 0, 0, 0.03)',
                    }
                },
            },
        };
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fc; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .clean-card {
            background-color: #ffffff;
            border-radius: 1rem;
            border: 1px solid #f3f4f6;
            box-shadow: 0 2px 10px -2px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }
        .clean-card:active {
            transform: scale(0.98);
        }
    </style>
    @stack('styles')
</head>
<body class="text-on-surface min-h-screen pb-24 selection:bg-primary-container selection:text-primary" x-data="{ showNotifs: false }">

    {{-- Mobile Top App Bar --}}
    <header class="fixed top-0 w-full z-40 bg-white/95 backdrop-blur border-b border-outline shadow-sm flex justify-between items-center px-5 h-16">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                <img src="{{ asset('logo.jpg') }}" alt="Logo" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-medium text-on-surface-variant truncate">Assalamu'alaikum,</p>
                <h1 class="text-sm font-bold text-on-surface truncate max-w-[120px]">@yield('greeting_name', 'Bapak/Ibu')</h1>
            </div>
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <button @click="showNotifs = !showNotifs" class="relative w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-50 transition-colors text-on-surface focus:outline-none">
                <span class="material-symbols-outlined text-[20px]">notifications</span>
                @if(isset($globalNotifs) && $globalNotifs->count() > 0)
                <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full border-2 border-white"></span>
                @endif
            </button>
            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                @csrf
                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-red-50 text-error transition-colors focus:outline-none" title="Keluar">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                </button>
            </form>
        </div>
    </header>

    {{-- Notification Dropdown --}}
    <div x-show="showNotifs" 
         @click.away="showNotifs = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-[-10px]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-10px]"
         class="fixed top-16 right-4 left-4 z-50 bg-white rounded-2xl shadow-soft border border-outline overflow-hidden" 
         style="display: none; max-height: 400px; overflow-y: auto;">
        
        <div class="p-4 border-b border-outline bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">notifications</span>
                Notifikasi Terbaru
            </h3>
            <button @click="showNotifs = false" class="text-on-surface-variant hover:text-error transition-colors p-1 rounded-full hover:bg-gray-100">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        
        <div class="divide-y divide-outline">
            @if(isset($globalNotifs) && $globalNotifs->count() > 0)
                @foreach($globalNotifs as $notif)
                <div class="p-4 hover:bg-gray-50 transition-colors flex gap-3 items-start">
                    <div class="w-8 h-8 rounded-full bg-primary-container text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">{{ $notif['icon'] ?? 'notifications' }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-on-surface mb-0.5">{{ $notif['title'] }}</p>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed">{{ $notif['desc'] }}</p>
                        <p class="text-[10px] text-gray-400 mt-1.5 font-medium">{{ $notif['time'] }}</p>
                    </div>
                </div>
                @endforeach
            @else
                <div class="p-8 text-center">
                    <span class="material-symbols-outlined text-gray-300 text-4xl mb-2">notifications_off</span>
                    <p class="text-sm font-medium text-gray-400">Belum ada notifikasi</p>
                </div>
            @endif
        </div>
        <div class="p-3 bg-gray-50 text-center border-t border-outline">
            <a href="{{ route('wali.home') }}" class="text-xs font-bold text-primary hover:underline">Lihat Semua di Dasbor</a>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="pt-20 px-5 space-y-6 max-w-lg mx-auto">
        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 w-full z-50 bg-white/75 backdrop-blur-lg border-t border-white/40 shadow-nav grid grid-cols-5 items-center px-1 py-2 pb-safe">
        @php
            $navItems = [
                ['route' => 'wali.home',      'icon' => 'home',         'label' => 'Beranda'],
                ['route' => 'wali.progres',   'icon' => 'analytics',    'label' => 'Progres'],
                ['route' => 'wali.keuangan',  'icon' => 'payments',     'label' => 'Keuangan'],
                ['route' => 'wali.izin',      'icon' => 'fact_check',   'label' => 'Izin'],
                ['route' => 'wali.chat',      'icon' => 'chat',         'label' => 'Chat'],
            ];
        @endphp
        @foreach($navItems as $item)
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
