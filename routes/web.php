<?php

use Illuminate\Support\Facades\Route;

// =============================================
// Auth Routes
// =============================================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'login_identifier' => ['required'],
        'password' => ['required'],
    ]);

    $identifier = $request->input('login_identifier');
    $password = $request->input('password');

    // Default assume it's email
    $credentials = ['email' => $identifier, 'password' => $password];

    // If it looks like a NISN (mostly numbers)
    if (preg_match('/^[0-9]+$/', $identifier) || str_starts_with($identifier, 'TBD-')) {
        $santri = \App\Models\Santri::where('nis', $identifier)->first();
        if ($santri) {
            $wali = \App\Models\WaliSantri::where('santri_id', $santri->id)->first();
            if ($wali && $wali->user) {
                $credentials = ['email' => $wali->user->email, 'password' => $password];
            }
        }
    }

    if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->has('remember'))) {
        $request->session()->regenerate();
        
        $role = \Illuminate\Support\Facades\Auth::user()->role_id;
        // 1: admin, 2: musyrif, 3: wali, 4: santri, 5: ustadz/guru
        if ($role == 2) {
            return redirect()->route('musyrif.dashboard');
        }
        if ($role == 3) {
            return redirect()->route('wali.home');
        }
        if ($role == 5) {
            return redirect()->route('guru.dashboard');
        }
        
        return redirect()->route('dashboard');
    }

    return back()->withErrors([
        'login_identifier' => 'Email/NISN atau password salah.',
    ])->onlyInput('login_identifier');
})->name('login.post');

Route::match(['get', 'post'], '/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// =============================================
// Admin Routes (Desktop Layout)
// =============================================
Route::middleware(['auth', \App\Http\Middleware\PreventBackHistory::class])->group(function () {
    
    // Shared Profile routes
    Route::get('/profile/edit',     [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/edit',     [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [\App\Http\Controllers\ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');


    Route::prefix('admin')->name('')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kehadiran', [\App\Http\Controllers\Admin\KehadiranController::class, 'index'])->name('kehadiran');
    Route::get('/aktivitas', [\App\Http\Controllers\Admin\AktivitasController::class, 'index'])->name('aktivitas');
    
    // Kamar / Asrama
    Route::get('/kamar', [\App\Http\Controllers\Admin\KamarController::class, 'index'])->name('kamar');
    Route::post('/kamar', [\App\Http\Controllers\Admin\KamarController::class, 'store'])->name('kamar.store');
    Route::get('/kamar/{kamar}', [\App\Http\Controllers\Admin\KamarController::class, 'show'])->name('kamar.show');
    Route::post('/kamar/{kamar}/santri', [\App\Http\Controllers\Admin\KamarController::class, 'addSantri'])->name('kamar.santri.add');
    Route::delete('/kamar/{kamar}/santri/{penghuni}', [\App\Http\Controllers\Admin\KamarController::class, 'removeSantri'])->name('kamar.santri.remove');

    Route::get('/pengguna', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('pengguna');
    Route::post('/santri', [\App\Http\Controllers\Admin\SantriController::class, 'store'])->name('santri.store');
    Route::put('/santri/{id}', [\App\Http\Controllers\Admin\SantriController::class, 'update'])->name('santri.update');
    Route::delete('/santri/{id}', [\App\Http\Controllers\Admin\SantriController::class, 'destroy'])->name('santri.destroy');

    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');



    Route::get('/akademik', [\App\Http\Controllers\Admin\AkademikController::class, 'index'])->name('akademik');
    Route::post('/akademik/kelas', [\App\Http\Controllers\Admin\AkademikController::class, 'storeKelas'])->name('akademik.kelas.store');
    Route::put('/akademik/kelas/{id}', [\App\Http\Controllers\Admin\AkademikController::class, 'updateKelas'])->name('akademik.kelas.update');
    Route::delete('/akademik/kelas/{id}', [\App\Http\Controllers\Admin\AkademikController::class, 'destroyKelas'])->name('akademik.kelas.destroy');

    Route::post('/akademik/jadwal', [\App\Http\Controllers\Admin\AkademikController::class, 'storeJadwal'])->name('akademik.jadwal.store');
    Route::put('/akademik/jadwal/{id}', [\App\Http\Controllers\Admin\AkademikController::class, 'updateJadwal'])->name('akademik.jadwal.update');
    Route::delete('/akademik/jadwal/{id}', [\App\Http\Controllers\Admin\AkademikController::class, 'destroyJadwal'])->name('akademik.jadwal.destroy');

    // Mata Pelajaran (Mapel)
    Route::get('/akademik/mapel', [\App\Http\Controllers\Admin\AkademikController::class, 'mapelIndex'])->name('akademik.mapel');
    Route::post('/akademik/mapel', [\App\Http\Controllers\Admin\AkademikController::class, 'mapelStore'])->name('akademik.mapel.store');
    Route::put('/akademik/mapel/{id}', [\App\Http\Controllers\Admin\AkademikController::class, 'mapelUpdate'])->name('akademik.mapel.update');
    Route::delete('/akademik/mapel/{id}', [\App\Http\Controllers\Admin\AkademikController::class, 'mapelDestroy'])->name('akademik.mapel.destroy');

    // Data Nilai
    Route::get('/akademik/nilai', [\App\Http\Controllers\Admin\AkademikController::class, 'nilaiIndex'])->name('akademik.nilai');
    Route::post('/akademik/nilai', [\App\Http\Controllers\Admin\AkademikController::class, 'nilaiStore'])->name('akademik.nilai.store');
    Route::put('/akademik/nilai/{id}', [\App\Http\Controllers\Admin\AkademikController::class, 'nilaiUpdate'])->name('akademik.nilai.update');
    Route::delete('/akademik/nilai/{id}', [\App\Http\Controllers\Admin\AkademikController::class, 'nilaiDestroy'])->name('akademik.nilai.destroy');

    // Ujian & Tugas
    Route::get('/akademik/ujian', [\App\Http\Controllers\Admin\AkademikController::class, 'ujianIndex'])->name('akademik.ujian');
    Route::post('/akademik/ujian', [\App\Http\Controllers\Admin\AkademikController::class, 'ujianStore'])->name('akademik.ujian.store');
    Route::delete('/akademik/ujian/{id}', [\App\Http\Controllers\Admin\AkademikController::class, 'ujianDestroy'])->name('akademik.ujian.destroy');

    // Raport
    Route::get('/akademik/raport', [\App\Http\Controllers\Admin\AkademikController::class, 'raportIndex'])->name('akademik.raport');

    Route::get('/tahfizh', [\App\Http\Controllers\Admin\TahfizhController::class, 'index'])->name('tahfizh');
    Route::post('/tahfizh/setoran', [\App\Http\Controllers\Admin\TahfizhController::class, 'storeSetoran'])->name('tahfizh.setoran.store');
    Route::put('/tahfizh/hafalan/{id}', [\App\Http\Controllers\Admin\TahfizhController::class, 'updateHafalan'])->name('tahfizh.hafalan.update');
    
    // Kelola Halaqoh
    Route::post('/tahfizh/halaqoh', [\App\Http\Controllers\Admin\TahfizhController::class, 'storeHalaqoh'])->name('tahfizh.halaqoh.store');
    Route::put('/tahfizh/halaqoh/{id}', [\App\Http\Controllers\Admin\TahfizhController::class, 'updateHalaqoh'])->name('tahfizh.halaqoh.update');
    Route::delete('/tahfizh/halaqoh/{id}', [\App\Http\Controllers\Admin\TahfizhController::class, 'destroyHalaqoh'])->name('tahfizh.halaqoh.destroy');

    Route::get('/asrama', function () {
        return view('admin.asrama');
    })->name('asrama');

    Route::get('/keuangan', [\App\Http\Controllers\Admin\KeuanganController::class, 'index'])->name('keuangan');
    Route::post('/keuangan/tagihan', [\App\Http\Controllers\Admin\KeuanganController::class, 'storeTagihan'])->name('keuangan.tagihan.store');
    Route::put('/keuangan/tagihan/{id}', [\App\Http\Controllers\Admin\KeuanganController::class, 'updateTagihan'])->name('keuangan.tagihan.update');
    Route::post('/keuangan/pembayaran', [\App\Http\Controllers\Admin\KeuanganController::class, 'bayarTagihan'])->name('keuangan.pembayaran.store');

    Route::get('/perizinan', [\App\Http\Controllers\Admin\PerizinanController::class, 'index'])->name('perizinan');
    Route::post('/perizinan', [\App\Http\Controllers\Admin\PerizinanController::class, 'store'])->name('perizinan.store');
    Route::put('/perizinan/{id}/status', [\App\Http\Controllers\Admin\PerizinanController::class, 'updateStatus'])->name('perizinan.status');

    Route::get('/kesehatan', [\App\Http\Controllers\Admin\KesehatanController::class, 'index'])->name('kesehatan');
    Route::post('/kesehatan', [\App\Http\Controllers\Admin\KesehatanController::class, 'store'])->name('kesehatan.store');

    Route::get('/pengumuman', [\App\Http\Controllers\Admin\PengumumanController::class, 'index'])->name('pengumuman');
    Route::post('/pengumuman', [\App\Http\Controllers\Admin\PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::delete('/pengumuman/{id}', [\App\Http\Controllers\Admin\PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    Route::get('/konsultasi', function () {
        return view('admin.konsultasi');
    })->name('konsultasi');

    Route::get('/chat', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat');

    Route::get('/perpustakaan', function () {
        return view('admin.perpustakaan');
    })->name('perpustakaan');

    Route::get('/ppdb', function () {
        return view('admin.ppdb');
    })->name('ppdb');

    Route::get('/prestasi', function () {
        return view('admin.prestasi');
    })->name('prestasi');
});

// =============================================
// Wali Santri Routes (Mobile Layout)
// =============================================
Route::middleware(['auth'])->prefix('wali')->name('wali.')->group(function () {

    Route::get('/beranda', [\App\Http\Controllers\Wali\WaliController::class, 'dashboard'])->name('home');

    Route::get('/progres', [\App\Http\Controllers\Wali\WaliController::class, 'progres'])->name('progres');

    Route::get('/keuangan', function () {
        return view('wali.keuangan');
    })->name('keuangan');

        Route::get('/izin', [\App\Http\Controllers\Wali\WaliController::class, 'izin'])->name('izin');
        Route::post('/izin', [\App\Http\Controllers\Wali\WaliController::class, 'storeIzin'])->name('izin.store');

        Route::get('/chat', [\App\Http\Controllers\Wali\WaliController::class, 'chat'])->name('chat');
        Route::get('/chat/room/{user_id}', [\App\Http\Controllers\Wali\WaliController::class, 'chatRoom'])->name('chat.room');
    });

    // Global Chat API Endpoints (accessible by any authenticated user)
    Route::get('/api/chat/messages/{user_id}', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('api.chat.messages');
    Route::post('/api/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('api.chat.send');

});

// =============================================
// Guru / Ustadz Routes (Responsive Layout)
// =============================================
Route::middleware(['auth'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard',  [\App\Http\Controllers\Guru\GuruController::class, 'dashboard'])->name('dashboard');
    Route::get('/nilai',      [\App\Http\Controllers\Guru\GuruController::class, 'nilai'])->name('nilai');
    Route::post('/nilai',     [\App\Http\Controllers\Guru\GuruController::class, 'storeNilai'])->name('nilai.store');
    Route::get('/jadwal',     [\App\Http\Controllers\Guru\GuruController::class, 'jadwal'])->name('jadwal');
    Route::get('/pengumuman', [\App\Http\Controllers\Guru\GuruController::class, 'pengumuman'])->name('pengumuman');
});

// =============================================
// Musyrif Routes (Role 2)
// =============================================
Route::middleware(['auth'])->prefix('musyrif')->name('musyrif.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Musyrif\MusyrifController::class, 'dashboard'])->name('dashboard');
    Route::get('/tahfizh', [\App\Http\Controllers\Musyrif\MusyrifController::class, 'tahfizh'])->name('tahfizh');
    Route::post('/tahfizh', [\App\Http\Controllers\Musyrif\MusyrifController::class, 'storeSetoran'])->name('tahfizh.store');
    Route::get('/pengumuman', [\App\Http\Controllers\Musyrif\MusyrifController::class, 'pengumuman'])->name('pengumuman');
    Route::post('/sakit', [\App\Http\Controllers\Musyrif\MusyrifController::class, 'storeSakit'])->name('sakit.store');
    Route::post('/kehadiran', [\App\Http\Controllers\Musyrif\MusyrifController::class, 'storeKehadiran'])->name('kehadiran.store');
    
    // Chat
    Route::get('/chat', [\App\Http\Controllers\Musyrif\ChatController::class, 'index'])->name('chat');

    // Rekap Data
    Route::get('/rekap', [\App\Http\Controllers\Musyrif\MusyrifController::class, 'rekap'])->name('rekap');
    Route::get('/rekap/export', [\App\Http\Controllers\Musyrif\MusyrifController::class, 'exportCsv'])->name('rekap.export');
});
