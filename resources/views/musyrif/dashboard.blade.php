@extends('layouts.musyrif')
@section('title', 'Dasbor Musyrif')

@section('content')
<div class="mb-5 fade-in-up">
    <h1 class="text-xl font-black text-on-surface tracking-tight flex items-center gap-2">
        Ahlan wa Sahlan, Musyrif!
    </h1>
    <p class="text-sm text-on-surface-variant mt-1">Pantau perkembangan tahfizh santri di halaqoh Anda hari ini.</p>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-5 mb-6 fade-in-up fade-up-1">
    {{-- Card 1 --}}
    <div class="bg-primary-container rounded-2xl p-4 shadow-sm border border-primary/20 relative overflow-hidden flex flex-col justify-between h-28">
        <div class="flex justify-between items-start relative z-10">
            <h3 class="text-xs font-bold text-on-primary-container/70 uppercase tracking-widest">Total Santri</h3>
            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">group</span>
        </div>
        <div class="relative z-10">
            <p class="text-2xl font-black text-on-primary-container">{{ $totalSantri }}</p>
            <p class="text-[9px] text-on-primary-container/80 mt-1 font-bold">Binaan Anda</p>
        </div>
        <div class="absolute -right-4 -bottom-4 opacity-10">
            <span class="material-symbols-outlined text-[100px]" style="font-variation-settings: 'FILL' 1;">group</span>
        </div>
    </div>
    
    {{-- Card 2 --}}
    <div class="bg-secondary-container rounded-2xl p-4 shadow-sm border border-secondary/20 relative overflow-hidden flex flex-col justify-between h-28">
        <div class="flex justify-between items-start relative z-10">
            <h3 class="text-xs font-bold text-on-secondary-container/70 uppercase tracking-widest">Rata-rata Juz</h3>
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">auto_graph</span>
        </div>
        <div class="relative z-10">
            <p class="text-2xl font-black text-on-secondary-container">{{ $rataJuz }}</p>
            <p class="text-[9px] text-on-secondary-container/80 mt-1 font-bold">Juz Selesai</p>
        </div>
        <div class="absolute -right-4 -bottom-4 opacity-10">
            <span class="material-symbols-outlined text-[100px]" style="font-variation-settings: 'FILL' 1;">auto_graph</span>
        </div>
    </div>
    
    {{-- Card 3 --}}
    <div class="bg-yellow-100 rounded-2xl p-4 shadow-sm border border-yellow-200 relative overflow-hidden flex flex-col justify-between h-28">
        <div class="flex justify-between items-start relative z-10">
            <h3 class="text-xs font-bold text-yellow-800/70 uppercase tracking-widest">Setoran (Hri ini)</h3>
            <span class="material-symbols-outlined text-yellow-600" style="font-variation-settings: 'FILL' 1;">record_voice_over</span>
        </div>
        <div class="relative z-10">
            <p class="text-2xl font-black text-yellow-800">{{ $setoranHariIni }}</p>
            <p class="text-[9px] text-yellow-800/80 mt-1 font-bold">Kali setoran</p>
        </div>
        <div class="absolute -right-4 -bottom-4 opacity-10">
            <span class="material-symbols-outlined text-[100px] text-yellow-600" style="font-variation-settings: 'FILL' 1;">record_voice_over</span>
        </div>
    </div>
    
    {{-- Card 4 --}}
    <div class="bg-green-100 rounded-2xl p-4 shadow-sm border border-green-200 relative overflow-hidden flex flex-col justify-between h-28">
        <div class="flex justify-between items-start relative z-10">
            <h3 class="text-xs font-bold text-green-800/70 uppercase tracking-widest">Selesai 30 Juz</h3>
            <span class="material-symbols-outlined text-green-600" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
        </div>
        <div class="relative z-10">
            <p class="text-2xl font-black text-green-800">{{ $totalHafiz }}</p>
            <p class="text-[9px] text-green-800/80 mt-1 font-bold">Santri Hafizh</p>
        </div>
        <div class="absolute -right-4 -bottom-4 opacity-10">
            <span class="material-symbols-outlined text-[100px] text-green-600" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
        </div>
    </div>
</div>

{{-- Grafik Keaktifan Halaqoh --}}
<div class="bg-surface rounded-2xl p-5 shadow-sm border border-outline-variant/30 mb-5 fade-in-up fade-up-1">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-base font-bold text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1;">monitoring</span>
            Grafik Keaktifan Hafalan (7 Hari Terakhir)
        </h2>
    </div>
    <div class="relative h-64 w-full">
        <canvas id="keaktifanChart"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 fade-in-up fade-up-2">
    
    {{-- Riwayat Setoran Terbaru --}}
    <div class="bg-surface rounded-2xl p-5 shadow-sm border border-outline-variant/30 flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1;">history</span>
                Setoran Terbaru (Halaqoh Anda)
            </h2>
            <a href="{{ route('musyrif.tahfizh') }}" class="text-xs font-bold text-primary hover:underline">Lihat Semua</a>
        </div>
        
        <div class="flex-1">
            @if($recentSetoran->isEmpty())
                <div class="h-full flex flex-col items-center justify-center text-center py-8">
                    <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center mb-3">
                        <span class="material-symbols-outlined text-outline-variant text-3xl">inbox</span>
                    </div>
                    <p class="text-sm font-bold text-on-surface-variant">Belum ada aktivitas</p>
                    <p class="text-xs text-outline mt-1">Setoran hafalan terbaru akan muncul di sini.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($recentSetoran as $log)
                    <div class="flex items-start gap-4 p-3 rounded-2xl hover:bg-surface-container-low transition-colors group">
                        <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center shrink-0">
                            <span class="text-primary font-bold text-xs">{{ substr($log->santri->nama, 0, 2) }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-on-surface truncate group-hover:text-primary transition-colors">
                                {{ $log->santri->nama }}
                            </p>
                            @php
                                $jenisLabel = ['hafalan_baru' => 'Ziyadah', 'murajaah' => 'Muraja\'ah', 'tasmi' => 'Tasmi\''][$log->jenis] ?? 'Setoran';
                                $jenisColor = ['hafalan_baru' => 'text-primary', 'murajaah' => 'text-secondary', 'tasmi' => 'text-amber-500'][$log->jenis] ?? 'text-outline-variant';
                            @endphp
                            <p class="text-xs text-on-surface-variant truncate">
                                <span class="{{ $jenisColor }} font-bold">{{ $jenisLabel }}</span> &bull; Juz {{ $log->juz }} &bull; QS. {{ $log->surah }} ({{ $log->ayat_dari }}-{{ $log->ayat_sampai }})
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            @php
                                $nilaiColor = [
                                    'Mumtaz' => 'text-green-600 bg-green-100',
                                    'Jayyid Jiddan' => 'text-blue-600 bg-blue-100',
                                    'Jayyid' => 'text-yellow-600 bg-yellow-100',
                                    'Maqbul' => 'text-orange-600 bg-orange-100',
                                    'Rosib' => 'text-error bg-error-container',
                                ][$log->nilai] ?? 'text-outline bg-surface-container';
                            @endphp
                            <span class="inline-block px-2 py-0.5 {{ $nilaiColor }} text-[10px] font-bold rounded-md mb-1">
                                {{ $log->nilai }}
                            </span>
                            <p class="text-[9px] text-outline">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Info Halaqoh --}}
    <div class="bg-surface rounded-2xl p-5 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-xl" style="font-variation-settings:'FILL' 1;">groups</span>
                    Info Halaqoh Anda
                </h2>
                @if($halaqohs->isNotEmpty())
                <button onclick="document.getElementById('modal-absen-halaqoh').classList.remove('hidden')" class="text-xs font-bold text-primary bg-primary/10 hover:bg-primary/20 px-3 py-1.5 rounded-lg flex items-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[14px]">checklist</span> Absen Halaqoh
                </button>
                @endif
            </div>
        
        @if($halaqohs->isEmpty())
            <div class="text-center py-8">
                <span class="material-symbols-outlined text-outline-variant text-4xl mb-2">person_off</span>
                <p class="text-sm font-bold text-on-surface-variant">Anda belum ditugaskan ke halaqoh manapun.</p>
                <p class="text-xs text-outline mt-1">Silakan hubungi Administrator untuk penempatan.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($halaqohs as $hq)
                <div class="border border-outline-variant/30 rounded-2xl p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-black text-primary">{{ $hq->nama }}</p>
                        <p class="text-xs text-on-surface-variant mt-1">{{ $hq->santri->count() }} Santri Aktif</p>
                    </div>
                    <a href="{{ route('musyrif.tahfizh') }}" class="w-10 h-10 rounded-full bg-surface-container-high hover:bg-primary/20 flex items-center justify-center text-primary transition-colors">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                </div>
                @endforeach
            </div>
            
            <div class="mt-6 p-4 rounded-xl bg-secondary/10 border border-secondary/20 flex items-start gap-3">
                <span class="material-symbols-outlined text-secondary">info</span>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    Setiap hafalan baru (ziyadah) atau muraja'ah dapat dicatat melalui menu <strong>Tahfizh</strong>. Pastikan absen halaqoh dan catatan setoran dilaporkan harian.
                </p>
            </div>
        @endif
        </div>
    </div>

    {{-- Info Santri Sakit --}}
    @if($santriSakitHariIni->isNotEmpty())
    <div class="bg-error/10 rounded-3xl p-6 shadow-sm border border-error/20 col-span-1 lg:col-span-2 mt-2">
        <h2 class="text-lg font-bold text-error flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">sick</span>
            Santri Sakit Hari Ini (Halaqoh Anda)
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($santriSakitHariIni as $sakit)
            <div class="bg-white/60 backdrop-blur-sm border border-error/20 rounded-2xl p-4 flex gap-4 items-start">
                <div class="w-10 h-10 rounded-full bg-error text-white flex items-center justify-center shrink-0 font-bold">
                    {{ substr($sakit->santri->nama, 0, 1) }}
                </div>
                <div>
                    <p class="font-bold text-on-surface">{{ $sakit->santri->nama }}</p>
                    <p class="text-xs text-on-surface-variant mt-1"><span class="font-bold">Keluhan:</span> {{ $sakit->keluhan }}</p>
                    @if($sakit->diagnosa)
                    <p class="text-xs text-on-surface-variant"><span class="font-bold">Diagnosa:</span> {{ $sakit->diagnosa }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

{{-- Floating Action Buttons --}}
<div class="fixed bottom-20 lg:bottom-8 right-8 z-40 flex flex-col gap-4">
    <button onclick="document.getElementById('modal-lapor-sakit').classList.remove('hidden')" title="Lapor Santri Sakit"
        class="w-16 h-16 bg-error text-white rounded-full shadow-2xl flex items-center justify-center group active:scale-95 transition-all hover:opacity-90 border-4 border-white">
        <span class="material-symbols-outlined text-3xl group-hover:scale-110 transition-transform duration-300">local_hospital</span>
    </button>
    <button onclick="document.getElementById('modal-setoran-dashboard').classList.remove('hidden')" title="Input Setoran"
        class="w-16 h-16 bg-primary text-white rounded-full shadow-2xl flex items-center justify-center group active:scale-95 transition-all hover:opacity-90 border-4 border-white">
        <span class="material-symbols-outlined text-3xl group-hover:rotate-90 transition-transform duration-300">post_add</span>
    </button>
</div>

{{-- MODAL INPUT SETORAN --}}
<div id="modal-setoran-dashboard" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col relative z-10 overflow-hidden border border-white/20">
        <div class="bg-primary/10 px-8 py-5 border-b border-primary/20 flex justify-between items-center shrink-0">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">add_circle</span> Input Setoran Cepat
            </h3>
            <button onclick="document.getElementById('modal-setoran-dashboard').classList.add('hidden')" class="text-primary hover:bg-primary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <form method="POST" action="{{ route('musyrif.tahfizh.store') }}" class="flex flex-col overflow-hidden min-h-0 h-full">
            @csrf
            
            <div class="p-8 space-y-5 overflow-y-auto custom-scrollbar flex-1">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Pilih Santri</label>
                    <select name="santri_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="">-- Pilih Santri --</option>
                        @foreach($santris as $santri)
                        <option value="{{ $santri->id }}">{{ $santri->nama }} (Juz {{ $santri->hafalan->juz_selesai ?? 0 }})</option>
                        @endforeach
                    </select>
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
            </div>

            <div class="flex gap-3 p-6 bg-surface-container-lowest border-t border-outline-variant/20 shrink-0">
                <button type="button" onclick="document.getElementById('modal-setoran-dashboard').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Setoran</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL LAPOR SAKIT --}}
<div id="modal-lapor-sakit" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col relative z-10 overflow-hidden border border-white/20">
        <div class="bg-error/10 px-8 py-5 border-b border-error/20 flex justify-between items-center shrink-0">
            <h3 class="text-lg font-bold text-error flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">local_hospital</span> Lapor Santri Sakit
            </h3>
            <button onclick="document.getElementById('modal-lapor-sakit').classList.add('hidden')" class="text-error hover:bg-error/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <form method="POST" action="{{ route('musyrif.sakit.store') }}" class="flex flex-col overflow-hidden min-h-0 h-full">
            @csrf
            
            <div class="p-8 space-y-5 overflow-y-auto custom-scrollbar flex-1">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Pilih Santri</label>
                    <select name="santri_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-error outline-none">
                        <option value="">-- Pilih Santri Binaan Anda --</option>
                        @foreach($santris as $santri)
                        <option value="{{ $santri->id }}">{{ $santri->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Keluhan / Sakit</label>
                    <textarea name="keluhan" rows="3" required class="w-full p-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-error outline-none resize-none" placeholder="Misal: Demam panas, batuk pilek, dsb..."></textarea>
                    <p class="text-[10px] text-on-surface-variant mt-1">Data akan otomatis diteruskan ke Admin / UKS.</p>
                </div>
            </div>

            <div class="flex gap-3 p-6 bg-surface-container-lowest border-t border-outline-variant/20 shrink-0">
                <button type="button" onclick="document.getElementById('modal-lapor-sakit').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-error text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Laporkan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL ABSEN HALAQOH --}}
<div id="modal-absen-halaqoh" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col relative z-10 overflow-hidden border border-white/20">
        <div class="bg-primary/10 px-8 py-5 border-b border-primary/20 flex justify-between items-center shrink-0">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">checklist</span> Absensi Halaqoh Hari Ini
            </h3>
            <button onclick="document.getElementById('modal-absen-halaqoh').classList.add('hidden')" class="text-primary hover:bg-primary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <form method="POST" action="{{ route('musyrif.kehadiran.store') }}" class="flex flex-col overflow-hidden min-h-0 h-full">
            @csrf
            
            <div class="p-0 overflow-y-auto custom-scrollbar flex-1">
                @if($santris->isEmpty())
                    <div class="p-8 text-center">
                        <span class="material-symbols-outlined text-outline-variant text-5xl mb-2">group_off</span>
                        <p class="text-on-surface-variant font-bold">Tidak ada santri di halaqoh Anda.</p>
                    </div>
                @else
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-surface-container-low border-b border-outline-variant/30 sticky top-0 z-10">
                            <tr>
                                <th class="py-3 px-6 text-[10px] font-black text-on-surface-variant uppercase tracking-widest w-1/2">Nama Santri</th>
                                <th class="py-3 px-6 text-[10px] font-black text-on-surface-variant uppercase tracking-widest text-center">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/20">
                            @foreach($santris as $index => $santri)
                            @php
                                // Ambil status jika sudah pernah absen hari ini
                                $status = isset($kehadiranHariIni[$santri->id]) ? $kehadiranHariIni[$santri->id]->status : 'hadir';
                            @endphp
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="py-4 px-6">
                                    <p class="text-sm font-bold text-on-surface">{{ $santri->nama }}</p>
                                    <p class="text-xs text-on-surface-variant">{{ $santri->nis }}</p>
                                    <input type="hidden" name="kehadiran[{{ $index }}][santri_id]" value="{{ $santri->id }}">
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <label class="cursor-pointer group">
                                            <input type="radio" name="kehadiran[{{ $index }}][status]" value="hadir" class="peer sr-only" {{ $status == 'hadir' ? 'checked' : '' }}>
                                            <div class="px-2 py-1.5 rounded-lg border border-outline-variant text-xs font-bold text-on-surface-variant peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary transition-all">H</div>
                                        </label>
                                        <label class="cursor-pointer group">
                                            <input type="radio" name="kehadiran[{{ $index }}][status]" value="sakit" class="peer sr-only" {{ $status == 'sakit' ? 'checked' : '' }}>
                                            <div class="px-2 py-1.5 rounded-lg border border-outline-variant text-xs font-bold text-on-surface-variant peer-checked:bg-error peer-checked:text-white peer-checked:border-error transition-all">S</div>
                                        </label>
                                        <label class="cursor-pointer group">
                                            <input type="radio" name="kehadiran[{{ $index }}][status]" value="izin" class="peer sr-only" {{ $status == 'izin' ? 'checked' : '' }}>
                                            <div class="px-2 py-1.5 rounded-lg border border-outline-variant text-xs font-bold text-on-surface-variant peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 transition-all">I</div>
                                        </label>
                                        <label class="cursor-pointer group">
                                            <input type="radio" name="kehadiran[{{ $index }}][status]" value="alpha" class="peer sr-only" {{ $status == 'alpha' ? 'checked' : '' }}>
                                            <div class="px-2 py-1.5 rounded-lg border border-outline-variant text-xs font-bold text-on-surface-variant peer-checked:bg-secondary peer-checked:text-white peer-checked:border-secondary transition-all">A</div>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="flex gap-3 p-6 bg-surface-container-lowest border-t border-outline-variant/20 shrink-0">
                <button type="button" onclick="document.getElementById('modal-absen-halaqoh').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Absensi</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('keaktifanChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Total Setoran',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#2563eb', // primary color
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 13, family: 'Inter' },
                            bodyFont: { size: 14, family: 'Inter', weight: 'bold' },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: 'Inter' } },
                            grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter' } }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        }
    });
</script>
@endpush
