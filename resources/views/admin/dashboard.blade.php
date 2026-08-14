@extends('layouts.app')

@section('title', 'Dashboard — SUNTRI')
@section('meta_description', 'Pantau seluruh aktivitas santri, hafalan, kehadiran dan keuangan SUNTRI.')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-primary-container rounded-2xl shadow-lg">
            <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">dashboard</span>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-primary">Dashboard Utama</h2>
            <p class="text-sm text-on-surface-variant">Assalamu'alaikum, {{ explode(' ', auth()->user()->name)[0] }}. Mari pantau perkembangan pesantren hari ini.</p>
        </div>
    </div>
    <div class="bg-white/60 backdrop-blur-md px-5 py-2.5 rounded-xl border border-outline-variant/30 text-right shadow-sm">
        <div class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">{{ now()->locale('id')->isoFormat('dddd') }}</div>
        <div class="text-sm font-bold text-primary">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
    </div>
</div>

{{-- Stats Grid --}}
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 fade-in-up delay-1">
    @php
        $stats = [
            ['icon' => 'group',             'label' => 'Total Santri',    'value' => number_format($totalSantri, 0, ',', '.'), 'color' => 'primary',    'link' => '#'],
            ['icon' => 'check_circle',      'label' => 'Hadir Hari Ini',  'value' => $hadirHariIni . '%',                      'color' => 'secondary',  'link' => '#modal-absensi', 'is_modal' => true],
            ['icon' => 'menu_book',         'label' => 'Setoran Hafalan', 'value' => number_format($setoranHariIni, 0, ',', '.'), 'color' => 'yellow-600', 'link' => route('tahfizh')],
            ['icon' => 'exit_to_app',       'label' => 'Santri Izin',     'value' => $santriIzin,                              'color' => 'orange-600', 'link' => route('perizinan')],
            ['icon' => 'medical_services',  'label' => 'Santri Sakit',    'value' => $santriSakit,                             'color' => 'error',      'link' => route('kesehatan')],
            ['icon' => 'payments',          'label' => 'Tagihan Pending', 'value' => $tagihanPending,                          'color' => 'purple-600', 'link' => route('keuangan')],
        ];
        $bgColors = [
            'primary'    => 'bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white',
            'secondary'  => 'bg-secondary/10 text-secondary group-hover:bg-secondary group-hover:text-white',
            'yellow-600' => 'bg-yellow-500/10 text-yellow-700 group-hover:bg-yellow-500 group-hover:text-white',
            'orange-600' => 'bg-orange-500/10 text-orange-700 group-hover:bg-orange-500 group-hover:text-white',
            'error'      => 'bg-error/10 text-error group-hover:bg-error group-hover:text-white',
            'purple-600' => 'bg-purple-500/10 text-purple-700 group-hover:bg-purple-500 group-hover:text-white',
        ];
    @endphp

    @foreach($stats as $stat)
    @if(isset($stat['is_modal']) && $stat['is_modal'])
    <button onclick="document.getElementById('{{ ltrim($stat['link'], '#') }}').classList.remove('hidden')"
    @else
    <a href="{{ $stat['link'] }}"
    @endif
       class="glassmorphism p-5 rounded-xl border border-white/20 shadow-sm flex flex-col items-center text-center group hover:scale-105 transition-all duration-300">
        <div class="w-11 h-11 rounded-full flex items-center justify-center mb-3 transition-colors duration-300 {{ $bgColors[$stat['color']] }}">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">{{ $stat['icon'] }}</span>
        </div>
        <span class="text-[10px] text-on-surface-variant uppercase tracking-widest font-bold mb-1">{{ $stat['label'] }}</span>
        <span class="text-2xl font-bold text-on-surface">{{ $stat['value'] }}</span>
    @if(isset($stat['is_modal']) && $stat['is_modal'])
    </button>
    @else
    </a>
    @endif
    @endforeach
</section>

{{-- Chart & Activity Layout --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 fade-in-up delay-2">

    {{-- Hafalan Chart --}}
    <div class="lg:col-span-8 glassmorphism p-8 rounded-2xl border border-white/20 shadow-sm">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h3 class="text-xl font-bold text-on-surface">Perkembangan Hafalan</h3>
                <p class="text-sm text-on-surface-variant">Statistik setoran Juz dari database</p>
            </div>
            <div class="flex items-center gap-1 bg-surface-container-low p-1 rounded-lg">
                <button id="btnBulanan"
                    class="px-4 py-1.5 bg-white shadow-sm rounded-md text-xs font-bold text-primary transition-all">BULANAN</button>
                <button id="btnPekanan"
                    class="px-4 py-1.5 text-xs font-bold text-on-surface-variant hover:text-primary transition-colors">PEKANAN</button>
            </div>
        </div>

        {{-- Canvas Chart --}}
        <div class="relative h-64 w-full">
            <canvas id="hafalanChart"></canvas>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="lg:col-span-4 flex flex-col gap-6">

        {{-- Activity Feed --}}
        <div class="glassmorphism rounded-3xl p-6 shadow-sm border border-outline-variant/20 relative flex flex-col flex-1">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Aktivitas Terbaru</h3>
                <a href="{{ route('aktivitas') }}" class="text-[10px] font-bold text-primary hover:underline uppercase tracking-wider">Lihat Semua</a>
            </div>
            <div class="space-y-5">
                @forelse($activities as $act)
                <div class="flex gap-3">
                    <div class="w-2 h-2 mt-1.5 rounded-full {{ $act['dot'] }} flex-shrink-0"></div>
                    <div>
                        <p class="text-sm text-on-surface">{!! $act['html'] !!}</p>
                        <p class="text-[10px] text-on-surface-variant font-medium mt-0.5">{{ $act['time'] }} • {{ $act['tag'] }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-on-surface-variant text-center py-4">Belum ada aktivitas hari ini.</p>
                @endforelse
            </div>
        </div>

        {{-- Agenda Widget --}}
        <div class="glassmorphism p-6 rounded-2xl border border-white/20 shadow-sm bg-secondary-container/5">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-secondary">event</span>
                <h3 class="text-xs font-bold text-on-secondary-container uppercase tracking-widest">Agenda Mendatang</h3>
            </div>
            <div class="space-y-3">
                @forelse($agendas as $agenda)
                <div class="p-3 bg-white/50 rounded-xl border border-white/40 flex items-center gap-4 hover:bg-white transition-colors cursor-pointer">
                    <div class="{{ $agenda->is_pinned ? 'bg-error/10' : 'bg-primary/10' }} px-3 py-1 rounded-lg text-center flex-shrink-0">
                        <div class="text-sm font-extrabold {{ $agenda->is_pinned ? 'text-error' : 'text-primary' }} leading-none">{{ \Carbon\Carbon::parse($agenda->published_at)->format('d') }}</div>
                        <div class="text-[8px] font-bold {{ $agenda->is_pinned ? 'text-error' : 'text-primary' }} uppercase">{{ \Carbon\Carbon::parse($agenda->published_at)->locale('id')->isoFormat('MMM') }}</div>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-on-surface truncate" title="{{ $agenda->judul }}">
                            @if($agenda->is_pinned) <span class="material-symbols-outlined text-[12px] text-error align-middle mr-1" style="font-variation-settings:'FILL' 1;">push_pin</span> @endif
                            {{ $agenda->judul }}
                        </p>
                        <p class="text-[10px] text-on-surface-variant line-clamp-1" title="{{ $agenda->isi }}">{{ $agenda->isi }}</p>
                    </div>
                </div>
                @empty
                <p class="text-[11px] text-on-surface-variant text-center py-6">Tidak ada agenda mendatang.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Info Santri Sakit Global --}}
@if($santriSakitGlobal->isNotEmpty())
<div class="bg-error/10 rounded-3xl p-6 shadow-sm border border-error/20 mb-8 fade-in-up delay-3">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold text-error flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">emergency</span>
            Perhatian: Santri Sakit / Dirawat Hari Ini
        </h2>
        <a href="{{ route('kesehatan') }}" class="text-xs font-bold text-error hover:underline flex items-center gap-1">
            Lihat Modul Kesehatan <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($santriSakitGlobal as $sakit)
        <div class="bg-white/60 backdrop-blur-sm border border-error/20 rounded-2xl p-4 flex gap-4 items-start">
            <div class="w-10 h-10 rounded-full bg-error text-white flex items-center justify-center shrink-0 font-bold">
                {{ substr($sakit->santri->nama, 0, 1) }}
            </div>
            <div>
                <p class="font-bold text-on-surface">{{ $sakit->santri->nama }}</p>
                <p class="text-[10px] text-on-surface-variant mb-1">{{ $sakit->santri->kelas->nama ?? 'Tanpa Kelas' }} | Musyrif: {{ $sakit->santri->halaqoh->musyrif->name ?? '-' }}</p>
                <p class="text-xs text-on-surface-variant"><span class="font-bold text-error">Keluhan:</span> {{ $sakit->keluhan }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Quick Actions --}}
<section class="grid grid-cols-1 md:grid-cols-4 gap-4 pb-8 fade-in-up delay-3 relative z-50">
    @php
        $actions = [
            ['icon'=>'person_add',  'label'=>'TAMBAH SANTRI',  'gradient'=>'from-primary to-green-600',           'href'=> '#modal-tambah-santri',   'modal' => true],
            ['icon'=>'badge',       'label'=>'TAMBAH PENGURUS','gradient'=>'from-blue-600 to-indigo-600',         'href'=> '#modal-tambah-pengurus', 'modal' => true],
            ['icon'=>'edit_note',   'label'=>'INPUT SETORAN',  'gradient'=>'from-yellow-600 to-orange-600',       'href'=> route('tahfizh'),         'modal' => false],
            ['icon'=>'description', 'label'=>'CETAK LAPORAN',  'gradient'=>'from-purple-600 to-purple-800',       'href'=> '#modal-cetak-laporan',  'modal' => true],
        ];
    @endphp
    @foreach($actions as $action)
    @if($action['modal'])
    <button onclick="document.getElementById('{{ ltrim($action['href'],'#') }}').classList.remove('hidden')"
        class="flex items-center justify-center gap-3 py-4 rounded-2xl bg-gradient-to-br {{ $action['gradient'] }} text-white font-bold text-sm shadow-lg hover:opacity-90 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 active:scale-[0.98]">
        <span class="material-symbols-outlined">{{ $action['icon'] }}</span>
        {{ $action['label'] }}
    </button>
    @else
    <a href="{{ $action['href'] }}"
        class="flex items-center justify-center gap-3 py-4 rounded-2xl bg-gradient-to-br {{ $action['gradient'] }} text-white font-bold text-sm shadow-lg hover:opacity-90 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 active:scale-[0.98]">
        <span class="material-symbols-outlined">{{ $action['icon'] }}</span>
        {{ $action['label'] }}
    </a>
    @endif
    @endforeach
</section>

{{-- Modal: Detail Kehadiran --}}
<div id="modal-absensi" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="bg-secondary p-6 flex justify-between items-center shrink-0">
            <h3 class="font-bold text-lg text-white flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">fact_check</span>
                Laporan Kehadiran Hari Ini
            </h3>
            <button onclick="document.getElementById('modal-absensi').classList.add('hidden')"
                class="text-white/70 hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 overflow-y-auto space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Hadir --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-secondary flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        Hadir ({{ $hadirList->count() }})
                    </h4>
                    <div class="bg-secondary/5 rounded-xl border border-secondary/20 p-3 max-h-60 overflow-y-auto">
                        @forelse($hadirList as $s)
                        <div class="py-1.5 border-b border-secondary/10 last:border-0">
                            <p class="text-sm font-bold text-on-surface">{{ $s->nama }}</p>
                            <p class="text-[10px] text-on-surface-variant">{{ $s->kelas->nama ?? 'Tanpa Kelas' }}</p>
                        </div>
                        @empty
                        <p class="text-xs text-on-surface-variant text-center py-2">Belum ada data hadir hari ini.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Sakit --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-error flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">medical_services</span>
                        Sakit ({{ $sakitList->count() }})
                    </h4>
                    <div class="bg-error/5 rounded-xl border border-error/20 p-3 max-h-60 overflow-y-auto">
                        @forelse($sakitList as $s)
                        <div class="py-1.5 border-b border-error/10 last:border-0">
                            <p class="text-sm font-bold text-on-surface">{{ $s->nama }}</p>
                            <p class="text-[10px] text-on-surface-variant">{{ $s->kelas->nama ?? 'Tanpa Kelas' }}</p>
                        </div>
                        @empty
                        <p class="text-xs text-on-surface-variant text-center py-2">Alhamdulillah, tidak ada yang sakit.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Izin --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-orange-600 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">exit_to_app</span>
                        Izin ({{ $izinList->count() }})
                    </h4>
                    <div class="bg-orange-500/5 rounded-xl border border-orange-500/20 p-3 max-h-60 overflow-y-auto">
                        @forelse($izinList as $s)
                        <div class="py-1.5 border-b border-orange-500/10 last:border-0">
                            <p class="text-sm font-bold text-on-surface">{{ $s->nama }}</p>
                            <p class="text-[10px] text-on-surface-variant">{{ $s->kelas->nama ?? 'Tanpa Kelas' }}</p>
                        </div>
                        @empty
                        <p class="text-xs text-on-surface-variant text-center py-2">Tidak ada santri izin hari ini.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Alpha / Belum Dicatat --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-gray-600 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">help</span>
                        Belum Dicatat / Alpha ({{ $alphaList->count() }})
                    </h4>
                    <div class="bg-gray-100 rounded-xl border border-gray-200 p-3 max-h-60 overflow-y-auto">
                        @forelse($alphaList as $s)
                        <div class="py-1.5 border-b border-gray-200 last:border-0">
                            <p class="text-sm font-bold text-on-surface">{{ $s->nama }}</p>
                            <p class="text-[10px] text-on-surface-variant">{{ $s->kelas->nama ?? 'Tanpa Kelas' }}</p>
                        </div>
                        @empty
                        <p class="text-xs text-on-surface-variant text-center py-2">Semua santri telah dicatat absensinya.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Tambah Santri --}}
<div id="modal-tambah-santri" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-primary p-6 flex justify-between items-center">
            <h3 class="font-bold text-lg text-white flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">person_add</span>
                Tambah Santri Baru
            </h3>
            <button onclick="document.getElementById('modal-tambah-santri').classList.add('hidden')"
                class="text-white/70 hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('santri.store') }}" class="p-8 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Nama santri..."
                        class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">NIS</label>
                    <input type="text" name="nis" required placeholder="Nomor Induk..."
                        class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none appearance-none bg-white">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Kelas</label>
                    <select name="kelas_id" class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none appearance-none bg-white">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach(\App\Models\Kelas::all() as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }} ({{ $kelas->julukan }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Tahun Masuk</label>
                    <input type="number" name="tahun_masuk" value="{{ date('Y') }}"
                        class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-tambah-santri').classList.add('hidden')"
                    class="flex-1 py-3 border border-outline-variant text-on-surface-variant rounded-xl text-sm font-bold hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-primary text-white rounded-xl text-sm font-bold hover:opacity-90 transition-opacity">
                    Simpan Santri
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Cetak Laporan (Placeholder) --}}
<div id="modal-cetak-laporan" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-purple-600 p-6 flex justify-between items-center">
            <h3 class="font-bold text-lg text-white flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">print</span>
                Cetak Laporan
            </h3>
            <button onclick="document.getElementById('modal-cetak-laporan').classList.add('hidden')"
                class="text-white/70 hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-8 text-center">
            <span class="material-symbols-outlined text-6xl text-purple-200 mb-4">construction</span>
            <p class="text-on-surface-variant text-sm font-medium">Fitur cetak laporan massal sedang dalam tahap pengembangan. Segera hadir!</p>
        </div>
    </div>
</div>

{{-- Modal: Tambah Pengurus (Ustadz/Musyrif) --}}
<div id="modal-tambah-pengurus" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-indigo-600 p-6 flex justify-between items-center">
            <h3 class="font-bold text-lg text-white flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">badge</span>
                Tambah Pengurus Baru
            </h3>
            <button onclick="document.getElementById('modal-tambah-pengurus').classList.add('hidden')"
                class="text-white/70 hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('users.store') }}" class="p-8 space-y-4 max-h-[80vh] overflow-y-auto">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="Nama Ustadz/Musyrif..."
                    class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Email Login</label>
                    <input type="email" name="email" required placeholder="email@contoh.com"
                        class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Password</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" minlength="6"
                        class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Peran (Role)</label>
                    <select name="role_id" required class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-indigo-600 outline-none appearance-none bg-white">
                        <option value="5">Ustadz (Akademik)</option>
                        <option value="2">Musyrif (Asrama/Tahfizh)</option>
                        <option value="6">Mudir (Kepala Pesantren)</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Nomor Telepon</label>
                    <input type="text" name="phone" placeholder="08123xxxx"
                        class="w-full h-12 px-4 border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-indigo-600 outline-none">
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-outline-variant/30 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-tambah-pengurus').classList.add('hidden')"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold text-on-surface-variant hover:bg-surface-container transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:opacity-90 transition-opacity shadow-md">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari controller
    const bulananLabels = @json(array_column($hafalanBulanan, 'month'));
    const bulananData   = @json(array_column($hafalanBulanan, 'val'));
    const pekananLabels = @json(array_column($hafalanPekanan, 'month'));
    const pekananData   = @json(array_column($hafalanPekanan, 'val'));

    const ctx = document.getElementById('hafalanChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: bulananLabels,
            datasets: [{
                label: 'Jumlah Setoran',
                data: bulananData,
                backgroundColor: 'rgba(0,69,50,0.25)',
                borderColor: 'rgba(0,69,50,0.8)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.parsed.y + ' Setoran'
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11, weight: 'bold' } } },
                y: { grid: { color: 'rgba(0,0,0,0.05)' }, beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // Toggle Bulanan / Pekanan
    document.getElementById('btnBulanan').addEventListener('click', function() {
        chart.data.labels = bulananLabels;
        chart.data.datasets[0].data = bulananData;
        chart.update();
        this.classList.add('bg-white','shadow-sm','text-primary');
        this.classList.remove('text-on-surface-variant');
        document.getElementById('btnPekanan').classList.remove('bg-white','shadow-sm','text-primary');
        document.getElementById('btnPekanan').classList.add('text-on-surface-variant');
    });
    document.getElementById('btnPekanan').addEventListener('click', function() {
        chart.data.labels = pekananLabels;
        chart.data.datasets[0].data = pekananData;
        chart.update();
        this.classList.add('bg-white','shadow-sm','text-primary');
        this.classList.remove('text-on-surface-variant');
        document.getElementById('btnBulanan').classList.remove('bg-white','shadow-sm','text-primary');
        document.getElementById('btnBulanan').classList.add('text-on-surface-variant');
    });

    // Tutup modal klik di luar
    ['modal-absensi','modal-tambah-santri','modal-cetak-laporan','modal-tambah-pengurus'].forEach(id => {
        const modal = document.getElementById(id);
        if(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });
        }
    });
</script>
@endpush
