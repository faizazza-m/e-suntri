@extends('layouts.app')

@section('title', 'Detail Kamar ' . $kamar->nama)
@section('header', 'Kamar ' . $kamar->nama)

@section('content')
<div class="mb-6 flex justify-between items-center fade-in-up">
    <div>
        <a href="{{ route('kamar') }}" class="text-sm font-bold text-primary hover:underline mb-1 inline-flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Kembali ke Daftar Kamar
        </a>
        <h2 class="text-2xl font-black text-on-surface">Kamar {{ $kamar->nama }}</h2>
        <p class="text-sm text-on-surface-variant">Gedung {{ $kamar->gedung ?? '-' }} / Lantai {{ $kamar->lantai ?? '-' }}</p>
    </div>
    <div class="flex items-center gap-4">
        <div class="text-right">
            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Kapasitas</p>
            <p class="text-xl font-black {{ $kamar->penghuni->count() >= $kamar->kapasitas ? 'text-error' : 'text-primary' }}">{{ $kamar->penghuni->count() }} / {{ $kamar->kapasitas }}</p>
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl border border-green-200 fade-in-up">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-6 p-4 bg-red-100 text-red-800 rounded-xl border border-red-200 fade-in-up">
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-in-up fade-up-1">
    
    {{-- Form Tambah Penghuni --}}
    <div class="lg:col-span-1">
        <div class="bg-surface rounded-2xl p-6 shadow-sm border border-outline-variant/30">
            <h3 class="text-sm font-bold text-on-surface uppercase tracking-widest mb-4">Tambahkan Penghuni</h3>
            
            <form method="POST" action="{{ route('kamar.santri.add', $kamar->id) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-on-surface-variant mb-2">Pilih Santri (Tanpa Kamar)</label>
                    <select name="santri_id" required class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="">-- Pilih Santri --</option>
                        @foreach($unassignedSantris as $santri)
                            <option value="{{ $santri->id }}">{{ $santri->nama }} ({{ $santri->nis }})</option>
                        @endforeach
                    </select>
                </div>
                
                @if($kamar->penghuni->count() >= $kamar->kapasitas)
                    <button type="button" disabled class="w-full py-3 bg-surface-variant text-on-surface-variant font-bold rounded-xl cursor-not-allowed">Kamar Penuh</button>
                @else
                    <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 18px;">add</span> Masukkan ke Kamar
                    </button>
                @endif
            </form>
        </div>
    </div>

    {{-- Daftar Penghuni --}}
    <div class="lg:col-span-2">
        <div class="bg-surface rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
            <div class="px-6 py-4 border-b border-outline-variant/30 bg-surface-container-lowest flex justify-between items-center">
                <h3 class="text-sm font-bold text-on-surface uppercase tracking-widest">Daftar Penghuni Saat Ini</h3>
            </div>
            
            <div class="divide-y divide-outline-variant/20">
                @forelse($kamar->penghuni as $penghuni)
                <div class="p-4 flex items-center justify-between hover:bg-surface-container/50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold">
                            {{ substr($penghuni->santri->nama, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-on-surface">{{ $penghuni->santri->nama }}</p>
                            <p class="text-xs text-on-surface-variant">NIS: {{ $penghuni->santri->nis }} • Masuk: {{ \Carbon\Carbon::parse($penghuni->tanggal_masuk)->format('d M Y') }}</p>
                        </div>
                    </div>
                    <form action="{{ route('kamar.santri.remove', [$kamar->id, $penghuni->id]) }}" method="POST" onsubmit="return confirm('Keluarkan santri dari kamar ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-error hover:bg-error/10 transition-colors" title="Keluarkan">
                            <span class="material-symbols-outlined" style="font-size: 18px;">logout</span>
                        </button>
                    </form>
                </div>
                @empty
                <div class="p-8 text-center">
                    <p class="text-sm text-on-surface-variant">Belum ada penghuni di kamar ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
