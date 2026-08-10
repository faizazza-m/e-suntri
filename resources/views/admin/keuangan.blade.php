@extends('layouts.app')

@section('title', 'Keuangan')
@section('meta_description', 'Manajemen keuangan, tagihan SPP, dan invoice digital santri SUNTRI.')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-purple-600 rounded-2xl shadow-lg">
            <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">payments</span>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-purple-600">Pusat Keuangan</h2>
            <p class="text-sm text-on-surface-variant">Kelola tagihan, pembayaran, dan invoice santri</p>
        </div>
    </div>
    <div class="flex gap-2">
        <button onclick="document.getElementById('modal-tambah-tagihan').classList.remove('hidden')" class="px-4 py-2 rounded-lg bg-secondary text-white text-sm font-bold flex items-center gap-2 hover:opacity-90 shadow-md transition-opacity">
            <span class="material-symbols-outlined text-sm">add_box</span> Buat Tagihan Baru
        </button>
    </div>
</div>

{{-- Summary Cards --}}
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 fade-in-up delay-1">
    @php
        $cards = [
            ['icon'=>'account_balance_wallet','label'=>'Total Tagihan',  'value'=>'Rp '.number_format($stats['total_tagihan'],0,',','.'), 'badge'=>'Total',    'border'=>'border-primary',           'iconBg'=>'bg-primary/10 text-primary',               'badgeBg'=>'bg-primary/5 text-primary'],
            ['icon'=>'check_circle',          'label'=>'Sudah Dibayar',  'value'=>'Rp '.number_format($stats['sudah_dibayar'],0,',','.'), 'badge'=>'Lunas',  'border'=>'border-primary-container', 'iconBg'=>'bg-primary-container/10 text-primary-container','badgeBg'=>'bg-primary-container/5 text-primary-container'],
            ['icon'=>'pending_actions',       'label'=>'Belum Dibayar',  'value'=>'Rp '.number_format($stats['belum_dibayar'],0,',','.'), 'badge'=>$stats['count_belum_dibayar'].' Tagihan',  'border'=>'border-secondary-container','iconBg'=>'bg-secondary-container/10 text-on-secondary-container','badgeBg'=>'bg-secondary-container/5 text-on-secondary-container'],
            ['icon'=>'priority_high',         'label'=>'Jatuh Tempo',    'value'=>'Rp '.number_format($stats['jatuh_tempo'],0,',','.'),  'badge'=>'Urgent',      'border'=>'border-error',             'iconBg'=>'bg-error/10 text-error',                   'badgeBg'=>'bg-error/5 text-error'],
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
        <h3 class="text-xl lg:text-2xl font-bold text-on-surface mt-1 truncate" title="{{ $card['value'] }}">{{ $card['value'] }}</h3>
    </div>
    @endforeach
</section>

{{-- Main Content --}}
<div class="grid grid-cols-12 gap-8 items-start fade-in-up delay-2 mt-6">

    {{-- Table Section (Left 8/12) --}}
    <section class="col-span-12 lg:col-span-8 space-y-5">
        
        {{-- Filter Bar --}}
        <form action="{{ route('keuangan') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4 glassmorphism p-4 rounded-xl">
            <div class="flex items-center gap-4 flex-1 min-w-[260px]">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">filter_list</span>
                    <select name="kelas" onchange="this.form.submit()" class="w-full bg-surface border border-outline-variant/40 rounded-lg pl-9 pr-4 py-2 text-sm appearance-none focus:ring-2 focus:ring-primary/20 outline-none">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        {{-- Data Table --}}
        <div class="glassmorphism rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-surface-container-low/60">
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Santri</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tagihan</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($tagihans as $tagihan)
                        @php
                            $statusClass = match($tagihan->status) {
                                'lunas' => 'bg-primary-container/10 text-primary-container border-primary-container/20',
                                'belum' => 'bg-surface-container-highest text-on-surface-variant border-outline-variant/30',
                                'terlambat' => 'bg-error/10 text-error border-error/20',
                                default => ''
                            };
                            $invJson = json_encode([
                                'id' => $tagihan->id,
                                'nama' => $tagihan->santri->nama,
                                'kelas' => $tagihan->santri->kelas->nama ?? '-',
                                'jatuh_tempo' => \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d F Y'),
                                'keterangan' => $tagihan->jenis->nama . ' - ' . \Carbon\Carbon::create()->month($tagihan->bulan)->translatedFormat('F') . ' ' . $tagihan->tahun,
                                'nominal' => 'Rp ' . number_format($tagihan->nominal, 0, ',', '.'),
                                'status' => $tagihan->status
                            ]);
                        @endphp
                        <tr class="hover:bg-primary/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($tagihan->santri->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">{{ $tagihan->santri->nama }}</p>
                                        <p class="text-[11px] text-on-surface-variant">ID: {{ $tagihan->santri->nis ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-on-surface-variant">{{ $tagihan->santri->kelas->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-on-surface">{{ $tagihan->jenis->nama }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-on-surface">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 {{ $statusClass }} text-[10px] font-bold rounded-full border uppercase">{{ $tagihan->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="previewInvoice({{ $invJson }})" class="w-8 h-8 rounded-lg hover:bg-surface-container-high flex items-center justify-center text-outline transition-colors hover:text-primary" title="Lihat Invoice">
                                        <span class="material-symbols-outlined text-lg">receipt_long</span>
                                    </button>
                                    @if($tagihan->status != 'lunas')
                                    <button onclick="openEditModal({{ $invJson }})" class="w-8 h-8 rounded-lg hover:bg-surface-container-high flex items-center justify-center text-outline transition-colors hover:text-secondary" title="Edit Nominal Tagihan">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </button>
                                    <button onclick="openBayarModal({{ $invJson }})" class="w-8 h-8 rounded-lg hover:bg-surface-container-high flex items-center justify-center text-outline transition-colors hover:text-primary" title="Bayar">
                                        <span class="material-symbols-outlined text-lg">payments</span>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-on-surface-variant">Tidak ada data tagihan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-surface-container-low/30 border-t border-outline-variant/10">
                {{ $tagihans->links('pagination::tailwind') }}
            </div>
        </div>
    </section>

    {{-- Invoice Preview (Right 4/12) --}}
    <aside class="col-span-12 lg:col-span-4">
        <div class="glassmorphism p-6 rounded-2xl shadow-xl sticky top-24" id="invoicePreviewContainer">
            <div class="flex items-center justify-between mb-5">
                <h4 class="text-xs font-bold text-on-surface uppercase tracking-wider">Invoice Preview</h4>
                <span class="material-symbols-outlined text-outline cursor-pointer hover:text-primary transition-colors">more_vert</span>
            </div>

            <div id="invoiceBlank" class="text-center py-10 text-on-surface-variant">
                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">receipt</span>
                <p class="text-sm">Klik ikon invoice pada tabel untuk melihat detail.</p>
            </div>

            <div id="invoiceContent" class="hidden">
                {{-- Invoice Card --}}
                <div class="bg-white rounded-xl p-6 shadow-inner border border-outline-variant/20 relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-28 h-28 bg-primary/5 rounded-full pointer-events-none"></div>
                    <div class="flex justify-between items-start mb-5 relative z-10">
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">mosque</span>
                                <p class="text-xs font-black text-primary tracking-tight">SUNTRI</p>
                            </div>
                            <p class="text-[8px] text-outline leading-tight">YAYASAN PENDIDIKAN ISLAM<br/>Nusantara, Indonesia</p>
                        </div>
                        <div class="text-right">
                            <h5 class="text-sm font-bold text-on-surface">INVOICE</h5>
                            <p class="text-[10px] text-outline" id="invNo">#INV-xxxxx</p>
                        </div>
                    </div>
                    <div class="h-px bg-outline-variant/20 mb-4"></div>
                    <div class="space-y-3 mb-5">
                        <div class="flex justify-between text-[10px]">
                            <span class="text-outline uppercase">Nama Santri:</span>
                            <span class="text-on-surface font-bold" id="invNama">-</span>
                        </div>
                        <div class="flex justify-between text-[10px]">
                            <span class="text-outline uppercase">Kelas:</span>
                            <span class="text-on-surface font-bold" id="invKelas">-</span>
                        </div>
                        <div class="flex justify-between text-[10px]">
                            <span class="text-outline uppercase">Jatuh Tempo:</span>
                            <span class="text-error font-bold" id="invTempo">-</span>
                        </div>
                    </div>
                    <div class="space-y-1.5 mb-5">
                        <div class="flex justify-between text-[10px] bg-surface-container-low p-2 rounded">
                            <span class="font-medium" id="invKet">-</span>
                            <span class="font-bold" id="invNominal">-</span>
                        </div>
                    </div>
                    <div class="bg-primary p-3 rounded-lg flex justify-between items-center text-on-primary">
                        <span class="text-[10px] font-medium uppercase opacity-80">Total Tagihan</span>
                        <span class="text-sm font-black" id="invTotal">-</span>
                    </div>
                    <div class="mt-3 flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-[10px] text-primary-container">verified</span>
                        <p class="text-[8px] text-primary-container font-bold italic">Generated by SUNTRI FinSys</p>
                    </div>
                </div>

                <div class="mt-5 flex flex-col gap-3">
                    <button class="w-full bg-primary text-on-primary py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:opacity-90 shadow-lg shadow-primary/20 transition-all">
                        <span class="material-symbols-outlined text-sm">download</span> Download Invoice
                    </button>
                </div>
            </div>
        </div>
    </aside>
</div>

{{-- Riwayat Pembayaran --}}
<section class="space-y-5 fade-in-up delay-3 mt-8">
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-on-surface">Riwayat Pembayaran Terbaru</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse($riwayatPembayaran as $riwayat)
        <div class="glassmorphism p-4 rounded-xl flex items-center gap-4 border border-outline-variant/10 hover:border-primary/20 transition-colors">
            <div class="w-10 h-10 rounded-full bg-primary-container/10 flex items-center justify-center text-primary-container flex-shrink-0">
                <span class="material-symbols-outlined">done_all</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-on-surface truncate">{{ $riwayat->santri->nama ?? 'Unknown' }}</p>
                <p class="text-[10px] text-on-surface-variant">Rp {{ number_format($riwayat->nominal_bayar,0,',','.') }} • {{ \Carbon\Carbon::parse($riwayat->created_at)->diffForHumans() }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-full p-4 text-center text-sm text-on-surface-variant">
            Belum ada riwayat pembayaran.
        </div>
        @endforelse
    </div>
</section>

{{-- MODAL TAMBAH TAGIHAN --}}
<div id="modal-tambah-tagihan" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-secondary/10 px-8 py-5 border-b border-secondary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">add_box</span> Generate Tagihan Baru
            </h3>
            <button type="button" onclick="document.getElementById('modal-tambah-tagihan').classList.add('hidden')" class="text-secondary hover:bg-secondary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('keuangan.tagihan.store') }}" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Jenis Tagihan</label>
                <select name="jenis_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                    @foreach($jenisTagihans as $jt)
                    <option value="{{ $jt->id }}">{{ $jt->nama }} (Rp {{ number_format($jt->nominal,0,',','.') }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Bulan</label>
                    <select name="bulan" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                        @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Tahun</label>
                    <input type="number" name="tahun" value="{{ date('Y') }}" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Jatuh Tempo</label>
                <input type="date" name="jatuh_tempo" value="{{ date('Y-m-t') }}" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
            </div>

            <div class="p-4 bg-surface-container-low border border-outline-variant/30 rounded-xl space-y-2 mt-4">
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Penerima Tagihan</label>
                <div class="flex items-center gap-2">
                    <input type="radio" name="target_type" value="semua_santri" id="target_semua" checked onchange="toggleTarget()">
                    <label for="target_semua" class="text-sm">Semua Santri</label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="radio" name="target_type" value="kelas" id="target_kelas" onchange="toggleTarget()">
                    <label for="target_kelas" class="text-sm">Berdasarkan Kelas</label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="radio" name="target_type" value="santri" id="target_santri" onchange="toggleTarget()">
                    <label for="target_santri" class="text-sm">Santri Tertentu</label>
                </div>

                <div id="selectKelas" class="hidden mt-2">
                    <select name="kelas_id" class="w-full h-10 px-3 bg-white border border-outline-variant rounded-lg text-sm outline-none">
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div id="selectSantri" class="hidden mt-2">
                    <select name="santri_id" class="w-full h-10 px-3 bg-white border border-outline-variant rounded-lg text-sm outline-none">
                        @foreach($santris as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->kelas->nama ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="w-full py-3 bg-secondary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Generate Tagihan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL BAYAR TAGIHAN --}}
<div id="modal-bayar-tagihan" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden border border-white/20">
        <div class="bg-primary/10 px-8 py-5 border-b border-primary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">payments</span> Proses Pembayaran
            </h3>
            <button type="button" onclick="document.getElementById('modal-bayar-tagihan').classList.add('hidden')" class="text-primary hover:bg-primary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('keuangan.pembayaran.store') }}" class="p-8 space-y-4">
            @csrf
            <input type="hidden" name="tagihan_id" id="bayar_tagihan_id">
            
            <div class="bg-surface-container p-4 rounded-xl border border-outline-variant/30 mb-4">
                <p class="text-xs text-on-surface-variant font-bold uppercase tracking-widest mb-1">Informasi Tagihan</p>
                <p class="text-sm font-bold text-on-surface" id="bayar_nama_santri">-</p>
                <p class="text-xs text-on-surface-variant" id="bayar_keterangan">-</p>
                <div class="h-px bg-outline-variant/20 my-2"></div>
                <p class="text-sm font-bold text-primary" id="bayar_nominal">-</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Nominal Bayar (Rp)</label>
                <input type="number" name="nominal_bayar" id="input_nominal_bayar" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-lg font-bold focus:ring-2 focus:ring-primary outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Metode Pembayaran</label>
                <select name="metode" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                    <option value="tunai">Tunai / Cash</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Catatan (Opsional)</label>
                <input type="text" name="catatan" class="w-full h-10 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Proses Pembayaran</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT TAGIHAN --}}
<div id="modal-edit-tagihan" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden border border-white/20">
        <div class="bg-secondary/10 px-8 py-5 border-b border-secondary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">edit</span> Edit Nominal Tagihan
            </h3>
            <button type="button" onclick="document.getElementById('modal-edit-tagihan').classList.add('hidden')" class="text-secondary hover:bg-secondary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" id="form-edit-tagihan" class="p-8 space-y-4">
            @csrf
            @method('PUT')
            
            <div class="bg-surface-container p-4 rounded-xl border border-outline-variant/30 mb-4">
                <p class="text-xs text-on-surface-variant font-bold uppercase tracking-widest mb-1">Santri</p>
                <p class="text-sm font-bold text-on-surface" id="edit_nama_santri">-</p>
                <p class="text-xs text-on-surface-variant" id="edit_keterangan">-</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Nominal Tagihan (Rp)</label>
                <input type="number" name="nominal" id="input_edit_nominal" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-lg font-bold focus:ring-2 focus:ring-secondary outline-none">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="w-full py-3 bg-secondary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewInvoice(data) {
        document.getElementById('invoiceBlank').classList.add('hidden');
        document.getElementById('invoiceContent').classList.remove('hidden');

        document.getElementById('invNo').innerText = '#INV-TAG-' + data.id.toString().padStart(5, '0');
        document.getElementById('invNama').innerText = data.nama;
        document.getElementById('invKelas').innerText = data.kelas;
        document.getElementById('invTempo').innerText = data.jatuh_tempo;
        document.getElementById('invKet').innerText = data.keterangan;
        document.getElementById('invNominal').innerText = data.nominal;
        document.getElementById('invTotal').innerText = data.nominal;
    }

    function openBayarModal(data) {
        document.getElementById('bayar_tagihan_id').value = data.id;
        document.getElementById('bayar_nama_santri').innerText = data.nama + ' (' + data.kelas + ')';
        document.getElementById('bayar_keterangan').innerText = data.keterangan;
        document.getElementById('bayar_nominal').innerText = data.nominal;
        
        // Remove 'Rp ' and '.' for the number input
        let numOnly = data.nominal.replace(/[^0-9]/g, '');
        document.getElementById('input_nominal_bayar').value = numOnly;

        document.getElementById('modal-bayar-tagihan').classList.remove('hidden');
    }

    function openEditModal(data) {
        document.getElementById('edit_nama_santri').innerText = data.nama + ' (' + data.kelas + ')';
        document.getElementById('edit_keterangan').innerText = data.keterangan;
        
        let numOnly = data.nominal.replace(/[^0-9]/g, '');
        document.getElementById('input_edit_nominal').value = numOnly;

        // Set form action dynamically
        document.getElementById('form-edit-tagihan').action = '/admin/keuangan/tagihan/' + data.id;

        document.getElementById('modal-edit-tagihan').classList.remove('hidden');
    }

    function toggleTarget() {
        const type = document.querySelector('input[name="target_type"]:checked').value;
        document.getElementById('selectKelas').classList.toggle('hidden', type !== 'kelas');
        document.getElementById('selectSantri').classList.toggle('hidden', type !== 'santri');
    }
</script>

@endsection
