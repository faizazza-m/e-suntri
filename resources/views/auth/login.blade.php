<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpg') }}">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login — SUNTRI Islamic Education Platform</title>
    <meta name="description" content="Masuk ke platform SUNTRI untuk mengelola lembaga pendidikan Islam Anda."/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                        "primary-fixed": "#a6f2d1",
                        "primary-fixed-dim": "#8bd6b6",
                        "on-primary-fixed": "#002116",
                        "secondary": "#4059aa",
                        "secondary-container": "#8fa7fe",
                        "on-secondary-container": "#1d3989",
                        "secondary-fixed": "#dce1ff",
                        "surface": "#f8f9ff",
                        "surface-bright": "#f8f9ff",
                        "surface-container-low": "#eff4ff",
                        "surface-container-highest": "#d5e3fc",
                        "on-surface": "#0d1c2e",
                        "on-surface-variant": "#3f4944",
                        "outline": "#6f7973",
                        "outline-variant": "#bec9c2",
                        "error": "#ba1a1a",
                    },
                    fontFamily: { "sans": ["Inter", "sans-serif"] },
                },
            },
        };
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

        .glass-panel {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .islamic-pattern {
            mask-image: radial-gradient(circle, black, transparent);
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0l5 20h20l-15 12 5 23-15-15-15 15 5-23-15-12h20z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
        .emerald-gradient {
            background: linear-gradient(135deg, #065f46 0%, #004532 100%);
            transition: all 0.3s ease;
        }
        .emerald-gradient:hover {
            filter: brightness(1.12);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0, 69, 50, 0.25);
        }
        .floating-input { position: relative; }
        .floating-input input { transition: border-color 0.2s; }
        .floating-input input:focus { outline: none; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { opacity: 0; animation: fadeInUp 0.7s ease-out forwards; }
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
    </style>
</head>
<body class="bg-surface text-on-surface min-h-screen overflow-x-hidden">
<main class="flex flex-col md:flex-row min-h-screen w-full">

    {{-- Left Column: Hero Branding (60%) --}}
    <section class="relative hidden md:flex md:w-[60%] flex-col justify-between p-12 bg-primary-container overflow-hidden">
        {{-- Animated WebGL Shader Background --}}
        <canvas id="shader-canvas" class="absolute inset-0 w-full h-full" style="display:block;"></canvas>

        {{-- Islamic Pattern Overlay --}}
        <div class="absolute inset-0 islamic-pattern opacity-15 pointer-events-none"></div>

        {{-- Content --}}
        <div class="relative z-10 flex flex-col h-full">
            {{-- Brand --}}
            <div class="mb-12 fade-in-up">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                        <img src="{{ asset('logo.jpg') }}" alt="Logo" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h1 class="text-4xl font-extrabold text-white tracking-tight">SUNTRI</h1>
                        <p class="text-white/60 text-xs uppercase tracking-widest font-bold">Islamic Education Platform</p>
                    </div>
                </div>
                <p class="text-white text-3xl font-bold leading-tight max-w-md">
                    One Platform,<br/>Unlimited Services<br/>
                    <span class="text-primary-fixed opacity-90">for Islamic Education.</span>
                </p>
            </div>

            {{-- Feature Highlights --}}
            <div class="mt-auto space-y-7">
                <div class="fade-in-up delay-1 flex items-start gap-5 group cursor-default">
                    <div class="w-12 h-12 flex-shrink-0 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-all duration-300">
                        <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white mb-1">Dashboard Terpadu</h3>
                        <p class="text-white/65 text-sm leading-relaxed">Pantau seluruh aktivitas santri, kehadiran, hafalan, dan keuangan dalam satu tampilan.</p>
                    </div>
                </div>
                <div class="fade-in-up delay-2 flex items-start gap-5 group cursor-default">
                    <div class="w-12 h-12 flex-shrink-0 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-all duration-300">
                        <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">menu_book</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white mb-1">Tahfizh Center</h3>
                        <p class="text-white/65 text-sm leading-relaxed">Tracking hafalan real-time dengan evaluasi Tajwid, Makhraj, dan sertifikat digital.</p>
                    </div>
                </div>
                <div class="fade-in-up delay-3 flex items-start gap-5 group cursor-default">
                    <div class="w-12 h-12 flex-shrink-0 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-all duration-300">
                        <span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">account_balance_wallet</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white mb-1">Keuangan Transparan</h3>
                        <p class="text-white/65 text-sm leading-relaxed">Manajemen tagihan, invoice digital, dan pengingat otomatis via WhatsApp & email.</p>
                    </div>
                </div>
            </div>

            {{-- Footer Quote --}}
            <div class="mt-10 pt-6 border-t border-white/10">
                <p class="text-white/35 text-xs italic">"Connecting Islamic Education Digitally — SUNTRI"</p>
            </div>
        </div>
    </section>

    {{-- Right Column: Login Card (40%) --}}
    <section class="w-full md:w-[40%] bg-surface-bright flex items-center justify-center p-6 md:p-12 min-h-screen">
        <div class="w-full max-w-md glass-panel p-8 md:p-10 rounded-3xl shadow-2xl shadow-secondary/5 border border-white/40">

            {{-- Logo (Mobile only / Card header) --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden shrink-0">
                    <img src="{{ asset('logo.jpg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <h2 class="text-2xl font-bold text-on-surface">Selamat Datang</h2>
                <p class="text-on-surface-variant text-sm mt-1">Silakan masuk ke akun SUNTRI Anda</p>
            </div>

            {{-- Login Form --}}
            <form id="loginForm" class="space-y-5" method="POST" action="{{ route('login.post') }}">
                @csrf


                {{-- Email / NISN --}}
                <div class="space-y-1.5">
                    <label for="login_identifier" class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest px-1">Email / NISN Santri</label>
                    <input
                        id="login_identifier"
                        name="login_identifier"
                        type="text"
                        autocomplete="username"
                        value="{{ old('login_identifier') }}"
                        required
                        class="w-full h-14 px-4 bg-white border @error('login_identifier') border-error @else border-outline-variant @enderror rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm text-on-surface outline-none placeholder-outline"
                        placeholder="Masukkan Email atau NISN..."
                    />
                    @error('login_identifier')
                        <p class="text-xs text-error font-medium px-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest px-1">Password</label>
                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="w-full h-14 px-4 pr-12 bg-white border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm text-on-surface outline-none placeholder-outline"
                            placeholder="Masukkan password..."
                        />
                        <button type="button" id="togglePasswordBtn"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                            <span class="material-symbols-outlined" id="eyeIcon">visibility</span>
                        </button>
                    </div>
                </div>

                {{-- Remember & Forgot --}}
                <div class="flex items-center justify-between py-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary"/>
                        <span class="text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-secondary hover:text-primary transition-colors font-semibold">Lupa Password?</a>
                </div>

                {{-- CTA Button --}}
                <button type="submit" id="loginBtn"
                    class="w-full h-14 emerald-gradient text-white font-bold text-base rounded-xl shadow-lg flex items-center justify-center gap-2 active:scale-[0.98] transition-all">
                    <span id="loginBtnText">Masuk</span>
                    <span class="material-symbols-outlined" id="loginBtnIcon">login</span>
                </button>
            </form>

            {{-- Support Footer --}}
            <div class="mt-8 pt-6 border-t border-outline-variant/30 text-center">
                <p class="text-on-surface-variant text-xs">
                    Butuh bantuan? Hubungi
                    <a href="mailto:support@suntri.id" class="text-primary font-bold hover:underline">Customer Support</a>
                </p>
            </div>
        </div>
    </section>
</main>

<script>
    // Toggle password visibility
    document.getElementById('togglePasswordBtn').addEventListener('click', function() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            pwd.type = 'password';
            icon.textContent = 'visibility';
        }
    });

    // Form submission loading state
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('loginBtn');
        const txt = document.getElementById('loginBtnText');
        const ico = document.getElementById('loginBtnIcon');
        btn.disabled = true;
        txt.textContent = 'Memproses...';
        ico.innerHTML = '<svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    });

    // Animated WebGL Shader
    (function() {
        const canvas = document.getElementById('shader-canvas');
        function syncSize() {
            const w = canvas.clientWidth || 768;
            const h = canvas.clientHeight || 800;
            if (canvas.width !== w || canvas.height !== h) {
                canvas.width = w; canvas.height = h;
            }
        }
        if (typeof ResizeObserver !== 'undefined') new ResizeObserver(syncSize).observe(canvas);
        syncSize();
        const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
        if (!gl) return;
        const vs = `attribute vec2 a_position; varying vec2 v_texCoord;
void main() { v_texCoord = a_position * 0.5 + 0.5; gl_Position = vec4(a_position, 0.0, 1.0); }`;
        const fs = `precision highp float;
uniform float u_time; uniform vec2 u_resolution; varying vec2 v_texCoord;
void main() {
    vec2 uv = v_texCoord;
    vec3 c1 = vec3(0.023, 0.372, 0.274);
    vec3 c2 = vec3(0.117, 0.227, 0.541);
    float t = u_time * 0.18;
    float n = sin(uv.x * 3.2 + t) * cos(uv.y * 2.1 - t) * 0.5 + 0.5;
    gl_FragColor = vec4(mix(c1, c2, uv.y + n * 0.22), 1.0);
}`;
        function cs(type, src) {
            const s = gl.createShader(type); gl.shaderSource(s, src); gl.compileShader(s); return s;
        }
        const prog = gl.createProgram();
        gl.attachShader(prog, cs(gl.VERTEX_SHADER, vs));
        gl.attachShader(prog, cs(gl.FRAGMENT_SHADER, fs));
        gl.linkProgram(prog); gl.useProgram(prog);
        const buf = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, buf);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1,-1,1,-1,-1,1,1,1]), gl.STATIC_DRAW);
        const pos = gl.getAttribLocation(prog, 'a_position');
        gl.enableVertexAttribArray(pos); gl.vertexAttribPointer(pos, 2, gl.FLOAT, false, 0, 0);
        const uTime = gl.getUniformLocation(prog, 'u_time');
        const uRes = gl.getUniformLocation(prog, 'u_resolution');
        function render(t) {
            if (typeof ResizeObserver === 'undefined') syncSize();
            gl.viewport(0, 0, canvas.width, canvas.height);
            if (uTime) gl.uniform1f(uTime, t * 0.001);
            if (uRes) gl.uniform2f(uRes, canvas.width, canvas.height);
            gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
            requestAnimationFrame(render);
        }
        render(0);
    })();
</script>
</body>
</html>
