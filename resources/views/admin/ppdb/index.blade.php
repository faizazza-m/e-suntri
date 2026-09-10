@extends('layouts.app')
@section('title', 'Manajemen Gelombang PPDB')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-primary-container rounded-2xl shadow-lg">
                <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">date_range</span>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-primary">Manajemen Gelombang PPDB</h2>
                <p class="text-sm text-on-surface-variant">Kelola periode dan kuota pendaftaran santri baru.</p>
            </div>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.ppdb.students') }}" class="px-5 py-2.5 bg-surface text-primary border border-primary/30 text-sm font-bold rounded-xl hover:bg-primary/5 transition-all shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">group</span> Data Pendaftar
            </a>
            <button onclick="document.getElementById('modalAddBatch').classList.remove('hidden')" class="px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 transition-all shadow-lg hover:shadow-primary/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span> Tambah Gelombang
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-800 rounded-xl font-medium text-sm flex items-center gap-2 fade-in-up delay-1">
        <span class="material-symbols-outlined">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    {{-- Batches Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in-up delay-2">
        @forelse($batches as $batch)
        <div class="glassmorphism p-6 rounded-2xl border border-white/20 shadow-sm flex flex-col h-full relative group">
            
            <div class="absolute top-4 right-4">
                <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg {{ $batch->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $batch->status ? 'Aktif' : 'Tutup' }}
                </span>
            </div>

            <div class="mb-4 pr-16">
                <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg bg-blue-100 text-blue-700 mb-2 inline-block">
                    {{ $batch->unit }}
                </span>
                <h3 class="text-xl font-bold text-on-surface leading-tight">{{ $batch->name }}</h3>
            </div>

            <div class="space-y-3 mb-6 flex-1">
                <div class="flex items-center gap-2 text-sm text-on-surface-variant">
                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                    <span>Mulai: {{ \Carbon\Carbon::parse($batch->start_date)->format('d M Y') }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-on-surface-variant">
                    <span class="material-symbols-outlined text-[16px]">event</span>
                    <span>Selesai: {{ \Carbon\Carbon::parse($batch->end_date)->format('d M Y') }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-on-surface-variant">
                    <span class="material-symbols-outlined text-[16px]">groups</span>
                    <span>Kuota: {{ $batch->quota }} Santri</span>
                </div>
                @if($batch->registration_link)
                <div class="flex items-center gap-2 text-sm text-primary font-bold mt-2 pt-2 border-t border-outline-variant/20">
                    <span class="material-symbols-outlined text-[16px]">link</span>
                    <a href="{{ $batch->registration_link }}" target="_blank" class="hover:underline truncate">{{ $batch->registration_link }}</a>
                </div>
                @endif
            </div>

            <div class="mt-auto pt-4 border-t border-outline-variant/30 flex justify-between items-center">
                <span class="text-xs font-bold text-primary">{{ $batch->students()->count() }} Pendaftar</span>
                <a href="{{ route('admin.ppdb.students', ['batch_id' => $batch->id]) }}" class="text-xs font-bold text-primary flex items-center gap-1 hover:underline">
                    Lihat Pendaftar <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 glassmorphism rounded-3xl border border-dashed border-outline-variant/50 flex flex-col items-center justify-center text-center">
            <span class="material-symbols-outlined text-6xl text-outline-variant mb-4" style="font-variation-settings:'FILL' 1;">inbox</span>
            <h3 class="text-xl font-bold text-on-surface">Belum Ada Gelombang</h3>
            <p class="text-sm text-on-surface-variant mt-1">Silakan tambah gelombang pendaftaran baru.</p>
        </div>
        @endforelse
    </div>

</div>

{{-- Modal Tambah Gelombang --}}
<div id="modalAddBatch" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('modalAddBatch').classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-4">
        <div class="bg-surface rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="p-6 border-b border-outline-variant/30 bg-white/60 shrink-0 flex justify-between items-center">
                <h3 class="text-xl font-bold text-on-surface">Tambah Gelombang PPDB</h3>
                <button type="button" onclick="document.getElementById('modalAddBatch').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-black/5 transition-colors">
                    <span class="material-symbols-outlined text-[20px] text-on-surface-variant">close</span>
                </button>
            </div>

            <div class="p-6 overflow-y-auto bg-surface-container-low">
                <form action="{{ route('admin.ppdb.batch.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-1">Unit Pendidikan</label>
                        <select name="unit" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-white focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="TK">TK Islam ERQIU</option>
                            <option value="SD">SD Islam ERQIU</option>
                            <option value="MTRQ 1" selected>MTRQ 1 (Bogor)</option>
                            <option value="MTRQ 2">MTRQ 2 (Pamijahan)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-1">Nama Gelombang</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-white focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Contoh: Gelombang 1 TA 2026/2027">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-white focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-1">Tanggal Selesai</label>
                            <input type="date" name="end_date" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-white focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-1">Kuota Santri</label>
                        <input type="number" name="quota" required min="1" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-white focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Contoh: 150">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-1">Link Pendaftaran (Opsional)</label>
                        <input type="url" name="registration_link" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-white focus:ring-2 focus:ring-primary focus:border-primary transition-all" placeholder="Contoh: https://forms.gle/xyz atau link eksternal lain">
                        <p class="text-[10px] text-on-surface-variant mt-1">Kosongkan jika tidak menggunakan form eksternal (Google Form, Typeform, dll).</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-white focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="1">Aktif (Dibuka)</option>
                            <option value="0">Tutup</option>
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('modalAddBatch').classList.add('hidden')" class="px-5 py-2.5 text-sm font-bold text-on-surface-variant hover:bg-black/5 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 transition-all shadow-md">Simpan Gelombang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
