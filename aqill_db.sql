-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2026 at 12:31 PM
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
-- Database: `aqill_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `admin_username` varchar(50) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `admin_username`, `admin_password`, `created_at`) VALUES
(1, 'admin', 'admin123', '2026-06-17 03:06:36');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `state_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `state_name`) VALUES
(1, 'Perlis'),
(2, 'Kedah'),
(3, 'P. Pinang'),
(4, 'Perak'),
(5, 'Selangor'),
(6, 'Kuala Lumpur'),
(7, 'Negeri Sembilan'),
(8, 'Melaka'),
(9, 'Johor'),
(10, 'Pahang'),
(11, 'Terengganu'),
(12, 'Kelantan'),
(13, 'Sabah'),
(14, 'Sarawak');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `educational_background` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `gender`, `address`, `state`, `phone_number`, `email_address`, `educational_background`, `username`, `password`, `created_at`) VALUES
(1, 'Muhammad ', 'Danish', 'Male', '137, Taman Heliconia, Tapah, Perak', NULL, '014-651-4020', 'danish@gmail.com', 'Diploma, Degree', 'danish', '$2y$10$iaB2NIA0rmGDF8O2vmIWF.1sagFPNrC3rZxGS94pgNFPfAmYqWrJq', '2026-06-16 16:16:47'),
(2, 'irfan', 'romzi', 'Male', '265,Taman Desa Permai 2,09600,Lunas,Kedah', 'Kedah', '20115487965', 'irfan@gmail.com', 'SPM', 'irfan', 'irfan1111', '2026-06-17 03:29:33'),
(3, 'Muhammad', 'Sufi', 'Male', '265, Taman heliconia', 'Selangor', '0156987742', 'sufi@gmail.com', 'Degree', 'sufi', 'sufi222', '2026-06-17 09:09:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_username` (`admin_username`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_address` (`email_address`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
