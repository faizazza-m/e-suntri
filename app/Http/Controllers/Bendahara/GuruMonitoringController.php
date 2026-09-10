<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LaporanGuru;
use Carbon\Carbon;

class GuruMonitoringController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Fetch all teachers (role_id = 5 for Ustadz/Guru)
        $gurus = User::where('role_id', 5)->orderBy('name')->get();

        // Fetch today's reports
        $laporans = LaporanGuru::whereDate('tanggal', $today)->get()->keyBy('guru_id');

        return view('bendahara.guru_monitoring', compact('gurus', 'laporans', 'today'));
    }
}
