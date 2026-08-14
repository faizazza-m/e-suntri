@php
    $role = auth()->user()->role_id ?? 1;
    if ($role == 5) {
        $layout = 'layouts.guru';
        $dashboardRoute = 'guru.dashboard';
    } elseif ($role == 2) {
        $layout = 'layouts.musyrif';
        $dashboardRoute = 'musyrif.dashboard';
    } elseif ($role == 6) {
        $layout = 'layouts.mudir';
        $dashboardRoute = 'mudir.dashboard';
    } elseif ($role == 3) {
        $layout = 'layouts.wali';
        $dashboardRoute = 'wali.home';
    } else {
        $layout = 'layouts.app';
        $dashboardRoute = 'dashboard';
    }
@endphp
@extends($layout)

@section('title', 'Edit Profil — SUNTRI')
@section('meta_description', 'Edit informasi profil akun SUNTRI Anda.')

@section('content')

<div class="max-w-3xl mx-auto space-y-6 fade-in-up">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route($dashboardRoute) }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-on-surface">Edit Profil</h1>
                <p class="text-sm text-on-surface-variant">Perbarui informasi akun Anda</p>
            </div>
        </div>
        
        <a href="{{ route('logout') }}" onclick="return confirm('Apakah Anda yakin ingin keluar?');" 
           class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-error/10 text-error rounded-xl font-bold hover:bg-error hover:text-white transition-colors">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">logout</span>
            <span class="hidden sm:inline text-sm">Keluar</span>
        </a>
    </div>

    {{-- Profile Card --}}
    <div class="glassmorphism rounded-2xl border border-white/20 shadow-sm overflow-hidden">

        {{-- Cover & Avatar --}}
        <div class="h-28 bg-gradient-to-br from-primary to-emerald-700 relative">
            <div class="absolute -bottom-10 left-8">
                <div class="w-20 h-20 rounded-2xl bg-white border-4 border-white shadow-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings:'FILL' 1;">person</span>
                </div>
            </div>
        </div>
        <div class="pt-14 px-8 pb-6 border-b border-outline-variant/20">
            <h2 class="text-xl font-bold text-on-surface">{{ $user->name }}</h2>
            <p class="text-sm text-on-surface-variant">{{ $user->email }}</p>
            <span class="mt-1 inline-block text-[10px] font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-full">
                {{ $user->role_id == 1 ? 'Administrator' : ($user->role_id == 2 ? 'Musyrif' : 'User') }}
            </span>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('profile.update') }}" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">badge</span>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full h-13 pl-12 pr-4 py-3.5 bg-surface-container border @error('name') border-error @else border-outline-variant @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition-all"
                            placeholder="Nama lengkap...">
                    </div>
                    @error('name')
                    <p class="text-xs text-error mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">mail</span>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full h-13 pl-12 pr-4 py-3.5 bg-surface-container border @error('email') border-error @else border-outline-variant @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition-all"
                            placeholder="Email...">
                    </div>
                    @error('email')
                    <p class="text-xs text-error mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">
                        Nomor Telepon
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">phone</span>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                            class="w-full h-13 pl-12 pr-4 py-3.5 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition-all"
                            placeholder="08xxxxxxxxxx...">
                    </div>
                </div>
            </div>

            {{-- Info box --}}
            <div class="flex items-start gap-3 p-4 bg-secondary/5 border border-secondary/20 rounded-xl">
                <span class="material-symbols-outlined text-secondary text-xl mt-0.5" style="font-variation-settings:'FILL' 1;">info</span>
                <p class="text-sm text-on-surface-variant">Role akun tidak dapat diubah sendiri. Hubungi Super Administrator untuk perubahan role.</p>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route($dashboardRoute) }}"
                    class="flex-1 py-3 text-center border border-outline-variant text-on-surface-variant rounded-xl text-sm font-bold hover:bg-surface-container transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 bg-primary text-white rounded-xl text-sm font-bold hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Quick Link to Password --}}
    <a href="{{ route('profile.password') }}"
        class="glassmorphism flex items-center gap-4 p-5 rounded-2xl border border-white/20 hover:border-primary/30 hover:shadow-md transition-all group">
        <div class="w-11 h-11 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-orange-500 transition-colors">
            <span class="material-symbols-outlined text-orange-600 group-hover:text-white transition-colors">lock</span>
        </div>
        <div>
            <p class="text-sm font-bold text-on-surface">Ganti Password</p>
            <p class="text-xs text-on-surface-variant">Perbarui kata sandi akun Anda</p>
        </div>
        <span class="material-symbols-outlined text-on-surface-variant ml-auto group-hover:text-primary transition-colors">chevron_right</span>
    </a>

</div>

@endsection
