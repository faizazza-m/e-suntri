@extends('layouts.app')

@section('title', 'Dashboard Bendahara')
@section('meta_description', 'Ringkasan keuangan dan status tagihan santri SUNTRI.')

@section('content')

{{-- Welcome Section --}}
<div class="relative overflow-hidden bg-primary rounded-3xl p-8 mb-8 text-on-primary shadow-xl glassmorphism border border-white/20 fade-in-up">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-bold mb-2 tracking-tight">Ahlan wa Sahlan, Bendahara</h2>
            <p class="text-on-primary/80 max-w-xl text-sm leading-relaxed">
                Pantau sirkulasi keuangan, pemasukan, dan tagihan santri dengan mudah melalui dashboard ini.
            </p>
        </div>
        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center shrink-0 border border-white/30 backdrop-blur-sm">
            <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">account_balance_wallet</span>
        </div>
    </div>
</div>

{{-- Quick Stats --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    {{-- Pemasukan --}}
    <div class="bg-surface-bright rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex items-center gap-4 hover:shadow-md transition-shadow fade-in-up" style="animation-delay: 0.1s;">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 bg-green-100 text-green-600">
            <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">trending_up</span>
        </div>
        <div>
            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Pemasukan Bulan Ini</p>
            <h3 class="text-2xl font-bold text-on-surface">Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Tunggakan --}}
    <div class="bg-surface-bright rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex items-center gap-4 hover:shadow-md transition-shadow fade-in-up" style="animation-delay: 0.2s;">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 bg-red-100 text-red-600">
            <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">account_balance</span>
        </div>
        <div>
            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Total Tunggakan</p>
            <h3 class="text-2xl font-bold text-on-surface">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Santri Nunggak --}}
    <div class="bg-surface-bright rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex items-center gap-4 hover:shadow-md transition-shadow fade-in-up" style="animation-delay: 0.3s;">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 bg-orange-100 text-orange-600">
            <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">group_off</span>
        </div>
        <div>
            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Santri Menunggak</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $jumlahSantriNunggak }} Santri</h3>
        </div>
    </div>

    {{-- PPDB --}}
    <div class="bg-surface-bright rounded-3xl p-6 shadow-sm border border-outline-variant/30 flex items-center gap-4 hover:shadow-md transition-shadow fade-in-up" style="animation-delay: 0.4s;">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 bg-blue-100 text-blue-600">
            <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">person_add</span>
        </div>
        <div>
            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">PPDB (Pending)</p>
            <h3 class="text-2xl font-bold text-on-surface">{{ $pendaftarBaru }} Pendaftar</h3>
        </div>
    </div>

</div>

{{-- Main Content Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Riwayat Pembayaran Terbaru --}}
    <div class="lg:col-span-2 bg-surface-bright rounded-3xl p-6 shadow-sm border border-outline-variant/30 fade-in-up" style="animation-delay: 0.5s;">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">history</span>
                Pembayaran Terbaru
            </h3>
            <a href="{{ route('bendahara.keuangan') }}" class="text-sm font-bold text-primary hover:underline">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant/30 text-sm text-on-surface-variant">
                        <th class="py-3 px-4 font-bold">Tanggal</th>
                        <th class="py-3 px-4 font-bold">Santri</th>
                        <th class="py-3 px-4 font-bold">Tagihan</th>
                        <th class="py-3 px-4 font-bold">Nominal</th>
                        <th class="py-3 px-4 font-bold">Metode</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaranTerakhir as $p)
                    <tr class="border-b border-outline-variant/10 hover:bg-surface-container-lowest transition-colors">
                        <td class="py-4 px-4 text-sm">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y') }}</td>
                        <td class="py-4 px-4">
                            <div class="font-bold text-sm text-on-surface">{{ $p->santri->nama }}</div>
                        </td>
                        <td class="py-4 px-4 text-sm">{{ $p->tagihan->jenis->nama }}</td>
                        <td class="py-4 px-4 font-bold text-green-600">Rp {{ number_format($p->nominal_bayar, 0, ',', '.') }}</td>
                        <td class="py-4 px-4 text-sm capitalize">
                            <span class="px-2 py-1 rounded bg-blue-50 text-blue-700 text-xs font-bold">{{ $p->metode }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-on-surface-variant text-sm">Belum ada riwayat pembayaran terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Aksi Cepat --}}
    <div class="bg-surface-bright rounded-3xl p-6 shadow-sm border border-outline-variant/30 fade-in-up" style="animation-delay: 0.6s;">
        <h3 class="text-lg font-bold text-on-surface flex items-center gap-2 mb-6">
            <span class="material-symbols-outlined text-primary">bolt</span>
            Aksi Cepat
        </h3>
        
        <div class="space-y-4">
            <a href="{{ route('bendahara.keuangan') }}" class="block w-full text-left p-4 rounded-2xl bg-surface-container hover:bg-primary hover:text-on-primary transition-all group shadow-sm border border-outline-variant/20">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary group-hover:text-on-primary">add_circle</span>
                        <span class="font-bold text-sm">Buat Tagihan Baru</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-on-primary">chevron_right</span>
                </div>
            </a>
            
            <a href="{{ route('bendahara.keuangan') }}" class="block w-full text-left p-4 rounded-2xl bg-surface-container hover:bg-primary hover:text-on-primary transition-all group shadow-sm border border-outline-variant/20">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary group-hover:text-on-primary">payments</span>
                        <span class="font-bold text-sm">Catat Pembayaran</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-on-primary">chevron_right</span>
                </div>
            </a>
            
            <a href="#" class="block w-full text-left p-4 rounded-2xl bg-surface-container hover:bg-primary hover:text-on-primary transition-all group shadow-sm border border-outline-variant/20">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary group-hover:text-on-primary">print</span>
                        <span class="font-bold text-sm">Cetak Rekap Bulanan</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-on-primary">chevron_right</span>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection
