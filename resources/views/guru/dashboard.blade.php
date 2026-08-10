@extends('layouts.guru')
@section('title', 'Dashboard Guru')

@section('content')

{{-- ============================================================ --}}
{{-- HERO / GREETING BANNER                                        --}}
{{-- ============================================================ --}}
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary via-primary-container to-emerald-700 p-6 sm:p-8 mb-6 shadow-xl fade-up">
    {{-- Decorative circles --}}
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/5"></div>
    <div class="absolute -bottom-8 -left-8 w-32 h-32 rounded-full bg-white/5"></div>
    <div class="absolute top-4 right-24 w-16 h-16 rounded-full bg-yellow-400/10"></div>

    <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-white/60 text-sm font-medium mb-1">Assalamu'alaikum,</p>
            <h1 class="text-white text-2xl sm:text-3xl font-black leading-tight">{{ auth()->user()->name ?? 'Ustadz' }} 👋</h1>
            <p class="text-white/70 text-sm mt-1">
                <span class="material-symbols-outlined text-[14px] align-middle">today</span>
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>
        <div class="flex gap-3 shrink-0">
            <div class="bg-white/10 backdrop-blur rounded-2xl px-4 py-3 text-center border border-white/10">
                <p class="text-white text-2xl font-black">{{ $totalMapel }}</p>
                <p class="text-white/60 text-[10px] font-bold uppercase tracking-wider">Mapel</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-2xl px-4 py-3 text-center border border-white/10">
                <p class="text-white text-2xl font-black">{{ $totalJadwal }}</p>
                <p class="text-white/60 text-[10px] font-bold uppercase tracking-wider">Jadwal</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-2xl px-4 py-3 text-center border border-white/10">
                <p class="text-white text-2xl font-black">{{ $totalSantri }}</p>
                <p class="text-white/60 text-[10px] font-bold uppercase tracking-wider">Santri</p>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- QUICK STATS GRID                                              --}}
{{-- ============================================================ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $stats = [
            ['icon'=>'menu_book',     'label'=>'Mata Pelajaran',  'value'=>$totalMapel,          'color'=>'text-primary',   'bg'=>'bg-primary/10',   'border'=>'border-primary/20',   'delay'=>'fade-up-1'],
            ['icon'=>'calendar_month','label'=>'Jadwal/Minggu',   'value'=>$totalJadwal,          'color'=>'text-indigo-600','bg'=>'bg-indigo-500/10','border'=>'border-indigo-500/20','delay'=>'fade-up-2'],
            ['icon'=>'groups',        'label'=>'Total Santri',    'value'=>$totalSantri,           'color'=>'text-amber-600', 'bg'=>'bg-amber-500/10', 'border'=>'border-amber-500/20', 'delay'=>'fade-up-3'],
            ['icon'=>'grade',         'label'=>'Nilai Bulan Ini', 'value'=>$nilaiInputBulanIni,   'color'=>'text-rose-600',  'bg'=>'bg-rose-500/10',  'border'=>'border-rose-500/20',  'delay'=>'fade-up-4'],
        ];
    @endphp
    @foreach($stats as $s)
    <div class="glass-card {{ $s['border'] }} border rounded-2xl p-4 sm:p-5 fade-up {{ $s['delay'] }} hover:shadow-lg transition-shadow">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl {{ $s['bg'] }} flex items-center justify-center">
                <span class="material-symbols-outlined {{ $s['color'] }} text-xl" style="font-variation-settings:'FILL' 1;">{{ $s['icon'] }}</span>
            </div>
            <span class="text-[9px] font-black uppercase tracking-wider text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full">Live</span>
        </div>
        <p class="text-[11px] text-on-surface-variant font-semibold uppercase tracking-wider leading-tight">{{ $s['label'] }}</p>
        <p class="text-3xl font-black {{ $s['color'] }} mt-0.5">{{ $s['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- ============================================================ --}}
{{-- MAIN CONTENT GRID                                             --}}
{{-- ============================================================ --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- Jadwal Hari Ini --}}
    <div class="xl:col-span-2 glass-card rounded-3xl border border-outline-variant/20 overflow-hidden fade-up fade-up-2">
        <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/10 bg-primary/5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings:'FILL' 1;">today</span>
                <h2 class="font-bold text-on-surface text-sm">Jadwal Hari Ini
                    @if($dayName)<span class="text-on-surface-variant font-normal ml-1">({{ $dayName }})</span>@endif
                </h2>
            </div>
            <a href="{{ route('guru.jadwal') }}" class="text-xs text-primary font-bold hover:underline flex items-center gap-1">
                Semua <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
        </div>

        <div class="p-5">
            @forelse($jadwalHariIni as $j)
            <div class="flex items-center gap-4 mb-3 p-4 rounded-2xl bg-surface-container-low border border-outline-variant/10 hover:border-primary/20 hover:bg-primary/5 transition-all group">
                <div class="text-center min-w-[60px] bg-primary rounded-xl py-2 px-3 shrink-0 shadow-sm">
                    <p class="text-xs text-white font-black">{{ \Carbon\Carbon::createFromTimeString($j->jam_mulai)->format('H:i') }}</p>
                    <p class="text-[9px] text-white/60 mt-0.5">{{ \Carbon\Carbon::createFromTimeString($j->jam_selesai)->format('H:i') }}</p>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-on-surface truncate">{{ $j->mapel->nama ?? '—' }}</p>
                    <div class="flex flex-wrap items-center gap-2 mt-0.5">
                        <span class="text-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-[12px] align-middle">people</span>
                            Kelas {{ $j->kelas->nama ?? '—' }} ({{ $j->kelas->julukan ?? '' }})
                        </span>
                        @if($j->ruang)
                        <span class="text-[10px] text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full">
                            <span class="material-symbols-outlined text-[11px] align-middle">room</span> {{ $j->ruang }}
                        </span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('guru.nilai', ['mapel_id' => $j->mapel_id, 'kelas_id' => $j->kelas_id]) }}"
                   class="hidden sm:flex items-center gap-1 text-[10px] font-bold text-secondary border border-secondary/30 rounded-xl px-3 py-1.5 hover:bg-secondary hover:text-white transition-all shrink-0">
                    <span class="material-symbols-outlined text-xs">grade</span> Nilai
                </a>
                <div class="w-2 h-2 rounded-full bg-green-400 shrink-0 sm:hidden"></div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-primary/5 flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-3xl text-primary/30">event_available</span>
                </div>
                <p class="font-bold text-on-surface-variant text-sm">Tidak ada jadwal hari ini</p>
                <p class="text-on-surface-variant text-xs mt-1">Selamat beristirahat! 😊</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Pengumuman Sidebar --}}
    <div class="glass-card rounded-3xl border border-outline-variant/20 overflow-hidden fade-up fade-up-3">
        <div class="flex items-center justify-between px-5 py-4 border-b border-outline-variant/10 bg-cyan-500/5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-cyan-500 text-xl" style="font-variation-settings:'FILL' 1;">campaign</span>
                <h2 class="font-bold text-on-surface text-sm">Pengumuman</h2>
            </div>
            <a href="{{ route('guru.pengumuman') }}" class="text-xs text-primary font-bold hover:underline">Lihat →</a>
        </div>
        <div class="p-4 space-y-3 max-h-80 overflow-y-auto">
            @forelse($pengumuman as $p)
            <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/10 hover:border-cyan-500/20 hover:bg-cyan-500/5 transition-all relative">
                @if($p->is_pinned)
                <span class="absolute top-2 right-2 text-[9px] font-black text-cyan-500 bg-cyan-500/10 px-1.5 py-0.5 rounded-full">📌</span>
                @endif
                <p class="text-sm font-bold text-on-surface leading-snug pr-6">{{ $p->judul }}</p>
                <p class="text-[10px] text-on-surface-variant mt-1">{{ \Carbon\Carbon::parse($p->published_at)->diffForHumans() }}</p>
            </div>
            @empty
            <div class="py-8 text-center">
                <span class="material-symbols-outlined text-3xl text-outline-variant/40 mb-1">inbox</span>
                <p class="text-xs text-on-surface-variant">Tidak ada pengumuman</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MATA PELAJARAN CARDS                                          --}}
{{-- ============================================================ --}}
@if($mapelSaya->isNotEmpty())
<div class="glass-card rounded-3xl border border-outline-variant/20 p-6 fade-up fade-up-4">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings:'FILL' 1;">menu_book</span>
            Mata Pelajaran Saya
        </h2>
        <a href="{{ route('guru.nilai') }}" class="text-xs text-secondary font-bold hover:underline">Input Nilai →</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        @foreach($mapelSaya as $mapel)
        <a href="{{ route('guru.nilai', ['mapel_id' => $mapel->id]) }}"
           class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-surface-container-low border border-outline-variant/10
                  hover:border-secondary/30 hover:bg-secondary/5 hover:shadow-md transition-all text-center group">
            <div class="w-11 h-11 rounded-xl bg-secondary/10 flex items-center justify-center group-hover:bg-secondary/20 transition-colors">
                <span class="material-symbols-outlined text-secondary text-xl" style="font-variation-settings:'FILL' 1;">auto_stories</span>
            </div>
            <p class="text-xs font-bold text-on-surface leading-tight line-clamp-2">{{ $mapel->nama }}</p>
            @if($mapel->kode)
            <span class="text-[9px] font-bold text-secondary/70 bg-secondary/10 px-2 py-0.5 rounded-full uppercase">{{ $mapel->kode }}</span>
            @endif
        </a>
        @endforeach

        {{-- Add input nilai shortcut --}}
        <a href="{{ route('guru.nilai') }}"
           class="flex flex-col items-center justify-center gap-2 p-4 rounded-2xl border-2 border-dashed border-outline-variant/40
                  hover:border-secondary hover:bg-secondary/5 transition-all text-center group min-h-[100px]">
            <span class="material-symbols-outlined text-2xl text-outline-variant group-hover:text-secondary transition-colors">add_circle</span>
            <p class="text-[10px] font-bold text-on-surface-variant group-hover:text-secondary transition-colors">Input Nilai</p>
        </a>
    </div>
</div>
@endif

@endsection
