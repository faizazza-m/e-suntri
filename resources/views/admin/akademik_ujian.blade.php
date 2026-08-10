@extends('layouts.app')

@section('title', 'Ujian & Tugas — SUNTRI')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-secondary rounded-2xl shadow-lg">
                <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">event_note</span>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-secondary">Jadwal Ujian & Tugas</h2>
                <p class="text-sm text-on-surface-variant">Kelola agenda akademik seperti UTS, UAS, atau penugasan besar.</p>
            </div>
        </div>
        <button onclick="document.getElementById('modal-tambah-ujian').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">add</span>
            Tambah Agenda
        </button>
    </div>

    {{-- Main Content: Timeline View --}}
    <div class="grid grid-cols-1 gap-6 fade-in-up delay-1">
        @forelse($ujians as $ujian)
        <div class="glassmorphism rounded-2xl border border-white/20 shadow-sm p-6 flex flex-col md:flex-row gap-6 items-start hover:border-primary/30 transition-colors relative group">
            
            {{-- Date Bubble --}}
            <div class="flex-shrink-0 w-20 h-20 rounded-2xl bg-surface-container flex flex-col items-center justify-center border border-outline-variant/30 text-center">
                <span class="text-sm font-bold text-error uppercase">{{ \Carbon\Carbon::parse($ujian->tanggal)->translatedFormat('M') }}</span>
                <span class="text-2xl font-black text-on-surface leading-none">{{ \Carbon\Carbon::parse($ujian->tanggal)->format('d') }}</span>
            </div>

            {{-- Info --}}
            <div class="flex-grow">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest rounded bg-primary/10 text-primary">{{ $ujian->tipe }}</span>
                    <span class="text-xs font-bold text-on-surface-variant bg-surface-container px-2 py-1 rounded">Kelas: {{ $ujian->kelas->nama }}</span>
                </div>
                <h3 class="text-lg font-bold text-on-surface mb-1">{{ $ujian->judul }}</h3>
                <p class="text-sm text-on-surface-variant font-medium flex items-center gap-1.5 mb-3">
                    <span class="material-symbols-outlined text-[16px] text-secondary">menu_book</span> {{ $ujian->mapel->nama }}
                    @if($ujian->jam_mulai && $ujian->jam_selesai)
                    <span class="mx-2">•</span>
                    <span class="material-symbols-outlined text-[16px] text-primary">schedule</span> {{ substr($ujian->jam_mulai, 0, 5) }} - {{ substr($ujian->jam_selesai, 0, 5) }}
                    @endif
                </p>
                @if($ujian->keterangan)
                <div class="p-3 bg-surface-container/50 rounded-xl border border-outline-variant/20 text-sm text-on-surface-variant">
                    {{ $ujian->keterangan }}
                </div>
                @endif
            </div>

            {{-- Delete Button --}}
            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                <form action="{{ route('akademik.ujian.destroy', $ujian->id) }}" method="POST" onsubmit="return confirm('Yakin menghapus agenda ini?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-8 h-8 rounded-full hover:bg-error/10 flex items-center justify-center text-error transition-colors" title="Hapus Agenda">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="glassmorphism rounded-2xl border border-white/20 p-12 text-center flex flex-col items-center justify-center">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant/50 mb-3" style="font-variation-settings: 'FILL' 1;">event_busy</span>
            <h3 class="text-lg font-bold text-on-surface mb-1">Belum ada agenda ujian.</h3>
            <p class="text-sm text-on-surface-variant">Tambahkan agenda ujian atau tugas untuk santri.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Tambah Agenda --}}
<div id="modal-tambah-ujian" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-primary/10 px-8 py-5 border-b border-primary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">event</span> Buat Agenda Baru
            </h3>
            <button onclick="document.getElementById('modal-tambah-ujian').classList.add('hidden')" class="text-primary hover:bg-primary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('akademik.ujian.store') }}" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Judul Agenda</label>
                <input type="text" name="judul" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none" placeholder="Cth: UTS Semester Ganjil 2026">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Jenis</label>
                    <select name="tipe" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="Ujian">Ujian / Tes</option>
                        <option value="Tugas">Tugas Besar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Tanggal</label>
                    <input type="date" name="tanggal" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Mata Pelajaran</label>
                    <select name="mapel_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="">Pilih Mapel...</option>
                        @foreach($mapels as $m)
                        <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Kelas</label>
                    <select name="kelas_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="">Pilih Kelas...</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Jam Mulai (Opsional)</label>
                    <input type="time" name="jam_mulai" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Jam Selesai (Opsional)</label>
                    <input type="time" name="jam_selesai" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Keterangan / Instruksi</label>
                <textarea name="keterangan" rows="3" class="w-full p-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none resize-none" placeholder="Cth: Bawa alat tulis lengkap..."></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>

@endsection
