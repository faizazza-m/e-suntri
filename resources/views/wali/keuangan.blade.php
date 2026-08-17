@extends('layouts.mobile')

@section('title', 'Keuangan Santri')
@section('greeting_name', 'Bapak/Ibu ' . explode(' ', auth()->user()->name)[0])

@section('content')
<div class="px-4 pb-24 pt-4 space-y-6">

    <!-- Header / Total Tunggakan -->
    <div class="clean-card p-5 relative overflow-hidden bg-primary text-white border-0 shadow-lg">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
        <div class="absolute -left-4 -bottom-4 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
        
        <div class="relative z-10 flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings:'FILL' 1;">account_balance_wallet</span>
            </div>
            <p class="text-sm text-white/80 font-medium mb-1">Total Tunggakan</p>
            <h2 class="text-3xl font-bold tracking-tight">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</h2>
            <p class="text-[11px] text-white/60 mt-2">Untuk Ananda {{ $activeSantri->nama ?? 'Santri' }}</p>
        </div>
    </div>

    <!-- Tagihan Belum Lunas -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-on-surface">Menunggu Pembayaran</h3>
            <span class="text-[10px] font-bold bg-error/10 text-error px-2 py-0.5 rounded-full">{{ $tagihanBelumLunas->count() }} Tagihan</span>
        </div>

        <div class="space-y-3">
            @forelse($tagihanBelumLunas as $tagihan)
            <div class="clean-card p-4 flex gap-4 items-start">
                <div class="w-10 h-10 rounded-xl bg-error/10 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-error" style="font-variation-settings:'FILL' 1;">receipt_long</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-on-surface truncate">{{ $tagihan->jenis->nama }}</p>
                    <p class="text-xs text-on-surface-variant mb-1">{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('F Y') }}</p>
                    <p class="text-[10px] font-medium text-error bg-error/5 inline-flex px-2 py-0.5 rounded border border-error/10">
                        Jatuh Tempo: {{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d M Y') }}
                    </p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-sm font-bold text-error">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</p>
                    <a href="https://wa.me/628000000000?text=Halo%20Admin%20SUNTRI,%20saya%20ingin%20membayar%20tagihan%20{{ $tagihan->jenis->nama }}%20untuk%20anak%20saya%20{{ $activeSantri->nama }}" target="_blank" class="inline-block mt-2 text-[10px] bg-primary text-white px-3 py-1 rounded-full font-medium hover:bg-primary-container hover:text-on-primary-container transition-colors">
                        Bayar
                    </a>
                </div>
            </div>
            @empty
            <div class="clean-card p-6 flex flex-col items-center justify-center text-center">
                <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-success text-xl" style="font-variation-settings:'FILL' 1;">check_circle</span>
                </div>
                <p class="text-sm font-bold text-on-surface">Alhamdulillah</p>
                <p class="text-xs text-on-surface-variant">Tidak ada tagihan yang belum dibayar.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    @if($tagihanLunas->count() > 0)
    <div class="pt-2">
        <h3 class="text-sm font-bold text-on-surface mb-3">Riwayat Pembayaran</h3>
        <div class="space-y-3">
            @foreach($tagihanLunas as $lunas)
            <div class="clean-card p-3 px-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-success/10 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-success text-sm" style="font-variation-settings:'FILL' 1;">task_alt</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-on-surface truncate">{{ $lunas->jenis->nama }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ \Carbon\Carbon::parse($lunas->jatuh_tempo)->translatedFormat('F Y') }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs font-bold text-success">Lunas</p>
                    <p class="text-[10px] text-on-surface-variant">Rp {{ number_format($lunas->nominal, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
