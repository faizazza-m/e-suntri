@extends('layouts.app')

@section('title', 'Kehadiran Santri')

@section('content')
<div class="fade-in-up">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-display-lg-mobile sm:text-display-lg text-primary">Kehadiran Santri</h1>
            <p class="text-body-md text-on-surface-variant mt-2">Pantau daftar kehadiran harian seluruh santri.</p>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="glass-card rounded-2xl p-6 mb-8 delay-1">
        <form method="GET" action="{{ route('kehadiran') }}" class="flex flex-col md:flex-row items-end gap-4">
            <div class="w-full md:w-1/4">
                <label class="block text-label-sm text-on-surface-variant mb-1">Mulai Tanggal</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary">calendar_month</span>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none text-sm transition-all" />
                </div>
            </div>
            
            <div class="w-full md:w-1/4">
                <label class="block text-label-sm text-on-surface-variant mb-1">Sampai Tanggal</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary">event</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none text-sm transition-all" />
                </div>
            </div>
            
            <div class="w-full md:w-1/4">
                <label class="block text-label-sm text-on-surface-variant mb-1">Status</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary">fact_check</span>
                    <select name="status" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant bg-surface focus:ring-2 focus:ring-primary focus:border-primary outline-none text-sm appearance-none cursor-pointer transition-all">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="alpa" {{ request('status') == 'alpa' ? 'selected' : '' }}>Alpa</option>
                    </select>
                </div>
            </div>
            
            <div class="w-full md:w-auto flex gap-2">
                <button type="submit" class="flex-1 md:flex-none px-6 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary-container hover:shadow-lg transition-all shimmer-button">
                    Filter
                </button>
                @if(request()->has('start_date') || request()->has('end_date') || request()->has('status'))
                <a href="{{ route('kehadiran') }}" class="px-4 py-2.5 bg-surface text-error rounded-xl font-bold border border-error/20 hover:bg-error-container transition-all flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">close</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Statistics Recap -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 delay-1">
        <div class="glass-card p-4 rounded-xl flex items-center gap-4 border-l-4 border-l-emerald-500 hover:-translate-y-1 transition-transform">
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <span class="material-symbols-outlined text-xl">check_circle</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Hadir</p>
                <p class="text-xl font-bold text-emerald-700">{{ $stats['hadir'] }}</p>
            </div>
        </div>
        <div class="glass-card p-4 rounded-xl flex items-center gap-4 border-l-4 border-l-blue-500 hover:-translate-y-1 transition-transform">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                <span class="material-symbols-outlined text-xl">info</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Izin</p>
                <p class="text-xl font-bold text-blue-700">{{ $stats['izin'] }}</p>
            </div>
        </div>
        <div class="glass-card p-4 rounded-xl flex items-center gap-4 border-l-4 border-l-orange-500 hover:-translate-y-1 transition-transform">
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0">
                <span class="material-symbols-outlined text-xl">medical_services</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Sakit</p>
                <p class="text-xl font-bold text-orange-700">{{ $stats['sakit'] }}</p>
            </div>
        </div>
        <div class="glass-card p-4 rounded-xl flex items-center gap-4 border-l-4 border-l-red-500 hover:-translate-y-1 transition-transform">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 flex-shrink-0">
                <span class="material-symbols-outlined text-xl">cancel</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Alpa</p>
                <p class="text-xl font-bold text-red-700">{{ $stats['alpa'] }}</p>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="glass-card rounded-2xl overflow-hidden delay-2">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container/50 border-b border-outline-variant/30">
                        <th class="py-4 px-6 text-label-sm text-on-surface-variant font-semibold">Tanggal</th>
                        <th class="py-4 px-6 text-label-sm text-on-surface-variant font-semibold">Nama Santri</th>
                        <th class="py-4 px-6 text-label-sm text-on-surface-variant font-semibold">Kelas</th>
                        <th class="py-4 px-6 text-label-sm text-on-surface-variant font-semibold text-center">Status</th>
                        <th class="py-4 px-6 text-label-sm text-on-surface-variant font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse($kehadiran as $item)
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="py-4 px-6 text-sm text-on-surface font-medium whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs flex-shrink-0">
                                    {{ substr($item->santri->nama ?? 'S', 0, 1) }}
                                </div>
                                <span class="text-sm font-semibold text-on-surface">{{ $item->santri->nama ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-sm text-on-surface-variant">
                            {{ $item->santri->kelas->nama ?? '-' }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($item->status == 'hadir')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hadir
                                </span>
                            @elseif($item->status == 'izin')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Izin
                                </span>
                            @elseif($item->status == 'sakit')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-bold border border-orange-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Sakit
                                </span>
                            @elseif($item->status == 'alpa')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold border border-red-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Alpa
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> {{ ucfirst($item->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-sm text-on-surface-variant max-w-[200px] truncate">
                            {{ $item->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 px-6 text-center">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center">
                                    <span class="material-symbols-outlined text-3xl text-outline">event_busy</span>
                                </div>
                                <p class="text-body-md text-on-surface-variant font-medium">Belum ada data kehadiran santri.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($kehadiran->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant/20 bg-surface/50">
            {{ $kehadiran->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>
@endsection
