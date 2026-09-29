<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SpkKriteria;
use App\Models\SpkPenilaian;
use App\Models\Santri;

class SpkController extends Controller
{
    public function index()
    {
        // 1. Ensure Kriteria exists
        if (SpkKriteria::count() == 0) {
            SpkKriteria::insert([
                ['kode' => 'C1', 'nama' => 'Progres Tahfizh', 'bobot' => 30, 'sifat' => 'benefit'],
                ['kode' => 'C2', 'nama' => 'Kedisiplinan', 'bobot' => 20, 'sifat' => 'benefit'],
                ['kode' => 'C3', 'nama' => 'Nilai Akademik', 'bobot' => 30, 'sifat' => 'benefit'],
                ['kode' => 'C4', 'nama' => 'Jumlah Pelanggaran', 'bobot' => 20, 'sifat' => 'cost'],
            ]);
        }
        
        $kriterias = SpkKriteria::all();
        
        // 2. Fetch all active Santri
        $santris = Santri::with(['setoran', 'kehadirans', 'nilaiAkademik'])->get();
        
        // 3. Process raw data & Find Min Max
        $penilaians = [];
        $maxC1 = 0; $maxC2 = 0; $maxC3 = 0; $minC4 = 99999;
        
        foreach($santris as $santri) {
            // C1: Jumlah baris setoran hafalan
            $c1_raw = $santri->setoran->count();
            
            // C2: Persentase Kehadiran
            $total_hadir = $santri->kehadirans->where('status', 'hadir')->count();
            $total_absen = $santri->kehadirans->count();
            $c2_raw = $total_absen > 0 ? ($total_hadir / $total_absen) * 100 : 0;
            
            // C3: Rata-rata Nilai Akademik
            $c3_raw = $santri->nilaiAkademik->avg('nilai_akhir') ?? 0;
            
            // C4: Pelanggaran (dari tabel spk_penilaians)
            $spkVal = SpkPenilaian::where('santri_id', $santri->id)->first();
            $c4_raw = $spkVal ? $spkVal->c4_nilai : 0; 
            
            if ($c1_raw > $maxC1) $maxC1 = $c1_raw;
            if ($c2_raw > $maxC2) $maxC2 = $c2_raw;
            if ($c3_raw > $maxC3) $maxC3 = $c3_raw;
            if ($c4_raw < $minC4) $minC4 = $c4_raw;
            
            $penilaians[$santri->id] = [
                'nama' => $santri->nama,
                'c1' => $c1_raw,
                'c2' => $c2_raw,
                'c3' => $c3_raw,
                'c4' => $c4_raw,
            ];
        }
        
        // Anti zero division
        if($maxC1 == 0) $maxC1 = 1;
        if($maxC2 == 0) $maxC2 = 1;
        if($maxC3 == 0) $maxC3 = 1;
        
        // 4. Normalisasi & Hitung SAW
        $rankings = [];
        $w1 = $kriterias->where('kode', 'C1')->first()->bobot / 100;
        $w2 = $kriterias->where('kode', 'C2')->first()->bobot / 100;
        $w3 = $kriterias->where('kode', 'C3')->first()->bobot / 100;
        $w4 = $kriterias->where('kode', 'C4')->first()->bobot / 100;
        
        foreach($penilaians as $id => $p) {
            $norm_c1 = $p['c1'] / $maxC1; // Benefit
            $norm_c2 = $p['c2'] / $maxC2; // Benefit
            $norm_c3 = $p['c3'] / $maxC3; // Benefit
            // Cost: Jika p['c4'] == 0, nilainya sempurna (1), jika > 0, min / nilai
            $norm_c4 = $p['c4'] == 0 ? 1 : ($minC4 / $p['c4']); 
            
            $total_skor = ($norm_c1 * $w1) + ($norm_c2 * $w2) + ($norm_c3 * $w3) + ($norm_c4 * $w4);
            
            $rankings[] = [
                'id' => $id,
                'nama' => $p['nama'],
                'c1' => $p['c1'], 'c2' => round($p['c2'], 1), 'c3' => round($p['c3'], 1), 'c4' => $p['c4'],
                'total' => round($total_skor, 4)
            ];
        }
        
        // Sort dari tertinggi ke terendah
        usort($rankings, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        
        return view('admin.spk.index', compact('kriterias', 'rankings', 'santris'));
    }

    public function updateKriteria(Request $request)
    {
        if($request->has('bobot')) {
            foreach($request->bobot as $id => $bobot) {
                SpkKriteria::where('id', $id)->update(['bobot' => $bobot]);
            }
        }
        return back()->with('success', 'Bobot Kriteria berhasil diperbarui!');
    }

    public function savePenilaian(Request $request)
    {
        if($request->has('c4')) {
            foreach($request->c4 as $santri_id => $val) {
                SpkPenilaian::updateOrCreate(
                    ['santri_id' => $santri_id],
                    ['c4_nilai' => $val]
                );
            }
        }
        return back()->with('success', 'Nilai Pelanggaran (C4) berhasil disimpan!');
    }
}
