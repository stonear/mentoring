-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Agu 2018 pada 05.09
-- Versi server: 10.1.30-MariaDB
-- Versi PHP: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simits`
--
CREATE DATABASE IF NOT EXISTS `simits` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `simits`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_absen`
--

CREATE TABLE `simits_absen` (
  `mingguke` int(11) NOT NULL,
  `statuskehadiran` int(11) DEFAULT NULL,
  `NRPpeserta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_absenmentor`
--

CREATE TABLE `simits_absenmentor` (
  `tahun` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `mingguke` int(11) NOT NULL,
  `statuskehadiran` int(11) DEFAULT NULL,
  `nrpmentor` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_admin`
--

CREATE TABLE `simits_admin` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_berita`
--

CREATE TABLE `simits_berita` (
  `id` int(11) NOT NULL,
  `admin` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL,
  `judul` text NOT NULL,
  `konten` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_dosen`
--

CREATE TABLE `simits_dosen` (
  `NIKdosen` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_dosenpembina`
--

CREATE TABLE `simits_dosenpembina` (
  `NIKdosenpembina` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_fileabsen`
--

CREATE TABLE `simits_fileabsen` (
  `mingguke` int(11) NOT NULL,
  `IDkelompok` int(11) NOT NULL,
  `linkfile` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_fileabsenmentor`
--

CREATE TABLE `simits_fileabsenmentor` (
  `tahun` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `mingguke` int(11) NOT NULL,
  `nipdosenpembina` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_jadwal`
--

CREATE TABLE `simits_jadwal` (
  `mingguke` int(11) NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `IDkelompok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_jadwalmentor`
--

CREATE TABLE `simits_jadwalmentor` (
  `tahun` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `mingguke` int(11) NOT NULL,
  `NIKdosenpembina` varchar(255) NOT NULL,
  `tanggal` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_kelas`
--

CREATE TABLE `simits_kelas` (
  `IDkelas` int(11) NOT NULL,
  `NOkelas` varchar(3) NOT NULL,
  `NIKdosen` varchar(255) NOT NULL,
  `tahun` int(11) NOT NULL,
  `semester` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_kelompokmentoring`
--

CREATE TABLE `simits_kelompokmentoring` (
  `IDkelompok` int(11) NOT NULL,
  `NOkelompok` int(11) NOT NULL,
  `NRPmentor` varchar(255) NOT NULL,
  `IDkelas` int(11) NOT NULL,
  `NIKdosenpembina` varchar(255) NOT NULL,
  `hari` int(11) NOT NULL,
  `jeniskelamin` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_masternilai`
--

CREATE TABLE `simits_masternilai` (
  `IDnilai` int(11) NOT NULL,
  `namanilai` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_mentor`
--

CREATE TABLE `simits_mentor` (
  `NRPmentor` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` varchar(1) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `pernah_jadi_mentor` int(11) DEFAULT NULL,
  `nilai` varchar(2) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `linkfoto` varchar(255) DEFAULT NULL,
  `kode_verifikasi` varchar(255) DEFAULT NULL,
  `verified` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_nilai`
--

CREATE TABLE `simits_nilai` (
  `NRPpeserta` varchar(255) NOT NULL,
  `IDnilai` int(11) NOT NULL,
  `nilai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_pertemuan`
--

CREATE TABLE `simits_pertemuan` (
  `tahun` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `jumlahpertemuan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `simits_peserta`
--

CREATE TABLE `simits_peserta` (
  `NRPpeserta` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `IDkelas` int(11) NOT NULL,
  `IDkelompok` int(11) NOT NULL,
  `jeniskelamin` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `simits_berita`
--
ALTER TABLE `simits_berita`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `simits_dosen`
--
ALTER TABLE `simits_dosen`
  ADD PRIMARY KEY (`NIKdosen`);

--
-- Indeks untuk tabel `simits_dosenpembina`
--
ALTER TABLE `simits_dosenpembina`
  ADD PRIMARY KEY (`NIKdosenpembina`);

--
-- Indeks untuk tabel `simits_kelas`
--
ALTER TABLE `simits_kelas`
  ADD PRIMARY KEY (`IDkelas`);

--
-- Indeks untuk tabel `simits_kelompokmentoring`
--
ALTER TABLE `simits_kelompokmentoring`
  ADD PRIMARY KEY (`IDkelompok`);

--
-- Indeks untuk tabel `simits_masternilai`
--
ALTER TABLE `simits_masternilai`
  ADD PRIMARY KEY (`IDnilai`);

--
-- Indeks untuk tabel `simits_mentor`
--
ALTER TABLE `simits_mentor`
  ADD PRIMARY KEY (`NRPmentor`);

--
-- Indeks untuk tabel `simits_peserta`
--
ALTER TABLE `simits_peserta`
  ADD PRIMARY KEY (`NRPpeserta`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `simits_berita`
--
ALTER TABLE `simits_berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `simits_kelas`
--
ALTER TABLE `simits_kelas`
  MODIFY `IDkelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `simits_kelompokmentoring`
--
ALTER TABLE `simits_kelompokmentoring`
  MODIFY `IDkelompok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `simits_masternilai`
--
ALTER TABLE `simits_masternilai`
  MODIFY `IDnilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
