@extends('layouts.guru')
@section('title', 'Pengumuman')

@section('content')

<div class="flex items-center gap-3 mb-6 fade-in-up">
    <div class="p-2.5 bg-cyan-500/10 rounded-xl">
        <span class="material-symbols-outlined text-cyan-500 text-2xl" style="font-variation-settings:'FILL' 1;">campaign</span>
    </div>
    <div>
        <h2 class="text-xl sm:text-2xl font-bold text-on-surface">Pengumuman</h2>
        <p class="text-xs text-on-surface-variant">Informasi & pengumuman dari manajemen pesantren.</p>
    </div>
</div>

@if($pengumumans->isEmpty())
<div class="glassmorphism rounded-2xl p-10 text-center fade-in-up border border-dashed border-outline-variant/40">
    <span class="material-symbols-outlined text-5xl text-outline-variant/40 mb-2">inbox</span>
    <p class="text-sm font-bold text-on-surface-variant">Belum ada pengumuman.</p>
</div>
@else
<div class="space-y-4 fade-in-up">
    @foreach($pengumumans as $p)
    @php
        $targetBadge = match($p->target) {
            'semua'   => 'bg-cyan-500/10 text-cyan-600 border-cyan-500/20',
            'musyrif' => 'bg-secondary/10 text-secondary border-secondary/20',
            'guru'    => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
            default   => 'bg-surface-container text-on-surface-variant border-outline-variant/20',
        };
    @endphp
    <div x-data="{ expanded: false }" class="glassmorphism rounded-2xl p-5 shadow-sm border {{ $p->is_pinned ? 'border-l-4 border-l-cyan-500 border-outline-variant/20' : 'border-outline-variant/20' }} relative">
        @if($p->is_pinned)
        <span class="absolute top-3 right-3 text-[9px] font-bold text-cyan-500 bg-cyan-500/10 px-2 py-0.5 rounded-full border border-cyan-500/20">📌 DISEMATKAN</span>
        @endif
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <h3 class="text-base font-bold text-on-surface leading-snug pr-16">{{ $p->judul }}</h3>
            <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full border {{ $targetBadge }}">{{ $p->target }}</span>
        </div>
        <p class="text-[11px] text-on-surface-variant mb-3 flex items-center gap-1">
            <span class="material-symbols-outlined text-[13px]">schedule</span>
            {{ \Carbon\Carbon::parse($p->published_at)->translatedFormat('d F Y, H:i') }}
        </p>
        <div class="border-t border-outline-variant/20 pt-3">
            <div :class="expanded ? '' : 'line-clamp-3 overflow-hidden'" class="text-sm text-on-surface leading-relaxed whitespace-pre-wrap transition-all duration-300 relative">
                {!! nl2br(e($p->isi)) !!}
            </div>
            @if(strlen($p->isi) > 120)
            <button @click="expanded = !expanded" class="mt-2 text-cyan-600 text-xs font-bold hover:text-cyan-700 flex items-center gap-1 transition-colors">
                <span x-text="expanded ? 'Tampilkan Lebih Sedikit' : 'Baca Selengkapnya'"></span>
                <span class="material-symbols-outlined text-[14px]" x-text="expanded ? 'expand_less' : 'expand_more'"></span>
            </button>
            @endif
        </div>
    </div>
    @endforeach

    <div class="pt-2">{{ $pengumumans->links('pagination::tailwind') }}</div>
</div>
@endif

@endsection
