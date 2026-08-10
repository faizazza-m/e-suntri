@extends('layouts.musyrif')
@section('title', 'Tahfizh & Hafalan')

@section('content')
<div class="mb-5">
    <h1 class="text-2xl font-black text-on-surface tracking-tight flex items-center gap-2">
        <span class="material-symbols-outlined text-3xl text-primary" style="font-variation-settings: 'FILL' 1;">menu_book</span>
        Tahfizh & Hafalan
    </h1>
    <p class="text-sm text-on-surface-variant mt-1">Kelola data setoran hafalan santri di halaqoh Anda.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Daftar Santri --}}
    <div class="bg-surface rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="px-5 py-4 border-b border-outline-variant/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings:'FILL' 1;">group</span>
            <h2 class="font-bold text-on-surface">Santri Binaan</h2>
            <span class="ml-auto text-xs font-bold text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full">{{ $santris->count() }} santri</span>
        </div>

        <div class="divide-y divide-outline-variant/15">
            @forelse($santris as $santri)
            <div class="flex items-center gap-3 px-4 py-3.5 hover:bg-surface-container-low transition-colors">
                {{-- Avatar --}}
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 font-bold text-sm">
                    {{ mb_substr($santri->nama, 0, 1) }}
                </div>
                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-on-surface text-sm truncate">{{ $santri->nama }}</p>
                    <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $santri->nis }} · {{ $santri->kelas->nama ?? '-' }}</p>
                </div>
                {{-- Juz Badge --}}
                <span class="shrink-0 px-2 py-1 bg-secondary-container text-on-secondary-container text-[11px] font-bold rounded-lg">
                    Juz {{ $santri->hafalan ? $santri->hafalan->juz_selesai : 0 }}
                </span>
                {{-- Aksi --}}
                <button onclick="openSetoranModal({{ $santri->id }}, '{{ addslashes($santri->nama) }}')"
                        class="shrink-0 w-9 h-9 flex items-center justify-center bg-primary text-white rounded-xl shadow-sm hover:opacity-90 transition-opacity active:scale-95">
                    <span class="material-symbols-outlined text-lg">add</span>
                </button>
            </div>
            @empty
            <div class="py-12 text-center">
                <span class="material-symbols-outlined text-4xl text-outline-variant mb-2 block">group_off</span>
                <p class="text-sm text-on-surface-variant">Belum ada santri di halaqoh Anda.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Riwayat Setoran --}}
    <div class="bg-surface rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="px-5 py-4 border-b border-outline-variant/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-xl" style="font-variation-settings:'FILL' 1;">history</span>
            <h2 class="font-bold text-on-surface">Riwayat Setoran</h2>
        </div>

        <div class="divide-y divide-outline-variant/15">
            @forelse($riwayatSetoran as $log)
            @php
                $jenisLabel = ['hafalan_baru' => 'Ziyadah', 'murajaah' => "Muraja'ah", 'tasmi' => "Tasmi'"][$log->jenis] ?? 'Setoran';
                $jenisColor = ['hafalan_baru' => 'bg-primary/10 text-primary', 'murajaah' => 'bg-secondary/10 text-secondary', 'tasmi' => 'bg-amber-100 text-amber-600'][$log->jenis] ?? 'bg-surface-container text-on-surface-variant';
                $nilaiColor = ['Mumtaz' => 'text-green-700 bg-green-100', 'Jayyid Jiddan' => 'text-blue-700 bg-blue-100', 'Jayyid' => 'text-yellow-700 bg-yellow-100', 'Maqbul' => 'text-orange-600 bg-orange-100', 'Rosib' => 'text-red-600 bg-red-100'][$log->nilai] ?? 'text-on-surface-variant bg-surface-container';
            @endphp
            <div class="px-4 py-3.5 hover:bg-surface-container-low transition-colors">
                <div class="flex items-start justify-between gap-2 mb-1.5">
                    <p class="font-bold text-on-surface text-sm">{{ $log->santri->nama }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 {{ $nilaiColor }} text-[10px] font-bold rounded-lg shrink-0">{{ $log->nilai }}</span>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center px-2 py-0.5 {{ $jenisColor }} text-[10px] font-bold rounded-md">{{ $jenisLabel }}</span>
                    <span class="text-[11px] text-on-surface-variant">Juz {{ $log->juz }}, Qs. {{ $log->surah }} ({{ $log->ayat_dari }}–{{ $log->ayat_sampai }})</span>
                </div>
                <p class="text-[10px] text-outline mt-1.5">{{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('D MMM Y') }} · {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</p>
            </div>
            @empty
            <div class="py-12 text-center">
                <span class="material-symbols-outlined text-4xl text-outline-variant mb-2 block">receipt_long</span>
                <p class="text-sm text-on-surface-variant">Belum ada riwayat setoran.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>


{{-- MODAL INPUT SETORAN --}}
<div id="modal-setoran" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-primary/10 px-8 py-5 border-b border-primary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">add_circle</span> Input Setoran Baru
            </h3>
            <button onclick="document.getElementById('modal-setoran').classList.add('hidden')" class="text-primary hover:bg-primary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <form method="POST" action="{{ route('musyrif.tahfizh.store') }}" class="p-8 space-y-5">
            @csrf
            <input type="hidden" name="santri_id" id="input_santri_id">
            
            <div class="p-4 rounded-xl bg-surface-container border border-outline-variant flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-sm">person</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Santri</p>
                    <p class="text-base font-black text-primary" id="display_santri_nama">Nama Santri</p>
                </div>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Jenis Setoran</label>
                <div class="flex gap-4">
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="jenis" value="hafalan_baru" class="peer sr-only" required checked>
                        <div class="py-2.5 px-3 rounded-xl border border-outline-variant text-sm font-bold text-center text-on-surface-variant peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary transition-all">Ziyadah (Baru)</div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="jenis" value="murajaah" class="peer sr-only" required>
                        <div class="py-2.5 px-3 rounded-xl border border-outline-variant text-sm font-bold text-center text-on-surface-variant peer-checked:bg-secondary peer-checked:text-white peer-checked:border-secondary transition-all">Muraja'ah</div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Nama Surah</label>
                    <select name="surah" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="">-- Pilih Surah --</option>
                        @foreach(\App\Helpers\QuranHelper::$surahs as $num => $surahData)
                        <option value="{{ $num }}">{{ $num }}. {{ $surahData['englishName'] }} ({{ $surahData['nama'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Ayat Dari</label>
                    <input type="number" name="ayat_dari" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Ayat Sampai</label>
                    <input type="number" name="ayat_sampai" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Posisi Juz</label>
                    <input type="number" name="juz" min="1" max="30" required placeholder="1-30" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Nilai Kelancaran</label>
                    <select name="nilai" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="Mumtaz">Mumtaz (Sempurna)</option>
                        <option value="Jayyid Jiddan">Jayyid Jiddan (Sangat Baik)</option>
                        <option value="Jayyid">Jayyid (Baik)</option>
                        <option value="Maqbul">Maqbul (Cukup)</option>
                        <option value="Rosib">Rosib (Mengulang)</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Catatan Musyrif (Opsional)</label>
                    <textarea name="catatan" rows="2" class="w-full p-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none resize-none" placeholder="Masukkan catatan tajwid, kelancaran, dsb..."></textarea>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-outline-variant/20">
                <button type="button" onclick="document.getElementById('modal-setoran').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Setoran</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openSetoranModal(santriId, santriNama) {
        document.getElementById('input_santri_id').value = santriId;
        document.getElementById('display_santri_nama').innerText = santriNama;
        document.getElementById('modal-setoran').classList.remove('hidden');
    }
</script>
@endsection
