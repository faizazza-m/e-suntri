@extends('layouts.guru')
@section('title', 'Laporan Mingguan')

@section('content')
<div class="space-y-6">

    {{-- Header & Add Button --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 fade-up">
        <div>
            <h2 class="text-2xl font-black text-on-surface tracking-tight">Laporan Mingguan</h2>
            <p class="text-sm text-on-surface-variant mt-1">Sampaikan laporan progres mengajar Anda setiap pekannya.</p>
        </div>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="w-full sm:w-auto px-5 py-2.5 bg-primary hover:bg-primary-container text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[20px]">add</span> Buat Laporan Baru
        </button>
    </div>

    {{-- Laporan List --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 fade-up fade-up-1">
        @forelse($laporans as $laporan)
        <div class="glass-card rounded-2xl p-5 hover:shadow-lg transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-full -z-10 group-hover:scale-110 transition-transform"></div>
            
            <div class="flex justify-between items-start mb-3">
                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg 
                    {{ $laporan->status === 'dibaca' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $laporan->status }}
                </span>
                <span class="text-xs text-on-surface-variant font-medium">
                    {{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y') }}
                </span>
            </div>

            <h3 class="font-bold text-on-surface text-lg leading-tight mb-2">{{ $laporan->judul }}</h3>
            <p class="text-xs text-on-surface-variant font-medium mb-3">
                Periode: {{ \Carbon\Carbon::parse($laporan->tanggal_awal)->format('d/m') }} - {{ \Carbon\Carbon::parse($laporan->tanggal_akhir)->format('d/m/Y') }}
            </p>
            
            <p class="text-sm text-on-surface-variant line-clamp-3 leading-relaxed">
                {{ $laporan->isi_laporan }}
            </p>
        </div>
        @empty
        <div class="col-span-full py-12 flex flex-col items-center justify-center text-center glass-card rounded-3xl border-dashed">
            <span class="material-symbols-outlined text-5xl text-outline-variant mb-3" style="font-variation-settings:'FILL' 1;">description</span>
            <h3 class="text-lg font-bold text-on-surface">Belum Ada Laporan</h3>
            <p class="text-sm text-on-surface-variant mt-1">Anda belum membuat laporan mingguan apa pun.</p>
        </div>
        @endforelse
    </div>

</div>

{{-- Modal Tambah --}}
<div id="modalTambah" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('modalTambah').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-4">
        <form action="{{ route('guru.laporan.store') }}" method="POST" class="bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            @csrf
            
            {{-- Modal Header --}}
            <div class="p-5 border-b border-outline-variant/30 flex justify-between items-center bg-surface shrink-0">
                <h3 class="text-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">add_circle</span> Buat Laporan Baru
                </h3>
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-black/5 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-5 overflow-y-auto space-y-4 flex-1">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" required class="w-full rounded-xl border-outline-variant bg-surface focus:ring-primary focus:border-primary transition-colors text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" required class="w-full rounded-xl border-outline-variant bg-surface focus:ring-primary focus:border-primary transition-colors text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Judul Laporan</label>
                    <input type="text" name="judul" required placeholder="Contoh: Laporan Mengajar Minggu ke-1" class="w-full rounded-xl border-outline-variant bg-surface focus:ring-primary focus:border-primary transition-colors text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1.5">Isi Laporan</label>
                    <textarea name="isi_laporan" required rows="5" placeholder="Tuliskan detail aktivitas, progres santri, dan kendala (jika ada)..." class="w-full rounded-xl border-outline-variant bg-surface focus:ring-primary focus:border-primary transition-colors text-sm resize-none"></textarea>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-5 border-t border-outline-variant/30 flex justify-end gap-3 bg-surface shrink-0">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-5 py-2 rounded-xl font-bold text-on-surface-variant hover:bg-black/5 transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 rounded-xl font-bold bg-primary hover:bg-primary-container text-white shadow-md transition-colors text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">send</span> Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
