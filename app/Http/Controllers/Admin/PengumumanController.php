<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Carbon\Carbon;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::with('pembuat')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $totalPengumuman = Pengumuman::count();
        $targetWali = Pengumuman::where('target', 'wali')->count();
        $targetMusyrif = Pengumuman::where('target', 'musyrif')->count();
        $targetGuru = Pengumuman::where('target', 'guru')->count();
        $targetSantri = Pengumuman::where('target', 'santri')->count();

        $stats = [
            'total' => $totalPengumuman,
            'wali' => $targetWali,
            'musyrif' => $targetMusyrif,
            'guru' => $targetGuru,
            'santri' => $targetSantri
        ];

        return view('admin.pengumuman', compact('pengumumans', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:200',
            'isi' => 'required|string',
            'target' => 'required|in:semua,wali,santri,musyrif,guru',
        ]);

        $isPinned = $request->has('is_pinned') ? 1 : 0;

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'target' => $request->target,
            'dibuat_oleh' => auth()->id(),
            'is_pinned' => $isPinned,
            'published_at' => now(),
        ]);

        return back()->with('success', 'Pengumuman berhasil diterbitkan.');
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();
        
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}
