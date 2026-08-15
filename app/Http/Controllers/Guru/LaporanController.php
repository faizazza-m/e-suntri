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
            'tanggal' => 'required|date',
            'kelas' => 'required|string|max:255',
            'mata_pelajaran' => 'required|string|max:255',
            'materi' => 'required|string|max:255',
            'isi_laporan' => 'nullable|string',
        ]);

        \App\Models\LaporanGuru::create([
            'guru_id' => auth()->id(),
            'tanggal' => $request->tanggal,
            'kelas' => $request->kelas,
            'mata_pelajaran' => $request->mata_pelajaran,
            'materi' => $request->materi,
            'isi_laporan' => $request->isi_laporan,
            'status' => 'menunggu',
        ]);

        return redirect()->route('guru.laporan')->with('success', 'Jurnal Mengajar berhasil disimpan.');
    }
}
