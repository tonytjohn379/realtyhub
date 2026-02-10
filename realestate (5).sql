-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 25, 2025 at 02:05 PM
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
-- Database: `realestate`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `date` date NOT NULL,
  `token` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `pid`, `bid`, `date`, `token`) VALUES
(1, 17, 1, '2025-10-21', 5000),
(2, 17, 1, '2025-10-21', 5000),
(3, 17, 1, '2025-10-21', 5000),
(4, 17, 1, '2025-10-21', 5000),
(5, 22, 1, '2025-10-21', 5000),
(6, 22, 1, '2025-10-21', 5000),
(7, 22, 1, '2025-10-21', 5000),
(8, 22, 1, '2025-10-21', 5000),
(9, 22, 1, '2025-10-21', 5000),
(10, 22, 1, '2025-10-22', 5000),
(11, 19, 1, '2025-10-22', 5000);

-- --------------------------------------------------------

--
-- Table structure for table `buyer`
--

CREATE TABLE `buyer` (
  `bid` int(11) NOT NULL,
  `bname` varchar(50) NOT NULL,
  `bphone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `bmail` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buyer`
--

INSERT INTO `buyer` (`bid`, `bname`, `bphone`, `password`, `bmail`, `created_at`, `status`) VALUES
(1, 'kiran', '47851236954', 'kiran1', 'kiran@gmail.com', '2025-10-18 14:30:40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `buyer_feedback`
--

CREATE TABLE `buyer_feedback` (
  `fid` int(11) UNSIGNED NOT NULL,
  `bid` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `feedback_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cid` int(11) NOT NULL,
  `cname` varchar(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cid`, `cname`, `status`) VALUES
(11, 'land ', 1),
(3, 'flat', 1),
(4, 'villa', 1),
(12, 'land', 1);

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `did` int(11) NOT NULL,
  `dname` varchar(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`did`, `dname`, `status`) VALUES
(8, 'ernakulam', 1),
(16, 'malappuram', 1),
(14, 'kollam', 1),
(11, 'idukki', 1),
(17, 'pathanamthitta', 1);

-- --------------------------------------------------------

--
-- Table structure for table `feedback1`
--

CREATE TABLE `feedback1` (
  `fid` int(11) UNSIGNED NOT NULL,
  `bid` int(11) NOT NULL,
  `rating` tinyint(11) NOT NULL,
  `feedback_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback1`
--

INSERT INTO `feedback1` (`fid`, `bid`, `rating`, `feedback_text`, `created_at`) VALUES
(1, 2, 4, 'happy be happy', '2025-10-20 17:45:30'),
(2, 1, 3, 'nice service', '2025-10-20 19:31:48'),
(3, 1, 1, 'really bad exp', '2025-10-21 16:59:33');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) UNSIGNED NOT NULL,
  `booking_id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `token_amount` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `booking_id`, `pid`, `bid`, `payment_date`, `token_amount`, `status`) VALUES
(1, 1, 17, 0, '2025-10-21 23:46:53', 5000, 'Completed'),
(2, 1, 22, 0, '2025-10-21 23:58:48', 5000, 'Completed'),
(3, 1, 22, 0, '2025-10-21 23:59:12', 5000, 'Completed'),
(4, 1, 22, 0, '2025-10-22 00:00:30', 5000, 'Completed'),
(5, 1, 22, 0, '2025-10-22 00:00:52', 5000, 'Completed'),
(6, 1, 22, 0, '2025-10-22 00:04:05', 5000, 'Completed'),
(7, 1, 22, 0, '2025-10-22 00:07:53', 5000, 'Completed'),
(8, 1, 22, 0, '2025-10-22 00:08:15', 5000, 'Completed'),
(9, 1, 22, 0, '2025-10-22 00:09:30', 5000, 'Completed'),
(10, 1, 22, 0, '2025-10-22 12:17:24', 5000, 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `pid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `did` int(11) NOT NULL,
  `pcent` int(11) DEFAULT NULL,
  `psqft` int(11) DEFAULT NULL,
  `pbhk` int(11) DEFAULT NULL,
  `plocation` varchar(50) NOT NULL,
  `pdescription` varchar(100) NOT NULL,
  `pprice` int(11) NOT NULL,
  `pimage` varchar(500) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`pid`, `sid`, `cid`, `did`, `pcent`, `psqft`, `pbhk`, `plocation`, `pdescription`, `pprice`, `pimage`, `status`) VALUES
(19, 20, 10, 14, 1000, 0, 0, 'kakkanad', 'football ground', 6000000, '36bb8f5c02dbf400939e10196a70e1bf_7202b8c4baaee8.JPG', 1),
(20, 20, 10, 14, 1000, 0, 0, 'kakkanad', 'football ground', 6000000, 'c4e7acb035c374018e2fc8fe728b85da_f54cfacdd1d80aac9.JPG', 1),
(21, 20, 4, 11, 10, 1200, 5, 'thodupuzha', 'near main road', 7500000, '11d708d9d0a778c08074f9978780cefb_d0e1c50ab0f823924.jpg', 1),
(24, 22, 12, 17, 2000, 0, 0, 'near police station', 'plot for flat', 3256222, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `sid` int(11) NOT NULL,
  `sname` varchar(50) NOT NULL,
  `sphone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `smail` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`sid`, `sname`, `sphone`, `password`, `smail`, `created_at`, `status`) VALUES
(18, 'anto', '2154879631', '123tony', 'anto@gmail.com', '2025-10-16 18:01:16', 1),
(19, 'arun123', '2563987413', 'arun1', 'arun@gmail.com', '2025-10-16 18:16:07', 1),
(22, 'anna mathew', '8598788888', 'anna1', 'anna@gmail.com', '2025-10-24 19:45:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `seller_feedback`
--

CREATE TABLE `seller_feedback` (
  `fid` int(11) UNSIGNED NOT NULL,
  `sid` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL CHECK (`rating` between 1 and 5),
  `feedback_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller_feedback`
--

INSERT INTO `seller_feedback` (`fid`, `sid`, `rating`, `feedback_text`, `created_at`) VALUES
(1, 19, 4, 'very good exp with realty hub', '2025-10-16 19:03:08'),
(6, 22, 4, 'kollam nice', '2025-10-24 20:53:02');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `added_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `buyer`
--
ALTER TABLE `buyer`
  ADD PRIMARY KEY (`bid`),
  ADD KEY `bname` (`bname`),
  ADD KEY `bphone` (`bphone`);

--
-- Indexes for table `buyer_feedback`
--
ALTER TABLE `buyer_feedback`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `bid` (`bid`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`did`);

--
-- Indexes for table `feedback1`
--
ALTER TABLE `feedback1`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`sid`),
  ADD UNIQUE KEY `username` (`sname`),
  ADD UNIQUE KEY `phone` (`sphone`);

--
-- Indexes for table `seller_feedback`
--
ALTER TABLE `seller_feedback`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `sid` (`sid`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wid`),
  ADD KEY `buyer_id` (`bid`),
  ADD KEY `property_id` (`pid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `buyer`
--
ALTER TABLE `buyer`
  MODIFY `bid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `buyer_feedback`
--
ALTER TABLE `buyer_feedback`
  MODIFY `fid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `did` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `feedback1`
--
ALTER TABLE `feedback1`
  MODIFY `fid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `seller_feedback`
--
ALTER TABLE `seller_feedback`
  MODIFY `fid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `seller_feedback`
--
ALTER TABLE `seller_feedback`
  ADD CONSTRAINT `seller_feedback_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `seller` (`sid`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `buyer` (`bid`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `property` (`pid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
