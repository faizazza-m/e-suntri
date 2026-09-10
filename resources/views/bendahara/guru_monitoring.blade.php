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
            <p class="text-xs font-bold text-on-surface-variant uppercase mb-1">Sudah Mengisi (Hari Ini)</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $laporansHariIni->count() }}</h3>
        </div>
    </div>

    <div class="bg-surface-bright rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex items-center gap-4 fade-in-up" style="animation-delay: 0.2s;">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-red-100 text-red-600">
            <span class="material-symbols-outlined text-3xl">pending_actions</span>
        </div>
        <div>
            <p class="text-xs font-bold text-on-surface-variant uppercase mb-1">Belum Mengisi (Hari Ini)</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $gurus->count() - $laporansHariIni->count() }}</h3>
        </div>
    </div>
</div>

{{-- Table Data --}}
<div class="bg-surface-bright rounded-3xl border border-outline-variant/30 shadow-sm overflow-hidden fade-in-up" style="animation-delay: 0.3s;">
    
    <div class="p-6 border-b border-outline-variant/30 flex flex-col md:flex-row justify-between items-center gap-4">
        <h2 class="text-lg font-bold text-on-surface">Rekap Kehadiran Guru</h2>
        
        <form action="{{ route('bendahara.guru-monitoring') }}" method="GET" class="flex items-center gap-2">
            <select name="bulan" class="bg-surface-container border border-outline-variant/50 text-on-surface text-sm rounded-lg focus:ring-primary focus:border-primary p-2 w-32">
                @php
                    $months = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                @endphp
                @foreach($months as $key => $name)
                    <option value="{{ $key }}" {{ $filterBulan == $key ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            
            <select name="tahun" class="bg-surface-container border border-outline-variant/50 text-on-surface text-sm rounded-lg focus:ring-primary focus:border-primary p-2 w-24">
                @for($y = date('Y') - 2; $y <= date('Y'); $y++)
                    <option value="{{ $y }}" {{ $filterTahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <button type="submit" class="bg-primary hover:bg-primary/90 text-on-primary p-2 rounded-lg text-sm font-bold shadow-sm transition">
                Filter
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-outline-variant/30 bg-surface-container-lowest text-sm text-on-surface-variant">
                    <th class="py-4 px-6 font-bold w-12 text-center">No</th>
                    <th class="py-4 px-6 font-bold">Nama Guru / Ustadz</th>
                    <th class="py-4 px-6 font-bold">Kehadiran (Bulan Ini)</th>
                    <th class="py-4 px-6 font-bold">Kehadiran (Semester {{ $semesterName }})</th>
                    <th class="py-4 px-6 font-bold">Status Hari Ini</th>
                    <th class="py-4 px-6 font-bold">Waktu Submit Jurnal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gurus as $index => $guru)
                    @php
                        $laporanHariIni = $laporansHariIni->get($guru->id);
                        $hadirBulanIni = $rekapBulan[$guru->id] ?? 0;
                        $hadirSemesterIni = $rekapSemester[$guru->id] ?? 0;
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
                        <td class="py-4 px-6 text-center">
                            <span class="font-bold text-lg text-primary">{{ $hadirBulanIni }}</span>
                            <span class="text-xs text-on-surface-variant ml-1">pertemuan</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="font-bold text-lg text-secondary">{{ $hadirSemesterIni }}</span>
                            <span class="text-xs text-on-surface-variant ml-1">pertemuan</span>
                        </td>
                        <td class="py-4 px-6">
                            @if($laporanHariIni)
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
                            @if($laporanHariIni)
                                <span class="font-mono bg-surface-container px-2 py-1 rounded text-on-surface">{{ \Carbon\Carbon::parse($laporanHariIni->created_at)->format('H:i') }} WIB</span>
                            @else
                                <span class="text-on-surface-variant">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
