@extends('layouts.app')

@section('title', 'Tahfizh Center')
@section('meta_description', 'Monitoring setoran dan perkembangan hafalan seluruh santri SUNTRI.')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-primary-container rounded-2xl shadow-lg">
            <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">menu_book</span>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-primary">Tahfizh Center</h2>
            <p class="text-on-surface-variant text-sm">Monitoring setoran dan perkembangan hafalan Santri SUNTRI</p>
        </div>
    </div>
    <div class="flex gap-2">
        <button class="px-4 py-2 rounded-lg bg-primary-container text-white text-sm font-bold flex items-center gap-2 hover:opacity-90 transition-opacity shadow-md">
            <span class="material-symbols-outlined text-sm">download</span> Export Report
        </button>
    </div>
</div>

{{-- Stats Bar --}}
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 fade-in-up delay-1 mt-6">
    @foreach($summary as $stat)
    <div class="glassmorphism p-5 rounded-xl border border-white/20 shadow-sm flex flex-col items-center text-center group hover:scale-105 transition-all duration-300">
        <div class="w-11 h-11 rounded-full flex items-center justify-center mb-3 transition-colors duration-300 bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white">
            @if(isset($stat['icon']))
                <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">{{ $stat['icon'] }}</span>
            @endif
        </div>
        <span class="text-[10px] text-on-surface-variant uppercase tracking-widest font-bold mb-1">{{ $stat['title'] }}</span>
        <div class="flex items-center justify-center gap-2">
            <span class="text-2xl font-bold text-on-surface">{{ $stat['value'] }}</span>
            @if(isset($stat['trend']))
                <span class="{{ $stat['color'] }} text-white px-2 py-0.5 rounded text-[10px] font-bold">{{ $stat['trend'] }}</span>
            @endif
        </div>
    </div>
    @endforeach
</section>

{{-- Main Content Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8 fade-in-up delay-2 mt-8">

    {{-- Left Column (75%) --}}
    <div class="lg:col-span-3 space-y-8">

        {{-- Top Performers --}}
        <section class="glassmorphism p-6 rounded-3xl border border-white/20 shadow-sm overflow-hidden relative">
            <div class="absolute top-0 right-0 opacity-5 pointer-events-none transform translate-x-1/4 -translate-y-1/4">
                <span class="material-symbols-outlined text-[120px]">stars</span>
            </div>
            <h4 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-yellow-500">military_tech</span>
                Top Performing Santri
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $colors = ['#fbbf24', '#065f46', '#10b981'];
                    $circumference = 263.89;
                @endphp
                @foreach($topSantri as $index => $santri)
                @php
                    $percent = ($santri->hafalan->juz_selesai / 30) * 100;
                    $offset = $circumference - ($percent / 100) * $circumference;
                    $color = $colors[$index % count($colors)];
                @endphp
                <div class="flex flex-col items-center p-4 bg-surface-container-low rounded-2xl border border-white">
                    <div class="relative w-24 h-24 mb-4">
                        <svg class="w-full h-full" viewBox="0 0 100 100">
                            <circle class="stroke-current text-surface-container-high" cx="50" cy="50" r="42" fill="transparent" stroke-width="8"/>
                            <circle class="progress-ring__circle" cx="50" cy="50" r="42" fill="transparent" stroke-width="8"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $offset }}"
                                stroke="{{ $color }}"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold text-lg border-2 border-white shadow-sm">
                                {{ $santri->hafalan->juz_selesai }}
                            </div>
                        </div>
                    </div>
                    <h5 class="font-bold text-on-surface text-sm">{{ $santri->nama }}</h5>
                    <p class="text-[11px] text-on-surface-variant uppercase tracking-wide">Halaqoh {{ $santri->halaqoh->nama ?? '-' }}</p>
                    <p class="mt-2 text-primary font-bold text-sm">{{ $santri->hafalan->juz_selesai }} / 30 Juz</p>
                </div>
                @endforeach
                
                @if($topSantri->count() == 0)
                <div class="col-span-3 text-center text-sm text-on-surface-variant py-8">
                    Belum ada data capaian juz santri.
                </div>
                @endif
            </div>
        </section>

        {{-- Tab & Table Section --}}
        <section class="glassmorphism rounded-3xl border border-white/20 shadow-sm overflow-hidden mt-8">
            <div class="border-b border-outline-variant/10">
                <div class="flex gap-4 px-6 pt-4">
                    <button class="tab-btn active pb-4 text-primary font-bold border-b-2 border-primary" data-target="#tab-santri">
                        Daftar Santri
                    </button>
                    <button class="tab-btn pb-4 text-on-surface-variant font-bold border-b-2 border-transparent hover:text-primary transition-colors" data-target="#tab-halaqoh">
                        Kelola Halaqoh
                    </button>
                </div>
            </div>

            {{-- TAB: DAFTAR SANTRI --}}
            <div id="tab-santri" class="tab-content block">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-lowest text-on-surface-variant uppercase text-[10px] font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Halaqoh</th>
                            <th class="px-6 py-4">Musyrif</th>
                            <th class="px-6 py-4">Hafalan</th>
                            <th class="px-6 py-4">Progress</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @foreach($santris as $santri)
                        @php
                            $juz = $santri->hafalan ? $santri->hafalan->juz_selesai : 0;
                            $percent = ($juz / 30) * 100;
                            
                            $progressColor = 'bg-primary';
                            if($percent == 100) $progressColor = 'bg-emerald-500';
                            elseif($percent < 20) $progressColor = 'bg-yellow-400';
                        @endphp
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-white text-xs font-bold">
                                        {{ substr($santri->nama, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-on-surface text-sm">{{ $santri->nama }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-on-surface-variant text-sm">{{ $santri->halaqoh->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-on-surface-variant text-sm italic">{{ $santri->halaqoh->musyrif->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="text-primary font-bold">{{ $juz }}</span>
                                <span class="text-xs text-on-surface-variant">/ 30 Juz</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="w-24 bg-surface-container-highest h-1.5 rounded-full overflow-hidden">
                                    <div class="{{ $progressColor }} h-full rounded-full transition-all duration-500"
                                         style="width: {{ $percent }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="openUpdateJuzModal({{ $santri->id }}, {{ $juz }}, '{{ $santri->nama }}')" class="px-3 py-1.5 rounded-lg bg-surface-container-high text-primary font-bold hover:bg-primary hover:text-white transition-all text-xs" title="Update Capaian Juz">
                                        Update Juz
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div> <!-- End Tab Santri -->

            {{-- TAB: KELOLA HALAQOH --}}
            <div id="tab-halaqoh" class="tab-content hidden">
                <div class="p-6 flex justify-between items-center border-b border-outline-variant/10">
                    <h4 class="text-lg font-bold text-on-surface">Data Halaqoh</h4>
                    <button onclick="openHalaqohModal()" class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-container transition-colors flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span> Tambah Halaqoh
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-lowest text-on-surface-variant uppercase text-[10px] font-bold tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Nama Halaqoh</th>
                                <th class="px-6 py-4">Musyrif Pembina</th>
                                <th class="px-6 py-4">Jumlah Santri</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10">
                            @forelse($halaqohs as $hq)
                            <tr class="hover:bg-surface-container-low transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-secondary text-xs font-bold">
                                            {{ substr($hq->nama, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-on-surface text-sm">{{ $hq->nama }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-on-surface-variant text-sm font-bold">{{ $hq->musyrif->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-primary font-bold">{{ $hq->santri->count() }} <span class="text-xs text-on-surface-variant font-normal">Santri</span></td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @php
                                            $santriIds = $hq->santri->pluck('id')->toJson();
                                        @endphp
                                        <button onclick="openHalaqohModal({{ $hq->id }}, '{{ addslashes($hq->nama) }}', {{ $hq->musyrif_id ?? 'null' }}, {{ $santriIds }})" class="p-2 rounded-lg bg-surface-container-high text-primary hover:bg-primary hover:text-white transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <form action="{{ route('tahfizh.halaqoh.destroy', $hq->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus halaqoh ini? Semua santri di dalamnya akan dilepas dari halaqoh ini.')" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-surface-container-high text-error hover:bg-error hover:text-white transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-on-surface-variant">Belum ada data halaqoh.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div> <!-- End Tab Halaqoh -->

        </section>

    {{-- Grafik Perkembangan --}}
    <section class="glassmorphism p-6 rounded-3xl border border-white/20 shadow-sm mt-8">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-xl font-bold text-on-surface">Aktivitas Setoran (7 Hari Terakhir)</h4>
        </div>
        <div class="h-64 flex items-end justify-between gap-2 px-2">
            @foreach($weekData as $i => $day)
            <div class="flex-1 rounded-t-lg relative group cursor-pointer hover:opacity-80 transition-all duration-200 flex flex-col justify-end"
                 style="height: 100%; background: transparent;">
                 <div class="w-full rounded-t-lg" style="height: {{ max(5, $day['height']) }}%; background: {{ $i === 6 ? '#065f46' : 'rgba(6,95,70,0.25)' }};"></div>
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                    {{ $day['val'] }} Setoran
                </div>
            </div>
            @endforeach
        </div>
        <div class="flex justify-between mt-4 px-2 text-[10px] text-on-surface-variant font-bold uppercase tracking-widest text-center">
            @foreach($weekData as $day)
            <span class="flex-1">{{ $day['day'] }}</span>
            @endforeach
        </div>
    </section>

    </div>

    {{-- Right Sidebar (25%) --}}
    <aside class="space-y-6">
        {{-- Setoran Hari Ini --}}
        <div class="glassmorphism rounded-3xl border border-white/20 shadow-sm flex flex-col" style="max-height: 600px;">
            <div class="p-6 border-b border-outline-variant/10">
                <h4 class="text-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">schedule</span>
                    Setoran Hari Ini
                </h4>
                <p class="text-[11px] text-on-surface-variant mt-0.5">Real-time updates</p>
            </div>
            <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-5">
                @forelse($recentSetoran as $setoran)
                <div class="relative pl-6 border-l-2 {{ $setoran->surah ? 'border-primary-fixed' : 'border-outline-variant/30' }}">
                    <div class="absolute -left-1.5 top-0 w-3 h-3 rounded-full {{ $setoran->surah ? 'bg-primary ring-4 ring-white' : 'bg-outline-variant ring-4 ring-white' }}"></div>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-bold text-on-surface text-sm">{{ $setoran->santri->nama }}</p>
                            @if($setoran->surah)
                            <p class="text-xs text-primary font-medium">{{ $setoran->surah }}: {{ $setoran->ayat_dari }}-{{ $setoran->ayat_sampai }}</p>
                            @endif
                        </div>
                        <span class="text-[10px] text-on-surface-variant whitespace-nowrap ml-2">{{ \Carbon\Carbon::parse($setoran->created_at)->diffForHumans(null, true) }}</span>
                    </div>
                    @if($setoran->juz)
                        <div class="mt-1 text-[10px] text-on-surface-variant font-bold">Juz {{ $setoran->juz }}</div>
                    @endif
                    @if($setoran->nilai)
                    <div class="mt-1.5 px-2 py-0.5 {{ str_contains(strtolower($setoran->nilai), 'jayyid') || $setoran->nilai == 'Mumtaz' ? 'bg-primary-fixed/20 text-primary-container' : 'bg-surface-container-high text-on-surface-variant' }} rounded text-[10px] font-bold inline-block">{{ $setoran->nilai }}</div>
                    @endif
                </div>
                @empty
                <div class="text-center text-sm text-on-surface-variant py-4">Belum ada setoran hari ini.</div>
                @endforelse
            </div>
        </div>

        {{-- Tip Musyrif --}}
        <div class="bg-gradient-to-br from-primary to-emerald-800 p-6 rounded-3xl text-white relative overflow-hidden group cursor-default">
            <div class="absolute -bottom-10 -right-10 opacity-10 group-hover:scale-125 transition-transform duration-500">
                <span class="material-symbols-outlined text-[150px]">lightbulb</span>
            </div>
            <h5 class="font-bold mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-yellow-400">lightbulb</span>
                Tip Musyrif
            </h5>
            <p class="text-xs opacity-90 leading-relaxed">Fokus pada kualitas makhraj di surah-surah pendek sebelum beralih ke hafalan baru untuk memperkuat dasar Santri.</p>
        </div>
    </aside>
</div>

{{-- Floating Action Button --}}
<button id="openSetoranModal"
    class="fixed bottom-8 right-8 w-16 h-16 bg-primary-container text-white rounded-full shadow-2xl flex items-center justify-center group active:scale-95 transition-all z-50 hover:opacity-90">
    <span class="material-symbols-outlined text-3xl group-hover:rotate-90 transition-transform duration-300">add</span>
</button>

{{-- Modal: Input Setoran --}}
<div id="setoranModal" class="fixed inset-0 bg-on-surface/40 backdrop-blur-sm z-[60] flex items-center justify-center hidden">
    <div id="setoranModalContent" class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-6 bg-primary text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined">library_add</span>
                <h3 class="font-bold text-lg">Input Record Setoran</h3>
            </div>
            <button id="closeSetoranModal" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('tahfizh.setoran.store') }}" class="p-8 space-y-5" id="setoranForm">
            @csrf
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Nama Santri</label>
                <select name="santri_id" required class="w-full border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary text-on-surface py-3 px-4 appearance-none outline-none">
                    <option value="">Pilih Santri...</option>
                    @foreach($santris as $s)
                    <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Surah</label>
                    <input name="surah" type="text" required placeholder="e.g. Al-Baqarah"
                        class="w-full border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary px-4 py-3 text-sm outline-none"/>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Juz Ke-</label>
                    <input name="juz" type="number" required min="1" max="30" placeholder="1-30"
                        class="w-full border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary px-4 py-3 text-sm outline-none"/>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Ayat Dari</label>
                    <input name="ayat_dari" type="number" min="1" placeholder="1"
                        class="w-full border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary px-4 py-3 text-sm outline-none"/>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Ayat Sampai</label>
                    <input name="ayat_sampai" type="number" min="1" placeholder="10"
                        class="w-full border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary px-4 py-3 text-sm outline-none"/>
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Nilai</label>
                <div class="flex gap-2 flex-wrap" id="nilaiGroup">
                    @php
                        $nilaiOptions = ['Mumtaz', 'Jayyid Jiddan', 'Jayyid', 'Maqbul', 'Rosib'];
                    @endphp
                    @foreach($nilaiOptions as $nilai)
                    <button type="button" data-nilai="{{ $nilai }}"
                        class="nilai-btn px-3 py-1.5 rounded-lg border border-outline-variant text-on-surface-variant text-xs hover:border-primary hover:text-primary transition-all focus:outline-none">
                        {{ $nilai }}
                    </button>
                    @endforeach
                    <input type="hidden" name="nilai" id="selectedNilai" required/>
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Catatan (Opsional)</label>
                <textarea name="catatan" rows="2" placeholder="Berikan arahan untuk Santri..."
                    class="w-full border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary px-4 py-3 text-sm outline-none resize-none"></textarea>
            </div>
            <div class="pt-2">
                <button type="submit" class="w-full py-4 bg-primary text-white font-bold rounded-2xl shadow-xl hover:opacity-90 active:scale-[0.98] transition-all">
                    Simpan Record Setoran
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Update Capaian Juz --}}
<div id="updateJuzModal" class="fixed inset-0 bg-on-surface/40 backdrop-blur-sm z-[60] flex items-center justify-center hidden">
    <div id="updateJuzModalContent" class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-6 bg-secondary text-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined">trending_up</span>
                <h3 class="font-bold text-lg">Update Capaian Juz</h3>
            </div>
            <button id="closeUpdateJuzModal" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="" id="updateJuzForm" class="p-8 space-y-5">
            @csrf @method('PUT')
            <div class="text-center">
                <p class="text-sm text-on-surface-variant mb-1">Santri:</p>
                <h4 class="text-lg font-bold text-on-surface" id="updateJuzNamaSantri">Nama Santri</h4>
            </div>
            <div class="space-y-1 text-center mt-6">
                <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Total Juz Diselesaikan</label>
                <div class="flex items-center justify-center gap-4 mt-2">
                    <button type="button" onclick="ubahJuz(-1)" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:bg-surface-container-high transition-colors focus:outline-none">
                        <span class="material-symbols-outlined">remove</span>
                    </button>
                    <input name="juz_selesai" id="juzSelesaiInput" type="number" required min="0" max="30" readonly
                        class="w-20 text-center font-black text-3xl text-primary bg-transparent outline-none"/>
                    <button type="button" onclick="ubahJuz(1)" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center hover:bg-surface-container-high transition-colors focus:outline-none">
                        <span class="material-symbols-outlined">add</span>
                    </button>
                </div>
            </div>
            <div class="pt-6">
                <button type="submit" class="w-full py-4 bg-secondary text-white font-bold rounded-2xl shadow-xl hover:opacity-90 active:scale-[0.98] transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Kelola Halaqoh --}}
<div id="halaqohModal" class="fixed inset-0 bg-on-surface/40 backdrop-blur-sm z-[70] flex items-center justify-center hidden">
    <div id="halaqohModalContent" class="bg-white w-full max-w-lg max-h-[90vh] flex flex-col rounded-3xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-6 bg-primary text-white flex justify-between items-center shrink-0">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined">groups</span>
                <h3 class="font-bold text-lg" id="halaqohModalTitle">Tambah Halaqoh Baru</h3>
            </div>
            <button onclick="closeHalaqohModal()" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <form method="POST" action="{{ route('tahfizh.halaqoh.store') }}" id="halaqohForm" class="flex flex-col overflow-hidden min-h-0 h-full">
            @csrf
            <input type="hidden" name="_method" id="halaqohMethod" value="POST">
            
            <div class="p-6 space-y-5 overflow-y-auto custom-scrollbar flex-1">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Nama Halaqoh</label>
                    <input name="nama" id="halaqohNama" type="text" required placeholder="Contoh: Halaqoh Abu Bakar"
                        class="w-full border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary px-4 py-3 text-sm outline-none"/>
                </div>
                
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Musyrif Pembina</label>
                    <select name="musyrif_id" id="halaqohMusyrif" required class="w-full border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary text-on-surface py-3 px-4 outline-none">
                        <option value="">-- Pilih Musyrif --</option>
                        @foreach($musyrifs as $musyrif)
                        <option value="{{ $musyrif->id }}">{{ $musyrif->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-1 pt-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Pilih Santri</label>
                        <span class="text-[10px] text-primary font-bold px-2 py-1 bg-primary/10 rounded" id="selectedSantriCount">0 Dipilih</span>
                    </div>
                    
                    <div class="border border-outline-variant rounded-xl max-h-48 overflow-y-auto custom-scrollbar">
                        @foreach($semuaSantri as $santri)
                        <label class="flex items-center px-4 py-3 border-b border-outline-variant/30 hover:bg-surface-container-lowest cursor-pointer transition-colors group">
                            <input type="checkbox" name="santri_ids[]" value="{{ $santri->id }}" class="santri-checkbox w-5 h-5 text-primary border-outline-variant rounded focus:ring-primary" onchange="updateSantriCount()">
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-on-surface group-hover:text-primary transition-colors">{{ $santri->nama }}</span>
                                <span class="block text-[10px] text-on-surface-variant">{{ $santri->kelas->nama ?? 'Tanpa Kelas' }} | Saat ini: {{ $santri->halaqoh->nama ?? 'Belum ada Halaqoh' }}</span>
                            </div>
                        </label>
                        @endforeach
                        @if($semuaSantri->isEmpty())
                        <div class="p-4 text-center text-xs text-on-surface-variant">Tidak ada data santri.</div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="p-6 bg-surface-container-lowest border-t border-outline-variant/20 shrink-0">
                <button type="submit" class="w-full py-3.5 bg-primary text-white font-bold rounded-xl shadow-lg hover:opacity-90 active:scale-[0.98] transition-all">
                    Simpan Halaqoh
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Setoran Modal
    const setoranModal = document.getElementById('setoranModal');
    const setoranModalContent = document.getElementById('setoranModalContent');

    function openModal() {
        setoranModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            setoranModalContent.classList.remove('scale-95', 'opacity-0');
        });
    }
    function closeModal() {
        setoranModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => setoranModal.classList.add('hidden'), 300);
    }

    document.getElementById('openSetoranModal').addEventListener('click', openModal);
    document.getElementById('closeSetoranModal').addEventListener('click', closeModal);
    setoranModal.addEventListener('click', (e) => { if (e.target === setoranModal) closeModal(); });

    // Nilai button selection in Setoran Form
    document.querySelectorAll('.nilai-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.nilai-btn').forEach(b => {
                b.classList.remove('bg-primary', 'text-white', 'border-primary');
                b.classList.add('border-outline-variant', 'text-on-surface-variant');
            });
            this.classList.add('bg-primary', 'text-white', 'border-primary');
            this.classList.remove('border-outline-variant', 'text-on-surface-variant');
            document.getElementById('selectedNilai').value = this.dataset.nilai;
        });
    });

    // Form validation for Setoran
    document.getElementById('setoranForm').addEventListener('submit', function(e) {
        if(!document.getElementById('selectedNilai').value) {
            e.preventDefault();
            alert('Mohon pilih nilai setoran!');
        }
    });


    // Update Juz Modal
    const updateJuzModal = document.getElementById('updateJuzModal');
    const updateJuzModalContent = document.getElementById('updateJuzModalContent');
    const updateJuzForm = document.getElementById('updateJuzForm');
    const juzInput = document.getElementById('juzSelesaiInput');
    const namaSantriText = document.getElementById('updateJuzNamaSantri');

    function openUpdateJuzModal(id, currentJuz, nama) {
        updateJuzForm.action = `/admin/tahfizh/hafalan/${id}`;
        juzInput.value = currentJuz;
        namaSantriText.innerText = nama;

        updateJuzModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            updateJuzModalContent.classList.remove('scale-95', 'opacity-0');
        });
    }

    function closeUpdateJuzModal() {
        updateJuzModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => updateJuzModal.classList.add('hidden'), 300);
    }

    document.getElementById('closeUpdateJuzModal').addEventListener('click', closeUpdateJuzModal);
    updateJuzModal.addEventListener('click', (e) => { if (e.target === updateJuzModal) closeUpdateJuzModal(); });

    function ubahJuz(delta) {
        let val = parseInt(juzInput.value) || 0;
        val += delta;
        if(val >= 0 && val <= 30) {
            juzInput.value = val;
        }
    }

    // Tabs Logic
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Reset all buttons
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('active', 'text-primary', 'border-primary');
                b.classList.add('text-on-surface-variant', 'border-transparent');
            });
            // Set active button
            btn.classList.add('active', 'text-primary', 'border-primary');
            btn.classList.remove('text-on-surface-variant', 'border-transparent');
            
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(c => {
                c.classList.remove('block');
                c.classList.add('hidden');
            });
            // Show target content
            document.querySelector(btn.dataset.target).classList.remove('hidden');
            document.querySelector(btn.dataset.target).classList.add('block');
        });
    });

    // Halaqoh Modal Logic
    const halaqohModal = document.getElementById('halaqohModal');
    const halaqohModalContent = document.getElementById('halaqohModalContent');
    const halaqohForm = document.getElementById('halaqohForm');
    
    function openHalaqohModal(id = null, nama = '', musyrif_id = '', santriIds = []) {
        if (id) {
            document.getElementById('halaqohModalTitle').innerText = 'Edit Halaqoh';
            halaqohForm.action = `/admin/tahfizh/halaqoh/${id}`;
            document.getElementById('halaqohMethod').value = 'PUT';
            document.getElementById('halaqohNama').value = nama;
            document.getElementById('halaqohMusyrif').value = musyrif_id;
        } else {
            document.getElementById('halaqohModalTitle').innerText = 'Tambah Halaqoh Baru';
            halaqohForm.action = `/admin/tahfizh/halaqoh`;
            document.getElementById('halaqohMethod').value = 'POST';
            document.getElementById('halaqohNama').value = '';
            document.getElementById('halaqohMusyrif').value = '';
        }

        // Reset checkboxes
        document.querySelectorAll('.santri-checkbox').forEach(cb => {
            cb.checked = santriIds.includes(parseInt(cb.value));
        });
        updateSantriCount();

        halaqohModal.classList.remove('hidden');
        requestAnimationFrame(() => {
            halaqohModalContent.classList.remove('scale-95', 'opacity-0');
        });
    }

    function closeHalaqohModal() {
        halaqohModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => halaqohModal.classList.add('hidden'), 300);
    }
    halaqohModal.addEventListener('click', (e) => { if (e.target === halaqohModal) closeHalaqohModal(); });

    function updateSantriCount() {
        const count = document.querySelectorAll('.santri-checkbox:checked').length;
        document.getElementById('selectedSantriCount').innerText = `${count} Dipilih`;
    }
</script>
@endpush
