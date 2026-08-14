@extends('layouts.mudir')

@section('title', 'Laporan Keuangan — SUNTRI')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 fade-in-up">
    <div>
        <h2 class="text-2xl font-bold text-primary">Laporan Keuangan</h2>
        <p class="text-sm text-on-surface-variant">Ringkasan tagihan dan pembayaran santri.</p>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 fade-in-up delay-1">
    @php
        $cards = [
            ['title'=>'Total Tagihan', 'val'=>$stats['total_tagihan'], 'color'=>'text-blue-600'],
            ['title'=>'Sudah Lunas',   'val'=>$stats['sudah_dibayar'], 'color'=>'text-emerald-600'],
            ['title'=>'Total Tunggakan','val'=>$stats['belum_dibayar'],'color'=>'text-error'],
            ['title'=>'Jatuh Tempo',   'val'=>$stats['jatuh_tempo'],   'color'=>'text-orange-600'],
        ];
    @endphp
    @foreach($cards as $c)
    <div class="glassmorphism p-5 rounded-2xl border border-white/20 shadow-sm text-center">
        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">{{ $c['title'] }}</p>
        <p class="text-xl font-bold mt-1 {{ $c['color'] }}">Rp {{ number_format($c['val'], 0, ',', '.') }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 fade-in-up delay-2">
    {{-- Riwayat Pembayaran Terbaru --}}
    <div class="glassmorphism rounded-2xl p-6 border border-white/20 shadow-sm">
        <h3 class="font-bold text-lg text-primary mb-4">Pembayaran Terbaru</h3>
        <div class="space-y-3">
            @forelse($riwayatPembayaran as $p)
            <div class="p-3 bg-white/60 rounded-xl border border-outline-variant/20 flex justify-between items-center">
                <div>
                    <p class="text-sm font-bold text-on-surface">{{ $p->santri->nama ?? '—' }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ $p->tagihan->jenis->nama ?? 'Tagihan' }} • {{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-emerald-600">Rp {{ number_format($p->nominal_bayar, 0, ',', '.') }}</p>
                    <p class="text-[9px] uppercase tracking-wider text-gray-500">{{ $p->metode }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-on-surface-variant py-4 text-center">Belum ada pembayaran masuk.</p>
            @endforelse
        </div>
    </div>

    {{-- Daftar Tagihan Aktif --}}
    <div class="glassmorphism rounded-2xl p-6 border border-white/20 shadow-sm">
        <h3 class="font-bold text-lg text-orange-600 mb-4">Tagihan ({{ $stats['count_belum_dibayar'] }} Belum Lunas)</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant/30">
                        <th class="p-2 text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Santri</th>
                        <th class="p-2 text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Tagihan</th>
                        <th class="p-2 text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihans as $t)
                    <tr class="border-b border-outline-variant/10">
                        <td class="p-2 text-xs font-medium">{{ $t->santri->nama ?? '—' }}</td>
                        <td class="p-2 text-xs">
                            {{ $t->jenis->nama ?? 'Tagihan' }}<br>
                            <span class="font-bold">Rp {{ number_format($t->nominal, 0, ',', '.') }}</span>
                        </td>
                        <td class="p-2">
                            @if($t->status == 'lunas')
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-800 text-[9px] font-bold rounded-lg uppercase">Lunas</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-[9px] font-bold rounded-lg uppercase">Belum Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="p-4 text-center text-xs text-on-surface-variant">Tidak ada data tagihan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-xs">
            {{ $tagihans->links() }}
        </div>
    </div>
</div>
@endsection
