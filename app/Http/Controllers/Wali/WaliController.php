<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WaliSantri;
use App\Models\Santri;
use Illuminate\Support\Facades\Auth;

class WaliController extends Controller
{
    public function dashboard()
    {
        // Ambil data wali santri dari user yang login
        $wali = WaliSantri::where('user_id', Auth::id())->first();

        // Jika tidak ditemukan record wali santri, berikan error
        if (!$wali) {
            abort(403, 'Anda belum terdaftar sebagai wali santri.');
        }

        // Ambil data santri yang terhubung dengan wali ini
        // Asumsi: wali bisa punya lebih dari 1 anak, tapi di database saat ini `wali_santri` punya `santri_id`.
        // Idealnya wali bisa banyak anak, tapi struktur DB: wali_santri belongsTo santri.
        // Cek struktur DB: CREATE TABLE wali_santri ( id, user_id, santri_id, ... )
        // Berarti satu record wali_santri berelasi ke 1 santri.
        // Jika 1 user wali punya 2 anak, dia punya 2 record wali_santri?
        // Mari kita query semua wali_santri milik user ini untuk ambil santri-santrinya.
        $waliRecords = WaliSantri::where('user_id', Auth::id())->get();
        $santriIds = $waliRecords->pluck('santri_id');

        $santris = Santri::with(['kelas', 'halaqoh.musyrif', 'hafalan'])
            ->whereIn('id', $santriIds)
            ->get();

        $stats = [];
        foreach ($santris as $s) {
            // Tagihan
            $totalTagihan = \App\Models\Tagihan::where('santri_id', $s->id)
                ->where('status', 'belum_lunas')
                ->sum('nominal');

            // Hafalan
            $juzSelesai = $s->hafalan->juz_selesai ?? 0;

            // Kehadiran (Bulan Ini)
            $totalHari = \App\Models\Kehadiran::where('santri_id', $s->id)
                ->whereMonth('tanggal', now()->month)
                ->count();
            
            $hadir = \App\Models\Kehadiran::where('santri_id', $s->id)
                ->whereMonth('tanggal', now()->month)
                ->where('status', 'hadir')
                ->count();
            
            $persentaseHadir = $totalHari > 0 ? round(($hadir / $totalHari) * 100) : 100;

            $stats[$s->id] = [
                'tagihan' => $totalTagihan,
                'juz' => $juzSelesai,
                'kehadiran' => $persentaseHadir
            ];
        }

        // Data dinamis untuk santri pertama (sebagai default view)
        $activeSantri = $santris->first();
        $jadwals = collect();
        $jadwalSeminggu = collect();
        $notifs = collect();

        if ($activeSantri) {
            $hariMap = [0=>'Minggu', 1=>'Senin', 2=>'Selasa', 3=>'Rabu', 4=>'Kamis', 5=>'Jumat', 6=>'Sabtu'];
            $hariIni = $hariMap[now()->dayOfWeek];

            if ($activeSantri->kelas_id) {
                $jadwals = \App\Models\JadwalPelajaran::with('mapel')
                    ->where('kelas_id', $activeSantri->kelas_id)
                    ->where('hari', $hariIni)
                    ->orderBy('jam_mulai')
                    ->get();
                    
                $jadwalSeminggu = \App\Models\JadwalPelajaran::with('mapel')
                    ->where('kelas_id', $activeSantri->kelas_id)
                    ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                    ->orderBy('jam_mulai')
                    ->get()
                    ->groupBy('hari');
            }

            // Notifikasi: Gabungan Setoran Terakhir, Tagihan Belum Lunas, & Pengumuman Terbaru
            $latestSetorans = \App\Models\Setoran::where('santri_id', $activeSantri->id)
                ->orderBy('created_at', 'desc')
                ->take(2)->get()->map(function($item) {
                    return [
                        'icon' => 'check_circle',
                        'iconClass' => 'text-primary bg-primary-container',
                        'title' => 'Setoran Disetujui',
                        'desc' => "Telah menyetorkan Surat {$item->surah} (Nilai: {$item->nilai}).",
                        'time' => $item->created_at->diffForHumans(),
                        'read' => false,
                        'timestamp' => $item->created_at->timestamp
                    ];
                });

            $latestTagihans = \App\Models\Tagihan::with('jenisTagihan')
                ->where('santri_id', $activeSantri->id)
                ->where('status', 'belum_lunas')
                ->orderBy('created_at', 'desc')
                ->take(1)->get()->map(function($item) {
                    return [
                        'icon' => 'payments',
                        'iconClass' => 'text-error bg-error-container',
                        'title' => 'Tagihan Belum Lunas',
                        'desc' => "Tagihan {$item->jenisTagihan->nama} sebesar Rp " . number_format($item->nominal, 0, ',', '.') . ".",
                        'time' => $item->created_at->diffForHumans(),
                        'read' => true,
                        'timestamp' => $item->created_at->timestamp
                    ];
                });

            $latestPengumumans = \App\Models\Pengumuman::orderBy('published_at', 'desc')
                ->take(2)->get()->map(function($item) {
                    return [
                        'icon' => 'campaign',
                        'iconClass' => 'text-secondary bg-secondary-container',
                        'title' => $item->judul,
                        'desc' => \Illuminate\Support\Str::limit($item->isi, 60),
                        'time' => \Carbon\Carbon::parse($item->published_at)->diffForHumans(),
                        'read' => true,
                        'timestamp' => \Carbon\Carbon::parse($item->published_at)->timestamp
                    ];
                });

            $latestIzin = \App\Models\Perizinan::where('santri_id', $activeSantri->id)
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->orderBy('updated_at', 'desc')
                ->take(1)->get()->map(function($item) {
                    $statusText = $item->status == 'disetujui' ? 'Disetujui' : 'Ditolak';
                    $iconClass = $item->status == 'disetujui' ? 'text-primary bg-primary-container' : 'text-error bg-error-container';
                    $icon = $item->status == 'disetujui' ? 'check_circle' : 'cancel';
                    return [
                        'icon' => $icon,
                        'iconClass' => $iconClass,
                        'title' => 'Izin ' . $statusText,
                        'desc' => 'Pengajuan izin ' . str_replace('_', ' ', $item->jenis) . ' telah ' . strtolower($statusText) . ' oleh admin.',
                        'time' => $item->updated_at->diffForHumans(),
                        'read' => false,
                        'timestamp' => $item->updated_at->timestamp
                    ];
                });

            $notifs = collect()
                ->merge($latestSetorans)
                ->merge($latestTagihans)
                ->merge($latestPengumumans)
                ->merge($latestIzin)
                ->sortByDesc('timestamp')
                ->values()
                ->take(4);
        }

        return view('wali.dashboard', compact('santris', 'wali', 'stats', 'jadwals', 'jadwalSeminggu', 'notifs'));
    }

    public function izin()
    {
        $wali = WaliSantri::where('user_id', Auth::id())->first();
        if (!$wali) abort(403, 'Akses ditolak.');

        $waliRecords = WaliSantri::where('user_id', Auth::id())->get();
        $santriIds = $waliRecords->pluck('santri_id');
        $santris = Santri::whereIn('id', $santriIds)->get();

        $perizinans = \App\Models\Perizinan::with('santri')
            ->whereIn('santri_id', $santriIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wali.izin', compact('perizinans', 'santris'));
    }

    public function storeIzin(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'jenis' => 'required|in:pulang,sakit,kegiatan_luar,lainnya',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500',
        ]);

        // Verifikasi bahwa santri ini memang milik wali yang login
        $isMyChild = WaliSantri::where('user_id', Auth::id())
            ->where('santri_id', $request->santri_id)
            ->exists();

        if (!$isMyChild) {
            abort(403, 'Akses ditolak.');
        }

        \App\Models\Perizinan::create([
            'santri_id' => $request->santri_id,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'status' => 'pending', // Perlu persetujuan admin
        ]);

        return back()->with('success', 'Pengajuan izin berhasil dikirim dan sedang menunggu persetujuan.');
    }

    public function progres()
    {
        $wali = WaliSantri::where('user_id', Auth::id())->first();
        if (!$wali) abort(403, 'Akses ditolak.');

        // Get first santri for now
        $activeSantri = Santri::with(['hafalan', 'kelas'])->where('id', $wali->santri_id)->first();
        
        if (!$activeSantri) {
            abort(404, 'Data Santri tidak ditemukan.');
        }

        // 1. Total Hafalan & Target
        $juzSelesai = $activeSantri->hafalan->juz_selesai ?? 0;
        $targetJuz = $activeSantri->hafalan->target_juz ?? 30; // Default 30 jika belum diset
        
        // 2. Setoran Terkini (5 terakhir)
        $setorans = \App\Models\Setoran::where('santri_id', $activeSantri->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Calculate average score if needed, but for now we just show the latest one in the target card
        $latestSetoran = $setorans->first();

        // 3. Konsistensi Kehadiran (Heatmap) - 28 days
        $heatData = [];
        $startDate = now()->subDays(27); // 28 days including today
        
        // Fetch all attendance for the last 28 days
        $kehadirans = \App\Models\Kehadiran::where('santri_id', $activeSantri->id)
            ->where('tanggal', '>=', $startDate->toDateString())
            ->get()
            ->keyBy('tanggal');

        for ($i = 0; $i < 28; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $status = $kehadirans->has($date) ? $kehadirans[$date]->status : 'alfa';
            
            $colorClass = match($status) {
                'hadir' => 'bg-primary',
                'sakit', 'izin' => 'bg-primary-fixed-dim',
                default => 'bg-surface-container-highest' // alfa or no record
            };
            
            $heatData[] = [
                'date' => $date,
                'status' => $status,
                'color' => $colorClass
            ];
        }

        return view('wali.progres', compact('activeSantri', 'juzSelesai', 'targetJuz', 'setorans', 'latestSetoran', 'heatData'));
    }

    public function chat()
    {
        $wali = WaliSantri::where('user_id', Auth::id())->first();
        if (!$wali) abort(403, 'Akses ditolak.');

        $activeSantri = Santri::with(['halaqoh', 'kelas'])->where('id', $wali->santri_id)->first();
        
        if (!$activeSantri) {
            abort(404, 'Data Santri tidak ditemukan.');
        }

        $contacts = [];

        // 1. Musyrif
        if ($activeSantri->halaqoh && $activeSantri->halaqoh->musyrif_id) {
            $musyrif = \App\Models\User::find($activeSantri->halaqoh->musyrif_id);
            if ($musyrif) {
                $contacts[] = [
                    'id' => $musyrif->id,
                    'role' => 'Musyrif Halaqoh',
                    'name' => $musyrif->name,
                    'phone' => $musyrif->phone ?? '62800000000',
                    'icon' => 'menu_book',
                    'color' => 'primary'
                ];
            }
        }

        // 2. Wali Kelas
        if ($activeSantri->kelas && $activeSantri->kelas->wali_kelas_id) {
            $waliKelas = \App\Models\User::find($activeSantri->kelas->wali_kelas_id);
            if ($waliKelas && $waliKelas->id != ($activeSantri->halaqoh->musyrif_id ?? -1)) {
                $contacts[] = [
                    'id' => $waliKelas->id,
                    'role' => 'Wali Kelas',
                    'name' => $waliKelas->name,
                    'phone' => $waliKelas->phone ?? '62800000000',
                    'icon' => 'school',
                    'color' => 'secondary'
                ];
            }
        }

        // 3. Admin Utama
        $admin = \App\Models\User::where('role_id', 1)->first();
        if ($admin) {
            $contacts[] = [
                'id' => $admin->id,
                'role' => 'Pusat Informasi (Admin)',
                'name' => 'Admin SUNTRI',
                'phone' => $admin->phone ?? '62800000000',
                'icon' => 'support_agent',
                'color' => 'amber-500'
            ];
        }

        return view('wali.chat', compact('contacts'));
    }

    public function chatRoom($user_id)
    {
        $contact = \App\Models\User::findOrFail($user_id);
        return view('wali.chat_room', compact('contact'));
    }
}
