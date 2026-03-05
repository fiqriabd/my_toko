-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2026 at 08:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_toko`
--

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(10) UNSIGNED NOT NULL,
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `kode_produk` varchar(255) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `merk_produk` varchar(255) DEFAULT NULL,
  `harga_beli_produk` int(11) NOT NULL,
  `harga_jual_produk` int(11) NOT NULL,
  `stok_produk` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_kategori`, `kode_produk`, `nama_produk`, `merk_produk`, `harga_beli_produk`, `harga_jual_produk`, `stok_produk`, `created_at`, `updated_at`) VALUES
(5, 1, 'P000001', 'Biskuit Regal', 'Marie', 11800, 14000, 15, '2026-03-03 23:13:03', '2026-03-03 23:54:36'),
(6, 7, 'P000006', 'Obat Nyamuk', 'Kingkong', 5000, 6000, 20, '2026-03-03 23:13:41', '2026-03-03 23:13:41'),
(7, 1, 'P000007', 'Mie Goreng', 'Indomie', 2800, 3500, 30, '2026-03-03 23:14:30', '2026-03-03 23:14:30'),
(8, 1, 'P000008', 'Sarden Kaleng', 'ABC', 9700, 12000, 15, '2026-03-03 23:16:02', '2026-03-03 23:16:02'),
(9, 4, 'P000009', 'Rokok 76', 'Djarum', 15400, 17000, 25, '2026-03-03 23:17:01', '2026-03-03 23:17:01'),
(10, 2, 'P000010', 'Pocari Sweat  500 mL', 'Pocari', 6100, 7500, 10, '2026-03-03 23:18:25', '2026-03-03 23:18:25'),
(11, 9, 'P000011', 'Plastik 1 Kg  bening', 'Boyo', 2800, 4000, 15, '2026-03-03 23:19:24', '2026-03-03 23:19:24'),
(12, 3, 'P000013', 'Oli 2T', 'Evalube', 25200, 27500, 3, '2026-03-03 23:21:15', '2026-03-03 23:21:15'),
(13, 8, 'P000014', 'Rinso 50 gr', 'Rinso', 800, 1000, 50, '2026-03-03 23:22:18', '2026-03-03 23:22:18'),
(14, 4, 'P000015', 'Rokok Sampoerna Mild', 'Sampoerna', 35000, 37000, 5, '2026-03-03 23:23:29', '2026-03-03 23:23:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD UNIQUE KEY `produk_nama_produk_unique` (`nama_produk`),
  ADD UNIQUE KEY `produk_kode_produk_unique` (`kode_produk`),
  ADD KEY `produk_id_kategori_foreign` (`id_kategori`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
