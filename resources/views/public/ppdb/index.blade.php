<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Informasi PPDB — SUNTRI Islamic Education Platform</title>
    <meta name="description" content="Portal Pendaftaran Peserta Didik Baru (PPDB) melalui platform SUNTRI."/>
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

    <!-- Right Panel: PPDB Information -->
    <section class="flex-1 flex items-center justify-center p-6 md:p-12 lg:p-20 relative z-10 w-full lg:w-auto min-h-[50vh]">
        <div class="w-full max-w-[440px] space-y-8 fade-in-up">
            
            {{-- Header --}}
            <div class="text-center md:text-left space-y-2">
                <div class="inline-flex items-center justify-center p-3 bg-surface-container-low rounded-2xl mb-4 border border-outline-variant/30 text-primary">
                    <span class="material-symbols-outlined text-3xl">school</span>
                </div>
                <h2 class="text-2xl font-bold text-on-surface">Pendaftaran Santri Baru</h2>
                <p class="text-on-surface-variant text-sm mt-1">Silakan pilih gelombang pendaftaran yang sedang dibuka di bawah ini.</p>
            </div>

            {{-- Batches List --}}
            <div class="space-y-4">
                @forelse($activeBatches as $batch)
                <div class="bg-white border border-outline-variant/30 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-1 h-full emerald-gradient"></div>
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold text-on-surface">{{ $batch->name }}</h3>
                        <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg bg-green-100 text-green-700">Buka</span>
                    </div>
                    
                    <div class="space-y-1.5 mb-4 text-xs text-on-surface-variant">
                        <p class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">calendar_today</span> Mulai: {{ \Carbon\Carbon::parse($batch->start_date)->format('d M Y') }}</p>
                        <p class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">event</span> Selesai: {{ \Carbon\Carbon::parse($batch->end_date)->format('d M Y') }}</p>
                        <p class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">groups</span> Kuota: {{ $batch->quota }} Santri</p>
                    </div>

                    @if($batch->registration_link)
                    <a href="{{ $batch->registration_link }}" target="_blank"
                        class="w-full h-11 emerald-gradient text-white font-bold text-sm rounded-xl shadow flex items-center justify-center gap-2 active:scale-[0.98] transition-all">
                        <span>Daftar Sekarang</span>
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                    </a>
                    @else
                    <button disabled class="w-full h-11 bg-surface-container-highest text-on-surface-variant font-bold text-sm rounded-xl flex items-center justify-center gap-2 cursor-not-allowed">
                        <span>Form Belum Tersedia</span>
                    </button>
                    @endif
                </div>
                @empty
                <div class="bg-surface-container-low border border-dashed border-outline-variant/50 rounded-2xl p-8 text-center">
                    <span class="material-symbols-outlined text-4xl text-outline-variant mb-2" style="font-variation-settings:'FILL' 1;">inbox</span>
                    <h3 class="text-base font-bold text-on-surface">Pendaftaran Ditutup</h3>
                    <p class="text-xs text-on-surface-variant mt-1">Saat ini belum ada gelombang pendaftaran PPDB yang dibuka.</p>
                </div>
                @endforelse
            </div>

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
