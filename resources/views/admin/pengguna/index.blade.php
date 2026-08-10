@extends('layouts.app')
@section('title', 'Data Pengguna')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-on-surface tracking-tight flex items-center gap-3">
            <span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings: 'FILL' 1;">manage_accounts</span>
            Data Pengguna
        </h1>
        <p class="text-sm text-on-surface-variant mt-1">Kelola data seluruh santri, ustadz, musyrif, dan admin.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Kartu Data Santri --}}
    <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border-2 border-outline-variant/30 relative overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">group</span>
                Data Santri ({{ $santris->count() }})
            </h2>
            <button onclick="document.getElementById('modal-tambah-santri').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl hover:opacity-90 transition-all">
                <span class="material-symbols-outlined text-sm">add</span> Tambah Santri
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-variant text-on-surface-variant text-xs uppercase tracking-widest font-bold">
                        <th class="p-3 rounded-tl-xl">Nama / NIS</th>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Halaqoh</th>
                        <th class="p-3 text-right rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20 text-sm">
                    @forelse($santris as $santri)
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="p-3">
                            <p class="font-bold text-on-surface">{{ $santri->nama }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $santri->nis }}</p>
                        </td>
                        <td class="p-3 font-medium">{{ $santri->kelas->nama ?? '-' }}</td>
                        <td class="p-3 font-medium">{{ $santri->halaqoh->nama ?? '-' }}</td>
                        <td class="p-3 text-right relative">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="editSantri({{ $santri->id }}, '{{ addslashes($santri->nama) }}', '{{ $santri->nis }}', '{{ $santri->jenis_kelamin }}', '{{ $santri->kelas_id }}', '{{ $santri->tahun_masuk }}')" class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-primary transition-colors" title="Edit Santri">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                                <form action="{{ route('santri.destroy', $santri->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus santri ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full hover:bg-error/10 flex items-center justify-center text-error transition-colors" title="Hapus Santri">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-on-surface-variant">Belum ada data santri.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Kartu Data Staff (Admin/Guru) --}}
    <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border-2 border-outline-variant/30 relative overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">badge</span>
                Data Staff & Pengurus ({{ $users->count() }})
            </h2>
            <button onclick="document.getElementById('modal-tambah-staff').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 bg-secondary text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl hover:opacity-90 transition-all">
                <span class="material-symbols-outlined text-sm">add</span> Tambah Staff
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-variant text-on-surface-variant text-xs uppercase tracking-widest font-bold">
                        <th class="p-3 rounded-tl-xl">Nama / Email</th>
                        <th class="p-3">Peran (Role)</th>
                        <th class="p-3 text-right rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20 text-sm">
                    @forelse($users as $user)
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="p-3">
                            <p class="font-bold text-on-surface">{{ $user->name }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $user->email }}</p>
                        </td>
                        <td class="p-3">
                            @php
                                $roleColors = [
                                    1 => 'bg-error-container text-on-error-container',
                                    2 => 'bg-secondary-container text-on-secondary-container',
                                    5 => 'bg-primary-container text-on-primary-container',
                                ];
                                $roleNames = [1 => 'Admin', 2 => 'Musyrif', 5 => 'Ustadz'];
                                $color = $roleColors[$user->role_id] ?? 'bg-surface-variant text-on-surface-variant';
                                $name = $roleNames[$user->role_id] ?? 'Lainnya';
                            @endphp
                            <span class="px-2 py-1 {{ $color }} text-[10px] font-bold rounded-lg uppercase tracking-wider">
                                {{ $name }}
                            </span>
                        </td>
                        <td class="p-3 text-right relative">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="editStaff({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->role_id }}', '{{ $user->phone }}')" class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-primary transition-colors" title="Edit Staff">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full hover:bg-error/10 flex items-center justify-center text-error transition-colors" title="Hapus Staff">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center text-on-surface-variant">Belum ada data staff.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ======================= MODALS SANTRI ======================= --}}
{{-- Modal Tambah Santri --}}
<div id="modal-tambah-santri" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-primary/10 px-8 py-5 border-b border-primary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">person_add</span> Tambah Santri Baru
            </h3>
            <button onclick="document.getElementById('modal-tambah-santri').classList.add('hidden')" class="text-primary hover:bg-primary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('santri.store') }}" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input type="text" name="nama" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">NIS</label>
                    <input type="text" name="nis" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Kelas (Opsional)</label>
                    <select name="kelas_id" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($semuaKelas as $kls)
                        <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Tahun Masuk</label>
                    <input type="number" name="tahun_masuk" value="{{ date('Y') }}" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-tambah-santri').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Santri</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Santri --}}
<div id="modal-edit-santri" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-primary/10 px-8 py-5 border-b border-primary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">edit</span> Edit Data Santri
            </h3>
            <button onclick="document.getElementById('modal-edit-santri').classList.add('hidden')" class="text-primary hover:bg-primary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-edit-santri" method="POST" action="" class="p-8 space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input type="text" name="nama" id="edit-santri-nama" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">NIS</label>
                    <input type="text" name="nis" id="edit-santri-nis" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="edit-santri-jk" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="L">Laki-laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Kelas (Opsional)</label>
                    <select name="kelas_id" id="edit-santri-kelas" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($semuaKelas as $kls)
                        <option value="{{ $kls->id }}">{{ $kls->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Tahun Masuk</label>
                    <input type="number" name="tahun_masuk" id="edit-santri-tahun" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none">
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-edit-santri').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- ======================= MODALS STAFF ======================= --}}
{{-- Modal Tambah Staff --}}
<div id="modal-tambah-staff" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-secondary/10 px-8 py-5 border-b border-secondary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">person_add</span> Tambah Staff Baru
            </h3>
            <button onclick="document.getElementById('modal-tambah-staff').classList.add('hidden')" class="text-secondary hover:bg-secondary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('users.store') }}" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Email</label>
                    <input type="email" name="email" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Password</label>
                    <input type="password" name="password" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Peran (Role)</label>
                    <select name="role_id" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                        <option value="1">Admin</option>
                        <option value="2">Musyrif</option>
                        <option value="5">Ustadz</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">No. Telepon (Opsional)</label>
                    <input type="text" name="phone" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-tambah-staff').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-secondary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Staff</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Staff --}}
<div id="modal-edit-staff" class="fixed inset-0 z-50 hidden flex items-center justify-center fade-in-up">
    <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="bg-surface rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-white/20">
        <div class="bg-secondary/10 px-8 py-5 border-b border-secondary/20 flex justify-between items-center">
            <h3 class="text-lg font-bold text-secondary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">edit</span> Edit Data Staff
            </h3>
            <button onclick="document.getElementById('modal-edit-staff').classList.add('hidden')" class="text-secondary hover:bg-secondary/20 p-1 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-edit-staff" method="POST" action="" class="p-8 space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="edit-staff-nama" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Email</label>
                    <input type="email" name="email" id="edit-staff-email" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Password <span class="text-[9px] lowercase font-normal">(Kosongkan jika tdk diubah)</span></label>
                    <input type="password" name="password" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Peran (Role)</label>
                    <select name="role_id" id="edit-staff-role" required class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                        <option value="1">Admin</option>
                        <option value="2">Musyrif</option>
                        <option value="5">Ustadz</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">No. Telepon (Opsional)</label>
                    <input type="text" name="phone" id="edit-staff-phone" class="w-full h-12 px-4 bg-surface-container border border-outline-variant rounded-xl text-sm focus:ring-2 focus:ring-secondary outline-none">
                </div>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('modal-edit-staff').classList.add('hidden')" class="flex-1 py-3 border border-outline-variant text-on-surface-variant font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-secondary text-white font-bold rounded-xl hover:opacity-90 transition-opacity">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editSantri(id, nama, nis, jk, kelasId, tahun) {
        document.getElementById('form-edit-santri').action = `/admin/santri/${id}`;
        document.getElementById('edit-santri-nama').value = nama;
        document.getElementById('edit-santri-nis').value = nis;
        document.getElementById('edit-santri-jk').value = jk;
        document.getElementById('edit-santri-kelas').value = kelasId || '';
        document.getElementById('edit-santri-tahun').value = tahun || '';
        document.getElementById('modal-edit-santri').classList.remove('hidden');
    }

    function editStaff(id, nama, email, roleId, phone) {
        document.getElementById('form-edit-staff').action = `/admin/users/${id}`;
        document.getElementById('edit-staff-nama').value = nama;
        document.getElementById('edit-staff-email').value = email;
        document.getElementById('edit-staff-role').value = roleId;
        document.getElementById('edit-staff-phone').value = phone || '';
        document.getElementById('modal-edit-staff').classList.remove('hidden');
    }
</script>
@endsection
