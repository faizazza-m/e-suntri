<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Santri;

class SantriController extends Controller
{
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:150',
            'nis'           => 'required|string|max:20|unique:santri,nis',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id'      => 'nullable|exists:kelas,id',
            'tahun_masuk'   => 'nullable|integer|min:2000|max:' . date('Y'),
        ]);

        Santri::create(array_merge($validated, ['status' => 'aktif']));

        return redirect()->route('pengguna')->with('success', 'Santri berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $santri = Santri::findOrFail($id);

        $validated = $request->validate([
            'nama'          => 'required|string|max:150',
            'nis'           => 'required|string|max:20|unique:santri,nis,' . $id,
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id'      => 'nullable|exists:kelas,id',
            'tahun_masuk'   => 'nullable|integer|min:2000|max:' . date('Y'),
        ]);

        $santri->update($validated);

        return redirect()->route('pengguna')->with('success', 'Data santri berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $santri = Santri::findOrFail($id);
        $santri->delete();

        return redirect()->route('pengguna')->with('success', 'Data santri berhasil dihapus!');
    }
}
