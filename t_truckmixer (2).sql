-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Feb 2024 pada 02.32
-- Versi server: 10.4.18-MariaDB
-- Versi PHP: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bash`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_truckmixer`
--

CREATE TABLE `t_truckmixer` (
  `driver_id` int(50) NOT NULL,
  `no_pol` varchar(50) DEFAULT NULL,
  `driver_name` varchar(100) DEFAULT NULL,
  `no_telp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `t_truckmixer`
--

INSERT INTO `t_truckmixer` (`driver_id`, `no_pol`, `driver_name`, `no_telp`) VALUES
(4, 'F3212CK', 'IMAR', '089517502213'),
(6, 'TM1324', 'budi', '89517502213');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `t_truckmixer`
--
ALTER TABLE `t_truckmixer`
  ADD PRIMARY KEY (`driver_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `t_truckmixer`
--
ALTER TABLE `t_truckmixer`
  MODIFY `driver_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
