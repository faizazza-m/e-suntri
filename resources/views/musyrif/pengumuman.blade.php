@extends('layouts.musyrif')
@section('title', 'Pengumuman')

@section('content')
<div class="mb-5 fade-in-up">
    <h1 class="text-xl font-black text-on-surface tracking-tight flex items-center gap-2">
        <span class="material-symbols-outlined text-3xl text-primary" style="font-variation-settings: 'FILL' 1;">campaign</span>
        Pengumuman
    </h1>
    <p class="text-sm text-on-surface-variant mt-1">Informasi dan pengumuman terbaru untuk Musyrif.</p>
</div>

@if($pengumumans->isEmpty())
<div class="bg-surface rounded-2xl p-6 shadow-sm border border-outline-variant/30 text-center py-16 fade-in-up fade-up-1">
    <div class="w-20 h-20 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-4">
        <span class="material-symbols-outlined text-outline-variant text-4xl">notifications_off</span>
    </div>
    <h2 class="text-lg font-bold text-on-surface mb-2">Belum ada pengumuman</h2>
    <p class="text-sm text-on-surface-variant max-w-md mx-auto">
        Saat ini tidak ada informasi atau pengumuman baru dari administrator. Silakan periksa kembali nanti.
    </p>
</div>
@else
<div class="space-y-3 fade-in-up fade-up-1">
    @foreach($pengumumans as $p)
    @php
        $targetBadge = match($p->target) {
            'semua'   => 'bg-cyan-500/10 text-cyan-600 border-cyan-500/20',
            'musyrif' => 'bg-secondary/10 text-secondary border-secondary/20',
            default   => 'bg-surface-container text-on-surface-variant border-outline-variant/20',
        };
    @endphp
    <div class="bg-surface rounded-2xl p-5 shadow-sm border {{ $p->is_pinned ? 'border-l-4 border-l-cyan-500 border-outline-variant/20' : 'border-outline-variant/30' }} relative">
        @if($p->is_pinned)
        <span class="absolute top-4 right-4 text-[9px] font-bold text-cyan-500 bg-cyan-500/10 px-2 py-0.5 rounded-full border border-cyan-500/20">📌 DISEMATKAN</span>
        @endif
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <h3 class="text-base font-bold text-on-surface leading-snug pr-20">{{ $p->judul }}</h3>
            <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full border {{ $targetBadge }}">{{ $p->target }}</span>
        </div>
        <p class="text-[10px] text-on-surface-variant mb-3 flex items-center gap-1">
            <span class="material-symbols-outlined text-[12px]">schedule</span>
            {{ \Carbon\Carbon::parse($p->published_at)->translatedFormat('d F Y, H:i') }}
        </p>
        <p class="text-xs text-on-surface leading-relaxed whitespace-pre-wrap border-t border-outline-variant/20 pt-3">{!! nl2br(e($p->isi)) !!}</p>
    </div>
    @endforeach

    <div class="pt-4">{{ $pengumumans->links('pagination::tailwind') }}</div>
</div>
@endif
@endsection
