-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 25, 2026 at 11:11 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vaccination`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `bid` int NOT NULL AUTO_INCREMENT,
  `cid` int NOT NULL,
  `vid` int NOT NULL,
  `hid` int NOT NULL,
  `cfirstname` varchar(50) NOT NULL,
  `cur_date` date NOT NULL,
  `book_date` date NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`bid`, `cid`, `vid`, `hid`, `cfirstname`, `cur_date`, `book_date`, `status`) VALUES
(1, 2, 1, 2, 'testa', '2025-11-21', '2025-11-22', 0),
(2, 3, 1, 2, 'testab', '2025-11-21', '2025-11-28', 0),
(3, 4, 1, 1, 'kruthika', '2025-12-19', '2025-12-19', 0),
(4, 5, 1, 1, 'prahalad', '2025-12-19', '2025-12-19', 0),
(5, 6, 1, 1, 'test', '2026-03-23', '2026-03-29', 0),
(6, 7, 1, 1, 'test', '2026-03-23', '2026-03-23', 0),
(7, 8, 1, 1, 'mithun', '2026-03-23', '2026-03-24', 1),
(8, 9, 1, 2, 'nagu', '2026-03-23', '2026-03-23', 1),
(9, 10, 1, 2, 'mithun', '2026-03-23', '2026-03-24', 1),
(10, 11, 1, 2, 'kruthika', '2026-03-23', '2026-03-24', 1),
(11, 12, 1, 1, 'test', '2026-03-25', '2026-03-25', 0),
(12, 13, 1, 1, 'test', '2026-03-25', '2026-03-25', 0);

-- --------------------------------------------------------

--
-- Table structure for table `child`
--

DROP TABLE IF EXISTS `child`;
CREATE TABLE IF NOT EXISTS `child` (
  `cid` int NOT NULL AUTO_INCREMENT,
  `cfirstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `clastname` varchar(50) NOT NULL,
  `pid` int NOT NULL,
  `gender` varchar(7) NOT NULL,
  `dob` date NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `child`
--

INSERT INTO `child` (`cid`, `cfirstname`, `clastname`, `pid`, `gender`, `dob`) VALUES
(1, 'test', 'test', 0, 'm', '2025-11-01'),
(2, 'testa', 'testa', 4, 'f', '2025-11-01'),
(3, 'testab', 'testab', 4, 'm', '2025-11-01'),
(4, 'kruthika', 'kruthika', 4, 'f', '2025-12-01'),
(5, 'prahalad', 'singandi', 4, 'm', '2025-12-01'),
(13, 'test', 't', 5, 'f', '2026-03-02'),
(12, 'test', 'test', 5, 'm', '2026-03-01');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `date` date NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `healthcenter`
--

DROP TABLE IF EXISTS `healthcenter`;
CREATE TABLE IF NOT EXISTS `healthcenter` (
  `hid` int NOT NULL AUTO_INCREMENT,
  `hname` varchar(30) NOT NULL,
  `hregion` varchar(20) NOT NULL,
  `himage` varchar(400) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`hid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `healthcenter`
--

INSERT INTO `healthcenter` (`hid`, `hname`, `hregion`, `himage`, `description`, `status`) VALUES
(1, 'Victoria Hospital', 'Bengaluru', '0c16e69e8fd4edd1a2536052adf54b63_1199050a8bd4ea18196f.jpeg', 'Victoria Hospital is a government-run hospital affiliated with Bangalore Medical College and Research Institute. It is one of the largest hospitals in Karnataka, located in the heart of Bengaluru.', 1),
(2, 'Bangalore Medical College', 'Bengaluru', 'cityhospital.jpeg', 'Bangalore Medical College and Research Institute (BMCRI) is a premier medical institution in Karnataka. It provides high-quality medical education and tertiary healthcare services to the people of Karnataka.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `healthworker`
--

DROP TABLE IF EXISTS `healthworker`;
CREATE TABLE IF NOT EXISTS `healthworker` (
  `hwid` int NOT NULL AUTO_INCREMENT,
  `hname` varchar(50) NOT NULL,
  `h_email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `hid` int NOT NULL,
  PRIMARY KEY (`hwid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `healthworker`
--

INSERT INTO `healthworker` (`hwid`, `hname`, `h_email`, `password`, `hid`) VALUES
(1, 'Nurse Sarah', 'nurse@gmail.com', '1234', 1),
(2, 'Dr. Arjun', 'doctor@gmail.com', '1234', 2),
(3, 'test', 'test@gmail.com', 'test123', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) NOT NULL COMMENT 'Can be parent username or health worker email',
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_email`, `message`, `is_read`, `date_created`) VALUES
(1, 'test1', 'Update: test has successfully received the Hepatitis A vaccine. View your reports for details.', 1, '2026-03-25 16:20:03'),
(2, 'test1', 'Your vaccination booking for test on 2026-03-25 at Victoria Hospital is Confirmed.', 1, '2026-03-25 16:27:28'),
(3, 'nurse@gmail.com', 'New Booking: test is scheduled for Hepatitis A on 2026-03-25.', 0, '2026-03-25 16:27:28'),
(4, 'test@gmail.com', 'New Booking: test is scheduled for Hepatitis A on 2026-03-25.', 1, '2026-03-25 16:27:28'),
(5, 'test1', 'Update: test has successfully received the Hepatitis A vaccine. View your reports for details.', 1, '2026-03-25 16:30:06');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

DROP TABLE IF EXISTS `registration`;
CREATE TABLE IF NOT EXISTS `registration` (
  `pid` int NOT NULL AUTO_INCREMENT,
  `pname` varchar(30) NOT NULL,
  `phone` char(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`pid`, `pname`, `phone`, `username`, `password`) VALUES
(1, 'yaseen', '9898989898', 'yaseen@gmail.com', '1234'),
(2, 'abhsihek', '8989898989', 'abhishek@gmail.com', '1234'),
(3, 'abhidev', '9898989898', 'abhidev@gmail.com', '1234'),
(4, 'test123', '6362389287', 'test123', 'test123'),
(5, 'test1', '9492992029', 'test1', 'test1');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

DROP TABLE IF EXISTS `schedule`;
CREATE TABLE IF NOT EXISTS `schedule` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `hname` varchar(30) NOT NULL,
  `vname` varchar(30) NOT NULL,
  `quantity` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`sid`, `date`, `hname`, `vname`, `quantity`, `status`) VALUES
(1, '2025-12-19', 'Victoria Hospital', 'Hepatitis A', 8, 1),
(2, '2026-03-24', 'Bangalore Medical College', 'Hepatitis A', 19, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vaccine`
--

DROP TABLE IF EXISTS `vaccine`;
CREATE TABLE IF NOT EXISTS `vaccine` (
  `vid` int NOT NULL AUTO_INCREMENT,
  `vname` varchar(30) NOT NULL,
  `vage` int NOT NULL,
  `vimage` varchar(400) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vaccine`
--

INSERT INTO `vaccine` (`vid`, `vname`, `vage`, `vimage`, `description`, `status`) VALUES
(1, 'Hepatitis A', 1, 'hepb.jpg', 'Hepatitis A is a vaccine-preventable liver infection caused by the hepatitis A virus (HAV). HAV is found in the stool and blood of people who are infected. Hepatitis A is very contagious.', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
