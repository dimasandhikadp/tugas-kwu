-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2026 at 09:02 AM
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
(6, 1, 'Dimas Andhika Dwi Permana', '082253557299', 'Perum PNS Blok A No 456', 'Palangkaraya', 'Jekan Raya', 'Menteng', 'Kalimantan Tengah', 1);

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
(2, 1, '2026-06-17 05:46:17');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(7, 1, 'Ikan Bawal', 'ikan-bawal', 'Ikan', 'Bawal laut adalah sebutan untuk ikan laut dalam famili Bramidae. Ikan ini dapat ditemukan di Kepulauan Hawaii dan sejumlah daerah di Indonesia. Bawal laut hidup berkoloni dan termasuk jenis ikan pemangsa. Ikan ini jauh berbeda dengan ikan bawal air tawar yang termasuk ke dalam famili Serrasalmidae.', 50000.00, 9, 1.00, 'kg', 'Tangkapan Harian', '', 'aktif', '2026-06-13 02:23:03', NULL),
(8, 1, 'Kepiting Soka', 'kepiting-soka', 'Kepiting', 'Kepiting soka atau kepiting cangkang lunak adalah sebuah istilah kuliner untuk kepiting-kepiting yang baru melepas kulit lamanya dan masih lunak. Cangkang lunak diangkat dari air agar cangkang mereka tak mengeras.', 60000.00, 20, 1.00, 'kg', 'Budidaya Air Tawar', 'TERLARIS', 'aktif', '2026-06-13 02:33:04', NULL),
(9, 1, 'Ikan Bandeng', 'ikan-bandeng', 'Ikan', 'Ikan bandeng adalah ikan pangan populer di Asia Tenggara dan Oseania. Ikan ini merupakan satu-satunya spesies yang masih ada dalam famili Chanidae.', 40000.00, 12, 1.00, 'kg', 'Budidaya Air Tawar', 'TERLARIS', 'aktif', '2026-06-13 03:12:19', NULL),
(10, 5, 'Ikan Kiper', 'ikan-kiper', 'Ikan', 'Ikan kiper atau Ketang-ketang adalah satu dari tiga spesies ikan dalam genus Scatophagus, famili Scatophagidae. Ikan ini umumnya tersebar di sekitar kawasan Indo-Pasifik, hingga Jepang, Papua, dan Australia tenggara.', 40000.00, 14, 1.00, 'kg', 'Tangkapan Harian', 'TERLARIS', 'aktif', '2026-06-15 10:52:28', NULL),
(11, 5, 'Ikan Tongkol', 'ikan-tongkol', 'Ikan', 'Tongkol Komo adalah golongan ikan tuna kecil dengan ciri badan memanjang, tidak memiliki sisik dengan tektur sirip punggung keras. Ikan ini termasuk dalam famili Scombridae bergenus Euthynnus ini mempunyai ukuran tubuh cukup besar, kulit berwarna abu-abu, dan berdaging tebal berwarna merah tua.', 30000.00, 38, 1.00, 'kg', 'Tangkapan Harian', 'TERLARIS', 'aktif', '2026-06-15 11:00:08', NULL),
(12, 5, 'Udang Tiger', 'udang-tiger', 'Udang', 'Penaeus monodon atau giant tiger prawn, Asian tiger shrimp, black tiger shrimp, adalah sebuah crustaces yang dibudidayakan secara luas untuk dikunsumsi. Di Indonesia, udang ini disebut udang pancet atau udang windu.', 50000.00, 28, 1.00, 'kg', 'Budidaya Air Tawar', 'TERLARIS', 'aktif', '2026-06-15 11:31:25', NULL),
(13, 5, 'Ikan Kembung', 'ikan-kembung', 'Ikan', 'Ikan Kembung adalah nama sekelompok ikan laut yang tergolong ke dalam genus Rastrelliger, famili Scombridae. Meskipun bertubuh kecil, ikan ini masih sekerabat dengan tenggiri, tongkol, tuna, madidihang, dan makerel. Di Sumatera Barat dikenal sebagai ikan Gembolo/gambolo.', 25000.00, 29, 1.00, 'kg', 'Tangkapan Harian', '', 'aktif', '2026-06-15 11:39:16', NULL),
(14, 5, 'Ikan Lele', 'ikan-lele', 'Ikan', 'Bangsa Siluriformes mencakup semua kelompok ikan yang secara bebas disebut sebagai ikan berkumis atau lazim disebut lele atau patin. Namanya muncul karena adanya organ pengindra tambahan di sekitar moncongnya yang tampak seperti kumis kucing.', 25000.00, 18, 1.00, 'kg', 'Budidaya Air Tawar', 'PROMO', 'aktif', '2026-06-15 11:41:27', NULL);

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
(28, 14, 'ikan-lele-1781523687-3.jpg');

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
(5, 'Penjual1', 'wan@gmail.com', NULL, '$2y$10$gLcNuXdwyt7NyzNm.tOQmuli5RSws023zzpsbQuAEMNXuGlPHf.zm', 'seller', '2026-06-15 10:31:34');

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_logs`
--
ALTER TABLE `order_status_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
