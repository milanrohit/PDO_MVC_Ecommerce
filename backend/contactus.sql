-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2025 at 03:29 PM
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
-- Database: `phpecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `contactus_id` int(4) NOT NULL,
  `contactus_name` varchar(150) NOT NULL,
  `contactus_email` varchar(100) NOT NULL,
  `contactus_mobile` int(11) NOT NULL,
  `contactus_status` enum('A','N','D') NOT NULL DEFAULT 'N',
  `contactus_comment` varchar(250) NOT NULL,
  `contactus_add_datetime` datetime NOT NULL,
  `contactus_update_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactus`
--

INSERT INTO `contactus` (`contactus_id`, `contactus_name`, `contactus_email`, `contactus_mobile`, `contactus_status`, `contactus_comment`, `contactus_add_datetime`, `contactus_update_datetime`) VALUES
(1, 'milan', 'rohit.milan3@gmail.com', 141810925, 'N', 'this is contactus comment section', '2025-02-03 15:03:19', '2025-02-03 15:03:19'),
(2, 'mayur', 'rohit.mayur@gmail.com', 638200987, 'N', '', '2025-02-03 15:03:19', '2025-02-03 15:03:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`contactus_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `contactus_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
