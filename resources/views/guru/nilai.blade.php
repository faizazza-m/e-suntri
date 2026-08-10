@extends('layouts.guru')
@section('title', 'Input Nilai')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 fade-up">
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-2xl bg-indigo-500/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-indigo-600 text-2xl" style="font-variation-settings:'FILL' 1;">grade</span>
        </div>
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-on-surface">Input Nilai Santri</h1>
            <p class="text-xs text-on-surface-variant">Pilih mapel, kelas & semester, lalu simpan nilai satu per satu.</p>
        </div>
    </div>
</div>

{{-- Info: Belum ditugaskan --}}
@if($tidakDitugaskan)
<div class="flex items-start gap-3 bg-amber-500/10 border border-amber-500/30 rounded-2xl px-4 py-3 mb-5 fade-up">
    <span class="material-symbols-outlined text-amber-600 shrink-0 mt-0.5" style="font-variation-settings:'FILL' 1;">info</span>
    <div>
        <p class="text-sm font-bold text-amber-700">Anda belum ditugaskan ke mata pelajaran tertentu.</p>
        <p class="text-xs text-amber-600 mt-0.5">Menampilkan semua mata pelajaran yang tersedia. Hubungi Admin untuk mengatur penugasan resmi.</p>
    </div>
</div>
@endif

{{-- Filter Form --}}
<form method="GET" action="{{ route('guru.nilai') }}"
      class="glass-card rounded-2xl border border-outline-variant/20 p-5 mb-6 fade-up shadow-sm">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
        <div>
            <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">Mata Pelajaran</label>
            <select name="mapel_id"
                class="w-full h-10 px-3 border border-outline-variant rounded-xl text-sm bg-white focus:ring-2 focus:ring-indigo-500 outline-none appearance-none">
                @foreach($mapelList as $m)
                <option value="{{ $m->id }}" {{ $mapelId == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">Kelas</label>
            <select name="kelas_id"
                class="w-full h-10 px-3 border border-outline-variant rounded-xl text-sm bg-white focus:ring-2 focus:ring-indigo-500 outline-none appearance-none">
                @foreach($kelas as $k)
                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                    {{ $k->nama }} – {{ $k->julukan }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">Semester</label>
            <select name="semester"
                class="w-full h-10 px-3 border border-outline-variant rounded-xl text-sm bg-white focus:ring-2 focus:ring-indigo-500 outline-none appearance-none">
                <option value="1" {{ $semester == 1 ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                <option value="2" {{ $semester == 2 ? 'selected' : '' }}>Semester 2 (Genap)</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" value="{{ $tahunAjaran }}" placeholder="2025/2026"
                class="w-full h-10 px-3 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>
    </div>
    <button type="submit"
        class="flex items-center gap-2 px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:opacity-90 shadow-sm transition-opacity">
        <span class="material-symbols-outlined text-sm">filter_alt</span>
        Tampilkan Santri
    </button>
</form>

{{-- Nilai Table / Cards --}}
@if($santriList->isNotEmpty())
<div class="glass-card rounded-2xl border border-outline-variant/20 overflow-hidden fade-up shadow-sm">

    {{-- Table Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b border-outline-variant/10 bg-indigo-500/5">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600 text-lg" style="font-variation-settings:'FILL' 1;">people</span>
            <p class="text-sm font-bold text-on-surface">{{ $santriList->count() }} Santri Ditemukan</p>
        </div>
        <div class="flex flex-wrap gap-3 text-[10px] font-bold text-on-surface-variant">
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-400 inline-block"></span> Harian 30%</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-orange-500 inline-block"></span> UTS 30%</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span> UAS 40%</span>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-surface-container/30 text-on-surface-variant text-[10px] uppercase tracking-wider">
                    <th class="px-5 py-3 text-left font-bold">Santri</th>
                    <th class="px-4 py-3 text-center font-bold">Harian</th>
                    <th class="px-4 py-3 text-center font-bold">UTS</th>
                    <th class="px-4 py-3 text-center font-bold">UAS</th>
                    <th class="px-4 py-3 text-center font-bold">Nilai Akhir</th>
                    <th class="px-4 py-3 text-center font-bold">Predikat</th>
                    <th class="px-4 py-3 text-center font-bold">Simpan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @foreach($santriList as $idx => $santri)
                @php $nilai = $santri->nilaiAkademik->first(); @endphp
                <form method="POST" action="{{ route('guru.nilai.store') }}">
                @csrf
                <input type="hidden" name="santri_id"    value="{{ $santri->id }}">
                <input type="hidden" name="mapel_id"     value="{{ $mapelId }}">
                <input type="hidden" name="semester"     value="{{ $semester }}">
                <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">
                <tr class="{{ $idx % 2 == 0 ? 'bg-white' : 'bg-surface-container-low/50' }} hover:bg-indigo-50/50 transition-colors group">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-500/10 flex items-center justify-center shrink-0">
                                <span class="text-xs font-black text-indigo-600">{{ substr($santri->nama, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-on-surface text-sm leading-tight">{{ $santri->nama }}</p>
                                <p class="text-[10px] text-on-surface-variant">{{ $santri->nis }}</p>
                            </div>
                        </div>
                    </td>
                    @foreach([['name'=>'nilai_harian','val'=>$nilai?->nilai_harian,'color'=>'yellow'],['name'=>'nilai_uts','val'=>$nilai?->nilai_uts,'color'=>'orange'],['name'=>'nilai_uas','val'=>$nilai?->nilai_uas,'color'=>'red']] as $f)
                    <td class="px-4 py-3.5 text-center">
                        <input type="number" name="{{ $f['name'] }}" value="{{ $f['val'] }}"
                            min="0" max="100" step="0.01"
                            class="w-16 h-9 text-center text-sm font-semibold border border-outline-variant/40 rounded-xl
                                   focus:ring-2 focus:ring-{{ $f['color'] }}-400 focus:border-{{ $f['color'] }}-400
                                   outline-none bg-white hover:border-{{ $f['color'] }}-400 transition-colors"
                            placeholder="–">
                    </td>
                    @endforeach
                    <td class="px-4 py-3.5 text-center">
                        @if($nilai?->nilai_akhir !== null)
                        <span class="text-base font-black {{ $nilai->nilai_akhir >= 75 ? 'text-green-600' : ($nilai->nilai_akhir >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                            {{ $nilai->nilai_akhir }}
                        </span>
                        @else
                        <span class="text-on-surface-variant text-sm">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        @if($nilai?->predikat)
                        @php $pColor = match($nilai->predikat) { 'A'=>'green','B'=>'blue','C'=>'yellow','D'=>'orange',default=>'red' }; @endphp
                        <span class="inline-flex w-8 h-8 items-center justify-center rounded-xl text-xs font-black bg-{{ $pColor }}-100 text-{{ $pColor }}-700">
                            {{ $nilai->predikat }}
                        </span>
                        @else
                        <span class="text-on-surface-variant">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <button type="submit"
                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:opacity-90 transition-opacity shadow-sm">
                            <span class="material-symbols-outlined text-sm">save</span>
                            Simpan
                        </button>
                    </td>
                </tr>
                </form>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Card List --}}
    <div class="sm:hidden divide-y divide-outline-variant/10">
        @foreach($santriList as $santri)
        @php $nilai = $santri->nilaiAkademik->first(); @endphp
        <form method="POST" action="{{ route('guru.nilai.store') }}" class="p-4">
            @csrf
            <input type="hidden" name="santri_id"    value="{{ $santri->id }}">
            <input type="hidden" name="mapel_id"     value="{{ $mapelId }}">
            <input type="hidden" name="semester"     value="{{ $semester }}">
            <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">

            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-indigo-500/10 flex items-center justify-center shrink-0">
                        <span class="text-sm font-black text-indigo-600">{{ substr($santri->nama, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-bold text-on-surface text-sm">{{ $santri->nama }}</p>
                        <p class="text-[10px] text-on-surface-variant">{{ $santri->nis }}</p>
                    </div>
                </div>
                @if($nilai?->nilai_akhir !== null)
                <div class="text-right">
                    <p class="text-lg font-black {{ $nilai->nilai_akhir >= 75 ? 'text-green-600' : 'text-orange-500' }}">{{ $nilai->nilai_akhir }}</p>
                    @if($nilai->predikat)
                    @php $pColor = match($nilai->predikat) { 'A'=>'green','B'=>'blue','C'=>'yellow','D'=>'orange',default=>'red' }; @endphp
                    <span class="text-[10px] font-black text-{{ $pColor }}-600">{{ $nilai->predikat }}</span>
                    @endif
                </div>
                @endif
            </div>

            <div class="grid grid-cols-3 gap-2 mb-3">
                @foreach([['label'=>'Harian','name'=>'nilai_harian','val'=>$nilai?->nilai_harian],['label'=>'UTS','name'=>'nilai_uts','val'=>$nilai?->nilai_uts],['label'=>'UAS','name'=>'nilai_uas','val'=>$nilai?->nilai_uas]] as $f)
                <div>
                    <label class="text-[9px] text-on-surface-variant font-bold uppercase block mb-1">{{ $f['label'] }}</label>
                    <input type="number" name="{{ $f['name'] }}" value="{{ $f['val'] }}" min="0" max="100" step="0.01" placeholder="0"
                        class="w-full h-10 text-center text-sm font-semibold border border-outline-variant rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                </div>
                @endforeach
            </div>
            <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span> Simpan Nilai
            </button>
        </form>
        @endforeach
    </div>
</div>

@else
{{-- Empty state when no santri --}}
<div class="glass-card rounded-2xl border border-dashed border-outline-variant/40 p-12 text-center fade-up">
    <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 flex items-center justify-center mx-auto mb-4">
        <span class="material-symbols-outlined text-3xl text-indigo-400">people_outline</span>
    </div>
    <p class="font-bold text-on-surface text-sm mb-1">
        @if($mapelList->isEmpty())
            Belum ada mata pelajaran yang tersedia
        @else
            Pilih filter di atas lalu klik "Tampilkan Santri"
        @endif
    </p>
    <p class="text-xs text-on-surface-variant">
        @if($mapelList->isEmpty())
            Hubungi Admin untuk menambahkan mata pelajaran ke sistem.
        @else
            Pastikan kelas yang dipilih memiliki santri aktif.
        @endif
    </p>
</div>
@endif

@endsection
