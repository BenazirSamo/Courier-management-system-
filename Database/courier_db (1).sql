-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2026 at 04:46 AM
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
-- Database: `courier_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `user_id`, `branch_name`, `city`, `phone`) VALUES
(14, 18, 'karachi', 'karachi', '03330272515'),
(15, 19, 'lahore branch', 'lahore', '03358973458'),
(18, 16, 'karachi', 'malir', '03456789876'),
(25, 14, 'Lahore Branch', 'Lahore', '0300-7654321'),
(26, 2, 'Main Branch', 'Karachi', '0300-0000000'),
(27, 18, 'tcs', 'karachi', '64654646'),
(28, 19, 'main branch', 'karachi', '+92 9203009259');

-- --------------------------------------------------------

--
-- Table structure for table `couriers`
--

CREATE TABLE `couriers` (
  `id` int(11) NOT NULL,
  `tracking_no` varchar(50) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `from_city` varchar(100) NOT NULL,
  `to_city` varchar(100) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'Booked',
  `booking_date` date DEFAULT curdate(),
  `delivery_date` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `couriers`
--

INSERT INTO `couriers` (`id`, `tracking_no`, `sender_id`, `receiver_id`, `from_city`, `to_city`, `agent_id`, `status`, `booking_date`, `delivery_date`, `price`) VALUES
(13, 'TRK1769516069869', 5, 1, 'malir', 'kala board', 14, 'Delivered', '2026-01-27', NULL, 30000.00),
(14, 'TRK1769516407675', 1, 5, 'karachi ', 'lahore', 15, 'Delivered', '2026-01-27', NULL, 10000.00),
(41, 'TRK202602056390', 63, 64, 'Lahore', 'Karachi', 26, 'Booked', '2026-02-05', '2026-02-12', 10000.00),
(42, 'TRK202602052835', 65, 66, 'Karachi', 'Islamabad', 26, 'Delivered', '2026-02-05', NULL, 20000.00),
(43, 'TRK202602056791', 67, 68, 'Karachi', 'Sialkot', 26, 'Delivered', '2026-02-05', NULL, 10000.00),
(44, 'TRK1770356511146', 5, 65, 'karachi', 'hyderabad', 25, 'Delivered', '2026-02-06', NULL, 10000.00),
(51, 'TRK202602112183', 76, 77, 'Karachi', 'Hyderabad', 26, 'Booked', '2026-02-11', NULL, 10000.00),
(52, 'TRK202602129806', 78, 79, 'Gujranwala', 'Hyderabad', 26, 'Booked', '2026-02-12', NULL, 20000.00),
(54, 'TRK202602139546', 80, 81, 'Peshawar', 'Quetta', 26, 'Delivered', '2026-02-13', '2026-02-18', 30000.00),
(55, 'TRK202602136210', 82, 83, 'Peshawar', 'Quetta', 26, 'In Transit', '2026-02-13', NULL, 30000.00),
(59, 'TRK1770991328672', 74, 80, 'hyderabad', 'karachi', 15, 'In Transit', '2026-02-13', NULL, 60000.00),
(60, 'TRK1771058497527', 83, 84, 'hyderabad', 'karachi', 15, 'Booked', '2026-02-14', NULL, 10000.00),
(61, 'TRK1771220395468', 82, 74, 'hyderabad', 'karachi', 15, 'In Transit', '2026-02-16', NULL, 20000.00),
(62, 'TRK202602166456', 89, 90, 'Karachi', 'Hyderabad', 26, 'Delivered', '2026-02-16', '2026-02-16', 60000.00),
(63, 'TRK202602163919', 91, 92, 'Islamabad', 'Karachi', 26, 'In Transit', '2026-02-16', NULL, 5000.00),
(64, 'TRK1771395379563', 84, 90, 'hyderabad', 'karachi', 25, 'Delivered', '2026-02-18', NULL, 10000.00);

-- --------------------------------------------------------

--
-- Table structure for table `courier_status`
--

CREATE TABLE `courier_status` (
  `id` int(11) NOT NULL,
  `courier_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courier_status`
--

INSERT INTO `courier_status` (`id`, `courier_id`, `status`, `location`, `updated_at`) VALUES
(5, 41, 'Booked', 'Lahore', '2026-02-05 17:59:17'),
(6, 42, 'Booked', 'Karachi', '2026-02-05 18:00:00'),
(7, 43, 'Booked', 'Karachi', '2026-02-05 18:00:49'),
(10, 51, 'Booked', 'Karachi', '2026-02-11 04:51:21'),
(11, 52, 'Booked', 'Gujranwala', '2026-02-12 14:19:23'),
(12, 41, 'Delivered', 'Gujranwala', '2026-02-12 14:33:57'),
(13, 54, 'Booked', 'Peshawar', '2026-02-13 12:01:56'),
(14, 55, 'Booked', 'Peshawar', '2026-02-13 12:18:07'),
(17, 54, 'Cancelled', 'In Warehouse', '2026-02-13 12:44:47'),
(18, 55, 'In Transit', 'Quetta', '2026-02-13 13:40:10'),
(19, 62, 'Booked', 'Karachi', '2026-02-16 05:46:13'),
(20, 62, 'Delivered', 'Hyderabad', '2026-02-16 05:50:52'),
(21, 63, 'Booked', 'Islamabad', '2026-02-16 06:55:25'),
(22, 63, 'In Transit', 'Karachi', '2026-02-16 08:11:42'),
(23, 41, 'In Transit', 'Karachi', '2026-02-17 15:43:47'),
(24, 54, 'Delivered', 'Quetta', '2026-02-18 04:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `address`, `email`) VALUES
(1, 'Ali', '03001234569', 'Karachi', 'ali@email.com'),
(5, 'Benazir', '03303333333', 'lahore', 'benazir@gmail.com'),
(6, 'sara', '03330272515', 'hyderabad', 'sara@gmail.com'),
(63, 'babo', '03345678971', 'lahore', 'babo@gmaill.com'),
(64, 'Ali', '03333333333', 'karachi', 'ali@email.com'),
(65, 'Ali', '03333333333', 'karachi', 'ali@email.com'),
(66, 'sara', '03330272515', 'hyderabad', 'sara@gmail.com'),
(67, 'sara', '03330272515', 'hyderabad', 'sara@gmail.com'),
(68, 'Benazir', '03303333333', 'lahore', 'benazir@gmail.com'),
(71, 'Benazir', '03303333333', 'karachi', 'benazirsamo23456780@gmail.com'),
(74, 'amjad', '+92 9292924564', 'karachi', 'amjad@gmailcom'),
(75, 'ali', '+92 9292929292', 'karachi', 'benazirsamo70@gmail.com'),
(76, 'gull', '03333333333', 'karachi', 'Gull123@gmail.com'),
(77, 'sara', '03345678971', 'hyderabad', 'sara1432@gmail.com'),
(78, 'sara', '03345678971', 'hyderabad', 'sara1432@gmail.com'),
(79, 'huma', '03303333333', 'gujranwala', 'huma7488540@gmail.com'),
(80, 'ahmed', '03345678971', 'karachi', 'Ahmed@gmail.com'),
(81, 'ali', '03333333333', 'hyderabad', 'ali89@gmail.com'),
(82, 'ahmed', '03345678971', 'karachi', 'Ahmed@gmail.com'),
(83, 'ali', '03333333333', 'hyderabad', 'ali89@gmail.com'),
(84, 'ahmed', '03345678971', 'karachi', 'Ahmed@gmail.com'),
(85, 'ali', '03333333333', 'hyderabad', 'ali89@gmail.com'),
(86, 'sara', '03333333333', 'karachi', 'sara@gmail.com'),
(88, '  Dhani', '03009259343', 'karachi', 'samo@gmail.com'),
(89, 'gull', '03330272515', 'karachi', 'Gull123@gmail.com'),
(90, 'Alisha', '03001234569', 'hyderabad', 'Alisha@gmail.com'),
(91, 'user', '03345678971', 'islamabad', 'user@gmail.com'),
(92, 'benazir', '03330272515', 'karachi', 'benazir@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(4) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `city`, `created_at`) VALUES
(1, 'admin', 'admin@email.com', '123', 1, 'Karachi', '2026-02-01 06:14:05'),
(2, 'agent', 'agent@email.com', '123', 3, 'Lahore', '2026-02-01 06:14:05'),
(3, 'user', 'user@email.com', '123', 2, 'Islamabad', '2026-02-01 06:14:05'),
(13, 'Ali', 'ali@gmail.com', '123', 2, NULL, '2026-02-03 15:25:57'),
(14, 'samrah', 'samrah@gmail.com', '12345', 2, NULL, '2026-02-04 04:17:02'),
(16, 'new1', 'agent1@gmail.com', '321', 3, 'karachi', '2026-02-05 10:44:45'),
(17, 'faraz', 'fazar@gmail.com', '12345', 2, NULL, '2026-02-07 13:33:57'),
(18, 'ali', 'testali@gmail.com', '123', 3, 'karachi', '2026-02-11 04:29:53'),
(19, 'alisha', 'alisha234545@gmail.com', '123', 3, 'karachi', '2026-02-13 13:58:28'),
(20, 'saima', 'saima@gmail.com', '123', 2, NULL, '2026-02-17 16:20:59'),
(24, 'benazirSamo', 'benazirsamo@gmail.com', 'ASDFERGn1', 2, NULL, '2026-02-20 03:32:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `couriers`
--
ALTER TABLE `couriers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_no` (`tracking_no`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `courier_status`
--
ALTER TABLE `courier_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courier_id` (`courier_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `couriers`
--
ALTER TABLE `couriers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `courier_status`
--
ALTER TABLE `courier_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agents`
--
ALTER TABLE `agents`
  ADD CONSTRAINT `agents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `couriers`
--
ALTER TABLE `couriers`
  ADD CONSTRAINT `couriers_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `couriers_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `couriers_ibfk_3` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courier_status`
--
ALTER TABLE `courier_status`
  ADD CONSTRAINT `courier_status_ibfk_1` FOREIGN KEY (`courier_id`) REFERENCES `couriers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
