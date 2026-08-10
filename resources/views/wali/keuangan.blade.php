@extends('layouts.mobile')

@section('title', 'Info Keuangan')
@section('greeting_name', 'Bapak/Ibu ' . explode(' ', auth()->user()->name)[0])

@section('content')
<div class="flex flex-col items-center justify-center min-h-[50vh] text-center pt-8">
    <div class="w-20 h-20 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-6 shadow-sm">
        <span class="material-symbols-outlined text-[36px] text-gray-400">construction</span>
    </div>
    <h2 class="text-[18px] font-bold text-on-surface mb-2">Segera Hadir</h2>
    <p class="text-[13px] text-gray-500 max-w-[200px] mx-auto leading-relaxed">Modul Keuangan sedang dalam tahap pengembangan.</p>
</div>
@endsection
