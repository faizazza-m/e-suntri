<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setoran;
use App\Models\Santri;
use App\Models\HafalanSantri;
use Illuminate\Support\Facades\Auth;

class TahfizhController extends Controller
{
    public function index()
    {
        $santris = Santri::with(['hafalan', 'kelas', 'halaqoh.musyrif'])->get();

        $totalHafiz = $santris->filter(function($s) {
            return $s->hafalan && $s->hafalan->juz_selesai >= 30;
        })->count();

        $totalJuz = $santris->sum(function($s) {
            return $s->hafalan ? $s->hafalan->juz_selesai : 0;
        });
        $rataJuz = $santris->count() > 0 ? round($totalJuz / $santris->count(), 1) : 0;
        
        $totalSetoranHariIni = Setoran::whereDate('tanggal', now()->toDateString())->count();
        
        $summary = [
            ['title' => 'Total Hafizh (30 Juz)', 'value' => $totalHafiz, 'color' => 'bg-primary', 'icon' => 'workspace_premium'],
            ['title' => 'Rata-rata Capaian Juz', 'value' => $rataJuz, 'color' => 'bg-secondary', 'icon' => 'auto_graph'],
            ['title' => 'Setoran Hari Ini', 'value' => $totalSetoranHariIni, 'color' => 'bg-yellow-600', 'icon' => 'record_voice_over'],
            ['title' => 'Target Tercapai', 'value' => ($rataJuz > 0 ? round(($rataJuz/30)*100).'%' : '0%'), 'color' => 'bg-primary-container text-white', 'icon' => 'track_changes'],
        ];

        $topSantri = $santris->filter(function($s) {
            return $s->hafalan;
        })->sortByDesc(function($s) {
            return $s->hafalan->juz_selesai;
        })->take(3);

        $recentSetoran = Setoran::with(['santri.kelas', 'musyrif'])
            ->whereDate('tanggal', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Data Chart 7 Hari Terakhir
        $weekData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Setoran::whereDate('tanggal', $date->toDateString())->count();
            $weekData->push([
                'day' => substr($date->locale('id')->dayName, 0, 3), // Sen, Sel, etc.
                'height' => $count > 0 ? min(100, $count * 10) : 5, // Simple height calc
                'val' => $count
            ]);
        }

        // Data untuk kelola Halaqoh
        $halaqohs = \App\Models\Halaqoh::with(['musyrif', 'santri'])->orderBy('nama')->get();
        $musyrifs = \App\Models\User::where('role_id', 2)->orderBy('name')->get(); // role 2 = Musyrif
        $semuaSantri = Santri::orderBy('nama')->get();

        return view('admin.tahfizh', compact(
            'summary', 'recentSetoran', 'topSantri', 'santris', 'weekData', 
            'halaqohs', 'musyrifs', 'semuaSantri'
        ));
    }

    public function storeSetoran(Request $request)
    {
        $validated = $request->validate([
            'santri_id'   => 'required|exists:santri,id',
            'surah'       => 'required|string|max:80',
            'ayat_dari'   => 'nullable|integer|min:1',
            'ayat_sampai' => 'nullable|integer|min:1',
            'juz'         => 'required|integer|min:1|max:30',
            'nilai'       => 'required|in:Mumtaz,Jayyid Jiddan,Jayyid,Maqbul,Rosib',
            'catatan'     => 'nullable|string'
        ]);

        $validated['musyrif_id'] = Auth::id() ?? 1; // Default to 1 if not logged in properly for some reason
        $validated['tanggal'] = now()->toDateString();
        $validated['jenis'] = 'hafalan_baru'; // Default for now, can be adjusted in UI later

        Setoran::create($validated);

        // Auto update juz if it's higher
        $hafalan = HafalanSantri::firstOrCreate(
            ['santri_id' => $validated['santri_id']],
            ['juz_selesai' => 0, 'target_juz' => 30]
        );

        if ($validated['juz'] > $hafalan->juz_selesai) {
            $hafalan->update(['juz_selesai' => $validated['juz']]);
        }

        return redirect()->route('tahfizh')->with('success', 'Setoran berhasil dicatat!');
    }

    public function updateHafalan(Request $request, $id)
    {
        $validated = $request->validate([
            'juz_selesai' => 'required|integer|min:0|max:30'
        ]);

        $hafalan = HafalanSantri::firstOrCreate(
            ['santri_id' => $id],
            ['target_juz' => 30]
        );
        $hafalan->update(['juz_selesai' => $validated['juz_selesai']]);

        return redirect()->route('tahfizh')->with('success', 'Progress Juz berhasil diperbarui!');
    }

    // ==========================================
    // MANAJEMEN HALAQOH
    // ==========================================
    public function storeHalaqoh(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:150',
            'musyrif_id' => 'required|exists:users,id',
            'santri_ids' => 'nullable|array',
            'santri_ids.*' => 'exists:santri,id'
        ]);

        $halaqoh = \App\Models\Halaqoh::create([
            'nama'       => $validated['nama'],
            'musyrif_id' => $validated['musyrif_id']
        ]);

        if (!empty($validated['santri_ids'])) {
            Santri::whereIn('id', $validated['santri_ids'])->update(['halaqoh_id' => $halaqoh->id]);
        }

        return redirect()->route('tahfizh')->with('success', 'Halaqoh baru berhasil dibuat!');
    }

    public function updateHalaqoh(Request $request, $id)
    {
        $halaqoh = \App\Models\Halaqoh::findOrFail($id);
        
        $validated = $request->validate([
            'nama'       => 'required|string|max:150',
            'musyrif_id' => 'required|exists:users,id',
            'santri_ids' => 'nullable|array',
            'santri_ids.*' => 'exists:santri,id'
        ]);

        $halaqoh->update([
            'nama'       => $validated['nama'],
            'musyrif_id' => $validated['musyrif_id']
        ]);

        // Kosongkan santri yang sebelumnya ada di halaqoh ini
        Santri::where('halaqoh_id', $halaqoh->id)->update(['halaqoh_id' => null]);

        // Isi dengan santri baru
        if (!empty($validated['santri_ids'])) {
            Santri::whereIn('id', $validated['santri_ids'])->update(['halaqoh_id' => $halaqoh->id]);
        }

        return redirect()->route('tahfizh')->with('success', 'Data halaqoh berhasil diperbarui!');
    }

    public function destroyHalaqoh($id)
    {
        $halaqoh = \App\Models\Halaqoh::findOrFail($id);
        
        // Kosongkan relasi halaqoh pada santri
        Santri::where('halaqoh_id', $halaqoh->id)->update(['halaqoh_id' => null]);
        
        $halaqoh->delete();

        return redirect()->route('tahfizh')->with('success', 'Halaqoh berhasil dihapus!');
    }
}
