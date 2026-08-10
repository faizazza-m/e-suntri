<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Santri;
use App\Models\PenghuniKamar;

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::withCount('penghuni')->get();
        return view('admin.kamar.index', compact('kamars'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1',
            'gedung' => 'nullable|string|max:50',
            'lantai' => 'nullable|integer|min:1'
        ]);

        Kamar::create($validated);
        return back()->with('success', 'Data kamar berhasil ditambahkan.');
    }

    public function show(Kamar $kamar)
    {
        $kamar->load('penghuni.santri');
        
        // Cari santri yang belum punya kamar
        $unassignedSantris = Santri::whereDoesntHave('kamar')->get();

        return view('admin.kamar.show', compact('kamar', 'unassignedSantris'));
    }

    public function addSantri(Request $request, Kamar $kamar)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id'
        ]);

        // Cek kapasitas
        if ($kamar->penghuni()->count() >= $kamar->kapasitas) {
            return back()->with('error', 'Kamar sudah penuh!');
        }

        PenghuniKamar::updateOrCreate(
            ['santri_id' => $request->santri_id],
            [
                'kamar_id' => $kamar->id,
                'tanggal_masuk' => now()->toDateString()
            ]
        );

        return back()->with('success', 'Santri berhasil dimasukkan ke kamar.');
    }

    public function removeSantri(Kamar $kamar, PenghuniKamar $penghuni)
    {
        $penghuni->delete();
        return back()->with('success', 'Santri berhasil dikeluarkan dari kamar.');
    }
}
