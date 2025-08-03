-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2025 at 12:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ywca`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `age_group` varchar(50) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `residential_address` text DEFAULT NULL,
  `communication_address` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `original_membership_year` int(11) DEFAULT NULL,
  `break_year` int(11) DEFAULT NULL,
  `committee_experience` text DEFAULT NULL,
  `office_bearer` text DEFAULT NULL,
  `board_member` text DEFAULT NULL,
  `voluntary_work` text DEFAULT NULL,
  `interest_areas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `dob`, `age_group`, `education`, `residential_address`, `communication_address`, `email`, `phone`, `nationality`, `occupation`, `original_membership_year`, `break_year`, `committee_experience`, `office_bearer`, `board_member`, `voluntary_work`, `interest_areas`) VALUES
(1, 'Martin ', '2025-01-03', '18-35 yrs', 'MCA', 'bfdshflkh', 'dsfdgfh', 'martinvishali2002@gmail.com', '7539930626', 'INDIA', 'Software Developer', 2004, 2008, 'dgfh', 'No', 'No', 'No', 'Community Development, Vocational Skills'),
(4, 'seeli', '2013-02-10', '18-35 yrs', 'MA', '123 CHENNAI', '123 CHENNAI', 'martinvishali200@gmail.com', '7539930690', 'INDIA', 'IT JOB', 2000, 2005, 'NILL', 'No', 'Yes', 'Yes', 'Young Women Programme, Education, Ecumenical Programmes');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_entries1`
--

CREATE TABLE `receipt_entries1` (
  `receipt_no` int(11) NOT NULL,
  `membership_type` varchar(50) NOT NULL,
  `member_type` varchar(50) NOT NULL,
  `member_name` varchar(100) NOT NULL,
  `father_husband_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `member_fee` decimal(10,2) NOT NULL CHECK (`member_fee` >= 0),
  `joining_fee` decimal(10,2) NOT NULL CHECK (`joining_fee` >= 0),
  `total_amount` decimal(10,2) NOT NULL CHECK (`total_amount` >= 0),
  `service_tax` tinyint(1) NOT NULL DEFAULT 0,
  `payment_by` char(50) NOT NULL,
  `received_date` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile_no` varchar(15) NOT NULL CHECK (`mobile_no` regexp '^[0-9]{10,15}$'),
  `dob` date NOT NULL,
  `date_of_joining` date NOT NULL,
  `occupation` varchar(50) DEFAULT NULL,
  `office_no` varchar(15) DEFAULT NULL CHECK (`office_no` regexp '^[0-9]{5,15}$'),
  `received` enum('Yes','No') NOT NULL,
  `type_of_received` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipt_entries1`
--

INSERT INTO `receipt_entries1` (`receipt_no`, `membership_type`, `member_type`, `member_name`, `father_husband_name`, `address`, `member_fee`, `joining_fee`, `total_amount`, `service_tax`, `payment_by`, `received_date`, `email`, `mobile_no`, `dob`, `date_of_joining`, `occupation`, `office_no`, `received`, `type_of_received`) VALUES
(1, 'Electoral', 'New', 'marvin', 'justin', '123,chennai', 456.00, 0.00, 538.08, 1, 'cash', '2025-01-31', 'martinvishali20@gmail.com', '7539930626', '2024-11-01', '2025-01-23', 'IT JOB', '1234512345', 'Yes', 'Cheque'),
(2, 'Electoral', 'New', 'seeli', 'Noah', '123,chennai', 245.00, 33.00, 328.04, 1, '0', '2025-01-23', 'martinvishali203@gmail.com', '7539930656', '2024-07-13', '2025-01-23', 'IT JOB', '1234567890', 'Yes', 'Cash'),
(3, 'Electoral', 'Renewal', 'seeli', 'Diraviam', 'chennai', 35.00, 56.00, 91.00, 1, 'cash', '2025-01-23', 'vishali2000@gmail.com', '1234554321', '2024-11-30', '2025-01-31', 'student', '6265897912', 'Yes', 'Cash'),
(4, 'Electoral', 'New', 'god', 'Noah', '123', 200.00, 0.00, 236.00, 1, 'Check', '2025-01-23', 'ajaj@gmail.com', '7657647373', '2024-11-08', '2025-02-02', 'CA', '1234567895', 'Yes', 'Cash'),
(5, 'Electoral', 'New', 'alis', 'Noah', '123,chennai', 200.00, 100.00, 300.00, 0, 'cheque', '2025-09-28', 'alish@gmail.com', '8765124356', '2024-05-04', '2025-01-30', 'student', '1234098765', 'Yes', 'Cheque');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `receipt_entries1`
--
ALTER TABLE `receipt_entries1`
  ADD PRIMARY KEY (`receipt_no`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile_no` (`mobile_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `receipt_entries1`
--
ALTER TABLE `receipt_entries1`
  MODIFY `receipt_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
