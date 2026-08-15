@extends('layouts.app')
@section('title', 'Laporan Mingguan Guru')

@push('styles')
<style>
    .brutal-card {
        background: #ffffff;
        border: 3px solid #000;
        border-radius: 16px;
        box-shadow: 6px 6px 0px #000;
        transition: all 0.2s ease;
    }
    .brutal-card:hover {
        transform: translate(-2px, -2px);
        box-shadow: 8px 8px 0px #000;
    }
    .brutal-input {
        border: 2px solid #000;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .brutal-input:focus {
        box-shadow: 4px 4px 0px #000;
        outline: none;
    }
    .brutal-btn {
        background: #fbbf24;
        border: 2px solid #000;
        box-shadow: 4px 4px 0px #000;
        transition: all 0.2s ease;
    }
    .brutal-btn:hover {
        transform: translate(-2px, -2px);
        box-shadow: 6px 6px 0px #000;
    }
    .brutal-btn:active {
        transform: translate(0px, 0px);
        box-shadow: 0px 0px 0px #000;
    }
    .status-badge {
        border: 2px solid #000;
        box-shadow: 2px 2px 0px #000;
        border-radius: 99px;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.65rem;
        padding: 4px 10px;
    }
    .status-menunggu { background-color: #fef08a; color: #854d0e; }
    .status-dibaca { background-color: #bbf7d0; color: #166534; }
</style>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="brutal-card p-6 bg-[#fde68a] flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden fade-up">
        <div class="absolute -right-10 -bottom-10 opacity-20 pointer-events-none">
            <span class="material-symbols-outlined text-[150px]" style="font-variation-settings:'FILL' 1;">assignment</span>
        </div>
        <div class="relative z-10">
            <h2 class="text-3xl font-black text-black uppercase tracking-tight" style="text-shadow: 2px 2px 0px rgba(0,0,0,0.1);">Laporan Mingguan Guru</h2>
            <p class="font-bold text-black/70 mt-1">Pantau progres dan aktivitas mengajar Ustadz/Guru setiap pekannya.</p>
        </div>
        <div class="relative z-10 flex gap-2 w-full md:w-auto">
            {{-- Filter Form --}}
            <form action="{{ route('laporan-guru') }}" method="GET" class="flex gap-2 w-full">
                <select name="guru_id" class="brutal-input flex-1 px-4 py-2 font-bold bg-white" onchange="this.form.submit()">
                    <option value="">-- Semua Guru --</option>
                    @foreach($gurus as $guru)
                    <option value="{{ $guru->id }}" {{ request('guru_id') == $guru->id ? 'selected' : '' }}>
                        {{ $guru->name }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    {{-- Laporan Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-up fade-up-1">
        @forelse($laporans as $laporan)
        <div class="brutal-card p-6 flex flex-col h-full bg-white relative group cursor-pointer" onclick="openDetail({{ $laporan->id }})">
            
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full border-2 border-black bg-[#bfdbfe] flex items-center justify-center shadow-[2px_2px_0px_#000]">
                        <span class="material-symbols-outlined text-[16px] text-black">person</span>
                    </div>
                    <div>
                        <p class="font-black text-sm text-black truncate max-w-[120px]">{{ $laporan->guru->name }}</p>
                    </div>
                </div>
                <span class="status-badge status-{{ $laporan->status }}">
                    {{ $laporan->status }}
                </span>
            </div>

            <h3 class="text-xl font-black text-black leading-tight mb-2">{{ $laporan->judul }}</h3>
            
            <div class="inline-block border-2 border-black rounded-lg px-2 py-1 bg-gray-100 mb-3 self-start shadow-[2px_2px_0px_#000]">
                <p class="text-[10px] font-bold text-black uppercase">
                    Periode: {{ \Carbon\Carbon::parse($laporan->tanggal_awal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($laporan->tanggal_akhir)->format('d/m/Y') }}
                </p>
            </div>

            <p class="text-sm text-black/70 font-medium line-clamp-3 leading-relaxed mb-4 flex-1">
                {{ $laporan->isi_laporan }}
            </p>

            <div class="mt-auto pt-4 border-t-2 border-black border-dashed flex justify-between items-center text-[10px] font-bold text-black/50">
                <span>Dikirim: {{ $laporan->created_at->diffForHumans() }}</span>
                <span class="flex items-center gap-1 group-hover:text-primary transition-colors">
                    Baca Detail <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 brutal-card bg-gray-50 flex flex-col items-center justify-center text-center">
            <span class="material-symbols-outlined text-6xl text-black/20 mb-4" style="font-variation-settings:'FILL' 1;">assignment</span>
            <h3 class="text-2xl font-black text-black">Belum Ada Laporan</h3>
            <p class="font-bold text-black/60 mt-1">Tidak ada laporan guru yang ditemukan saat ini.</p>
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
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDetail()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl p-4">
        <div class="brutal-card bg-white overflow-hidden flex flex-col max-h-[90vh]">
            
            {{-- Modal Header --}}
            <div class="p-6 border-b-4 border-black bg-[#bfdbfe] shrink-0 flex justify-between items-start">
                <div>
                    <h3 class="text-2xl font-black text-black mb-1 uppercase tracking-tight">Detail Laporan</h3>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full border-2 border-black bg-white flex items-center justify-center">
                            <span class="material-symbols-outlined text-[12px] text-black">person</span>
                        </div>
                        <p class="font-bold text-sm text-black" id="detailGuruName">-</p>
                    </div>
                </div>
                <button type="button" onclick="closeDetail()" class="w-10 h-10 border-2 border-black bg-white shadow-[2px_2px_0px_#000] flex items-center justify-center rounded-lg hover:-translate-y-1 hover:shadow-[4px_4px_0px_#000] transition-all">
                    <span class="material-symbols-outlined text-[24px]">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto space-y-6 flex-1 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8cmVjdCB3aWR0aD0iOCIgaGVpZ2h0PSI4IiBmaWxsPSIjZmZmIj48L3JlY3Q+CjxjaXJjbGUgY3g9IjMiIGN5PSIzIiByPSIxIiBmaWxsPSIjZTBlMGUwIj48L2NpcmNsZT4KPC9zdmc+')]">
                
                <div class="bg-white border-2 border-black shadow-[4px_4px_0px_#000] rounded-xl p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-xl font-black text-black" id="detailJudul">-</h4>
                        <span class="status-badge status-dibaca">Dibaca</span>
                    </div>
                    <div class="inline-block border-2 border-black rounded-lg px-2 py-1 bg-[#fef08a] mb-4">
                        <p class="text-[10px] font-black text-black uppercase" id="detailPeriode">
                            Periode: -
                        </p>
                    </div>
                    <div class="border-t-2 border-black border-dashed pt-4">
                        <p class="text-sm font-medium text-black leading-relaxed whitespace-pre-wrap" id="detailIsi">-</p>
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
        document.getElementById('detailJudul').textContent = "Memuat...";
        document.getElementById('detailIsi').textContent = "Sedang mengambil data laporan...";
        
        fetch(`/admin/laporan-guru/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('detailGuruName').textContent = data.guru.name;
                document.getElementById('detailJudul').textContent = data.judul;
                
                // Format dates safely
                const tglAwal = new Date(data.tanggal_awal).toLocaleDateString('id-ID');
                const tglAkhir = new Date(data.tanggal_akhir).toLocaleDateString('id-ID');
                document.getElementById('detailPeriode').textContent = `Periode: ${tglAwal} - ${tglAkhir}`;
                
                document.getElementById('detailIsi').textContent = data.isi_laporan;

                // Mark the badge as read locally in the UI to sync
                const cardBadge = document.querySelector(`[onclick="openDetail(${id})"] .status-badge`);
                if(cardBadge) {
                    cardBadge.textContent = 'dibaca';
                    cardBadge.classList.remove('status-menunggu');
                    cardBadge.classList.add('status-dibaca');
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
