@extends('layouts.mudir')

@section('title', 'Data Santri — SUNTRI')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 fade-in-up">
    <div>
        <h2 class="text-2xl font-bold text-primary">Direktori Santri</h2>
        <p class="text-sm text-on-surface-variant">Daftar seluruh santri aktif di pesantren.</p>
    </div>
</div>

<div class="glassmorphism rounded-2xl p-6 border border-white/20 shadow-sm fade-in-up delay-1">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-outline-variant/30">
                    <th class="p-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">NIS</th>
                    <th class="p-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Santri</th>
                    <th class="p-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">L/P</th>
                    <th class="p-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kelas</th>
                    <th class="p-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Halaqoh</th>
                    <th class="p-3 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Masuk</th>
                </tr>
            </thead>
            <tbody>
                @forelse($santris as $s)
                <tr class="border-b border-outline-variant/10 hover:bg-surface-container-low transition-colors">
                    <td class="p-3 text-sm font-bold text-primary">{{ $s->nis }}</td>
                    <td class="p-3 text-sm font-medium">{{ $s->nama }}</td>
                    <td class="p-3 text-sm">{{ $s->jenis_kelamin }}</td>
                    <td class="p-3 text-sm">{{ $s->kelas->nama ?? '—' }}</td>
                    <td class="p-3 text-sm">{{ $s->halaqoh->nama ?? '—' }}</td>
                    <td class="p-3 text-sm">{{ $s->tahun_masuk }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-4 text-center text-sm text-on-surface-variant">Tidak ada data santri.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
