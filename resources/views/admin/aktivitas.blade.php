@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')
@section('meta_description', 'Pantau seluruh pergerakan dan aktivitas pesantren dalam satu halaman.')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-surface-container-highest rounded-2xl shadow-lg border border-outline-variant/30">
            <span class="material-symbols-outlined text-on-surface text-3xl" style="font-variation-settings: 'FILL' 1;">history</span>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-on-surface">Log Aktivitas Menyeluruh</h2>
            <p class="text-sm text-on-surface-variant">Pantau segala kejadian di pesantren dari semua modul (Tahfizh, Keuangan, Izin, UKS, Pengumuman)</p>
        </div>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-surface-container text-on-surface text-sm font-bold flex items-center gap-2 hover:bg-surface-container-high transition-colors border border-outline-variant/30">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Dashboard
        </a>
    </div>
</div>

{{-- Main Content (Timeline Layout) --}}
<div class="mt-8 fade-in-up delay-2 max-w-4xl mx-auto">
    <div class="glassmorphism rounded-3xl p-8 shadow-sm border border-outline-variant/20 relative overflow-hidden">
        
        <div class="flex items-center justify-between mb-8 border-b border-outline-variant/20 pb-4">
            <h3 class="text-sm font-bold text-on-surface uppercase tracking-widest">Timeline Aktivitas</h3>
            <span class="text-[11px] font-bold text-on-surface-variant bg-surface-container-high px-3 py-1 rounded-full">Menampilkan 100 aktivitas terbaru</span>
        </div>

        @if($paginator->isEmpty())
            <div class="p-10 text-center">
                <span class="material-symbols-outlined text-5xl text-outline-variant/50 mb-3">inbox</span>
                <p class="text-on-surface-variant font-medium">Belum ada aktivitas yang terekam di sistem.</p>
            </div>
        @else
            <div class="relative space-y-6">
                {{-- Vertical Line for timeline --}}
                <div class="absolute left-6 top-2 bottom-2 w-px bg-outline-variant/30 z-0"></div>

                @foreach($paginator as $act)
                <div class="flex gap-4 relative z-10 group">
                    <div class="w-12 h-12 rounded-full {{ $act['dot'] }} shrink-0 flex items-center justify-center text-white shadow-lg border-2 border-surface mt-1 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">{{ $act['icon'] ?? 'circle' }}</span>
                    </div>
                    <div class="flex-1 bg-surface-container/50 border border-outline-variant/20 rounded-2xl p-4 shadow-sm hover:bg-surface-container transition-colors">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded border {{ $act['tagColor'] ?? 'bg-surface-container text-on-surface' }}">
                                {{ $act['tag'] }}
                            </span>
                            <span class="text-[11px] text-on-surface-variant flex items-center gap-1 font-medium" title="{{ $act['timestamp']->format('d M Y H:i') }}">
                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                {{ $act['timestamp']->diffForHumans() }} ({{ $act['timestamp']->format('H:i') }} WIB)
                            </span>
                        </div>
                        <p class="text-sm text-on-surface leading-relaxed">{!! $act['html'] !!}</p>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-8 border-t border-outline-variant/20 pt-6">
                {{ $paginator->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>

@endsection
