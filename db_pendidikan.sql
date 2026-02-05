-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Feb 2026 pada 02.26
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
-- Database: `db_pendidikan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `matapelajaran`
--

CREATE TABLE `matapelajaran` (
  `id_mapel` int(11) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `kkm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `matapelajaran`
--

INSERT INTO `matapelajaran` (`id_mapel`, `nama_mapel`, `kkm`) VALUES
(1, 'Pendidikan Agama dan Budi Pekerti', 75),
(2, 'Pendidikan Pancasila dan Kewarganegaraan (PPKn)', 75),
(3, 'Bahasa Indonesia', 75),
(4, 'Matematika', 70),
(5, 'Sejarah Indonesia', 75),
(6, 'Bahasa Inggris', 75),
(7, 'Seni Budaya', 78),
(8, 'Pendidikan Jasmani, Olahraga, dan Kesehatan (PJOK)', 78),
(9, 'Muatan Lokal (Bahasa Daerah)', 75),
(10, 'Simulasi dan Komunikasi Digital', 75),
(11, 'Fisika', 70),
(12, 'Kimia', 70),
(13, 'IPA Terapan', 75),
(14, 'Sistem Komputer', 72),
(15, 'Komputer dan Jaringan Dasar', 72),
(16, 'Pemrograman Dasar', 70),
(17, 'Dasar Desain Grafis', 75),
(18, 'Pemodelan Perangkat Lunak', 75),
(19, 'Basis Data', 75),
(20, 'Pemrograman Berorientasi Objek', 72),
(21, 'Pemrograman Web dan Perangkat Bergerak', 75),
(22, 'Produk Kreatif dan Kewirausahaan', 80),
(23, 'Teknologi Jaringan Berbasis Luas (WAN)', 75),
(24, 'Administrasi Infrastruktur Jaringan', 72),
(25, 'Administrasi Sistem Jaringan', 72),
(26, 'Teknologi Layanan Jaringan', 75),
(27, 'Etika Profesi', 75),
(28, 'Aplikasi Pengolah Angka (Spreadsheet)', 75),
(29, 'Akuntansi Dasar', 70),
(30, 'Perbankan Dasar', 75),
(31, 'Praktikum Akuntansi Perusahaan Jasa & Dagang', 72),
(32, 'Praktikum Akuntansi Lembaga/Instansi Pemerintah', 72),
(33, 'Administrasi Pajak', 72);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `tugas` int(11) NOT NULL,
  `uts` int(11) NOT NULL,
  `uas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_siswa`, `id_mapel`, `tugas`, `uts`, `uas`) VALUES
(1, 1, 1, 100, 100, 100),
(2, 3, 1, 100, 100, 100),
(3, 3, 2, 100, 100, 100),
(4, 3, 3, 100, 100, 100),
(5, 3, 4, 100, 100, 100),
(6, 3, 5, 100, 100, 100),
(7, 3, 6, 100, 100, 100),
(8, 3, 7, 100, 100, 100),
(9, 3, 8, 100, 100, 100),
(10, 3, 9, 100, 100, 100),
(11, 3, 10, 100, 100, 100),
(12, 3, 11, 100, 100, 100),
(13, 3, 12, 100, 100, 100),
(14, 3, 13, 100, 100, 100),
(15, 3, 14, 100, 100, 100),
(16, 3, 15, 100, 100, 100),
(17, 3, 16, 100, 100, 100),
(18, 3, 17, 100, 100, 100),
(19, 3, 18, 100, 100, 100),
(20, 3, 19, 100, 100, 100),
(21, 3, 20, 100, 100, 100),
(22, 3, 21, 100, 100, 100),
(23, 3, 22, 100, 100, 100),
(24, 3, 23, 100, 100, 100),
(25, 3, 24, 100, 100, 100),
(26, 3, 25, 100, 100, 100),
(27, 3, 26, 100, 100, 100),
(28, 3, 27, 100, 100, 100),
(29, 3, 28, 100, 100, 100),
(30, 3, 29, 100, 100, 100),
(31, 3, 30, 100, 100, 100),
(32, 3, 31, 100, 100, 100),
(33, 3, 32, 100, 100, 100),
(34, 3, 33, 100, 100, 100),
(35, 4, 1, 100, 100, 100),
(36, 4, 2, 100, 100, 100),
(37, 4, 3, 100, 100, 100);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$g3oiVxCL29B67gKSNoJHKe6a9vniLvR3L3a2MS76N.awjkXJtelVW', 'Administrator', 'admin', '2026-02-05 00:30:36'),
(2, 'j45t1n', '$2y$10$ffx72A1Vu.O9.MaKIDESu.AN3tWNF3ic8YPrMTMiYvXrFy2FnuzWi', 'Justine', 'user', '2026-02-05 00:38:17');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `matapelajaran`
--
ALTER TABLE `matapelajaran`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `matapelajaran`
--
ALTER TABLE `matapelajaran`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
