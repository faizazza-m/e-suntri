@extends('layouts.mobile')

@section('title', 'Perkembangan Santri')
@section('greeting_name', 'Bapak/Ibu ' . explode(' ', auth()->user()->name)[0])

@section('content')

{{-- Title --}}
<section>
    <h2 class="text-[18px] font-bold text-on-surface">Perkembangan Santri</h2>
    <p class="text-[12px] font-medium text-gray-500">Update terakhir: Hari ini, 08:45 WIB</p>
</section>

{{-- Hafalan Section: Circular + Stats --}}
<section class="grid grid-cols-1 gap-4">

    {{-- Total Juz Circular Progress --}}
    <div class="clean-card bg-white p-6 flex flex-col items-center text-center">
        <h3 class="text-[11px] font-bold text-gray-500 mb-6 uppercase tracking-widest">Total Hafalan</h3>
        <div class="relative w-40 h-40 mb-6">
            <svg class="w-full h-full drop-shadow-sm" viewBox="0 0 100 100">
                <circle class="stroke-current text-gray-100" cx="50" cy="50" r="40" fill="transparent" stroke-width="8"/>
                @php
                    $percentage = min(1, $juzSelesai / max(1, $targetJuz));
                    $offset = 251.2 - (251.2 * $percentage);
                @endphp
                <circle class="stroke-current text-primary" cx="50" cy="50" r="40" fill="transparent" stroke-width="8"
                    stroke-linecap="round"
                    stroke-dasharray="251.2"
                    stroke-dashoffset="{{ $offset }}"
                    transform="rotate(-90 50 50)"
                    style="transition: stroke-dashoffset 1s ease;"/>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-4xl font-extrabold text-primary leading-none mb-1">{{ $juzSelesai }}</span>
                <span class="text-[10px] font-bold text-gray-400">DARI {{ $targetJuz }} JUZ</span>
            </div>
        </div>
        <div class="flex gap-8 items-center justify-center">
            <div class="flex items-center gap-2">
                <span class="w-3.5 h-3.5 rounded-full bg-primary inline-block shadow-sm"></span>
                <span class="text-[12px] font-bold text-on-surface">Selesai</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3.5 h-3.5 rounded-full bg-gray-100 inline-block shadow-inner border border-gray-200"></span>
                <span class="text-[12px] font-bold text-on-surface">Belum</span>
            </div>
        </div>
    </div>

    {{-- Current Target + Rapor Button --}}
    <div class="space-y-4">
        <div class="clean-card bg-white p-5">
            <div class="flex justify-between items-end mb-4">
                <div>
                    <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Target Saat Ini</h3>
                    <p class="text-[16px] font-bold text-on-surface mt-1">
                        @if($latestSetoran)
                            Juz {{ $latestSetoran->juz }} ({{ $latestSetoran->surah }})
                        @else
                            Belum Ada Setoran
                        @endif
                    </p>
                </div>
                <span class="text-[15px] font-bold text-primary">{{ round($percentage * 100) }}%</span>
            </div>
            <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden mb-5 shadow-inner">
                <div class="bg-primary h-full rounded-full transition-all duration-700" style="width: {{ $percentage * 100 }}%"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 block mb-1 uppercase">Setoran Terakhir</span>
                    <span class="text-[13px] font-bold text-on-surface truncate block">
                        {{ $latestSetoran ? $latestSetoran->surah . ': ' . $latestSetoran->ayat_dari . '-' . $latestSetoran->ayat_sampai : '-' }}
                    </span>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 block mb-1 uppercase">Nilai Terakhir</span>
                    <span class="text-[13px] font-bold text-primary">{{ $latestSetoran->nilai ?? '-' }}</span>
                </div>
            </div>
        </div>
        <button class="w-full py-3.5 rounded-[14px] text-white font-bold text-[14px] flex items-center justify-center gap-2 bg-primary hover:bg-primary-container hover:text-primary transition-colors shadow-sm">
            <span>Lihat Rapor Digital</span>
            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
        </button>
    </div>
</section>

{{-- Recent Setoran Table --}}
<section class="clean-card bg-white overflow-hidden mt-2">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="text-[15px] font-bold text-on-surface">Setoran Terkini</h3>
        <span class="text-[12px] text-primary cursor-pointer font-bold hover:underline">Lihat Semua</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Surah</th>
                    <th class="px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Nilai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($setorans as $setoran)
                @php
                    $nilaiClass = match($setoran->nilai) {
                        'Mumtaz' => 'bg-emerald-100 text-emerald-700',
                        'Jayyid Jiddan' => 'bg-blue-100 text-blue-700',
                        'Jayyid' => 'bg-gray-100 text-gray-700',
                        'Maqbul' => 'bg-orange-100 text-orange-700',
                        'Rosib' => 'bg-red-100 text-red-700',
                        default => 'bg-gray-100 text-gray-700'
                    };
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3.5">
                        <div class="flex flex-col">
                            <span class="text-[13px] font-bold text-on-surface">{{ $setoran->surah }}: {{ $setoran->ayat_dari }}-{{ $setoran->ayat_sampai }}</span>
                            <span class="text-[11px] font-medium text-gray-500 mt-0.5">Juz {{ $setoran->juz }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-[12px] font-medium text-gray-600">{{ \Carbon\Carbon::parse($setoran->created_at)->format('d M Y') }}</td>
                    <td class="px-4 py-3.5">
                        <span class="px-2.5 py-1 {{ $nilaiClass }} rounded-md text-[10px] font-bold">{{ $setoran->nilai }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center">
                        <div class="flex flex-col items-center">
                            <span class="material-symbols-outlined text-gray-300 text-3xl mb-2">history</span>
                            <span class="text-[13px] font-medium text-gray-400">Belum ada riwayat setoran.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- Attendance Heatmap + Radar Grid --}}
<section class="grid grid-cols-1 gap-4 mt-2">

    {{-- Heatmap --}}
    <div class="clean-card bg-white p-5">
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4">Konsistensi Kehadiran (28 Hari)</h3>
        <div class="grid grid-cols-7 gap-1.5 mb-4">
            @foreach($heatData as $cell)
            @php
                $color = str_replace(['bg-surface-container-highest', 'bg-primary-fixed-dim', 'bg-primary-fixed', 'bg-primary'], ['bg-gray-100', 'bg-primary/40', 'bg-primary/70', 'bg-primary'], $cell['color']);
            @endphp
            <div class="aspect-square {{ $color }} rounded-[4px] hover:scale-105 transition-transform cursor-default shadow-sm" title="{{ \Carbon\Carbon::parse($cell['date'])->format('d M') }}: {{ ucfirst($cell['status']) }}"></div>
            @endforeach
        </div>
        <div class="flex justify-between items-center text-[10px] font-bold text-gray-400">
            <span>Kurang Aktif</span>
            <div class="flex gap-1.5">
                <div class="w-3.5 h-3.5 rounded-[4px] bg-gray-100 shadow-inner"></div>
                <div class="w-3.5 h-3.5 rounded-[4px] bg-primary/40"></div>
                <div class="w-3.5 h-3.5 rounded-[4px] bg-primary/70"></div>
                <div class="w-3.5 h-3.5 rounded-[4px] bg-primary"></div>
            </div>
            <span>Sangat Aktif</span>
        </div>
    </div>

    {{-- Akhlak Radar --}}
    <div class="clean-card bg-white p-5 flex flex-col items-center">
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4 self-start">Penilaian Akhlak</h3>
        <div class="w-full h-48 relative flex items-center justify-center">
            <svg class="w-40 h-40" viewBox="0 0 100 100">
                {{-- Web background --}}
                <polygon fill="none" points="50,10 90,50 50,90 10,50" stroke="#e5e7eb" stroke-width="1"/>
                <polygon fill="none" points="50,25 75,50 50,75 25,50" stroke="#e5e7eb" stroke-width="1"/>
                <line x1="50" y1="10" x2="50" y2="90" stroke="#e5e7eb" stroke-width="1"/>
                <line x1="10" y1="50" x2="90" y2="50" stroke="#e5e7eb" stroke-width="1"/>
                {{-- Data polygon --}}
                <polygon fill="rgba(0, 69, 50, 0.15)" stroke="#004532" stroke-width="2"
                    points="50,14 83,50 50,86 20,50"/>
                {{-- Data points --}}
                <circle cx="50" cy="14" r="3" fill="#004532"/>
                <circle cx="83" cy="50" r="3" fill="#004532"/>
                <circle cx="50" cy="86" r="3" fill="#004532"/>
                <circle cx="20" cy="50" r="3" fill="#004532"/>
            </svg>
            <span class="absolute top-0 text-[11px] font-bold text-on-surface">Sidiq</span>
            <span class="absolute right-0 text-[11px] font-bold text-on-surface">Amanah</span>
            <span class="absolute bottom-0 text-[11px] font-bold text-on-surface">Tabligh</span>
            <span class="absolute left-0 text-[11px] font-bold text-on-surface">Fathonah</span>
        </div>
        <p class="mt-4 text-[12px] text-center font-medium italic text-gray-500 leading-relaxed bg-gray-50 p-3 rounded-xl border border-gray-100">"Ananda menunjukkan kejujuran yang sangat baik dalam keseharian."</p>
    </div>
</section>

@endsection
