<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LaporanGuru;
use Carbon\Carbon;

class GuruMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        
        // Define filters from request or default to current month/semester
        $filterBulan = $request->input('bulan', $today->month);
        $filterTahun = $request->input('tahun', $today->year);

        // Define semester range based on selected month/year
        $isGanjil = $filterBulan >= 7;
        $semesterStart = $isGanjil ? Carbon::create($filterTahun, 7, 1)->startOfDay() : Carbon::create($filterTahun, 1, 1)->startOfDay();
        $semesterEnd = $isGanjil ? Carbon::create($filterTahun, 12, 31)->endOfDay() : Carbon::create($filterTahun, 6, 30)->endOfDay();
        
        $semesterName = $isGanjil ? 'Ganjil' : 'Genap';

        // Fetch all teachers (role_id = 5 for Ustadz/Guru)
        $gurus = User::where('role_id', 5)->orderBy('name')->get();

        // 1. Fetch today's reports (for real-time tracking)
        $laporansHariIni = LaporanGuru::whereDate('tanggal', $today)->get()->keyBy('guru_id');

        // 2. Fetch monthly reports for the distinct count
        $laporansBulanIni = LaporanGuru::whereMonth('tanggal', $filterBulan)
                            ->whereYear('tanggal', $filterTahun)
                            ->get()
                            ->groupBy('guru_id');

        // 3. Fetch semester reports for the distinct count
        $laporansSemesterIni = LaporanGuru::whereBetween('tanggal', [$semesterStart, $semesterEnd])
                            ->get()
                            ->groupBy('guru_id');

        // Map distinct dates count for each guru
        $rekapBulan = [];
        $rekapSemester = [];

        foreach ($gurus as $guru) {
            $rekapBulan[$guru->id] = isset($laporansBulanIni[$guru->id]) 
                ? $laporansBulanIni[$guru->id]->pluck('tanggal')->unique()->count() 
                : 0;
                
            $rekapSemester[$guru->id] = isset($laporansSemesterIni[$guru->id]) 
                ? $laporansSemesterIni[$guru->id]->pluck('tanggal')->unique()->count() 
                : 0;
        }

        return view('bendahara.guru_monitoring', compact(
            'gurus', 
            'laporansHariIni', 
            'rekapBulan', 
            'rekapSemester', 
            'today', 
            'filterBulan', 
            'filterTahun',
            'semesterName'
        ));
    }
}
