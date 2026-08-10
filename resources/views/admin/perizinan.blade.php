@extends('layouts.app')

@section('title', 'Perizinan Santri')
@section('meta_description', 'Manajemen perizinan santri SUNTRI (sakit, pulang, kegiatan luar).')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-amber-500 rounded-2xl shadow-lg">
            <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">directions_run</span>
        </div>
        <div>
            <h2 class="text-3xl font-bold text-amber-500">Pusat Perizinan</h2>
            <p class="text-sm text-on-surface-variant">Kelola izin sakit, pulang, dan kegiatan luar santri</p>
        </div>
    </div>
    <div class="flex gap-2">
        <button onclick="document.getElementById('modal-tambah-izin').classList.remove('hidden')" class="px-4 py-2 rounded-lg bg-amber-500 text-white text-sm font-bold flex items-center gap-2 hover:opacity-90 shadow-md transition-opacity">
            <span class="material-symbols-outlined text-sm">add_box</span> Ajukan Izin Baru
        </button>
    </div>
</div>

{{-- Summary Cards --}}
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 fade-in-up delay-1">
    @php
        $cards = [
            ['icon'=>'how_to_reg',     'label'=>'Total Izin Aktif',  'value'=>$stats['aktif'],   'border'=>'border-amber-500',       'iconBg'=>'bg-amber-500/10 text-amber-500',           'badgeBg'=>'bg-amber-500/5 text-amber-500'],
            ['icon'=>'pending_actions','label'=>'Menunggu Approval', 'value'=>$stats['pending'], 'border'=>'border-secondary',       'iconBg'=>'bg-secondary/10 text-secondary',           'badgeBg'=>'bg-secondary/5 text-secondary'],
            ['icon'=>'local_hospital', 'label'=>'Izin Sakit',        'value'=>$stats['sakit'],   'border'=>'border-error',           'iconBg'=>'bg-error/10 text-error',                   'badgeBg'=>'bg-error/5 text-error'],
            ['icon'=>'home',           'label'=>'Izin Pulang',       'value'=>$stats['pulang'],  'border'=>'border-primary-container','iconBg'=>'bg-primary-container/10 text-primary-container','badgeBg'=>'bg-primary-container/5 text-primary-container'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="glassmorphism p-6 rounded-2xl shadow-sm {{ $card['border'] }} border-l-4">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-xl {{ $card['iconBg'] }} flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">{{ $card['icon'] }}</span>
            </div>
            <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $card['badgeBg'] }}">Hari Ini</span>
        </div>
        <p class="text-sm text-on-surface-variant font-medium">{{ $card['label'] }}</p>
        <h3 class="text-3xl font-black text-on-surface mt-1">{{ $card['value'] }}</h3>
    </div>
    @endforeach
</section>

{{-- Main Content --}}
<div class="grid grid-cols-1 gap-8 items-start fade-in-up delay-2 mt-6">

    <section class="space-y-5">
        {{-- Filter Bar --}}
        <form action="{{ route('perizinan') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4 glassmorphism p-4 rounded-xl">
            <div class="flex items-center gap-4 flex-1 min-w-[260px]">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">filter_list</span>
                    <select name="status" onchange="this.form.submit()" class="w-full bg-surface border border-outline-variant/40 rounded-lg pl-9 pr-4 py-2 text-sm appearance-none focus:ring-2 focus:ring-amber-500/20 outline-none">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
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
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Santri</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Jenis Izin</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Alasan</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($perizinans as $izin)
                        @php
                            $statusClass = match($izin->status) {
                                'disetujui' => 'bg-primary-container/10 text-primary-container border-primary-container/20',
                                'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                'ditolak' => 'bg-error/10 text-error border-error/20',
                                default => ''
                            };
                            
                            $jenisFormat = match($izin->jenis) {
                                'pulang' => 'Pulang',
                                'sakit' => 'Sakit',
                                'kegiatan_luar' => 'Keg. Luar',
                                'lainnya' => 'Lainnya'
                            };

                            $json = json_encode([
                                'id' => $izin->id,
                                'nama' => $izin->santri->nama,
                                'kelas' => $izin->santri->kelas->nama ?? '-',
                                'jenis' => $jenisFormat,
                                'tanggal_mulai' => \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d M Y'),
                                'tanggal_selesai' => \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d M Y'),
                                'alasan' => $izin->alasan,
                                'catatan_admin' => $izin->catatan_admin,
                            ]);
                        @endphp
                        <tr class="hover:bg-amber-500/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center font-bold text-sm text-on-surface-variant">
                                        {{ substr($izin->santri->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">{{ $izin->santri->nama }}</p>
                                        <p class="text-[11px] text-on-surface-variant">{{ $izin->santri->kelas->nama ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-on-surface">{{ $jenisFormat }}</td>
                            <td class="px-6 py-4 text-[11px] text-on-surface-variant">
                                <span class="block font-bold text-on-surface">{{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d/m/Y') }}</span>
                                s/d {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-on-surface-variant max-w-[200px] truncate" title="{{ $izin->alasan }}">{{ $izin->alasan }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 {{ $statusClass }} text-[10px] font-bold rounded-full border uppercase">{{ $izin->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($izin->status == 'pending')
                                    <button onclick="openProsesModal({{ $json }}, 'disetujui')" class="w-8 h-8 rounded-lg hover:bg-primary/10 flex items-center justify-center text-outline transition-colors hover:text-primary" title="Setujui">
                                        <span class="material-symbols-outlined text-lg">check_circle</span>
                                    </button>
                                    <button onclick="openProsesModal({{ $json }}, 'ditolak')" class="w-8 h-8 rounded-lg hover:bg-error/10 flex items-center justify-center text-outline transition-colors hover:text-error" title="Tolak">
                                        <span class="material-symbols-outlined text-lg">cancel</span>
                                    </button>
                                    @else
                                    <button onclick="alert('Catatan Admin: \n{{ $izin->catatan_admin ?? 'Tidak ada catatan.' }}')" class="w-8 h-8 rounded-lg hover:bg-surface-container-high flex items-center justify-center text-outline transition-colors hover:text-secondary" title="Lihat Info">
                                        <span class="material-symbols-outlined text-lg">info</span>
                                    </button>
                                    @if($izin->status == 'disetujui')
                                    <button onclick="window.print()" class="w-8 h-8 rounded-lg hover:bg-surface-container-high flex items-center justify-center text-outline transition-colors hover:text-amber-500" title="Cetak Surat Izin">
                                        <span class="material-symbols-outlined text-lg">print</span>
                                    </button>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-on-surface-variant">Tidak ada data perizinan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-surface-container-low/30 border-t border-outline-variant/10">
                {{ $perizinans->links('pagination::tailwind') }}
            </div>
        </div>
    </section>
</div>

{{-- MODAL TAMBAH IZIN --}}
<div id="modal-tambah-izin" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-amber-500/10 px-8 py-5 border-b border-amber-500/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-amber-500 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">directions_run</span> Ajukan Izin Baru
            </h3>
            <button type="button" onclick="document.getElementById('modal-tambah-izin').classList.add('hidden')" class="text-amber-500 hover:bg-amber-500/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('perizinan.store') }}" class="p-8 space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Santri</label>
                <select name="santri_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                    <option value="">Pilih Santri...</option>
                    @foreach($santris as $s)
                    <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->kelas->nama ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Jenis Izin</label>
                <select name="jenis" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                    <option value="pulang">Pulang ke Rumah</option>
                    <option value="sakit">Izin Sakit</option>
                    <option value="kegiatan_luar">Kegiatan Luar (Lomba, dll)</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Alasan Lengkap</label>
                <textarea name="alasan" required rows="3" class="w-full p-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none resize-none" placeholder="Tuliskan alasan pengajuan izin secara detail..."></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="w-full py-3 bg-amber-500 text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan & Setujui Otomatis</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PROSES IZIN --}}
<div id="modal-proses-izin" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden border border-white/20">
        <div class="bg-secondary/10 px-8 py-5 border-b border-secondary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">rule</span> Proses Perizinan
            </h3>
            <button type="button" onclick="document.getElementById('modal-proses-izin').classList.add('hidden')" class="text-secondary hover:bg-secondary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" id="form-proses-izin" class="p-8 space-y-4">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="status" id="input_status_proses">

            <div class="bg-surface-container p-4 rounded-xl border border-outline-variant/30 mb-4">
                <p class="text-xs text-on-surface-variant font-bold uppercase tracking-widest mb-1">Informasi Izin</p>
                <p class="text-sm font-bold text-on-surface" id="proses_nama_santri">-</p>
                <p class="text-xs text-on-surface-variant" id="proses_detail">-</p>
                <div class="h-px bg-outline-variant/20 my-2"></div>
                <p class="text-sm italic text-on-surface-variant">"<span id="proses_alasan">-</span>"</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Catatan Admin (Opsional)</label>
                <textarea name="catatan_admin" rows="2" class="w-full p-3 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none resize-none" placeholder="Misal: Harap kembali sebelum Maghrib..."></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" id="btn_submit_proses" class="w-full py-3 bg-secondary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openProsesModal(data, action) {
        document.getElementById('proses_nama_santri').innerText = data.nama + ' (' + data.kelas + ')';
        document.getElementById('proses_detail').innerText = data.jenis + ' | ' + data.tanggal_mulai + ' - ' + data.tanggal_selesai;
        document.getElementById('proses_alasan').innerText = data.alasan;
        
        document.getElementById('input_status_proses').value = action;

        const btn = document.getElementById('btn_submit_proses');
        if (action === 'disetujui') {
            btn.className = 'w-full py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity';
            btn.innerText = 'Setujui Izin';
        } else {
            btn.className = 'w-full py-3 bg-error text-white font-bold rounded-xl hover:opacity-90 transition-opacity';
            btn.innerText = 'Tolak Izin';
        }

        document.getElementById('form-proses-izin').action = '/admin/perizinan/' + data.id + '/status';
        document.getElementById('modal-proses-izin').classList.remove('hidden');
    }
</script>

@endsection
