@extends('layouts.musyrif')
@section('title', 'Rekap Data')

@section('content')
<div class="mb-5 fade-in-up">
    <h1 class="text-xl font-black text-on-surface tracking-tight flex items-center gap-2">
        <span class="material-symbols-outlined text-primary" style="font-variation-settings:'FILL' 1;">assessment</span>
        Rekap Data Hafalan Halaqoh
    </h1>
    <p class="text-sm text-on-surface-variant mt-1">Laporan rekapitulasi setoran hafalan dan jumlah halaman santri.</p>
</div>

{{-- Filter Form --}}
<div class="bg-surface rounded-2xl p-5 shadow-sm border border-outline-variant/30 mb-5 fade-in-up fade-up-1">
    <form method="GET" action="{{ route('musyrif.rekap') }}" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ $startDate }}" required class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
        </div>
        <div class="flex-1 w-full">
            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ $endDate }}" required class="w-full h-12 px-4 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit" class="flex-1 md:flex-none h-12 px-6 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">filter_alt</span> Tampilkan
            </button>
            <a href="{{ route('musyrif.rekap.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="flex-1 md:flex-none h-12 px-6 bg-secondary text-white font-bold rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">download</span> Export CSV
            </a>
        </div>
    </form>
    <div class="mt-4 flex gap-2 flex-wrap">
        @php
            $startOfWeek = now()->startOfWeek()->toDateString();
            $endOfWeek = now()->endOfWeek()->toDateString();
            $startOfMonth = now()->startOfMonth()->toDateString();
            $endOfMonth = now()->endOfMonth()->toDateString();
        @endphp
        <a href="{{ route('musyrif.rekap', ['start_date' => $startOfWeek, 'end_date' => $endOfWeek]) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">Minggu Ini</a>
        <a href="{{ route('musyrif.rekap', ['start_date' => $startOfMonth, 'end_date' => $endOfMonth]) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg border border-outline-variant text-on-surface-variant hover:bg-surface-container transition-colors">Bulan Ini</a>
    </div>
</div>

{{-- Data Table --}}
<div class="bg-surface rounded-2xl shadow-sm border border-outline-variant/30 overflow-hidden fade-in-up fade-up-2">
    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="bg-surface-container-low border-b border-outline-variant/30">
                <tr>
                    <th rowspan="2" class="py-3 px-4 text-[10px] font-black text-on-surface-variant uppercase tracking-widest text-center border-r border-outline-variant/30">No</th>
                    <th rowspan="2" class="py-3 px-4 text-[10px] font-black text-on-surface-variant uppercase tracking-widest border-r border-outline-variant/30">Santri</th>
                    <th colspan="4" class="py-2 px-4 text-[10px] font-black text-on-surface-variant uppercase tracking-widest text-center border-b border-outline-variant/30">Pencapaian Tahfizh</th>
                </tr>
                <tr>
                    <th class="py-2 px-3 text-[10px] font-bold text-on-surface-variant text-center border-r border-outline-variant/30">Total Setoran</th>
                    <th class="py-2 px-3 text-[10px] font-bold text-primary text-center border-r border-outline-variant/30">Halaman Ziyadah</th>
                    <th class="py-2 px-3 text-[10px] font-bold text-secondary text-center border-r border-outline-variant/30">Halaman Muraja'ah</th>
                    <th class="py-2 px-3 text-[10px] font-black text-on-surface text-center">Total Halaman</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($rekapData as $index => $row)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="py-3 px-4 text-sm text-center border-r border-outline-variant/30">{{ $loop->iteration }}</td>
                    <td class="py-3 px-4 border-r border-outline-variant/30">
                        <p class="text-sm font-bold text-on-surface">{{ $row->nama }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $row->nis }} &bull; {{ $row->kelas }}</p>
                    </td>
                    <td class="py-3 px-3 text-sm text-center font-bold text-on-surface-variant border-r border-outline-variant/30">{{ $row->total_setoran }}</td>
                    <td class="py-3 px-3 text-sm text-center font-bold text-primary border-r border-outline-variant/30">{{ $row->halaman_ziyadah }}</td>
                    <td class="py-3 px-3 text-sm text-center font-bold text-secondary border-r border-outline-variant/30">{{ $row->halaman_murajaah }}</td>
                    <td class="py-3 px-3 text-sm text-center font-black text-on-surface">{{ $row->total_halaman }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-on-surface-variant">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50">inbox</span>
                            <p class="font-bold">Tidak ada data santri</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
