<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Setoran;
use App\Models\Kehadiran;
use App\Models\Perizinan;
use App\Models\Tagihan;
use App\Models\Pengumuman;
use App\Models\RekamKesehatan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Label manusiawi untuk enum
    private $jenisSetoran = [
        'hafalan_baru' => 'Hafalan Baru',
        'murajaah'     => "Muraja'ah",
        'tasmi'        => "Tasmi'",
    ];

    private $jenisPerizinan = [
        'pulang'          => 'Pulang',
        'sakit'           => 'Sakit',
        'kegiatan_luar'   => 'Kegiatan Luar',
        'lainnya'         => 'Lainnya',
    ];

    public function index()
    {
        $today = today()->toDateString();

        // ── Stats ──────────────────────────────────────────────────────
        $totalSantri    = Santri::where('status', 'aktif')->count();
        $hadirCount     = Kehadiran::whereDate('tanggal', $today)->where('status', 'hadir')->count();
        $hadirHariIni   = $totalSantri > 0 ? round(($hadirCount / $totalSantri) * 100, 1) : 0;
        $setoranHariIni = Setoran::whereDate('tanggal', $today)->count();
        $santriIzin     = Perizinan::whereDate('tanggal_mulai', '<=', $today)
                            ->whereDate('tanggal_selesai', '>=', $today)
                            ->where('status', 'disetujui')->count();
        $santriSakit    = Kehadiran::whereDate('tanggal', $today)->where('status', 'sakit')->count();
        $tagihanPending = Tagihan::where('status', 'belum')->count();

        // ── Chart Hafalan Bulanan (12 bulan terakhir) ─────────────────
        $hafalanBulanan = [];
        for ($m = 11; $m >= 0; $m--) {
            $date  = now()->subMonths($m);
            $count = Setoran::whereYear('tanggal', $date->year)->whereMonth('tanggal', $date->month)->count();
            $hafalanBulanan[] = ['month' => $date->locale('id')->isoFormat('MMM'), 'val' => $count];
        }
        $maxHafalan = max(array_column($hafalanBulanan, 'val')) ?: 1;

        // ── Chart Hafalan Pekanan (8 minggu terakhir) ─────────────────
        $hafalanPekanan = [];
        for ($w = 7; $w >= 0; $w--) {
            $start = now()->startOfWeek()->subWeeks($w);
            $end   = $start->copy()->endOfWeek();
            $count = Setoran::whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])->count();
            $hafalanPekanan[] = ['month' => 'Mg ' . now()->subWeeks($w)->weekOfYear, 'val' => $count];
        }
        $maxPekanan = max(array_column($hafalanPekanan, 'val')) ?: 1;

        // ── Aktivitas Terbaru (semua dari DB, label bersih) ────────────
        $activities = collect();

        // Setoran terbaru
        Setoran::with('santri')->orderByDesc('created_at')->take(4)->get()
            ->each(function ($s) use (&$activities) {
                if (!$s->santri) return;
                $jenisMap = ['hafalan_baru' => 'Ziyadah', 'murajaah' => 'Muraja\'ah', 'tasmi' => 'Tasmi\''];
                $jenisLabel = $jenisMap[$s->jenis] ?? ucfirst($s->jenis);
                $surahText  = $s->surah ? " — {$s->surah}" : '';
                $activities->push([
                    'dot'       => 'bg-primary',
                    'html'      => '<strong>' . e($s->santri->nama) . '</strong> menyelesaikan setoran <strong>' . $jenisLabel . '</strong>' . $surahText,
                    'timestamp' => Carbon::parse($s->created_at),
                    'time'      => Carbon::parse($s->created_at)->diffForHumans(),
                    'tag'       => 'Tahfizh',
                ]);
            });

        // Perizinan terbaru
        Perizinan::with('santri')->orderByDesc('created_at')->take(2)->get()
            ->each(function ($p) use (&$activities) {
                if (!$p->santri) return;
                $jenisLabel = $this->jenisPerizinan[$p->jenis] ?? ucfirst($p->jenis);
                $activities->push([
                    'dot'       => 'bg-orange-500',
                    'html'      => '<strong>' . e($p->santri->nama) . '</strong> mengajukan izin ' . $jenisLabel . '.',
                    'timestamp' => Carbon::parse($p->created_at),
                    'time'      => Carbon::parse($p->created_at)->diffForHumans(),
                    'tag'       => 'Perizinan',
                ]);
            });

        // Tagihan lunas terbaru
        Tagihan::with(['santri','jenis'])->where('status', 'lunas')->orderByDesc('created_at')->take(2)->get()
            ->each(function ($t) use (&$activities) {
                if (!$t->santri) return;
                $namaTagihan = $t->jenis->nama ?? 'Tagihan';
                $activities->push([
                    'dot'       => 'bg-green-500',
                    'html'      => 'Pembayaran <strong>' . e($namaTagihan) . '</strong> atas nama <strong>' . e($t->santri->nama) . '</strong> berhasil dikonfirmasi.',
                    'timestamp' => Carbon::parse($t->created_at),
                    'time'      => Carbon::parse($t->created_at)->diffForHumans(),
                    'tag'       => 'Keuangan',
                ]);
            });

        // Kesehatan terbaru
        RekamKesehatan::with('santri')->orderByDesc('created_at')->take(2)->get()
            ->each(function ($k) use (&$activities) {
                if (!$k->santri) return;
                $activities->push([
                    'dot'       => 'bg-rose-500',
                    'html'      => '<strong>' . e($k->santri->nama) . '</strong> berkunjung ke UKS dengan keluhan: ' . e($k->keluhan),
                    'timestamp' => Carbon::parse($k->created_at),
                    'time'      => Carbon::parse($k->created_at)->diffForHumans(),
                    'tag'       => 'Kesehatan',
                ]);
            });

        // Pengumuman terbaru
        Pengumuman::orderByDesc('created_at')->take(2)->get()
            ->each(function ($p) use (&$activities) {
                $activities->push([
                    'dot'       => 'bg-cyan-500',
                    'html'      => 'Pengumuman baru: <strong>' . e($p->judul) . '</strong>',
                    'timestamp' => Carbon::parse($p->created_at),
                    'time'      => Carbon::parse($p->created_at)->diffForHumans(),
                    'tag'       => 'Pengumuman',
                ]);
            });

        // Urutkan dari yang paling baru
        $activities = $activities->sortByDesc(function($item) {
            return $item['timestamp']->timestamp;
        })->take(6)->values();

        // ── Agenda dari tabel pengumuman (is_pinned / upcoming) ────────
        $agendas = Pengumuman::where('published_at', '>=', now()->startOfDay())
            ->orderBy('published_at')
            ->take(3)
            ->get();

        // Fallback: ambil 3 pengumuman terbaru kalau tidak ada yang upcoming
        if ($agendas->isEmpty()) {
            $agendas = Pengumuman::orderByDesc('published_at')->take(3)->get();
        }

        // Data Santri Sakit Global Hari Ini
        $santriSakitGlobal = \App\Models\RekamKesehatan::with('santri')
            ->whereDate('tanggal', now()->toDateString())
            ->get();

        // ── Data Detail Kehadiran untuk Modal ──────────────────────────
        $kehadiranHariIni = Kehadiran::with('santri.kelas')->whereDate('tanggal', $today)->get();
        $hadirList = $kehadiranHariIni->where('status', 'hadir')->pluck('santri')->filter();
        $sakitList = $kehadiranHariIni->where('status', 'sakit')->pluck('santri')->filter();
        
        $izinRecords = Perizinan::with('santri.kelas')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->where('status', 'disetujui')
            ->get();
        $izinList = $izinRecords->pluck('santri')->filter();

        $allActiveSantri = Santri::with('kelas')->where('status', 'aktif')->get();
        $recordedIds = collect()
            ->merge($hadirList->pluck('id'))
            ->merge($sakitList->pluck('id'))
            ->merge($izinList->pluck('id'))
            ->unique();
            
        $alphaList = $allActiveSantri->whereNotIn('id', $recordedIds);

        return view('admin.dashboard', compact(
            'totalSantri', 'hadirHariIni', 'setoranHariIni',
            'santriIzin', 'santriSakit', 'tagihanPending',
            'hafalanBulanan', 'maxHafalan',
            'hafalanPekanan', 'maxPekanan',
            'activities', 'agendas', 'santriSakitGlobal',
            'hadirList', 'sakitList', 'izinList', 'alphaList'
        ));
    }
}
