-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 18, 2019 at 01:58 PM
-- Server version: 5.7.26-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-0ubuntu0.16.04.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s259371`
--

-- --------------------------------------------------------

--
-- Table structure for table `Seats`
--

CREATE TABLE `Seats` (
  `seatRow` int(11) NOT NULL,
  `seatColumn` char(1) NOT NULL,
  `seatState` enum('purchased','free','selected') NOT NULL,
  `holdingUser` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Seats`
--

INSERT INTO `Seats` (`seatRow`, `seatColumn`, `seatState`, `holdingUser`) VALUES
(1, 'A', 'free', NULL),
(1, 'B', 'free', NULL),
(1, 'C', 'free', NULL),
(1, 'D', 'free', NULL),
(1, 'E', 'free', NULL),
(1, 'F', 'free', NULL),
(2, 'A', 'free', NULL),
(2, 'B', 'purchased', 'u2@p.it'),
(2, 'C', 'free', NULL),
(2, 'D', 'free', NULL),
(2, 'E', 'free', NULL),
(2, 'F', 'free', NULL),
(3, 'A', 'free', NULL),
(3, 'B', 'purchased', 'u2@p.it'),
(3, 'C', 'free', NULL),
(3, 'D', 'free', NULL),
(3, 'E', 'free', NULL),
(3, 'F', 'free', NULL),
(4, 'A', 'selected', 'u1@p.it'),
(4, 'B', 'purchased', 'u2@p.it'),
(4, 'C', 'free', NULL),
(4, 'D', 'selected', 'u1@p.it'),
(4, 'E', 'free', NULL),
(4, 'F', 'selected', 'u2@p.it'),
(5, 'A', 'free', NULL),
(5, 'B', 'free', NULL),
(5, 'C', 'free', NULL),
(5, 'D', 'free', NULL),
(5, 'E', 'free', NULL),
(5, 'F', 'free', NULL),
(6, 'A', 'free', NULL),
(6, 'B', 'free', NULL),
(6, 'C', 'free', NULL),
(6, 'D', 'free', NULL),
(6, 'E', 'free', NULL),
(6, 'F', 'free', NULL),
(7, 'A', 'free', NULL),
(7, 'B', 'free', NULL),
(7, 'C', 'free', NULL),
(7, 'D', 'free', NULL),
(7, 'E', 'free', NULL),
(7, 'F', 'free', NULL),
(8, 'A', 'free', NULL),
(8, 'B', 'free', NULL),
(8, 'C', 'free', NULL),
(8, 'D', 'free', NULL),
(8, 'E', 'free', NULL),
(8, 'F', 'free', NULL),
(9, 'A', 'free', NULL),
(9, 'B', 'free', NULL),
(9, 'C', 'free', NULL),
(9, 'D', 'free', NULL),
(9, 'E', 'free', NULL),
(9, 'F', 'free', NULL),
(10, 'A', 'free', NULL),
(10, 'B', 'free', NULL),
(10, 'C', 'free', NULL),
(10, 'D', 'free', NULL),
(10, 'E', 'free', NULL),
(10, 'F', 'free', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `userID` varchar(255) NOT NULL,
  `userPassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userID`, `userPassword`) VALUES
('u1@p.it', '$2y$10$jOMQO/TG4lUyV8I/KwUeMeUSkoRXjZkn0ZI.Tt8.UnaPU52mO8elq'),
('u2@p.it', '$2y$10$uKvBBNzRrsRNJHtCkJ0L0.OV2kKwt7QswHYvP06Wc4I1UAS/q3QNW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Seats`
--
ALTER TABLE `Seats`
  ADD PRIMARY KEY (`seatRow`,`seatColumn`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
