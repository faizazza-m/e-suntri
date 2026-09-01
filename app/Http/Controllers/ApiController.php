<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Santri;
use App\Models\Setoran;
use App\Models\Halaqoh;
use App\Models\Perizinan;
use App\Models\RekamKesehatan;
use App\Models\Kamar;
use App\Models\PenghuniKamar;
use App\Models\Tagihan;
use App\Models\JenisTagihan;
use App\Models\Pembayaran;
use App\Models\NilaiAkademik;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Pengumuman;
use Carbon\Carbon;

class ApiController extends Controller
{
    // 1. Authenticate & Login
    public function login(Request $request)
    {
        $request->validate([
            'login_identifier' => ['required'],
            'password' => ['required'],
        ]);

        $identifier = $request->input('login_identifier');
        $password = $request->input('password');

        $credentials = ['email' => $identifier, 'password' => $password];

        // Check if it's a NISN / Santri NIS (Identifier might not be an email)
        if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $santri = Santri::where('nis', $identifier)->first();
            if ($santri) {
                $wali = \App\Models\WaliSantri::where('santri_id', $santri->id)->first();
                if ($wali && $wali->user) {
                    $credentials = ['email' => $wali->user->email, 'password' => $password];
                }
            }
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $roleName = match((int)$user->role_id) {
                1 => 'Admin',
                2 => 'Musyrif',
                3 => 'Wali',
                5 => 'Guru',
                6 => 'Mudir',
                default => 'Wali', // Fallback
            };

            return response()->json([
                'status' => 'success',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role_id' => $user->role_id,
                    'role' => $roleName,
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email/NISN atau password salah.'
        ], 401);
    }

    // 2. Full Sync Data to AppState
    public function syncData()
    {
        $students = Santri::with(['kelas', 'halaqoh', 'hafalan', 'kamar.kamar', 'kehadirans', 'wali'])->get()->map(function ($s) {
            return [
                'id' => (string)$s->id,
                'nis' => $s->nis,
                'name' => $s->nama,
                'gender' => $s->jenis_kelamin,
                'class' => $s->kelas->nama ?? 'Umum',
                'parent' => $s->wali->first()->nama ?? 'Orang Tua',
                'juz' => (int)($s->hafalan->juz_selesai ?? 0),
                'room' => $s->kamar->kamar->nama ?? 'Belum ada Kamar',
                'attendance' => [
                    'hadir' => $s->kehadirans->where('status', 'hadir')->count(),
                    'sakit' => $s->kehadirans->where('status', 'sakit')->count(),
                    'izin' => $s->kehadirans->where('status', 'izin')->count(),
                    'alpha' => $s->kehadirans->where('status', 'alpha')->count(),
                ],
            ];
        });

        // B. Halaqohs
        $halaqohs = Halaqoh::with(['musyrif'])->get()->map(function ($h) {
            // Find student IDs belonging to this halaqoh
            $studentIds = Santri::where('halaqoh_id', $h->id)->pluck('id')->map(function ($id) {
                return (string)$id;
            })->toArray();

            return [
                'id' => (string)$h->id,
                'name' => $h->nama,
                'teacher' => $h->musyrif->name ?? 'Belum Ditentukan',
                'studentIds' => $studentIds,
            ];
        });

        // C. Setorans
        $setorans = Setoran::with(['santri'])->orderByDesc('created_at')->get()->map(function ($s) {
            $typeMap = [
                'hafalan_baru' => 'Ziyadah',
                'murajaah' => 'Murajaah',
                'tasmi' => 'Tasmi\'',
            ];

            return [
                'id' => (string)$s->id,
                'studentName' => $s->santri->nama ?? 'Santri',
                'type' => $typeMap[$s->jenis] ?? 'Ziyadah',
                'surah' => $s->surah ?? '',
                'ayatDari' => (string)($s->ayat_dari ?? 1),
                'ayatSampai' => (string)($s->ayat_sampai ?? 1),
                'juz' => (string)($s->juz ?? 1),
                'kelancaran' => match($s->nilai) {
                    'Mumtaz' => 'Sangat Baik',
                    'Jayyid Jiddan' => 'Baik',
                    'Jayyid' => 'Baik',
                    'Maqbul' => 'Cukup',
                    'Rosib' => 'Kurang',
                    default => 'Baik',
                },
                'tajwid' => 'Baik',
                'makharijul' => 'Baik',
                'date' => Carbon::parse($s->tanggal)->toDateString(),
            ];
        });

        // D. Permissions
        $permissions = Perizinan::with(['santri'])->orderByDesc('created_at')->get()->map(function ($p) {
            $typeMap = [
                'sakit' => 'Sakit',
                'pulang' => 'Pulang',
                'kegiatan_luar' => 'Keluar',
                'lainnya' => 'Liburan',
            ];

            $statusMap = [
                'pending' => 'Ditinjau',
                'disetujui' => 'Disetujui',
                'ditolak' => 'Ditolak',
            ];

            $start = Carbon::parse($p->tanggal_mulai);
            $end = Carbon::parse($p->tanggal_selesai);
            $diff = $end->diffInDays($start) + 1;

            return [
                'id' => (string)$p->id,
                'studentName' => $p->santri->nama ?? 'Santri',
                'type' => $typeMap[$p->jenis] ?? 'Sakit',
                'dateStart' => $p->tanggal_mulai,
                'dateEnd' => $p->tanggal_selesai,
                'durationDays' => $diff > 0 ? $diff : 1,
                'reason' => $p->alasan,
                'contact' => $p->santri->phone ?? '081234567890',
                'status' => $statusMap[$p->status] ?? 'Ditinjau',
            ];
        });

        // E. Medical Records
        $medicalRecords = RekamKesehatan::with(['santri'])->orderByDesc('created_at')->get()->map(function ($m) {
            return [
                'id' => (string)$m->id,
                'studentName' => $m->santri->nama ?? 'Santri',
                'date' => $m->tanggal,
                'symptom' => $m->keluhan ?? '',
                'diagnosis' => $m->diagnosa ?? '',
                'action' => $m->tindakan ?? '',
                'isReferredToLeave' => (bool)$m->dirujuk,
            ];
        });

        // F. Rooms
        $rooms = Kamar::all()->map(function ($k) {
            $occupants = Santri::whereHas('kamar', function ($query) use ($k) {
                $query->where('kamar_id', $k->id);
            })->pluck('nama')->toArray();

            return [
                'id' => (string)$k->id,
                'name' => $k->nama,
                'capacity' => (int)$k->kapasitas,
                'occupants' => $occupants,
            ];
        });

        // G. Bills
        $bills = Tagihan::with(['santri', 'jenis', 'pembayaran'])->orderByDesc('id')->get()->map(function ($b) {
            $pay = $b->pembayaran->first();
            $statusMap = [
                'belum' => 'Belum Bayar',
                'lunas' => 'Lunas',
                'terlambat' => 'Belum Bayar', // Render as unpaid with warning in UI
            ];

            return [
                'id' => (string)$b->id,
                'studentName' => $b->santri->nama ?? 'Santri',
                'type' => $b->jenis->nama ?? 'Tagihan',
                'period' => $b->bulan ? "Bulan {$b->bulan}/{$b->tahun}" : "Tahun {$b->tahun}",
                'amount' => (double)$b->nominal,
                'status' => $statusMap[$b->status] ?? 'Belum Bayar',
                'datePaid' => $pay ? Carbon::parse($pay->tanggal_bayar)->toDateString() : null,
                'method' => $pay->metode ?? null,
                'notes' => $pay->catatan ?? null,
            ];
        });

        // H. Grades
        $grades = NilaiAkademik::with(['santri', 'mapel'])->get()->map(function ($g) {
            return [
                'id' => (string)$g->id,
                'studentName' => $g->santri->nama ?? 'Santri',
                'class' => $g->santri->kelas->nama ?? 'Umum',
                'subject' => $g->mapel->nama ?? 'Mapel',
                'tugas' => (double)($g->nilai_harian ?? 0),
                'uas' => (double)($g->nilai_uas ?? 0),
                'finalScore' => (double)($g->nilai_akhir ?? 0),
            ];
        });

        // I. Dynamic Activities log
        $activitiesList = collect();

        // Add sets
        Setoran::with('santri')->orderByDesc('id')->take(5)->get()->each(function ($s) use (&$activitiesList) {
            if (!$s->santri) return;
            $typeMap = ['hafalan_baru' => 'Ziyadah', 'murajaah' => 'Muraja\'ah', 'tasmi' => 'Tasmi\''];
            $typeLabel = $typeMap[$s->jenis] ?? 'Setoran';
            $activitiesList->push([
                'id' => 'act_set_' . $s->id,
                'title' => 'Setoran Hafalan',
                'description' => "{$s->santri->nama} menyetor {$typeLabel} surah {$s->surah} juz {$s->juz}.",
                'time' => Carbon::parse($s->created_at)->diffForHumans(),
                'type' => 'Tahfizh',
                'tag' => 'TAHFIZH',
                'color' => '#FF9800',
            ]);
        });

        // Add permissions
        Perizinan::with('santri')->orderByDesc('id')->take(3)->get()->each(function ($p) use (&$activitiesList) {
            if (!$p->santri) return;
            $activitiesList->push([
                'id' => 'act_perm_' . $p->id,
                'title' => 'Pengajuan Izin',
                'description' => "Santri {$p->santri->nama} mengajukan izin {$p->jenis}.",
                'time' => Carbon::parse($p->created_at)->diffForHumans(),
                'type' => 'Perizinan',
                'tag' => 'PERIZINAN',
                'color' => '#9C27B0',
            ]);
        });

        // Add medical records
        RekamKesehatan::with('santri')->orderByDesc('id')->take(3)->get()->each(function ($m) use (&$activitiesList) {
            if (!$m->santri) return;
            $activitiesList->push([
                'id' => 'act_med_' . $m->id,
                'title' => 'Pencatatan UKS',
                'description' => "Kesehatan {$m->santri->nama} dicatat di UKS: {$m->diagnosa}.",
                'time' => Carbon::parse($m->created_at)->diffForHumans(),
                'type' => 'UKS',
                'tag' => 'KESEHATAN',
                'color' => '#F44336',
            ]);
        });

        // Add bills
        Tagihan::with('santri', 'jenis')->orderByDesc('id')->take(3)->get()->each(function ($t) use (&$activitiesList) {
            if (!$t->santri) return;
            $activitiesList->push([
                'id' => 'act_bill_' . $t->id,
                'title' => 'Tagihan Dibuat',
                'description' => "Tagihan {$t->jenis->nama} dibuat untuk {$t->santri->nama}.",
                'time' => 'Baru saja',
                'type' => 'Keuangan',
                'tag' => 'KEUANGAN',
                'color' => '#2196F3',
            ]);
        });

        // Add users/students creation
        Santri::orderByDesc('id')->take(3)->get()->each(function ($s) use (&$activitiesList) {
            $activitiesList->push([
                'id' => 'act_santri_' . $s->id,
                'title' => 'Santri Terdaftar',
                'description' => "Santri {$s->nama} (NIS {$s->nis}) berhasil ditambahkan ke database.",
                'time' => 'Baru saja',
                'type' => 'Akademik',
                'tag' => 'AKADEMIK',
                'color' => '#009688',
            ]);
        });

        $activities = $activitiesList->take(15)->values()->toArray();

        // J. Schedules (Jadwal Pelajaran)
        $schedules = \App\Models\JadwalPelajaran::with(['kelas', 'mapel.guru'])->get()->map(function ($jp) {
            return [
                'id' => (string)$jp->id,
                'class' => $jp->kelas->nama ?? 'Umum',
                'subject' => $jp->mapel->nama ?? 'Mapel',
                'teacherId' => (string)($jp->mapel->guru_id ?? ''),
                'teacherName' => $jp->mapel->guru->name ?? 'Guru',
                'day' => $jp->hari,
                'timeStart' => $jp->jam_mulai ? Carbon::parse($jp->jam_mulai)->format('H:i') : '07:30',
                'timeEnd' => $jp->jam_selesai ? Carbon::parse($jp->jam_selesai)->format('H:i') : '09:00',
                'room' => $jp->ruang ?? 'Kelas',
            ];
        });

        // K. Announcements (Pengumuman)
        $announcements = Pengumuman::with('pembuat')
            ->orderByDesc('is_pinned')
            ->orderByDesc('id')
            ->get()
            ->map(function ($p) {
                return [
                    'id'        => (string)$p->id,
                    'title'     => $p->judul,
                    'body'      => $p->isi,
                    'target'    => $p->target,
                    'isPinned'  => (bool)$p->is_pinned,
                    'author'    => $p->pembuat->name ?? 'Admin',
                    'date'      => $p->published_at
                        ? Carbon::parse($p->published_at)->diffForHumans()
                        : '',
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'students'      => $students,
                'halaqohs'      => $halaqohs,
                'setorans'      => $setorans,
                'permissions'   => $permissions,
                'medicalRecords'=> $medicalRecords,
                'rooms'         => $rooms,
                'bills'         => $bills,
                'grades'        => $grades,
                'activities'    => $activities,
                'schedules'     => $schedules,
                'announcements' => $announcements,
            ]
        ]);
    }

    // 3. Add Student
    public function addStudent(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:santri,nis',
            'name' => 'required',
            'gender' => 'required|in:L,P',
            'class' => 'nullable',
        ]);

        $kelas = Kelas::where('nama', $request->input('class'))->first();
        if (!$kelas && $request->input('class')) {
            $kelas = Kelas::create(['nama' => $request->input('class'), 'kapasitas' => 30]);
        }

        $santri = Santri::create([
            'nis' => $request->input('nis'),
            'nama' => $request->input('name'),
            'jenis_kelamin' => $request->input('gender'),
            'kelas_id' => $kelas ? $kelas->id : null,
            'status' => 'aktif',
        ]);

        return response()->json([
            'status' => 'success',
            'student' => [
                'id' => (string)$santri->id,
                'nis' => $santri->nis,
                'name' => $santri->nama,
                'gender' => $santri->jenis_kelamin,
                'class' => $kelas ? $kelas->nama : 'Umum',
                'parent' => 'Orang Tua',
                'juz' => 0,
                'room' => 'Belum ada Kamar',
                'attendance' => [
                    'hadir' => 0,
                    'sakit' => 0,
                    'izin' => 0,
                    'alpha' => 0,
                ],
            ]
        ]);
    }

    // 4. Setoran Tahfizh Mutations
    public function addSetoran(Request $request)
    {
        $request->validate([
            'studentName' => 'required',
            'type' => 'required',
            'surah' => 'required',
            'ayatDari' => 'nullable',
            'ayatSampai' => 'nullable',
            'juz' => 'nullable',
        ]);

        $santri = Santri::where('nama', $request->input('studentName'))->first();
        if (!$santri) {
            $santri = Santri::create([
                'nis' => 'TBD-' . mt_rand(100, 999),
                'nama' => $request->input('studentName'),
                'jenis_kelamin' => 'L',
                'status' => 'aktif',
            ]);
        }

        $typeMap = [
            'Ziyadah' => 'hafalan_baru',
            'Murajaah' => 'murajaah',
            'Tasmi\'' => 'tasmi',
            'tasmi' => 'tasmi',
        ];
        $jenis = $typeMap[$request->input('type')] ?? 'hafalan_baru';

        // Kelancaran score mapping
        $gradeMap = [
            'Sangat Baik' => 'Mumtaz',
            'Baik' => 'Jayyid Jiddan',
            'Cukup' => 'Maqbul',
            'Kurang' => 'Rosib',
        ];
        $nilai = $gradeMap[$request->input('kelancaran')] ?? 'Mumtaz';

        $setoran = Setoran::create([
            'santri_id' => $santri->id,
            'tanggal' => Carbon::now()->toDateString(),
            'jenis' => $jenis,
            'surah' => $request->input('surah'),
            'juz' => (int)($request->input('juz') ?: 1),
            'ayat_dari' => (int)($request->input('ayatDari') ?: 1),
            'ayat_sampai' => (int)($request->input('ayatSampai') ?: 1),
            'nilai' => $nilai,
            'catatan' => $request->input('catatan') ?: '',
        ]);

        // Auto update hafalan_santri current juz completion
        $hafalan = \App\Models\HafalanSantri::firstOrCreate(
            ['santri_id' => $santri->id],
            ['target_juz' => 30, 'status' => 'aktif']
        );
        $juzInput = (int)($request->input('juz') ?: 1);
        if ($juzInput > $hafalan->juz_selesai) {
            $hafalan->juz_selesai = $juzInput;
            $hafalan->save();
        }

        return response()->json(['status' => 'success', 'id' => (string)$setoran->id]);
    }

    public function deleteSetoran($id)
    {
        Setoran::destroy($id);
        return response()->json(['status' => 'success']);
    }

    // 5. Leave Request Mutations (Perizinan)
    public function addPermission(Request $request)
    {
        $request->validate([
            'studentName' => 'required',
            'type' => 'required',
            'dateStart' => 'required',
            'dateEnd' => 'required',
            'reason' => 'required',
        ]);

        $santri = Santri::where('nama', $request->input('studentName'))->first();
        if (!$santri) {
            return response()->json(['status' => 'error', 'message' => 'Santri tidak ditemukan.'], 404);
        }

        $typeMap = [
            'Sakit' => 'sakit',
            'Pulang' => 'pulang',
            'Keluar' => 'kegiatan_luar',
            'Liburan' => 'lainnya',
        ];
        $jenis = $typeMap[$request->input('type')] ?? 'lainnya';

        $perm = Perizinan::create([
            'santri_id' => $santri->id,
            'jenis' => $jenis,
            'tanggal_mulai' => $request->input('dateStart'),
            'tanggal_selesai' => $request->input('dateEnd'),
            'alasan' => $request->input('reason'),
            'status' => 'pending',
        ]);

        return response()->json(['status' => 'success', 'id' => (string)$perm->id]);
    }

    public function approvePermission($id)
    {
        $perm = Perizinan::find($id);
        if ($perm) {
            $perm->status = 'disetujui';
            $perm->save();
        }
        return response()->json(['status' => 'success']);
    }

    public function rejectPermission($id)
    {
        $perm = Perizinan::find($id);
        if ($perm) {
            $perm->status = 'ditolak';
            $perm->save();
        }
        return response()->json(['status' => 'success']);
    }

    // 6. Kesehatan (Medical Record) Mutations
    public function addMedicalRecord(Request $request)
    {
        $request->validate([
            'studentName' => 'required',
            'symptom' => 'required',
            'diagnosis' => 'required',
            'action' => 'required',
        ]);

        $santri = Santri::where('nama', $request->input('studentName'))->first();
        if (!$santri) {
            return response()->json(['status' => 'error', 'message' => 'Santri tidak ditemukan.'], 404);
        }

        $med = RekamKesehatan::create([
            'santri_id' => $santri->id,
            'tanggal' => Carbon::now()->toDateString(),
            'keluhan' => $request->input('symptom'),
            'diagnosa' => $request->input('diagnosis'),
            'tindakan' => $request->input('action'),
            'dirujuk' => $request->input('isReferredToLeave') ? 1 : 0,
        ]);

        if ($request->input('isReferredToLeave')) {
            Perizinan::create([
                'santri_id' => $santri->id,
                'jenis' => 'sakit',
                'tanggal_mulai' => Carbon::now()->toDateString(),
                'tanggal_selesai' => Carbon::now()->toDateString(),
                'alasan' => 'Rujukan UKS: ' . $request->input('diagnosis'),
                'status' => 'pending',
            ]);
        }

        return response()->json(['status' => 'success', 'id' => (string)$med->id]);
    }

    // 7. Halaqoh Mutations
    public function addHalaqoh(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'teacher' => 'required',
        ]);

        $musyrif = User::where('name', $request->input('teacher'))->first();
        if (!$musyrif) {
            $musyrif = User::create([
                'name' => $request->input('teacher'),
                'email' => strtolower(str_replace(' ', '', $request->input('teacher'))) . '@suntri.com',
                'password' => bcrypt('password123'),
                'role_id' => 2, // Musyrif
            ]);
        }

        $hq = Halaqoh::create([
            'nama' => $request->input('name'),
            'musyrif_id' => $musyrif->id,
        ]);

        return response()->json(['status' => 'success', 'id' => (string)$hq->id]);
    }

    public function updateHalaqoh(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'teacher' => 'required',
        ]);

        $hq = Halaqoh::find($id);
        if ($hq) {
            $musyrif = User::where('name', $request->input('teacher'))->first();
            if (!$musyrif) {
                $musyrif = User::create([
                    'name' => $request->input('teacher'),
                    'email' => strtolower(str_replace(' ', '', $request->input('teacher'))) . '@suntri.com',
                    'password' => bcrypt('password123'),
                    'role_id' => 2,
                ]);
            }
            $hq->nama = $request->input('name');
            $hq->musyrif_id = $musyrif->id;
            $hq->save();
        }

        return response()->json(['status' => 'success']);
    }

    public function deleteHalaqoh($id)
    {
        Halaqoh::destroy($id);
        return response()->json(['status' => 'success']);
    }

    // 8. Finance (Billing) Mutations
    public function generateBill(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'period' => 'required',
            'amount' => 'required|numeric',
        ]);

        $jenis = JenisTagihan::where('nama', $request->input('type'))->first();
        if (!$jenis) {
            $jenis = JenisTagihan::create([
                'nama' => $request->input('type'),
                'nominal' => $request->input('amount'),
                'periode' => 'bulanan',
            ]);
        }

        // Get affected students
        $students = collect();
        if ($request->filled('targetStudent')) {
            $students = Santri::where('nama', $request->input('targetStudent'))->get();
        } elseif ($request->filled('targetClass')) {
            $students = Santri::whereHas('kelas', function ($q) use ($request) {
                $q->where('nama', $request->input('targetClass'));
            })->get();
        } else {
            $students = Santri::all();
        }

        foreach ($students as $s) {
            Tagihan::create([
                'santri_id' => $s->id,
                'jenis_id' => $jenis->id,
                'bulan' => Carbon::now()->month,
                'tahun' => Carbon::now()->year,
                'nominal' => $request->input('amount'),
                'jatuh_tempo' => Carbon::now()->addDays(14)->toDateString(),
                'status' => 'belum',
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function payBill(Request $request)
    {
        $request->validate([
            'billId' => 'required',
            'nominal' => 'required|numeric',
            'method' => 'required',
        ]);

        $tagihan = Tagihan::find($request->input('billId'));
        if ($tagihan) {
            $tagihan->status = 'lunas';
            $tagihan->save();

            Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'santri_id' => $tagihan->santri_id,
                'tanggal_bayar' => $request->input('date') ?: Carbon::now()->toDateTimeString(),
                'nominal_bayar' => $request->input('nominal'),
                'metode' => strtolower($request->input('method')),
                'no_invoice' => 'INV-' . Carbon::now()->format('Ymd') . '-' . mt_rand(1000, 9999),
                'catatan' => $request->input('notes') ?: '',
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    // 9. Academic Grade Mutations
    public function saveGrade(Request $request)
    {
        $request->validate([
            'studentName' => 'required',
            'className' => 'required',
            'subject' => 'required',
            'tugas' => 'required|numeric',
            'uh' => 'required|numeric',
            'uts' => 'required|numeric',
            'uas' => 'required|numeric',
        ]);

        $santri = Santri::where('nama', $request->input('studentName'))->first();
        if (!$santri) {
            return response()->json(['status' => 'error', 'message' => 'Santri tidak ditemukan.'], 404);
        }

        $mapel = MataPelajaran::where('nama', $request->input('subject'))->first();
        if (!$mapel) {
            $mapel = MataPelajaran::create([
                'nama' => $request->input('subject'),
                'kode' => 'MAP-' . mt_rand(100, 999),
            ]);
        }

        $finalScore = ($request->input('tugas') * 0.2) + ($request->input('uh') * 0.1) + ($request->input('uts') * 0.3) + ($request->input('uas') * 0.4);
        $predikat = match(true) {
            $finalScore >= 85 => 'A',
            $finalScore >= 75 => 'B',
            $finalScore >= 60 => 'C',
            $finalScore >= 45 => 'D',
            default => 'E',
        };

        $grade = NilaiAkademik::updateOrCreate(
            [
                'santri_id' => $santri->id,
                'mapel_id' => $mapel->id,
                'semester' => 1,
                'tahun_ajaran' => '2026/2027',
            ],
            [
                'nilai_harian' => $request->input('tugas'), // Use tugas as harian representation
                'nilai_uas' => $request->input('uas'),
                'nilai_akhir' => $finalScore,
                'predikat' => $predikat,
            ]
        );

        return response()->json(['status' => 'success']);
    }
}
