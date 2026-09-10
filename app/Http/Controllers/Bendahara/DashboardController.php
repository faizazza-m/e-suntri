<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\PpdbStudent;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Keuangan Santri Aktif
        $totalPemasukanBulanIni = Pembayaran::whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->sum('nominal_bayar');

        $totalTunggakan = Tagihan::where('status', '!=', 'lunas')->sum('nominal');
        $jumlahSantriNunggak = Tagihan::where('status', '!=', 'lunas')->distinct('santri_id')->count();

        // Statistik PPDB (Jika Bendahara mengurus validasi pembayaran PPDB)
        $pendaftarBaru = PpdbStudent::where('status', 'pending')->count();

        // Riwayat Pembayaran Terakhir
        $pembayaranTerakhir = Pembayaran::with(['santri', 'tagihan.jenis'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('bendahara.dashboard', compact(
            'totalPemasukanBulanIni', 
            'totalTunggakan', 
            'jumlahSantriNunggak',
            'pendaftarBaru',
            'pembayaranTerakhir'
        ));
    }
}
