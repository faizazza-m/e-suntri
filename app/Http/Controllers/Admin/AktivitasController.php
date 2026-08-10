<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setoran;
use App\Models\Perizinan;
use App\Models\Tagihan;
use App\Models\Pengumuman;
use App\Models\RekamKesehatan;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AktivitasController extends Controller
{
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

    public function index(Request $request)
    {
        $activities = collect();

        // Ambil data 100 terakhir dari masing-masing tabel untuk digabung
        $limit = 100;

        // Setoran
        Setoran::with('santri')->orderByDesc('created_at')->take($limit)->get()
            ->each(function ($s) use (&$activities) {
                if (!$s->santri) return;
                $jenisLabel = $this->jenisSetoran[$s->jenis] ?? ucfirst($s->jenis);
                $surahText  = $s->surah ? " — {$s->surah}" : '';
                $activities->push([
                    'dot'       => 'bg-primary',
                    'icon'      => 'menu_book',
                    'html'      => '<strong>' . e($s->santri->nama) . '</strong> menyelesaikan setoran <strong>' . $jenisLabel . '</strong>' . $surahText,
                    'timestamp' => Carbon::parse($s->created_at),
                    'tag'       => 'Tahfizh',
                    'tagColor'  => 'bg-primary/10 text-primary border-primary/20',
                ]);
            });

        // Perizinan
        Perizinan::with('santri')->orderByDesc('created_at')->take($limit)->get()
            ->each(function ($p) use (&$activities) {
                if (!$p->santri) return;
                $jenisLabel = $this->jenisPerizinan[$p->jenis] ?? ucfirst($p->jenis);
                $activities->push([
                    'dot'       => 'bg-orange-500',
                    'icon'      => 'directions_run',
                    'html'      => '<strong>' . e($p->santri->nama) . '</strong> mengajukan izin ' . $jenisLabel . '.',
                    'timestamp' => Carbon::parse($p->created_at),
                    'tag'       => 'Perizinan',
                    'tagColor'  => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
                ]);
            });

        // Keuangan (Tagihan Lunas)
        Tagihan::with(['santri','jenis'])->where('status', 'lunas')->orderByDesc('created_at')->take($limit)->get()
            ->each(function ($t) use (&$activities) {
                if (!$t->santri) return;
                $namaTagihan = $t->jenis->nama ?? 'Tagihan';
                $activities->push([
                    'dot'       => 'bg-green-500',
                    'icon'      => 'payments',
                    'html'      => 'Pembayaran <strong>' . e($namaTagihan) . '</strong> atas nama <strong>' . e($t->santri->nama) . '</strong> berhasil dikonfirmasi.',
                    'timestamp' => Carbon::parse($t->created_at),
                    'tag'       => 'Keuangan',
                    'tagColor'  => 'bg-green-500/10 text-green-500 border-green-500/20',
                ]);
            });

        // Kesehatan
        RekamKesehatan::with('santri')->orderByDesc('created_at')->take($limit)->get()
            ->each(function ($k) use (&$activities) {
                if (!$k->santri) return;
                $activities->push([
                    'dot'       => 'bg-rose-500',
                    'icon'      => 'medical_services',
                    'html'      => '<strong>' . e($k->santri->nama) . '</strong> berkunjung ke UKS dengan keluhan: ' . e($k->keluhan),
                    'timestamp' => Carbon::parse($k->created_at),
                    'tag'       => 'Kesehatan',
                    'tagColor'  => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                ]);
            });

        // Pengumuman
        Pengumuman::with('pembuat')->orderByDesc('created_at')->take($limit)->get()
            ->each(function ($p) use (&$activities) {
                $activities->push([
                    'dot'       => 'bg-cyan-500',
                    'icon'      => 'campaign',
                    'html'      => 'Pengumuman baru: <strong>' . e($p->judul) . '</strong> ditambahkan oleh ' . ($p->pembuat->name ?? 'Admin'),
                    'timestamp' => Carbon::parse($p->created_at),
                    'tag'       => 'Pengumuman',
                    'tagColor'  => 'bg-cyan-500/10 text-cyan-500 border-cyan-500/20',
                ]);
            });

        // Urutkan semua secara descending
        $sortedActivities = $activities->sortByDesc(function($item) {
            return $item['timestamp']->timestamp;
        })->values();

        // Manual Pagination
        $perPage = 20;
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;
        
        $pagedData = $sortedActivities->slice($offset, $perPage)->all();
        
        $paginator = new LengthAwarePaginator(
            $pagedData,
            $sortedActivities->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.aktivitas', compact('paginator'));
    }
}
