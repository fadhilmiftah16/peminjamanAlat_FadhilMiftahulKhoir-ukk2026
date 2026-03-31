-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Mar 2026 pada 04.53
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
-- Database: `peminjaman_alat`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alat`
--

CREATE TABLE `alat` (
  `id` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alat`
--

INSERT INTO `alat` (`id`, `nama_alat`, `kategori`, `id_kategori`, `stok`, `gambar`) VALUES
(6, 'Proyektor', 'Elektronik', NULL, 10, '69800e421f333.png'),
(10, 'Laptop ', 'Elektronik', NULL, 4, '69800edb8f787.png'),
(15, 'Televisi', 'Elektronik', NULL, 9, '69800ffc79c5b.png'),
(16, 'speaker', 'Elektronik', NULL, 10, '698010fe5ee8c.png'),
(17, 'Camera Canon', 'Elektronik', NULL, 7, '698011b33a80d.jpg'),
(19, 'PS 5', 'Elektronik', NULL, 5, '69800c2a69cee.png'),
(20, 'Printer', 'Elektronik', NULL, 6, '6980c6d814f0d.png'),
(21, 'Drone', 'Elektronik', NULL, 5, '6980c73baa3f5.png'),
(22, 'Kunci Set Tekiro', 'Perkakas', NULL, 3, '698146e9bc459.png'),
(23, 'PC', 'Elektronik', NULL, 5, '6981c70503d67.png'),
(24, 'mouse', 'Elektronik', NULL, 10, '698961dab9755.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_alat` int(11) DEFAULT NULL,
  `jumlah_pinjam` int(11) DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('Pending','Disetujui','Selesai','Ditolak') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `peminjaman`
--
DELIMITER $$
CREATE TRIGGER `setelah_disetujui` AFTER UPDATE ON `peminjaman` FOR EACH ROW BEGIN
    IF NEW.status = 'Disetujui' AND OLD.status = 'Pending' THEN
        UPDATE alat SET stok = stok - NEW.jumlah_pinjam WHERE id_alat = NEW.id_alat;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `setelah_kembali` AFTER UPDATE ON `peminjaman` FOR EACH ROW BEGIN
    IF NEW.status = 'Selesai' AND OLD.status = 'Disetujui' THEN
        UPDATE alat SET stok = stok + NEW.jumlah_pinjam WHERE id_alat = NEW.id_alat;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('ADMIN','PETUGAS','PENGGUNA') NOT NULL DEFAULT 'PENGGUNA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama_lengkap`, `username`, `password`, `role`) VALUES
(1, 'Administrator', 'admin', 'admin123', 'ADMIN'),
(2, 'Fadhil Miftahul Khoir', 'dhilll16', 'sbrci009', 'PETUGAS'),
(3, 'Miftahul khoir', 'miftah', 'sbrci007', 'PENGGUNA'),
(4, 'bala bala ', 'tempe', '123', 'PENGGUNA'),
(5, 'yuril', 'yuril', '$2y$10$zbgvXCh4D9JykaEWS8fQuOX7OGyz3lfe07Q1WgBFXU.Oqnx7GmKxW', 'PENGGUNA'),
(6, 'iqbal', 'parloy', '$2y$10$B/GmzMoTdy.MCQKJ01tx7eSNWuSOMXShgiO1QODk.cU1Q2Usu15AO', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_alat` int(11) NOT NULL,
  `jumlah_pinjam` int(11) NOT NULL,
  `tgl_pinjam` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `denda` int(11) DEFAULT 0,
  `tanggal_kembali` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `id_user`, `id_alat`, `jumlah_pinjam`, `tgl_pinjam`, `status`, `denda`, `tanggal_kembali`) VALUES
(1, 3, 4, 0, '2026-01-30', 'kembali', 0, '2026-01-30 03:45:00'),
(2, 3, 8, 0, '2026-01-31', 'kembali', 0, '2026-01-31 20:56:02'),
(3, 3, 6, 0, '2026-02-01', 'kembali', 0, '2026-02-01 10:05:15'),
(4, 3, 5, 0, '2026-02-01', 'kembali', 0, '2026-02-01 10:05:12'),
(5, 3, 8, 0, '2026-02-01', 'pending', 0, NULL),
(6, 3, 4, 0, '2026-02-01', 'dipinjam', 0, NULL),
(7, 3, 15, 0, '2026-02-01', 'kembali', 0, '2026-02-01 10:05:09'),
(8, 3, 16, 0, '2026-02-01', 'kembali', 0, '2026-02-02 03:05:56'),
(9, 3, 6, 0, '2026-02-02', 'kembali', 0, '2026-02-02 15:09:01'),
(10, 4, 16, 0, '2026-02-02', 'kembali', 0, '2026-02-03 01:17:16'),
(11, 4, 17, 0, '2026-02-02', 'kembali', 0, '2026-02-03 01:17:08'),
(12, 4, 20, 0, '2026-02-03', 'kembali', 0, '2026-02-03 01:23:05'),
(13, 4, 20, 0, '2026-02-03', 'kembali', 0, '2026-02-03 01:23:01'),
(14, 4, 21, 0, '2026-02-03', 'kembali', 0, '2026-02-03 10:01:47'),
(15, 4, 19, 0, '2026-02-03', 'kembali', 0, '2026-02-03 10:18:32'),
(16, 4, 22, 0, '2026-02-03', 'ditolak', 0, NULL),
(17, 5, 10, 0, '2026-02-03', 'kembali', 0, '2026-02-03 10:18:05'),
(18, 3, 17, 0, '2026-02-03', 'kembali', 0, '2026-02-03 10:19:49'),
(19, 5, 20, 0, '2026-02-03', 'kembali', 0, '2026-02-03 10:56:26'),
(20, 5, 22, 0, '2026-02-09', 'dipinjam', 0, NULL),
(21, 5, 16, 0, '2026-02-09', 'kembali', 0, '2026-02-10 02:24:59'),
(22, 5, 10, 0, '2026-02-09', 'pending', 0, NULL),
(23, 5, 10, 0, '2026-02-09', 'pending', 0, NULL),
(24, 5, 10, 0, '2026-02-09', 'dipinjam', 0, NULL),
(25, 6, 6, 0, '2026-02-09', 'dipinjam', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_alat` (`id_alat`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alat`
--
ALTER TABLE `alat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `alat`
--
ALTER TABLE `alat`
  ADD CONSTRAINT `alat_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id`);

--
-- Ketidakleluasaan untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`id`) REFERENCES `transaksi` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
