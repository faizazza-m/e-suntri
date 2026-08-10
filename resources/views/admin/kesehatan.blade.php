@extends('layouts.app')

@section('title', 'Pusat Kesehatan (UKS)')
@section('meta_description', 'Manajemen rekam medis dan kesehatan santri SUNTRI.')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-rose-500 rounded-2xl shadow-lg">
            <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">medical_services</span>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-rose-500">Pusat Kesehatan (UKS)</h2>
            <p class="text-sm text-on-surface-variant">Catat riwayat keluhan, diagnosa, dan tindakan medis santri</p>
        </div>
    </div>
    <div class="flex gap-2">
        <button onclick="document.getElementById('modal-tambah-rekam').classList.remove('hidden')" class="px-4 py-2 rounded-lg bg-rose-500 text-white text-sm font-bold flex items-center gap-2 hover:opacity-90 shadow-md transition-opacity">
            <span class="material-symbols-outlined text-sm">add_box</span> Catat Pasien Baru
        </button>
    </div>
</div>

{{-- Summary Cards --}}
<section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 fade-in-up delay-1">
    @php
        $cards = [
            ['icon'=>'group',           'label'=>'Pasien Hari Ini','value'=>$stats['hari_ini'],      'border'=>'border-rose-500',          'iconBg'=>'bg-rose-500/10 text-rose-500',             'badge'=>'Hari ini', 'badgeBg'=>'bg-rose-500/5 text-rose-500'],
            ['icon'=>'local_hospital',  'label'=>'Total Rujukan',  'value'=>$stats['total_rujukan'], 'border'=>'border-error',             'iconBg'=>'bg-error/10 text-error',                   'badge'=>'RS/Klinik','badgeBg'=>'bg-error/5 text-error'],
            ['icon'=>'folder_shared',   'label'=>'Total Rekam Medis','value'=>$stats['total_rekam'], 'border'=>'border-primary-container', 'iconBg'=>'bg-primary-container/10 text-primary-container','badge'=>'All Time','badgeBg'=>'bg-primary-container/5 text-primary-container'],
            ['icon'=>'coronavirus',     'label'=>'Kasus Terbanyak', 'value'=>Str::limit($stats['top_diagnosa'], 15), 'border'=>'border-secondary-container','iconBg'=>'bg-secondary-container/10 text-on-secondary-container','badge'=>'Trending', 'badgeBg'=>'bg-secondary-container/5 text-on-secondary-container'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="glassmorphism p-6 rounded-2xl shadow-sm {{ $card['border'] }} border-l-4">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-xl {{ $card['iconBg'] }} flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">{{ $card['icon'] }}</span>
            </div>
            <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $card['badgeBg'] }}">{{ $card['badge'] }}</span>
        </div>
        <p class="text-sm text-on-surface-variant font-medium">{{ $card['label'] }}</p>
        <h3 class="text-2xl font-black text-on-surface mt-1 truncate" title="{{ $card['value'] }}">{{ $card['value'] }}</h3>
    </div>
    @endforeach
</section>

{{-- Main Content --}}
<div class="grid grid-cols-1 gap-8 items-start fade-in-up delay-2 mt-6">

    <section class="space-y-5">
        {{-- Filter Bar --}}
        <div class="flex flex-wrap items-center justify-between gap-4 glassmorphism p-4 rounded-xl">
            <div class="flex items-center gap-4 flex-1 min-w-[260px]">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">search</span>
                    <input type="text" placeholder="Cari nama santri atau diagnosa..." class="w-full bg-surface border border-outline-variant/40 rounded-lg pl-9 pr-4 py-2 text-sm focus:ring-2 focus:ring-rose-500/20 outline-none">
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="glassmorphism rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-surface-container-low/60">
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Santri</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Keluhan / Gejala</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Diagnosa</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tindakan & Obat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($rekamMedis as $rm)
                        <tr class="hover:bg-rose-500/5 transition-colors group">
                            <td class="px-6 py-4 text-sm font-medium text-on-surface">
                                {{ \Carbon\Carbon::parse($rm->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center font-bold text-sm text-on-surface-variant">
                                        {{ substr($rm->santri->nama ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">{{ $rm->santri->nama ?? 'Tidak Diketahui' }}</p>
                                        <p class="text-[11px] text-on-surface-variant">{{ $rm->santri->kelas->nama ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-on-surface-variant max-w-[200px] truncate" title="{{ $rm->keluhan }}">{{ $rm->keluhan }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-on-surface">{{ $rm->diagnosa ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-on-surface-variant max-w-[200px] truncate" title="{{ $rm->tindakan }}">
                                @if($rm->dirujuk)
                                <span class="px-2 py-0.5 bg-error/10 text-error text-[10px] font-bold rounded mr-1">DIRUJUK</span> 
                                {{ $rm->tempat_rujukan }}
                                @else
                                {{ $rm->tindakan ?? '-' }}
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-on-surface-variant">Belum ada riwayat rekam medis.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-surface-container-low/30 border-t border-outline-variant/10">
                {{ $rekamMedis->links('pagination::tailwind') }}
            </div>
        </div>
    </section>
</div>

{{-- MODAL TAMBAH REKAM MEDIS --}}
<div id="modal-tambah-rekam" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-rose-500/10 px-8 py-5 border-b border-rose-500/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-rose-500 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">medical_information</span> Catat Kunjungan UKS
            </h3>
            <button type="button" onclick="document.getElementById('modal-tambah-rekam').classList.add('hidden')" class="text-rose-500 hover:bg-rose-500/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('kesehatan.store') }}" class="p-8 space-y-4 max-h-[75vh] overflow-y-auto">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Pasien (Santri)</label>
                <select name="santri_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                    <option value="">Pilih Santri...</option>
                    @foreach($santris as $s)
                    <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->kelas->nama ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Keluhan / Gejala</label>
                <textarea name="keluhan" required rows="2" class="w-full p-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none resize-none" placeholder="Misal: Pusing, demam sejak semalam..."></textarea>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Diagnosa Awal</label>
                <input type="text" name="diagnosa" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none" placeholder="Misal: Gejala Tipes, Maag">
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Tindakan & Obat</label>
                <input type="text" name="tindakan" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none" placeholder="Misal: Diberi Paracetamol 500mg, Istirahat">
            </div>

            <div class="p-4 bg-error/5 border border-error/20 rounded-xl space-y-3 mt-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="dirujuk" id="cb_dirujuk" class="w-4 h-4 text-error rounded border-outline-variant focus:ring-error" onchange="toggleRujukan()">
                    <span class="text-sm font-bold text-error">Rujuk ke Fasilitas Kesehatan Luar (RS/Klinik)</span>
                </label>
                
                <div id="input_rujukan" class="hidden">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Tempat Rujukan</label>
                    <input type="text" name="tempat_rujukan" class="w-full h-10 px-3 bg-white border border-outline-variant rounded-lg text-sm outline-none" placeholder="Nama Rumah Sakit atau Klinik...">
                </div>
            </div>

            <div class="p-4 bg-primary/5 border border-primary/20 rounded-xl space-y-3 mt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="buat_izin_sakit" checked class="w-4 h-4 text-primary rounded border-outline-variant focus:ring-primary">
                    <span class="text-sm font-bold text-primary">Buatkan Izin Istirahat / Sakit Otomatis (Modul Perizinan)</span>
                </label>
                <p class="text-[10px] text-on-surface-variant ml-6">Jika dicentang, sistem akan otomatis mencatat status izin sakit hari ini di tabel Perizinan agar terdeteksi oleh Wali Kelas.</p>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="w-full py-3 bg-rose-500 text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Rekam Medis</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleRujukan() {
        const isChecked = document.getElementById('cb_dirujuk').checked;
        document.getElementById('input_rujukan').classList.toggle('hidden', !isChecked);
    }
</script>

@endsection
