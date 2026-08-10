<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $query = Kehadiran::with(['santri.kelas', 'santri.halaqoh'])
            ->orderBy('tanggal', 'desc');

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $kehadiran = $query->paginate(20)->withQueryString();

        $statsQuery = Kehadiran::query();
        if ($request->has('start_date') && $request->start_date != '') {
            $statsQuery->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $statsQuery->whereDate('tanggal', '<=', $request->end_date);
        }

        $stats = [
            'hadir' => (clone $statsQuery)->where('status', 'hadir')->count(),
            'izin' => (clone $statsQuery)->where('status', 'izin')->count(),
            'sakit' => (clone $statsQuery)->where('status', 'sakit')->count(),
            'alpa' => (clone $statsQuery)->where('status', 'alpa')->count(),
            'total' => $statsQuery->count(),
        ];

        return view('admin.kehadiran', compact('kehadiran', 'stats'));
    }
}
