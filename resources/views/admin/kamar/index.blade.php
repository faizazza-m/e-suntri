@extends('layouts.app')

@section('title', 'Manajemen Asrama & Kamar')
@section('header', 'Kamar Santri')

@section('content')
<div class="mb-6 flex justify-between items-center fade-in-up">
    <div>
        <h2 class="text-2xl font-black text-on-surface">Data Kamar</h2>
        <p class="text-sm text-on-surface-variant">Kelola daftar kamar dan kapasitasnya.</p>
    </div>
    <button onclick="document.getElementById('modal-tambah-kamar').classList.remove('hidden')" class="btn-primary flex items-center gap-2">
        <span class="material-symbols-outlined">add</span> Tambah Kamar
    </button>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl border border-green-200 fade-in-up">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in-up fade-up-1">
    @forelse($kamars as $kamar)
    <div class="bg-surface rounded-2xl p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between hover:border-primary/50 transition-colors">
        <div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">meeting_room</span>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold px-2 py-1 bg-surface-container rounded-lg text-on-surface-variant">Gedung {{ $kamar->gedung ?? '-' }} / Lt. {{ $kamar->lantai ?? '-' }}</span>
                </div>
            </div>
            <h3 class="text-lg font-black text-on-surface">{{ $kamar->nama }}</h3>
            
            <div class="mt-4 bg-surface-container-lowest rounded-xl p-3 border border-outline-variant/20">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-on-surface-variant font-medium">Kapasitas Terisi</span>
                    <span class="font-bold {{ $kamar->penghuni_count >= $kamar->kapasitas ? 'text-error' : 'text-primary' }}">
                        {{ $kamar->penghuni_count }} / {{ $kamar->kapasitas }}
                    </span>
                </div>
                <div class="w-full bg-surface-variant rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full" style="width: {{ min(100, ($kamar->penghuni_count / $kamar->kapasitas) * 100) }}%"></div>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ route('kamar.show', $kamar->id) }}" class="w-full py-2.5 bg-surface-container hover:bg-primary/10 text-primary font-bold text-sm text-center rounded-xl transition-colors inline-block">
                Kelola Penghuni
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center bg-surface-container-lowest rounded-3xl border border-outline-variant/30">
        <span class="material-symbols-outlined text-outline-variant text-5xl mb-3">meeting_room</span>
        <h3 class="text-lg font-bold text-on-surface">Belum ada data kamar</h3>
        <p class="text-sm text-on-surface-variant mt-1">Silakan tambahkan kamar asrama baru.</p>
    </div>
    @endforelse
</div>

{{-- Modal Tambah Kamar --}}
<div id="modal-tambah-kamar" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-md flex flex-col relative z-10 overflow-hidden border border-white/20">
        <div class="bg-primary/10 px-6 py-4 border-b border-primary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">add_box</span> Tambah Kamar
            </h3>
            <button onclick="document.getElementById('modal-tambah-kamar').classList.add('hidden')" class="text-primary hover:bg-primary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('kamar.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Nama Kamar</label>
                <input type="text" name="nama" required placeholder="Contoh: Kamar A1" class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Kapasitas (Orang)</label>
                <input type="number" name="kapasitas" required value="8" min="1" class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Gedung</label>
                    <input type="text" name="gedung" placeholder="Contoh: Asrama Putra 1" class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Lantai</label>
                    <input type="number" name="lantai" min="1" value="1" class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-tambah-kamar').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
