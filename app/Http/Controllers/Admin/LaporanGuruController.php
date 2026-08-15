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
