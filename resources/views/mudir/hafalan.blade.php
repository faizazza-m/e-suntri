@extends('layouts.mudir')

@section('title', 'Laporan Hafalan — SUNTRI')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 fade-in-up">
    <div>
        <h2 class="text-2xl font-bold text-primary">Laporan Tahfizh Center</h2>
        <p class="text-sm text-on-surface-variant">Statistik capaian hafalan Al-Quran santri.</p>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 fade-in-up delay-1">
    @foreach($summary as $s)
    <div class="glassmorphism p-5 rounded-2xl border border-white/20 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 {{ $s['color'] }}">
            <span class="material-symbols-outlined {{ str_contains($s['color'],'text-white') ? '' : 'text-white' }}">{{ $s['icon'] }}</span>
        </div>
        <div>
            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">{{ $s['title'] }}</p>
            <p class="text-xl font-bold text-on-surface">{{ $s['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 fade-in-up delay-2">
    <div class="glassmorphism rounded-2xl p-6 border border-white/20 shadow-sm">
        <h3 class="font-bold text-lg text-primary mb-4">Setoran Terbaru Hari Ini</h3>
        <div class="space-y-3 max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
            @forelse($recentSetoran as $setoran)
            <div class="p-3 bg-white/60 rounded-xl border border-white flex justify-between items-center">
                <div>
                    <p class="text-sm font-bold text-on-surface">{{ $setoran->santri->nama }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ $setoran->surah }} ({{ $setoran->ayat_dari }} - {{ $setoran->ayat_sampai }}) • Musyrif: {{ optional($setoran->musyrif)->name }}</p>
                </div>
                <div class="text-right">
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-[10px] font-bold rounded-lg">{{ $setoran->nilai }}</span>
                </div>
            </div>
            @empty
            <p class="text-sm text-on-surface-variant py-4 text-center">Belum ada setoran hari ini.</p>
            @endforelse
        </div>
    </div>

    <div class="glassmorphism rounded-2xl p-6 border border-white/20 shadow-sm">
        <h3 class="font-bold text-lg text-amber-600 mb-4">Top 3 Hafizh</h3>
        <div class="space-y-3">
            @forelse($topSantri as $idx => $ts)
            <div class="p-4 bg-amber-50 rounded-xl border border-amber-200 flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold text-lg">
                    {{ $idx + 1 }}
                </div>
                <div class="flex-1">
                    <p class="font-bold text-on-surface">{{ $ts->nama }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ optional($ts->kelas)->nama }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-bold text-amber-600">{{ $ts->hafalan->juz_selesai }} Juz</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-on-surface-variant py-4 text-center">Belum ada data hafalan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
