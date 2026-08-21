<?php

namespace App\Http\Controllers\Mudir;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Setoran;
use App\Models\Kehadiran;
use App\Models\Perizinan;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Pengumuman;
use App\Models\RekamKesehatan;
use App\Models\HafalanSantri;
use App\Models\Halaqoh;
use App\Models\User;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MudirController extends Controller
{
    public function dashboard()
    {
        $today = today()->toDateString();
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        // ── KPI Cards ──────────────────────────────────────────────────
        $totalSantri    = Santri::where('status', 'aktif')->count();
        $totalMusyrif   = User::where('role_id', 2)->count();
        $totalUstadz    = User::where('role_id', 5)->count();

        // % Kehadiran hari ini
        $hadirCount    = Kehadiran::whereDate('tanggal', $today)->where('status', 'hadir')->count();
        $pctKehadiran  = $totalSantri > 0 ? round(($hadirCount / $totalSantri) * 100, 1) : 0;

        // Total setoran hari ini
        $setoranHariIni = Setoran::whereDate('tanggal', $today)->count();

        // Santri izin & sakit
        $santriIzin  = Perizinan::whereDate('tanggal_mulai', '<=', $today)
                        ->whereDate('tanggal_selesai', '>=', $today)
                        ->where('status', 'disetujui')->count();
        $santriSakit = Kehadiran::whereDate('tanggal', $today)->where('status', 'sakit')->count();

        // Keuangan bulan ini
        $totalTagihanBulanIni = Tagihan::where('bulan', $bulanIni)->where('tahun', $tahunIni)->sum('nominal');
        $totalLunasBulanIni   = Tagihan::where('bulan', $bulanIni)->where('tahun', $tahunIni)->where('status', 'lunas')->sum('nominal');
        $totalTunggakan       = Tagihan::where('status', 'belum')->sum('nominal');
        $pctKeuangan          = $totalTagihanBulanIni > 0 ? round(($totalLunasBulanIni / $totalTagihanBulanIni) * 100, 1) : 0;

        // ── Chart Hafalan (Bulan Ini - per hari) ───────────────────────────
        $hafalanBulanan = [];
        for ($d = 1; $d <= now()->day; $d++) {
            $date  = Carbon::createFromDate($tahunIni, $bulanIni, $d);
            $count = Setoran::whereDate('tanggal', $date->toDateString())->count();
            $hafalanBulanan[] = ['month' => $d . ' ' . $date->locale('id')->isoFormat('MMM'), 'val' => $count];
        }

        // ── Chart Kehadiran (Bulan Ini - per hari) ────────────────────────
        $kehadiranBulanan = [];
        for ($d = 1; $d <= now()->day; $d++) {
            $date  = Carbon::createFromDate($tahunIni, $bulanIni, $d);
            $hadir = Kehadiran::whereDate('tanggal', $date->toDateString())->where('status','hadir')->count();
            $total = Santri::where('status','aktif')->count();
            $kehadiranBulanan[] = [
                'month' => $d . ' ' . $date->locale('id')->isoFormat('MMM'),
                'val'   => $total > 0 ? round(($hadir / $total) * 100, 1) : 0,
            ];
        }

        // ── Distribusi Santri per Kelas ────────────────────────────────
        $distribusiKelas = Kelas::withCount(['santri' => function($q) {
            $q->where('status', 'aktif');
        }])->get()->map(fn($k) => ['label' => $k->nama, 'val' => $k->santri_count]);

        // ── Top 10 Leaderboard Hafalan ─────────────────────────────────
        $leaderboard = Santri::with(['hafalan', 'kelas', 'halaqoh.musyrif'])
            ->where('status', 'aktif')
            ->get()
            ->sortByDesc(fn($s) => optional($s->hafalan)->juz_selesai ?? 0)
            ->take(10)
            ->values();

        // ── Monitoring Musyrif (sudah input hari ini?) ─────────────────
        $halaqohList = Halaqoh::with('musyrif')->get()->map(function($h) use ($today) {
            $sudahInputAbsensi = Kehadiran::whereDate('tanggal', $today)
                ->whereHas('santri', fn($q) => $q->where('halaqoh_id', $h->id))
                ->exists();
            $sudahInputSetoran = Setoran::whereDate('tanggal', $today)
                ->whereHas('santri', fn($q) => $q->where('halaqoh_id', $h->id))
                ->exists();
            $jumlahSantri = Santri::where('halaqoh_id', $h->id)->where('status','aktif')->count();

            return [
                'nama'              => $h->nama,
                'musyrif'           => $h->musyrif->name ?? '—',
                'jumlah_santri'     => $jumlahSantri,
                'absensi'           => $sudahInputAbsensi,
                'setoran'           => $sudahInputSetoran,
            ];
        });

        // ── Alert: Santri Sakit Hari Ini ───────────────────────────────
        $santriSakitAlert = RekamKesehatan::with('santri.kelas')
            ->whereDate('tanggal', $today)
            ->get();

        // ── Alert: Santri Belum Absen / Alpha Hari Ini ─────────────────
        $santriHadirIzinSakitIds = Kehadiran::whereDate('tanggal', $today)
            ->whereIn('status', ['hadir', 'izin', 'sakit'])
            ->pluck('santri_id');
        
        $santriAlphaAlert = Santri::with('kelas')
            ->where('status', 'aktif')
            ->whereNotIn('id', $santriHadirIzinSakitIds)
            ->get();

        // ── Alert: Santri Kehadiran Rendah (< 75% sebulan ini) ─────────
        $santriRendahKehadiran = collect();
        try {
            $hariKerjaBulanIni = Kehadiran::whereYear('tanggal', $tahunIni)
                ->whereMonth('tanggal', $bulanIni)
                ->select('tanggal')
                ->distinct()
                ->count();

            if ($hariKerjaBulanIni > 0) {
                $santriRendahKehadiran = Santri::with('kelas')
                    ->where('status', 'aktif')
                    ->get()
                    ->map(function($s) use ($bulanIni, $tahunIni, $hariKerjaBulanIni) {
                        $hadirCount = Kehadiran::where('santri_id', $s->id)
                            ->whereYear('tanggal', $tahunIni)
                            ->whereMonth('tanggal', $bulanIni)
                            ->where('status', 'hadir')
                            ->count();
                        $pct = round(($hadirCount / $hariKerjaBulanIni) * 100, 1);
                        return ['santri' => $s, 'pct' => $pct, 'hadir' => $hadirCount, 'total' => $hariKerjaBulanIni];
                    })
                    ->filter(fn($item) => $item['pct'] < 75)
                    ->sortBy('pct')
                    ->take(8)
                    ->values();
            }
        } catch (\Exception $e) {}

        // ── Agenda / Pengumuman Mendatang ──────────────────────────────
        $agendas = Pengumuman::where('published_at', '>=', now()->startOfDay())
            ->orderBy('published_at')
            ->take(4)
            ->get();
        if ($agendas->isEmpty()) {
            $agendas = Pengumuman::orderByDesc('published_at')->take(4)->get();
        }

        // ── Keuangan per Jenis Tagihan (bulan ini) ─────────────────────
        $keuanganPerJenis = DB::table('tagihan')
            ->join('jenis_tagihan', 'tagihan.jenis_id', '=', 'jenis_tagihan.id')
            ->where('tagihan.bulan', $bulanIni)
            ->where('tagihan.tahun', $tahunIni)
            ->select(
                'jenis_tagihan.nama',
                DB::raw('SUM(tagihan.nominal) as total'),
                DB::raw("SUM(CASE WHEN tagihan.status = 'lunas' THEN tagihan.nominal ELSE 0 END) as lunas")
            )
            ->groupBy('jenis_tagihan.nama')
            ->get();

        // ── Setoran Hari Ini Detail ─────────────────────────────────────
        $setoranHariIniList = Setoran::with(['santri.kelas', 'musyrif'])
            ->whereDate('tanggal', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mudir.dashboard', compact(
            'totalSantri', 'totalMusyrif', 'totalUstadz',
            'pctKehadiran', 'hadirCount',
            'setoranHariIni', 'santriIzin', 'santriSakit',
            'totalTagihanBulanIni', 'totalLunasBulanIni', 'totalTunggakan', 'pctKeuangan',
            'hafalanBulanan', 'kehadiranBulanan', 'distribusiKelas',
            'leaderboard', 'halaqohList',
            'santriSakitAlert', 'santriAlphaAlert', 'santriRendahKehadiran',
            'agendas', 'keuanganPerJenis', 'setoranHariIniList'
        ));
    }

    public function hafalan()
    {
        $santris = Santri::with(['hafalan', 'kelas', 'halaqoh.musyrif'])->get();

        $totalHafiz = $santris->filter(fn($s) => $s->hafalan && $s->hafalan->juz_selesai >= 30)->count();
        $totalJuz = $santris->sum(fn($s) => $s->hafalan ? $s->hafalan->juz_selesai : 0);
        $rataJuz = $santris->count() > 0 ? round($totalJuz / $santris->count(), 1) : 0;
        $totalSetoranHariIni = Setoran::whereDate('tanggal', now()->toDateString())->count();
        
        $summary = [
            ['title' => 'Total Hafizh (30 Juz)', 'value' => $totalHafiz, 'color' => 'bg-primary', 'icon' => 'workspace_premium'],
            ['title' => 'Rata-rata Capaian Juz', 'value' => $rataJuz, 'color' => 'bg-secondary', 'icon' => 'auto_graph'],
            ['title' => 'Setoran Hari Ini', 'value' => $totalSetoranHariIni, 'color' => 'bg-yellow-600', 'icon' => 'record_voice_over'],
            ['title' => 'Target Tercapai', 'value' => ($rataJuz > 0 ? round(($rataJuz/30)*100).'%' : '0%'), 'color' => 'bg-primary-container text-white', 'icon' => 'track_changes'],
        ];

        $topSantri = $santris->filter(fn($s) => $s->hafalan)->sortByDesc(fn($s) => $s->hafalan->juz_selesai)->take(3);
        $recentSetoran = Setoran::with(['santri.kelas', 'musyrif'])->whereDate('tanggal', now()->toDateString())->orderBy('created_at', 'desc')->take(10)->get();

        $weekData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Setoran::whereDate('tanggal', $date->toDateString())->count();
            $weekData->push(['day' => substr($date->locale('id')->dayName, 0, 3), 'height' => $count > 0 ? min(100, $count * 10) : 5, 'val' => $count]);
        }

        $halaqohs = Halaqoh::with(['musyrif', 'santri'])->orderBy('nama')->get();

        return view('mudir.hafalan', compact('summary', 'recentSetoran', 'topSantri', 'santris', 'weekData', 'halaqohs'));
    }

    public function kehadiran(\Illuminate\Http\Request $request)
    {
        $query = Kehadiran::with(['santri.kelas', 'santri.halaqoh'])->orderBy('tanggal', 'desc');

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $kehadiran = $query->paginate(20)->withQueryString();

        $statsQuery = Kehadiran::query();
        if ($request->has('start_date') && $request->start_date != '') $statsQuery->whereDate('tanggal', '>=', $request->start_date);
        if ($request->has('end_date') && $request->end_date != '') $statsQuery->whereDate('tanggal', '<=', $request->end_date);

        $stats = [
            'hadir' => (clone $statsQuery)->where('status', 'hadir')->count(),
            'izin' => (clone $statsQuery)->where('status', 'izin')->count(),
            'sakit' => (clone $statsQuery)->where('status', 'sakit')->count(),
            'alpa' => (clone $statsQuery)->where('status', 'alpa')->count(),
            'total' => $statsQuery->count(),
        ];

        return view('mudir.kehadiran', compact('kehadiran', 'stats'));
    }

    public function keuangan(\Illuminate\Http\Request $request)
    {
        $stats = [
            'total_tagihan' => Tagihan::sum('nominal'),
            'sudah_dibayar' => Tagihan::where('status', 'lunas')->sum('nominal'),
            'belum_dibayar' => Tagihan::where('status', '!=', 'lunas')->sum('nominal'),
            'jatuh_tempo' => Tagihan::where('status', '!=', 'lunas')->whereDate('jatuh_tempo', '<=', now()->toDateString())->sum('nominal'),
            'count_belum_dibayar' => Tagihan::where('status', '!=', 'lunas')->count()
        ];

        $query = Tagihan::with(['santri.kelas', 'jenis'])->orderBy('created_at', 'desc');
        if ($request->has('kelas') && $request->kelas != '') {
            $query->whereHas('santri', fn($q) => $q->where('kelas_id', $request->kelas));
        }

        $tagihans = $query->paginate(20);
        $riwayatPembayaran = Pembayaran::with(['santri', 'tagihan.jenis'])->orderBy('created_at', 'desc')->take(10)->get();
        $kelas = Kelas::all();

        return view('mudir.keuangan', compact('stats', 'tagihans', 'riwayatPembayaran', 'kelas'));
    }

    public function santri()
    {
        $santris = Santri::with(['kelas', 'halaqoh'])->get();
        $semuaKelas = Kelas::orderBy('nama')->get();
        $semuaHalaqoh = Halaqoh::orderBy('nama')->get();

        return view('mudir.santri', compact('santris', 'semuaKelas', 'semuaHalaqoh'));
    }

    public function pengumuman()
    {
        $pengumumans = Pengumuman::with('pembuat')->orderBy('is_pinned', 'desc')->orderBy('created_at', 'desc')->paginate(10);
            
        $stats = [
            'total' => Pengumuman::count(),
            'wali' => Pengumuman::where('target', 'wali')->count(),
            'musyrif' => Pengumuman::where('target', 'musyrif')->count(),
            'guru' => Pengumuman::where('target', 'guru')->count(),
            'santri' => Pengumuman::where('target', 'santri')->count()
        ];

        return view('mudir.pengumuman', compact('pengumumans', 'stats'));
    }
}

