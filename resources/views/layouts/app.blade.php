<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'SUNTRI') — SUNTRI Islamic Education Platform</title>
    <meta name="description" content="@yield('meta_description', 'SUNTRI – Smart Unified Network for Santri Information & Services. Platform digital terpadu untuk lembaga pendidikan Islam.')"/>
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
                        "primary-fixed": "#a6f2d1",
                        "primary-fixed-dim": "#8bd6b6",
                        "on-primary-fixed": "#002116",
                        "on-primary-fixed-variant": "#00513b",
                        "secondary": "#4059aa",
                        "secondary-container": "#8fa7fe",
                        "on-secondary": "#ffffff",
                        "on-secondary-container": "#1d3989",
                        "secondary-fixed": "#dce1ff",
                        "secondary-fixed-dim": "#b6c4ff",
                        "on-secondary-fixed": "#00164e",
                        "on-secondary-fixed-variant": "#264191",
                        "tertiary": "#393d3f",
                        "tertiary-container": "#505456",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-container": "#c5c8ca",
                        "tertiary-fixed": "#e0e3e5",
                        "tertiary-fixed-dim": "#c4c7c9",
                        "on-tertiary-fixed": "#191c1e",
                        "on-tertiary-fixed-variant": "#444749",
                        "background": "#f8f9ff",
                        "on-background": "#0d1c2e",
                        "surface": "#f8f9ff",
                        "surface-dim": "#ccdbf3",
                        "surface-bright": "#f8f9ff",
                        "on-surface": "#0d1c2e",
                        "on-surface-variant": "#3f4944",
                        "surface-variant": "#d5e3fc",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#eff4ff",
                        "surface-container": "#e6eeff",
                        "surface-container-high": "#dce9ff",
                        "surface-container-highest": "#d5e3fc",
                        "surface-tint": "#1b6b51",
                        "inverse-surface": "#233144",
                        "inverse-on-surface": "#eaf1ff",
                        "inverse-primary": "#8bd6b6",
                        "outline": "#6f7973",
                        "outline-variant": "#bec9c2",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                        "on-error": "#ffffff",
                        "on-error-container": "#93000a",
                        "gold-accent": "#fbbf24",
                    },
                    fontFamily: {
                        "sans": ["Inter", "sans-serif"],
                    },
                    fontSize: {
                        "display-lg": ["48px", { lineHeight: "56px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "display-lg-mobile": ["32px", { lineHeight: "40px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "headline-md": ["24px", { lineHeight: "32px", fontWeight: "600" }],
                        "body-lg": ["18px", { lineHeight: "28px", fontWeight: "400" }],
                        "body-md": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                        "label-sm": ["14px", { lineHeight: "20px", letterSpacing: "0.01em", fontWeight: "500" }],
                        "label-xs": ["12px", { lineHeight: "16px", fontWeight: "600" }],
                    },
                    spacing: {
                        "margin-desktop": "48px",
                        "margin-mobile": "16px",
                        "gutter": "24px",
                        "container-max": "1280px",
                        "base": "8px",
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px",
                    },
                },
            },
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
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }
        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l5.878 18.09h19.022l-15.39 11.18 5.878 18.09L30 36.18l-15.39 11.18 5.878-18.09-15.39-11.18h19.022L30 0z' fill='%23004532' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        .shimmer-button:hover {
            animation: shine 1.5s linear infinite;
            background-size: 200% auto;
        }
        @keyframes shine { to { background-position: 200% center; } }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .progress-ring__circle {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
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

        /* Dropdown animation */
        .dropdown-enter {
            animation: dropdownIn 0.18s ease-out both;
        }
        @keyframes dropdownIn {
            from { opacity: 0; transform: translateY(-8px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
    @stack('styles')
</head>
<body class="islamic-pattern min-h-screen bg-surface text-on-surface">

    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Top Header --}}
    <header class="flex justify-between items-center w-full px-8 h-20 ml-64 max-w-[calc(100%-16rem)] sticky top-0 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 z-40">

        {{-- Search --}}
        <div class="flex items-center gap-6">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input
                    type="text"
                    id="globalSearch"
                    class="pl-10 pr-4 py-2 bg-surface-container rounded-full border-none focus:ring-2 focus:ring-primary text-label-sm w-72 outline-none"
                    placeholder="Cari data santri, pengumuman..."
                    autocomplete="off"
                />
                {{-- Search Suggestions Dropdown --}}
                <div id="searchDropdown" class="hidden dropdown-enter absolute top-full mt-2 left-0 w-80 bg-white rounded-2xl shadow-2xl border border-outline-variant/20 overflow-hidden z-50">
                    <div class="p-3 border-b border-outline-variant/10">
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Akses Cepat</p>
                    </div>
                    <div class="p-2">
                        @php
                            $searchLinks = [
                                ['icon'=>'school',              'label'=>'Akademik',        'href'=>route('akademik'),       'color'=>'text-secondary'],
                                ['icon'=>'menu_book',           'label'=>'Tahfizh Center',  'href'=>route('tahfizh'),        'color'=>'text-primary'],
                                ['icon'=>'payments',            'label'=>'Keuangan',        'href'=>route('keuangan'),       'color'=>'text-purple-600'],
                                ['icon'=>'assignment_turned_in','label'=>'Perizinan',       'href'=>route('perizinan'),      'color'=>'text-orange-600'],
                                ['icon'=>'campaign',            'label'=>'Pengumuman',      'href'=>route('pengumuman'),     'color'=>'text-blue-600'],
                                ['icon'=>'medical_services',    'label'=>'Kesehatan',       'href'=>route('kesehatan'),      'color'=>'text-error'],
                            ];
                        @endphp
                        @foreach($searchLinks as $sl)
                        <a href="{{ $sl['href'] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined {{ $sl['color'] }} text-xl" style="font-variation-settings:'FILL' 1;">{{ $sl['icon'] }}</span>
                            <span class="text-sm font-medium text-on-surface">{{ $sl['label'] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Icons --}}
        <div class="flex items-center gap-2">

            {{-- 🔔 Notifications --}}
            <div class="relative">
                <button id="btnNotif" onclick="toggleDropdown('dropdownNotif')"
                    class="p-2 hover:bg-surface-container-high rounded-full transition-colors relative">
                    <span class="material-symbols-outlined text-primary">notifications</span>
                    @php $notifCount = \App\Models\Perizinan::where('status','pending')->count(); @endphp
                    @if($notifCount > 0)
                    <span class="absolute top-1 right-1 min-w-[16px] h-4 bg-error rounded-full border-2 border-surface flex items-center justify-center text-white text-[8px] font-bold px-0.5">
                        {{ $notifCount > 9 ? '9+' : $notifCount }}
                    </span>
                    @endif
                </button>
                <div id="dropdownNotif" class="hidden dropdown-enter absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-outline-variant/20 overflow-hidden z-50">
                    <div class="bg-primary px-4 py-3 flex justify-between items-center">
                        <h4 class="text-sm font-bold text-white">Notifikasi</h4>
                        @if($notifCount > 0)
                        <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $notifCount }} baru</span>
                        @endif
                    </div>
                    <div class="max-h-72 overflow-y-auto custom-scrollbar">
                        @php $perizinanPending = \App\Models\Perizinan::with('santri')->where('status','pending')->orderByDesc('created_at')->take(5)->get(); @endphp
                        @forelse($perizinanPending as $pz)
                        <a href="{{ route('perizinan') }}" class="flex gap-3 px-4 py-3 hover:bg-surface-container border-b border-outline-variant/10 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-orange-100 flex-shrink-0 flex items-center justify-center">
                                <span class="material-symbols-outlined text-orange-600 text-sm" style="font-variation-settings:'FILL' 1;">assignment_turned_in</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-on-surface">
                                    <strong>{{ $pz->santri?->nama ?? 'Santri' }}</strong> mengajukan izin {{ $pz->jenis }}
                                </p>
                                <p class="text-[10px] text-on-surface-variant mt-0.5">{{ \Carbon\Carbon::parse($pz->created_at)->diffForHumans() }}</p>
                            </div>
                        </a>
                        @empty
                        <div class="px-4 py-8 text-center">
                            <span class="material-symbols-outlined text-3xl text-outline mb-2 block">notifications_off</span>
                            <p class="text-sm text-on-surface-variant">Tidak ada notifikasi baru</p>
                        </div>
                        @endforelse
                    </div>
                    <div class="p-2">
                        <a href="{{ route('perizinan') }}" class="block text-center text-xs font-bold text-primary py-2 hover:bg-surface-container rounded-xl transition-colors">
                            Lihat Semua Perizinan →
                        </a>
                    </div>
                </div>
            </div>

            {{-- ⚙️ Settings --}}
            <div class="relative">
                <button id="btnSettings" onclick="toggleDropdown('dropdownSettings')"
                    class="p-2 hover:bg-surface-container-high rounded-full transition-colors">
                    <span class="material-symbols-outlined text-primary">settings</span>
                </button>
                <div id="dropdownSettings" class="hidden dropdown-enter absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-outline-variant/20 overflow-hidden z-50">
                    <div class="px-4 py-3 border-b border-outline-variant/10">
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Pengaturan Sistem</p>
                    </div>
                    <div class="p-2">
                        @php
                            $settingItems = [
                                ['icon'=>'manage_accounts',       'label'=>'Manajemen Pengguna', 'href'=>'#',                    'color'=>'text-primary'],
                                ['icon'=>'school',                'label'=>'Pengaturan Kelas',   'href'=>route('akademik'),       'color'=>'text-secondary'],
                                ['icon'=>'mosque',                'label'=>'Profil Pesantren',   'href'=>'#',                    'color'=>'text-emerald-600'],
                                ['icon'=>'notifications_active',  'label'=>'Notifikasi',         'href'=>'#',                    'color'=>'text-orange-500'],
                            ];
                        @endphp
                        @foreach($settingItems as $si)
                        <a href="{{ $si['href'] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined {{ $si['color'] }} text-xl">{{ $si['icon'] }}</span>
                            <span class="text-sm text-on-surface">{{ $si['label'] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ❓ Help --}}
            <div class="relative">
                <button id="btnHelp" onclick="toggleDropdown('dropdownHelp')"
                    class="p-2 hover:bg-surface-container-high rounded-full transition-colors">
                    <span class="material-symbols-outlined text-primary">help</span>
                </button>
                <div id="dropdownHelp" class="hidden dropdown-enter absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-2xl border border-outline-variant/20 overflow-hidden z-50">
                    <div class="px-4 py-3 border-b border-outline-variant/10">
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Bantuan & Info</p>
                    </div>
                    <div class="p-2">
                        <div class="px-3 py-2.5">
                            <p class="text-sm font-bold text-on-surface">SUNTRI Platform</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">Versi 1.0.0 — Build {{ date('Y') }}</p>
                        </div>
                        <hr class="border-outline-variant/10 my-1">
                        @php
                            $helpItems = [
                                ['icon'=>'menu_book',    'label'=>'Dokumentasi',        'href'=>'#'],
                                ['icon'=>'support_agent','label'=>'Hubungi Support',    'href'=>'mailto:support@suntri.id'],
                                ['icon'=>'info',         'label'=>'Tentang SUNTRI',     'href'=>'#'],
                            ];
                        @endphp
                        @foreach($helpItems as $hi)
                        <a href="{{ $hi['href'] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined text-on-surface-variant text-xl">{{ $hi['icon'] }}</span>
                            <span class="text-sm text-on-surface">{{ $hi['label'] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="h-8 w-px bg-outline-variant/30 mx-1"></div>

            {{-- 👤 Admin Profile --}}
            <div class="relative">
                <button id="btnProfile" onclick="toggleDropdown('dropdownProfile')"
                    class="flex items-center gap-3 px-3 py-1.5 rounded-full hover:bg-surface-container-high transition-colors">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-primary leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-on-surface-variant leading-tight">
                            {{ auth()->user()->role_id == 1 ? 'Administrator' : (auth()->user()->role_id == 2 ? 'Musyrif' : 'User') }}
                        </p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-base" style="font-variation-settings: 'FILL' 1;">person</span>
                    </div>
                </button>
                <div id="dropdownProfile" class="hidden dropdown-enter absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-2xl border border-outline-variant/20 overflow-hidden z-50">
                    <div class="bg-gradient-to-br from-primary to-emerald-700 px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-full bg-white/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-xl" style="font-variation-settings:'FILL' 1;">person</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-white/70">{{ auth()->user()->email }}</p>
                                <span class="text-[9px] font-bold bg-white/20 text-white px-2 py-0.5 rounded-full inline-block mt-1">
                                    {{ auth()->user()->role_id == 1 ? 'Administrator' : (auth()->user()->role_id == 2 ? 'Musyrif' : 'User') }}
                                </span>
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

    {{-- Backdrop to close dropdowns --}}
    <div id="dropdownBackdrop" class="fixed inset-0 z-30 hidden" onclick="closeAllDropdowns()"></div>

    {{-- Main Content --}}
    <main class="ml-64 p-8 space-y-6 min-h-screen">
        {{-- Flash Messages --}}
        @if(session('success'))
        <div id="flash-success" class="flex items-center gap-3 bg-primary text-white px-5 py-3.5 rounded-xl shadow-lg text-sm font-medium">
            <span class="material-symbols-outlined text-white" style="font-variation-settings:'FILL' 1;">check_circle</span>
            {{ session('success') }}
            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-white/70 hover:text-white">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        @endif
        @if(session('error'))
        <div id="flash-error" class="flex items-center gap-3 bg-error text-white px-5 py-3.5 rounded-xl shadow-lg text-sm font-medium">
            <span class="material-symbols-outlined text-white" style="font-variation-settings:'FILL' 1;">error</span>
            {{ session('error') }}
            <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-white/70 hover:text-white">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
        @endif

        @yield('content')
    </main>

    <script>
        // Dropdown toggle logic
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
            ['dropdownNotif','dropdownSettings','dropdownHelp','dropdownProfile','searchDropdown'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.add('hidden');
            });
            const backdrop = document.getElementById('dropdownBackdrop');
            if (backdrop) backdrop.classList.add('hidden');
        }
        // Search focus shows quick-access
        document.addEventListener('DOMContentLoaded', function() {
            const gs = document.getElementById('globalSearch');
            if (gs) {
                gs.addEventListener('focus', function() {
                    document.getElementById('searchDropdown').classList.remove('hidden');
                    document.getElementById('dropdownBackdrop').classList.remove('hidden');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
