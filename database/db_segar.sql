-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2026 at 03:37 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
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

INSERT INTO `users` (`id`, `username`, `email`, `no_hp`, `password`, `role`, `created_at`) VALUES
(1, 'dimasadp', NULL, '082253557299', '$2y$10$4jiS2yX8cay5XBWgLbfFPuyPjQ0O22DqPcqYnu2jMWhgq0P3JYoJm', 'buyer', '2026-06-08 13:07:29'),
(2, 'ikhwan', 'ikhwan@gmail.com', NULL, '$2y$10$JBliRLNZK1P4muNE2wxXyudIG7lPQNW9y0Er8prv70OPMl2CmrZY.', 'buyer', '2026-06-08 13:22:28'),
(3, 'dimasa', 'dimas@gmail.com', NULL, '$2y$10$F5HpE7J3Cy4rfNW1BzOq0uGlpeYDZBLnaRbYMVRxCqOTE5gVKmhpa', 'buyer', '2026-06-08 13:28:51'),
(4, 'Person 1', NULL, '081346442454', '$2y$10$Fx2emkpme.dwQ3onAZ5z2O6iDt1ZsQgBR.J.QeK83nWgx8ivfRt22', 'buyer', '2026-06-08 13:34:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `no_hp` (`no_hp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
