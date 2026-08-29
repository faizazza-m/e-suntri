@extends('layouts.app')
@section('title', 'Jurnal Harian Guru')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-primary-container rounded-2xl shadow-lg">
                <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">assignment</span>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-primary">Jurnal Harian Guru</h2>
                <p class="text-sm text-on-surface-variant">Pantau progres dan aktivitas harian Ustadz/Guru per pertemuan.</p>
            </div>
        </div>
        
        {{-- Filter Form --}}
        <form action="{{ route('laporan-guru') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <select name="guru_id" class="px-4 py-2 text-sm rounded-xl border border-outline-variant/30 bg-white/60 backdrop-blur-md shadow-sm focus:ring-primary focus:border-primary transition-colors" onchange="this.form.submit()">
                <option value="">-- Semua Guru --</option>
                @foreach($gurus as $guru)
                <option value="{{ $guru->id }}" {{ request('guru_id') == $guru->id ? 'selected' : '' }}>
                    {{ $guru->name }}
                </option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Laporan Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in-up delay-1">
        @forelse($laporans as $laporan)
        <div class="glassmorphism p-6 rounded-2xl border border-white/20 shadow-sm flex flex-col h-full relative group cursor-pointer hover:scale-[1.02] transition-all duration-300" onclick="openDetail({{ $laporan->id }})">
            
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px] text-primary">person</span>
                    </div>
                    <div>
                        <p class="font-bold text-sm text-on-surface truncate max-w-[120px]">{{ $laporan->guru->name }}</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg 
                    {{ $laporan->status === 'dibaca' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} status-badge">
                    {{ $laporan->status }}
                </span>
            </div>

            <h3 class="text-lg font-bold text-on-surface leading-tight mb-1">{{ $laporan->materi }}</h3>
            <p class="text-xs font-bold text-primary mb-3">
                {{ $laporan->kelas }} &bull; {{ $laporan->mata_pelajaran }}
            </p>
            
            <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-3">
                Tanggal: {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}
            </p>
            
            @if($laporan->isi_laporan)
            <p class="text-sm text-on-surface-variant font-medium line-clamp-3 leading-relaxed mb-4 flex-1">
                {{ $laporan->isi_laporan }}
            </p>
            @else
            <p class="text-sm text-on-surface-variant italic mb-4 flex-1">Tidak ada catatan tambahan.</p>
            @endif

            <div class="mt-auto pt-4 border-t border-outline-variant/30 flex justify-between items-center text-[10px] font-bold text-on-surface-variant">
                <span>Dikirim: {{ optional($laporan->created_at)->diffForHumans() ?? '-' }}</span>
                <span class="flex items-center gap-1 group-hover:text-primary transition-colors">
                    Baca Detail <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 glassmorphism rounded-3xl border border-dashed border-outline-variant/50 flex flex-col items-center justify-center text-center">
            <span class="material-symbols-outlined text-6xl text-outline-variant mb-4" style="font-variation-settings:'FILL' 1;">assignment</span>
            <h3 class="text-xl font-bold text-on-surface">Belum Ada Jurnal</h3>
            <p class="text-sm text-on-surface-variant mt-1">Tidak ada jurnal harian guru yang ditemukan saat ini.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $laporans->links() }}
    </div>

</div>

{{-- Modal Detail Laporan --}}
<div id="modalDetail" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDetail()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4">
        <div class="bg-surface rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            {{-- Modal Header --}}
            <div class="p-6 border-b border-outline-variant/30 bg-white/60 shrink-0 flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold text-on-surface mb-1">Detail Jurnal Mengajar</h3>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-primary-container flex items-center justify-center">
                            <span class="material-symbols-outlined text-[12px] text-primary">person</span>
                        </div>
                        <p class="font-bold text-sm text-on-surface-variant" id="detailGuruName">-</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetail()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-black/5 transition-colors">
                    <span class="material-symbols-outlined text-[20px] text-on-surface-variant">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto space-y-6 flex-1 bg-surface-container-low">
                
                <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/20 p-5">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="text-lg font-bold text-on-surface" id="detailMateri">-</h4>
                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg bg-green-100 text-green-700">Dibaca</span>
                    </div>
                    <p class="text-sm font-bold text-primary mb-3" id="detailKelasMapel">-</p>

                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-4" id="detailTanggal">
                        Tanggal: -
                    </p>
                    
                    <div class="border-t border-outline-variant/30 pt-4">
                        <p class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-wrap" id="detailIsi">-</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openDetail(id) {
        document.getElementById('modalDetail').classList.remove('hidden');
        
        // Show loading state (optional)
        document.getElementById('detailMateri').textContent = "Memuat...";
        document.getElementById('detailIsi').textContent = "Sedang mengambil data jurnal...";
        
        fetch(`/admin/laporan-guru/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('detailGuruName').textContent = data.guru.name;
                document.getElementById('detailMateri').textContent = data.materi;
                document.getElementById('detailKelasMapel').textContent = `${data.kelas} • ${data.mata_pelajaran}`;
                
                // Format dates safely
                const tgl = new Date(data.tanggal).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
                document.getElementById('detailTanggal').textContent = `Tanggal: ${tgl}`;
                
                document.getElementById('detailIsi').textContent = data.isi_laporan || 'Tidak ada catatan tambahan.';

                // Mark the badge as read locally in the UI to sync
                const cardBadge = document.querySelector(`[onclick="openDetail(${id})"] .status-badge`);
                if(cardBadge) {
                    cardBadge.textContent = 'dibaca';
                    cardBadge.classList.remove('bg-yellow-100', 'text-yellow-700');
                    cardBadge.classList.add('bg-green-100', 'text-green-700');
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('detailIsi').textContent = "Gagal memuat data. Silakan coba lagi.";
            });
    }

    function closeDetail() {
        document.getElementById('modalDetail').classList.add('hidden');
    }
</script>
@endpush
