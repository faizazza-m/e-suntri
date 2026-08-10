@extends('layouts.app')

@section('title', 'Mata Pelajaran — SUNTRI')
@section('meta_description', 'Kelola data Mata Pelajaran dan Guru pengampunya.')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 fade-in-up mb-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-secondary rounded-2xl shadow-lg">
                <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings: 'FILL' 1;">menu_book</span>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-secondary">Data Mata Pelajaran</h2>
                <p class="text-sm text-on-surface-variant">Kelola daftar mata pelajaran beserta guru pengampunya.</p>
            </div>
        </div>
        <button onclick="document.getElementById('modal-tambah-mapel').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2.5 bg-secondary text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">add</span>
            Tambah Mapel
        </button>
    </div>

    {{-- Main Content --}}
    <div class="glassmorphism rounded-2xl border border-white/20 shadow-sm overflow-hidden fade-in-up delay-1">
        <div class="p-5 border-b border-outline-variant/20 flex justify-between items-center bg-white/40">
            <h3 class="text-base font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">menu_book</span>
                Daftar Mata Pelajaran
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low/50 text-xs text-on-surface-variant uppercase tracking-wider border-b border-outline-variant/20">
                        <th class="px-5 py-3 font-bold">Kode</th>
                        <th class="px-5 py-3 font-bold">Mata Pelajaran</th>
                        <th class="px-5 py-3 font-bold">Guru Pengampu</th>
                        <th class="px-5 py-3 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 bg-white/30">
                    @forelse($mapels as $mapel)
                    <tr class="hover:bg-white/60 transition-colors group">
                        <td class="px-5 py-3">
                            <span class="text-xs font-bold text-on-surface-variant bg-surface-container px-2 py-1 rounded-md">{{ $mapel->kode ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <p class="text-sm font-bold text-on-surface">{{ $mapel->nama }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-[10px]" style="font-variation-settings:'FILL' 1;">person</span>
                                </div>
                                <p class="text-xs text-on-surface-variant font-bold">{{ $mapel->guru->name ?? 'Belum Ditentukan' }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="editMapel({{ $mapel->id }}, '{{ addslashes($mapel->nama) }}', '{{ addslashes($mapel->kode) }}', '{{ $mapel->guru_id }}')" class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-secondary transition-colors" title="Edit Mapel">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                                <form action="{{ route('akademik.mapel.destroy', $mapel->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mapel ini? Semua data terkait (termasuk nilai) mungkin akan terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full hover:bg-error/10 flex items-center justify-center text-error transition-colors" title="Hapus Mapel">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center">
                            <div class="inline-flex flex-col items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl mb-2">menu_book</span>
                                <p class="text-sm font-medium">Belum ada data mata pelajaran.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah Mapel --}}
<div id="modal-tambah-mapel" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-secondary/10 px-8 py-5 border-b border-secondary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">add_circle</span> Tambah Mapel Baru
            </h3>
            <button onclick="document.getElementById('modal-tambah-mapel').classList.add('hidden')" class="text-secondary hover:bg-secondary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('akademik.mapel.store') }}" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Nama Pelajaran</label>
                <input type="text" name="nama" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none" placeholder="Cth: Matematika Dasar">
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Kode Pelajaran (Opsional)</label>
                <input type="text" name="kode" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none" placeholder="Cth: MTD-101">
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Guru Pengampu (Opsional)</label>
                <select name="guru_id" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-tambah-mapel').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-secondary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Mapel</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Mapel --}}
<div id="modal-edit-mapel" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-primary/10 px-8 py-5 border-b border-primary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">edit</span> Edit Mata Pelajaran
            </h3>
            <button onclick="document.getElementById('modal-edit-mapel').classList.add('hidden')" class="text-primary hover:bg-primary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="" id="form-edit-mapel" class="p-8 space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Nama Pelajaran</label>
                <input type="text" name="nama" id="edit-nama" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Kode Pelajaran (Opsional)</label>
                <input type="text" name="kode" id="edit-kode" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Guru Pengampu (Opsional)</label>
                <select name="guru_id" id="edit-guru_id" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-edit-mapel').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editMapel(id, nama, kode, guruId) {
        document.getElementById('form-edit-mapel').action = `/admin/akademik/mapel/${id}`;
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-kode').value = kode;
        document.getElementById('edit-guru_id').value = guruId;
        document.getElementById('modal-edit-mapel').classList.remove('hidden');
    }
</script>

@endsection
