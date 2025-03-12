-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3308
-- Generation Time: Nov 18, 2024 at 07:53 PM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `numbersdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `number`
--

CREATE TABLE `number` (
  `id` int(11) NOT NULL,
  `number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `number`
--

INSERT INTO `number` (`id`, `number`) VALUES
(1396, '+94 76 339 7051');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'misal', 'misalsathsara042@gmail.com', '$2y$10$WNdXcEmLG8VNOdglnQX0JOKKBA46zgQ./CxQVGXJILhKqFUGKwh6y', '2024-09-01 04:25:38'),
(2, 'sathsara', 'sathsara@gmail.com', '$2y$10$WI8G52YZPCB0.WxIq6sYWOr4pLvUlua/Vv.IKDWAcL8FB0Mwh4juC', '2024-09-28 06:30:33'),
(3, 'misal2', 'misal@gmail.com', '$2y$10$J7IA/JyxCH3R9gTW5bR35ugk3CJKPdNk1amh3SSI0ia4E1U0Z5Ht.', '2024-10-05 03:20:56'),
(4, 'admin', 'admin@gmail.com', '$2y$10$TvxUfiIdq9BmVBj00L5hO.n7Xr2qoFcSKzlKybVZdH8sBhQaIsPIm', '2024-10-09 13:49:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `number`
--
ALTER TABLE `number`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `number`
--
ALTER TABLE `number`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1707;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
