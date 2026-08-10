@php
    $role = auth()->user()->role_id ?? 1;
    if ($role == 5) {
        $layout = 'layouts.guru';
    } elseif ($role == 2) {
        $layout = 'layouts.musyrif';
    } else {
        $layout = 'layouts.app';
    }
@endphp
@extends($layout)

@section('title', 'Ganti Password — SUNTRI')
@section('meta_description', 'Perbarui kata sandi akun SUNTRI Anda.')

@section('content')

<div class="max-w-xl mx-auto space-y-6 fade-in-up">

    {{-- Page Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('profile.edit') }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-on-surface">Ganti Password</h1>
            <p class="text-sm text-on-surface-variant">Perbarui kata sandi akun Anda secara berkala</p>
        </div>
    </div>

    {{-- Password Card --}}
    <div class="glassmorphism rounded-2xl border border-white/20 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-br from-orange-500 to-red-600 px-8 py-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings:'FILL' 1;">lock_reset</span>
            </div>
            <div>
                <p class="text-white font-bold text-lg">Keamanan Akun</p>
                <p class="text-white/70 text-sm">Gunakan password yang kuat dan unik</p>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('profile.password.update') }}" class="p-8 space-y-5">
            @csrf
            @method('PUT')

            {{-- Password Lama --}}
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">
                    Password Saat Ini
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">lock</span>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full h-13 pl-12 pr-12 py-3.5 bg-surface-container border @error('current_password') border-error @else border-outline-variant @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition-all"
                        placeholder="Password saat ini...">
                    <button type="button" onclick="togglePwd('current_password','icon0')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-xl" id="icon0">visibility</span>
                    </button>
                </div>
                @error('current_password')
                <div class="flex items-center gap-1.5 mt-1.5">
                    <span class="material-symbols-outlined text-error text-sm" style="font-variation-settings:'FILL' 1;">error</span>
                    <p class="text-xs text-error font-medium">{{ $message }}</p>
                </div>
                @enderror
            </div>

            <hr class="border-outline-variant/20">

            {{-- Password Baru --}}
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">
                    Password Baru
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">key</span>
                    <input type="password" name="password" id="new_password" required
                        class="w-full h-13 pl-12 pr-12 py-3.5 bg-surface-container border @error('password') border-error @else border-outline-variant @enderror rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition-all"
                        placeholder="Minimal 8 karakter..."
                        oninput="checkStrength(this.value)">
                    <button type="button" onclick="togglePwd('new_password','icon1')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-xl" id="icon1">visibility</span>
                    </button>
                </div>
                @error('password')
                <div class="flex items-center gap-1.5 mt-1.5">
                    <span class="material-symbols-outlined text-error text-sm" style="font-variation-settings:'FILL' 1;">error</span>
                    <p class="text-xs text-error font-medium">{{ $message }}</p>
                </div>
                @enderror

                {{-- Password Strength --}}
                <div class="mt-2 space-y-1">
                    <div class="flex gap-1">
                        <div id="s1" class="h-1 flex-1 rounded-full bg-outline-variant/30 transition-colors duration-300"></div>
                        <div id="s2" class="h-1 flex-1 rounded-full bg-outline-variant/30 transition-colors duration-300"></div>
                        <div id="s3" class="h-1 flex-1 rounded-full bg-outline-variant/30 transition-colors duration-300"></div>
                        <div id="s4" class="h-1 flex-1 rounded-full bg-outline-variant/30 transition-colors duration-300"></div>
                    </div>
                    <p id="strengthLabel" class="text-[10px] text-on-surface-variant font-medium">Masukkan password untuk cek kekuatan</p>
                </div>
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">
                    Konfirmasi Password Baru
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">key</span>
                    <input type="password" name="password_confirmation" id="confirm_password" required
                        class="w-full h-13 pl-12 pr-12 py-3.5 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none transition-all"
                        placeholder="Ulangi password baru...">
                    <button type="button" onclick="togglePwd('confirm_password','icon2')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-xl" id="icon2">visibility</span>
                    </button>
                </div>
            </div>

            {{-- Tips --}}
            <div class="bg-primary/5 border border-primary/20 rounded-xl p-4 space-y-1.5">
                <p class="text-xs font-bold text-primary flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">tips_and_updates</span>
                    Tips Password Kuat
                </p>
                <ul class="text-xs text-on-surface-variant space-y-1 pl-5 list-disc">
                    <li>Minimal 8 karakter</li>
                    <li>Kombinasi huruf besar, kecil, angka</li>
                    <li>Gunakan simbol (!, @, #, $, dll)</li>
                    <li>Jangan gunakan info pribadi yang mudah ditebak</li>
                </ul>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('profile.edit') }}"
                    class="flex-1 py-3 text-center border border-outline-variant text-on-surface-variant rounded-xl text-sm font-bold hover:bg-surface-container transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-xl text-sm font-bold hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">lock_reset</span>
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function togglePwd(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    function checkStrength(val) {
        const bars   = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document.getElementById('s4')];
        const label  = document.getElementById('strengthLabel');
        let score    = 0;

        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors  = ['bg-error','bg-orange-400','bg-yellow-400','bg-primary'];
        const labels  = ['Sangat Lemah','Lemah','Sedang','Kuat'];
        const txtClrs = ['text-error','text-orange-500','text-yellow-600','text-primary'];

        bars.forEach((b, i) => {
            b.className = 'h-1 flex-1 rounded-full transition-colors duration-300 ' + (i < score ? colors[score - 1] : 'bg-outline-variant/30');
        });

        if (val.length === 0) {
            label.textContent = 'Masukkan password untuk cek kekuatan';
            label.className   = 'text-[10px] text-on-surface-variant font-medium';
        } else {
            label.textContent = 'Kekuatan: ' + labels[score - 1];
            label.className   = 'text-[10px] font-medium ' + txtClrs[score - 1];
        }
    }
</script>
@endpush
