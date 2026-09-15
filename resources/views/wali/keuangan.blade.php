@extends('layouts.mobile')

@section('title', 'Keuangan Santri')
@section('greeting_name', 'Bapak/Ibu ' . explode(' ', auth()->user()->name)[0])

@section('content')
<div class="px-4 pb-24 pt-4 space-y-6" x-data="{ showUploadModal: false, selectedTagihanId: null, selectedTagihanName: '', nominal: 0 }">

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
                    <button type="button" @click="showUploadModal = true; selectedTagihanId = {{ $tagihan->id }}; selectedTagihanName = '{{ addslashes($tagihan->jenis->nama) }}'; nominal = {{ $tagihan->nominal }}" class="inline-block mt-2 text-[10px] bg-primary text-white px-3 py-1 rounded-full font-medium hover:bg-primary-container hover:text-on-primary-container transition-colors">
                        Upload Bukti
                    </button>
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

    <!-- Modal Upload Bukti -->
    <div x-show="showUploadModal" style="display: none;" class="fixed inset-0 z-50 flex items-end justify-center sm:items-center p-4 pb-20">
        <div x-show="showUploadModal" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showUploadModal = false"></div>
        
        <div x-show="showUploadModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-4"
             class="relative bg-white rounded-t-2xl sm:rounded-2xl w-full max-w-md p-6 shadow-xl z-10"
             @click.stop>
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-on-surface">Upload Bukti Transfer</h3>
                <button @click="showUploadModal = false" class="text-on-surface-variant hover:text-error transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form action="{{ route('wali.keuangan.bayar') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="tagihan_id" x-model="selectedTagihanId">
                <input type="hidden" name="nominal" x-model="nominal">
                <input type="hidden" name="metode" value="transfer">
                
                <div class="bg-primary/5 p-3 rounded-xl border border-primary/10 mb-4">
                    <p class="text-xs text-on-surface-variant font-medium">Tagihan</p>
                    <p class="text-sm font-bold text-primary truncate" x-text="selectedTagihanName"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-on-surface mb-1">Bukti Transfer (JPG/PNG/PDF) <span class="text-error">*</span></label>
                    <input type="file" name="bukti_foto" required accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-on-surface mb-1">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="2" class="w-full px-4 py-2 text-sm rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Masukkan catatan..."></textarea>
                </div>
                
                <button type="submit" class="w-full py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-container transition-colors">
                    Kirim Bukti Pembayaran
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
