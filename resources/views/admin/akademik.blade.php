@extends('layouts.app')

@section('title', 'Akademik — SUNTRI')
@section('meta_description', 'Modul Akademik SUNTRI: kelola kelas, mata pelajaran, jadwal, dan nilai akademik santri.')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-primary-container rounded-2xl shadow-lg">
                <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">school</span>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-primary">Pusat Akademik</h2>
                <p class="text-sm text-on-surface-variant">Kelola kegiatan belajar mengajar, kelas, dan jadwal pelajaran.</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="document.getElementById('modal-tambah-kelas').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface text-sm font-bold rounded-xl transition-colors border border-outline-variant/30 shadow-sm">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">add</span>
                Tambah Kelas
            </button>
            <button onclick="document.getElementById('modal-susun-jadwal').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2.5 bg-secondary text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl hover:opacity-90 transition-all">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">event_note</span>
                Susun Jadwal
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 fade-in-up delay-1">
        {{-- Total Kelas --}}
        <div class="glassmorphism rounded-2xl p-6 border border-white/20 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center border border-primary/20">
                    <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">meeting_room</span>
                </div>
                <span class="bg-primary/10 text-primary text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Aktif</span>
            </div>
            <div>
                <h3 class="text-3xl font-black text-on-surface mb-1">{{ $totalKelas }}</h3>
                <p class="text-sm font-medium text-on-surface-variant">Total Kelas</p>
            </div>
        </div>

        {{-- Total Mapel --}}
        <div class="glassmorphism rounded-2xl p-6 border border-white/20 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-secondary/5 rounded-full blur-2xl group-hover:bg-secondary/10 transition-colors"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center border border-secondary/20">
                    <span class="material-symbols-outlined text-secondary text-2xl" style="font-variation-settings: 'FILL' 1;">book</span>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-black text-on-surface mb-1">{{ $totalMapel }}</h3>
                <p class="text-sm font-medium text-on-surface-variant">Mata Pelajaran</p>
            </div>
        </div>

        {{-- Total Guru --}}
        <div class="glassmorphism rounded-2xl p-6 border border-white/20 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-500/5 rounded-full blur-2xl group-hover:bg-orange-500/10 transition-colors"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center border border-orange-200">
                    <span class="material-symbols-outlined text-orange-600 text-2xl" style="font-variation-settings: 'FILL' 1;">school</span>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-black text-on-surface mb-1">{{ $totalGuru }}</h3>
                <p class="text-sm font-medium text-on-surface-variant">Total Tenaga Pengajar</p>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-in-up delay-2">

        {{-- Jadwal Hari Ini --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="glassmorphism rounded-2xl border border-white/20 shadow-sm overflow-hidden flex flex-col h-full max-h-[600px]">
                <div class="p-5 border-b border-outline-variant/20 bg-gradient-to-r from-secondary/10 to-transparent flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-secondary text-white flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">event</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-on-surface">Jadwal Pelajaran</h3>
                            <p class="text-[10px] font-bold text-secondary uppercase tracking-widest">{{ $hariIniNama }}, {{ date('d M Y') }}</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('modal-semua-jadwal').classList.remove('hidden')" class="px-3 py-1.5 bg-white border border-outline-variant/30 rounded-lg text-[10px] font-bold text-secondary hover:bg-secondary hover:text-white transition-colors shadow-sm">
                        LIHAT SEMUA
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto custom-scrollbar p-5 space-y-4 relative">
                    {{-- Timeline Line --}}
                    <div class="absolute left-[39px] top-5 bottom-5 w-[2px] bg-outline-variant/30"></div>

                    @forelse($jadwalHariIni as $jdwl)
                    <div class="flex gap-4 relative z-10">
                        {{-- Time Circle --}}
                        <div class="w-10 h-10 rounded-full bg-surface border-4 border-white flex-shrink-0 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-base text-secondary" style="font-variation-settings: 'FILL' 1;">schedule</span>
                        </div>
                        {{-- Content --}}
                        <div class="flex-1 bg-white/60 p-3.5 rounded-xl border border-white hover:border-secondary/30 hover:bg-white transition-all cursor-pointer shadow-sm group">
                            <div class="flex justify-between items-start mb-1">
                                <p class="text-[10px] font-bold text-secondary bg-secondary/10 px-2 py-0.5 rounded-full">
                                    {{ \Carbon\Carbon::parse($jdwl->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jdwl->jam_selesai)->format('H:i') }}
                                </p>
                                <span class="text-[10px] font-bold text-on-surface-variant flex items-center gap-0.5">
                                    <span class="material-symbols-outlined text-[12px]">room</span> {{ $jdwl->ruang }}
                                </span>
                            </div>
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('akademik.jadwal.destroy', $jdwl->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-6 h-6 rounded-full bg-error/10 text-error flex items-center justify-center hover:bg-error hover:text-white transition-colors">
                                        <span class="material-symbols-outlined text-[14px]">close</span>
                                    </button>
                                </form>
                            </div>
                            <p class="text-sm font-bold text-on-surface group-hover:text-secondary transition-colors">{{ $jdwl->mapel->nama ?? 'Mapel Unknown' }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">person</span>
                                {{ $jdwl->mapel->guru->name ?? 'Belum ada guru' }}
                            </p>
                            <div class="mt-2 text-[10px] font-bold text-primary flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">school</span>
                                Kelas {{ $jdwl->kelas->nama ?? '-' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-10 relative z-10 bg-surface-container/50 rounded-2xl border border-dashed border-outline-variant/40">
                        <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">event_busy</span>
                        <p class="text-sm font-bold text-on-surface">Tidak ada jadwal.</p>
                        <p class="text-xs text-on-surface-variant mt-1">Belum ada kelas yang dijadwalkan<br>untuk hari {{ $hariIniNama }}.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Daftar Kelas --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="glassmorphism rounded-2xl border border-white/20 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-outline-variant/20 flex justify-between items-center bg-white/40">
                    <h3 class="text-base font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">meeting_room</span>
                        Daftar Kelas & Rombel
                    </h3>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                        <input type="text" placeholder="Cari kelas..." class="pl-9 pr-3 py-1.5 bg-surface rounded-full border-none focus:ring-2 focus:ring-primary text-xs w-48 shadow-inner">
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low/50 text-xs text-on-surface-variant uppercase tracking-wider border-b border-outline-variant/20">
                                <th class="px-5 py-3 font-bold">Nama Kelas</th>
                                <th class="px-5 py-3 font-bold">Tingkat</th>
                                <th class="px-5 py-3 font-bold">Wali Kelas</th>
                                <th class="px-5 py-3 font-bold text-center">Jumlah Santri</th>
                                <th class="px-5 py-3 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10 bg-white/30">
                            @forelse($daftarKelas as $kls)
                            <tr class="hover:bg-white/60 transition-colors group">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                            {{ substr($kls->nama, 0, 2) }}
                                        </div>
                                        <p class="text-sm font-bold text-on-surface">{{ $kls->nama }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="text-xs font-semibold bg-surface-container text-on-surface-variant px-2 py-1 rounded-md">{{ $kls->tingkat }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-secondary-container flex items-center justify-center">
                                            <span class="material-symbols-outlined text-white text-[10px]" style="font-variation-settings:'FILL' 1;">person</span>
                                        </div>
                                        <p class="text-xs text-on-surface-variant font-medium">{{ $kls->waliKelas->name ?? 'Belum Ditentukan' }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2.5 py-1 rounded-full text-xs font-bold border border-green-200">
                                        <span class="material-symbols-outlined text-[14px]">groups</span>
                                        {{ $kls->santri_count }} / {{ $kls->kapasitas }}
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-right relative">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick="editKelas({{ $kls->id }}, '{{ addslashes($kls->nama) }}', '{{ $kls->tingkat }}', {{ $kls->kapasitas }}, '{{ $kls->wali_kelas_id }}', {{ json_encode($kls->santri->pluck('id')) }})" class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-primary transition-colors" title="Edit Kelas">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                        </button>
                                        <form action="{{ route('akademik.kelas.destroy', $kls->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-full hover:bg-error/10 flex items-center justify-center text-error transition-colors" title="Hapus Kelas">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                    <button class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-on-surface-variant group-hover:hidden transition-colors ml-auto absolute right-5 top-1/2 -translate-y-1/2">
                                        <span class="material-symbols-outlined text-sm">more_horiz</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center">
                                    <div class="inline-flex flex-col items-center justify-center text-on-surface-variant">
                                        <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                                        <p class="text-sm font-medium">Belum ada data kelas.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Quick Links Akademik --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('akademik.nilai') }}" class="glassmorphism p-4 rounded-xl border border-white/20 hover:border-primary/40 hover:shadow-md transition-all flex flex-col items-center justify-center gap-2 text-center group">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary transition-colors">
                        <span class="material-symbols-outlined text-primary group-hover:text-white transition-colors" style="font-variation-settings:'FILL' 1;">assignment</span>
                    </div>
                    <span class="text-xs font-bold text-on-surface">Data Nilai</span>
                </a>
                <a href="{{ route('akademik.mapel') }}" class="glassmorphism p-4 rounded-xl border border-white/20 hover:border-secondary/40 hover:shadow-md transition-all flex flex-col items-center justify-center gap-2 text-center group">
                    <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center group-hover:bg-secondary transition-colors">
                        <span class="material-symbols-outlined text-secondary group-hover:text-white transition-colors" style="font-variation-settings:'FILL' 1;">book</span>
                    </div>
                    <span class="text-xs font-bold text-on-surface">Mata Pelajaran</span>
                </a>
                <a href="{{ route('akademik.ujian') }}" class="glassmorphism p-4 rounded-xl border border-white/20 hover:border-orange-500/40 hover:shadow-md transition-all flex flex-col items-center justify-center gap-2 text-center group">
                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center group-hover:bg-orange-500 transition-colors">
                        <span class="material-symbols-outlined text-orange-600 group-hover:text-white transition-colors" style="font-variation-settings:'FILL' 1;">history_edu</span>
                    </div>
                    <span class="text-xs font-bold text-on-surface">Ujian & Tugas</span>
                </a>
                <a href="{{ route('akademik.raport') }}" class="glassmorphism p-4 rounded-xl border border-white/20 hover:border-purple-500/40 hover:shadow-md transition-all flex flex-col items-center justify-center gap-2 text-center group">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center group-hover:bg-purple-600 transition-colors">
                        <span class="material-symbols-outlined text-purple-600 group-hover:text-white transition-colors" style="font-variation-settings:'FILL' 1;">description</span>
                    </div>
                    <span class="text-xs font-bold text-on-surface">Raport</span>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Kelas --}}
<div id="modal-tambah-kelas" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-primary/10 px-8 py-5 border-b border-primary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">meeting_room</span> Tambah Kelas Baru
            </h3>
            <button onclick="document.getElementById('modal-tambah-kelas').classList.add('hidden')" class="text-primary hover:bg-primary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('akademik.kelas.store') }}" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Nama Kelas</label>
                <input type="text" name="nama" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none" placeholder="Cth: Kelas VII - A">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Tingkat</label>
                    <select name="tingkat" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="Ibtidaiyah">Ibtidaiyah</option>
                        <option value="Tsanawiyah">Tsanawiyah</option>
                        <option value="Aliyah">Aliyah</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Kapasitas</label>
                    <input type="number" name="kapasitas" min="1" value="30" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Wali Kelas (Opsional)</label>
                <select name="wali_kelas_id" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($semuaGuru as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Pilih Santri (Anggota Kelas)</label>
                <div class="w-full h-40 overflow-y-auto bg-surface-container border border-outline-variant rounded-xl p-2 space-y-1 custom-scrollbar">
                    @foreach($semuaSantri as $santri)
                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white cursor-pointer transition-all border border-transparent hover:border-outline-variant/30 hover:shadow-sm">
                        <input type="checkbox" name="santri_ids[]" value="{{ $santri->id }}" class="w-4 h-4 text-primary bg-white border-outline-variant rounded focus:ring-primary focus:ring-2">
                        <span class="text-sm font-semibold text-on-surface">{{ $santri->nama }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="text-[10px] text-on-surface-variant mt-1.5">*Centang nama santri yang ingin dimasukkan ke kelas ini.</p>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-tambah-kelas').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Kelas --}}
<div id="modal-edit-kelas" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-primary/10 px-8 py-5 border-b border-primary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">edit</span> Edit Kelas
            </h3>
            <button onclick="document.getElementById('modal-edit-kelas').classList.add('hidden')" class="text-primary hover:bg-primary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="" id="form-edit-kelas" class="p-8 space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Nama Kelas</label>
                <input type="text" name="nama" id="edit-kelas-nama" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Tingkat</label>
                    <select name="tingkat" id="edit-kelas-tingkat" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="Ibtidaiyah">Ibtidaiyah</option>
                        <option value="Tsanawiyah">Tsanawiyah</option>
                        <option value="Aliyah">Aliyah</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Kapasitas</label>
                    <input type="number" name="kapasitas" id="edit-kelas-kapasitas" min="1" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Wali Kelas (Opsional)</label>
                <select name="wali_kelas_id" id="edit-kelas-wali" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($semuaGuru as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Pilih Santri (Anggota Kelas)</label>
                <div class="w-full h-40 overflow-y-auto bg-surface-container border border-outline-variant rounded-xl p-2 space-y-1 custom-scrollbar">
                    @foreach($semuaSantri as $santri)
                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white cursor-pointer transition-all border border-transparent hover:border-outline-variant/30 hover:shadow-sm">
                        <input type="checkbox" name="santri_ids[]" value="{{ $santri->id }}" class="edit-kelas-santri-cb w-4 h-4 text-primary bg-white border-outline-variant rounded focus:ring-primary focus:ring-2">
                        <span class="text-sm font-semibold text-on-surface">{{ $santri->nama }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="text-[10px] text-on-surface-variant mt-1.5">*Centang nama santri yang ingin dimasukkan ke kelas ini.</p>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-edit-kelas').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Susun Jadwal --}}
<div id="modal-susun-jadwal" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-secondary/10 px-8 py-5 border-b border-secondary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">event_note</span> Susun Jadwal Pelajaran
            </h3>
            <button onclick="document.getElementById('modal-susun-jadwal').classList.add('hidden')" class="text-secondary hover:bg-secondary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('akademik.jadwal.store') }}" class="p-8 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Kelas</label>
                    <select name="kelas_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                        <option value="">Pilih Kelas</option>
                        @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Mata Pelajaran</label>
                    <select name="mapel_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                        <option value="">Pilih Mapel</option>
                        @foreach($semuaMapel as $m)
                        <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Hari</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                    <label class="cursor-pointer">
                        <input type="radio" name="hari" value="{{ $hari }}" class="peer sr-only" required>
                        <div class="px-4 py-2 rounded-xl border border-outline-variant/50 text-sm font-medium text-on-surface-variant peer-checked:bg-secondary peer-checked:text-white peer-checked:border-secondary transition-colors hover:bg-surface-container">
                            {{ $hari }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Jam Mulai</label>
                    <input type="time" name="jam_mulai" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Jam Selesai</label>
                    <input type="time" name="jam_selesai" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Ruang</label>
                    <input type="text" name="ruang" placeholder="Cth: R-10" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-susun-jadwal').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-secondary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Semua Jadwal --}}
<div id="modal-semua-jadwal" class="fixed inset-0 z-[60] hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-5xl relative z-10 overflow-hidden border border-white/20 flex flex-col max-h-[90vh]">
        <div class="bg-secondary/10 px-8 py-5 border-b border-secondary/20 flex justify-between items-center shrink-0">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">calendar_view_week</span> Seluruh Jadwal Pelajaran
            </h3>
            <button onclick="document.getElementById('modal-semua-jadwal').classList.add('hidden')" class="text-secondary hover:bg-secondary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 overflow-y-auto custom-scrollbar">
            <div class="overflow-x-auto rounded-xl border border-outline-variant/30">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-surface-container-low text-xs text-on-surface-variant uppercase tracking-wider border-b border-outline-variant/30">
                            <th class="px-4 py-3 font-bold">Hari</th>
                            <th class="px-4 py-3 font-bold">Jam</th>
                            <th class="px-4 py-3 font-bold">Kelas</th>
                            <th class="px-4 py-3 font-bold">Mata Pelajaran</th>
                            <th class="px-4 py-3 font-bold">Pengajar</th>
                            <th class="px-4 py-3 font-bold">Ruang</th>
                            <th class="px-4 py-3 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10 bg-white">
                        @forelse($seluruhJadwal as $jdwl)
                        <tr class="hover:bg-surface/50 transition-colors">
                            <td class="px-4 py-3 font-bold text-sm text-secondary">{{ $jdwl->hari }}</td>
                            <td class="px-4 py-3 text-xs font-semibold text-on-surface-variant">
                                {{ \Carbon\Carbon::parse($jdwl->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jdwl->jam_selesai)->format('H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm font-bold text-primary">{{ $jdwl->kelas->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-on-surface">{{ $jdwl->mapel->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs text-on-surface-variant flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">person</span> {{ $jdwl->mapel->guru->name ?? 'Belum ada' }}
                            </td>
                            <td class="px-4 py-3 text-xs font-medium text-on-surface-variant">{{ $jdwl->ruang ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="editJadwal({{ $jdwl->id }}, {{ $jdwl->kelas_id }}, {{ $jdwl->mapel_id }}, '{{ $jdwl->hari }}', '{{ \Carbon\Carbon::parse($jdwl->jam_mulai)->format('H:i') }}', '{{ \Carbon\Carbon::parse($jdwl->jam_selesai)->format('H:i') }}', '{{ $jdwl->ruang }}')" class="w-7 h-7 rounded-full hover:bg-surface-container flex items-center justify-center text-primary transition-colors" title="Edit Jadwal">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </button>
                                    <form action="{{ route('akademik.jadwal.destroy', $jdwl->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-7 h-7 rounded-full hover:bg-error/10 flex items-center justify-center text-error transition-colors" title="Hapus Jadwal">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center">
                                <div class="inline-flex flex-col items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl mb-2">event_busy</span>
                                    <p class="text-sm font-medium">Belum ada data jadwal.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Jadwal --}}
<div id="modal-edit-jadwal" class="fixed inset-0 z-[70] hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-secondary/10 px-8 py-5 border-b border-secondary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">edit_calendar</span> Edit Jadwal Pelajaran
            </h3>
            <button onclick="document.getElementById('modal-edit-jadwal').classList.add('hidden')" class="text-secondary hover:bg-secondary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="" id="form-edit-jadwal" class="p-8 space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Kelas</label>
                    <select name="kelas_id" id="edit-jadwal-kelas" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                        <option value="">Pilih Kelas</option>
                        @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Mata Pelajaran</label>
                    <select name="mapel_id" id="edit-jadwal-mapel" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                        <option value="">Pilih Mapel</option>
                        @foreach($semuaMapel as $m)
                        <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Hari</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                    <label class="cursor-pointer">
                        <input type="radio" name="hari" value="{{ $hari }}" class="edit-jadwal-hari peer sr-only" required>
                        <div class="px-4 py-2 rounded-xl border border-outline-variant/50 text-sm font-medium text-on-surface-variant peer-checked:bg-secondary peer-checked:text-white peer-checked:border-secondary transition-colors hover:bg-surface-container">
                            {{ $hari }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="edit-jadwal-jam-mulai" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="edit-jadwal-jam-selesai" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Ruang</label>
                    <input type="text" name="ruang" id="edit-jadwal-ruang" placeholder="Cth: R-10" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-edit-jadwal').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-secondary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editKelas(id, nama, tingkat, kapasitas, waliKelasId, santriIds = []) {
        document.getElementById('form-edit-kelas').action = `/admin/akademik/kelas/${id}`;
        document.getElementById('edit-kelas-nama').value = nama;
        document.getElementById('edit-kelas-tingkat').value = tingkat;
        document.getElementById('edit-kelas-kapasitas').value = kapasitas;
        document.getElementById('edit-kelas-wali').value = waliKelasId || '';
        
        // Handle select multiple santri with checkboxes
        let checkboxes = document.querySelectorAll('.edit-kelas-santri-cb');
        checkboxes.forEach(cb => {
            cb.checked = santriIds.includes(parseInt(cb.value));
        });

        document.getElementById('modal-edit-kelas').classList.remove('hidden');
    }

    function editJadwal(id, kelasId, mapelId, hari, jamMulai, jamSelesai, ruang) {
        document.getElementById('form-edit-jadwal').action = `/admin/akademik/jadwal/${id}`;
        document.getElementById('edit-jadwal-kelas').value = kelasId;
        document.getElementById('edit-jadwal-mapel').value = mapelId;
        document.getElementById('edit-jadwal-jam-mulai').value = jamMulai;
        document.getElementById('edit-jadwal-jam-selesai').value = jamSelesai;
        document.getElementById('edit-jadwal-ruang').value = ruang || '';

        // Handle radio buttons for "hari"
        let radios = document.querySelectorAll('.edit-jadwal-hari');
        radios.forEach(radio => {
            radio.checked = (radio.value === hari);
        });

        document.getElementById('modal-edit-jadwal').classList.remove('hidden');
    }
</script>

@endsection
