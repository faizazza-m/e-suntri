<?php

namespace App\Http\Controllers\Musyrif;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Halaqoh;
use App\Models\Santri;
use App\Models\Setoran;
use App\Models\HafalanSantri;

class MusyrifController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        
        // Ambil halaqoh yang dibina oleh musyrif ini
        $halaqohs = Halaqoh::with('santri')->where('musyrif_id', $userId)->get();
        $halaqohIds = $halaqohs->pluck('id');
        
        $santris = Santri::whereIn('halaqoh_id', $halaqohIds)->with('hafalan')->get();
        $totalSantri = $santris->count();
        
        $totalHafiz = $santris->filter(function($s) {
            return $s->hafalan && $s->hafalan->juz_selesai >= 30;
        })->count();
        
        $totalJuz = $santris->sum(function($s) {
            return $s->hafalan ? $s->hafalan->juz_selesai : 0;
        });
        
        $rataJuz = $totalSantri > 0 ? round($totalJuz / $totalSantri, 1) : 0;

        $santriIds = $santris->pluck('id');
        $setoranHariIni = Setoran::whereIn('santri_id', $santriIds)
                            ->whereDate('tanggal', now()->toDateString())
                            ->count();
                            
        $recentSetoran = Setoran::with('santri')
                            ->whereIn('santri_id', $santriIds)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // Data Santri Sakit (dari Rekam Kesehatan)
        $santriSakitHariIni = \App\Models\RekamKesehatan::with('santri')
            ->whereIn('santri_id', $santriIds)
            ->whereDate('tanggal', now()->toDateString())
            ->get();

        // Grafik Keaktifan (Setoran 7 Hari Terakhir)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->locale('id')->isoFormat('dddd');
            $count = Setoran::whereIn('santri_id', $santriIds)
                        ->whereDate('tanggal', $date->toDateString())
                        ->count();
            $chartData[] = $count;
        }

        // Kehadiran hari ini
        $kehadiranHariIni = \App\Models\Kehadiran::whereIn('santri_id', $santriIds)
            ->whereDate('tanggal', now()->toDateString())
            ->get()
            ->keyBy('santri_id');

        return view('musyrif.dashboard', compact(
            'halaqohs', 'totalSantri', 'totalHafiz', 'rataJuz', 'setoranHariIni', 
            'recentSetoran', 'santris', 'santriSakitHariIni', 'chartLabels', 'chartData', 'kehadiranHariIni'
        ));
    }

    public function tahfizh()
    {
        $userId = Auth::id();
        $halaqohs = Halaqoh::where('musyrif_id', $userId)->get();
        $halaqohIds = $halaqohs->pluck('id');
        
        $santris = Santri::whereIn('halaqoh_id', $halaqohIds)
                    ->with(['hafalan', 'kelas', 'halaqoh'])
                    ->get();
                    
        $santriIds = $santris->pluck('id');
        $riwayatSetoran = Setoran::with('santri')
                            ->whereIn('santri_id', $santriIds)
                            ->orderBy('created_at', 'desc')
                            ->take(20)
                            ->get();

        return view('musyrif.tahfizh', compact('santris', 'riwayatSetoran'));
    }

    public function storeSetoran(Request $request)
    {
        $validated = $request->validate([
            'santri_id'   => 'required|exists:santri,id',
            'jenis'       => 'required|in:hafalan_baru,murajaah,tasmi',
            'surah'       => 'required|string|max:80',
            'ayat_dari'   => 'nullable|integer|min:1',
            'ayat_sampai' => 'nullable|integer|min:1',
            'juz'         => 'required|integer|min:1|max:30',
            'nilai'       => 'required|in:Mumtaz,Jayyid Jiddan,Jayyid,Maqbul,Rosib',
            'catatan'     => 'nullable|string'
        ]);

        // Verifikasi bahwa santri ini benar-benar di bawah bimbingan musyrif ybs
        $santri = Santri::findOrFail($validated['santri_id']);
        if ($santri->halaqoh && $santri->halaqoh->musyrif_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak berhak mengisi setoran untuk santri dari halaqoh lain.');
        }

        $validated['musyrif_id'] = Auth::id();
        $validated['tanggal'] = now()->toDateString();

        Setoran::create($validated);

        // Auto update juz if it's higher
        $hafalan = HafalanSantri::firstOrCreate(
            ['santri_id' => $validated['santri_id']],
            ['juz_selesai' => 0, 'target_juz' => 30]
        );

        if ($validated['juz'] > $hafalan->juz_selesai) {
            $hafalan->update(['juz_selesai' => $validated['juz']]);
        }

        return redirect()->route('musyrif.tahfizh')->with('success', 'Setoran berhasil dicatat!');
    }

    public function pengumuman()
    {
        $pengumumans = \App\Models\Pengumuman::whereIn('target', ['semua', 'musyrif'])
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(10);
        
        return view('musyrif.pengumuman', compact('pengumumans'));
    }

    public function storeSakit(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'keluhan' => 'required|string',
        ]);

        \App\Models\RekamKesehatan::create([
            'santri_id' => $request->santri_id,
            'tanggal' => now()->toDateString(),
            'keluhan' => $request->keluhan,
            'petugas_id' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        return redirect()->route('musyrif.dashboard')->with('success', 'Data santri sakit berhasil dicatat!');
    }

    public function storeKehadiran(Request $request)
    {
        $request->validate([
            'kehadiran' => 'required|array',
            'kehadiran.*.santri_id' => 'required|exists:santri,id',
            'kehadiran.*.status' => 'required|in:hadir,sakit,izin,alpha',
        ]);

        $tanggal = now()->toDateString();
        $petugasId = \Illuminate\Support\Facades\Auth::id();

        foreach ($request->kehadiran as $index => $data) {
            \App\Models\Kehadiran::updateOrCreate(
                ['santri_id' => $data['santri_id'], 'tanggal' => $tanggal],
                [
                    'status' => $data['status'],
                    'dicatat_oleh' => $petugasId,
                ]
            );
        }

        return redirect()->route('musyrif.dashboard')->with('success', 'Absensi halaqoh berhasil disimpan!');
    }

    public function rekap(Request $request)
    {
        $userId = Auth::id();
        $startDate = $request->input('start_date', now()->startOfWeek()->toDateString());
        $endDate = $request->input('end_date', now()->endOfWeek()->toDateString());

        $halaqohs = Halaqoh::where('musyrif_id', $userId)->get();
        $halaqohIds = $halaqohs->pluck('id');
        
        $santris = Santri::whereIn('halaqoh_id', $halaqohIds)->with(['hafalan', 'kelas'])->get();

        $rekapData = [];
        foreach ($santris as $santri) {
            $setorans = \App\Models\Setoran::where('santri_id', $santri->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();
            
            $setoranCount = $setorans->count();
            $halamanZiyadah = 0;
            $halamanMurajaah = 0;
            
            foreach ($setorans as $setoran) {
                $pages = \App\Helpers\QuranHelper::calculatePages($setoran->surah, $setoran->ayat_dari, $setoran->ayat_sampai);
                if ($setoran->jenis == 'hafalan_baru') {
                    $halamanZiyadah += $pages;
                } else {
                    $halamanMurajaah += $pages;
                }
            }

            $rekapData[] = (object) [
                'nis' => $santri->nis,
                'nama' => $santri->nama,
                'kelas' => $santri->kelas->nama ?? '-',
                'juz_terakhir' => $santri->hafalan->juz_selesai ?? 0,
                'total_setoran' => $setoranCount,
                'halaman_ziyadah' => $halamanZiyadah,
                'halaman_murajaah' => $halamanMurajaah,
                'total_halaman' => $halamanZiyadah + $halamanMurajaah,
            ];
        }

        return view('musyrif.rekap', compact('rekapData', 'startDate', 'endDate'));
    }

    public function exportCsv(Request $request)
    {
        $userId = Auth::id();
        $startDate = $request->input('start_date', now()->startOfWeek()->toDateString());
        $endDate = $request->input('end_date', now()->endOfWeek()->toDateString());

        $halaqohs = Halaqoh::where('musyrif_id', $userId)->get();
        $halaqohIds = $halaqohs->pluck('id');
        
        $santris = Santri::whereIn('halaqoh_id', $halaqohIds)->with(['hafalan', 'kelas'])->get();

        $filename = "rekap_musyrif_{$startDate}_sampai_{$endDate}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['NIS', 'Nama Santri', 'Kelas', 'Juz Terakhir', 'Total Setoran', 'Halaman Ziyadah', 'Halaman Murajaah', 'Total Halaman'];

        $callback = function() use($santris, $columns, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($santris as $santri) {
                $setorans = \App\Models\Setoran::where('santri_id', $santri->id)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->get();
                
                $setoranCount = $setorans->count();
                $halamanZiyadah = 0;
                $halamanMurajaah = 0;
                
                foreach ($setorans as $setoran) {
                    $pages = \App\Helpers\QuranHelper::calculatePages($setoran->surah, $setoran->ayat_dari, $setoran->ayat_sampai);
                    if ($setoran->jenis == 'hafalan_baru') {
                        $halamanZiyadah += $pages;
                    } else {
                        $halamanMurajaah += $pages;
                    }
                }

                fputcsv($file, [
                    $santri->nis,
                    $santri->nama,
                    $santri->kelas->nama ?? '-',
                    $santri->hafalan->juz_selesai ?? 0,
                    $setoranCount,
                    $halamanZiyadah,
                    $halamanMurajaah,
                    $halamanZiyadah + $halamanMurajaah
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
