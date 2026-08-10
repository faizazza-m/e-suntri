@extends('layouts.guru')
@section('title', 'Jadwal Mengajar')

@section('content')

<div class="flex items-center gap-3 mb-6 fade-in-up">
    <div class="p-2.5 bg-amber-500/10 rounded-xl">
        <span class="material-symbols-outlined text-amber-600 text-2xl" style="font-variation-settings:'FILL' 1;">calendar_today</span>
    </div>
    <div>
        <h2 class="text-xl sm:text-2xl font-bold text-on-surface">Jadwal Mengajar</h2>
        <p class="text-xs text-on-surface-variant">Jadwal mengajar Anda selama seminggu.</p>
    </div>
</div>

@php
    $today = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][(\Carbon\Carbon::now()->dayOfWeekIso - 1)] ?? null;
@endphp

@if($jadwalByHari->isEmpty())
<div class="glassmorphism rounded-2xl p-10 text-center fade-in-up border border-dashed border-outline-variant/40">
    <span class="material-symbols-outlined text-5xl text-outline-variant/40 mb-2">event_busy</span>
    <p class="text-sm font-bold text-on-surface-variant">Belum ada jadwal mengajar yang ditugaskan.</p>
    <p class="text-xs text-on-surface-variant mt-1">Hubungi Admin untuk pengaturan jadwal.</p>
</div>
@else
<div class="space-y-5 fade-in-up">
    @foreach($hariOrder as $hari)
        @if(isset($jadwalByHari[$hari]))
        @php $isToday = $hari === $today; @endphp
        <div class="glassmorphism rounded-2xl overflow-hidden shadow-sm border {{ $isToday ? 'border-primary/40' : 'border-outline-variant/20' }}">
            <div class="flex items-center gap-3 px-5 py-3 {{ $isToday ? 'bg-primary/10' : 'bg-surface-container/30' }} border-b border-outline-variant/10">
                <span class="material-symbols-outlined {{ $isToday ? 'text-primary' : 'text-on-surface-variant' }} text-lg" style="font-variation-settings:'FILL' 1;">{{ $isToday ? 'today' : 'calendar_today' }}</span>
                <h3 class="font-bold {{ $isToday ? 'text-primary' : 'text-on-surface' }} text-sm">{{ $hari }}</h3>
                @if($isToday)
                <span class="ml-auto text-[10px] font-bold text-white bg-primary px-2 py-0.5 rounded-full">HARI INI</span>
                @endif
            </div>

            <div class="divide-y divide-outline-variant/10">
                @foreach($jadwalByHari[$hari] as $j)
                <div class="flex items-center gap-4 px-5 py-3 hover:bg-surface-container/30 transition-colors">
                    <div class="text-center min-w-[56px] bg-amber-500/10 rounded-lg py-1.5 px-2 shrink-0">
                        <p class="text-xs text-amber-600 font-bold">{{ \Carbon\Carbon::createFromTimeString($j->jam_mulai)->format('H:i') }}</p>
                        <p class="text-[9px] text-on-surface-variant">{{ \Carbon\Carbon::createFromTimeString($j->jam_selesai)->format('H:i') }}</p>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-on-surface text-sm truncate">{{ $j->mapel->nama ?? '—' }}</p>
                        <p class="text-xs text-on-surface-variant">Kelas {{ $j->kelas->nama ?? '—' }}
                            @if($j->ruang) • {{ $j->ruang }} @endif
                        </p>
                    </div>
                    <a href="{{ route('guru.nilai', ['mapel_id' => $j->mapel_id, 'kelas_id' => $j->kelas_id]) }}"
                       class="hidden sm:flex items-center gap-1 text-[10px] text-secondary font-bold border border-secondary/30 rounded-lg px-2 py-1 hover:bg-secondary/10 transition-colors shrink-0">
                        <span class="material-symbols-outlined text-xs">grade</span> Input Nilai
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach
</div>
@endif

@endsection
