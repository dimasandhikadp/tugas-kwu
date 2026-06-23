-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2026 at 01:21 PM
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
-- Database: `db_segar`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kelurahan` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `nama_penerima`, `no_hp`, `alamat`, `kota`, `kecamatan`, `kelurahan`, `provinsi`, `is_default`) VALUES
(1, 1, 'Dimas Andhika Dwi Permana', '082253557299', 'Perum PNS Blok A No 456', 'Tarakan', 'Tarakan Timur', 'Mamburungan', 'Kalimantan Utara', 0),
(2, 5, 'Bambang', '082253557299', 'Perum PNS Blok A No 456', 'Tarakan', 'Tarakan Timur', 'Lingkas Ujung', 'Kalimantan Utara', 1),
(3, 1, 'Dimas Andhika Dwi Permana', '082253557299', 'Perum PNS Blok A No 456', 'Palangkaraya', 'Jekan Raya', 'Menteng', 'Kalimantan Tengah', 0),
(4, 1, 'Dimas Andhika Dwi Permana', '082253557299', 'Perum PNS Blok A No 456', 'Palangkaraya', 'Jekan Raya', 'Menteng', 'Kalimantan Tengah', 0),
(5, 1, 'Dimas Andhika Dwi Permana', '082253557299', 'Perum PNS Blok A No 456', 'Palangkaraya', 'Jekan Raya', 'Menteng', 'Kalimantan Tengah', 0),
(6, 1, 'Dimas Andhika Dwi Permana', '082253557299', 'Perum PNS Blok A No 456', 'Palangkaraya', 'Jekan Raya', 'Menteng', 'Kalimantan Tengah', 1),
(7, 6, 'ikhwan', '089523234545', 'no 3', 'Tarakan', 'Tarakan Barat', 'Karang Anyar', 'Kalimantan Utara', 1),
(8, 7, 'Budi', '082222222222', 'Perum PNS Blok A No 456', 'Tarakan', 'Tarakan Timur', 'Lingkas Ujung', 'Kalimantan Utara', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `created_at`) VALUES
(1, 5, '2026-06-16 13:04:05'),
(2, 1, '2026-06-17 05:46:17'),
(3, 7, '2026-06-19 11:33:48');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `alamat_id` int(11) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `status` enum('pending','diproses','dikirim','selesai','dibatalkan') DEFAULT 'pending',
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `va_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `alamat_id`, `total_harga`, `status`, `metode_pembayaran`, `va_number`, `created_at`) VALUES
(1, 1, 6, 42000.00, 'selesai', 'COD', 'COD2606221543231', '2026-06-22 13:43:23'),
(2, 1, 6, 42000.00, 'dibatalkan', 'DANA', 'DAN2606221548301', '2026-06-22 13:48:30'),
(3, 1, 6, 42000.00, 'diproses', 'GOPAY', 'GOP2606221548401', '2026-06-22 13:48:40'),
(4, 1, 6, 42000.00, 'pending', 'COD', 'COD2606231157481', '2026-06-23 09:57:48'),
(5, 1, 6, 42000.00, 'dikirim', 'COD', 'COD2606231204521', '2026-06-23 10:04:52'),
(6, 1, 6, 42000.00, 'pending', 'QRIS', 'QRI2606231205411', '2026-06-23 10:05:41'),
(7, 1, 6, 62000.00, 'diproses', 'COD', 'COD2606231221121', '2026-06-23 10:21:12'),
(8, 1, 6, 52000.00, 'diproses', 'QRIS', 'QRI2606231221301', '2026-06-23 10:21:30'),
(9, 1, 6, 52000.00, 'diproses', 'OVO', 'OVO2606231222111', '2026-06-23 10:22:11'),
(10, 1, 6, 42000.00, 'diproses', 'BNI', 'BNI2606231222321', '2026-06-23 10:22:32'),
(11, 1, 6, 52000.00, 'diproses', 'COD', 'COD2606231222491', '2026-06-23 10:22:49'),
(12, 1, 6, 47000.00, 'pending', 'BNI', 'BNI2606231223021', '2026-06-23 10:23:02'),
(13, 1, 6, 47000.00, 'diproses', 'BRI', 'BRI2606231224111', '2026-06-23 10:24:11'),
(14, 1, 6, 42000.00, 'diproses', 'DANA', 'DAN2606231226461', '2026-06-23 10:26:46'),
(15, 1, 6, 52000.00, 'pending', 'BCA', 'BCA2606231230171', '2026-06-23 10:30:17'),
(16, 1, 6, 67000.00, 'diproses', 'SHOPEEPAY', 'SHO2606231230391', '2026-06-23 10:30:39'),
(17, 1, 6, 67000.00, 'diproses', 'COD', 'COD2606231234481', '2026-06-23 10:34:48'),
(18, 1, 6, 67000.00, 'diproses', 'COD', 'COD2606231236061', '2026-06-23 10:36:06'),
(19, 1, 6, 72000.00, 'diproses', 'GOPAY', 'GOP2606231236251', '2026-06-23 10:36:25'),
(20, 1, 6, 62000.00, 'diproses', 'COD', 'COD2606231236401', '2026-06-23 10:36:40'),
(21, 1, 6, 62000.00, 'pending', 'QRIS', 'QRI2606231236541', '2026-06-23 10:36:54'),
(22, 1, 6, 62000.00, 'pending', 'BNI', 'BNI2606231237391', '2026-06-23 10:37:39'),
(23, 1, 6, 47000.00, 'pending', 'QRIS', 'QRI2606231239071', '2026-06-23 10:39:07'),
(24, 1, 6, 77000.00, 'pending', 'GOPAY', 'GOP2606231239211', '2026-06-23 10:39:21'),
(25, 1, 6, 42000.00, 'diproses', 'COD', 'COD2606231253211', '2026-06-23 10:53:21'),
(26, 1, 6, 47000.00, 'diproses', 'OVO', 'OVO2606231253551', '2026-06-23 10:53:55'),
(27, 1, 6, 52000.00, 'diproses', 'BNI', 'BNI2606231254341', '2026-06-23 10:54:34'),
(28, 1, 6, 42000.00, 'diproses', 'COD', 'COD2606231255021', '2026-06-23 10:55:02'),
(29, 1, 6, 77000.00, 'diproses', 'DANA', 'DAN2606231255221', '2026-06-23 10:55:22'),
(30, 1, 6, 52000.00, 'diproses', 'QRIS', 'QRI2606231255581', '2026-06-23 10:55:58'),
(31, 1, 6, 77000.00, 'diproses', 'OVO', 'OVO2606231256231', '2026-06-23 10:56:23'),
(32, 1, 6, 52000.00, 'diproses', 'COD', 'COD2606231259131', '2026-06-23 10:59:13'),
(33, 1, 6, 52000.00, 'diproses', 'COD', 'COD2606231259181', '2026-06-23 10:59:18'),
(34, 1, 6, 52000.00, 'diproses', 'COD', 'COD2606231259231', '2026-06-23 10:59:23'),
(35, 1, 6, 52000.00, 'diproses', 'COD', 'COD2606231259331', '2026-06-23 10:59:33'),
(36, 1, 6, 47000.00, 'diproses', 'COD', 'COD2606231259451', '2026-06-23 10:59:45'),
(37, 1, 6, 47000.00, 'diproses', 'COD', 'COD2606231259531', '2026-06-23 10:59:53'),
(38, 1, 6, 47000.00, 'diproses', 'COD', 'COD2606231300041', '2026-06-23 11:00:04'),
(39, 1, 6, 47000.00, 'diproses', 'COD', 'COD2606231300131', '2026-06-23 11:00:13'),
(40, 1, 6, 47000.00, 'diproses', 'COD', 'COD2606231300361', '2026-06-23 11:00:36'),
(41, 1, 6, 47000.00, 'diproses', 'COD', 'COD2606231300461', '2026-06-23 11:00:46'),
(42, 1, 6, 47000.00, 'diproses', 'COD', 'COD2606231301061', '2026-06-23 11:01:06'),
(43, 1, 6, 47000.00, 'diproses', 'COD', 'COD2606231301171', '2026-06-23 11:01:17'),
(44, 1, 6, 77000.00, 'diproses', 'QRIS', 'QRI2606231301371', '2026-06-23 11:01:37'),
(45, 1, 6, 42000.00, 'diproses', 'OVO', 'OVO2606231302011', '2026-06-23 11:02:01'),
(46, 1, 6, 52000.00, 'diproses', 'BCA', 'BCA2606231302191', '2026-06-23 11:02:19'),
(47, 1, 6, 52000.00, 'pending', 'BNI', 'BNI2606231303021', '2026-06-23 11:03:02'),
(48, 1, 6, 52000.00, 'diproses', 'BNI', 'BNI2606231303081', '2026-06-23 11:03:08'),
(49, 1, 6, 52000.00, 'diproses', 'BCA', 'BCA2606231303321', '2026-06-23 11:03:32'),
(50, 1, 6, 42000.00, 'diproses', 'BCA', 'BCA2606231303551', '2026-06-23 11:03:55'),
(51, 1, 6, 42000.00, 'diproses', 'BRI', 'BRI2606231305271', '2026-06-23 11:05:27'),
(52, 1, 6, 62000.00, 'diproses', 'BRI', 'BRI2606231307331', '2026-06-23 11:07:33'),
(53, 1, 6, 62000.00, 'diproses', 'BRI', 'BRI2606231307561', '2026-06-23 11:07:56'),
(54, 1, 6, 42000.00, 'diproses', 'COD', 'COD2606231308091', '2026-06-23 11:08:09'),
(55, 1, 6, 77000.00, 'diproses', 'QRIS', 'QRI2606231308231', '2026-06-23 11:08:23'),
(56, 1, 6, 47000.00, 'diproses', 'GOPAY', 'GOP2606231309021', '2026-06-23 11:09:02'),
(57, 1, 6, 77000.00, 'diproses', 'OVO', 'OVO2606231310141', '2026-06-23 11:10:14'),
(58, 1, 6, 62000.00, 'diproses', 'BCA', 'BCA2606231311131', '2026-06-23 11:11:13'),
(59, 1, 6, 62000.00, 'pending', 'GOPAY', 'GOP2606231312381', '2026-06-23 11:12:38'),
(60, 1, 6, 62000.00, 'pending', 'OVO', 'OVO2606231313051', '2026-06-23 11:13:05'),
(61, 1, 6, 62000.00, 'pending', 'OVO', 'OVO2606231313361', '2026-06-23 11:13:36'),
(62, 1, 6, 62000.00, 'diproses', 'COD', 'COD2606231313431', '2026-06-23 11:13:43'),
(63, 1, 6, 42000.00, 'diproses', 'GOPAY', 'GOP2606231313551', '2026-06-23 11:13:55'),
(64, 1, 6, 42000.00, 'diproses', 'COD', 'COD2606231320221', '2026-06-23 11:20:22'),
(65, 1, 6, 42000.00, 'diproses', 'QRIS', 'QRI2606231320321', '2026-06-23 11:20:32'),
(66, 1, 6, 47000.00, 'diproses', 'GOPAY', 'GOP2606231320451', '2026-06-23 11:20:45'),
(67, 1, 6, 47000.00, 'diproses', 'BNI', 'BNI2606231320551', '2026-06-23 11:20:55');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `harga`, `qty`, `subtotal`) VALUES
(1, 1, 23, 20000.00, 1, 20000.00),
(2, 2, 23, 20000.00, 1, 20000.00),
(3, 3, 23, 20000.00, 1, 20000.00),
(4, 4, 23, 20000.00, 1, 20000.00),
(5, 5, 23, 20000.00, 1, 20000.00),
(6, 6, 23, 20000.00, 1, 20000.00),
(7, 7, 17, 40000.00, 1, 40000.00),
(8, 8, 21, 30000.00, 1, 30000.00),
(9, 9, 22, 30000.00, 1, 30000.00),
(10, 10, 23, 20000.00, 1, 20000.00),
(11, 11, 21, 30000.00, 1, 30000.00),
(12, 12, 13, 25000.00, 1, 25000.00),
(13, 13, 13, 25000.00, 1, 25000.00),
(14, 14, 23, 20000.00, 1, 20000.00),
(15, 15, 21, 30000.00, 1, 30000.00),
(16, 16, 15, 45000.00, 1, 45000.00),
(17, 17, 15, 45000.00, 1, 45000.00),
(18, 18, 15, 45000.00, 1, 45000.00),
(19, 19, 7, 50000.00, 1, 50000.00),
(20, 20, 19, 40000.00, 1, 40000.00),
(21, 21, 19, 40000.00, 1, 40000.00),
(22, 22, 19, 40000.00, 1, 40000.00),
(23, 23, 13, 25000.00, 1, 25000.00),
(24, 24, 30, 55000.00, 1, 55000.00),
(25, 25, 23, 20000.00, 1, 20000.00),
(26, 26, 13, 25000.00, 1, 25000.00),
(27, 27, 21, 30000.00, 1, 30000.00),
(28, 28, 23, 20000.00, 1, 20000.00),
(29, 29, 30, 55000.00, 1, 55000.00),
(30, 30, 21, 30000.00, 1, 30000.00),
(31, 31, 30, 55000.00, 1, 55000.00),
(32, 32, 21, 30000.00, 1, 30000.00),
(33, 33, 21, 30000.00, 1, 30000.00),
(34, 34, 21, 30000.00, 1, 30000.00),
(35, 35, 21, 30000.00, 1, 30000.00),
(36, 36, 14, 25000.00, 1, 25000.00),
(37, 37, 14, 25000.00, 1, 25000.00),
(38, 38, 14, 25000.00, 1, 25000.00),
(39, 39, 14, 25000.00, 1, 25000.00),
(40, 40, 14, 25000.00, 1, 25000.00),
(41, 41, 14, 25000.00, 1, 25000.00),
(42, 42, 14, 25000.00, 1, 25000.00),
(43, 43, 14, 25000.00, 1, 25000.00),
(44, 44, 30, 55000.00, 1, 55000.00),
(45, 45, 23, 20000.00, 1, 20000.00),
(46, 46, 21, 30000.00, 1, 30000.00),
(47, 47, 21, 30000.00, 1, 30000.00),
(48, 48, 21, 30000.00, 1, 30000.00),
(49, 49, 21, 30000.00, 1, 30000.00),
(50, 50, 23, 20000.00, 1, 20000.00),
(51, 51, 23, 20000.00, 1, 20000.00),
(52, 52, 19, 40000.00, 1, 40000.00),
(53, 53, 19, 40000.00, 1, 40000.00),
(54, 54, 23, 20000.00, 1, 20000.00),
(55, 55, 30, 55000.00, 1, 55000.00),
(56, 56, 14, 25000.00, 1, 25000.00),
(57, 57, 30, 55000.00, 1, 55000.00),
(58, 58, 19, 40000.00, 1, 40000.00),
(59, 59, 19, 40000.00, 1, 40000.00),
(60, 60, 19, 40000.00, 1, 40000.00),
(61, 61, 19, 40000.00, 1, 40000.00),
(62, 62, 19, 40000.00, 1, 40000.00),
(63, 63, 23, 20000.00, 1, 20000.00),
(64, 64, 23, 20000.00, 1, 20000.00),
(65, 65, 23, 20000.00, 1, 20000.00),
(66, 66, 14, 25000.00, 1, 25000.00),
(67, 67, 14, 25000.00, 1, 25000.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_status_logs`
--

CREATE TABLE `order_status_logs` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_status_logs`
--

INSERT INTO `order_status_logs` (`id`, `order_id`, `status`, `created_at`) VALUES
(1, 1, 'pending', '2026-06-22 13:43:23'),
(2, 2, 'pending', '2026-06-22 13:48:30'),
(3, 3, 'pending', '2026-06-22 13:48:40'),
(4, 4, 'pending', '2026-06-23 09:57:48'),
(5, 5, 'pending', '2026-06-23 10:04:52'),
(6, 6, 'pending', '2026-06-23 10:05:41'),
(7, 7, 'diproses', '2026-06-23 10:21:12'),
(8, 8, 'pending', '2026-06-23 10:21:30'),
(9, 8, 'diproses', '2026-06-23 10:21:48'),
(10, 9, 'pending', '2026-06-23 10:22:11'),
(11, 9, 'diproses', '2026-06-23 10:22:17'),
(12, 10, 'pending', '2026-06-23 10:22:32'),
(13, 10, 'diproses', '2026-06-23 10:22:39'),
(14, 11, 'diproses', '2026-06-23 10:22:49'),
(15, 12, 'pending', '2026-06-23 10:23:02'),
(16, 13, 'pending', '2026-06-23 10:24:11'),
(17, 13, 'diproses', '2026-06-23 10:24:14'),
(18, 14, 'pending', '2026-06-23 10:26:47'),
(19, 14, 'diproses', '2026-06-23 10:27:03'),
(20, 15, 'pending', '2026-06-23 10:30:17'),
(21, 16, 'pending', '2026-06-23 10:30:39'),
(22, 16, 'diproses', '2026-06-23 10:34:35'),
(23, 17, 'diproses', '2026-06-23 10:34:48'),
(24, 18, 'diproses', '2026-06-23 10:36:06'),
(25, 19, 'pending', '2026-06-23 10:36:25'),
(26, 19, 'diproses', '2026-06-23 10:36:26'),
(27, 20, 'diproses', '2026-06-23 10:36:40'),
(28, 21, 'pending', '2026-06-23 10:36:54'),
(29, 22, 'pending', '2026-06-23 10:37:39'),
(30, 23, 'pending', '2026-06-23 10:39:07'),
(31, 24, 'pending', '2026-06-23 10:39:21'),
(32, 25, 'diproses', '2026-06-23 10:53:21'),
(33, 26, 'pending', '2026-06-23 10:53:55'),
(34, 26, 'diproses', '2026-06-23 10:54:02'),
(35, 27, 'pending', '2026-06-23 10:54:34'),
(36, 27, 'diproses', '2026-06-23 10:54:36'),
(37, 28, 'diproses', '2026-06-23 10:55:02'),
(38, 29, 'pending', '2026-06-23 10:55:22'),
(39, 29, 'diproses', '2026-06-23 10:55:24'),
(40, 30, 'pending', '2026-06-23 10:55:58'),
(41, 31, 'pending', '2026-06-23 10:56:23'),
(42, 31, 'diproses', '2026-06-23 10:56:24'),
(43, 30, 'diproses', '2026-06-23 10:56:30'),
(44, 32, 'diproses', '2026-06-23 10:59:13'),
(45, 33, 'diproses', '2026-06-23 10:59:18'),
(46, 34, 'diproses', '2026-06-23 10:59:23'),
(47, 35, 'diproses', '2026-06-23 10:59:33'),
(48, 36, 'diproses', '2026-06-23 10:59:45'),
(49, 37, 'diproses', '2026-06-23 10:59:53'),
(50, 38, 'diproses', '2026-06-23 11:00:04'),
(51, 39, 'diproses', '2026-06-23 11:00:13'),
(52, 40, 'diproses', '2026-06-23 11:00:36'),
(53, 41, 'diproses', '2026-06-23 11:00:46'),
(54, 42, 'diproses', '2026-06-23 11:01:06'),
(55, 43, 'diproses', '2026-06-23 11:01:17'),
(56, 44, 'pending', '2026-06-23 11:01:37'),
(57, 44, 'diproses', '2026-06-23 11:01:40'),
(58, 45, 'pending', '2026-06-23 11:02:01'),
(59, 45, 'diproses', '2026-06-23 11:02:02'),
(60, 46, 'pending', '2026-06-23 11:02:19'),
(61, 46, 'diproses', '2026-06-23 11:02:26'),
(62, 46, 'diproses', '2026-06-23 11:02:30'),
(63, 47, 'pending', '2026-06-23 11:03:02'),
(64, 48, 'pending', '2026-06-23 11:03:08'),
(65, 48, 'diproses', '2026-06-23 11:03:10'),
(66, 49, 'pending', '2026-06-23 11:03:32'),
(67, 49, 'diproses', '2026-06-23 11:03:34'),
(68, 50, 'pending', '2026-06-23 11:03:55'),
(69, 50, 'diproses', '2026-06-23 11:03:57'),
(70, 51, 'pending', '2026-06-23 11:05:27'),
(71, 51, 'diproses', '2026-06-23 11:05:28'),
(72, 52, 'pending', '2026-06-23 11:07:33'),
(73, 52, 'diproses', '2026-06-23 11:07:35'),
(74, 53, 'pending', '2026-06-23 11:07:56'),
(75, 53, 'diproses', '2026-06-23 11:07:59'),
(76, 54, 'diproses', '2026-06-23 11:08:09'),
(77, 55, 'pending', '2026-06-23 11:08:23'),
(78, 55, 'diproses', '2026-06-23 11:08:32'),
(79, 56, 'pending', '2026-06-23 11:09:02'),
(80, 56, 'diproses', '2026-06-23 11:09:06'),
(81, 57, 'pending', '2026-06-23 11:10:14'),
(82, 57, 'diproses', '2026-06-23 11:10:15'),
(83, 58, 'pending', '2026-06-23 11:11:13'),
(84, 58, 'diproses', '2026-06-23 11:11:15'),
(85, 59, 'pending', '2026-06-23 11:12:38'),
(86, 60, 'pending', '2026-06-23 11:13:05'),
(87, 61, 'pending', '2026-06-23 11:13:36'),
(88, 62, 'diproses', '2026-06-23 11:13:43'),
(89, 63, 'pending', '2026-06-23 11:13:55'),
(90, 63, 'diproses', '2026-06-23 11:14:00'),
(91, 64, 'diproses', '2026-06-23 11:20:22'),
(92, 65, 'pending', '2026-06-23 11:20:32'),
(93, 65, 'diproses', '2026-06-23 11:20:36'),
(94, 66, 'pending', '2026-06-23 11:20:45'),
(95, 66, 'diproses', '2026-06-23 11:20:48'),
(96, 67, 'pending', '2026-06-23 11:20:55'),
(97, 67, 'diproses', '2026-06-23 11:21:02');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `metode` varchar(50) DEFAULT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','berhasil','gagal') DEFAULT 'menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `berat` decimal(8,2) DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `asal_produk` varchar(100) DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `nama_produk`, `slug`, `kategori`, `deskripsi`, `harga`, `stok`, `berat`, `satuan`, `asal_produk`, `badge`, `status`, `created_at`, `updated_at`) VALUES
(7, 1, 'Ikan Bawal', 'ikan-bawal', 'Ikan', 'Bawal laut adalah sebutan untuk ikan laut dalam famili Bramidae. Ikan ini dapat ditemukan di Kepulauan Hawaii dan sejumlah daerah di Indonesia. Bawal laut hidup berkoloni dan termasuk jenis ikan pemangsa. Ikan ini jauh berbeda dengan ikan bawal air tawar yang termasuk ke dalam famili Serrasalmidae.', 50000.00, 8, 1.00, 'kg', 'Tangkapan Harian', '', 'aktif', '2026-06-13 02:23:03', NULL),
(8, 1, 'Kepiting Soka', 'kepiting-soka', 'Kepiting', 'Kepiting soka atau kepiting cangkang lunak adalah sebuah istilah kuliner untuk kepiting-kepiting yang baru melepas kulit lamanya dan masih lunak. Cangkang lunak diangkat dari air agar cangkang mereka tak mengeras.', 60000.00, 20, 1.00, 'kg', 'Budidaya Air Tawar', 'TERLARIS', 'aktif', '2026-06-13 02:33:04', NULL),
(9, 1, 'Ikan Bandeng', 'ikan-bandeng', 'Ikan', 'Ikan bandeng adalah ikan pangan populer di Asia Tenggara dan Oseania. Ikan ini merupakan satu-satunya spesies yang masih ada dalam famili Chanidae.', 40000.00, 12, 1.00, 'kg', 'Budidaya Air Tawar', 'TERLARIS', 'aktif', '2026-06-13 03:12:19', NULL),
(10, 5, 'Ikan Kiper', 'ikan-kiper', 'Ikan', 'Ikan kiper atau Ketang-ketang adalah satu dari tiga spesies ikan dalam genus Scatophagus, famili Scatophagidae. Ikan ini umumnya tersebar di sekitar kawasan Indo-Pasifik, hingga Jepang, Papua, dan Australia tenggara.', 40000.00, 14, 1.00, 'kg', 'Tangkapan Harian', 'TERLARIS', 'aktif', '2026-06-15 10:52:28', NULL),
(11, 5, 'Ikan Tongkol', 'ikan-tongkol', 'Ikan', 'Tongkol Komo adalah golongan ikan tuna kecil dengan ciri badan memanjang, tidak memiliki sisik dengan tektur sirip punggung keras. Ikan ini termasuk dalam famili Scombridae bergenus Euthynnus ini mempunyai ukuran tubuh cukup besar, kulit berwarna abu-abu, dan berdaging tebal berwarna merah tua.', 30000.00, 38, 1.00, 'kg', 'Tangkapan Harian', 'TERLARIS', 'aktif', '2026-06-15 11:00:08', NULL),
(12, 5, 'Udang Tiger', 'udang-tiger', 'Udang', 'Penaeus monodon atau giant tiger prawn, Asian tiger shrimp, black tiger shrimp, adalah sebuah crustaces yang dibudidayakan secara luas untuk dikunsumsi. Di Indonesia, udang ini disebut udang pancet atau udang windu.', 50000.00, 28, 1.00, 'kg', 'Budidaya Air Tawar', 'TERLARIS', 'aktif', '2026-06-15 11:31:25', NULL),
(13, 5, 'Ikan Kembung', 'ikan-kembung', 'Ikan', 'Ikan Kembung adalah nama sekelompok ikan laut yang tergolong ke dalam genus Rastrelliger, famili Scombridae. Meskipun bertubuh kecil, ikan ini masih sekerabat dengan tenggiri, tongkol, tuna, madidihang, dan makerel. Di Sumatera Barat dikenal sebagai ikan Gembolo/gambolo.', 25000.00, 24, 1.00, 'kg', 'Tangkapan Harian', '', 'aktif', '2026-06-15 11:39:16', NULL),
(14, 5, 'Ikan Lele', 'ikan-lele', 'Ikan', 'Bangsa Siluriformes mencakup semua kelompok ikan yang secara bebas disebut sebagai ikan berkumis atau lazim disebut lele atau patin. Namanya muncul karena adanya organ pengindra tambahan di sekitar moncongnya yang tampak seperti kumis kucing.', 25000.00, 7, 1.00, 'kg', 'Budidaya Air Tawar', 'PROMO', 'aktif', '2026-06-15 11:41:27', NULL),
(15, 5, 'Ikan Tuna', 'ikan-tuna', 'Ikan', 'Ikan laut berukuran besar dengan daging merah yang padat, kaya protein dan omega-3.', 45000.00, 17, 1.00, 'kg', 'Tangkapan Harian', 'TERLARIS', 'aktif', '2026-06-17 11:15:41', NULL),
(16, 5, 'Ikan Cakalang', 'ikan-cakalang', 'Ikan', 'Ikan laut populer dengan tekstur daging padat, sering diolah menjadi ikan asap dan masakan khas Nusantara.', 35000.00, 10, 1.00, 'kg', 'Tangkapan Harian', 'BARU', 'aktif', '2026-06-17 11:17:05', NULL),
(17, 5, 'Ikan Kakap Merah', 'ikan-kakap-merah', 'Ikan', 'Ikan premium dengan daging putih lembut dan sedikit duri.', 40000.00, 9, 0.00, 'kg', 'Tangkapan Harian', 'TERLARIS', 'aktif', '2026-06-17 11:18:14', NULL),
(18, 5, 'Ikan Kakap Putih', 'ikan-kakap-putih', 'Ikan', 'Ikan dengan daging lembut dan rasa ringan, cocok untuk berbagai olahan masakan.', 50000.00, 20, 0.00, 'kg', 'Budidaya Air Tawar', 'TERLARIS', 'aktif', '2026-06-17 11:19:37', NULL),
(19, 5, 'Ikan Kerapu', 'ikan-kerapu', 'Ikan', 'Ikan bernilai ekonomi tinggi dengan tekstur daging kenyal dan gurih.', 40000.00, 10, 0.00, 'kg', 'Budidaya Air Tawar', 'TERLARIS', 'aktif', '2026-06-17 11:21:07', NULL),
(20, 5, 'Ikan Nila', 'ikan-nila', 'Ikan', 'Ikan berdaging putih yang lembut dan mudah diolah menjadi berbagai masakan.', 20000.00, 100, 0.00, 'kg', 'Budidaya Air Tawar', 'TERLARIS', 'aktif', '2026-06-17 11:22:46', NULL),
(21, 5, 'Ikan Gurame', 'ikan-gurame', 'Ikan', 'Ikan air tawar premium yang sering disajikan di restoran.', 30000.00, 5, 0.00, 'kg', 'Budidaya Air Tawar', 'TERLARIS', 'aktif', '2026-06-17 11:24:15', NULL),
(22, 5, 'Ikan Patin', 'ikan-patin', 'Ikan', 'Ikan dengan tekstur lembut dan rasa gurih, populer untuk masakan berkuah.', 30000.00, 19, 0.00, 'kg', 'Budidaya Air Tawar', '', 'aktif', '2026-06-17 11:25:49', NULL),
(23, 5, 'Ikan Selar', 'ikan-selar', 'Ikan', 'Ikan laut kecil dengan rasa gurih dan tekstur daging padat.', 20000.00, 1, 0.00, 'kg', 'Tangkapan Harian', 'TERLARIS', 'aktif', '2026-06-17 11:26:49', NULL),
(24, 5, 'Udang Vaname', 'udang-vaname', 'Udang', 'Udang paling populer di Indonesia dengan daging kenyal dan produksi tinggi.', 40000.00, 60, 0.00, 'kg', 'Budidaya Air Tawar', '', 'aktif', '2026-06-17 11:30:55', NULL),
(25, 5, 'Udang Windu', 'udang-windu', 'Udang', 'Udang berukuran besar dengan rasa manis dan tekstur daging padat.', 30000.00, 40, 0.00, 'kg', 'Budidaya Air Tawar', '', 'aktif', '2026-06-17 11:32:03', NULL),
(26, 5, 'Udang Galah', 'udang-galah', 'Udang', 'Udang air tawar besar dengan capit panjang dan rasa manis.', 40000.00, 60, 0.00, 'kg', 'Budidaya Air Tawar', '', 'aktif', '2026-06-17 11:33:19', NULL),
(27, 5, 'Kerang Hijau', 'kerang-hijau', 'Kerang', 'Kerang dengan daging lembut dan rasa gurih yang populer di Indonesia.', 50000.00, 30, 0.00, 'kg', 'Tangkapan Harian', '', 'aktif', '2026-06-17 11:35:11', NULL),
(28, 5, 'Kerang Dara', 'kerang-dara', 'Kerang', 'Kerang bercangkang tebal dengan rasa manis dan tekstur kenyal.', 30000.00, 100, 0.00, 'kg', 'Tangkapan Harian', '', 'aktif', '2026-06-17 11:36:19', NULL),
(29, 5, 'Kerang Bambu', 'kerang-bambu', 'Kerang', 'Kerang berbentuk panjang dengan daging yang lembut.', 40000.00, 40, 0.00, 'kg', 'Tangkapan Harian', '', 'aktif', '2026-06-17 11:37:26', NULL),
(30, 5, 'Cumi-cumi', 'cumi-cumi', 'Cumi', 'Cumi segar hasil tangkapan nelayan dengan tekstur kenyal dan rasa manis alami.', 55000.00, 12, 0.00, 'kg', 'Tangkapan Harian', 'TERLARIS', 'aktif', '2026-06-17 11:40:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `nama_file`) VALUES
(7, 7, 'ikan-bawal-1781317383-1.jpg'),
(8, 7, 'ikan-bawal-1781317383-2.jpg'),
(9, 7, 'ikan-bawal-1781317383-3.jpg'),
(10, 8, 'kepitin-soka-1781317984-1.jpg'),
(11, 8, 'kepitin-soka-1781317984-2.jpg'),
(13, 9, 'ikan-bandeng-1781320339-1.jpg'),
(14, 9, 'ikan-bandeng-1781320339-2.jpg'),
(15, 9, 'ikan-bandeng-1781320339-3.jpg'),
(16, 10, 'ikan-kiper-1781520748-1.jpg'),
(17, 10, 'ikan-kiper-1781520748-2.jpg'),
(18, 11, 'ikan-tongkol-1781521208-1.jpg'),
(19, 11, 'ikan-tongkol-1781521208-2.jpg'),
(20, 11, 'ikan-tongkol-1781521208-3.jpg'),
(21, 12, 'udang-tiger-1781523085-1.jpg'),
(22, 12, 'udang-tiger-1781523085-2.jpg'),
(23, 12, 'udang-tiger-1781523085-3.jpg'),
(24, 13, 'ikan-kembung-1781523556-1.jpg'),
(25, 13, 'ikan-kembung-1781523556-2.jpg'),
(26, 14, 'ikan-lele-1781523687-1.jpg'),
(27, 14, 'ikan-lele-1781523687-2.jpg'),
(28, 14, 'ikan-lele-1781523687-3.jpg'),
(29, 15, 'ikan-tuna-1781694941-1.jpg'),
(30, 15, 'ikan-tuna-1781694941-2.jpg'),
(31, 16, 'ikan-cakalang-1781695025-1.jpg'),
(32, 16, 'ikan-cakalang-1781695025-2.jpg'),
(33, 17, 'ikan-kakap-merah-1781695094-1.jpg'),
(34, 17, 'ikan-kakap-merah-1781695094-2.jpg'),
(35, 18, 'ikan-kakap-putih-1781695177-1.jpg'),
(36, 19, 'ikan-kerapu-1781695267-1.jpg'),
(37, 20, 'ikan-nila-1781695366-1.jpg'),
(38, 21, 'ikan-gurame-1781695455-1.jpg'),
(39, 22, 'ikan-patin-1781695549-1.jpg'),
(40, 23, 'ikan-selar-1781695609-1.webp'),
(41, 24, 'udang-vaname-1781695855-1.jpg'),
(42, 24, 'udang-vaname-1781695855-2.jpg'),
(43, 25, 'udang-windu-1781695923-1.jpg'),
(44, 25, 'udang-windu-1781695923-2.jpg'),
(45, 26, 'udang-galah-1781695999-1.jpg'),
(46, 27, 'kerang-hijau-1781696111-1.jpg'),
(47, 27, 'kerang-hijau-1781696111-2.jpg'),
(48, 28, 'kerang-dara-1781696179-1.jpg'),
(49, 28, 'kerang-dara-1781696179-2.jpg'),
(50, 29, 'kerang-bambu-1781696246-1.jpg'),
(51, 30, 'cumi-cumi-1781696400-1.jpg'),
(52, 30, 'cumi-cumi-1781696400-2.jpg'),
(53, 30, 'cumi-cumi-1781696400-3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('buyer','seller') DEFAULT 'buyer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `no_hp`, `password`, `role`, `created_at`) VALUES
(1, 'dimasadp', NULL, '082253557299', '$2y$10$4jiS2yX8cay5XBWgLbfFPuyPjQ0O22DqPcqYnu2jMWhgq0P3JYoJm', 'seller', '2026-06-08 13:07:29'),
(2, 'ikhwan', 'ikhwan@gmail.com', NULL, '$2y$10$JBliRLNZK1P4muNE2wxXyudIG7lPQNW9y0Er8prv70OPMl2CmrZY.', 'buyer', '2026-06-08 13:22:28'),
(3, 'dimasa', 'dimas@gmail.com', NULL, '$2y$10$F5HpE7J3Cy4rfNW1BzOq0uGlpeYDZBLnaRbYMVRxCqOTE5gVKmhpa', 'buyer', '2026-06-08 13:28:51'),
(4, 'Person 1', NULL, '081346442454', '$2y$10$Fx2emkpme.dwQ3onAZ5z2O6iDt1ZsQgBR.J.QeK83nWgx8ivfRt22', 'buyer', '2026-06-08 13:34:33'),
(5, 'Penjual1', 'wan@gmail.com', NULL, '$2y$10$gLcNuXdwyt7NyzNm.tOQmuli5RSws023zzpsbQuAEMNXuGlPHf.zm', 'seller', '2026-06-15 10:31:34'),
(6, 'person3', NULL, '082351728383', '$2y$10$vjgaPI9fbcc.nWvTQ7v8fOJ.g.MGcacu9m60ZPNU1j4AyqavG/rpa', 'buyer', '2026-06-19 11:06:31'),
(7, 'person', NULL, '082355555555', '$2y$10$xn2nw4l5hvqlvdhMz.A1LuQCi/CTGpTEUYzcFdJ.O48jRhJWZpvqG', 'buyer', '2026-06-19 11:32:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_addresses_user` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_addresses` (`alamat_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_status_logs`
--
ALTER TABLE `order_status_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_products_users` (`user_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `no_hp` (`no_hp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `order_status_logs`
--
ALTER TABLE `order_status_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `fk_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_addresses` FOREIGN KEY (`alamat_id`) REFERENCES `addresses` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
