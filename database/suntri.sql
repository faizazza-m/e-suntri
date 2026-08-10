-- ============================================================
-- SUNTRI DATABASE SCHEMA
-- Sistem Informasi Pesantren Terpadu
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. USERS & ROLES
-- ============================================================
CREATE TABLE roles (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL UNIQUE  -- admin, musyrif, wali, santri
);

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    role_id TINYINT UNSIGNED NOT NULL,
    phone VARCHAR(20),
    foto VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- ============================================================
-- 2. KELAS & HALAQOH
-- ============================================================
CREATE TABLE kelas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,        -- e.g. 7A
    julukan VARCHAR(50),              -- e.g. Madinah
    tingkat TINYINT UNSIGNED,         -- 7, 8, 9
    wali_kelas_id BIGINT UNSIGNED,
    FOREIGN KEY (wali_kelas_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE halaqoh (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(80) NOT NULL,        -- e.g. Imam Malik
    musyrif_id BIGINT UNSIGNED,
    FOREIGN KEY (musyrif_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- 3. SANTRI
-- ============================================================
CREATE TABLE santri (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    nis VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(150) NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL,
    tanggal_lahir DATE,
    asal_sekolah VARCHAR(150),
    kelas_id INT UNSIGNED,
    halaqoh_id INT UNSIGNED,
    tahun_masuk YEAR,
    status ENUM('aktif','alumni','keluar') DEFAULT 'aktif',
    foto VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE SET NULL,
    FOREIGN KEY (halaqoh_id) REFERENCES halaqoh(id) ON DELETE SET NULL
);

CREATE TABLE wali_santri (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    santri_id BIGINT UNSIGNED NOT NULL,
    nama VARCHAR(150) NOT NULL,
    hubungan ENUM('ayah','ibu','wali') DEFAULT 'ayah',
    phone VARCHAR(20),
    email VARCHAR(150),
    alamat TEXT,
    pekerjaan VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE
);

-- ============================================================
-- 4. TAHFIZH
-- ============================================================
CREATE TABLE hafalan_santri (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    santri_id BIGINT UNSIGNED NOT NULL,
    juz_selesai TINYINT UNSIGNED DEFAULT 0,
    target_juz TINYINT UNSIGNED DEFAULT 30,
    status ENUM('aktif','murajaah','lulus') DEFAULT 'aktif',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE
);

CREATE TABLE setoran (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    santri_id BIGINT UNSIGNED NOT NULL,
    musyrif_id BIGINT UNSIGNED,
    tanggal DATE NOT NULL,
    jenis ENUM('hafalan_baru','murajaah','tasmi') NOT NULL,
    surah VARCHAR(80),
    ayat_dari SMALLINT UNSIGNED,
    ayat_sampai SMALLINT UNSIGNED,
    nilai ENUM('Mumtaz','Jayyid Jiddan','Jayyid','Maqbul','Rosib'),
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE,
    FOREIGN KEY (musyrif_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- 5. AKADEMIK
-- ============================================================
CREATE TABLE mata_pelajaran (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kode VARCHAR(20),
    guru_id BIGINT UNSIGNED,
    FOREIGN KEY (guru_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE jadwal_pelajaran (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kelas_id INT UNSIGNED NOT NULL,
    mapel_id INT UNSIGNED NOT NULL,
    hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    ruang VARCHAR(50),
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE
);

CREATE TABLE nilai_akademik (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    santri_id BIGINT UNSIGNED NOT NULL,
    mapel_id INT UNSIGNED NOT NULL,
    semester TINYINT UNSIGNED NOT NULL,
    tahun_ajaran VARCHAR(10) NOT NULL,
    nilai_harian DECIMAL(5,2),
    nilai_uts DECIMAL(5,2),
    nilai_uas DECIMAL(5,2),
    nilai_akhir DECIMAL(5,2),
    predikat ENUM('A','B','C','D','E'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE,
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE
);

-- ============================================================
-- 6. KEHADIRAN
-- ============================================================
CREATE TABLE kehadiran (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    santri_id BIGINT UNSIGNED NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('hadir','sakit','izin','alpha') NOT NULL,
    keterangan TEXT,
    dicatat_oleh BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE,
    FOREIGN KEY (dicatat_oleh) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_kehadiran (santri_id, tanggal)
);

-- ============================================================
-- 7. PERIZINAN
-- ============================================================
CREATE TABLE perizinan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    santri_id BIGINT UNSIGNED NOT NULL,
    jenis ENUM('pulang','sakit','kegiatan_luar','lainnya') NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    alasan TEXT NOT NULL,
    status ENUM('pending','disetujui','ditolak') DEFAULT 'pending',
    disetujui_oleh BIGINT UNSIGNED,
    catatan_admin TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE,
    FOREIGN KEY (disetujui_oleh) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- 8. KEUANGAN
-- ============================================================
CREATE TABLE jenis_tagihan (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,       -- SPP Bulanan, Uang Makan, dll
    nominal DECIMAL(12,2) NOT NULL,
    periode ENUM('bulanan','tahunan','sekali') DEFAULT 'bulanan',
    keterangan TEXT
);

CREATE TABLE tagihan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    santri_id BIGINT UNSIGNED NOT NULL,
    jenis_id INT UNSIGNED NOT NULL,
    bulan TINYINT UNSIGNED,
    tahun YEAR NOT NULL,
    nominal DECIMAL(12,2) NOT NULL,
    jatuh_tempo DATE NOT NULL,
    status ENUM('belum','lunas','terlambat') DEFAULT 'belum',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE,
    FOREIGN KEY (jenis_id) REFERENCES jenis_tagihan(id)
);

CREATE TABLE pembayaran (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tagihan_id BIGINT UNSIGNED NOT NULL,
    santri_id BIGINT UNSIGNED NOT NULL,
    tanggal_bayar DATETIME NOT NULL,
    nominal_bayar DECIMAL(12,2) NOT NULL,
    metode ENUM('tunai','transfer','qris') DEFAULT 'tunai',
    bukti_foto VARCHAR(255),
    no_invoice VARCHAR(50) UNIQUE,
    dikonfirmasi_oleh BIGINT UNSIGNED,
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tagihan_id) REFERENCES tagihan(id) ON DELETE CASCADE,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE,
    FOREIGN KEY (dikonfirmasi_oleh) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- 9. ASRAMA / KAMAR
-- ============================================================
CREATE TABLE kamar (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    kapasitas TINYINT UNSIGNED DEFAULT 8,
    gedung VARCHAR(50),
    lantai TINYINT UNSIGNED DEFAULT 1
);

CREATE TABLE penghuni_kamar (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    santri_id BIGINT UNSIGNED NOT NULL UNIQUE,
    kamar_id INT UNSIGNED NOT NULL,
    tanggal_masuk DATE,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE,
    FOREIGN KEY (kamar_id) REFERENCES kamar(id) ON DELETE CASCADE
);

-- ============================================================
-- 10. KESEHATAN
-- ============================================================
CREATE TABLE rekam_kesehatan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    santri_id BIGINT UNSIGNED NOT NULL,
    tanggal DATE NOT NULL,
    keluhan TEXT,
    diagnosa TEXT,
    tindakan TEXT,
    petugas_id BIGINT UNSIGNED,
    dirujuk TINYINT(1) DEFAULT 0,
    tempat_rujukan VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE,
    FOREIGN KEY (petugas_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- 11. PENGUMUMAN
-- ============================================================
CREATE TABLE pengumuman (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    isi TEXT NOT NULL,
    target ENUM('semua','wali','santri','musyrif') DEFAULT 'semua',
    dibuat_oleh BIGINT UNSIGNED,
    is_pinned TINYINT(1) DEFAULT 0,
    published_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dibuat_oleh) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- 12. KONSULTASI / CHAT
-- ============================================================
CREATE TABLE konsultasi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    wali_id BIGINT UNSIGNED NOT NULL,
    musyrif_id BIGINT UNSIGNED NOT NULL,
    santri_id BIGINT UNSIGNED NOT NULL,
    topik VARCHAR(200),
    status ENUM('aktif','selesai') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (wali_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (musyrif_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE
);

CREATE TABLE pesan_konsultasi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    konsultasi_id BIGINT UNSIGNED NOT NULL,
    pengirim_id BIGINT UNSIGNED NOT NULL,
    isi TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (konsultasi_id) REFERENCES konsultasi(id) ON DELETE CASCADE,
    FOREIGN KEY (pengirim_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- 13. PERPUSTAKAAN
-- ============================================================
CREATE TABLE buku (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    pengarang VARCHAR(150),
    penerbit VARCHAR(100),
    tahun_terbit YEAR,
    isbn VARCHAR(30),
    kategori VARCHAR(80),
    stok SMALLINT UNSIGNED DEFAULT 1,
    tersedia SMALLINT UNSIGNED DEFAULT 1
);

CREATE TABLE peminjaman_buku (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    santri_id BIGINT UNSIGNED NOT NULL,
    buku_id INT UNSIGNED NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali_rencana DATE NOT NULL,
    tanggal_kembali_aktual DATE,
    status ENUM('dipinjam','dikembalikan','terlambat') DEFAULT 'dipinjam',
    denda DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE,
    FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE
);

-- ============================================================
-- 14. PPDB (Penerimaan Peserta Didik Baru)
-- ============================================================
CREATE TABLE pendaftar_ppdb (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(150) NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL,
    tanggal_lahir DATE,
    asal_sekolah VARCHAR(150),
    nama_wali VARCHAR(150) NOT NULL,
    phone_wali VARCHAR(20) NOT NULL,
    email_wali VARCHAR(150),
    tahun_ajaran VARCHAR(10) NOT NULL,
    status ENUM('pending','lulus','tidak_lulus','mengundurkan_diri') DEFAULT 'pending',
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 15. PRESTASI
-- ============================================================
CREATE TABLE prestasi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    santri_id BIGINT UNSIGNED NOT NULL,
    nama_lomba VARCHAR(200) NOT NULL,
    tingkat ENUM('sekolah','kabupaten','provinsi','nasional','internasional'),
    peringkat VARCHAR(50),
    tahun YEAR NOT NULL,
    keterangan TEXT,
    bukti_foto VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (santri_id) REFERENCES santri(id) ON DELETE CASCADE
);

-- ============================================================
-- 16. NOTIFIKASI
-- ============================================================
CREATE TABLE notifikasi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    judul VARCHAR(200) NOT NULL,
    pesan TEXT NOT NULL,
    tipe ENUM('hafalan','keuangan','perizinan','pengumuman','kesehatan') DEFAULT 'pengumuman',
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SEED DATA AWAL
-- ============================================================
INSERT INTO roles (id, name) VALUES
(1, 'admin'),
(2, 'musyrif'),
(3, 'wali'),
(4, 'santri');

INSERT INTO users (name, email, password, role_id, phone) VALUES
('Admin SUNTRI', 'admin@suntri.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '08100000001'),
('Ust. Abdullah', 'abdullah@suntri.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, '08100000002'),
('Ust. Mansur', 'mansur@suntri.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, '08100000003'),
('Bpk. Ridwan', 'ridwan@suntri.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, '08100000004');

INSERT INTO kelas (nama, julukan, tingkat) VALUES
('7A', 'Madinah', 7),
('7B', 'Makkah', 7),
('8A', 'Kairo', 8),
('8B', 'Baghdad', 8),
('9A', 'Istanbul', 9);

INSERT INTO halaqoh (nama, musyrif_id) VALUES
('Imam Malik', 2),
("Imam Syafi'i", 2),
('Abu Bakr', 3),
('Utsman', 3),
('Umar', 3);

INSERT INTO jenis_tagihan (nama, nominal, periode) VALUES
('SPP Bulanan', 1500000, 'bulanan'),
('Uang Makan', 850000, 'bulanan'),
('Uang Laundry', 150000, 'bulanan'),
('Dana Gedung', 5000000, 'sekali');
