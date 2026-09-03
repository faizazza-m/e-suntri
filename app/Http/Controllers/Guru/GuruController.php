<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Kelas;
use App\Models\NilaiAkademik;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\Pengumuman;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();
        
        // Mapel yang dipegang guru ini
        $mapelSaya = MataPelajaran::where('guru_id', $userId)->with('jadwal')->get();
        
        // Jadwal hari ini
        $hariEnum = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $dayName = $hariEnum[Carbon::now()->dayOfWeekIso - 1] ?? null;
        
        $jadwalHariIni = JadwalPelajaran::with(['mapel','kelas'])
            ->whereHas('mapel', fn($q) => $q->where('guru_id', $userId))
            ->when($dayName, fn($q) => $q->where('hari', $dayName))
            ->orderBy('jam_mulai')
            ->get();

        // Total santri yang diajar
        $kelasDiajar = JadwalPelajaran::whereHas('mapel', fn($q) => $q->where('guru_id', $userId))
            ->pluck('kelas_id')->unique();
        $totalSantri = Santri::whereIn('kelas_id', $kelasDiajar)->where('status', 'aktif')->count();
        
        // Nilai yang sudah diinput bulan ini
        $nilaiInputBulanIni = NilaiAkademik::whereHas('mapel', fn($q) => $q->where('guru_id', $userId))
            ->whereMonth('created_at', now()->month)
            ->count();
        
        // Pengumuman terbaru
        $pengumuman = Pengumuman::whereIn('target', ['semua', 'guru'])
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
        
        $totalMapel  = $mapelSaya->count();
        $totalJadwal = JadwalPelajaran::whereHas('mapel', fn($q) => $q->where('guru_id', $userId))->count();

        return view('guru.dashboard', compact(
            'mapelSaya', 'jadwalHariIni', 'dayName',
            'totalSantri', 'nilaiInputBulanIni',
            'pengumuman', 'totalMapel', 'totalJadwal'
        ));
    }

    public function nilai()
    {
        $userId   = auth()->id();
        // Mapel yang dipegang guru ini
        $mapelSaya = MataPelajaran::where('guru_id', $userId)->get();
        // Jika guru belum punya penugasan mapel, tampilkan semua mapel
        $semuaMapel = MataPelajaran::all();
        $mapelList  = $mapelSaya->isNotEmpty() ? $mapelSaya : $semuaMapel;
        $tidakDitugaskan = $mapelSaya->isEmpty();
        $kelas    = Kelas::all();

        $mapelId    = request('mapel_id', $mapelList->first()?->id);
        $kelasId    = request('kelas_id', $kelas->first()?->id);
        $semester   = request('semester', 1);
        $tahunAjaran = request('tahun_ajaran', now()->year . '/' . (now()->year + 1));
        
        $santriList = collect();
        if ($mapelId && $kelasId) {
            $santriList = Santri::where('kelas_id', $kelasId)
                ->where('status', 'aktif')
                ->with(['nilaiAkademik' => fn($q) => $q->where('mapel_id', $mapelId)
                    ->where('semester', $semester)
                    ->where('tahun_ajaran', $tahunAjaran)])
                ->orderBy('nama')
                ->get();
        }

        return view('guru.nilai', compact(
            'mapelList', 'kelas', 'santriList',
            'mapelId', 'kelasId', 'semester', 'tahunAjaran', 'tidakDitugaskan'
        ));
    }

    public function storeNilai(Request $request)
    {
        $request->validate([
            'santri_id'    => 'required|exists:santri,id',
            'mapel_id'     => 'required|exists:mata_pelajaran,id',
            'semester'     => 'required|in:1,2',
            'tahun_ajaran' => 'required|string|max:10',
            'nilai_harian' => 'nullable|numeric|min:0|max:100',
            'nilai_uas'    => 'nullable|numeric|min:0|max:100',
        ]);
        
        $harian  = $request->nilai_harian ?? 0;
        $uas     = $request->nilai_uas ?? 0;
        $akhir   = round(($harian * 0.2) + ($uas * 0.8), 2);
        $predikat = match(true) {
            $akhir >= 90 => 'A',
            $akhir >= 80 => 'B',
            $akhir >= 70 => 'C',
            $akhir >= 60 => 'D',
            default      => 'E',
        };
        
        NilaiAkademik::updateOrCreate(
            [
                'santri_id'    => $request->santri_id,
                'mapel_id'     => $request->mapel_id,
                'semester'     => $request->semester,
                'tahun_ajaran' => $request->tahun_ajaran,
            ],
            [
                'nilai_harian' => $request->nilai_harian,
                'nilai_uas'    => $request->nilai_uas,
                'nilai_akhir'  => $akhir,
                'predikat'     => $predikat,
                'created_at'   => now(),
            ]
        );
        
        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    public function jadwal()
    {
        $userId    = auth()->id();
        $hariOrder = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        
        $jadwals = JadwalPelajaran::with(['mapel','kelas'])
            ->whereHas('mapel', fn($q) => $q->where('guru_id', $userId))
            ->get()
            ->sortBy(fn($j) => array_search($j->hari, $hariOrder));
        
        $jadwalByHari = $jadwals->groupBy('hari');

        return view('guru.jadwal', compact('jadwalByHari', 'hariOrder'));
    }

    public function pengumuman()
    {
        $pengumumans = Pengumuman::whereIn('target', ['semua', 'guru'])
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(10);
        
        return view('guru.pengumuman', compact('pengumumans'));
    }
}
