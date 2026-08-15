<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = \App\Models\LaporanGuru::where('guru_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('guru.laporan.index', compact('laporans'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'judul' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
        ]);

        \App\Models\LaporanGuru::create([
            'guru_id' => auth()->id(),
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'judul' => $request->judul,
            'isi_laporan' => $request->isi_laporan,
            'status' => 'menunggu',
        ]);

        return redirect()->route('guru.laporan')->with('success', 'Laporan Mingguan berhasil dikirim.');
    }
}
