<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\User;
use App\Models\Santri;
use App\Models\NilaiAkademik;
use App\Models\Ujian;
use Carbon\Carbon;

class AkademikController extends Controller
{
    public function index()
    {
        // ── Stats ─────────────────────────────────────────────────────────
        $totalKelas = Kelas::count();
        $totalMapel = MataPelajaran::count();
        $totalGuru  = User::whereIn('role_id', [2, 5])->count(); // 2: Musyrif, 5: Ustadz

        // ── Jadwal Hari Ini ───────────────────────────────────────────────
        $hariArr = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu'
        ];
        $hariIniIndex = now()->dayOfWeek;
        $hariIniNama  = $hariArr[$hariIniIndex];

        // Jika akhir pekan, fallback ke Senin (supaya tidak kosong pas dites)
        if ($hariIniIndex == 0 || $hariIniIndex == 6) {
            $hariIniNama = 'Senin';
        }

        $jadwalHariIni = JadwalPelajaran::with(['kelas', 'mapel', 'mapel.guru'])
                            ->where('hari', $hariIniNama)
                            ->orderBy('jam_mulai')
                            ->get();

        // ── Daftar Kelas ──────────────────────────────────────────────────
        $daftarKelas = Kelas::with('waliKelas')->withCount('santri')->get();

        // ── Data Referensi untuk Form Modal ───────────────────────────────
        $semuaGuru = User::whereIn('role_id', [2, 5])->orderBy('name')->get(); // 2: Musyrif, 5: Ustadz
        $semuaMapel = MataPelajaran::all();
        $semuaSantri = \App\Models\Santri::orderBy('nama')->get(); // Untuk assign santri ke kelas

        // ── Seluruh Jadwal (Untuk Modal Semua Jadwal) ─────────────────────
        // Urutkan berdasarkan custom order hari (Senin=1 ... Ahad=7)
        $seluruhJadwal = JadwalPelajaran::with(['kelas', 'mapel', 'mapel.guru'])
                            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                            ->orderBy('jam_mulai')
                            ->get();

        return view('admin.akademik', compact(
            'totalKelas',
            'totalMapel',
            'totalGuru',
            'hariIniNama',
            'jadwalHariIni',
            'seluruhJadwal',
            'daftarKelas',
            'semuaGuru',
            'semuaMapel',
            'semuaSantri'
        ));
    }

    public function storeKelas(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:100',
            'tingkat'    => 'required|string|max:50',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'kapasitas'  => 'required|integer|min:1',
            'santri_ids' => 'nullable|array',
            'santri_ids.*' => 'exists:santri,id',
        ]);

        $kelas = Kelas::create([
            'nama' => $validated['nama'],
            'tingkat' => $validated['tingkat'],
            'wali_kelas_id' => $validated['wali_kelas_id'] ?? null,
            'kapasitas' => $validated['kapasitas'],
        ]);

        if (!empty($validated['santri_ids'])) {
            \App\Models\Santri::whereIn('id', $validated['santri_ids'])->update(['kelas_id' => $kelas->id]);
        }

        return redirect()->route('akademik')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function storeJadwal(Request $request)
    {
        $validated = $request->validate([
            'kelas_id'    => 'required|exists:kelas,id',
            'mapel_id'    => 'required|exists:mata_pelajaran,id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruang'       => 'nullable|string|max:50',
        ]);

        JadwalPelajaran::create($validated);

        return redirect()->route('akademik')->with('success', 'Jadwal pelajaran berhasil ditambahkan!');
    }

    public function updateJadwal(Request $request, $id)
    {
        $jadwal = JadwalPelajaran::findOrFail($id);
        $validated = $request->validate([
            'kelas_id'    => 'required|exists:kelas,id',
            'mapel_id'    => 'required|exists:mata_pelajaran,id',
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruang'       => 'nullable|string|max:50',
        ]);

        $jadwal->update($validated);

        return redirect()->route('akademik')->with('success', 'Jadwal pelajaran berhasil diperbarui!');
    }

    public function updateKelas(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $validated = $request->validate([
            'nama'       => 'required|string|max:100',
            'tingkat'    => 'required|string|max:50',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'kapasitas'  => 'required|integer|min:1',
            'santri_ids' => 'nullable|array',
            'santri_ids.*' => 'exists:santri,id',
        ]);

        $kelas->update([
            'nama' => $validated['nama'],
            'tingkat' => $validated['tingkat'],
            'wali_kelas_id' => $validated['wali_kelas_id'] ?? null,
            'kapasitas' => $validated['kapasitas'],
        ]);

        // Reset semua santri di kelas ini sebelumnya
        \App\Models\Santri::where('kelas_id', $kelas->id)->update(['kelas_id' => null]);
        
        // Update dengan santri yang baru dipilih
        if (!empty($validated['santri_ids'])) {
            \App\Models\Santri::whereIn('id', $validated['santri_ids'])->update(['kelas_id' => $kelas->id]);
        }

        return redirect()->route('akademik')->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroyKelas($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('akademik')->with('success', 'Kelas berhasil dihapus!');
    }

    public function destroyJadwal($id)
    {
        $jadwal = JadwalPelajaran::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('akademik')->with('success', 'Jadwal berhasil dihapus!');
    }

    // ── MATA PELAJARAN (MAPEL) CRUD ─────────────────────────────────────
    
    public function mapelIndex()
    {
        $mapels = MataPelajaran::with('guru')->get();
        $gurus = User::whereIn('role_id', [2, 5])->orderBy('name')->get(); // 2: Musyrif, 5: Ustadz
        return view('admin.akademik_mapel', compact('mapels', 'gurus'));
    }

    public function mapelStore(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:100',
            'kode'    => 'nullable|string|max:20',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        MataPelajaran::create($validated);
        return redirect()->route('akademik.mapel')->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }

    public function mapelUpdate(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $validated = $request->validate([
            'nama'    => 'required|string|max:100',
            'kode'    => 'nullable|string|max:20',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        $mapel->update($validated);
        return redirect()->route('akademik.mapel')->with('success', 'Mata Pelajaran berhasil diperbarui!');
    }

    public function mapelDestroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->delete();
        return redirect()->route('akademik.mapel')->with('success', 'Mata Pelajaran berhasil dihapus!');
    }

    // ── DATA NILAI CRUD ──────────────────────────────────────────────────
    
    public function nilaiIndex()
    {
        $nilais = NilaiAkademik::with(['santri', 'mapel'])->orderBy('created_at', 'desc')->get();
        $santris = Santri::all();
        $mapels = MataPelajaran::all();
        return view('admin.akademik_nilai', compact('nilais', 'santris', 'mapels'));
    }

    private function calculateNilai($harian, $uts, $uas)
    {
        $akhir = ($harian * 0.4) + ($uts * 0.3) + ($uas * 0.3);
        $predikat = 'E';
        if ($akhir >= 90) $predikat = 'A';
        elseif ($akhir >= 80) $predikat = 'B';
        elseif ($akhir >= 70) $predikat = 'C';
        elseif ($akhir >= 60) $predikat = 'D';

        return ['akhir' => round($akhir, 2), 'predikat' => $predikat];
    }

    public function nilaiStore(Request $request)
    {
        $validated = $request->validate([
            'santri_id'    => 'required|exists:santri,id',
            'mapel_id'     => 'required|exists:mata_pelajaran,id',
            'semester'     => 'required|integer|min:1|max:2',
            'tahun_ajaran' => 'required|string',
            'nilai_harian' => 'nullable|numeric|min:0|max:100',
            'nilai_uts'    => 'nullable|numeric|min:0|max:100',
            'nilai_uas'    => 'nullable|numeric|min:0|max:100',
        ]);

        $calc = $this->calculateNilai(
            $request->nilai_harian ?? 0, 
            $request->nilai_uts ?? 0, 
            $request->nilai_uas ?? 0
        );

        $validated['nilai_akhir'] = $calc['akhir'];
        $validated['predikat'] = $calc['predikat'];

        NilaiAkademik::create($validated);
        return redirect()->route('akademik.nilai')->with('success', 'Data Nilai berhasil disimpan!');
    }

    public function nilaiUpdate(Request $request, $id)
    {
        $nilai = NilaiAkademik::findOrFail($id);
        $validated = $request->validate([
            'santri_id'    => 'required|exists:santri,id',
            'mapel_id'     => 'required|exists:mata_pelajaran,id',
            'semester'     => 'required|integer|min:1|max:2',
            'tahun_ajaran' => 'required|string',
            'nilai_harian' => 'nullable|numeric|min:0|max:100',
            'nilai_uts'    => 'nullable|numeric|min:0|max:100',
            'nilai_uas'    => 'nullable|numeric|min:0|max:100',
        ]);

        $calc = $this->calculateNilai(
            $request->nilai_harian ?? 0, 
            $request->nilai_uts ?? 0, 
            $request->nilai_uas ?? 0
        );

        $validated['nilai_akhir'] = $calc['akhir'];
        $validated['predikat'] = $calc['predikat'];

        $nilai->update($validated);
        return redirect()->route('akademik.nilai')->with('success', 'Data Nilai berhasil diperbarui!');
    }

    public function nilaiDestroy($id)
    {
        $nilai = NilaiAkademik::findOrFail($id);
        $nilai->delete();
        return redirect()->route('akademik.nilai')->with('success', 'Data Nilai berhasil dihapus!');
    }

    // ── UJIAN & TUGAS ────────────────────────────────────────────────────

    public function ujianIndex()
    {
        $ujians = Ujian::with(['mapel', 'kelas'])->orderBy('tanggal', 'asc')->get();
        $mapels = MataPelajaran::all();
        $kelas = Kelas::all();
        return view('admin.akademik_ujian', compact('ujians', 'mapels', 'kelas'));
    }

    public function ujianStore(Request $request)
    {
        $validated = $request->validate([
            'judul'       => 'required|string|max:150',
            'tipe'        => 'required|in:Ujian,Tugas',
            'mapel_id'    => 'required|exists:mata_pelajaran,id',
            'kelas_id'    => 'required|exists:kelas,id',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'keterangan'  => 'nullable|string',
        ]);

        Ujian::create($validated);
        return redirect()->route('akademik.ujian')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function ujianDestroy($id)
    {
        $ujian = Ujian::findOrFail($id);
        $ujian->delete();
        return redirect()->route('akademik.ujian')->with('success', 'Jadwal berhasil dihapus!');
    }

    // ── RAPORT ───────────────────────────────────────────────────────────

    public function raportIndex(Request $request)
    {
        $santris = Santri::all();
        $selectedSantri = null;
        $nilais = collect();

        if ($request->has('santri_id')) {
            $selectedSantri = Santri::with('kelas')->findOrFail($request->santri_id);
            $nilais = NilaiAkademik::with('mapel')
                ->where('santri_id', $selectedSantri->id)
                ->where('semester', $request->get('semester', 1))
                ->get();
        }

        return view('admin.akademik_raport', compact('santris', 'selectedSantri', 'nilais'));
    }
}
