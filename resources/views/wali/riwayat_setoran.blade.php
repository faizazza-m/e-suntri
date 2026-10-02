@extends('layouts.mobile')

@section('title', 'Riwayat Setoran')
@section('greeting_name', 'Bapak/Ibu ' . explode(' ', auth()->user()->name)[0])

@section('content')

{{-- Header dengan tombol kembali --}}
<section class="flex items-center gap-3 mb-4">
    <a href="{{ route('wali.progres') }}" class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-gray-600 hover:bg-gray-50">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
    </a>
    <div>
        <h2 class="text-[18px] font-bold text-on-surface">Riwayat Hafalan</h2>
        <p class="text-[12px] font-medium text-gray-500">{{ $activeSantri->nama }}</p>
    </div>
</section>

{{-- Table --}}
<section class="clean-card bg-white overflow-hidden mt-2">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
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
                            <span class="text-[11px] font-medium text-gray-500 mt-0.5">Juz {{ $setoran->juz }} | {{ str_replace('_', ' ', ucwords($setoran->jenis)) }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-[12px] font-medium text-gray-600">{{ \Carbon\Carbon::parse($setoran->tanggal)->translatedFormat('d F Y') }}</td>
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

@endsection
