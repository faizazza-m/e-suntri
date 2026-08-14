@extends('layouts.mudir')

@section('title', 'Dashboard Mudir — SUNTRI')
@section('meta_description', 'Laporan eksekutif pimpinan pesantren SUNTRI.')

@section('content')

{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
    <div class="flex items-center gap-4">
        <div class="p-3 rounded-2xl shadow-lg" style="background:linear-gradient(135deg,#004532,#065f46);">
            <span class="material-symbols-outlined text-3xl" style="color:#fbbf24; font-variation-settings:'FILL' 1;">workspace_premium</span>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-primary">Dashboard Mudir</h2>
            <p class="text-sm text-on-surface-variant">Assalamu'alaikum, {{ explode(' ', auth()->user()->name)[0] }}. Berikut ringkasan kondisi pesantren hari ini.</p>
        </div>
    </div>
    <div class="glassmorphism px-5 py-2.5 rounded-xl border border-outline-variant/30 text-right shadow-sm">
        <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">{{ now()->locale('id')->isoFormat('dddd') }}</div>
        <div class="text-sm font-bold text-primary">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
    </div>
</div>

{{-- KPI Cards --}}
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 fade-in-up delay-1">
    @php
    $kpis = [
        ['icon'=>'group',            'label'=>'Total Santri',    'value'=> number_format($totalSantri),   'sub'=>$totalMusyrif.' Musyrif · '.$totalUstadz.' Ustadz', 'grad'=>'from-emerald-600 to-primary'],
        ['icon'=>'how_to_reg',       'label'=>'Kehadiran Hari',  'value'=> $pctKehadiran.'%',             'sub'=>$hadirCount.' hadir dari '.$totalSantri,             'grad'=>'from-blue-500 to-blue-700'],
        ['icon'=>'menu_book',        'label'=>'Setoran Hari Ini','value'=> number_format($setoranHariIni),'sub'=>'Ziyadah + Muraja\'ah',                              'grad'=>'from-amber-500 to-orange-600'],
        ['icon'=>'exit_to_app',      'label'=>'Santri Izin',     'value'=> $santriIzin,                   'sub'=>'Izin disetujui hari ini',                           'grad'=>'from-orange-500 to-red-500'],
        ['icon'=>'medical_services', 'label'=>'Santri Sakit',    'value'=> $santriSakit,                  'sub'=>'Tercatat hari ini',                                 'grad'=>'from-rose-500 to-rose-700'],
        ['icon'=>'payments',         'label'=>'Lunas Bulan Ini', 'value'=> $pctKeuangan.'%',              'sub'=>'Rp '.number_format($totalLunasBulanIni,0,',','.'),  'grad'=>'from-purple-500 to-purple-700'],
    ];
    @endphp
    @foreach($kpis as $k)
    <div class="glassmorphism p-5 rounded-xl border border-white/20 shadow-sm flex flex-col items-center text-center group hover:scale-105 transition-all duration-300 cursor-default">
        <div class="w-11 h-11 rounded-full flex items-center justify-center mb-3 bg-gradient-to-br {{ $k['grad'] }}">
            <span class="material-symbols-outlined text-white text-lg" style="font-variation-settings:'FILL' 1;">{{ $k['icon'] }}</span>
        </div>
        <span class="text-[10px] text-on-surface-variant uppercase tracking-widest font-bold mb-1">{{ $k['label'] }}</span>
        <span class="text-2xl font-bold text-on-surface">{{ $k['value'] }}</span>
        <span class="text-[10px] text-on-surface-variant mt-1">{{ $k['sub'] }}</span>
    </div>
    @endforeach
</section>

{{-- Alerts (Sakit & Kehadiran Rendah) --}}
@if($santriSakitAlert->isNotEmpty() || $santriRendahKehadiran->isNotEmpty())
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 fade-in-up delay-2">

    {{-- Santri Sakit Hari Ini --}}
    @if($santriSakitAlert->isNotEmpty())
    <div class="glassmorphism rounded-2xl p-5 border border-error/20 bg-error/5">
        <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-error" style="font-variation-settings:'FILL' 1;">emergency</span>
            <h3 class="font-bold text-error text-sm uppercase tracking-wider">Santri Sakit Hari Ini ({{ $santriSakitAlert->count() }})</h3>
        </div>
        <div class="space-y-2 max-h-40 overflow-y-auto custom-scrollbar">
            @foreach($santriSakitAlert as $s)
            <div class="flex items-center gap-3 p-2 bg-white/60 rounded-xl border border-error/10">
                <div class="w-8 h-8 rounded-full bg-error/10 flex items-center justify-center shrink-0 font-bold text-error text-sm">{{ substr($s->santri->nama ?? '?', 0, 1) }}</div>
                <div>
                    <p class="text-sm font-bold text-on-surface">{{ $s->santri->nama ?? '—' }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ $s->santri->kelas->nama ?? 'Tanpa Kelas' }} · <span class="text-error">{{ Str::limit($s->keluhan, 30) }}</span></p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Santri Kehadiran Rendah --}}
    @if($santriRendahKehadiran->isNotEmpty())
    <div class="glassmorphism rounded-2xl p-5 border border-amber-300/30 bg-amber-50/50">
        <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-amber-600" style="font-variation-settings:'FILL' 1;">warning</span>
            <h3 class="font-bold text-amber-700 text-sm uppercase tracking-wider">Kehadiran &lt; 75% Bulan Ini</h3>
        </div>
        <div class="space-y-2 max-h-40 overflow-y-auto custom-scrollbar">
            @foreach($santriRendahKehadiran as $item)
            <div class="flex items-center gap-3 p-2 bg-white/60 rounded-xl border border-amber-200">
                <div class="flex-1">
                    <p class="text-sm font-bold text-on-surface">{{ $item['santri']->nama }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ $item['santri']->kelas->nama ?? 'Tanpa Kelas' }}</p>
                </div>
                <div class="text-right shrink-0">
                    <div class="text-sm font-bold text-amber-700">{{ $item['pct'] }}%</div>
                    <div class="text-[10px] text-on-surface-variant">{{ $item['hadir'] }}/{{ $item['total'] }} hari</div>
                </div>
                <div class="w-16 bg-gray-200 rounded-full h-1.5 shrink-0">
                    <div class="bg-amber-500 h-1.5 rounded-full" style="width:{{ min($item['pct'],100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endif

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 fade-in-up delay-2">

    {{-- Chart Hafalan --}}
    <div class="lg:col-span-5 glassmorphism p-6 rounded-2xl border border-white/20 shadow-sm">
        <div class="flex justify-between items-center mb-5">
            <div>
                <h3 class="text-base font-bold text-on-surface">Tren Hafalan</h3>
                <p class="text-xs text-on-surface-variant">Setoran 12 bulan terakhir</p>
            </div>
            <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1;">menu_book</span>
        </div>
        <div class="relative h-52">
            <canvas id="chartHafalan"></canvas>
        </div>
    </div>

    {{-- Chart Kehadiran --}}
    <div class="lg:col-span-4 glassmorphism p-6 rounded-2xl border border-white/20 shadow-sm">
        <div class="flex justify-between items-center mb-5">
            <div>
                <h3 class="text-base font-bold text-on-surface">Tren Kehadiran</h3>
                <p class="text-xs text-on-surface-variant">% rata-rata per bulan</p>
            </div>
            <span class="material-symbols-outlined text-blue-600" style="font-variation-settings:'FILL' 1;">how_to_reg</span>
        </div>
        <div class="relative h-52">
            <canvas id="chartKehadiran"></canvas>
        </div>
    </div>

    {{-- Pie Distribusi Kelas --}}
    <div class="lg:col-span-3 glassmorphism p-6 rounded-2xl border border-white/20 shadow-sm">
        <div class="mb-5">
            <h3 class="text-base font-bold text-on-surface">Distribusi Santri</h3>
            <p class="text-xs text-on-surface-variant">Per kelas</p>
        </div>
        <div class="relative h-36 flex items-center justify-center">
            <canvas id="chartKelas"></canvas>
        </div>
        <div class="mt-3 space-y-1">
            @foreach($distribusiKelas->take(5) as $i => $dk)
            @php $colors = ['#004532','#4059aa','#f59e0b','#ba1a1a','#6b7280']; @endphp
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-1.5">
                    <div class="w-2.5 h-2.5 rounded-full" style="background:{{ $colors[$i % count($colors)] }}"></div>
                    <span class="text-on-surface-variant truncate max-w-[100px]">{{ $dk['label'] }}</span>
                </div>
                <span class="font-bold text-on-surface">{{ $dk['val'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Bottom Row: Leaderboard + Monitoring Musyrif + Keuangan + Agenda --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 fade-in-up delay-3">

    {{-- Leaderboard Hafalan --}}
    <div class="lg:col-span-4 glassmorphism p-6 rounded-2xl border border-white/20 shadow-sm">
        <div class="flex items-center gap-2 mb-5">
            <span class="material-symbols-outlined text-amber-500 text-2xl" style="font-variation-settings:'FILL' 1;">military_tech</span>
            <div>
                <h3 class="text-base font-bold text-on-surface">Top Hafalan Santri</h3>
                <p class="text-xs text-on-surface-variant">Berdasarkan juz selesai</p>
            </div>
        </div>
        <div class="space-y-2">
            @forelse($leaderboard as $idx => $santri)
            @php
                $juz = optional($santri->hafalan)->juz_selesai ?? 0;
                $rankColors = ['#fbbf24','#9ca3af','#b45309'];
                $rankIcons  = ['emoji_events','workspace_premium','grade'];
            @endphp
            <div class="flex items-center gap-3 p-2.5 rounded-xl {{ $idx < 3 ? 'bg-amber-50 border border-amber-200/50' : 'hover:bg-surface-container' }} transition-colors">
                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 font-bold text-sm
                    {{ $idx == 0 ? 'bg-amber-400 text-white' : ($idx == 1 ? 'bg-gray-300 text-gray-700' : ($idx == 2 ? 'bg-amber-700 text-white' : 'bg-surface-container text-on-surface-variant')) }}">
                    @if($idx < 3)
                        <span class="material-symbols-outlined text-xs" style="font-variation-settings:'FILL' 1;">{{ $rankIcons[$idx] }}</span>
                    @else
                        {{ $idx + 1 }}
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-on-surface truncate">{{ $santri->nama }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ optional($santri->kelas)->nama ?? 'Tanpa Kelas' }} · {{ optional($santri->halaqoh)->nama ?? '—' }}</p>
                </div>
                <div class="text-right shrink-0">
                    <div class="text-sm font-bold text-primary">{{ $juz }} Juz</div>
                    <div class="text-[10px] text-on-surface-variant">{{ optional($santri->hafalan)->target_juz ?? '—' }} target</div>
                </div>
            </div>
            @empty
            <p class="text-sm text-on-surface-variant text-center py-6">Belum ada data hafalan.</p>
            @endforelse
        </div>
    </div>

    {{-- Monitoring Musyrif --}}
    <div class="lg:col-span-4 glassmorphism p-6 rounded-2xl border border-white/20 shadow-sm">
        <div class="flex items-center gap-2 mb-5">
            <span class="material-symbols-outlined text-secondary text-2xl" style="font-variation-settings:'FILL' 1;">supervisor_account</span>
            <div>
                <h3 class="text-base font-bold text-on-surface">Monitoring Musyrif</h3>
                <p class="text-xs text-on-surface-variant">Input data hari ini</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-2 mb-4 text-center text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">
            <span>Halaqoh</span><span>Absensi</span><span>Setoran</span>
        </div>
        <div class="space-y-2 max-h-72 overflow-y-auto custom-scrollbar">
            @forelse($halaqohList as $h)
            <div class="grid grid-cols-3 gap-2 items-center p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/10">
                <div>
                    <p class="text-xs font-bold text-on-surface truncate">{{ $h['nama'] }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ $h['musyrif'] }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ $h['jumlah_santri'] }} santri</p>
                </div>
                <div class="flex justify-center">
                    @if($h['absensi'])
                        <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1;">check_circle</span>
                    @else
                        <span class="material-symbols-outlined text-gray-300">cancel</span>
                    @endif
                </div>
                <div class="flex justify-center">
                    @if($h['setoran'])
                        <span class="material-symbols-outlined text-emerald-600" style="font-variation-settings:'FILL' 1;">check_circle</span>
                    @else
                        <span class="material-symbols-outlined text-gray-300">cancel</span>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-on-surface-variant text-center py-6">Belum ada halaqoh terdaftar.</p>
            @endforelse
        </div>
    </div>

    {{-- Keuangan + Agenda --}}
    <div class="lg:col-span-4 flex flex-col gap-5">

        {{-- Ringkasan Keuangan --}}
        <div class="glassmorphism p-5 rounded-2xl border border-white/20 shadow-sm flex-1">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-purple-600 text-2xl" style="font-variation-settings:'FILL' 1;">payments</span>
                <div>
                    <h3 class="text-base font-bold text-on-surface">Keuangan</h3>
                    <p class="text-xs text-on-surface-variant">Bulan {{ now()->locale('id')->isoFormat('MMMM YYYY') }}</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-on-surface-variant">Total Tagihan</span>
                    <span class="text-sm font-bold text-on-surface">Rp {{ number_format($totalTagihanBulanIni,0,',','.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-on-surface-variant">Sudah Lunas</span>
                    <span class="text-sm font-bold text-emerald-700">Rp {{ number_format($totalLunasBulanIni,0,',','.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-on-surface-variant">Total Tunggakan</span>
                    <span class="text-sm font-bold text-error">Rp {{ number_format($totalTunggakan,0,',','.') }}</span>
                </div>
                {{-- Progress Bar --}}
                <div class="pt-1">
                    <div class="flex justify-between text-[10px] font-bold mb-1">
                        <span class="text-emerald-700">Collection Rate</span>
                        <span class="text-emerald-700">{{ $pctKeuangan }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all" style="width:{{ min($pctKeuangan,100) }}%; background:linear-gradient(90deg,#004532,#065f46);"></div>
                    </div>
                </div>
                {{-- Per jenis --}}
                @if($keuanganPerJenis->isNotEmpty())
                <div class="pt-2 border-t border-outline-variant/20 space-y-1.5">
                    @foreach($keuanganPerJenis as $kj)
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-on-surface-variant truncate max-w-[120px]">{{ $kj->nama }}</span>
                        <div class="text-right">
                            <span class="font-bold text-emerald-700">{{ $kj->total > 0 ? round(($kj->lunas/$kj->total)*100).'%' : '0%' }}</span>
                            <span class="text-on-surface-variant ml-1">lunas</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Agenda --}}
        <div class="glassmorphism p-5 rounded-2xl border border-white/20 shadow-sm">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL' 1;">event</span>
                <h3 class="text-base font-bold text-on-surface">Agenda Pesantren</h3>
            </div>
            <div class="space-y-2">
                @forelse($agendas as $agenda)
                <div class="p-2.5 bg-white/50 rounded-xl border border-white/40 flex items-center gap-3">
                    <div class="bg-primary/10 px-2.5 py-1 rounded-lg text-center shrink-0">
                        <div class="text-sm font-extrabold text-primary leading-none">{{ \Carbon\Carbon::parse($agenda->published_at)->format('d') }}</div>
                        <div class="text-[8px] font-bold text-primary uppercase">{{ \Carbon\Carbon::parse($agenda->published_at)->locale('id')->isoFormat('MMM') }}</div>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-on-surface truncate">{{ $agenda->judul }}</p>
                        <p class="text-[10px] text-on-surface-variant line-clamp-1">{{ $agenda->isi }}</p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-on-surface-variant text-center py-4">Tidak ada agenda mendatang.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const hafalanLabels = @json(array_column($hafalanBulanan, 'month'));
const hafalanData   = @json(array_column($hafalanBulanan, 'val'));
const kehadiranLabels = @json(array_column($kehadiranBulanan, 'month'));
const kehadiranData   = @json(array_column($kehadiranBulanan, 'val'));
const kelasLabels = @json($distribusiKelas->pluck('label'));
const kelasData   = @json($distribusiKelas->pluck('val'));

// Chart Hafalan
new Chart(document.getElementById('chartHafalan').getContext('2d'), {
    type: 'bar',
    data: {
        labels: hafalanLabels,
        datasets: [{ label: 'Setoran', data: hafalanData, backgroundColor: 'rgba(0,69,50,0.2)', borderColor: 'rgba(0,69,50,0.8)', borderWidth: 2, borderRadius: 6 }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false }, ticks: { font: { size: 9 }, maxRotation: 45 } }, y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } } } }
});

// Chart Kehadiran
new Chart(document.getElementById('chartKehadiran').getContext('2d'), {
    type: 'line',
    data: {
        labels: kehadiranLabels,
        datasets: [{ label: '% Hadir', data: kehadiranData, borderColor: '#4059aa', backgroundColor: 'rgba(64,89,170,0.1)', fill: true, tension: 0.4, pointRadius: 3, pointBackgroundColor: '#4059aa' }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false }, ticks: { font: { size: 9 }, maxRotation: 45 } }, y: { beginAtZero: true, max: 100, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { callback: v => v+'%' } } } }
});

// Pie Kelas
new Chart(document.getElementById('chartKelas').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: kelasLabels,
        datasets: [{ data: kelasData, backgroundColor: ['#004532','#4059aa','#f59e0b','#ba1a1a','#6b7280','#6d28d9','#0891b2'], borderWidth: 2, borderColor: '#fff' }]
    },
    options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { display: false } } }
});
</script>
@endpush
