@extends('layouts.mobile')

@section('title', 'Pusat Bantuan & Kontak')
@section('greeting_name', 'Bapak/Ibu ' . explode(' ', auth()->user()->name)[0])

@section('content')

{{-- Header Info --}}
<section class="mb-6 text-center">
    <div class="w-16 h-16 bg-primary-container rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
        <span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings: 'FILL' 1;">support_agent</span>
    </div>
    <h2 class="text-[18px] font-bold text-on-surface mb-1">Hubungi Pembimbing</h2>
    <p class="text-[13px] text-gray-500 px-4">Silakan hubungi pihak terkait melalui WhatsApp untuk berdiskusi tentang santri.</p>
</section>

{{-- Contact List --}}
<section class="space-y-4">
    @forelse($contacts as $contact)
    <div class="clean-card bg-white p-5 border border-gray-100 hover:border-{{ $contact['color'] }}-300 transition-colors shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-{{ $contact['color'] }}-50 text-{{ $contact['color'] }}-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1;">{{ $contact['icon'] }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $contact['role'] }}</h3>
                <p class="text-[15px] font-bold text-on-surface truncate">{{ $contact['name'] }}</p>
                <div class="mt-4">
                    <a href="{{ route('wali.chat.room', $contact['id']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary/10 text-primary font-bold text-[12px] hover:bg-primary/20 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">chat</span>
                        Kirim Pesan
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="p-8 text-center border border-dashed border-gray-200 rounded-2xl bg-gray-50">
        <span class="material-symbols-outlined text-gray-300 text-5xl mb-2">sentiment_dissatisfied</span>
        <p class="text-[14px] font-bold text-gray-500">Belum Ada Kontak</p>
        <p class="text-[12px] text-gray-400 mt-1.5 px-4">Data musyrif dan pembimbing belum terhubung ke santri.</p>
    </div>
    @endforelse
</section>

{{-- Info Note --}}
<div class="mt-8 p-4 bg-gray-50 rounded-xl border border-gray-100 flex gap-3 items-start shadow-inner">
    <span class="material-symbols-outlined text-gray-400 text-[20px]">info</span>
    <p class="text-[11px] text-gray-500 leading-relaxed font-medium">
        <strong class="text-gray-600">Jam Operasional:</strong> Harap menghubungi pembimbing pada waktu yang wajar (08:00 - 20:00). Pertanyaan mengenai status pembayaran dan aplikasi mohon ditujukan langsung ke Pusat Informasi (Admin).
    </p>
</div>

@endsection
