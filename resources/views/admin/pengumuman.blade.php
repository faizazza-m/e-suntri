@extends('layouts.app')

@section('title', 'Pusat Pengumuman')
@section('meta_description', 'Broadcast pengumuman ke admin, musyrif, wali santri, dan santri.')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-cyan-500 rounded-2xl shadow-lg">
            <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">campaign</span>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-cyan-500">Pusat Pengumuman</h2>
            <p class="text-sm text-on-surface-variant">Broadcast informasi mading digital ke berbagai pihak terkait</p>
        </div>
    </div>
    <div class="flex gap-2">
        <button onclick="document.getElementById('modal-tambah-pengumuman').classList.remove('hidden')" class="px-4 py-2 rounded-lg bg-cyan-500 text-white text-sm font-bold flex items-center gap-2 hover:opacity-90 shadow-md transition-opacity">
            <span class="material-symbols-outlined text-sm">post_add</span> Tulis Pengumuman
        </button>
    </div>
</div>

{{-- Summary Cards --}}
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6 fade-in-up delay-1">
    @php
        $cards = [
            ['icon'=>'speaker_notes', 'label'=>'Total',          'value'=>$stats['total'],   'border'=>'border-cyan-500',          'iconBg'=>'bg-cyan-500/10 text-cyan-500',             'badge'=>'All Time'],
            ['icon'=>'escalator_warning','label'=>'Khusus Wali', 'value'=>$stats['wali'],    'border'=>'border-primary-container', 'iconBg'=>'bg-primary-container/10 text-primary-container', 'badge'=>'Target'],
            ['icon'=>'school',        'label'=>'Musyrif',        'value'=>$stats['musyrif'], 'border'=>'border-secondary',         'iconBg'=>'bg-secondary/10 text-secondary',           'badge'=>'Target'],
            ['icon'=>'history_edu',   'label'=>'Guru',           'value'=>$stats['guru'],    'border'=>'border-emerald-500',       'iconBg'=>'bg-emerald-500/10 text-emerald-500',       'badge'=>'Target'],
            ['icon'=>'face',          'label'=>'Santri',         'value'=>$stats['santri'],  'border'=>'border-amber-500',         'iconBg'=>'bg-amber-500/10 text-amber-500',           'badge'=>'Target'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="glassmorphism p-6 rounded-2xl shadow-sm {{ $card['border'] }} border-l-4">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-xl {{ $card['iconBg'] }} flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">{{ $card['icon'] }}</span>
            </div>
            <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-surface-container-highest text-on-surface-variant">{{ $card['badge'] }}</span>
        </div>
        <p class="text-sm text-on-surface-variant font-medium">{{ $card['label'] }}</p>
        <h3 class="text-2xl font-black text-on-surface mt-1">{{ $card['value'] }}</h3>
    </div>
    @endforeach
</section>

{{-- Main Content (Timeline Layout) --}}
<div class="mt-8 fade-in-up delay-2 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-on-surface">Timeline Pengumuman</h3>
        <p class="text-sm text-on-surface-variant">Menampilkan dari yang terbaru</p>
    </div>

    @if($pengumumans->isEmpty())
        <div class="glassmorphism p-10 rounded-2xl text-center border border-dashed border-outline-variant/40">
            <span class="material-symbols-outlined text-6xl text-outline-variant/50 mb-2">inbox</span>
            <p class="text-on-surface-variant font-bold">Belum ada pengumuman yang diterbitkan.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($pengumumans as $p)
            @php
                $targetBadge = match($p->target) {
                    'semua' => 'bg-cyan-500/10 text-cyan-500 border-cyan-500/20',
                    'wali' => 'bg-primary-container/10 text-primary-container border-primary-container/20',
                    'musyrif' => 'bg-secondary/10 text-secondary border-secondary/20',
                    'santri' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                };

                $targetIcon = match($p->target) {
                    'semua' => 'public',
                    'wali' => 'escalator_warning',
                    'musyrif' => 'school',
                    'santri' => 'face',
                };
            @endphp
            
            <div class="glassmorphism p-6 rounded-2xl shadow-sm relative group overflow-hidden {{ $p->is_pinned ? 'border-l-4 border-l-cyan-500' : 'border border-outline-variant/20' }}">
                
                @if($p->is_pinned)
                <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden pointer-events-none z-0">
                    <div class="bg-cyan-500 text-white text-[10px] font-bold py-1 w-24 text-center absolute top-3 -right-6 transform rotate-45 shadow-sm">
                        PINNED
                    </div>
                </div>
                @endif

                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4 flex-1">
                        {{-- Avatar / Target Icon --}}
                        <div class="hidden sm:flex w-12 h-12 rounded-full {{ $targetBadge }} items-center justify-center border shrink-0">
                            <span class="material-symbols-outlined text-2xl" style="font-variation-settings:'FILL' 1;">{{ $targetIcon }}</span>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h4 class="text-xl font-bold text-on-surface">{{ $p->judul }}</h4>
                                <span class="px-2 py-0.5 rounded-full border text-[10px] font-bold uppercase flex items-center gap-1 {{ $targetBadge }}">
                                    <span class="material-symbols-outlined text-[12px]">{{ $targetIcon }}</span>
                                    Target: {{ $p->target }}
                                </span>
                            </div>
                            
                            <div class="text-xs text-on-surface-variant flex items-center gap-2 mb-4">
                                <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                {{ \Carbon\Carbon::parse($p->published_at)->translatedFormat('l, d F Y - H:i') }}
                                <span class="mx-1">•</span>
                                <span class="material-symbols-outlined text-[14px]">edit_note</span>
                                Oleh: {{ $p->pembuat->name ?? 'Admin' }}
                            </div>
                            
                            <div class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-wrap bg-surface-container-low/30 p-4 rounded-xl border border-outline-variant/10">{!! nl2br(e($p->isi)) !!}</div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="shrink-0 flex flex-col items-center opacity-0 group-hover:opacity-100 transition-opacity relative z-10 mt-2">
                        <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-full bg-error/10 text-error flex items-center justify-center hover:bg-error hover:text-white transition-colors shadow-sm" title="Hapus Pengumuman">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
            
            <div class="pt-4">
                {{ $pengumumans->links('pagination::tailwind') }}
            </div>
        </div>
    @endif
</div>

{{-- MODAL TAMBAH PENGUMUMAN --}}
<div id="modal-tambah-pengumuman" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-2xl relative z-10 overflow-hidden border border-white/20">
        <div class="bg-cyan-500/10 px-8 py-5 border-b border-cyan-500/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-cyan-500 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">post_add</span> Tulis Pengumuman Baru
            </h3>
            <button type="button" onclick="document.getElementById('modal-tambah-pengumuman').classList.add('hidden')" class="text-cyan-500 hover:bg-cyan-500/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('pengumuman.store') }}" class="p-8 space-y-5 max-h-[80vh] overflow-y-auto">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Judul Pengumuman</label>
                <input type="text" name="judul" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-lg font-bold focus:ring-2 focus:ring-cyan-500 outline-none" placeholder="Misal: Libur Idul Adha 1445 H">
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Target Audience</label>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mt-2">
                    <label class="cursor-pointer relative">
                        <input type="radio" name="target" value="semua" class="peer sr-only" checked>
                        <div class="w-full text-center px-2 py-3 bg-surface-container border border-outline-variant rounded-xl peer-checked:bg-cyan-500/10 peer-checked:border-cyan-500 peer-checked:text-cyan-600 text-sm font-bold transition-all">
                            <span class="material-symbols-outlined block mb-1">public</span> Semua
                        </div>
                    </label>
                    <label class="cursor-pointer relative">
                        <input type="radio" name="target" value="wali" class="peer sr-only">
                        <div class="w-full text-center px-2 py-3 bg-surface-container border border-outline-variant rounded-xl peer-checked:bg-primary/10 peer-checked:border-primary peer-checked:text-primary text-sm font-bold transition-all">
                            <span class="material-symbols-outlined block mb-1">escalator_warning</span> Wali Santri
                        </div>
                    </label>
                    <label class="cursor-pointer relative">
                        <input type="radio" name="target" value="musyrif" class="peer sr-only">
                        <div class="w-full text-center px-2 py-3 bg-surface-container border border-outline-variant rounded-xl peer-checked:bg-secondary/10 peer-checked:border-secondary peer-checked:text-secondary text-sm font-bold transition-all">
                            <span class="material-symbols-outlined block mb-1">school</span> Musyrif
                        </div>
                    </label>
                    <label class="cursor-pointer relative">
                        <input type="radio" name="target" value="guru" class="peer sr-only">
                        <div class="w-full text-center px-2 py-3 bg-surface-container border border-outline-variant rounded-xl peer-checked:bg-emerald-500/10 peer-checked:border-emerald-500 peer-checked:text-emerald-600 text-sm font-bold transition-all">
                            <span class="material-symbols-outlined block mb-1">history_edu</span> Guru
                        </div>
                    </label>
                    <label class="cursor-pointer relative">
                        <input type="radio" name="target" value="santri" class="peer sr-only">
                        <div class="w-full text-center px-2 py-3 bg-surface-container border border-outline-variant rounded-xl peer-checked:bg-amber-500/10 peer-checked:border-amber-500 peer-checked:text-amber-600 text-sm font-bold transition-all">
                            <span class="material-symbols-outlined block mb-1">face</span> Santri
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Isi Pengumuman</label>
                <textarea name="isi" required rows="6" class="w-full p-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-cyan-500 outline-none resize-y" placeholder="Tuliskan isi pengumuman secara lengkap di sini..."></textarea>
            </div>

            <div class="p-4 bg-cyan-500/5 border border-cyan-500/20 rounded-xl space-y-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_pinned" class="w-5 h-5 text-cyan-500 rounded border-outline-variant focus:ring-cyan-500">
                    <span class="text-sm font-bold text-cyan-600">Sematkan di Atas (Pin to Top)</span>
                </label>
                <p class="text-[10px] text-on-surface-variant ml-7">Jika dicentang, pengumuman ini akan selalu berada di urutan paling atas terlepas dari tanggal pembuatannya.</p>
            </div>

            <div class="flex gap-3 pt-4 border-t border-outline-variant/20">
                <button type="submit" class="w-full py-3 bg-cyan-500 text-white font-bold rounded-xl hover:opacity-90 shadow-md transition-opacity flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">send</span> Terbitkan Pengumuman
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
