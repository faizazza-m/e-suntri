@extends('layouts.app')
@section('title', 'Data Pendaftar PPDB')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.ppdb.index') }}" class="w-10 h-10 rounded-xl bg-surface border border-outline-variant/30 flex items-center justify-center hover:bg-black/5 transition-colors">
                <span class="material-symbols-outlined text-on-surface">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-bold text-primary">Data Pendaftar</h2>
                <p class="text-sm text-on-surface-variant">Kelola dan verifikasi data calon santri baru.</p>
            </div>
        </div>
        
        {{-- Filter Form --}}
        <form action="{{ route('admin.ppdb.students') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <select name="batch_id" class="px-4 py-2 text-sm rounded-xl border border-outline-variant/30 bg-white/60 backdrop-blur-md shadow-sm focus:ring-primary focus:border-primary transition-colors" onchange="this.form.submit()">
                <option value="">-- Semua Gelombang --</option>
                @foreach($batches as $batch)
                <option value="{{ $batch->id }}" {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                    {{ $batch->name }}
                </option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2 text-sm rounded-xl border border-outline-variant/30 bg-white/60 backdrop-blur-md shadow-sm focus:ring-primary focus:border-primary transition-colors" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Diverifikasi</option>
                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Lulus</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Tidak Lulus</option>
            </select>
        </form>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-800 rounded-xl font-medium text-sm flex items-center gap-2 fade-in-up delay-1">
        <span class="material-symbols-outlined">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    {{-- Students Table --}}
    <div class="bg-surface rounded-3xl shadow-sm border border-outline-variant/30 overflow-hidden fade-in-up delay-2">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-lowest border-b border-outline-variant/30">
                        <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">No. Registrasi</th>
                        <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nama Lengkap</th>
                        <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">L/P</th>
                        <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Asal Sekolah</th>
                        <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse($students as $student)
                    <tr class="hover:bg-black/[0.02] transition-colors group">
                        <td class="p-4 font-mono text-sm text-on-surface">{{ $student->registration_number }}</td>
                        <td class="p-4">
                            <p class="text-sm font-bold text-on-surface">{{ $student->full_name }}</p>
                            <p class="text-[10px] text-on-surface-variant">{{ $student->batch->name }}</p>
                        </td>
                        <td class="p-4 text-sm text-on-surface-variant">{{ $student->gender }}</td>
                        <td class="p-4 text-sm text-on-surface-variant">{{ $student->previous_school ?: '-' }}</td>
                        <td class="p-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'verified' => 'bg-blue-100 text-blue-700',
                                    'accepted' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700'
                                ];
                                $statusLabels = [
                                    'pending' => 'Pending',
                                    'verified' => 'Diverifikasi',
                                    'accepted' => 'Lulus',
                                    'rejected' => 'Tidak Lulus'
                                ];
                            @endphp
                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg {{ $statusColors[$student->status] }}">
                                {{ $statusLabels[$student->status] }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.ppdb.students.show', $student->id) }}" class="w-8 h-8 flex items-center justify-center rounded-xl bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors" title="Detail & Verifikasi">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-on-surface-variant text-sm">
                            Belum ada data pendaftar yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $students->links() }}
    </div>

</div>
@endsection
