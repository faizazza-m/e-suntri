@extends('layouts.app')
@section('title', ucfirst('PAGE_NAME'))
@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
    <div class="w-20 h-20 rounded-2xl bg-primary-container/10 flex items-center justify-center mb-6">
        <span class="material-symbols-outlined text-4xl text-primary-container">construction</span>
    </div>
    <h2 class="text-2xl font-bold text-on-surface mb-2">Halaman Sedang Dikembangkan</h2>
    <p class="text-on-surface-variant">Modul ini akan segera hadir. Stay tuned!</p>
</div>
@endsection
