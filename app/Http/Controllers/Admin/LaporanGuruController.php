<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanGuruController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\LaporanGuru::with('guru')->orderBy('created_at', 'desc');
        
        if ($request->has('guru_id') && $request->guru_id != '') {
            $query->where('guru_id', $request->guru_id);
        }

        if ($request->has('waktu') && $request->waktu != '') {
            if ($request->waktu == 'minggu') {
                $query->whereBetween('tanggal', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
            } elseif ($request->waktu == 'bulan') {
                $query->whereMonth('tanggal', \Carbon\Carbon::now()->month)
                      ->whereYear('tanggal', \Carbon\Carbon::now()->year);
            } elseif ($request->waktu == 'semester') {
                $month = \Carbon\Carbon::now()->month;
                $year = \Carbon\Carbon::now()->year;
                if ($month <= 6) {
                    $query->whereBetween('tanggal', [$year.'-01-01', $year.'-06-30']);
                } else {
                    $query->whereBetween('tanggal', [$year.'-07-01', $year.'-12-31']);
                }
            }
        }

        $laporans = $query->paginate(20);
        $gurus = \App\Models\User::where('role_id', 5)->get();

        return view('admin.laporan_guru.index', compact('laporans', 'gurus'));
    }

    public function show($id)
    {
        $laporan = \App\Models\LaporanGuru::with('guru')->findOrFail($id);
        if ($laporan->status === 'menunggu') {
            $laporan->update(['status' => 'dibaca']);
        }
        return response()->json($laporan);
    }
}
