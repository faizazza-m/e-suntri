<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->autoIncrement();
            $table->string('name', 30)->unique();
        });

        // Insert Default Roles immediately so users table can reference them
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'musyrif'],
            ['id' => 3, 'name' => 'wali'],
            ['id' => 4, 'name' => 'santri'],
            ['id' => 6, 'name' => 'mudir'], // mudir
        ]);

        // 2. Alter existing Users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('role_id')->after('password')->default(4);
            $table->string('phone', 20)->nullable()->after('role_id');
            $table->string('foto', 255)->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('foto');
            
            $table->foreign('role_id')->references('id')->on('roles');
        });

        // 3. Kelas
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('julukan', 50)->nullable();
            $table->unsignedTinyInteger('tingkat')->nullable();
            $table->foreignId('wali_kelas_id')->nullable()->constrained('users')->nullOnDelete();
        });

        // 4. Halaqoh
        Schema::create('halaqoh', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 80);
            $table->foreignId('musyrif_id')->nullable()->constrained('users')->nullOnDelete();
        });

        // 5. Santri
        Schema::create('santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nis', 20)->unique();
            $table->string('nama', 150);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('asal_sekolah', 150)->nullable();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->foreignId('halaqoh_id')->nullable()->constrained('halaqoh')->nullOnDelete();
            $table->year('tahun_masuk')->nullable();
            $table->enum('status', ['aktif', 'alumni', 'keluar'])->default('aktif');
            $table->string('foto', 255)->nullable();
            $table->timestamps();
        });

        // 6. Wali Santri
        Schema::create('wali_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->string('nama', 150);
            $table->enum('hubungan', ['ayah', 'ibu', 'wali'])->default('ayah');
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('alamat')->nullable();
            $table->string('pekerjaan', 100)->nullable();
        });

        // 7. Hafalan Santri
        Schema::create('hafalan_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->unsignedTinyInteger('juz_selesai')->default(0);
            $table->unsignedTinyInteger('target_juz')->default(30);
            $table->enum('status', ['aktif', 'murajaah', 'lulus'])->default('aktif');
            $table->timestamps();
        });

        // 8. Setoran
        Schema::create('setoran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('musyrif_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal');
            $table->enum('jenis', ['hafalan_baru', 'murajaah', 'tasmi']);
            $table->string('surah', 80)->nullable();
            $table->unsignedSmallInteger('ayat_dari')->nullable();
            $table->unsignedSmallInteger('ayat_sampai')->nullable();
            $table->enum('nilai', ['Mumtaz', 'Jayyid Jiddan', 'Jayyid', 'Maqbul', 'Rosib'])->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 9. Mata Pelajaran
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('kode', 20)->nullable();
            $table->foreignId('guru_id')->nullable()->constrained('users')->nullOnDelete();
        });

        // 10. Jadwal Pelajaran
        Schema::create('jadwal_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruang', 50)->nullable();
        });

        // 11. Nilai Akademik
        Schema::create('nilai_akademik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->unsignedTinyInteger('semester');
            $table->string('tahun_ajaran', 10);
            $table->decimal('nilai_harian', 5, 2)->nullable();
            $table->decimal('nilai_uts', 5, 2)->nullable();
            $table->decimal('nilai_uas', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->enum('predikat', ['A', 'B', 'C', 'D', 'E'])->nullable();
            $table->timestamps();
        });

        // 12. Kehadiran
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha']);
            $table->text('keterangan')->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['santri_id', 'tanggal']);
        });

        // 13. Perizinan
        Schema::create('perizinan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->enum('jenis', ['pulang', 'sakit', 'kegiatan_luar', 'lainnya']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        // 14. Jenis Tagihan
        Schema::create('jenis_tagihan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->decimal('nominal', 12, 2);
            $table->enum('periode', ['bulanan', 'tahunan', 'sekali'])->default('bulanan');
            $table->text('keterangan')->nullable();
        });

        // 15. Tagihan
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('jenis_id')->constrained('jenis_tagihan')->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan')->nullable();
            $table->year('tahun');
            $table->decimal('nominal', 12, 2);
            $table->date('jatuh_tempo');
            $table->enum('status', ['belum', 'lunas', 'terlambat'])->default('belum');
            $table->timestamps();
        });

        // 16. Pembayaran
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained('tagihan')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->dateTime('tanggal_bayar');
            $table->decimal('nominal_bayar', 12, 2);
            $table->enum('metode', ['tunai', 'transfer', 'qris'])->default('tunai');
            $table->string('bukti_foto', 255)->nullable();
            $table->string('no_invoice', 50)->unique()->nullable();
            $table->foreignId('dikonfirmasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 17. Kamar
        Schema::create('kamar', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->unsignedTinyInteger('kapasitas')->default(8);
            $table->string('gedung', 50)->nullable();
            $table->unsignedTinyInteger('lantai')->default(1);
        });

        // 18. Penghuni Kamar
        Schema::create('penghuni_kamar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->unique()->constrained('santri')->cascadeOnDelete();
            $table->foreignId('kamar_id')->constrained('kamar')->cascadeOnDelete();
            $table->date('tanggal_masuk')->nullable();
        });

        // 19. Rekam Kesehatan
        Schema::create('rekam_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('keluhan')->nullable();
            $table->text('diagnosa')->nullable();
            $table->text('tindakan')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('dirujuk')->default(false);
            $table->string('tempat_rujukan', 150)->nullable();
            $table->timestamps();
        });

        // 20. Pengumuman
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->text('isi');
            $table->enum('target', ['semua', 'wali', 'santri', 'musyrif'])->default('semua');
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 21. Konsultasi
        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wali_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('musyrif_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->string('topik', 200)->nullable();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });

        // 22. Pesan Konsultasi
        Schema::create('pesan_konsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konsultasi_id')->constrained('konsultasi')->cascadeOnDelete();
            $table->foreignId('pengirim_id')->constrained('users')->cascadeOnDelete();
            $table->text('isi');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 23. Buku
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->string('pengarang', 150)->nullable();
            $table->string('penerbit', 100)->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->string('isbn', 30)->nullable();
            $table->string('kategori', 80)->nullable();
            $table->unsignedSmallInteger('stok')->default(1);
            $table->unsignedSmallInteger('tersedia')->default(1);
        });

        // 24. Peminjaman Buku
        Schema::create('peminjaman_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('buku_id')->constrained('buku')->cascadeOnDelete();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
            $table->decimal('denda', 10, 2)->default(0);
        });

        // 25. Pendaftar PPDB
        Schema::create('pendaftar_ppdb', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 150);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('asal_sekolah', 150)->nullable();
            $table->string('nama_wali', 150);
            $table->string('phone_wali', 20);
            $table->string('email_wali', 150)->nullable();
            $table->string('tahun_ajaran', 10);
            $table->enum('status', ['pending', 'lulus', 'tidak_lulus', 'mengundurkan_diri'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 26. Prestasi
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->string('nama_lomba', 200);
            $table->enum('tingkat', ['sekolah', 'kabupaten', 'provinsi', 'nasional', 'internasional'])->nullable();
            $table->string('peringkat', 50)->nullable();
            $table->year('tahun');
            $table->text('keterangan')->nullable();
            $table->string('bukti_foto', 255)->nullable();
            $table->timestamps();
        });

        // 27. Notifikasi
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul', 200);
            $table->text('pesan');
            $table->enum('tipe', ['hafalan', 'keuangan', 'perizinan', 'pengumuman', 'kesehatan'])->default('pengumuman');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop in reverse order of creation
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('prestasi');
        Schema::dropIfExists('pendaftar_ppdb');
        Schema::dropIfExists('peminjaman_buku');
        Schema::dropIfExists('buku');
        Schema::dropIfExists('pesan_konsultasi');
        Schema::dropIfExists('konsultasi');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('rekam_kesehatan');
        Schema::dropIfExists('penghuni_kamar');
        Schema::dropIfExists('kamar');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('tagihan');
        Schema::dropIfExists('jenis_tagihan');
        Schema::dropIfExists('perizinan');
        Schema::dropIfExists('kehadiran');
        Schema::dropIfExists('nilai_akademik');
        Schema::dropIfExists('jadwal_pelajaran');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('setoran');
        Schema::dropIfExists('hafalan_santri');
        Schema::dropIfExists('wali_santri');
        Schema::dropIfExists('santri');
        Schema::dropIfExists('halaqoh');
        Schema::dropIfExists('kelas');

        // Alter users back
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['role_id']);
                $table->dropColumn(['role_id', 'phone', 'foto', 'is_active']);
            });
        }

        Schema::dropIfExists('roles');
    }
};
