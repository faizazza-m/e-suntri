@extends('layouts.mudir')

@section('title', 'Pengumuman — SUNTRI')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 fade-in-up">
    <div>
        <h2 class="text-2xl font-bold text-primary">Pengumuman & Agenda</h2>
        <p class="text-sm text-on-surface-variant">Riwayat pengumuman dan agenda pesantren.</p>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6 fade-in-up delay-1">
    <div class="glassmorphism p-4 rounded-xl border border-white/20 shadow-sm text-center">
        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Total</p>
        <p class="text-2xl font-bold text-primary mt-1">{{ $stats['total'] }}</p>
    </div>
    <div class="glassmorphism p-4 rounded-xl border border-white/20 shadow-sm text-center">
        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Wali</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['wali'] }}</p>
    </div>
    <div class="glassmorphism p-4 rounded-xl border border-white/20 shadow-sm text-center">
        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Musyrif</p>
        <p class="text-2xl font-bold text-orange-600 mt-1">{{ $stats['musyrif'] }}</p>
    </div>
    <div class="glassmorphism p-4 rounded-xl border border-white/20 shadow-sm text-center">
        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Ustadz</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['guru'] }}</p>
    </div>
    <div class="glassmorphism p-4 rounded-xl border border-white/20 shadow-sm text-center">
        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Santri</p>
        <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['santri'] }}</p>
    </div>
</div>

<div class="glassmorphism rounded-2xl p-6 border border-white/20 shadow-sm fade-in-up delay-2">
    <div class="space-y-4">
        @forelse($pengumumans as $p)
        <div class="p-4 bg-white/60 rounded-xl border border-outline-variant/20 {{ $p->is_pinned ? 'border-l-4 border-l-amber-500' : '' }}">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h3 class="text-lg font-bold text-on-surface flex items-center gap-2">
                        @if($p->is_pinned)
                        <span class="material-symbols-outlined text-amber-500 text-sm" style="font-variation-settings:'FILL' 1;">push_pin</span>
                        @endif
                        {{ $p->judul }}
                    </h3>
                    <p class="text-xs text-on-surface-variant">Diterbitkan oleh: <span class="font-bold">{{ $p->pembuat->name ?? 'Admin' }}</span> • {{ \Carbon\Carbon::parse($p->published_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') }}</p>
                </div>
                <span class="px-2 py-1 bg-surface-container-high text-[10px] font-bold uppercase rounded-lg border border-outline-variant/30">Target: {{ $p->target }}</span>
            </div>
            <p class="text-sm text-on-surface mt-2 whitespace-pre-wrap">{{ $p->isi }}</p>
        </div>
        @empty
        <p class="text-sm text-on-surface-variant py-8 text-center">Belum ada pengumuman yang diterbitkan.</p>
        @endforelse
    </div>
    <div class="mt-4">
        {{ $pengumumans->links() }}
    </div>
</div>
@endsection
