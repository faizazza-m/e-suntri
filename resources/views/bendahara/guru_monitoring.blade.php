@extends('layouts.app')

@section('title', 'Monitoring Kehadiran Guru')
@section('meta_description', 'Pantau kehadiran dan jurnal guru harian SUNTRI.')

@section('content')

{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-primary-container text-on-primary-container rounded-2xl flex items-center justify-center shrink-0 border border-outline-variant/50 shadow-sm">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">groups</span>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-on-surface tracking-tight">Monitoring Kehadiran Guru</h1>
            <p class="text-sm text-on-surface-variant">Real-time status jurnal harian: {{ $today->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-surface-bright rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex items-center gap-4 fade-in-up">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-blue-100 text-blue-600">
            <span class="material-symbols-outlined text-3xl">group</span>
        </div>
        <div>
            <p class="text-xs font-bold text-on-surface-variant uppercase mb-1">Total Guru</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $gurus->count() }}</h3>
        </div>
    </div>
    
    <div class="bg-surface-bright rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex items-center gap-4 fade-in-up" style="animation-delay: 0.1s;">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-green-100 text-green-600">
            <span class="material-symbols-outlined text-3xl">assignment_turned_in</span>
        </div>
        <div>
            <p class="text-xs font-bold text-on-surface-variant uppercase mb-1">Sudah Mengisi Jurnal</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $laporans->count() }}</h3>
        </div>
    </div>

    <div class="bg-surface-bright rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex items-center gap-4 fade-in-up" style="animation-delay: 0.2s;">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-red-100 text-red-600">
            <span class="material-symbols-outlined text-3xl">pending_actions</span>
        </div>
        <div>
            <p class="text-xs font-bold text-on-surface-variant uppercase mb-1">Belum Mengisi</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $gurus->count() - $laporans->count() }}</h3>
        </div>
    </div>
</div>

{{-- Table Data --}}
<div class="bg-surface-bright rounded-3xl border border-outline-variant/30 shadow-sm overflow-hidden fade-in-up" style="animation-delay: 0.3s;">
    <div class="p-6 border-b border-outline-variant/30 flex justify-between items-center">
        <h2 class="text-lg font-bold text-on-surface">Daftar Status Guru Hari Ini</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-outline-variant/30 bg-surface-container-lowest text-sm text-on-surface-variant">
                    <th class="py-4 px-6 font-bold w-12 text-center">No</th>
                    <th class="py-4 px-6 font-bold">Nama Guru / Ustadz</th>
                    <th class="py-4 px-6 font-bold">Status Kehadiran</th>
                    <th class="py-4 px-6 font-bold">Waktu Submit Jurnal</th>
                    <th class="py-4 px-6 font-bold">Materi/Catatan Jurnal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gurus as $index => $guru)
                    @php
                        $laporan = $laporans->get($guru->id);
                    @endphp
                    <tr class="border-b border-outline-variant/10 hover:bg-surface-container-lowest transition-colors">
                        <td class="py-4 px-6 text-center text-sm text-on-surface-variant">{{ $index + 1 }}</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-xs">
                                    {{ substr($guru->name, 0, 1) }}
                                </div>
                                <span class="font-bold text-sm text-on-surface">{{ $guru->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @if($laporan)
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full flex items-center gap-1 w-max">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Hadir
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full flex items-center gap-1 w-max">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span> Belum Hadir
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-sm">
                            @if($laporan)
                                <span class="font-mono bg-surface-container px-2 py-1 rounded text-on-surface">{{ \Carbon\Carbon::parse($laporan->created_at)->format('H:i') }} WIB</span>
                            @else
                                <span class="text-on-surface-variant">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-sm text-on-surface-variant max-w-xs truncate">
                            @if($laporan)
                                <strong>{{ $laporan->mata_pelajaran }}:</strong> {{ $laporan->materi }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
