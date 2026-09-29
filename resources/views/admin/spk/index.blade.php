@extends('layouts.app')

@section('title', 'SPK Santri Teladan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">🏆 SPK Santri Teladan (Metode SAW)</h1>
    </div>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-900/30 dark:text-green-400" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Pengaturan Bobot -->
        <div class="xl:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden h-full">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Pengaturan Bobot Kriteria</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('spk.kriteria.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-300">
                                    <tr>
                                        <th class="px-4 py-3 rounded-tl-lg">Kriteria</th>
                                        <th class="px-4 py-3 rounded-tr-lg w-24">Bobot (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kriterias as $k)
                                    <tr class="border-b border-gray-50 dark:border-gray-700/50 last:border-0">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-800 dark:text-gray-200">{{ $k->kode }} - {{ $k->nama }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ ucfirst($k->sifat) }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="bobot[{{ $k->id }}]" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ $k->bobot }}" min="1" max="100" required>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="w-full px-4 py-2.5 text-sm font-medium text-white bg-primary hover:bg-primary-container transition-colors rounded-xl shadow-sm">
                            Simpan Perubahan Bobot
                        </button>
                    </form>
                    
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-xs text-blue-800 dark:text-blue-300 space-y-1.5 leading-relaxed">
                        <p class="font-semibold mb-2">Informasi Sumber Data:</p>
                        <p>• <b>C1 (Tahfizh):</b> Jumlah baris setoran hafalan.</p>
                        <p>• <b>C2 (Kedisiplinan):</b> Persentase kehadiran.</p>
                        <p>• <b>C3 (Akademik):</b> Rata-rata nilai rapor akhir.</p>
                        <p>• <b>C4 (Pelanggaran):</b> Diisi manual di tabel kanan.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input C4 & Hasil Ranking -->
        <div class="xl:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Hasil Perankingan & Penilaian Pelanggaran</h2>
                </div>
                <div class="p-0">
                    <form action="{{ route('spk.penilaian.save') }}" method="POST">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-300">
                                    <tr>
                                        <th class="px-6 py-4">Rank</th>
                                        <th class="px-6 py-4">Nama Santri</th>
                                        <th class="px-4 py-4 text-center">C1<br><span class="text-[10px] font-normal text-gray-500">Tahfizh</span></th>
                                        <th class="px-4 py-4 text-center">C2<br><span class="text-[10px] font-normal text-gray-500">Disiplin</span></th>
                                        <th class="px-4 py-4 text-center">C3<br><span class="text-[10px] font-normal text-gray-500">Akademik</span></th>
                                        <th class="px-4 py-4 text-center w-32">C4<br><span class="text-[10px] font-normal text-gray-500">Pelanggaran</span></th>
                                        <th class="px-6 py-4 text-right">Skor Akhir</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($rankings as $index => $r)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $index == 0 ? 'bg-yellow-50/50 dark:bg-yellow-900/10' : '' }}">
                                        <td class="px-6 py-4 font-medium">
                                            @if($index == 0) <span class="text-xl" title="Juara 1">🥇</span> 
                                            @elseif($index == 1) <span class="text-xl" title="Juara 2">🥈</span> 
                                            @elseif($index == 2) <span class="text-xl" title="Juara 3">🥉</span> 
                                            @else <span class="text-gray-500 ml-1">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $r['nama'] }}
                                        </td>
                                        <td class="px-4 py-4 text-center">{{ $r['c1'] }}</td>
                                        <td class="px-4 py-4 text-center">{{ $r['c2'] }}%</td>
                                        <td class="px-4 py-4 text-center">{{ $r['c3'] }}</td>
                                        <td class="px-4 py-4">
                                            <input type="number" name="c4[{{ $r['id'] }}]" class="w-full px-3 py-2 text-sm text-center border border-gray-300 rounded-lg focus:ring-primary focus:border-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ $r['c4'] }}" min="0">
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold {{ $index < 3 ? 'text-primary dark:text-primary-fixed' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ number_format($r['total'], 4) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-secondary hover:bg-secondary-container transition-colors rounded-xl shadow-sm flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">save</span>
                                Simpan Nilai & Hitung Ulang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
