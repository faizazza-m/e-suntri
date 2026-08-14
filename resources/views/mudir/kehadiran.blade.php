@extends('layouts.mudir')

@section('title', 'Laporan Kehadiran — SUNTRI')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 fade-in-up">
    <div>
        <h2 class="text-2xl font-bold text-primary">Laporan Kehadiran</h2>
        <p class="text-sm text-on-surface-variant">Data absensi dan kehadiran santri.</p>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6 fade-in-up delay-1">
    @php
        $cards = [
            ['title' => 'Hadir', 'val' => $stats['hadir'], 'color' => 'bg-emerald-100 text-emerald-800'],
            ['title' => 'Sakit', 'val' => $stats['sakit'], 'color' => 'bg-rose-100 text-rose-800'],
            ['title' => 'Izin',  'val' => $stats['izin'],  'color' => 'bg-orange-100 text-orange-800'],
            ['title' => 'Alpa',  'val' => $stats['alpa'],  'color' => 'bg-gray-200 text-gray-800'],
            ['title' => 'Total', 'val' => $stats['total'], 'color' => 'bg-blue-100 text-blue-800'],
        ];
    @endphp
    @foreach($cards as $c)
    <div class="glassmorphism p-4 rounded-xl border border-white/20 shadow-sm text-center">
        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">{{ $c['title'] }}</p>
        <p class="text-2xl font-bold mt-1 {{ explode(' ', $c['color'])[1] }}">{{ $c['val'] }}</p>
    </div>
    @endforeach
</div>

<div class="glassmorphism rounded-2xl p-6 border border-white/20 shadow-sm fade-in-up delay-2">
    <form method="GET" class="flex gap-2 mb-4">
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-lg border-outline-variant text-sm px-3 py-2">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-lg border-outline-variant text-sm px-3 py-2">
        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold">Filter</button>
        <a href="{{ route('mudir.kehadiran') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-bold">Reset</a>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-outline-variant/30">
                    <th class="p-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                    <th class="p-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Santri</th>
                    <th class="p-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kelas</th>
                    <th class="p-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                    <th class="p-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kehadiran as $k)
                <tr class="border-b border-outline-variant/10 hover:bg-surface-container-low transition-colors">
                    <td class="p-3 text-sm">{{ \Carbon\Carbon::parse($k->tanggal)->locale('id')->isoFormat('D MMM YYYY') }}</td>
                    <td class="p-3 text-sm font-medium">{{ $k->santri->nama ?? '—' }}</td>
                    <td class="p-3 text-sm">{{ $k->santri->kelas->nama ?? '—' }}</td>
                    <td class="p-3">
                        @php
                            $badge = [
                                'hadir' => 'bg-emerald-100 text-emerald-800',
                                'sakit' => 'bg-rose-100 text-rose-800',
                                'izin' => 'bg-orange-100 text-orange-800',
                                'alpa' => 'bg-gray-200 text-gray-800'
                            ][$k->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase {{ $badge }}">{{ $k->status }}</span>
                    </td>
                    <td class="p-3 text-xs text-on-surface-variant">{{ $k->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-4 text-center text-sm text-on-surface-variant">Tidak ada data kehadiran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $kehadiran->links() }}
    </div>
</div>
@endsection
