@extends('layouts.app')

@section('title', 'Data Nilai Akademik — SUNTRI')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-secondary rounded-2xl shadow-lg">
                <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">bar_chart</span>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-secondary">Data Nilai Santri</h2>
                <p class="text-sm text-on-surface-variant">Kelola nilai Harian, UTS, dan UAS santri secara terpadu.</p>
            </div>
        </div>
        <button onclick="document.getElementById('modal-tambah-nilai').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2.5 bg-secondary text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">add</span>
            Input Nilai Baru
        </button>
    </div>

    {{-- Main Content --}}
    <div class="glassmorphism rounded-2xl border border-white/20 shadow-sm overflow-hidden fade-in-up delay-1">
        <div class="p-5 border-b border-outline-variant/20 bg-white/40">
            <h3 class="text-base font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">bar_chart</span>
                Rekapitulasi Nilai
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-surface-container-low/50 text-xs text-on-surface-variant uppercase tracking-wider border-b border-outline-variant/20">
                        <th class="px-5 py-3 font-bold">Santri</th>
                        <th class="px-5 py-3 font-bold">Mata Pelajaran</th>
                        <th class="px-5 py-3 font-bold">SMT / TA</th>
                        <th class="px-5 py-3 font-bold text-center">Harian</th>
                        <th class="px-5 py-3 font-bold text-center">UTS</th>
                        <th class="px-5 py-3 font-bold text-center">UAS</th>
                        <th class="px-5 py-3 font-bold text-center">Akhir</th>
                        <th class="px-5 py-3 font-bold text-center">Predikat</th>
                        <th class="px-5 py-3 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 bg-white/30">
                    @forelse($nilais as $nilai)
                    <tr class="hover:bg-white/60 transition-colors group">
                        <td class="px-5 py-3">
                            <p class="text-sm font-bold text-on-surface">{{ $nilai->santri->nama }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $nilai->santri->nis ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-3 text-sm font-medium">{{ $nilai->mapel->nama }}</td>
                        <td class="px-5 py-3 text-sm font-medium">Smt {{ $nilai->semester }} / {{ $nilai->tahun_ajaran }}</td>
                        <td class="px-5 py-3 text-center text-sm font-bold">{{ $nilai->nilai_harian ?? '-' }}</td>
                        <td class="px-5 py-3 text-center text-sm font-bold text-secondary">{{ $nilai->nilai_uts ?? '-' }}</td>
                        <td class="px-5 py-3 text-center text-sm font-bold text-primary">{{ $nilai->nilai_uas ?? '-' }}</td>
                        <td class="px-5 py-3 text-center text-sm font-bold bg-surface-container/50">{{ $nilai->nilai_akhir ?? '-' }}</td>
                        <td class="px-5 py-3 text-center">
                            @php
                                $color = match($nilai->predikat) {
                                    'A' => 'text-success bg-success/10 border-success/20',
                                    'B' => 'text-info bg-info/10 border-info/20',
                                    'C' => 'text-warning bg-warning/10 border-warning/20',
                                    'D' => 'text-orange-500 bg-orange-500/10 border-orange-500/20',
                                    default => 'text-error bg-error/10 border-error/20'
                                };
                            @endphp
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border text-sm font-bold {{ $color }}">
                                {{ $nilai->predikat ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('akademik.nilai.destroy', $nilai->id) }}" method="POST" onsubmit="return confirm('Hapus data nilai ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full hover:bg-error/10 flex items-center justify-center text-error transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-10 text-center text-on-surface-variant text-sm">Belum ada data nilai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah Nilai --}}
<div id="modal-tambah-nilai" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-secondary/10 px-8 py-5 border-b border-secondary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">add</span> Input Nilai Santri
            </h3>
            <button onclick="document.getElementById('modal-tambah-nilai').classList.add('hidden')" class="text-secondary hover:bg-secondary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('akademik.nilai.store') }}" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Santri</label>
                <select name="santri_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                    <option value="">Pilih Santri...</option>
                    @foreach($santris as $s)
                    <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Mata Pelajaran</label>
                    <select name="mapel_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                        <option value="">Pilih Mapel...</option>
                        @foreach($mapels as $m)
                        <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Semester</label>
                    <select name="semester" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                        <option value="1">1 (Ganjil)</option>
                        <option value="2">2 (Genap)</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" value="{{ date('Y').'/'.(date('Y')+1) }}" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Harian</label>
                    <input type="number" step="0.01" name="nilai_harian" max="100" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none" placeholder="0-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">UTS</label>
                    <input type="number" step="0.01" name="nilai_uts" max="100" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none" placeholder="0-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">UAS</label>
                    <input type="number" step="0.01" name="nilai_uas" max="100" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none" placeholder="0-100">
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 py-3 bg-secondary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Nilai</button>
            </div>
        </form>
    </div>
</div>

@endsection
