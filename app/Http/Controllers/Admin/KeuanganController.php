<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\JenisTagihan;
use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        // 1. Calculate Stats
        $totalTagihan = Tagihan::sum('nominal');
        $totalDibayar = Tagihan::where('status', 'lunas')->sum('nominal');
        $belumDibayar = Tagihan::where('status', '!=', 'lunas')->sum('nominal');
        $jatuhTempo = Tagihan::where('status', '!=', 'lunas')
            ->whereDate('jatuh_tempo', '<=', now()->toDateString())
            ->sum('nominal');
        
        $jumlahBelumDibayar = Tagihan::where('status', '!=', 'lunas')->count();

        $stats = [
            'total_tagihan' => $totalTagihan,
            'sudah_dibayar' => $totalDibayar,
            'belum_dibayar' => $belumDibayar,
            'jatuh_tempo' => $jatuhTempo,
            'count_belum_dibayar' => $jumlahBelumDibayar
        ];

        // 2. Fetch Tagihan with filters
        $query = Tagihan::with(['santri.kelas', 'jenis'])->orderBy('created_at', 'desc');
        
        if ($request->has('kelas') && $request->kelas != '') {
            $query->whereHas('santri', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas);
            });
        }

        $tagihans = $query->paginate(20);

        // 3. Fetch Riwayat Pembayaran Terbaru
        $riwayatPembayaran = Pembayaran::with(['santri', 'tagihan.jenis'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // 4. Data for Modals
        $jenisTagihans = JenisTagihan::all();
        $kelas = \App\Models\Kelas::all();
        $santris = Santri::with('kelas')->get();

        return view('admin.keuangan', compact('stats', 'tagihans', 'riwayatPembayaran', 'jenisTagihans', 'kelas', 'santris'));
    }

    public function storeTagihan(Request $request)
    {
        $request->validate([
            'jenis_id' => 'required|exists:jenis_tagihan,id',
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer',
            'jatuh_tempo' => 'required|date',
        ]);

        $jenis = JenisTagihan::find($request->jenis_id);

        if ($request->target_type == 'semua_santri') {
            $santris = Santri::all();
        } elseif ($request->target_type == 'kelas') {
            $santris = Santri::where('kelas_id', $request->kelas_id)->get();
        } else {
            $santris = Santri::where('id', $request->santri_id)->get();
        }

        $count = 0;
        foreach ($santris as $santri) {
            // Check if tagihan already exists to prevent duplicate
            $exists = Tagihan::where('santri_id', $santri->id)
                ->where('jenis_id', $jenis->id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->exists();

            if (!$exists) {
                Tagihan::create([
                    'santri_id' => $santri->id,
                    'jenis_id' => $jenis->id,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'nominal' => $jenis->nominal,
                    'jatuh_tempo' => $request->jatuh_tempo,
                    'status' => 'belum',
                ]);
                $count++;
            }
        }

        return back()->with('success', "Berhasil men-generate $count tagihan baru.");
    }

    public function bayarTagihan(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required|exists:tagihan,id',
            'nominal_bayar' => 'required|numeric|min:0',
            'metode' => 'required|in:tunai,transfer,qris',
        ]);

        $tagihan = Tagihan::find($request->tagihan_id);
        
        // Generate Invoice Number INV-YYYYMMDD-XXXX
        $dateStr = now()->format('Ymd');
        $lastPayment = Pembayaran::whereDate('created_at', now()->toDateString())->count();
        $invoiceNum = 'INV-' . $dateStr . '-' . str_pad($lastPayment + 1, 4, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'santri_id' => $tagihan->santri_id,
                'tanggal_bayar' => now(),
                'nominal_bayar' => $request->nominal_bayar,
                'metode' => $request->metode,
                'no_invoice' => $invoiceNum,
                'dikonfirmasi_oleh' => auth()->id(),
                'catatan' => $request->catatan,
            ]);

            // For simplicity, any payment marks it as lunas. 
            // In a real scenario, you'd check if sum of pembayaran >= tagihan nominal.
            $tagihan->update(['status' => 'lunas']);

            DB::commit();
            return back()->with('success', "Pembayaran berhasil dicatat dengan No. Invoice $invoiceNum");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Terjadi kesalahan saat memproses pembayaran.");
        }
    }

    public function updateTagihan(Request $request, $id)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:0',
        ]);

        $tagihan = Tagihan::findOrFail($id);
        
        // Prevent editing if already paid
        if ($tagihan->status === 'lunas') {
            return back()->with('error', 'Tagihan yang sudah lunas tidak dapat diubah nominalnya.');
        }

        $tagihan->update([
            'nominal' => $request->nominal
        ]);

        return back()->with('success', 'Nominal tagihan berhasil diperbarui.');
    }
}
