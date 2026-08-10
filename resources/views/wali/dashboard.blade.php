@extends('layouts.mobile')

@section('title', 'Beranda Wali Santri')
@section('greeting_name', 'Bapak/Ibu ' . explode(' ', Auth::user()->name)[0])

@section('content')
<div x-data="{ showJadwalModal: false }" class="space-y-6">

@php
    $santri = $santris->first();
@endphp

@if($santri)
{{-- Child Selector Card --}}
<section class="clean-card bg-white p-4 flex items-center gap-4">
    <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 border border-outline bg-gray-50 flex items-center justify-center">
        @if($santri->foto)
            <img src="{{ asset('storage/' . $santri->foto) }}" alt="Foto" class="w-full h-full object-cover">
        @else
            <span class="material-symbols-outlined text-gray-400 text-3xl" style="font-variation-settings: 'FILL' 1;">person</span>
        @endif
    </div>
    <div class="flex-1">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-base font-bold text-on-surface leading-tight">{{ $santri->nama }}</h2>
                <p class="text-[12px] font-medium text-gray-500 mt-0.5">Kelas {{ $santri->kelas->nama ?? '-' }} - {{ $santri->kelas->julukan ?? '-' }}</p>
            </div>
            <button class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined text-sm">expand_more</span>
            </button>
        </div>
        <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-primary-container text-primary">
            <span class="material-symbols-outlined text-[14px]">mosque</span>
            <p class="text-[10px] font-bold">Halaqoh {{ $santri->halaqoh->nama ?? '-' }}</p>
        </div>
    </div>
</section>
@endif

{{-- Quick Stats --}}
<section class="grid grid-cols-3 gap-3">
    @php
        $santriStats = $stats[$santri->id] ?? ['tagihan' => 0, 'juz' => 0, 'kehadiran' => 0];
        $tagihanColor = $santriStats['tagihan'] > 0 ? 'text-error bg-error/10' : 'text-primary bg-primary-container';
        
        $quickStats = [
            ['icon'=>'how_to_reg',    'colorClass'=>'text-primary bg-primary-container',   'label'=>'Kehadiran', 'value'=>$santriStats['kehadiran'] . '%', 'route' => route('wali.progres')],
            ['icon'=>'menu_book',     'colorClass'=>'text-info bg-info/10',                'label'=>'Hafalan',   'value'=>'Juz ' . $santriStats['juz'], 'route' => route('wali.progres')],
            ['icon'=>'payments',      'colorClass'=>$tagihanColor,                         'label'=>'Tagihan',   'value'=>'Rp ' . number_format($santriStats['tagihan'], 0, ',', '.'), 'route' => route('wali.keuangan')],
        ];
    @endphp
    @foreach($quickStats as $stat)
    <a href="{{ $stat['route'] }}" class="clean-card bg-white p-3 flex flex-col items-center text-center hover:bg-gray-50 transition-colors cursor-pointer">
        <div class="w-10 h-10 rounded-full {{ $stat['colorClass'] }} flex items-center justify-center mb-2">
            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">{{ $stat['icon'] }}</span>
        </div>
        <p class="text-[11px] font-medium text-gray-500">{{ $stat['label'] }}</p>
        <p class="text-[13px] font-bold text-on-surface mt-0.5">{{ $stat['value'] }}</p>
    </a>
    @endforeach
</section>

{{-- Quick Actions Grid --}}
<section>
    <div class="grid grid-cols-4 gap-3">
        @php
            $actions = [
                ['icon'=>'assignment_turned_in', 'label'=>'Ajukan Izin',     'route'=>'wali.izin',     'color' => 'text-orange-500', 'bg' => 'bg-orange-50'],
                ['icon'=>'account_balance_wallet','label'=>'Bayar Tagihan',   'route'=>'wali.keuangan', 'color' => 'text-emerald-500', 'bg' => 'bg-emerald-50'],
                ['icon'=>'forum',                'label'=>'Chat Musyrif',     'route'=>'wali.chat',     'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
                ['icon'=>'analytics',            'label'=>'Lihat Rapor',      'route'=>'wali.progres',  'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
            ];
        @endphp
        @foreach($actions as $action)
        <a href="{{ route($action['route']) }}" class="flex flex-col items-center gap-2 group cursor-pointer">
            <div class="w-14 h-14 rounded-[14px] {{ $action['bg'] }} {{ $action['color'] }} flex items-center justify-center border border-gray-100 shadow-sm group-hover:scale-105 transition-transform duration-200">
                <span class="material-symbols-outlined text-[24px]">{{ $action['icon'] }}</span>
            </div>
            <span class="text-[11px] font-semibold text-center text-gray-600 leading-tight">{{ $action['label'] }}</span>
        </a>
        @endforeach
    </div>
</section>

{{-- Today's Schedule --}}
<section class="space-y-3">
    <div class="flex justify-between items-center">
        <h3 class="text-[15px] font-bold text-on-surface">Jadwal Hari Ini</h3>
        <button @click="showJadwalModal = true" class="text-[12px] text-primary font-bold hover:underline focus:outline-none">Lihat Semua</button>
    </div>
    <div class="flex gap-3 overflow-x-auto hide-scrollbar pb-2 -mx-5 px-5">
        @forelse($jadwals as $jadwal)
        @php
            $colors = [
                0 => 'bg-blue-50 text-blue-600 border-blue-100',
                1 => 'bg-orange-50 text-orange-600 border-orange-100',
                2 => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            ];
            $colorClass = $colors[$loop->index % 3];
        @endphp
        <div class="clean-card min-w-[140px] p-3 rounded-xl border {{ $colorClass }} flex-shrink-0 shadow-none">
            <div class="flex items-center gap-1.5 mb-1.5">
                <span class="material-symbols-outlined text-[14px]">schedule</span>
                <p class="text-[11px] font-bold">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</p>
            </div>
            <p class="text-[13px] font-bold truncate text-on-surface">{{ $jadwal->mapel->nama ?? 'Pelajaran' }}</p>
            <p class="text-[11px] font-medium mt-0.5 opacity-80">{{ $jadwal->ruang ?? 'Ruang Kelas' }}</p>
        </div>
        @empty
        <div class="p-4 text-sm font-medium text-gray-400 italic w-full text-center border border-dashed border-gray-200 rounded-xl bg-gray-50">
            Tidak ada jadwal untuk hari ini.
        </div>
        @endforelse
    </div>
</section>

{{-- Recent Notifications --}}
<section class="space-y-3">
    <h3 class="text-[15px] font-bold text-on-surface">Notifikasi Terbaru</h3>
    <div class="space-y-2.5">
        @forelse($notifs as $notif)
        <div class="clean-card bg-white p-3 rounded-xl flex gap-3 items-start hover:bg-gray-50 transition-colors cursor-pointer {{ !$notif['read'] ? 'border-l-4 border-l-primary' : '' }}">
            <div class="w-9 h-9 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">{{ $notif['icon'] }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-start gap-2">
                    <h4 class="text-[13px] font-bold text-on-surface leading-tight">{{ $notif['title'] }}</h4>
                    <span class="text-[10px] font-medium text-gray-400 flex-shrink-0">{{ $notif['time'] }}</span>
                </div>
                <p class="text-[12px] font-medium text-gray-500 mt-1 leading-relaxed line-clamp-2">{{ $notif['desc'] }}</p>
            </div>
            @if(!$notif['read'])
            <div class="w-2.5 h-2.5 rounded-full bg-primary flex-shrink-0 mt-1"></div>
            @endif
        </div>
        @empty
        <div class="p-5 text-center border border-dashed border-gray-200 rounded-xl bg-gray-50">
            <span class="material-symbols-outlined text-gray-300 text-3xl mb-1">notifications_off</span>
            <p class="text-[13px] font-medium text-gray-400">Belum ada notifikasi baru.</p>
        </div>
        @endforelse
    </div>
</section>

{{-- Hafalan Quick Summary Card --}}
<section class="clean-card bg-primary-container p-5 border-none">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-[15px] font-bold text-primary flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings: 'FILL' 1;">menu_book</span>
            Progress Hafalan
        </h3>
        <a href="{{ route('wali.progres') }}" class="text-[11px] text-primary font-bold hover:underline bg-white/50 px-2.5 py-1 rounded-full">Detail →</a>
    </div>
    <div class="flex items-center gap-4 bg-white/60 p-3.5 rounded-xl">
        <div class="relative w-16 h-16 flex-shrink-0">
            <svg class="w-full h-full" viewBox="0 0 100 100">
                <circle class="stroke-current text-white" cx="50" cy="50" r="40" fill="transparent" stroke-width="8"/>
                <circle class="stroke-current text-primary" cx="50" cy="50" r="40" fill="transparent" stroke-width="8"
                    stroke-linecap="round"
                    stroke-dasharray="251.2"
                    stroke-dashoffset="{{ 251.2 - (251.2 * min(100, ($santriStats['juz'] / 30) * 100) / 100) }}"
                    transform="rotate(-90 50 50)"/>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-xl font-bold text-primary leading-none">{{ $santriStats['juz'] }}</span>
                <span class="text-[8px] font-bold text-primary/70">JUZ</span>
            </div>
        </div>
        <div class="flex-1">
            <p class="text-[14px] font-bold text-on-surface">Target: 30 Juz</p>
            <p class="text-[12px] font-medium text-on-surface-variant mb-2.5 mt-0.5">Telah menyelesaikan Juz {{ $santriStats['juz'] }}</p>
            @php
                $progressPercent = min(100, ($santriStats['juz'] / 30) * 100);
            @endphp
            <div class="w-full bg-white h-2.5 rounded-full overflow-hidden shadow-inner">
                <div class="bg-primary h-full rounded-full transition-all duration-700" style="width: {{ $progressPercent }}%"></div>
            </div>
            <p class="text-[10px] font-bold text-primary mt-1.5 text-right">{{ round($progressPercent) }}% selesai</p>
        </div>
    </div>
</section>

{{-- Modal Semua Jadwal (Bottom Sheet) --}}
<div x-show="showJadwalModal" class="fixed inset-0 z-[100] flex flex-col justify-end" style="display: none;">
    {{-- Backdrop --}}
    <div x-show="showJadwalModal" x-transition.opacity class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="showJadwalModal = false"></div>
    
    {{-- Content --}}
    <div x-show="showJadwalModal" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="relative bg-white rounded-t-3xl shadow-2xl flex flex-col max-h-[85vh]">
        
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h3 class="font-bold text-lg text-on-surface">Jadwal Seminggu</h3>
            <button @click="showJadwalModal = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors focus:outline-none">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-5 space-y-6 bg-gray-50/50">
            @if(isset($jadwalSeminggu) && $jadwalSeminggu->count() > 0)
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                    @if(isset($jadwalSeminggu[$hari]) && $jadwalSeminggu[$hari]->count() > 0)
                        <div>
                            <h4 class="font-bold text-primary mb-3 sticky top-0 bg-gray-50/90 backdrop-blur-sm py-1 z-10 border-b border-gray-200">{{ $hari }}</h4>
                            <div class="space-y-3">
                                @foreach($jadwalSeminggu[$hari] as $item)
                                    <div class="clean-card p-3 rounded-xl bg-white border border-gray-100 flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex flex-col items-center justify-center flex-shrink-0 text-primary">
                                            <span class="text-[10px] font-bold">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}</span>
                                            <span class="text-[10px] opacity-70">s/d</span>
                                            <span class="text-[10px] font-bold">{{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-[13px] text-on-surface truncate">{{ $item->mapel->nama ?? 'Pelajaran' }}</p>
                                            <p class="text-[11px] text-gray-500 flex items-center gap-1 mt-0.5">
                                                <span class="material-symbols-outlined text-[14px]">meeting_room</span>
                                                {{ $item->ruang ?? 'Ruang Kelas' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center py-10">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">event_busy</span>
                    <p class="text-sm font-medium text-gray-400">Belum ada jadwal yang tersedia.</p>
                </div>
            @endif
        </div>
    </div>
</div>

</div>
@endsection

@push('scripts')
<script>
    // Glass card hover effect
    document.querySelectorAll('.glass-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.borderColor = 'rgba(27, 107, 81, 0.3)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.borderColor = 'rgba(255, 255, 255, 0.2)';
        });
    });
</script>
@endpush
