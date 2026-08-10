<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekamKesehatan;
use App\Models\Perizinan;
use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KesehatanController extends Controller
{
    public function index()
    {
        // Stats
        $today = now()->toDateString();
        
        $totalPasienHariIni = RekamKesehatan::whereDate('tanggal', $today)->count();
        $totalDirujuk = RekamKesehatan::where('dirujuk', 1)->count();
        $totalRekam = RekamKesehatan::count();
        
        // Find top sickness
        $topDiagnosa = RekamKesehatan::select('diagnosa', DB::raw('count(*) as total'))
            ->whereNotNull('diagnosa')
            ->groupBy('diagnosa')
            ->orderByDesc('total')
            ->first();

        $stats = [
            'hari_ini' => $totalPasienHariIni,
            'total_rujukan' => $totalDirujuk,
            'total_rekam' => $totalRekam,
            'top_diagnosa' => $topDiagnosa ? $topDiagnosa->diagnosa : '-'
        ];

        $rekamMedis = RekamKesehatan::with(['santri.kelas', 'petugas'])->orderBy('created_at', 'desc')->paginate(15);
        $santris = Santri::with('kelas')->get();

        return view('admin.kesehatan', compact('stats', 'rekamMedis', 'santris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'keluhan' => 'required|string',
            'diagnosa' => 'nullable|string',
            'tindakan' => 'nullable|string',
        ]);

        $dirujuk = $request->has('dirujuk') ? 1 : 0;
        $buatIzin = $request->has('buat_izin_sakit');

        DB::beginTransaction();
        try {
            RekamKesehatan::create([
                'santri_id' => $request->santri_id,
                'tanggal' => now()->toDateString(),
                'keluhan' => $request->keluhan,
                'diagnosa' => $request->diagnosa,
                'tindakan' => $request->tindakan,
                'petugas_id' => auth()->id(),
                'dirujuk' => $dirujuk,
                'tempat_rujukan' => $dirujuk ? $request->tempat_rujukan : null,
            ]);

            if ($buatIzin) {
                // Buat Izin Sakit otomatis 1 hari
                Perizinan::create([
                    'santri_id' => $request->santri_id,
                    'jenis' => 'sakit',
                    'tanggal_mulai' => now()->toDateString(),
                    'tanggal_selesai' => now()->toDateString(),
                    'alasan' => 'Dibuat otomatis dari Modul Kesehatan (UKS). Diagnosa: ' . ($request->diagnosa ?? 'Belum ada diagnosa'),
                    'status' => 'disetujui',
                    'disetujui_oleh' => auth()->id(),
                    'catatan_admin' => 'Izin otomatis ter-generate via UKS.'
                ]);
            }

            DB::commit();
            
            $msg = 'Data rekam medis berhasil ditambahkan.';
            if($buatIzin) $msg .= ' Izin sakit otomatis telah dibuat.';
            
            return back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem saat menyimpan rekam medis.');
        }
    }
}
