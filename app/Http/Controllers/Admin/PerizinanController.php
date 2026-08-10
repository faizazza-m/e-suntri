<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perizinan;
use App\Models\Santri;
use Carbon\Carbon;

class PerizinanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Calculate Stats
        $today = now()->toDateString();
        
        $izinAktif = Perizinan::where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();
            
        $menunggu = Perizinan::where('status', 'pending')->count();
        
        $izinSakit = Perizinan::where('status', 'disetujui')
            ->where('jenis', 'sakit')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();
            
        $izinPulang = Perizinan::where('status', 'disetujui')
            ->where('jenis', 'pulang')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();

        $stats = [
            'aktif' => $izinAktif,
            'pending' => $menunggu,
            'sakit' => $izinSakit,
            'pulang' => $izinPulang
        ];

        // 2. Fetch Perizinan
        $query = Perizinan::with(['santri.kelas'])->orderBy('created_at', 'desc');
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $perizinans = $query->paginate(15);
        $santris = Santri::with('kelas')->get();

        return view('admin.perizinan', compact('stats', 'perizinans', 'santris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'jenis' => 'required|in:pulang,sakit,kegiatan_luar,lainnya',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string',
        ]);

        Perizinan::create([
            'santri_id' => $request->santri_id,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'status' => 'disetujui', // Admin input directly approved by default
            'disetujui_oleh' => auth()->id()
        ]);

        return back()->with('success', 'Perizinan santri berhasil ditambahkan dan disetujui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_admin' => 'nullable|string'
        ]);

        $perizinan = Perizinan::findOrFail($id);
        
        $perizinan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'disetujui_oleh' => auth()->id()
        ]);

        $msg = $request->status == 'disetujui' ? 'disetujui' : 'ditolak';
        return back()->with('success', "Perizinan berhasil $msg.");
    }
}
