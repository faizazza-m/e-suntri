-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 14 Agu 2026 pada 08.39
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suntri`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id` int(10) UNSIGNED NOT NULL,
  `judul` varchar(200) NOT NULL,
  `pengarang` varchar(150) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `isbn` varchar(30) DEFAULT NULL,
  `kategori` varchar(80) DEFAULT NULL,
  `stok` smallint(5) UNSIGNED DEFAULT 1,
  `tersedia` smallint(5) UNSIGNED DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hafalan_santri`
--

CREATE TABLE `hafalan_santri` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `juz_selesai` tinyint(3) UNSIGNED DEFAULT 0,
  `target_juz` tinyint(3) UNSIGNED DEFAULT 30,
  `status` enum('aktif','murajaah','lulus') DEFAULT 'aktif',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hafalan_santri`
--

INSERT INTO `hafalan_santri` (`id`, `santri_id`, `juz_selesai`, `target_juz`, `status`, `updated_at`) VALUES
(1, 3, 0, 30, 'aktif', '2026-08-11 22:07:07'),
(2, 4, 0, 30, 'aktif', '2026-08-11 22:07:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `halaqoh`
--

CREATE TABLE `halaqoh` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(80) NOT NULL,
  `musyrif_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `halaqoh`
--

INSERT INTO `halaqoh` (`id`, `nama`, `musyrif_id`) VALUES
(3, 'Halaqoh 1', 20),
(4, 'Halaqoh 2', 21),
(5, 'Halaqoh 3', 40);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_pelajaran`
--

CREATE TABLE `jadwal_pelajaran` (
  `id` int(10) UNSIGNED NOT NULL,
  `kelas_id` int(10) UNSIGNED NOT NULL,
  `mapel_id` int(10) UNSIGNED NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruang` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_pelajaran`
--

INSERT INTO `jadwal_pelajaran` (`id`, `kelas_id`, `mapel_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruang`) VALUES
(1, 7, 7, 'Senin', '07:15:00', '08:15:00', 'Ruang 1'),
(2, 7, 8, 'Senin', '08:15:00', '09:15:00', 'Ruang 1'),
(3, 7, 10, 'Selasa', '07:15:00', '08:15:00', 'Ruang 1'),
(4, 7, 11, 'Selasa', '08:15:00', '09:15:00', 'Ruang 1'),
(5, 7, 9, 'Rabu', '07:15:00', '08:15:00', 'Ruang 1'),
(6, 7, 12, 'Rabu', '08:15:00', '09:15:00', 'Ruang 1'),
(7, 7, 13, 'Kamis', '07:15:00', '08:15:00', 'Ruang 1'),
(8, 7, 14, 'Kamis', '08:15:00', '09:15:00', 'Ruang 1'),
(9, 7, 15, 'Jumat', '07:15:00', '08:15:00', 'Ruang 1'),
(10, 7, 16, 'Jumat', '08:15:00', '09:15:00', 'Ruang 1'),
(11, 7, 17, 'Sabtu', '07:15:00', '08:15:00', 'Ruang 1'),
(12, 8, 8, 'Senin', '07:15:00', '08:15:00', 'Ruang 2'),
(13, 8, 7, 'Senin', '08:15:00', '09:15:00', 'Ruang 2'),
(14, 8, 11, 'Selasa', '07:15:00', '08:15:00', 'Ruang 2'),
(15, 8, 10, 'Selasa', '08:15:00', '09:15:00', 'Ruang 2'),
(16, 8, 12, 'Rabu', '07:15:00', '08:15:00', 'Ruang 2'),
(17, 8, 9, 'Rabu', '08:15:00', '09:15:00', 'Ruang 2'),
(18, 8, 14, 'Kamis', '07:15:00', '08:15:00', 'Ruang 2'),
(19, 8, 13, 'Kamis', '08:15:00', '09:15:00', 'Ruang 2'),
(20, 8, 16, 'Jumat', '07:15:00', '08:15:00', 'Ruang 2'),
(21, 8, 15, 'Jumat', '08:15:00', '09:15:00', 'Ruang 2'),
(22, 8, 17, 'Sabtu', '08:15:00', '09:15:00', 'Ruang 2'),
(23, 8, 7, 'Sabtu', '07:15:00', '08:15:00', 'Ruang 2'),
(24, 7, 7, 'Sabtu', '08:15:00', '09:15:00', 'Ruang 1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_ujian`
--

CREATE TABLE `jadwal_ujian` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(150) NOT NULL,
  `tipe` enum('Ujian','Tugas') DEFAULT 'Ujian',
  `mapel_id` int(10) UNSIGNED NOT NULL,
  `kelas_id` int(10) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_tagihan`
--

CREATE TABLE `jenis_tagihan` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `periode` enum('bulanan','tahunan','sekali') DEFAULT 'bulanan',
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis_tagihan`
--

INSERT INTO `jenis_tagihan` (`id`, `nama`, `nominal`, `periode`, `keterangan`) VALUES
(1, 'SPP Bulanan', 1500000.00, 'bulanan', NULL),
(2, 'Uang Makan', 850000.00, 'bulanan', NULL),
(3, 'Uang Laundry', 150000.00, 'bulanan', NULL),
(4, 'Dana Gedung', 5000000.00, 'sekali', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar`
--

CREATE TABLE `kamar` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL,
  `kapasitas` tinyint(3) UNSIGNED DEFAULT 8,
  `gedung` varchar(50) DEFAULT NULL,
  `lantai` tinyint(3) UNSIGNED DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kehadiran`
--

CREATE TABLE `kehadiran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','sakit','izin','alpha') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `dicatat_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL,
  `julukan` varchar(50) DEFAULT NULL,
  `tingkat` varchar(50) DEFAULT NULL,
  `wali_kelas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kapasitas` int(10) UNSIGNED DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id`, `nama`, `julukan`, `tingkat`, `wali_kelas_id`, `kapasitas`) VALUES
(7, 'Mustawa Awwal', NULL, 'Tsanawiyah', NULL, 15),
(8, 'Mustawa Tsani', NULL, 'Tsanawiyah', NULL, 15);

-- --------------------------------------------------------

--
-- Struktur dari tabel `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wali_id` bigint(20) UNSIGNED NOT NULL,
  `musyrif_id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `topik` varchar(200) DEFAULT NULL,
  `status` enum('aktif','selesai') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kode` varchar(20) DEFAULT NULL,
  `guru_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id`, `nama`, `kode`, `guru_id`) VALUES
(7, 'Bahasa Inggris', 'BAH-610', 9),
(8, 'IPTEK', 'IPT-554', 10),
(9, 'Matematika', 'MAT-603', 10),
(10, 'Fiqih', 'FIQ-208', 17),
(11, 'Hadist', 'HAD-843', 12),
(12, 'Nahwu', 'NAH-135', 13),
(13, 'Bahasa Arab', 'BAH-562', 13),
(14, 'Adab & Akhlak', 'ADA-498', 18),
(15, 'Aqidah', 'AQI-409', 19),
(16, 'Tajwid', 'TAJ-871', 16),
(17, 'Matan', 'MAT-414', 16);

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_08_06_025610_create_messages_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai_akademik`
--

CREATE TABLE `nilai_akademik` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `mapel_id` int(10) UNSIGNED NOT NULL,
  `semester` tinyint(3) UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(10) NOT NULL,
  `nilai_harian` decimal(5,2) DEFAULT NULL,
  `nilai_uts` decimal(5,2) DEFAULT NULL,
  `nilai_uas` decimal(5,2) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `predikat` enum('A','B','C','D','E') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nilai_akademik`
--

INSERT INTO `nilai_akademik` (`id`, `santri_id`, `mapel_id`, `semester`, `tahun_ajaran`, `nilai_harian`, `nilai_uts`, `nilai_uas`, `nilai_akhir`, `predikat`, `created_at`) VALUES
(1, 3, 8, 1, '2026/2027', NULL, NULL, NULL, 0.00, 'E', '2026-08-06 02:37:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(200) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` enum('hafalan','keuangan','perizinan','pengumuman','kesehatan') DEFAULT 'pengumuman',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tagihan_id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_bayar` datetime NOT NULL,
  `nominal_bayar` decimal(12,2) NOT NULL,
  `metode` enum('tunai','transfer','qris') DEFAULT 'tunai',
  `bukti_foto` varchar(255) DEFAULT NULL,
  `no_invoice` varchar(50) DEFAULT NULL,
  `dikonfirmasi_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman_buku`
--

CREATE TABLE `peminjaman_buku` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `buku_id` int(10) UNSIGNED NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali_rencana` date NOT NULL,
  `tanggal_kembali_aktual` date DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan','terlambat') DEFAULT 'dipinjam',
  `denda` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftar_ppdb`
--

CREATE TABLE `pendaftar_ppdb` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `asal_sekolah` varchar(150) DEFAULT NULL,
  `nama_wali` varchar(150) NOT NULL,
  `phone_wali` varchar(20) NOT NULL,
  `email_wali` varchar(150) DEFAULT NULL,
  `tahun_ajaran` varchar(10) NOT NULL,
  `status` enum('pending','lulus','tidak_lulus','mengundurkan_diri') DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penghuni_kamar`
--

CREATE TABLE `penghuni_kamar` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `kamar_id` int(10) UNSIGNED NOT NULL,
  `tanggal_masuk` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(200) NOT NULL,
  `isi` text NOT NULL,
  `target` enum('semua','wali','santri','musyrif') DEFAULT 'semua',
  `dibuat_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `is_pinned` tinyint(1) DEFAULT 0,
  `published_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `perizinan`
--

CREATE TABLE `perizinan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `jenis` enum('pulang','sakit','kegiatan_luar','lainnya') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `alasan` text NOT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `disetujui_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan_konsultasi`
--

CREATE TABLE `pesan_konsultasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `konsultasi_id` bigint(20) UNSIGNED NOT NULL,
  `pengirim_id` bigint(20) UNSIGNED NOT NULL,
  `isi` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `prestasi`
--

CREATE TABLE `prestasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `nama_lomba` varchar(200) NOT NULL,
  `tingkat` enum('sekolah','kabupaten','provinsi','nasional','internasional') DEFAULT NULL,
  `peringkat` varchar(50) DEFAULT NULL,
  `tahun` year(4) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `bukti_foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekam_kesehatan`
--

CREATE TABLE `rekam_kesehatan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `keluhan` text DEFAULT NULL,
  `diagnosa` text DEFAULT NULL,
  `tindakan` text DEFAULT NULL,
  `petugas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dirujuk` tinyint(1) DEFAULT 0,
  `tempat_rujukan` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(6, 'mudir'),
(2, 'musyrif'),
(4, 'santri'),
(5, 'ustadz'),
(3, 'wali');

-- --------------------------------------------------------

--
-- Struktur dari tabel `santri`
--

CREATE TABLE `santri` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nis` varchar(20) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `asal_sekolah` varchar(150) DEFAULT NULL,
  `kelas_id` int(10) UNSIGNED DEFAULT NULL,
  `halaqoh_id` int(10) UNSIGNED DEFAULT NULL,
  `tahun_masuk` year(4) DEFAULT NULL,
  `status` enum('aktif','alumni','keluar') DEFAULT 'aktif',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `santri`
--

INSERT INTO `santri` (`id`, `user_id`, `nis`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `asal_sekolah`, `kelas_id`, `halaqoh_id`, `tahun_masuk`, `status`, `foto`, `created_at`) VALUES
(2, NULL, '3118507037', 'Abdurrahman Al-Bakasy', 'L', NULL, NULL, 8, 4, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(3, NULL, 'TBD-002', 'Alkhalifi Zavier Mikhail', 'L', NULL, NULL, 7, 3, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(4, NULL, '3115031717', 'Arkaan Musliim Icti', 'L', NULL, NULL, 8, 3, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(5, NULL, '0118538101', 'Dafa Bunyanudin', 'L', NULL, NULL, 8, 3, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(6, NULL, '3122708812', 'Hudzaifah Adzka Hidayat', 'L', NULL, NULL, 8, 4, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(7, NULL, '0123452346', 'M. Adhyasta Abd Jabbar', 'L', NULL, NULL, 8, 4, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(8, NULL, '0117598383', 'Muhammad Azam', 'L', NULL, NULL, 8, 4, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(9, NULL, '3121410245', 'Muhammad Azzam Alkhaf Akbar', 'L', NULL, NULL, 7, 4, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(10, NULL, '0126405290', 'Muhammad Bilal', 'L', NULL, NULL, 8, 4, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(11, NULL, '3121924981', 'Muhammad Fadhil Abdul Malik', 'L', NULL, NULL, 8, 4, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(12, NULL, '0109356646', 'Ibnu Rusdi Ademar', 'L', NULL, NULL, 8, 3, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(13, NULL, '3125346188', 'Muhammad Ghanim', 'L', NULL, NULL, 7, 3, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(14, NULL, '3129355873', 'Muhammad Khalifah Akhyar', 'L', NULL, NULL, 7, 3, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(15, NULL, '3149087515', 'Muhammad Zauzan Uruban', 'L', NULL, NULL, 7, 4, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(16, NULL, '0133418700', 'Nawaf', 'L', NULL, NULL, 7, 4, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(17, NULL, 'TBD-016', 'Romulus Askha Juna Budiharto', 'L', NULL, NULL, 7, 3, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(18, NULL, 'TBD-017', 'Bilal A', 'L', NULL, NULL, 7, 3, NULL, 'aktif', NULL, '2026-08-05 23:55:39'),
(19, NULL, 'TBD-018', 'Muhammad Ardi Heriawan', 'L', NULL, NULL, 8, 3, NULL, 'aktif', NULL, '2026-08-05 23:55:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setoran`
--

CREATE TABLE `setoran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `musyrif_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('hafalan_baru','murajaah','tasmi') NOT NULL,
  `surah` varchar(80) DEFAULT NULL,
  `juz` tinyint(3) UNSIGNED DEFAULT NULL,
  `ayat_dari` smallint(5) UNSIGNED DEFAULT NULL,
  `ayat_sampai` smallint(5) UNSIGNED DEFAULT NULL,
  `nilai` enum('Mumtaz','Jayyid Jiddan','Jayyid','Maqbul','Rosib') DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `setoran`
--

INSERT INTO `setoran` (`id`, `santri_id`, `musyrif_id`, `tanggal`, `jenis`, `surah`, `juz`, `ayat_dari`, `ayat_sampai`, `nilai`, `catatan`, `created_at`) VALUES
(1, 3, 20, '2026-08-07', 'hafalan_baru', '2', 1, 1, 16, 'Mumtaz', NULL, '2026-08-07 04:43:42'),
(2, 4, 20, '2026-08-07', 'hafalan_baru', '17', 15, 1, 23, 'Mumtaz', NULL, '2026-08-07 06:16:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tagihan`
--

CREATE TABLE `tagihan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_id` int(10) UNSIGNED NOT NULL,
  `bulan` tinyint(3) UNSIGNED DEFAULT NULL,
  `tahun` year(4) NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `status` enum('belum','lunas','terlambat') DEFAULT 'belum',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role_id` tinyint(3) UNSIGNED NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `role_id`, `phone`, `foto`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'faiz', 'admin@suntri.id', '$2y$12$g.6fTZu.YthJ9CmL1RK89uya.oyZiED8wi/bA9UbBdKEnnj8loQti', NULL, 1, '08100000001', NULL, 1, '2026-08-04 01:52:09', '2026-08-03 19:31:27'),
(9, 'Miss Evi', 'missevi@suntri.com', '$2y$12$MkQpJWO3HiKw7BdwO/jQn.P25HaJyNrmPnx8hFs7QX5kgWGT1I6py', NULL, 5, NULL, NULL, 1, '2026-08-05 23:30:05', '2026-08-05 23:30:05'),
(10, 'Ustadz Faiz', 'ustadzfaiz@suntri.com', '$2y$12$P4ABDsmjhdUq87fAp9VipO4VvoSXXdkcjCLS9GxpJ3YrL3Co9i2kS', NULL, 5, NULL, NULL, 1, '2026-08-05 23:30:05', '2026-08-05 23:30:05'),
(11, 'Ustadz Sam Sam S.T.', 'ustadzsamsamst@suntri.com', '$2y$12$TvzD.H4FqlxeEardgBDXSu13QXjwu2azTzbaM0qqpVuY.qc231NBS', NULL, 5, NULL, NULL, 1, '2026-08-05 23:30:06', '2026-08-05 23:30:06'),
(12, 'Ustadz Adnan Lc', 'ustadzadnanlc@suntri.com', '$2y$12$A3PTiRnzVyA7UTX849Pey.zcPGU1AaYD.yrwsW4yjCYd80bzjNYXG', NULL, 5, NULL, NULL, 1, '2026-08-05 23:30:06', '2026-08-05 23:30:06'),
(13, 'Ustadz Yaman', 'ustadzyaman@suntri.com', '$2y$12$OiO5eHbnPwVQpVey3.wn9.kuMXJBwHQOmAmxd4JR41BltV/C46i5u', NULL, 5, NULL, NULL, 1, '2026-08-05 23:30:07', '2026-08-05 23:30:07'),
(14, 'Ustadz Dahlan, Lc', 'ustdahlanlc@suntri.com', '$2y$12$myq1iWZUZzrmzCz.vmakz.Q9GyFiQa4yvdoi3q7gxiDu3VkKVwG3O', NULL, 5, NULL, NULL, 1, '2026-08-05 23:30:07', '2026-08-05 23:33:38'),
(15, 'Ustadz Andi, Lc', 'ustandilc@suntri.com', '$2y$12$mRdzACn.TiNOQzmmZn99k.vIFDsQ3Fjr3whbj9SW71UM0ycvx.jlq', NULL, 5, NULL, NULL, 1, '2026-08-05 23:30:08', '2026-08-05 23:33:49'),
(16, 'Ust Dr Ilham Waliyudin, M.A, M.Pd', 'ustdrilhamwaliyudinmampd@suntri.com', '$2y$12$0wLXUnO11/cLcKTlliGUB.Kmi.ZqsmGQj4EiPvedB15.PezOitK1G', NULL, 5, NULL, NULL, 1, '2026-08-05 23:30:09', '2026-08-05 23:30:09'),
(17, 'Ustadz Sam Sam, Lc', 'ustadzsamsamlc@example.com', '$2y$12$m7.1lEPuoUoixHWjc23EH.1RCXiEbrJuZblEL/lhHDrIrQQkHg6qS', NULL, 5, NULL, NULL, 1, '2026-08-06 00:10:11', '2026-08-06 00:10:11'),
(18, 'Ust Dahlan, Lc', 'ustdahlanlc@example.com', '$2y$12$0zlJ3n8NdVH8K5npMP3Ncu.NCBjY9DLB3fVy46sB0IX/iz4o8cDke', NULL, 5, NULL, NULL, 1, '2026-08-06 00:10:13', '2026-08-06 00:10:13'),
(19, 'Ust Andi, Lc', 'ustandilc@example.com', '$2y$12$zw5Hc0QwC6Ygkck2pEPEturXfdCWdV4fEMI77rLe4gy5al21sdfc6', NULL, 5, NULL, NULL, 1, '2026-08-06 00:10:13', '2026-08-06 00:10:13'),
(20, 'Mahar', 'musyrif@gmail.com', '$2y$12$LTuFNB8lIDaprAvQraUbQ.YHzIkZEbGHcxCkWzuWsVqlv82rb9gX.', NULL, 2, '082335579287', NULL, 1, '2026-08-06 00:12:40', '2026-08-07 04:28:53'),
(21, 'Izrail', 'izrail@suntri.com', '$2y$12$1oj0kbFMswrGoiguc3lOYutBR794SxBa//OY.BkqRUWjsOnKHuB26', NULL, 2, NULL, NULL, 1, '2026-08-06 00:13:13', '2026-08-06 00:13:13'),
(22, 'Wali Abdurrahman Al-Bakasy', 'wali.3118507037@example.com', '$2y$12$0mg3iNUcwg9N2ldkw3FLaesagw/puzWHexo6NICtUy56cyO5XSvWy', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:42', '2026-08-06 02:53:42'),
(23, 'Wali Alkhalifi Zavier Mikhail', 'wali.TBD-002@example.com', '$2y$12$TG0TfzjmTyxiAH529/FlPerUJUq.3s7g9VqeVz45FQyXWcvqTtlSy', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:42', '2026-08-06 02:53:42'),
(24, 'Wali Arkaan Musliim Icti', 'wali.3115031717@example.com', '$2y$12$tfstvzFDjhW7vohltzErzuGGzdRRdIordW/SRRyfjJujr9BUk03e6', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:43', '2026-08-06 02:53:43'),
(25, 'Wali Dafa Bunyanudin', 'wali.0118538101@example.com', '$2y$12$X3W71SOUdMeLMk0NJyks6eDl3h3d6hgA70ilm1a/8OmxEEtDJfuZa', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:43', '2026-08-06 02:53:43'),
(26, 'Wali Hudzaifah Adzka Hidayat', 'wali.3122708812@example.com', '$2y$12$i0KGPfRkjYP.XlfI1vxm9ungeC1pZyIdeJG1wDYI2e8iinAUY8rdy', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:43', '2026-08-06 02:53:43'),
(27, 'Wali M. Adhyasta Abd Jabbar', 'wali.0123452346@example.com', '$2y$12$amVk3jF4kT/67h481Zcg6.2Rlw0lyAAc6jLWiiufl3udt0uLHx/4O', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:44', '2026-08-06 02:53:44'),
(28, 'Wali Muhammad Azam', 'wali.0117598383@example.com', '$2y$12$dtE7k5OIqO2XVMpxPXiBN.O0MhuxK1r8Xv90.Mi7FLa7CEsSU8VBS', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:44', '2026-08-06 02:53:44'),
(29, 'Wali Muhammad Azzam Alkhaf Akbar', 'wali.3121410245@example.com', '$2y$12$350pINkvYMvJgVHmW7zFJ.ym7ZRD2rYNYutdxRXMfFdSXQV.nJJTy', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:44', '2026-08-06 02:53:44'),
(30, 'Wali Muhammad Bilal', 'wali.0126405290@example.com', '$2y$12$uWeIIy7T6s7uEdOT5igvmOKAjCmVtqpqnc1kcEkI0m2JBRoDMQ79a', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:45', '2026-08-06 02:53:45'),
(31, 'Wali Muhammad Fadhil Abdul Malik', 'wali.3121924981@example.com', '$2y$12$/p4Vfb6Mc4Q955W1cLZOIOgcxCCm1nJpCwx18EtrVpYGsawraH1z2', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:45', '2026-08-06 02:53:45'),
(32, 'Wali Ibnu Rusdi Ademar', 'wali.0109356646@example.com', '$2y$12$unAZ5LrRCd9lQ3fb2rHCQOrLP7SUdNcJKwbMQ2mQSLxj9tDn/70Sq', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:45', '2026-08-06 02:53:45'),
(33, 'Wali Muhammad Ghanim', 'wali.3125346188@example.com', '$2y$12$pqbCNHaXeCnOdLASvki3tuUkt/N7kJNvw.GxZzi3g8AhUqYC0mCRK', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:46', '2026-08-06 02:53:46'),
(34, 'Wali Muhammad Khalifah Akhyar', 'wali.3129355873@example.com', '$2y$12$AGZngWFq1OYmEPeC5tVb5.TUROIVYJVI8nQWKkNvHOZVYYNbOzFB.', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:46', '2026-08-06 02:53:46'),
(35, 'Wali Muhammad Zauzan Uruban', 'wali.3149087515@example.com', '$2y$12$6XWMnvmEBM412fRT/WPzYe7NNl420zl7rA36yymerRoGrdSXfaAri', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:47', '2026-08-06 02:53:47'),
(36, 'Wali Nawaf', 'wali.0133418700@example.com', '$2y$12$EEKUe.a7Sh2qgB0eGD9RX.9vovmEkZJTXJ1KyALr/CHoZ8KOQ2AYK', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:47', '2026-08-06 02:53:47'),
(37, 'Wali Romulus Askha Juna Budiharto', 'wali.TBD-016@example.com', '$2y$12$NU6xYzGCJYNeSJxUV.tKAuJqE08wEojHOB.87SYYDsvQeylz0XZhK', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:47', '2026-08-06 02:53:47'),
(38, 'Wali Bilal A', 'wali.TBD-017@example.com', '$2y$12$juLDmMjiEpS/82fhHbOQkekAbvc286wa3ETEINh14Kuh89sJk3gjy', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:48', '2026-08-06 02:53:48'),
(39, 'Wali Muhammad Ardi Heriawan', 'wali.TBD-018@example.com', '$2y$12$JnGVSTyxKpWYFgQcli/0U.yyoWXl/T4zcCj7YQhWfyctLfq1r2gqC', NULL, 3, NULL, NULL, 1, '2026-08-06 02:53:48', '2026-08-06 02:53:48'),
(40, 'Kohar', 'kohar@gmail.com', '$2y$12$sM7uXWtMm3hRlzjeM.rTpuAIExfmkPP.239nEjluoVr5rOo8c60Gy', NULL, 2, NULL, NULL, 1, '2026-08-11 22:06:38', '2026-08-11 22:06:38'),
(41, 'Dr. Ilham Waliyudin M.A, M.Pd', 'mahadtahfidzrijaalulq@gmail.com', '$2y$12$4i8p.nzDZFcrKwYSFNzBTObEx32mcET/h81cM9arnwcQvH4lmVywq', NULL, 6, '081549626787', NULL, 1, '2026-08-13 19:01:44', '2026-08-13 19:18:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wali_santri`
--

CREATE TABLE `wali_santri` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `santri_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(150) NOT NULL,
  `hubungan` enum('ayah','ibu','wali') DEFAULT 'ayah',
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `wali_santri`
--

INSERT INTO `wali_santri` (`id`, `user_id`, `santri_id`, `nama`, `hubungan`, `phone`, `email`, `alamat`, `pekerjaan`) VALUES
(1, 22, 2, 'Bapak/Ibu Abdurrahman Al-Bakasy', 'ayah', '081234567890', NULL, NULL, NULL),
(2, 23, 3, 'Bapak/Ibu Alkhalifi Zavier Mikhail', 'ayah', '081234567890', NULL, NULL, NULL),
(3, 24, 4, 'Bapak/Ibu Arkaan Musliim Icti', 'ayah', '081234567890', NULL, NULL, NULL),
(4, 25, 5, 'Bapak/Ibu Dafa Bunyanudin', 'ayah', '081234567890', NULL, NULL, NULL),
(5, 26, 6, 'Bapak/Ibu Hudzaifah Adzka Hidayat', 'ayah', '081234567890', NULL, NULL, NULL),
(6, 27, 7, 'Bapak/Ibu M. Adhyasta Abd Jabbar', 'ayah', '081234567890', NULL, NULL, NULL),
(7, 28, 8, 'Bapak/Ibu Muhammad Azam', 'ayah', '081234567890', NULL, NULL, NULL),
(8, 29, 9, 'Bapak/Ibu Muhammad Azzam Alkhaf Akbar', 'ayah', '081234567890', NULL, NULL, NULL),
(9, 30, 10, 'Bapak/Ibu Muhammad Bilal', 'ayah', '081234567890', NULL, NULL, NULL),
(10, 31, 11, 'Bapak/Ibu Muhammad Fadhil Abdul Malik', 'ayah', '081234567890', NULL, NULL, NULL),
(11, 32, 12, 'Bapak/Ibu Ibnu Rusdi Ademar', 'ayah', '081234567890', NULL, NULL, NULL),
(12, 33, 13, 'Bapak/Ibu Muhammad Ghanim', 'ayah', '081234567890', NULL, NULL, NULL),
(13, 34, 14, 'Bapak/Ibu Muhammad Khalifah Akhyar', 'ayah', '081234567890', NULL, NULL, NULL),
(14, 35, 15, 'Bapak/Ibu Muhammad Zauzan Uruban', 'ayah', '081234567890', NULL, NULL, NULL),
(15, 36, 16, 'Bapak/Ibu Nawaf', 'ayah', '081234567890', NULL, NULL, NULL),
(16, 37, 17, 'Bapak/Ibu Romulus Askha Juna Budiharto', 'ayah', '081234567890', NULL, NULL, NULL),
(17, 38, 18, 'Bapak/Ibu Bilal A', 'ayah', '081234567890', NULL, NULL, NULL),
(18, 39, 19, 'Bapak/Ibu Muhammad Ardi Heriawan', 'ayah', '081234567890', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indeks untuk tabel `hafalan_santri`
--
ALTER TABLE `hafalan_santri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`);

--
-- Indeks untuk tabel `halaqoh`
--
ALTER TABLE `halaqoh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `musyrif_id` (`musyrif_id`);

--
-- Indeks untuk tabel `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `mapel_id` (`mapel_id`);

--
-- Indeks untuk tabel `jadwal_ujian`
--
ALTER TABLE `jadwal_ujian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mapel_id` (`mapel_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `jenis_tagihan`
--
ALTER TABLE `jenis_tagihan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kehadiran`
--
ALTER TABLE `kehadiran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_kehadiran` (`santri_id`,`tanggal`),
  ADD KEY `dicatat_oleh` (`dicatat_oleh`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wali_kelas_id` (`wali_kelas_id`);

--
-- Indeks untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wali_id` (`wali_id`),
  ADD KEY `musyrif_id` (`musyrif_id`),
  ADD KEY `santri_id` (`santri_id`);

--
-- Indeks untuk tabel `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- Indeks untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `nilai_akademik`
--
ALTER TABLE `nilai_akademik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`),
  ADD KEY `mapel_id` (`mapel_id`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_invoice` (`no_invoice`),
  ADD KEY `tagihan_id` (`tagihan_id`),
  ADD KEY `santri_id` (`santri_id`),
  ADD KEY `dikonfirmasi_oleh` (`dikonfirmasi_oleh`);

--
-- Indeks untuk tabel `peminjaman_buku`
--
ALTER TABLE `peminjaman_buku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indeks untuk tabel `pendaftar_ppdb`
--
ALTER TABLE `pendaftar_ppdb`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penghuni_kamar`
--
ALTER TABLE `penghuni_kamar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `santri_id` (`santri_id`),
  ADD KEY `kamar_id` (`kamar_id`);

--
-- Indeks untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dibuat_oleh` (`dibuat_oleh`);

--
-- Indeks untuk tabel `perizinan`
--
ALTER TABLE `perizinan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`),
  ADD KEY `disetujui_oleh` (`disetujui_oleh`);

--
-- Indeks untuk tabel `pesan_konsultasi`
--
ALTER TABLE `pesan_konsultasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `konsultasi_id` (`konsultasi_id`),
  ADD KEY `pengirim_id` (`pengirim_id`);

--
-- Indeks untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`);

--
-- Indeks untuk tabel `rekam_kesehatan`
--
ALTER TABLE `rekam_kesehatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`),
  ADD KEY `petugas_id` (`petugas_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `santri`
--
ALTER TABLE `santri`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `halaqoh_id` (`halaqoh_id`);

--
-- Indeks untuk tabel `setoran`
--
ALTER TABLE `setoran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`),
  ADD KEY `musyrif_id` (`musyrif_id`);

--
-- Indeks untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `santri_id` (`santri_id`),
  ADD KEY `jenis_id` (`jenis_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indeks untuk tabel `wali_santri`
--
ALTER TABLE `wali_santri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `santri_id` (`santri_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hafalan_santri`
--
ALTER TABLE `hafalan_santri`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `halaqoh`
--
ALTER TABLE `halaqoh`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `jadwal_ujian`
--
ALTER TABLE `jadwal_ujian`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jenis_tagihan`
--
ALTER TABLE `jenis_tagihan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kehadiran`
--
ALTER TABLE `kehadiran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `nilai_akademik`
--
ALTER TABLE `nilai_akademik`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `peminjaman_buku`
--
ALTER TABLE `peminjaman_buku`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pendaftar_ppdb`
--
ALTER TABLE `pendaftar_ppdb`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penghuni_kamar`
--
ALTER TABLE `penghuni_kamar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `perizinan`
--
ALTER TABLE `perizinan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pesan_konsultasi`
--
ALTER TABLE `pesan_konsultasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rekam_kesehatan`
--
ALTER TABLE `rekam_kesehatan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `santri`
--
ALTER TABLE `santri`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `setoran`
--
ALTER TABLE `setoran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT untuk tabel `wali_santri`
--
ALTER TABLE `wali_santri`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hafalan_santri`
--
ALTER TABLE `hafalan_santri`
  ADD CONSTRAINT `hafalan_santri_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `halaqoh`
--
ALTER TABLE `halaqoh`
  ADD CONSTRAINT `halaqoh_ibfk_1` FOREIGN KEY (`musyrif_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `jadwal_pelajaran`
--
ALTER TABLE `jadwal_pelajaran`
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_ujian`
--
ALTER TABLE `jadwal_ujian`
  ADD CONSTRAINT `jadwal_ujian_ibfk_1` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_ujian_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kehadiran`
--
ALTER TABLE `kehadiran`
  ADD CONSTRAINT `kehadiran_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kehadiran_ibfk_2` FOREIGN KEY (`dicatat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`wali_kelas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD CONSTRAINT `konsultasi_ibfk_1` FOREIGN KEY (`wali_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `konsultasi_ibfk_2` FOREIGN KEY (`musyrif_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `konsultasi_ibfk_3` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD CONSTRAINT `mata_pelajaran_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nilai_akademik`
--
ALTER TABLE `nilai_akademik`
  ADD CONSTRAINT `nilai_akademik_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_akademik_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`tagihan_id`) REFERENCES `tagihan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_ibfk_3` FOREIGN KEY (`dikonfirmasi_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `peminjaman_buku`
--
ALTER TABLE `peminjaman_buku`
  ADD CONSTRAINT `peminjaman_buku_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_buku_ibfk_2` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penghuni_kamar`
--
ALTER TABLE `penghuni_kamar`
  ADD CONSTRAINT `penghuni_kamar_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penghuni_kamar_ibfk_2` FOREIGN KEY (`kamar_id`) REFERENCES `kamar` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `perizinan`
--
ALTER TABLE `perizinan`
  ADD CONSTRAINT `perizinan_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `perizinan_ibfk_2` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `pesan_konsultasi`
--
ALTER TABLE `pesan_konsultasi`
  ADD CONSTRAINT `pesan_konsultasi_ibfk_1` FOREIGN KEY (`konsultasi_id`) REFERENCES `konsultasi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesan_konsultasi_ibfk_2` FOREIGN KEY (`pengirim_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `prestasi`
--
ALTER TABLE `prestasi`
  ADD CONSTRAINT `prestasi_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rekam_kesehatan`
--
ALTER TABLE `rekam_kesehatan`
  ADD CONSTRAINT `rekam_kesehatan_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rekam_kesehatan_ibfk_2` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `santri`
--
ALTER TABLE `santri`
  ADD CONSTRAINT `santri_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `santri_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `santri_ibfk_3` FOREIGN KEY (`halaqoh_id`) REFERENCES `halaqoh` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `setoran`
--
ALTER TABLE `setoran`
  ADD CONSTRAINT `setoran_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `setoran_ibfk_2` FOREIGN KEY (`musyrif_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  ADD CONSTRAINT `tagihan_ibfk_1` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tagihan_ibfk_2` FOREIGN KEY (`jenis_id`) REFERENCES `jenis_tagihan` (`id`);

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Ketidakleluasaan untuk tabel `wali_santri`
--
ALTER TABLE `wali_santri`
  ADD CONSTRAINT `wali_santri_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `wali_santri_ibfk_2` FOREIGN KEY (`santri_id`) REFERENCES `santri` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;