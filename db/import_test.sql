-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2022 at 04:26 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `import_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `logintoken`
--

CREATE TABLE `logintoken` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `token` int(50) DEFAULT NULL,
  `createAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logintoken`
--

INSERT INTO `logintoken` (`id`, `userId`, `token`, `createAt`) VALUES
(5, 54, 39, '2022-03-12 11:32:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `forgotToken` varchar(100) DEFAULT NULL,
  `activeToken` varchar(100) NOT NULL,
  `status` tinyint(4) DEFAULT 0,
  `createAt` datetime DEFAULT NULL,
  `updateAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `fullname`, `phone`, `password`, `forgotToken`, `activeToken`, `status`, `createAt`, `updateAt`) VALUES
(54, 'duclong1619@gmail.com', 'PHẠM ĐỨC LONG', '0362494015', '$2y$10$M4x0db9X1M7njD4wtBr96uKHAoUdl70tB1ckAPxeYvXhTXNZU6rvO', 'fdffa98ec499c4f23a4ba5a7b9e99b3fac401676', '', 0, '2022-03-13 10:25:27', '2022-03-13 10:25:27'),
(55, 'duclong16@gmail.com', 'nguyen bbb', '0362494015', '$2y$10$ZxM42USS9u8RcZOGJ57NZuFcrjREuIz18SQ1QTpAx5kJFzcg2bFii', 'fdffa98ec499c4f23a4ba5a7b9e99b3fac401676', '', 0, '2022-03-13 10:26:11', '2022-03-13 10:26:11'),
(57, 'nguyenvanee@gmail.com', 'nguyễn van ee', '0362494015', '$2y$10$CTn0vmt2svRVCJdQ8KHonOqxvbjU.YqJHXx8fN382wVNloREgaiUu', 'fdffa98ec499c4f23a4ba5a7b9e99b3fac401676', '', 0, '2022-03-13 11:57:58', '2022-03-13 11:57:58'),
(58, 'duc@gmail.com', 'nguyễn van duc', '0362494015', '$2y$10$ZxM42USS9u8RcZOGJ57NZuFcrjREuIz18SQ1QTpAx5kJFzcg2bFii', 'fdffa98ec499c4f23a4ba5a7b9e99b3fac401676', '', 1, '2022-03-12 11:43:40', '2022-03-12 11:43:40'),
(59, 'nguyenvana1@gmail.com', 'nguyen van a1', '0362494015', '$2y$10$M4x0db9X1M7njD4wtBr96uKHAoUdl70tB1ckAPxeYvXhTXNZU6rvO', 'fdffa98ec499c4f23a4ba5a7b9e99b3fac401676', '', 1, '2022-03-13 10:25:27', '2022-03-13 10:25:27'),
(60, 'nguyenvana2@gmail.com', 'nguyen van a2', '0362494015', '$2y$10$ZxM42USS9u8RcZOGJ57NZuFcrjREuIz18SQ1QTpAx5kJFzcg2bFii', 'fdffa98ec499c4f23a4ba5a7b9e99b3fac401676', '', 1, '2022-03-13 10:26:11', '2022-03-13 10:26:11'),
(62, 'nguyenvana3@gmail.com', 'nguyen van a3', '0362494015', '$2y$10$ZxM42USS9u8RcZOGJ57NZuFcrjREuIz18SQ1QTpAx5kJFzcg2bFii', 'fdffa98ec499c4f23a4ba5a7b9e99b3fac401676', '', 0, '2022-03-12 11:43:40', '2022-03-12 11:43:40'),
(63, 'nguyenvana4@gmail.com', 'nguyen van a4', '0362494015', '$2y$10$ZxM42USS9u8RcZOGJ57NZuFcrjREuIz18SQ1QTpAx5kJFzcg2bFii', 'fdffa98ec499c4f23a4ba5a7b9e99b3fac401676', '', 1, '2022-03-12 11:43:40', '2022-03-12 11:43:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logintoken`
--
ALTER TABLE `logintoken`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logintoken`
--
ALTER TABLE `logintoken`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logintoken`
--
ALTER TABLE `logintoken`
  ADD CONSTRAINT `logintoken_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
