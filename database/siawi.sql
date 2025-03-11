-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 17, 2025 at 12:55 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siawi`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` bigint UNSIGNED NOT NULL,
  `id_siswa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kehadiran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_siswa`, `id_kelas`, `id_jurusan`, `hari`, `tanggal`, `kehadiran`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, '1', '1', '1', 'Jumat', '2025-01-17', 'hadir', '-', '2025-01-17 00:54:12', '2025-01-17 00:54:12'),
(2, '2', '1', '2', 'Jumat', '2025-01-17', 'hadir', '-', '2025-01-17 00:54:12', '2025-01-17 00:54:12');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id_berita` bigint UNSIGNED NOT NULL,
  `judul_berita` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi_berita` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pembuat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id_berita`, `judul_berita`, `isi_berita`, `pembuat`, `tanggal`, `cover`, `created_at`, `updated_at`) VALUES
(3, 'Kerja sama smk wisata indonesia', 'kerja sama antara smk wisata indonesia dengan beberapa hotel untuk meningkatkan kemampuan pembelajaran pada smk wisata indonesia untuk membekali siswa/i smk wisata indonesia untuk dapat bersaing di dunia industri. kerja sama ini merupakan penerapan dari visi/misi smk wisata indonesia.', '4', '22 April 2024 10:57', 'MPB (3).jpg', '2024-04-21 20:57:53', '2024-04-21 22:13:14'),
(4, 'Uji Seritifkasi Kompetensi', 'Uji Seritifkasi Kompetensi', '1', '24 April 2024 20:59', 'MPB10 (23).jpg', '2024-04-24 06:59:43', '2024-04-24 06:59:43');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_guru` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id_guru`, `username`, `password`, `nama_guru`, `role`, `created_at`, `updated_at`) VALUES
(1, 'deyarcipta', '$2y$12$92IEOxwBGV0QJx.Z94E6Iu8jROzOnwVoOpZYM0xVB8vVkY1gn6AhG', 'Deyar Cipta Rizky', 'admin', '2024-04-14 00:22:41', '2024-04-14 00:22:41'),
(3, 'ilham', '$2y$12$g/4FCN9uJ6/b4r/L.jVwxOse4Km8fo0hRUY/YX7XjhMsLoMtXRKU.', 'ilham', 'kesiswaan', '2024-04-14 00:39:20', '2024-04-14 00:39:20'),
(4, 'ardi', '$2y$12$UF7dmBscAKr8gsSrjA3ol.mVuQENg9izZc9IrbtfVONVtAc8U/hEi', 'Ardian Rizky Julkafonza', 'guru', '2024-04-14 00:39:32', '2024-08-12 22:30:06'),
(5, 'nadiah123', '$2y$12$ztePGOweLyOKgIF2jQhPduvKOzLrdNXeQNVTT6LMQEQLdfyBGcQF2', 'nadiah', 'kurikulum', '2024-04-14 02:18:07', '2024-04-14 02:25:04'),
(6, 'imax', '$2y$12$RBcFB6XWosfBUIr7QASU3.AKdkIBtQ941qrBgDGRkApGS7ALFddjW', 'Ilham Muhammad', 'guru', '2024-08-13 00:29:56', '2024-08-13 01:38:04');

-- --------------------------------------------------------

--
-- Table structure for table `informasi`
--

CREATE TABLE `informasi` (
  `id` bigint UNSIGNED NOT NULL,
  `informasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ket_informasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_awal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_akhir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `informasi`
--

INSERT INTO `informasi` (`id`, `informasi`, `ket_informasi`, `tanggal_awal`, `tanggal_akhir`, `file`, `created_at`, `updated_at`) VALUES
(1, 'Assesmen Sumatif Sekolah', 'Pelaksanaan Kegiatan Assesmen Sumatif Sekolah', '2024-04-22', '2024-04-22', 'FERNANDUS SMART PAKET.pdf', '2024-04-18 04:40:29', '2024-04-18 04:53:13'),
(2, 'Pembagian Rapot', 'Pembagian Rapot Semester Ganjil', '2024-04-29', '2024-04-29', 'QS-JK-2024-04-8532 INNOVA ZENIX MARIA TOY BBR.pdf', '2024-04-24 21:43:34', '2024-04-24 21:43:34');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_mapel`
--

CREATE TABLE `jadwal_mapel` (
  `id_jadwal` bigint UNSIGNED NOT NULL,
  `id_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_guru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_awal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_akhir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_awal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_akhir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_mapel`
--

INSERT INTO `jadwal_mapel` (`id_jadwal`, `id_mapel`, `id_guru`, `hari`, `kelas`, `jam_awal`, `jam_akhir`, `waktu_awal`, `waktu_akhir`, `created_at`, `updated_at`) VALUES
(4, '4', '1', 'senin', 'X-TJKT', '1', '7', '06:30', '11:45', '2024-04-17 22:36:18', '2024-04-17 22:36:27'),
(5, '3', '5', 'jumat', 'X-KUL-A', '8', '10', '12:30', '14:30', '2024-04-17 23:24:32', '2024-04-29 01:27:29'),
(6, '2', '4', 'kamis', 'X-TJKT', '1', '4', '06:30', '09:30', '2024-04-17 23:25:12', '2024-04-24 20:46:04'),
(7, '1', '1', 'kamis', 'X-TJKT', '5', '7', '10:00', '11:30', '2024-04-17 23:25:46', '2024-04-24 20:45:54'),
(8, '4', '5', 'kamis', 'X-TJKT', '8', '10', '12:30', '14:31', '2024-04-17 23:26:16', '2024-04-24 20:13:36'),
(9, '4', '5', 'selasa', 'X-TJKT', '4', '7', '15:26', '15:27', '2024-04-29 01:26:55', '2024-04-29 01:26:55'),
(10, '3', '4', 'rabu', 'X-TJKT', '6', '6', '15:27', '20:32', '2024-04-29 01:27:19', '2024-04-29 01:27:19');

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` bigint UNSIGNED NOT NULL,
  `kode_jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `kode_jurusan`, `nama_jurusan`, `created_at`, `updated_at`) VALUES
(1, 'TJKT', 'Teknik Jaringan Komputer dan Telekomunikasi', '2024-04-14 02:48:46', '2024-04-14 02:48:46'),
(2, 'PH', 'Perhotelan', '2024-04-14 02:48:57', '2024-04-14 02:48:57'),
(5, 'KUL', 'Kuliner', '2024-04-22 21:22:03', '2024-04-22 21:22:03');

-- --------------------------------------------------------

--
-- Table structure for table `kalender`
--

CREATE TABLE `kalender` (
  `id` bigint UNSIGNED NOT NULL,
  `kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_mulai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_akhir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kalender`
--

INSERT INTO `kalender` (`id`, `kegiatan`, `tgl_mulai`, `tgl_akhir`, `created_at`, `updated_at`) VALUES
(1, 'Asesmen Sumatif Sekolah', '2024-04-29', '2024-05-03', '2024-04-18 05:09:51', '2024-04-18 05:09:51'),
(2, 'Uji Sertifikasi Kompetensi PH', '2024-05-06', '2024-05-07', '2024-04-18 06:43:46', '2024-04-18 06:43:52');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` bigint UNSIGNED NOT NULL,
  `kode_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `kode_kelas`, `nama_kelas`, `kode_level`, `kode_jurusan`, `created_at`, `updated_at`) VALUES
(1, 'X-TJKT', 'X-TJKT', 'X', 'TJKT', '2024-04-14 23:24:35', '2024-04-14 23:24:35'),
(2, 'X-KUL-A', 'X-KUL-A', 'X', 'KUL', '2024-04-14 23:25:38', '2024-04-14 23:25:38'),
(3, 'XI-PH-A', 'XI-PH-A', 'XI', 'PH', '2024-04-14 23:25:51', '2024-04-14 23:25:51');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `id_level` bigint UNSIGNED NOT NULL,
  `kode_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id_level`, `kode_level`, `nama_level`, `created_at`, `updated_at`) VALUES
(1, 'X', 'X', '2024-04-14 22:56:18', '2024-04-14 22:56:18'),
(2, 'XI', 'XI', '2024-04-14 22:56:27', '2024-04-14 22:56:27'),
(3, 'XII', 'XII', '2024-04-14 22:56:37', '2024-04-14 22:56:37');

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` bigint UNSIGNED NOT NULL,
  `kode_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `kode_mapel`, `nama_mapel`, `created_at`, `updated_at`) VALUES
(1, 'BINDO', 'Bahasa Indonesia', '2024-04-17 20:57:07', '2024-04-17 20:57:07'),
(2, 'MTK', 'Matematika', '2024-04-17 21:12:07', '2024-04-17 21:12:07'),
(3, 'BING', 'Bahasa Inggris', '2024-04-17 21:12:20', '2024-04-17 21:12:20'),
(4, 'AIJ', 'Administrasi Infrastruktur Jaringan', '2024-04-17 21:12:45', '2024-04-17 21:12:45');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_04_14_054638_create_siswa_table', 1),
(6, '2024_04_14_054659_create_kelas_table', 1),
(7, '2024_04_14_054703_create_jurusan_table', 1),
(8, '2024_04_14_054708_create_level_table', 1),
(9, '2024_04_14_055517_create_guru_table', 1),
(10, '2024_04_14_054638_just_create_siswa_table', 2),
(11, '2024_04_17_111622_create_jadwal_mapel_table', 3),
(12, '2024_04_17_111622_new_create_jadwal_mapel_table', 4),
(13, '2024_04_18_034409_create_mapel', 5),
(14, '2024_04_18_062949_create_setting_table', 6),
(15, '2024_04_18_112629_create_informasi_table', 7),
(16, '2024_04_18_115542_create_kalender_table', 8),
(17, '2024_04_19_070201_create_absensi_table', 9),
(18, '2024_04_20_130159_create_rapot_table', 10),
(19, '2024_04_20_130555_create_tagihan_table', 11),
(20, '2024_04_20_141908_create_point_table', 12),
(21, '2024_04_20_141918_create_point_siswa_table', 12),
(22, '2024_04_22_024013_create_berita_table', 13),
(23, '2024_04_22_064044_create_modul_table', 14),
(24, '2024_04_22_095429_create_rapot_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `modul`
--

CREATE TABLE `modul` (
  `id_modul` bigint UNSIGNED NOT NULL,
  `id_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_guru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_modul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_modul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modul`
--

INSERT INTO `modul` (`id_modul`, `id_mapel`, `id_guru`, `id_level`, `id_jurusan`, `nama_modul`, `file_modul`, `created_at`, `updated_at`) VALUES
(2, '4', '1', '1', '1', 'Windows', 'XPANDER RINA MITS WB SMART 2.pdf', '2024-04-22 02:49:04', '2024-04-22 02:49:04'),
(6, '4', '1', '1', '1', 'Jaringan', 'XFORCE ADHI MITS GADING SMART.pdf', '2024-04-22 02:52:43', '2024-04-22 02:52:43'),
(7, '1', '5', '1', '1', 'Pantun', 'ALPHARD CINDI.pdf', '2024-04-28 19:53:43', '2024-04-28 19:53:43');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `point`
--

CREATE TABLE `point` (
  `id_point` bigint UNSIGNED NOT NULL,
  `nama_point` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_point` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skor_point` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `point`
--

INSERT INTO `point` (`id_point`, `nama_point`, `jenis_point`, `skor_point`, `created_at`, `updated_at`) VALUES
(1, 'Terlambat Masuk Sekolah', 'KEDISIPLINAN', 5, '2024-04-20 07:34:55', '2024-04-20 07:34:55'),
(2, 'Tawuran', 'KEDISIPLINAN', 100, '2024-04-20 07:35:16', '2024-04-20 07:35:16'),
(3, 'Melakukan vandalisme', 'SIKAP', 10, '2024-04-20 07:35:47', '2024-04-20 07:35:47'),
(5, 'Tidak Beratribut Lengkap', 'KERAPIHAN', 5, '2024-04-20 07:41:21', '2024-04-20 07:41:21');

-- --------------------------------------------------------

--
-- Table structure for table `point_siswa`
--

CREATE TABLE `point_siswa` (
  `id_point_siswa` bigint UNSIGNED NOT NULL,
  `id_siswa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_point` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_jurusan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_guru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skor_point` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `point_siswa`
--

INSERT INTO `point_siswa` (`id_point_siswa`, `id_siswa`, `id_point`, `id_kelas`, `id_jurusan`, `id_guru`, `role`, `skor_point`, `tanggal`, `created_at`, `updated_at`) VALUES
(1, '1', '3', '1', '1', '1', 'admin', '10', '23 April 2024 20:21', '2024-04-23 06:22:22', '2024-04-23 06:22:22'),
(3, '1', '3', '1', '1', '1', 'admin', '10', '23 April 2024 20:35', '2024-04-23 06:35:31', '2024-04-23 06:35:31'),
(4, '1', '1', '1', '1', '1', 'admin', '5', '23 April 2024 20:38', '2024-04-23 06:38:46', '2024-04-23 06:38:46'),
(5, '1', '2', '1', '1', '1', 'admin', '100', '25 April 2024 22:43', '2024-04-25 08:44:02', '2024-04-25 08:44:02'),
(6, '2', '5', '1', '1', '1', 'admin', '5', '30 April 2024 10:52', '2024-04-29 20:54:32', '2024-04-29 20:54:32');

-- --------------------------------------------------------

--
-- Table structure for table `rapot`
--

CREATE TABLE `rapot` (
  `id_rapot` bigint UNSIGNED NOT NULL,
  `id_siswa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rata_rata` int NOT NULL,
  `file_rapot` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rapot`
--

INSERT INTO `rapot` (`id_rapot`, `id_siswa`, `id_kelas`, `semester`, `rata_rata`, `file_rapot`, `created_at`, `updated_at`) VALUES
(1, '1', '1', '1', 80, 'ALPHARD CINDI.pdf', '2024-04-22 07:42:55', '2024-04-22 07:42:55'),
(3, '1', '1', '2', 75, 'MERCY GLE450 PT RAJAWALI MEDIA.pdf', '2024-04-22 07:51:13', '2024-04-22 07:51:13'),
(5, '1', '1', '3', 80, 'QS-JK-2024-04-8532 INNOVA ZENIX MARIA TOY BBR.pdf', '2024-04-22 08:30:58', '2024-04-22 22:07:39'),
(6, '2', '1', '1', 90, 'MERCY GLE450 PT RAJAWALI MEDIA.pdf', '2024-04-22 08:31:16', '2024-04-22 08:31:16'),
(7, '2', '1', '2', 79, 'ALPHARD CINDI.pdf', '2024-04-29 19:43:29', '2024-04-29 19:43:29');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_app` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_sekolah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kepsek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip_kepsek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kec` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prov` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `nama_app`, `nama_sekolah`, `nama_kepsek`, `nip_kepsek`, `alamat`, `kel`, `kec`, `prov`, `kota`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'SIAWI APP', 'SMK Wisata Indonesia', 'H. Abdul Munir, HMA, M.Pd.', '-', 'JL. Raya Lenteng Agung / Jl. Langgar RT 009/03 No. 1 Kode Pos 12520', 'Kebagusan', 'Pasar Minggu', 'DKI Jakarta', 'Jakarta Selatan', 'logo-wi.png', '2024-04-18 00:02:03', '2024-04-18 03:24:11');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` bigint UNSIGNED NOT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfid` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_siswa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_jurusan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tmpt_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_tlpn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rt` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rw` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_rumah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kec` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prov` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik_ayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tmpt_lahir_ayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir_ayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pendidikan_ayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan_ayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penghasilan_ayah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik_ibu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ibu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tmpt_lahir_ibu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir_ibu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pendidikan_ibu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan_ibu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penghasilan_ibu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik_wali` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_wali` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tmpt_lahir_wali` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir_wali` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pendidikan_wali` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan_wali` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penghasilan_wali` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nis`, `nisn`, `rfid`, `password`, `nama_siswa`, `id_level`, `id_kelas`, `id_jurusan`, `foto`, `tmpt_lahir`, `tgl_lahir`, `agama`, `jenis_kelamin`, `no_hp`, `no_tlpn`, `email`, `alamat`, `rt`, `rw`, `no_rumah`, `kel`, `kec`, `prov`, `kota`, `nik_ayah`, `nama_ayah`, `tmpt_lahir_ayah`, `tgl_lahir_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nik_ibu`, `nama_ibu`, `tmpt_lahir_ibu`, `tgl_lahir_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `nik_wali`, `nama_wali`, `tmpt_lahir_wali`, `tgl_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `created_at`, `updated_at`) VALUES
(1, '12345', '2103840192', '0011753597', '$2y$12$mvS.hu.33levn2qC3Sr0OuS8nxhGG6ShJ4nqI2QYMmeqxO4n8/ufG', 'Ilham Muhammad Alamsyah', '1', '1', '1', 'Screenshot 2024-01-27 161036.png', 'Jakarta', '2024-04-01', 'islam', 'L', '+6281382053328', '081382053328', 'ilhamalmsyh988@gmail.com', 'Jl. Komodor Halim Perdana Kusuma', '001', '001', '42', 'Halim Perdana Kusuma', 'Makasar', 'DKI Jakarta', 'Jakarta Timur', '3712371023', 'Mark', 'Jakarta', '1990-06-06', 's1', 'wiraswasta', '>Rp.5.000.000', '121082182012', 'Lisa', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '2024-04-16 00:25:52', '2024-11-19 06:31:22'),
(2, '233070', '191919', '', '$2y$12$8ejjiNuWzMCxJA1/KbvLDOFPARIQVsyBsBUwEz6wCMtAlPsR8vqvO', 'Muhammad Ridho', '1', '1', '1', 'avatar.jpg', 'jakarta', '2024-04-16', 'protestan', 'L', '08937479357576', '0848246425635', 'pip@gmail.com', 'Gg.langgar', '003', '003', '1', 'kebagusan', 'pasar minggu', 'DKI JAKARTA', 'jakarta selatan', '582783983', 'fatoni', 'jauh', '2024-04-16', 'sma', 'guru', 'Rp.4.100.000 - Rp 5.000.000', 'tebak', 'hehey', 'jauh', '2024-04-16', '-', 'karyawan', '-', '-', '-', '-', '-', '-', '-', '-', '2024-04-16 00:34:19', '2024-11-18 17:12:19'),
(5, '9090', '12037123', '0011792689', '$2y$12$O8H72te.XE0a1wlXmI5rM.WqK9qurXO22cs9XaIfG6cVY9nJ/ab0q', 'alika', '1', '2', '5', 'avatar.jpg', 'Jakarta', '2024-04-18', 'islam', 'P', '081382053328', '0848246425635', 'deyarsmkwi@gmail.com', 'Jl. Komodor Halim Perdana Kusuma', '001', '001', '1', 'Halim Perdana Kusuma', 'Pasar Minggu', 'DKI Jakarta', 'Jakarta Timur', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '2024-04-18 03:56:56', '2024-11-18 17:16:55'),
(6, '121212', '121212', '0012228421', '$2y$12$Q58v9j2mDmscg9F2Y/H7juGZHnZAMlGwx81VcEqKis.8KhTtkHSyW', 'IKHWAN PRATAMA SUBAGJA', '2', '3', '2', 'avatar.jpg', 'Jakarta', '2024-04-29', 'islam', 'L', '081382053328', '081382053328', 'smkwisataindonesia01@gmail.com', 'Jl. Komodor Halim Perdana Kusuma, Rt.001/Rw.007, No. 42, Kel. Halim Perdana Kusuma, Kec. Makasar\r\nRumah Pribadi', '001', '001', '001', 'halim', 'makasar', 'dki jakarta', 'Jakarta Timur', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '2024-04-29 04:41:41', '2024-11-18 17:16:49');

-- --------------------------------------------------------

--
-- Table structure for table `tagihan`
--

CREATE TABLE `tagihan` (
  `id_tagihan` bigint UNSIGNED NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tagihan`
--

INSERT INTO `tagihan` (`id_tagihan`, `link`, `created_at`, `updated_at`) VALUES
(1, 'https://www.infradigital.io/display_tagihan?display=dynamic&bc=10051&bk=', NULL, '2024-04-20 07:07:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id_berita`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indexes for table `informasi`
--
ALTER TABLE `informasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal_mapel`
--
ALTER TABLE `jadwal_mapel`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indexes for table `kalender`
--
ALTER TABLE `kalender`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`id_modul`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `point`
--
ALTER TABLE `point`
  ADD PRIMARY KEY (`id_point`);

--
-- Indexes for table `point_siswa`
--
ALTER TABLE `point_siswa`
  ADD PRIMARY KEY (`id_point_siswa`);

--
-- Indexes for table `rapot`
--
ALTER TABLE `rapot`
  ADD PRIMARY KEY (`id_rapot`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indexes for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id_tagihan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id_berita` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `informasi`
--
ALTER TABLE `informasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jadwal_mapel`
--
ALTER TABLE `jadwal_mapel`
  MODIFY `id_jadwal` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kalender`
--
ALTER TABLE `kalender`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `id_level` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `modul`
--
ALTER TABLE `modul`
  MODIFY `id_modul` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `point`
--
ALTER TABLE `point`
  MODIFY `id_point` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `point_siswa`
--
ALTER TABLE `point_siswa`
  MODIFY `id_point_siswa` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rapot`
--
ALTER TABLE `rapot`
  MODIFY `id_rapot` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id_tagihan` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
