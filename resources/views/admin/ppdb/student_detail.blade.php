@extends('layouts.app')
@section('title', 'Detail Pendaftar - ' . $student->full_name)

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.ppdb.students') }}" class="w-10 h-10 rounded-xl bg-surface border border-outline-variant/30 flex items-center justify-center hover:bg-black/5 transition-colors">
                <span class="material-symbols-outlined text-on-surface">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-bold text-primary">Detail Pendaftar</h2>
                <p class="text-sm text-on-surface-variant">{{ $student->registration_number }} - {{ $student->batch->name }}</p>
            </div>
        </div>
        
        <div class="flex gap-2">
            <form action="{{ route('admin.ppdb.students.status', $student->id) }}" method="POST" class="flex gap-2">
                @csrf
                <select name="status" class="px-4 py-2 text-sm font-bold rounded-xl border border-outline-variant/30 bg-white shadow-sm focus:ring-primary focus:border-primary transition-colors">
                    <option value="pending" {{ $student->status == 'pending' ? 'selected' : '' }}>Status: Pending</option>
                    <option value="verified" {{ $student->status == 'verified' ? 'selected' : '' }}>Status: Diverifikasi</option>
                    <option value="accepted" {{ $student->status == 'accepted' ? 'selected' : '' }}>Status: Lulus</option>
                    <option value="rejected" {{ $student->status == 'rejected' ? 'selected' : '' }}>Status: Tidak Lulus</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary/90 transition-colors shadow-sm">
                    Update Status
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-800 rounded-xl font-medium text-sm flex items-center gap-2 fade-in-up delay-1">
        <span class="material-symbols-outlined">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-in-up delay-2">
        {{-- Data Pribadi --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface rounded-3xl p-6 shadow-sm border border-outline-variant/30">
                <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">person</span> Data Pribadi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <div>
                        <p class="text-xs text-on-surface-variant font-medium">Nama Lengkap</p>
                        <p class="text-sm font-bold text-on-surface">{{ $student->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant font-medium">Jenis Kelamin</p>
                        <p class="text-sm font-bold text-on-surface">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant font-medium">Tempat, Tanggal Lahir</p>
                        <p class="text-sm font-bold text-on-surface">{{ $student->birth_place }}, {{ optional($student->birth_date)->format('d M Y') ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant font-medium">Asal Sekolah</p>
                        <p class="text-sm font-bold text-on-surface">{{ $student->previous_school ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant font-medium">NIK</p>
                        <p class="text-sm font-bold text-on-surface">{{ $student->nik ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant font-medium">NISN</p>
                        <p class="text-sm font-bold text-on-surface">{{ $student->nisn ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-surface rounded-3xl p-6 shadow-sm border border-outline-variant/30">
                <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">family_restroom</span> Data Orang Tua & Alamat
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                    <div>
                        <p class="text-xs text-on-surface-variant font-medium">Nama Ayah</p>
                        <p class="text-sm font-bold text-on-surface">{{ $student->father_name ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant font-medium">Nama Ibu</p>
                        <p class="text-sm font-bold text-on-surface">{{ $student->mother_name ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant font-medium">No. Telepon / WhatsApp</p>
                        <p class="text-sm font-bold text-on-surface">{{ $student->parent_phone ?: '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs text-on-surface-variant font-medium">Alamat Lengkap</p>
                        <p class="text-sm font-bold text-on-surface">{{ $student->address ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar: Berkas & Nilai --}}
        <div class="space-y-6">
            <div class="bg-surface rounded-3xl p-6 shadow-sm border border-outline-variant/30">
                <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">folder_open</span> Berkas Persyaratan
                </h3>
                <div class="space-y-3">
                    @forelse($student->documents as $doc)
                    <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-xl border border-outline-variant/20">
                        <div>
                            <p class="text-sm font-bold text-on-surface uppercase">{{ $doc->document_type }}</p>
                            <p class="text-[10px] text-on-surface-variant">{{ $doc->status }}</p>
                        </div>
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                        </a>
                    </div>
                    @empty
                    <div class="p-4 text-center text-sm text-on-surface-variant border border-dashed border-outline-variant/50 rounded-xl">
                        Belum ada berkas diunggah.
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-surface rounded-3xl p-6 shadow-sm border border-outline-variant/30">
                <h3 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">military_tech</span> Hasil Seleksi
                </h3>
                @if($student->score)
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-on-surface-variant">Tes Baca Al-Quran</span>
                        <span class="font-bold text-on-surface">{{ $student->score->quran_test ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-on-surface-variant">Tes Tertulis</span>
                        <span class="font-bold text-on-surface">{{ $student->score->written_test ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-on-surface-variant">Wawancara</span>
                        <span class="font-bold text-on-surface">{{ $student->score->interview_test ?? '-' }}</span>
                    </div>
                    <hr class="border-outline-variant/30">
                    <div class="flex justify-between items-center text-base">
                        <span class="font-bold text-on-surface">Total Nilai</span>
                        <span class="font-bold text-primary">{{ $student->score->total_score ?? '-' }}</span>
                    </div>
                </div>
                @else
                <div class="p-4 text-center text-sm text-on-surface-variant border border-dashed border-outline-variant/50 rounded-xl">
                    Nilai tes belum diinput.
                </div>
                @endif
                <button class="w-full mt-4 py-2 border-2 border-primary/20 text-primary font-bold text-sm rounded-xl hover:bg-primary/5 transition-colors">
                    Input/Update Nilai
                </button>
            </div>
        </div>

    </div>
</div>
@endsection
